<?php

namespace Equip\Layout;

use Equip\Factory;

/**
 * Class OptionsLayout
 *
 * @author  8guild
 * @package Equip\Layout
 */
class OptionsLayout extends Layout {

	/**
	 * @var string
	 */
	public $type = 'options';

	/**
	 * Add a section
	 *
	 * @param string $id       Section ID
	 * @param string $title    Title
	 * @param array  $settings Settings
	 *
	 * @return SectionLayout
	 */
	public function add_section( $id, $title, $settings = [] ) {
		$section          = Factory::layout( 'section', [ $id, $title, $settings, $this ] );
		$this->elements[] = $section;

		return $section;
	}

	/**
	 * Add a row
	 *
	 * @param array $settings Settings
	 *
	 * @return RowLayout
	 */
	public function add_row( $settings = [] ) {
		$row              = Factory::layout( 'row', [ $settings, $this ] );
		$this->elements[] = $row;

		return $row;
	}
}