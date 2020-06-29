<?php

namespace Equip\Engine;

use Equip\Equip;
use Equip\Factory;

/**
 * This engine is responsible to render the top-level "metabox" layout
 *
 * @author  8guild
 * @package Equip\Engine
 */
class MetaboxEngine extends Engine {

	public function before_elements( $slug, $layout ) {
		// create a nonce 
		$action = equip_get_nonce_action( Equip::METABOX, $slug );
		$nonce  = equip_get_nonce_name( Equip::METABOX, $slug );
		wp_nonce_field( $action, $nonce );
		unset( $action, $nonce );
	}

	public function before_element( $slug, $settings, $layout ) {
		return;
	}

	public function do_element( $slug, $settings, $values, $layout ) {
		$engine = Factory::engine( $layout );
		$engine->render( $slug, $layout, $values );
	}

	public function after_element( $slug, $settings, $layout ) {
		return;
	}

	public function after_elements( $slug, $layout ) {
		return;
	}

}