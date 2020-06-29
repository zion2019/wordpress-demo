<?php

namespace Equip\Service;

/**
 * Escaping values
 *
 * @author  8guild
 * @package Equip\Service
 */
class Escaper {

	/**
	 * Escape field before it will be rendered.
	 *
	 * Custom escaping callback has a highest priority. If custom
	 * callback not defined, use built-in field-specific escaping,
	 * if exists.
	 *
	 * Also, filter is available before returning the value.
	 *
	 * @param mixed              $value    Field value
	 * @param array              $settings Field declaration array
	 * @param \Equip\Field\Field $field    Field object
	 * @param string             $slug     Current element unique name
	 *
	 * @return mixed
	 */
	public function escape( $value, $settings, $field, $slug ) {
		if ( is_callable( $settings['escape'] ) ) {
			$value = call_user_func( $settings['escape'], $value );
		} elseif ( is_callable( array( $field, 'escape' ) ) ) {
			$value = $field->escape( $value, $settings, $slug );
		}

		/**
		 * Filter the value of field before it will be rendered.
		 *
		 * Fires only for unique element by its $slug.
		 *
		 * @param mixed $value    Value of current field
		 * @param array $settings Field settings
		 */
		$value = apply_filters( "equip/escape/{$slug}", $value, $settings );

		/**
		 * Filter the value of field before it will be rendered
		 *
		 * The dynamic part refers to the field type
		 *
		 * @param mixed  $value    Value of current field.
		 * @param array  $settings Field settings
		 * @param string $slug     Unique element name, like meta box or page slug.
		 */
		$value = apply_filters( "equip/escape/{$settings['field']}", $value, $settings, $slug );

		/**
		 * Filter the value of each field before it will be rendered
		 *
		 * @param mixed  $value    Value of current field.
		 * @param array  $settings Field settings
		 * @param string $slug     Unique element name, like meta box or page slug.
		 */
		$value = apply_filters( 'equip/escape', $value, $settings, $slug );

		return $value;
	}
}