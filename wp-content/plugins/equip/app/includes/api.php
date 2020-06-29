<?php
/**
 * Equip API
 *
 * @author  8guild
 * @package Equip
 */

use Equip\Equip;

/**
 * Creates the custom layout
 *
 * @param string $type Layout type
 * @param array  $args Layout arguments for constructor
 *
 * @return \Equip\Layout\Layout
 */
function equip_create_layout( $type, $args = [] ) {
	return Equip::create_layout( $type, $args );
}

/**
 * Creates the metabox layout
 *
 * @return \Equip\Layout\MetaboxLayout
 */
function equip_create_meta_box_layout() {
	return Equip::create_layout( Equip::METABOX );
}

/**
 * Creates the Theme Options page layout
 *
 * @return \Equip\Layout\OptionsLayout
 */
function equip_create_options_layout() {
	return Equip::create_layout( Equip::OPTIONS );
}

/**
 * Creates the User Profile page layout
 *
 * @return \Equip\Layout\UserLayout
 */
function equip_create_user_layout() {
	return Equip::create_layout( Equip::USER );
}

/**
 * Creates the Menu layout
 *
 * @return \Equip\Layout\MenuLayout
 */
function equip_create_menu_layout() {
	return Equip::create_layout( Equip::MENU );
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
function equip_add( $module, $slug, $layout, $args = [] ) {
	return Equip::add( $module, $slug, $layout, $args );
}

/**
 * Ask Equip to create a meta box
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
function equip_add_meta_box( $slug, $layout, $args = [] ) {
	return Equip::add_meta_box( $slug, $layout, $args );
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
function equip_add_menu( $slug, $contents, $args = [] ) {
	return Equip::add_menu( $slug, $contents, $args );
}

/**
 * Add custom fields to user / profile pages
 *
 * @param string               $slug     Fields name. This name will be used for storing
 *                                       fields' values in the DB and as a second parameter
 *                                       for {@see get_user_meta()}.
 * @param \Equip\Layout\Layout $contents List of sections and(or) fields
 * @param array                $args     Arguments
 *
 * @return bool
 */
function equip_add_user( $slug, $contents, $args = [] ) {
	return Equip::add_user( $slug, $contents, $args );
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
function equip_add_options_page( $slug, $contents, $args = [] ) {
	return Equip::add_options_page( $slug, $contents, $args );
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
function equip_get_meta( $post_id, $slug, $field = null, $default = [] ) {
	return Equip::get_meta( $post_id, $slug, $field, $default );
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
function equip_get_option( $slug, $field = 'all', $default = [] ) {
	return Equip::get_option( $slug, $field, $default );
} 
