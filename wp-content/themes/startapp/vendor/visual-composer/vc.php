<?php
/**
 * Visual Composer actions & filters
 *
 * @author 8guild
 */

if ( ! defined( 'ABSPATH' ) ) {
	die;
}

if ( ! defined( 'WPB_VC_VERSION' ) ) {
	return;
}

/**
 * Setup Visual Composer for theme.
 *
 * @hooked vc_before_init 10
 */
function startapp_vc_before_init() {
	vc_disable_frontend();
	vc_set_as_theme();

	/**
	 * Filter the default post types for Visual Composer
	 *
	 * This means VC should be enabled for this post types by default
	 *
	 * @param array $post_types Post types list
	 */
	$post_types = apply_filters( 'startapp_vc_default_editor_post_types', array(
		'page',
		'post',
	) );

	vc_set_default_editor_post_types( $post_types );
}

add_action( 'vc_before_init', 'startapp_vc_before_init' );

/**
 * Register all the styles for VC Iconpicker to be enqueue later
 *
 * @see vc_base_register_front_css
 * @see vc_base_register_admin_css
 */
function startapp_vc_iconpicker_register_css() {
	// remove vc's styles, we do not need them in our theme
	wp_deregister_style( 'font-awesome' );
	wp_deregister_style( 'vc_openiconic' );
	wp_deregister_style( 'vc_typicons' );
	wp_deregister_style( 'vc_entypo' );
	wp_deregister_style( 'vc_linecons' );
	wp_deregister_style( 'vc_monosocialiconsfont' );

	wp_register_style( 'material-icons', STARTAPP_TEMPLATE_URI . '/stylesheets/vendor/material-icons.min.css', array(), null );
	wp_register_style( 'font-awesome', STARTAPP_TEMPLATE_URI . '/stylesheets/vendor/font-awesome.min.css', array(), null );
}

add_action( 'vc_base_register_front_css', 'startapp_vc_iconpicker_register_css' );
add_action( 'vc_base_register_admin_css', 'startapp_vc_iconpicker_register_css' );

/**
 * Used to enqueue all needed files when VC editor is rendering
 *
 * @see vc_backend_editor_enqueue_js_css
 * @see vc_frontend_editor_enqueue_js_css
 */
function startapp_vc_iconpicker_enqueue_css() {
	wp_enqueue_style( 'material-icons' );
	wp_enqueue_style( 'font-awesome' );
}

add_action( 'vc_backend_editor_enqueue_js_css', 'startapp_vc_iconpicker_enqueue_css' );
add_action( 'vc_frontend_editor_enqueue_js_css', 'startapp_vc_iconpicker_enqueue_css' );

/**
 * Enqueue the CSS for the Front-end site,
 * when one of the fonts is selected in the shortcode.
 *
 * @see vc_icon_element_fonts_enqueue
 *
 * @param string $font Library name
 */
function startapp_vc_iconpicker_front_css( $font ) {
	switch ( $font ) {
		case 'material':
			wp_enqueue_style( 'material-icons', STARTAPP_TEMPLATE_URI . '/stylesheets/vendor/material-icons.min.css', array(), null, 'screen' );
			break;

		case 'fontawesome':
			wp_enqueue_style( 'font-awesome', STARTAPP_TEMPLATE_URI . '/stylesheets/vendor/font-awesome.min.css', array(), null, 'screen' );
			break;
	}
}

add_action( 'vc_enqueue_font_icon_element', 'startapp_vc_iconpicker_front_css' );

/**
 * Returns Material Icons for VC icon picker
 *
 * @param array $icons
 *
 * @return array Icons for icon picker, can be categorized, or not.
 */
function startapp_vc_iconpicker_material( $icons ) {
	$icons = array();
	foreach ( (array) startapp_get_material_icons() as $icon ) {
		list( $pack, $name ) = explode( ' ', $icon );
		$icons[] = array( $icon => $name );
	}

	return $icons;
}

add_filter( 'vc_iconpicker-type-material', 'startapp_vc_iconpicker_material' );

/**
 * Returns the icon from FontAwesome pack for VC's icon picker
 *
 * @see startapp_get_fa_icons()
 *
 * @param array $icons
 *
 * @return array
 */
function startapp_vc_iconpicker_fontawesome( $icons ) {
	$icons = array();
	foreach ( (array) startapp_get_fa_icons() as $icon ) {
		list( $fa, $name ) = explode( ' ', $icon );
		$icons[] = array( $icon => $name );
	}

	return $icons;
}

add_filter( 'vc_iconpicker-type-fontawesome', 'startapp_vc_iconpicker_fontawesome', 99 );

/**
 * Remove VC Welcome Page
 */
remove_action( 'vc_activation_hook', 'vc_page_welcome_set_redirect' );
remove_action( 'admin_init', 'vc_page_welcome_redirect' );