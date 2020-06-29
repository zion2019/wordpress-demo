<?php

namespace Equip\Engine;

use Equip\Factory;

/**
 * Engine for render the "column" type of the layout
 *
 * A special engine for rendering groups.
 * Should not be used as a standalone engine.
 *
 * @author  8guild
 * @package Equip\Engine
 */
class ColumnEngine extends Engine {

	/**
	 * Open div.equip-col-{n}
	 *
	 * @param string               $slug
	 * @param \Equip\Layout\Layout $layout
	 */
	public function before_elements( $slug, $layout ) {
		$settings = $layout->get_settings();
		if ( array_key_exists( 'width', $settings ) ) {
			$width = (int) $settings['width'];
			$width = ( $width > 0 && $width < 12 ) ? $width : 12;
		} else {
			$width = 12;
		}

		$attr = [];

		$attr['class']        = esc_attr( "equip-col-{$width}" );
		$attr['data-element'] = 'column';

		echo '<div ', equip_get_attr( $attr ), '>';
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

	/**
	 * Close div.equip-col-{n}
	 *
	 * @param string               $slug
	 * @param \Equip\Layout\Layout $layout
	 */
	public function after_elements( $slug, $layout ) {
		echo '</div>';
	}

}