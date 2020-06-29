<?php

namespace Equip\Service;

use Equip\Factory;
use Equip\Layout\FieldLayout;

/**
 * Sanitize values
 *
 * @author  8guild
 * @package Equip\Service
 */
class Sanitizer {

	/**
	 * Sanitize all values at once.
	 *
	 * @param array                $values Values
	 * @param \Equip\Layout\Layout $layout Element layout
	 * @param string               $slug   Element unique name
	 *
	 * @return array
	 */
	public function bulk_sanitize( $values, $layout, $slug ) {
		// if $slug or $contents not given returns $values AS IS
		if ( empty( $slug ) || empty( $layout ) ) {
			return $values;
		}

		// loop through the $layout and find fields
		// then convert $layout into format [key => $settings],
		// where key is a possible value in $values array
		$fields = equip_layout_get_fields( $layout );

		$sanitized = array();
		foreach ( (array) $values as $key => $value ) {
			// strange situation, value is present in POST, but
			// missing in current fields set, is this possible?
			if ( ! array_key_exists( $key, $fields ) ) {
				continue;
			}

			// create field object based on settings
			/** @var \Equip\Layout\FieldLayout $element */
			$element  = $fields[ $key ];
			$settings = $element->get_settings();
			$field    = Factory::field( $settings );

			$sanitized[ $key ] = $this->sanitize( $value, $settings, $field, $slug );
		}

		return $sanitized;
	}

	/**
	 * Sanitize the single field before it will be saved into database.
	 *
	 * Custom sanitizing callback has a highest priority. If custom
	 * callback not defined, use built-in field-specific sanitizing,
	 * if exists.
	 *
	 * Also, filter is available before returning the value.
	 *
	 * @param mixed              $value    Field value
	 * @param array              $settings Field settings
	 * @param \Equip\Field\Field $field    Field object
	 * @param string             $slug     Current element unique name
	 *
	 * @return mixed
	 */
	public function sanitize( $value, $settings, $field, $slug ) {
		if ( is_callable( $settings['sanitize'] ) ) {
			$value = call_user_func( $settings['sanitize'], $value );
		} elseif ( is_callable( array( $field, 'sanitize' ) ) ) {
			$value = $field->sanitize( $value, $settings, $slug );
		}

		/**
		 * Filter the value of field before it will be saved into the database.
		 *
		 * This hook fires only for unique element by its slug.
		 *
		 * @param mixed $value    Value of current field
		 * @param array $settings Field settings
		 */
		$value = apply_filters( "equip/sanitize/{$slug}", $value, $settings );

		/**
		 * Filter the value of field before it will be saved into the database
		 *
		 * The dynamic part refers to a field type
		 *
		 * @param mixed  $value    Value of current field
		 * @param array  $settings Field settings
		 * @param string $slug     Element name
		 */
		$value = apply_filters( "equip/sanitize/{$settings['field']}", $value, $settings, $slug );

		/**
		 * Filter the value of field before it will be saved into the database.
		 *
		 * This hook fires for each element.
		 *
		 * @param mixed  $value    Value of current field.
		 * @param array  $settings Field settings
		 * @param string $slug     Unique element name, like meta box or page slug.
		 */
		$value = apply_filters( 'equip/sanitize', $value, $settings, $slug );

		return $value;
	}

	/**
	 * Returns fields from passed Layout in format [key => [settings], ...]
	 *
	 * TODO: equip_layout_get_fields
	 *
	 * @param \Equip\Layout\Layout $layout Element layout
	 * @param array                $fields Fields from layout. Only for internal usage!
	 *
	 * @return array
	 */
	private function get_fields( $layout, $fields = [] ) {
		if ( empty( $layout->elements ) ) {
			return $fields;
		}

		foreach ( $layout->elements as $field ) {
			if ( $field instanceof FieldLayout ) {
				$key = $field->get_setting( 'key' );
				if ( empty( $key ) ) {
					continue;
				}

				$fields[ $key ] = $field->get_settings();
			} else {
				$fields = $this->get_fields( $field, $fields );
			}
		}

		return $fields;
	}
}