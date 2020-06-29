<?php

namespace Equip\Field;

/**
 * Textarea field
 *
 * @author  8guild
 * @package Equip\Field
 */
class TextareaField extends Field {

	/**
	 * @param string $slug
	 * @param array  $settings
	 * @param mixed  $value
	 */
	public function render( $slug, $settings, $value ) {

		// @since 0.7.4 Add icon support
		if ( ! empty( $settings['icon'] ) ) {
			$template = '
			<div class="equip-field-icon">
				<textarea name="{name}" id="{id}" {attr}>{value}</textarea>
				<i class="{icon}"></i>
			</div>
			';
		} else {
			$template = '<textarea name="{name}" id="{id}" {attr}>{value}</textarea>';
		}

		$r = [
			'{name}'  => esc_attr( $this->get_name() ),
			'{id}'    => esc_attr( $this->get_id() ),
			'{value}' => $value,
			'{attr}'  => $this->get_attr(),
			'{icon}'  => esc_attr( $settings['icon'] ),
		];

		echo str_replace( array_keys( $r ), array_values( $r ), $template );
	}

	public function sanitize( $value, $settings, $slug ) {
		// Get allowed HTML tags for wp_kses()
		$allowed_tags = wp_kses_allowed_html( 'data' );
		$value        = stripslashes( trim( $value ) );

		return wp_kses( $value, $allowed_tags );
	}

	public function escape( $value, $settings, $slug ) {
		// Get allowed HTML tags for wp_kses()
		$allowed_tags = wp_kses_allowed_html( 'data' );
		$value        = stripslashes( trim( $value ) );

		return wp_kses( $value, $allowed_tags );
	}

	public function get_defaults() {
		return [
			'icon' => '',
		];
	}

	public function get_default_attr() {
		return [
			'rows' => 6,
		];
	}
}