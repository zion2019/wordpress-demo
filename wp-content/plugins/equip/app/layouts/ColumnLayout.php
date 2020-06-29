<?php

namespace Equip\Layout;

/**
 * Class ColumnLayout is responsible for adding Columns into the Layout
 *
 * @author  8guild
 * @package Equip\Layout
 */
class ColumnLayout extends Layout {

	/**
	 * @var string
	 */
	public $type = 'column';

	public function __construct( $width = 12, $settings = [], Layout $parent = null ) {
		$this->parent   = $parent;
		$this->settings = array_merge(
			[ 'width' => $width ],
			$this->get_defaults(),
			$settings
		);
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
			throw new \Exception( 'You can not add Sections inside Column' );
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
	 * @throws \Exception
	 *
	 * @return AnchorLayout
	 */
	public function add_anchor( $id, $title, $settings = [] ) {
		if ( null === $this->parent ) {
			throw new \Exception( 'You can not add Anchors inside Column' );
		}

		return $this->parent->add_anchor( $id, $title, $settings );
	}

	/**
	 * Add a row
	 *
	 * @param array $settings Settings
	 *
	 * @throws \Exception
	 *
	 * @return RowLayout
	 */
	public function add_row( $settings = [] ) {
		if ( null === $this->parent ) {
			throw new \Exception( 'Can not add column to nowhere. May be you miss add_row()?' );
		}

		return $this->parent->add_row( $settings );
	}

	/**
	 * Add a column
	 *
	 * @param int   $width    Column width from 1 to 12
	 * @param array $settings Settings
	 *
	 * @throws \Exception
	 *
	 * @return ColumnLayout
	 */
	public function add_column( $width, $settings = [] ) {
		if ( null == $this->parent ) {
			throw new \Exception( 'Nested columns not supported! You should add fields.' );
		}

		return $this->parent->add_column( $width, $settings );
	}

	/**
	 * Returns the default settings for columns
	 *
	 * @return array
	 */
	public function get_defaults() {
		$settings = array(
			'priority' => 10,
		);

		/**
		 * Filter the default settings for columns
		 *
		 * @param array $settings Column default settings
		 */
		return apply_filters( 'equip/layout/column/defaults', $settings );
	}
}