<?php

namespace Equip\Layout;

use Equip\Factory;

/**
 * Tab
 *
 * This class is responsible for controlling tabs behavior in the layout
 *
 * @author  8guild
 * @package Equip\Layout
 */
class TabLayout extends Layout {
	/**
	 * @var string
	 */
	public $type = 'tab';

	/**
	 * TabLayout constructor.
	 *
	 * @param             $id
	 * @param             $title
	 * @param array       $settings
	 * @param Layout|null $parent
	 */
	public function __construct( $id, $title, $settings = [], Layout $parent = null ) {
		$settings = array_merge( $settings, [ 'id' => $id, 'title' => $title ] );

		$this->parent   = $parent;
		$this->settings = wp_parse_args( $settings, $this->get_defaults() );
	}

	/**
	 * Add a section
	 *
	 * Overload the default add_tab() method.
	 * Do not allow the nested tabs, thus add a tab to parent element.
	 *
	 * @param string $id       Section ID
	 * @param string $title    Title
	 * @param array  $settings Settings
	 *
	 * @throws \Exception
	 *
	 * @return TabLayout
	 */
	public function add_tab( $id, $title, $settings = [] ) {
		if ( null === $this->parent ) {
			throw new \Exception( 'Nested tabs not supported.' );
		}

		return $this->parent->add_tab( $id, $title, $settings );
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

	/**
	 * Return default settings for single tab
	 *
	 * @return mixed
	 */
	public function get_defaults() {
		$settings = [
			'is_active' => false,
			'priority'  => 10,
		];

		/**
		 * Filter the default settings for sections
		 *
		 * @param array $settings Section default settings
		 */
		return apply_filters( 'equip/layout/tab/defaults', $settings );
	}
}