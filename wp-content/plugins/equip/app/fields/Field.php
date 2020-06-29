<?php

namespace Equip\Field;

/**
 * Abstract field
 *
 * @author  8guild
 * @package Equip\Field
 */
abstract class Field {
	/**
	 * The unique name of the current element
	 *
	 * @var null|string
	 */
	protected $slug = null;

	/**
	 * Field key
	 *
	 * @var null|string
	 */
	protected $key = null;

	/**
	 * Field settings
	 *
	 * @see Field::setup
	 * @see Field::reset
	 *
	 * @var array
	 */
	public $settings = array();

	/**
	 * Field value
	 *
	 * @var null|mixed
	 */
	protected $value = null;

	/**
	 * The context, or where this field are going to be rendered?
	 *
	 * Other words this property will keep the string with the type
	 * of the high-level layout, like: meta_box, options, menu, etc
	 * where current field was added
	 *
	 * @var null|string
	 */
	protected $context = null;

	/**
	 * Setup current field
	 *
	 * This is required because Equip does not create new object
	 * for each field, but re-use previously created to save memory
	 *
	 * @param string $slug     Element unique name
	 * @param array  $settings Field settings
	 * @param mixed  $value    Field value. Real value, default one or null. Already escaped!
	 */
	public function setup( $slug, array $settings, $value = null ) {
		$this->slug     = $slug;
		$this->key      = sanitize_key( $settings['key'] );
		$this->settings = $settings;
		$this->value    = $value;
	}

	/**
	 * Engine the field itself
	 *
	 * @param string $slug     Element unique name
	 * @param array  $settings Field settings
	 * @param mixed  $value    Field value. Real value, default one or null. Already escaped!
	 */
	abstract public function render( $slug, $settings, $value );

	/**
	 * Sanitize value before saving to database
	 *
	 * This method does not sanitize anything! It returns value AS IS.
	 * If you want to use proper sanitizing you MUST to overload this
	 * method in your custom field class.
	 *
	 * @param mixed  $value    Field value
	 * @param array  $settings Field settings
	 * @param string $slug     Current element slug
	 *
	 * @return mixed Sanitized value
	 */
	public function sanitize( $value, $settings, $slug ) {
		return $value;
	}

	/**
	 * Escape value before rendering
	 *
	 * This method does not escape anything! It returns value AS IS.
	 * If you want to use proper escaping you MUST to overload this
	 * method in your custom field class.
	 *
	 * @param mixed  $value    Field value
	 * @param array  $settings Field settings
	 * @param string $slug     Current element slug
	 *
	 * @return mixed Escaped value
	 */
	public function escape( $value, $settings, $slug ) {
		return $value;
	}

	/**
	 * Enqueue JS and/or CSS for this field.
	 *
	 * This is a best place to enqueue scripts and styles.
	 * This method called during the "admin_enqueue_scripts" hook
	 *
	 * @deprecated
	 *
	 * @return bool
	 */
	public function enqueue() {
		return false;
	}

	/**
	 * Reset the current field to it's default state
	 *
	 * This is required because Equip does not create new object
	 * for each field, but re-use previously created to save memory
	 */
	public function reset() {
		$this->slug     = null;
		$this->key      = null;
		$this->settings = array();
		$this->value    = null;
	}

	/**
	 * Return field's slug
	 *
	 * @return null|string
	 */
	public function get_slug() {
		return $this->slug;
	}

	/**
	 * Returns current field's key
	 *
	 * @return null|string
	 */
	public function get_key() {
		return $this->key;
	}

	/**
	 * Get field ID
	 *
	 * Based on field $slug and $key
	 *
	 * @return string
	 */
	protected function get_id() {
		$id = sprintf( 'equip-%1$s-%2$s', $this->slug, $this->key );
		$id = str_replace( '_', '-', $id );

		// remove possible duplicated dashes
		$id = preg_replace( '/--+/', '-', $id );

		return $id;
	}

	/**
	 * Get field "name" attribute
	 *
	 * Based on $slug and $key
	 *
	 * @return string
	 */
	protected function get_name() {
		$name = sprintf( '%1$s[%2$s]', $this->slug, $this->key );

		/**
		 * Filter the each field's name attribute
		 *
		 * @param string             $name Name. Default is slug[key]
		 * @param \Equip\Field\Field $this Equip field object
		 */
		return apply_filters( 'equip/field/name', $name, $this );
	}

	/**
	 * Set the value dynamically
	 *
	 * @param mixed $value
	 */
	protected function set_value( $value ) {
		$this->value = $value;
	}

	/**
	 * Return the field value
	 *
	 * @return mixed|null
	 */
	protected function get_value() {
		return $this->value;
	}

	/**
	 * Set new value for setting
	 *
	 * @param string $setting Setting key
	 * @param mixed  $value   New setting value
	 */
	protected function set_setting( $setting, $value ) {
		$this->settings[ $setting ] = $value;
	}

	/**
	 * Return single setting
	 *
	 * @param string     $setting Setting key
	 * @param bool|mixed $default Default value if key is missing
	 *
	 * @return bool|mixed
	 */
	protected function get_setting( $setting, $default = false ) {
		return array_key_exists( $setting, $this->settings ) ? $this->settings[ $setting ] : $default;
	}

	/**
	 * Return the whole bunch of settings for current field
	 *
	 * @return array
	 */
	protected function get_settings() {
		return $this->settings;
	}

