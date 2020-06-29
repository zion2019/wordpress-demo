<?php

namespace Equip\Module;

use Equip\Equip;
use Equip\Factory;
use Equip\Storage;

/**
 * Engine and process meta boxes
 *
 * TODO: how to add meta box above the content? See ACF
 *
 * @author  8guild
 * @package Equip
 */
class MetaboxModule {

	/**
	 * Constructor
	 */
	public function __construct() {
		add_action( 'add_meta_boxes', array( $this, 'add' ), 10, 2 );
		add_action( 'save_post', array( $this, 'save' ), 10, 2 );
		add_action( 'equip/metabox/saved', array( $this, 'flush' ), 10, 2 );
	}

	/**
	 * Add meta box to the storage
	 *
	 * $slug will be used to save and retrieve data from database
	 * as a second parameter of {@see get_post_meta()}.
	 *
	 * @param string               $slug   Meta box unique name
	 * @param \Equip\Layout\Layout $layout Meta box layout
	 * @param array                $args   Meta box arguments. See {@see add_meta_box()} for more info.
	 *
	 * @return bool
	 */
	public function store( $slug, $layout, $args = [] ) {
		$args = wp_parse_args( $args, $this->get_defaults( $slug ) );
		$data = [
			'slug'    => $slug,
			'layout'  => $layout,
			'args'    => $args,
			'module'  => Equip::METABOX,
			'pattern' => 'module.args[screen].slug', // pattern for storing elements
		];

		$screen = $args['screen'];

		if ( is_array( $screen ) ) {
			foreach ( $screen as $s ) {
				$data['args']['screen'] = $s;
				Storage::add( $data );
			}
		} else {
			Storage::add( $data );
		}

		return true;
	}

	/**
	 * Return all registered meta boxes for current post type
	 *
	 * @param null|string $post_type Post type
	 *
	 * @return array
	 */
	public function reveal( $post_type = null ) {
		if ( null === $post_type ) {
			$screen    = get_current_screen();
			$post_type = $screen->post_type;
		}

		$metaboxes = Storage::find( 'module.post_type', [
			'module'    => Equip::METABOX,
			'post_type' => $post_type,
		] );

		if ( empty( $metaboxes ) ) {
			return [];
		}

		/**
		 * @var \Equip\Misc\StorageElement $element
		 */
		$result = [];
		foreach ( (array) $metaboxes as $element ) {
			$result[ $element->getSlug() ] = $element;
		}

		return $result;
	}

	/**
	 * Add meta box
	 *
	 * @see  add_meta_box
	 * @link https://codex.wordpress.org/Function_Reference/add_meta_box
	 *
	 * @param string   $post_type Post type
	 * @param \WP_Post $post      Post object
	 *
	 * @return bool
	 */
	public function add( $post_type, $post ) {
		$metaboxes = $this->reveal( $post_type );

		/**
		 * @var \Equip\Misc\StorageElement $item
		 */

		// Add all meta boxes from storage at once
		foreach ( (array) $metaboxes as $slug => $item ) {
			/**
			 * Filter current meta box arguments.
			 *
			 * Fires only for unique meta box.
			 *
			 * @param array $args Meta box arguments
			 */
			$args = apply_filters( "equip/metabox/{$slug}/args", $item->getArgs() );

			/**
			 * Filter meta box arguments.
			 *
			 * Fires for each meta box.
			 *
			 * @param array  $args Meta box arguments
			 * @param string $slug Meta box unique key
			 */
			$args = apply_filters( 'equip/metabox/args', $args, $slug );

			add_meta_box(
				$args['id'],
				$args['title'],
				array( $this, 'render' ),
				$args['screen'],
				$args['context'],
				$args['priority'],
				array( 'slug' => $slug, 'layout' => $item->getLayout(), 'args' => $args )
			);
		}
		unset( $slug, $item );

		return true;
	}

