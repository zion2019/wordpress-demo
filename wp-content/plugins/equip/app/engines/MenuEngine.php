<?php

namespace Equip\Engine;

use Equip\Factory;

/**
 * The engine for rendering
 *
 * @author  8guild
 * @package Equip\Engine
 */
class MenuEngine extends Engine {

	public function before_elements( $slug, $layout ) {
		return;
	}

	/**
	 * Open p.field-{key}
	 *
	 * Menu layout does not support any nested elements,
	 * so before_element should wrap the field
	 *
	 * @param string               $slug
	 * @param array                $settings
	 * @param \Equip\Layout\Layout $layout
	 */
	public function before_element( $slug, $settings, $layout ) {
		$class = array();

		$class[] = "field-" . sanitize_key( $settings['key'] );
		$class[] = 'description';
		$class[] = 'description-wide';

		echo '<div class="', equip_get_class_set( $class ), '">';
	}

	public function do_element( $slug, $settings, $values, $layout ) {
		$engine = Factory::engine( $layout );
		$engine->render( $slug, $layout, $values );
	}

	/**
	 * Close p.field-{key}
	 *
	 * @param string               $slug
	 * @param array                $settings
	 * @param \Equip\Layout\Layout $layout
	 */
	public function after_element( $slug, $settings, $layout ) {
		echo '</div>';
	}

	public function after_elements( $slug, $layout ) {
		return;
	}
}