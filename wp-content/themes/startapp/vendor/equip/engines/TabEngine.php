<?php
namespace Equip\Engine;

use Equip\Factory;

/**
 * TabEngine
 *
 * @package Equip\Engine
 */
class TabEngine extends Engine {

	/**
	 * Start parent element wrapper
	 *
	 * @param string               $slug   Element unique name
	 * @param \Equip\Layout\Layout $layout Current layout scope
	 *
	 * @return void
	 */
	public function before_elements( $slug, $layout ) {
		return;
	}

	/**
	 * Start nested element wrapper
	 *
	 * @param string               $slug     Element unique name
	 * @param array                $settings Element settings
	 * @param \Equip\Layout\Layout $layout   Current layout scope
	 *
	 * @return void
	 */
	public function before_element( $slug, $settings, $layout ) {
		return;
	}

	/**
	 * Do the element
	 *
	 * @param string               $slug     Element unique name
	 * @param array                $settings Element settings
	 * @param mixed                $values   Raw element value
	 * @param \Equip\Layout\Layout $layout   Current layout scope
	 */
	public function do_element( $slug, $settings, $values, $layout ) {
		$engine = Factory::engine( $layout );
		$engine->render( $slug, $layout, $values );
	}

	/**
	 * End nested element wrapper
	 *
	 * @param string               $slug     Element unique name
	 * @param array                $settings Element settings
	 * @param \Equip\Layout\Layout $layout   Current layout scope
	 *
	 * @return void
	 */
	public function after_element( $slug, $settings, $layout ) {
		return;
	}

	/**
	 * End parent element wrapper
	 *
	 * @param string               $slug   Element unique name
	 * @param \Equip\Layout\Layout $layout Current layout scope
	 *
	 * @return void
	 */
	public function after_elements( $slug, $layout ) {
		return;
	}
}