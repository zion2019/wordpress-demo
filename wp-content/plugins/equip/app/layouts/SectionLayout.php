<?php

namespace Equip\Layout;

use Equip\Factory;

/**
 * For rendering sections
 *
 * Reserved only for options page
 *
 * @author  8guild
 * @package Equip\Layout
 */
class SectionLayout extends Layout {

	/**
	 * @var string
	 */
	public $type = 'section';

	public function __construct( $id, $title, $settings = [], Layout $parent = null ) {
		$settings = array_merge( $settings, [ 'id' => $id, 'title' => $title ] );

		$this->parent   = $parent;
		$this->settings = wp_parse_args( $settings, $this->get_defaults() );
	}

	/**
	 * Add a section
	 *
	 * @param string $id       Section ID
	 * @param string $title    Title
	 * @param array  $settings Settings
	 *
	 * @throws \Exception
	 *
	 * @return SectionLayout
	 */
	public function add_section( $id, $title, $settings = [] ) {
		if ( null === $this->parent ) {
			throw new \Exception( 'Nested sections not supported!' );
		}

		return $this->parent->add_section( $id, $title, $settings );
	}

	/**
	 * Add an anchor
	 *
	 * @param string $id       ID of anchored section. Should be unique per page.
	 * @param string $title    Title
	 * @param array  $settings Settings
	 *
	 * @return AnchorLayout
	 */
	public function add_anchor( $id, $title, $settings = [] ) {
		$anchor           = Factory::layout( 'anchor', [ $id, $title, $settings, $this ] );
		$this->elements[] = $anchor;

		return $anchor;
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
	 * @return mixed
	 */
	public function get_defaults() {
		$settings = [
			'icon'      => '',
			'is_active' => false,
			'priority'  => 10,
		];

		/**
		 * Filter the default settings for sections
		 *
		 * @param array $settings Section default settings
		 */
		return apply_filters( 'equip/layout/section/defaults', $settings );
	}
}