<?php

use \Equip\Layout\Layout;

/**
 * Category is a top-level layout type
 *
 * This layout is responsible for the content
 * which may be added to the "add" or "edit" category screen
 *
 * @author     8guild
 * @package    Equip\Layout
 * @subpackage StartApp\Core
 */
class CategoryLayout extends Layout {
	/**
	 * @var string
	 */
	public $type = 'category';

	public function __construct( $settings = [] ) {
		$this->settings = $settings;
	}
}
