<?php

namespace Equip\Layout;

use Equip\Factory;

/**
 * Class MetaboxLayout is a top-level layout type and is responsible for
 * what kind of contents (other parts of layout) can be added to the meta box
 *
 * @author  8guild
 * @package Equip\Layout
 */
class MetaboxLayout extends Layout {

	/**
	 * @var string
	 */
	public $type = 'metabox';

	/**
	 * @param array $settings
	 *
	 * @return RowLayout
	 */
	public function add_row( $settings = array() ) {
		$row              = Factory::layout( 'row', [ $settings, $this ] );
		$this->elements[] = $row;

		return $row;
	}

	/**
	 * @param       $width
	 * @param array $settings
	 *
	 * @throws \Exception
	 *
	 * @return void
	 */
	public function add_column( $width, $settings = array() ) {
		throw new \Exception( 'You should put Columns into the Rows. Call add_row() before add_column().' );
	}
}