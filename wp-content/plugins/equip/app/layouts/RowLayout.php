<?php

namespace Equip\Layout;

use Equip\Factory;

/**
 * Class RowLayout is responsible for adding the rows into the Layout
 *
 * @author  8guild
 * @package Equip\Layout
 */
class RowLayout extends Layout {

	/**
	 * @var string
	 */
	public $type = 'row';

	/**
	 * RowLayout constructor
	 *
	 * @param array       $settings
	 * @param Layout|null $parent
	 */
	public function __construct( $settings = [], Layout $parent = null ) {
		$this->parent   = $parent;
		$this->settings = array_merge( $settings, $this->get_defaults() );
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
			throw new \Exception( 'You can\'t add Sections inside Rows' );
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
			throw new \Exception( 'You can not add Anchors inside Rows' );
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
		if ( null == $this->parent ) {
			throw new \Exception( 'Nested Rows not supported! You should add Columns to Row!' );
		}

		return $this->parent->add_row( $settings );
	}

	/**
	 * Add a column
	 *
	 * @param int   $width    Column width from 1 to 12
	 * @param array $settings Settings
	 *
	 * @return ColumnLayout
	 */
	public function add_column( $width, $settings = [] ) {
		$column           = Factory::layout( 'column', [ $width, $settings, $this ] );
		$this->elements[] = $column;

		return $column;
	}

	/**
	 * Add an offset
	 *
	 * @param int   $width    Offset width from 1 to 12
	 * @param array $settings Settings
	 *
	 * @return ColumnLayout
	 */
	public function add_offset( $width, $settings = [] ) {
		$offset           = Factory::layout( 'offset', [ $width, $settings, $this ] );
		$this->elements[] = $offset;

		return $offset;
	}

	/**
	 * Add a field
	 *
	 * @param string $key      Field key, should be unique per element
	 * @param string $field    Field type
	 * @param array  $settings Settings
	 *
	 * @throws \Exception
	 *
	 * @return void
	 */
	public function add_field( $key, $field, $settings = [] ) {
		throw new \Exception( 'You should add Columns or Offsets to Rows, not Fields!' );
	}

	/**
	 * Returns the default settings for rows
	 *
	 * @return array
	 */
	public function get_defaults() {
		$settings = array(
			'priority' => 10,
		);

		/**
		 * Filter the default settings for rows
		 *
		 * @param array $settings Row default settings
		 */
		return apply_filters( 'equip/layout/row/defaults', $settings );
	}
}