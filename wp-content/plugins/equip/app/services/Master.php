<?php

namespace Equip\Service;

/**
 * Handle the "master" fields
 *
 * @package Equip\Service
 */
class Master {

	/**
	 * Values
	 *
	 * @var array
	 */
	private $values = [];

	/**
	 * Constructor
	 */
	public function __construct() {
	}

	/**
	 * Do the job
	 *
	 * NOTE: each field supports only one "master" field
	 *
	 * @param \Equip\Layout\Layout $layout Layout
	 * @param array                $values Values
	 *
	 * @return \Equip\Layout\Layout
	 */
	public function handle( $layout, $values ) {
		$fields = equip_layout_get_fields( $layout );
		if ( empty( $fields ) ) {
			return $layout;
		}

		$this->set_values( $values );

		/** @var \Equip\Layout\FieldLayout $field */
		foreach ( $fields as $field ) {
			$master = $field->get_setting( 'master' );
			if ( empty( $master ) ) {
				continue;
			}

			// in case if user set a string instead an array
			if ( ! is_array( $master ) ) {
				$master = (array) $master;
			}

			$master_key = $master[0];
			$operator   = empty( $master[1] ) ? null : $master[1];
			$argument   = empty( $master[2] ) ? null : $master[2];
			$rounding   = empty( $master[3] ) ? 'none' : $master[3];

			// if field not present in layout
			if ( ! array_key_exists( $master_key, $fields ) ) {
				continue;
			}

			$master_value = $this->get_field_value( $master_key, $fields[ $master_key ] );

			// maybe compute the value
			if ( ! empty( $operator ) && ! empty( $argument ) ) {
				$master_value = $this->compute( $master_value, $operator, $argument, $rounding );
			}

			// set new value as default
			// because it should be possible to change child fields directly
			$field->set_setting( 'default', $master_value );

			unset( $master, $master_key, $master_value, $operator, $rounding );
		}

		$this->reset();

		return $layout;
	}

	/**
	 * Compute the value. Make sense for numeric values.
	 *
	 * @param int|float $value    The current value of the master field or default value
	 * @param string    $operator Operator
	 * @param int|float $argument Argument passed as a third parameter
	 * @param string    $rounding Rounding direction. Supports "ceil" or "floor"
	 *
	 * @return int
	 */
	private function compute( $value, $operator, $argument, $rounding ) {
		switch ( $operator ) {
			case '+':
				$value = $value + $argument;
				break;

			case '-':
				$value = $value - $argument;
				break;

			case '*':
				$value = $value * $argument;
				break;

			case '/':
				$value = $value / $argument;
				break;

			default:
				/**
				 * Compute the master value with user-defined operator
				 *
				 * @param mixed  $value    The current value of the master field or default value
				 * @param string $operator Operator
				 * @param mixed  $argument Argument passed as a third parameter
				 */
				$value = apply_filters( 'equip/master/compute', $value, $operator, $argument );
				break;
		}

		if ( 'none' !== $rounding ) {
			$value = ( 'ceil' === $rounding ) ? ceil( $value ) : floor( $value );
		}

		return (int) $value;
	}

	/**
	 * Returns the field value
	 *
	 * First check the $values. If $key is missing the default
	 * field value will be used.
	 *
	 * @param string                    $key   Field key
	 * @param \Equip\Layout\FieldLayout $field Field layout
	 *
	 * @return mixed
	 */
	private function get_field_value( $key, $field ) {
		$settings = $field->get_settings();

		if ( array_key_exists( $key, $this->values ) ) {
			$value = $this->values[ $key ];
		} elseif ( array_key_exists( 'default', $settings ) ) {
			// make sure the default is not removed by filter
			// @see FieldLayout::get_defaults
			$value = $settings['default'];
		} else {
			$value = null;
		}

		return $value;
	}

	/**
	 * Set values
	 *
	 * @param mixed $values
	 */
	private function set_values( $values ) {
		// values should be an array
		if ( ! is_array( $values ) ) {
			$values = (array) $values;
		}

		$this->values = $values;
	}

	/**
	 * Reset values to defaults
	 */
	private function reset() {
		$this->values = [];
	}
}