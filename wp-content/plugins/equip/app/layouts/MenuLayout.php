<?php

namespace Equip\Layout;

/**
 * Class MetaboxLayout is a top-level layout type and is responsible for
 * what kind of contents (other parts of layout) can be added to the menu
 *
 * @author  8guild
 * @package Equip\Layout
 */
class MenuLayout extends Layout {

	/**
	 * @var string
	 */
	public $type = 'menu';

	public function __construct() {
		$this->settings = array();
	}
}