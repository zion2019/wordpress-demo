<?php

namespace Equip\Layout;

use Equip\Factory;

/**
 * Layout classes is responsible for building the layout
 *
 * Create a traversable fields tree
 *
 * @author  8guild
 * @package Equip\Layout
 */
abstract class Layout {

	/**
	 * String representation of layout type
	 *
	 * Required for creating engines {@see \Equip\Factory::engine}
	 *
	 * Should be on of those strings which you register in "equip/factory/engine/map" filter.
	 *
	 * @var null|string
	 */
	public $type = null;

	/**
	 * Instance of parent object
	 *
	 * @var Layout|null
	 */
	public $parent = null;

	/**
	 * Array with elements
	 *
	 * @var array
	 */
	public $elements = [];

	/**
	 * Element settings
	 *
	 * You can work with settings directly inside the layout classes,
	 * but if you want to get settings outside use the getters and setters
	 *
	 * @var array
	 */
	protected $settings = [];

	/**
	 * Layout flags
	 *
	 * @var array
	 */
	protected $flags = [];

	/**
	 * Constructor
	 */
	public function __construct() {
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
		throw new \Exception( get_class( $this ) . ' does not support Sections' );
	}

	/**
	 * Add a tab to layout
	 *
	 * @param string $id       Tab ID
	 * @param string $title    Tab title
	 * @param array  $settings Tab settings
	 *
	 * @return TabLayout
	 */
	public function add_tab( $id, $title, $settings = [] ) {
		$tab              = Factory::layout( 'tab', [ $id, $title, $settings, $this ] );
		$this->elements[] = $tab;

		return $tab;
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
		throw new \Exception( get_class( $this ) . ' does not support Anchors' );
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
		throw new \Exception( get_class( $this ) . ' does not support Rows' );
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
		throw new \Exception( 'You should add Columns into the Rows' );
	}

	/**
	 * Add an offset
	 *
	 * @param int   $width    Width from 1 to 12
	 * @param array $settings Settings
	 *
	 * @throws \Exception
	 *
	 * @return OffsetLayout
	 */
	public function add_offset( $width, $settings = [] ) {
		throw new \Exception( 'You should add Offsets into the Rows' );
	}

	/**
	 * Add a field
	 *
	 * @param string $key      Field key, should be unique per element
	 * @param string $field    Field type
	 * @param array  $settings Settings
	 *
	 * @return FieldLayout
	 */
	public function add_field( $key, $field, $settings = [] ) {
		$field            = Factory::layout( 'field', [ $key, $field, $settings, $this ] );
		$this->elements[] = $field;

		return $field;
	}

	/**
	 * Reset the layout to the most top element
	 *
	 * @return Layout
	 */
	public function reset() {
		if ( null === $this->parent ) {
			return $this;
		}

		return $this->parent->reset();
	}

	/**
	 * Returns the parent object
	 *
	 * If parent object is missing returns itself
	 *
	 * If you specify the $type param method will traverse through
	 * the tree until it finds the specified type. If fails it
	 * returns the highest layout in tree.
	 *
	 * @param string $type Parent layout type
	 *
	 * @return Layout
	 */
	public function parent( $type = '' ) {
		if ( ! empty( $type ) ) {
			if ( $type === $this->type || null === $this->parent ) {
				return $this;
			} else {
				return $this->parent->parent( $type );
			}
		}

		return ( null === $this->parent ) ? $this : $this->parent;
	}

	/**
	 * Should return the default settings for current element
	 *
	 * @return array
	 */
	public function get_defaults() {
		return [];
	}

	/**
	 * Check if flag exists
	 *
	 * @param string $flag
	 *
	 * @return bool
	 */
	public function has_flag( $flag ) {
		return array_key_exists( $flag, $this->flags );
	}

	/**
	 * Set the flag
	 *
	 * You can attach any value to a flag, but bool is preferred
	 *
	 * @param string     $flag
	 * @param bool|mixed $value
	 */
	public function set_flag( $flag, $value ) {
		$this->flags[ $flag ] = $value;
	}

	/**
	 * Get the flag
	 *
	 * @param string $flag
	 *
	 * @return bool|mixed
	 */
	public function get_flag( $flag ) {
		return array_key_exists( $flag, $this->flags ) ? $this->flags[ $flag ] : false;
	}

	/**
	 * Set all flags at once
	 *
	 * Be careful, because this method can override existing flags
	 *
	 * @param array $flags
	 */
	public function set_flags( array $flags ) {
		$this->flags = $flags;
	}

	/**
	 * Get all flags
	 *
	 * @return array
	 */
	public function get_flags() {
		return $this->flags;
	}

	/**
	 * Set the setting
	 *
	 * @param string $setting Setting key
	 * @param mixed  $value   Setting value
	 */
	public function set_setting( $setting, $value ) {
		$this->settings[ $setting ] = $value;
	}

	/**
	 * Get the setting
	 *
	 * If setting is missing in settings array
	 * default value will be returned
	 *
	 * @param string $setting Setting key
	 * @param mixed  $default Default value if setting is missing
	 *
	 * @return mixed
	 */
	public function get_setting( $setting, $default = false ) {
		return array_key_exists( $setting, $this->settings )
			? $this->settings[ $setting ]
			: $default;
	}

	/**
	 * Set all settings
	 *
	 * Be careful, because this method can override existing settings
	 *
	 * @param array $settings
	 */
	public function set_settings( array $settings ) {
		$this->settings = $settings;
	}

	/**
	 * Get all settings
	 *
	 * @return array
	 */
	public function get_settings() {
		return $this->settings;
	}

	/**
	 * Check if current layout is the highest element
	 *
	 * @return bool
	 */
	public function is_root() {
		return ( null === $this->parent );
	}
}