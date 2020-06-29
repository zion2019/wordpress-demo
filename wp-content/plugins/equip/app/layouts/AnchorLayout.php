<?php

namespace Equip\Layout;

use Equip\Factory;

/**
 * This layout element is used for anchored navigation inside sections
 *
 * @author  8guild
 * @package Equip\Layout
 */
class AnchorLayout extends Layout {
	/**
	 * @var string
	 */
	public $type = 'anchor';

	public function __construct( $id, $title, $settings = [], Layout $parent = null ) {
		$settings = array_merge( $settings, [ 'id' => $id, 'title' => $title ] );

		$this->parent   = $parent;
		$this->settings = wp_parse_args( $settings, $this->get_defaults() );
	}

	/**
	 * Add a section
	 *
	 * @param string $id
	 * @param string $title
	 * @param array  $settings
	 *
	 * @throws \Exception
	 *
	 * @return SectionLayout
	 */
	public function add_section( $id, $title, $settings = [] ) {
		if ( null === $this->parent ) {
			throw new \Exception( 'You can not add Sections inside the Anchors' );
		}

		return $this->parent->add_section( $id, $title, $settings );
	}

	/**
	 * Add an anchor
	 *
	 * @param string $id
	 * @param string $title
	 * @param array  $settings
	 *
	 * @throws \Exception
	 *
	 * @return AnchorLayout
	 */
	public function add_anchor( $id, $title, $settings = [] ) {
		if ( null === $this->parent ) {
			throw new \Exception( 'Nested anchors not supported' );
		}

		return $this->parent->add_anchor( $id, $title, $settings );
	}

	/**
	 * Add a row
	 *
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
	 * @return array
	 */
	public function get_defaults() {
		$settings = [
			'priority' => 10,
		];

		/**
		 * Filters the default settings for anchors
		 *
		 * @param array $settings Anchor default settings
		 */
		return apply_filters( 'equip/layout/anchor/defaults', $settings );
	}


}