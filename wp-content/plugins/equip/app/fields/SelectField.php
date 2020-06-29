<?php

namespace Equip\Field;

/**
 * Select field
 *
 * @since   0.7.4 Convert to a normal select without select2.js
 *
 * @author  8guild
 * @package Equip\Field
 */
class SelectField extends Field {

	public function render( $slug, $settings, $value ) {
		$attr = [];

		$attr['id']   = esc_attr( $this->get_id() );
		$attr['name'] = esc_attr( $this->get_name() );

		// merge with defaults and ones defined by user
		$attr  = array_merge( $attr, $this->get_attr_array() );
		$value = (array) $value;

		echo '<div class="equip-select-wrap">';
		echo '<select ', equip_get_attr( $attr ), '>';
		foreach ( (array) $settings['options'] as $v => $name ) {
			echo sprintf( '<option value="%1$s" %3$s>%2$s</option>', $v, $name,
				in_array( (string) $v, $value ) ? 'selected' : ''
			);
		}
		echo '</select>';
		echo '</div>';
	}

	public function sanitize( $value, $settings, $slug ) {
		if ( array_key_exists( 'multiple', $settings['attr'] ) && $settings['attr']['multiple'] ) {
			$value = array_map( 'esc_attr', $value );
		} else {
			$value = esc_attr( $value );
		}

		return $value;
	}

	public function escape( $value, $settings, $slug ) {
		if ( array_key_exists( 'multiple', $settings['attr'] ) && $settings['attr']['multiple'] ) {
			$value = array_map( 'esc_attr', (array) $value );
		} else {
			$value = esc_attr( $value );
		}

		return $value;
	}

	public function get_defaults() {
		return [
			'options' => [],
		];
	}

	public function get_default_attr() {
		return [
			'class' => 'equip-select',
		];
	}

	/**
	 * Detects whether this instance of a select field declared as multiple
	 *
	 * @return bool
	 */
	public function is_multiple() {
		$settings = $this->get_settings();

		return array_key_exists( 'multiple', $settings['attr'] ) && $settings['attr']['multiple'];
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
		if ( $this->is_multiple() ) {
			$name = $name . '[]';
		}

		/**
		 * Filter the each field's name attribute
		 *
		 * @param string             $name Name. Default is slug[key]
		 * @param \Equip\Field\Field $this Equip field object
		 */
		return apply_filters( 'equip/field/name', $name, $this );
	}
}