	/**
	 * Get field label
	 *
	 * @uses esc_attr()
	 * @uses esc_html()
	 *
	 * @return string
	 */
	protected function get_label() {
		$label = $this->get_setting( 'label' );
		if ( empty( $label ) ) {
			return '';
		}

		return sprintf( '<label class="equip-field-label" for="%1$s">%2$s</label>',
			esc_attr( $this->get_id() ),
			esc_html( $label )
		);
	}

	/**
	 * Get the field helper
	 *
	 * @uses esc_html()
	 *
	 * @return string
	 */
	protected function get_helper() {
		$helper = $this->get_setting( 'helper' );
		if ( empty( $helper ) ) {
			return '';
		}

		return sprintf( '<span class="equip-helper">%s</span>', esc_html( $helper ) );
	}

	/**
	 * Get field description
	 *
	 * @uses wp_kses()
	 *
	 * @return string
	 */
	protected function get_description() {
		$description = $this->get_setting( 'description' );
		if ( empty( $description ) ) {
			return '';
		}

		return sprintf( '<p class="equip-description">%1$s</p>',
			wp_kses( $description, [
				'i'      => [ 'class' => true ],
				'span'   => [ 'class' => true ],
				'strong' => [ 'class' => true ],
				'em'     => [ 'class' => true ],
				'b'      => [ 'class' => true ],
				'br'     => [ 'class' => true ],
				'a'      => [ 'class' => true, 'href' => true, 'target' => true ],
			] )
		);
	}

	/**
	 * Returns ready-to-use string of HTML attributes
	 *
	 * @return string
	 */
	protected function get_attr() {
		$attr = $this->combine_attr( $this->get_default_attr(), $this->settings['attr'] );

		$restricted = $this->get_restricted_attr();
		$attributes = array_diff_key( (array) $attr, $restricted );

		// TODO: standalone attributes? like hidden, disabled, etc
		// TODO: arrays
		// $attributes = array_map( 'esc_attr', $attributes );

		$attributes = equip_get_attr( $attributes );

		return $attributes;
	}

	/**
	 * Returns a raw array with HTML attributes in format [attribute => value]
	 *
	 * TODO: may be rename to get_attr_raw?
	 *
	 * @return array
	 */
	protected function get_attr_array() {
		$attr = $this->combine_attr( $this->get_default_attr(), $this->settings['attr'] );

		$restricted = $this->get_restricted_attr();
		$attributes = array_diff_key( (array) $attr, $restricted );

		// TODO: standalone attributes? like hidden, disabled, etc
		// TODO: arrays
		// $attributes = array_map( 'esc_attr', $attributes );

		return $attributes;
	}

	/**
	 * This method should return an array of default HTML attributes for current field.
	 *
	 * Yes, this method should be implemented in each field,
	 * e.g. for "input" these attributes should be type=text, etc.
	 *
	 * @return array
	 */
	protected function get_default_attr() {
		return [];
	}

	/**
	 * This method should return an array with default $settings for current field
	 *
	 * This may be useful to make sure all unique settings are present
	 *
	 * @see \Equip\Engine\FieldEngine::do_element
	 *
	 * @return array
	 */
	public function get_defaults() {
		return [];
	}

	/**
	 * Set the context
	 *
	 * @param string $context
	 */
	public function set_context( $context ) {
		$this->context = $context;
	}

	/**
	 * Returns the context
	 *
	 * @return null|string
	 */
	public function get_context() {
		return $this->context;
	}

	/**
	 * Combine possible duplicated attributes
	 *
	 * This helper is designed for situations, when particular field MUST have some
	 * attributes, like some specific class. But user have an option to add a custom
	 * attributes by passing them into "attr" key. E.g. class.
	 *
	 * Note! Combined values removes from the original arrays.
	 *
	 * @param array $left  Required attribute for a field, defined in {@see Field::get_default_attr}
	 * @param array $right A list of user-defined attributes, passed into "attr" key.
	 *
	 * @return array
	 */
	private function combine_attr( $left = [], $right = [] ) {
		$intersected = array_intersect_key( $left, $right );
		if ( empty( $intersected ) ) {
			return array_merge( $left, $right );
		}

		$combined = [];
		foreach ( array_keys( $intersected ) as $key ) {
			switch ( $key ) {
				case 'class':
					$combined[ $key ] = equip_get_class_set( [ $left[ $key ], $right[ $key ] ] );
					break;

				case 'type':
					// if type if specified always use one from user
					$combined[ $key ] = $right[ $key ];
					break;

				default:
					/**
					 * Apply the custom combination strategy for unknown attributes
					 *
					 * $key is html attribute name, like "class", "id", etc.
					 *
					 * Note: user-defined attribute will be used as default
					 *
					 * @param string $right User-defined attribute, passed into "attr" key.
					 * @param string $left  Required attribute for a field, defined in {@see Field::default_attr}
					 */
					$value = apply_filters( "equip/field/combine_attr/{$key}", $right[ $key ], $left[ $key ] );

					$combined[ $key ] = $value;
					unset( $value );
					break;
			}

			unset( $left[ $key ], $right[ $key ] );
		}

		return array_merge( $left, $right, $combined );
	}

	/**
	 * Get list of restricted HTML attributes, which are not allowed to use
	 * in every field settings.
	 *
	 * @link  https://developer.mozilla.org/en-US/docs/Web/HTML/Attributes
	 *
	 * @return array
	 */
	private function get_restricted_attr() {
		$restricted = array(
			'id'    => '',
			'name'  => '',
			'value' => '',
			'type'  => '',
		);

		/**
		 * Filter the restricted attributes
		 *
		 * @param array $attr List of restricted HTML attributes
		 */

		return apply_filters( 'equip/field/restricted_attr', $restricted );
	}

}