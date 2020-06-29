<?php
/**
 * Menu customization
 *
 * @author 8guild
 */

// Exit if accessed directly
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

// Exit if Equip not installed
if ( ! defined( 'EQUIP_VERSION' ) ) {
	return;
}

if ( defined( 'DOING_AJAX' ) && DOING_AJAX ) {
	return;
}

/**
 * Add custom fields into the menu items
 *
 * These fields allowed for all levels in menu
 */
function startapp_menu_extra_fields() {
	try {
		$layout = equip_create_menu_layout();
		$layout
			->add_field( 'icon', 'icon', array(
				'label'       => esc_html__( 'Icon', 'startapp' ),
				'description' => esc_html__( 'Choose the icon from the Material Icons pack. Please note: Icons do not work with Scroller Menu.', 'startapp' ),
				'source'      => 'material',
				'settings'    => array( 'iconsPerPage' => 66 ),
			) )
			->add_field( 'is_anchor', 'select', array(
				'label'       => esc_html__( 'Link to Anchor', 'startapp' ),
				'description' => esc_html__( 'Please note: No need to enable this option for Scroller Menu, it works as anchor navigation out of the box.', 'startapp' ),
				'default'     => 'disable',
				'options'     => array(
					'enable'  => esc_html__( 'Enable', 'startapp' ),
					'disable' => esc_html__( 'Disable', 'startapp' ),
				)
			) );

		equip_add_menu( 'startapp_additions', $layout );

	} catch ( Exception $e ) {
		trigger_error( $e->getMessage() );
	}
}

add_action( 'equip/register', 'startapp_menu_extra_fields' );

/**
 * Add custom fields into the menu items
 *
 * These fields allowed only for top-level menu items
 */
function startapp_menu_top_level_fields() {
	try {
		$layout = equip_create_menu_layout();
		$layout->add_field( 'mega_menu', 'select', array(
			'label'       => esc_html__( 'Choose Mega Menu', 'startapp' ),
			'description' => esc_html__( 'Please note: No need to enable this option for Scroller Menu, it works as anchor navigation out of the box.', 'startapp' ),
			'default'     => 'none',
			'options'     => call_user_func( function() {
				global $wp_registered_sidebars;

				$_sidebars    = $wp_registered_sidebars;
				$_sidebar_ids = array_filter( array_keys( $_sidebars ), function( $sidebar ) {
					return ( false !== strpos( $sidebar, 'mega-menu-sidebar' ) );
				} );

				$sidebars         = array();
				$sidebars['none'] = esc_html__( 'Choose Mega Menu', 'startapp' );
				foreach ( $_sidebar_ids as $sidebar ) {
					$sidebars[ $sidebar ] = $_sidebars[ $sidebar ]['name'];
				}
				unset( $_sidebars, $_sidebar_ids, $sidebar );

				return $sidebars;
			} ),
		) );

		equip_add_menu( 'startapp_top', $layout, array(
			'exclude' => 'children',
		) );

	} catch ( Exception $e ) {
		trigger_error( $e->getMessage() );
	}
}

add_action( 'equip/register', 'startapp_menu_top_level_fields' );