	/**
	 * Engine meta box
	 *
	 * @param \WP_Post $post    Post object
	 * @param array    $metabox Is an array with meta box slug, contents and args.
	 *
	 * @uses \Equip\Factory
	 */
	public function render( $post, $metabox ) {
		// Get meta box data
		$slug   = $metabox['args']['slug'];
		$args   = $metabox['args']['args'];
		$layout = $metabox['args']['layout'];

		if ( empty( $slug ) ) {
			return;
		}

		/*
		 * Get values
		 *
		 * Note:
		 * you can control where Equip will get the values for the meta box.
		 * $args[values] is the third param of equip_add_meta_box().
		 *
		 * By default this is a post meta
		 */
		if ( false === $args['values'] ) {
			$values = [];
		} elseif ( is_callable( $args['values'] ) ) {
			// note: first param is an empty array to keep consistency with filter
			$values = call_user_func_array( $args['values'], [ [], $post, $slug, $layout, $args ] );
		} else {
			$values = get_post_meta( $post->ID, $slug, true );
		}

		/**
		 * This filter allows to modify the values for the meta box
		 *
		 * Note: Equip works with meta boxes as with arrays, the format
		 * is slug[key1, key2, etc], so the $values should be an array, too.
		 *
		 * @param mixed                       $values Meta box values
		 * @param \WP_Post                    $post   Post object
		 * @param string                      $slug   Meta box name
		 * @param \Equip\Layout\MetaboxLayout $layout Meta box layout
		 * @param array                       $args   Meta box args, third parameter of {@see equip_add_meta_box()}
		 */
		$values = apply_filters_ref_array( 'equip/metabox/values', [ $values, $post, $slug, $layout, $args ] );

		$engine = Factory::engine( $layout );
		$engine->render( $slug, $layout, $values );
	}

	/**
	 * Save post metadata when the post is saved.
	 *
	 * @param int      $post_id The ID of the post.
	 * @param \WP_Post $post    Post object
	 *
	 * @return void
	 */
	public function save( $post_id, $post ) {
		// No auto-drafts, please
		if ( isset( $post->post_status ) && 'auto-draft' === $post->post_status ) {
			return;
		}

		$post_type = $post->post_type;

		/*
		 * How to detect that current post has meta boxes, added through Equip?
		 *
		 * 1. Get list of all meta boxes from Storage
		 * 2. Check, if $_POST has any of them
		 * 3. ...
		 * 4. PROFIT!
		 */

		$metaboxes = $this->reveal( $post_type );
		if ( empty( $metaboxes ) ) {
			return;
		}

		$all     = array_keys( $metaboxes );
		$current = array_intersect_key( $_POST, array_flip( $all ) );
		unset( $all );

		if ( empty( $current ) ) {
			return;
		}

		// Check the permissions
		$capability = ( 'page' === $post_type ) ? 'edit_pages' : 'edit_posts';
		if ( false === current_user_can( $capability ) ) {
			return;
		}
		unset( $capability );

		// Check the auto-save and revisions
		if ( wp_is_post_autosave( $post_id ) || wp_is_post_revision( $post_id ) ) {
			return;
		}

		foreach ( $current as $slug => $values ) {
			/**
			 * Fires right before the meta box would be saved
			 *
			 * @param string   $slug    Meta box unique name
			 * @param int      $post_id Post ID
			 * @param \WP_Post $post    Post object
			 */
			do_action( 'equip/metabox/save', $slug, $post_id, $post );

			// Check the nonce. Nonce is individual for each meta box.
			$action = equip_get_nonce_action( Equip::METABOX, $slug );
			$nonce  = equip_get_nonce_name( Equip::METABOX, $slug );

			if ( ! array_key_exists( $nonce, $_POST ) || ! wp_verify_nonce( $_POST[ $nonce ], $action ) ) {
				continue;
			}

			/** @var \Equip\Misc\StorageElement $element */
			$element = $metaboxes[ $slug ];
			$args    = $element->getArgs();

			if ( false === $args['save'] ) {
				// do nothing
			} elseif ( is_callable( $args['save'] ) ) {
				call_user_func_array( $args['save'], [ $values, $slug, $post_id, $post ] );
			} else {
				// save as the post meta
				$sanitizer = Factory::service( Equip::SANITIZER );
				$values    = $sanitizer->bulk_sanitize( $values, $element->getLayout(), $slug );

				update_post_meta( $post_id, $slug, $values );
			}

			unset( $action, $nonce, $element, $args );

			/**
			 * Fires when meta box was already saved
			 *
			 * @param string   $slug    Meta box unique name
			 * @param int      $post_id Post ID
			 * @param \WP_Post $post    Post object
			 */
			do_action( 'equip/metabox/saved', $slug, $post_id, $post, $values );
		}
		unset( $slug, $values );
	}

