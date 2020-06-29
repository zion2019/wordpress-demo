<?php

namespace Equip\Engine;

use Equip\Factory;

/**
 * Engine for render the "offset" type of the layout
 *
 * @author  8guild
 * @package Equip\Engine
 */
class OffsetEngine extends Engine {

	/**
	 * Open dev.equip-offset-{n}
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

		$attr['class']        = esc_attr( "equip-offset-{$width}" );
		$attr['data-element'] = 'offset';

		echo '<div ', equip_get_attr( $attr ), '>';
	}

	public function before_element( $slug, $settings, $layout ) {
		return;
	}

	/**
	 * You can not add items inside Offset
	 *
	 * @param string               $slug     Slug
	 * @param array                $settings Settings
	 * @param mixed                $values   Values
	 * @param \Equip\Layout\Layout $layout   Layout
	 *
	 * @return void
	 */
	public function do_element( $slug, $settings, $values, $layout ) {
		return;
	}

	public function after_element( $slug, $settings, $layout ) {
		return;
	}

	/**
	 * Close div.equip-offset-{n}
	 *
	 * @param string               $slug
	 * @param \Equip\Layout\Layout $layout
	 */
	public function after_elements( $slug, $layout ) {
		echo '</div>';
	}

}