<?php

use Equip\Engine\Engine;
use Equip\Factory;

/**
 * This engine is responsible for rendering the top-level "category" layout
 *
 * @author     8guild
 * @package    Equip\Enigne
 * @subpackage Startapp\Core
 */
class CategoryEngine extends Engine {

	public function before_elements( $slug, $layout ) {
		return;
	}

	public function before_element( $slug, $settings, $layout ) {
		// detect the current screen
		$parent = $layout->reset();
		$screen = $parent->get_setting( 'screen', 'add' );

		if ( 'edit' === $screen ) {
			echo '<tr class="form-field term-' . $settings['key'] . '-wrap">';
			echo '<th>', $settings['label'], '</th>';
			echo '<td>';
		} else {
			echo '<div class="form-field term-' . $settings['key'] . '-wrap">';
		}
	}

	public function do_element( $slug, $settings, $values, $layout ) {
		$engine = Factory::engine( $layout );
		$engine->render( $slug, $layout, $values );
	}

	public function after_element( $slug, $settings, $layout ) {
		$parent = $layout->reset();
		$screen = $parent->get_setting( 'screen', 'add' );

		if ( 'edit' === $screen ) {
			echo '</td>';
			echo '</tr>';
		} else {
			echo '</div>';
		}
	}

	public function after_elements( $slug, $layout ) {
		return;
	}
}