<?php

namespace Equip\Module;

use Equip\Equip;
use Equip\Factory;
use Equip\Storage;

/**
 * Class MenuModule
 *
 * Allow users add fields to menu
 *
 * @author  8guild
 * @package Equip\Module
 */
class MenuModule {

	/**
	 * Constructor
	 */
	public function __construct() {
		add_filter( 'wp_edit_nav_menu_walker', [ $this, 'walker' ], 10, 2 );
		add_filter( 'wp_setup_nav_menu_item', [ $this, 'add' ] );
		add_action( 'equip/menu/render', [ $this, 'render' ], 10, 2 );
		add_action( 'wp_update_nav_menu_item', [ $this, 'save' ], 10, 3 );

		// modify the field name attribute
		add_filter( 'equip/field/name', [ $this, 'field_name' ], 10, 2 );
	}

	/**
	 * Add menus to storage
	 *
	 * @param       $slug
	 * @param       $layout
	 * @param array $args
	 */
	public function store( $slug, $layout, $args = [] ) {
		Storage::add( [
			'slug'    => $slug,
			'layout'  => $layout,
			'args'    => $args,
			'module'  => Equip::MENU,
			'pattern' => 'module.slug',
		] );
	}

	/**
	 * Get menus from storage
	 *
	 * @return mixed
	 */
	public function reveal() {
		$menus = Storage::find( 'module', [ 'module' => Equip::MENU ] );
		if ( empty( $menus ) ) {
			return [];
		}

		$result = [];
		/** @var \Equip\Misc\StorageElement $element */
		foreach ( $menus as $element ) {
			$result[ $element->getSlug() ] = $element;
		}

		return $result;
	}

	/**
	 * Inject a custom field into menu item object
	 *
	 * Without escaping, as is. Values will be escaped later.
	 * This is only place where you can add custom fields into the
	 * menu object
	 *
	 * Due to this filter your custom field and value will be present
	 * in your custom menu walker.
	 *
	 * @param object $menu_item The menu item object.
	 *
	 * @return object $menu_item object with custom field(s)
	 */
	public function add( $menu_item ) {
		if ( 'nav_menu_item' !== $menu_item->post_type ) {
			return $menu_item;
		}

		$menus = $this->reveal();
		if ( empty( $menus ) ) {
			return $menu_item;
		}

		/** @var \Equip\Misc\StorageElement $element */
		foreach ( $menus as $slug => $element ) {
			// if property already added
			if ( property_exists( $menu_item, $slug ) ) {
				continue;
			}

			$args = $element->getArgs();
			// may be exclude some elements
			if ( $this->exclude( $menu_item, $args ) ) {
				continue;
			}

			$slug  = trim( $slug, '_-' ); // equip_sanitize_slug
			$value = get_post_meta( $menu_item->ID, "_menu_item_{$slug}", true );

			$menu_item->$slug = $value;
		}

		return $menu_item;
	}

	public function render( $item_id, $item ) {
		$item_id = (int) $item_id;
		$menus   = $this->reveal();
		if ( empty( $menus ) ) {
			return;
		}

		// temporary save the menu item
		// @see Field::get_name()
		wp_cache_set( '_menu_item_id', $item_id, 'equip' );

		/** @var \Equip\Misc\StorageElement $element */
		// note, we may have more than one custom menu field
		foreach ( $menus as $slug => $element ) {
			$slug    = trim( $slug, '_-' ); // TODO: equip_sanitize_slug()
			$content = $element->getLayout();
			$args    = $element->getArgs();

			if ( $this->exclude( $item, $args ) ) {
				continue;
			}

			// get values from DB
			$key    = '_menu_item_' . trim( $slug, '_-' );
			$values = get_post_meta( $item_id, $key, true );

			// render
			$engine = Factory::engine( $content );
			$engine->render( $slug, $content, $values );
		}

		// remove cached menu item
		wp_cache_delete( '_menu_item_id', 'equip' );
	}

	/**
	 * Update custom menu item field(s)
	 *
	 * Fires after a navigation menu item has been updated
	 *
	 * Here is a place where all magic happens!
	 *
	 * @see wp_update_nav_menu_item
	 *
	 * @param int   $menu_id         ID of the updated menu
	 * @param int   $menu_item_db_id ID of the updated menu item
	 * @param array $args            An array of arguments used to update a menu item
	 *
	 * @return void
	 */
	public function save( $menu_id, $menu_item_db_id, $args ) {
		$menus = $this->reveal();
		if ( empty( $menus ) ) {
			return;
		}

		$slugs   = array_keys( $menus );
		$current = array_intersect_key( $_POST, array_flip( $slugs ) );
		if ( empty( $current ) ) {
			return;
		}

		foreach ( $current as $slug => $data ) {
			/** @var \Equip\Misc\StorageElement $element */
			$element = $menus[ $slug ];
			$args    = $element->getArgs();
			$item    = get_post( $menu_item_db_id );

			// do not save excluded posts
			if ( $this->exclude( $item, $args ) ) {
				continue;
			}

			// WordPress call this function for every menu item
			if ( ! array_key_exists( $menu_item_db_id, $data ) ) {
				return;
			}

			/** @var \Equip\Service\Sanitizer $sanitizer */
			$sanitizer = Factory::service( Equip::SANITIZER );
			$slug      = trim( $slug, '_-' ); // TODO: equip_sanitize_slug
			$layout    = $element->getLayout();

			$values = $data[ $menu_item_db_id ];
			$values = $sanitizer->bulk_sanitize( $values, $layout, $slug );
			update_post_meta( $menu_item_db_id, "_menu_item_{$slug}", $values );

			unset( $layout, $values );
		}
		unset( $slug, $data );

		// TODO: after menu item saved action
	}

	public function walker( $walker, $menu_id ) {
		return '\\Equip\\Misc\\EquipNavMenuEdit';
	}

	/**
	 * Modify the field name for menu
	 *
	 * Menu required special name structure: slug[menu_item_id][key]
	 *
	 * @param string             $name  Name. Default is slug[key]
	 * @param \Equip\Field\Field $field Equip field object
	 *
	 * @return string
	 */
	public function field_name( $name, $field ) {
		// We tried our best, you know the rest
		// Workaround for menus. I do not come up with anything better
		if ( 'menu' === $field->get_context() ) {
			$menu_item_id = (int) wp_cache_get( '_menu_item_id', 'equip' );

			return sprintf( '%1$s[%2$d][%3$s]', $field->get_slug(), $menu_item_id, $field->get_key() );
		}

		return $name;
	}

	/**
	 * May be exclude elements, based on $args[exclude]
	 *
	 * @param \WP_Post|object $item Menu item object
	 * @param array           $args Element args
	 *
	 * @return bool
	 */
	private function exclude( $item, $args ) {
		if ( ! array_key_exists( 'exclude', $args ) ) {
			return false;
		}

		$exclude = $args['exclude'];

		if ( in_array( $exclude, [ 'child', 'children' ] )
		     && isset( $item->menu_item_parent )
		     && 0 !== (int) $item->menu_item_parent
		) {
			return true;
		}

		if ( in_array( $exclude, [ 'parent', 'parents' ] )
		     && isset( $item->menu_item_parent )
		     && 0 === (int) $item->menu_item_parent
		) {
			return true;
		}

		return false;
	}
}