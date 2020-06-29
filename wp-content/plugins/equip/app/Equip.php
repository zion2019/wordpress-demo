<?php

namespace Equip;

/**
 * Create and save objects for multiple usage to save the performance
 *
 * @author  8guild
 * @package Equip
 */
class Equip {

	/**#@+
	 * Modules, Engines, Layouts
	 */
	const OPTIONS = 'options';
	const METABOX = 'metabox';
	const USER = 'user';
	const MENU = 'menu';
	const FIELD = 'field';
	const ROW = 'row';
	const COLUMN = 'column';
	/**#@-*/

	/**#@+
	 * Services
	 */
	const STORAGE = 'storage';
	const SANITIZER = 'sanitizer';
	const ESCAPER = 'escaper';
	const ENQUEUE = 'enqueue';
	/**#@-*/

	/**
	 * Create the Layout
	 *
	 * @param string $type
	 * @param array  $args
	 *
	 * @return \Equip\Layout\Layout
	 */
	public static function create_layout( $type, $args = [] ) {
		return Factory::layout( $type, $args );
	}

	/**
	 * Add a custom element
	 *
	 * @param string               $module Module
	 * @param string               $slug   Unique name
	 * @param \Equip\Layout\Layout $layout Layout
	 * @param array                $args   Arguments
	 *
	 * @return bool
	 */
	public static function add( $module, $slug, $layout, $args = [] ) {
		$module = Factory::module( $module );
		$module->store( $slug, $layout, $args );

		return true;
	}

	/**
	 * @param       $slug
	 * @param       $layout
	 * @param array $args
	 *
	 * @return bool
	 */
	public static function add_meta_box( $slug, $layout, $args = [] ) {
		// No need to add meta boxes during ajax calls?
		if ( defined( 'DOING_AJAX' ) && DOING_AJAX ) {
			return false;
		}

		// Also, do not execute if not in admin screens
		if ( ! is_admin() ) {
			return false;
		}

		/**
		 * @var \Equip\Module\MetaboxModule $metabox
		 */
		$metabox = Factory::module( self::METABOX );
		$metabox->store( $slug, $layout, $args );

		return true;
	}

	/**
	 * Add fields in the menu
	 *
	 * NOTE: experimental! Use on your own risk!
	 *
	 * @param       $slug
	 * @param       $contents
	 * @param array $args
	 *
	 * @return bool
	 */
	public static function add_menu( $slug, $contents, $args = [] ) {
		// AJAX is required
		// Also this module should be accessible on front-end
		// for setup menu items

		/**
		 * @var \Equip\Module\MenuModule $module
		 */
		$module = Factory::module( self::MENU );
		$module->store( $slug, $contents, $args );

		return true;
	}

	/**
	 * Add options page
	 *
	 * @param       $slug
	 * @param       $contents
	 * @param array $args
	 *
	 * @return bool
	 */
	public static function add_options_page( $slug, $contents, $args = [] ) {
		// AJAX is required here

		// do not execute if not in admin screens
		if ( ! is_admin() ) {
			return false;
		}

		/**
		 * @var \Equip\Module\OptionsModule $module
		 */
		$module = Factory::module( self::OPTIONS );
		$module->store( $slug, $contents, $args );

		return true;
	}

	/**
	 * Add custom user fields
	 *
	 * @param string               $slug
	 * @param \Equip\Layout\Layout $contents
	 * @param array                $args
	 *
	 * @return bool
	 */
	public static function add_user( $slug, $contents, $args = [] ) {
		// skip during ajax calls
		if ( defined( 'DOING_AJAX' ) && DOING_AJAX ) {
			return false;
		}

		// do not execute if not in admin screens
		if ( ! is_admin() ) {
			return false;
		}

		/**
		 * @var \Equip\Module\UserModule $module
		 */
		$module = Factory::module( self::USER );
		$module->store( $slug, $contents, $args );

		return true;
	}

	/**
	 * Returns the option
	 *
	 * May return array with all fields or the value
	 * of required field if $field is specified.
	 *
	 * @param string $slug    Option name
	 * @param string $field   Name of the field
	 * @param mixed  $default Default value
	 *
	 * @return mixed
	 */
	public static function get_option( $slug, $field = 'all', $default = [] ) {
		/**
		 * @var \Equip\Module\OptionsModule $module
		 */
		$module = Factory::module( self::OPTIONS );

		return $module->get( $slug, $field, $default );
	}

	/**
	 * Returns the values of meta box
	 *
	 * If $field is specified will return the field's value
	 * If nothing found will return $default
	 *
	 * @param int    $post_id Post ID
	 * @param string $slug    Meta box name
	 * @param null   $field   Key of the field
	 * @param array  $default Default value
	 *
	 * @return mixed
	 */
	public static function get_meta( $post_id, $slug, $field = null, $default = [] ) {
		/**
		 * @var \Equip\Module\MetaboxModule $module
		 */
		$module = Factory::module( self::METABOX );

		return $module->get( $post_id, $slug, $field, $default );
	}
}