	/**
	 * Returns the values of meta box.
	 *
	 * If $field is specified will return the field's value.
	 *
	 * @param int         $post_id Post ID
	 * @param string      $slug    Meta box unique name
	 * @param null|string $field   Key of the field
	 * @param mixed       $default Default value
	 *
	 * @return mixed Array with key-values, mixed data if field is specified
	 *               and the value of $default field if nothing found.
	 */
	public function get( $post_id, $slug, $field = null, $default = array() ) {
		$cache_key   = equip_cache_key( $slug, $post_id );
		$cache_group = Equip::METABOX;

		// Cached value should always be an array
		$values = wp_cache_get( $cache_key, $cache_group );
		if ( false === $values ) {
			$values = get_post_meta( $post_id, $slug, true );
			if ( empty( $values ) ) {
				// possible cases: meta box not saved yet
				// or mistake in $post_id or $slug
				return $default;
			}

			// cache for 1 day
			wp_cache_set( $cache_key, $values, $cache_group, 86400 );
		}

		if ( ! is_array( $values ) ) {
			// return AS IS for non-array values
			$result = $values;
		} elseif ( null === $field ) {
			// return whole array if $field not specified
			$result = $values;
		} elseif ( array_key_exists( $field, $values ) ) {
			// if specified $field present
			$result = $values[ $field ];
		} else {
			// nothing matched, return default value
			$result = $default;
		}

		return $result;
	}

	/**
	 * Flush the cached meta box value when new instance is saved
	 *
	 * Note! This methods removes only current cached meta box,
	 * by its $slug and $post_id
	 *
	 * @param string $slug    Meta box unique name
	 * @param int    $post_id Post ID
	 */
	public function flush( $slug, $post_id ) {
		$cache_key   = equip_cache_key( $slug, $post_id );
		$cache_group = Equip::METABOX;

		wp_cache_delete( $cache_key, $cache_group );
	}

	/**
	 * Returns the meta box default args
	 *
	 * @param string $slug Meta box unique name
	 *
	 * @return array
	 */
	private function get_defaults( $slug ) {
		$slug     = ltrim( $slug, '_' );
		$defaults = array(
			'id'       => str_replace( '_', '-', $slug ),
			'title'    => ucfirst( preg_replace( '/[_\\W]+/', ' ', $slug ) ),
			'screen'   => 'post',
			'context'  => 'normal',
			'priority' => 'default',
			'save'     => null,
			'values'   => null,
		);

		/**
		 * Filter the default args of current meta box.
		 *
		 * Fires only for unique meta box. This filter expects the array with keys
		 * described in {@see add_meta_box} and some extra equip keys.
		 *
		 * @link https://codex.wordpress.org/Function_Reference/add_meta_box
		 *
		 * @param array $defaults Meta box default arguments
		 */
		$defaults = apply_filters( "equip/metabox/{$slug}/defaults", $defaults );

		/**
		 * Filter the meta box default args.
		 *
		 * Fires for each meta box. This filter expects the array with keys
		 * described in {@see add_meta_box} and some extra equip keys.
		 *
		 * @link https://codex.wordpress.org/Function_Reference/add_meta_box
		 *
		 * @param array  $defaults Meta box default arguments
		 * @param string $slug     Meta box unique key
		 */
		$defaults = apply_filters( 'equip/metabox/defaults', $defaults, $slug );

		return $defaults;
	}
}