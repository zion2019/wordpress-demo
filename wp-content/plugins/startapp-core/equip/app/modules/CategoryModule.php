<?php

use Equip\Storage;
use Equip\Factory;

/**
 * This module allows to add a fields to a Categories screen
 *
 * @author  8guild
 * @package Equip\Module
 */
class CategoryModule {

	private $module = 'category';

	/**
	 * Constructor
	 */
	public function __construct() {
		add_action( 'init', [ $this, 'add' ] );

		// @see /wp-admin/edit-tag-form.php :: 229
		add_action( 'category_edit_form_fields', [ $this, 'render' ], 10, 2 );

		// @see /wp-admin/edit-tag-form.php :: 261
		//add_action( 'category_edit_form', [ $this, 'render' ], 10, 2 );

		// @see /wp-admin/edit-tags.php :: 495
		add_action( 'category_add_form_fields', [ $this, 'render' ] );

		// @see /wp-includes/taxonomy.php :: 3019 :: wp_update_term()
		// @see /wp-includes/taxonomy.php :: 2445 :: wp_insert_term()
		add_action( 'edited_category', [ $this, 'save' ], 10, 2 );
		add_action( 'created_category', [$this, 'save'], 10, 2 );
	}

	public function store( $slug, $layout, $args ) {
		$args = array_merge( $args, [ 'type' => 'array', 'single' => false ] );
		$args = wp_parse_args( $args, [
			'object_type'       => 'term',
			'description'       => '',
			'sanitize_callback' => null,
			'auth_callback'     => null,
			'show_in_rest'      => false,
		] );

		$data = [
			'slug'    => $slug,
			'layout'  => $layout,
			'args'    => $args,
			'module'  => $this->module,
			'pattern' => 'module.args[object_type].slug',
		];

		Storage::add( $data );
	}

	/**
	 * Reveal elements from the Storage
	 *
	 * @return array
	 */
	public function reveal() {
		$metadata = Storage::find( 'module', [ 'module' => $this->module ] );
		if ( empty( $metadata ) ) {
			return [];
		}

		$result = [];
		/** @var \Equip\Misc\StorageElement $item */
		foreach ( $metadata as $item ) {
			$result[ $item->getSlug() ] = $item;
		}

		return $result;
	}

	/**
	 * Register the term meta
	 */
	public function add() {
		$metadata = $this->reveal();
		if ( empty( $metadata ) ) {
			return;
		}

		/** @var Equip\Misc\StorageElement $item */
		foreach ( $metadata as $item ) {
			$args = $item->getArgs();
			register_meta( $args['object_type'], $item->getSlug(), $args );
		}
	}

	public function render( $term, $taxonomy = null ) {
		// this is required, because the quantity of attributes
		// passed to the callback differs on "add" and "edit" screens
		$screen = 'edit';
		if ( ! $term instanceof WP_Term ) {
			$taxonomy = $term;
			$term     = null;
			$screen   = 'add';
		}

		// TODO: may be detect is_new_term or is_edit_term
		// TODO: may be add in $args some keys to control screens where to add a fields

		// object_type.taxonomy.slug
		// term.category.slug

		$metadata = $this->reveal();
		if ( empty( $metadata ) ) {
			return;
		}

		/** @var Equip\Misc\StorageElement $item */
		foreach( $metadata as $item ) {
			$layout = $item->getLayout();
			$slug   = $item->getSlug();

			// set the current screen
			// required, as this will affects the output
			$layout->set_setting( 'screen', $screen );

			if ( null !== $term ) {
				$values = get_term_meta( $term->term_id, $slug, true );
			} else {
				$values = [];
			}

			$engine = Factory::engine( $layout );
			$engine->render( $slug, $layout, $values );
		}
	}

	/**
	 * Save the terms meta data
	 *
	 * @param int $term_id Term ID
	 * @param int $tt_id   Term Taxonomy ID
	 */
	public function save( $term_id, $tt_id ) {
		$metadata = $this->reveal();
		if ( empty( $metadata ) ) {
			return;
		}

		$slugs   = array_keys( $metadata );
		$current = array_intersect_key( $_POST, array_flip( $slugs ) );
		if ( empty( $current ) ) {
			return;
		}

		/** @var \Equip\Service\Sanitizer $sanitizer */
		$sanitizer = Factory::service( 'sanitizer' );

		foreach( $current as $slug => $values ) {
			/** @var \Equip\Misc\StorageElement $element */
			$element = $metadata[ $slug ];
			$layout  = $element->getLayout();
			$values  = $sanitizer->bulk_sanitize( $values, $layout, $slug );

			update_term_meta( $term_id, $slug, $values );
		}
	}
}