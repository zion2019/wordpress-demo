<?php

namespace Equip\Service;

/**
 * Handle the "required" fields
 *
 * @package Equip\Service
 */
class Required {

	/**
	 * Constructor
	 */
	public function __construct() {
	}

	/**
	 * Do the job
	 *
	 * @param \Equip\Layout\Layout $layout
	 * @param array                $values
	 *
	 * @return \Equip\Layout\Layout
	 */
	public function handle( $layout, $values ) {
		$fields = equip_layout_get_fields( $layout );
		if ( empty( $fields ) ) {
			return $layout;
		}

		// values should be an array
		if ( ! is_array( $values ) ) {
			$values = [];
		}

		/** @var \Equip\layout\FieldLayout $field */
		foreach ( $fields as $key => $field ) {
			$settings = $field->get_settings();
			// skip fields without "required"
			if ( ! array_key_exists( 'required', $settings ) || empty( $settings['required'] ) ) {
				continue;
			}

			if ( is_array( $settings['required'][0] ) ) {

				// multiple "required" fields
				foreach ( $settings['required'] as $required ) {
					$master_key    = $required[0];
					$operator      = $required[1];
					$compare_value = isset( $required[2] ) ? $required[2] : null;

					// if field not present in layout
					if ( ! array_key_exists( $master_key, $fields ) ) {
						continue;
					}

					$master_value = $this->get_value( $master_key, $fields[ $master_key ], $values );
					if ( ! $this->compare( $master_value, $operator, $compare_value ) ) {
						// mark dependent fields
						// as php work with references, so we can
						$field->set_flag( 'dependent', true );

						// if al least one condition fails
						// it does not make sense to compare another values
						break;
					}
					unset( $master_key, $master_value, $compare_value, $operator );
				}
				unset( $required );
			} else {

				// single "required"
				$master_key    = $settings['required'][0];
				$operator      = $settings['required'][1];
				$compare_value = isset( $settings['required'][2] ) ? $settings['required'][2] : null;

				// if field not present in layout
				if ( ! array_key_exists( $master_key, $fields ) ) {
					continue;
				}

				$master_value = $this->get_value( $master_key, $fields[ $master_key ], $values );
				if ( ! $this->compare( $master_value, $operator, $compare_value ) ) {
					$field->set_flag( 'dependent', true );
				}
				unset( $master_key, $master_value, $operator, $compare_value );
			}
		}
		unset( $key, $settings );

		return $layout;
	}

	/**
	 * Compare values
	 *
	 * If true the field will be visible, and hidden if false
	 *
	 * @example Single dependency
	 * required => [master_key, operator, compare_value]
	 *
	 * @example Multiple dependencies
	 * required => [
	 *   [master_key, operator, compare_value],
	 *   ...
	 * ]
	 *
	 * @param mixed  $master_value  The value of the master field
	 * @param string $operator      Compare operator
	 * @param mixed  $compare_value Required value to show the field
	 *
	 * @return bool
	 */
	public function compare( $master_value, $operator, $compare_value ) {
		$result = false;
		switch ( $operator ) {
			case '=':
			case 'eq':
			case 'equal':
			case 'equals':
				$result = ( $master_value == $compare_value );
				break;

			case '!=':
			case 'ne':
				$result = ( $master_value != $compare_value );
				break;

			case '>':
			case 'gt':
			case 'greater':
				$result = ( $master_value > $compare_value );
				break;

			case '>=':
			case 'ge':
			case 'greater_equal':
				$result = ( $master_value >= $compare_value );
				break;

			case '<':
			case 'lt':
			case 'less':
				$result = ( $master_value < $compare_value );
				break;

			case '<=':
			case 'le':
			case 'less_equal':
				$result = ( $master_value <= $compare_value );
				break;

			case 'empty':
				$result = empty( $master_value );
				break;

			case 'not_empty':
				$result = ( ! empty( $master_value ) );
				break;

			case 'contains':
				$result = ( false !== strpos( $master_value, $compare_value ) );
				break;

			case 'not_contains':
				$result = ( false === strpos( $master_value, $compare_value ) );
				break;

			case 'in_array':
				// check if master_value exists in provided compare_value
				// [ 'master_key', 'in_array', [ one, two ] ]
				$result = in_array( (string) $master_value, (array) $compare_value );
				break;

			case 'not_in_array':
				$result = ( ! in_array( (string) $master_value, (array) $compare_value ) );
				break;

			default:
				/**
				 * Compare values with user-defined operator
				 *
				 * @param bool   $result        The comparison result
				 * @param mixed  $master_value  The current value of the master field or default value
				 * @param string $operator      Operator
				 * @param mixed  $compare_value The required value to make dependent field visible
				 */
				$result = apply_filters_ref_array( 'equip/required/compare', [
					$result,
					$master_value,
					$operator,
					$compare_value
				] );
				break;
		}

		return (bool) $result;
	}

	/**
	 * Check if current $key is present in $values
	 * If $key is missing the default field value will be used
	 *
	 * @param string                    $key    Field key
	 * @param \Equip\Layout\FieldLayout $field  Field layout
	 * @param array                     $values Element values
	 *
	 * @return mixed
	 */
	private function get_value( $key, $field, $values ) {
		$settings = $field->get_settings();
		if ( array_key_exists( $key, $values ) ) {
			$value = $values[ $key ];
		} elseif ( array_key_exists( 'default', $settings ) ) {
			// make sure the default is not removed by filter
			// @see FieldLayout::get_defaults
			$value = $settings['default'];
		} else {
			$value = null;
		}

		return $value;
	}
}