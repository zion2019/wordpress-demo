<?php
/**
 * User additions
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

/**
 * Add a custom user fields
 */
function startapp_user_additions() {
	try {
		$layout = equip_create_user_layout();

		$layout->add_field( 'avatar', 'media', array( 'label' => esc_html__( 'Avatar', 'startapp' ) ) );
		$layout->add_field( 'socials', 'socials', array( 'label' => esc_html__( 'Socials', 'startapp' ) ) );

		equip_add_user( 'startapp_additions', $layout );
	} catch ( Exception $e ) {
		trigger_error( $e->getMessage() );
	}
}

add_action( 'equip/register', 'startapp_user_additions' );