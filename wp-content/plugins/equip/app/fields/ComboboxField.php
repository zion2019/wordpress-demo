<?php

namespace Equip\Field;

/**
 * Combobox field
 *
 * TODO: refactoring required
 * TODO: add more options
 *
 * @since   0.7.4
 * @since   0.7.7 Now using to selectize.js
 *
 * @author  8guild
 * @package Equip\Field
 */
class ComboboxField extends Field {

	public function render( $slug, $settings, $value ) {
		// init the attributes array
		$attr = [];

		$attr['id']   = esc_attr( $this->get_id() );
		$attr['name'] = esc_attr( $this->get_name() );
		if ( array_key_exists( 'multiple', $settings['attr'] ) && true === $settings['attr']['multiple'] ) {
			$attr['name'] = $attr['name'] . '[]';
		}

		if ( true === $this->get_setting( 'searchable' ) ) {
			$attr['data-searchable'] = 'true';
		}

		// TODO: deprecated, remove since selectize.js
		if ( '' !== $this->get_setting( 'placeholder' ) ) {
			$attr['data-placeholder'] = $this->get_setting( 'placeholder' );
		}

		// merge with defaults and ones defined by user
		$attr = array_merge( $attr, $this->get_attr_array() );

		echo '<select ', equip_get_attr( $attr ), '>';
		echo $this->get_options();
		echo '</select>';
	}

	public function sanitize( $value, $settings, $slug ) {
		if ( is_array( $value ) ) {
			$sanitized = array_map( 'esc_attr', $value );
		} else {
			$sanitized = esc_attr( $value );
		}

		return $sanitized;
	}

	public function escape( $value, $settings, $slug ) {
		if ( is_array( $value ) ) {
			$sanitized = array_map( 'esc_attr', $value );
		} else {
			$sanitized = esc_attr( $value );
		}

		return $sanitized;
	}

	/**
	 * Return the options for select field
	 *
	 * @return string
	 */
	private function get_options() {
		$options = '';
		$value   = (array) $this->get_value();

		foreach ( (array) $this->get_setting( 'options', [] ) as $val => $name ) {
			$options .= sprintf( '<option value="%1$s" %3$s>%2$s</option>',
				$val,
				$name,
				in_array( $val, $value ) ? 'selected' : ''
			);
		}

		return $options;
	}

	public function get_defaults() {
		return [
			'options'     => [],
			'placeholder' => '',
			'searchable'  => false,
		];
	}

	public function get_default_attr() {
		return [
			'class' => 'equip-combobox',
		];
	}
}