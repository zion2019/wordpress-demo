<?php

namespace Equip\Layout;

/**
 * Class FieldLayout is responsible for adding fields into the Layout
 *
 * @author  8guild
 * @package Equip\Layout
 */
class FieldLayout extends Layout {

	/**
	 * @var string
	 */
	public $type = 'field';

	/**
	 * Constructor
	 *
	 * @param string      $key      Field key
	 * @param string      $field    Field type
	 * @param array       $settings Field settings
	 * @param Layout|null $parent   Parent object
	 */
	public function __construct( $key, $field, $settings = [], Layout $parent = null ) {
		$this->parent   = $parent;
		$this->settings = array_merge(
			[ 'field' => $field, 'key' => $key ],
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
			throw new \Exception( 'You can\'t add Sections into the Field' );
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
			throw new \Exception( 'You can\'t add Anchors into the Field' );
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
			throw new \Exception( 'Last field has not got parents' );
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
		if ( null === $this->parent ) {
			throw new \Exception( 'You can not add columns to null. May be you miss Row?' );
		}

		return $this->parent->add_column( $width, $settings );
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
	 * @return FieldLayout
	 */
	public function add_field( $key, $field, $settings = [] ) {
		if ( null === $this->parent ) {
			throw new \Exception( 'Nested fields not supported!' );
		}

		return $this->parent->add_field( $key, $field, $settings );
	}

	/**
	 * Returns the default settings for fields
	 *
	 * This settings should be present in every field to avoid bad indexes.
	 *
	 * @return array
	 */
	public function get_defaults() {
		$settings = [
			'label'       => '',
			'helper'      => '',
			'description' => '',
			'default'     => null,
			'priority'    => 10,
			'attr'        => [],
			'sanitize'    => '',
			'escape'      => '',
			'hidden'      => false,
			'required'    => [],
			'master'      => [],
			'settings'    => [],
		];

		/**
		 * Filter the default settings for fields
		 *
		 * @param array $settings Field default settings
		 */
		return apply_filters( 'equip/layout/field/defaults', $settings );
	}
}