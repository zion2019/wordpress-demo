<?php

namespace Equip\Engine;

use Equip\Factory;

/**
 * Engine for render the "row" type of the layout
 *
 * @author  8guild
 * @package Equip\Engine
 */
class RowEngine extends Engine {

	/**
	 * Open the div.equip-row
	 *
	 * @param string $slug
	 * @param array  $layout
	 */
	public function before_elements( $slug, $layout ) {
		?>
		<div class="equip-row" data-element="row">
		<?php
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
	 * close div.equip-row
	 *
	 * @param string $slug
	 * @param array  $layout
	 */
	public function after_elements( $slug, $layout ) {
		?>
		</div>
		<?php
	}

}