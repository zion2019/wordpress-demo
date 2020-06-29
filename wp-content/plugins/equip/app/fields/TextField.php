<?php

namespace Equip\Field;

/**
 * Text field
 *
 * @author  8guild
 * @package Equip\Field
 */
class TextField extends Field {

	public function render( $slug, $settings, $value ) {
		// if icon is set
		$is_icon = ( ! empty( $settings['icon'] ) );

		// templates with and witout icon
		$without = '<input type="text" name="{name}" id="{id}" value="{value}" {attr}>';
		$with    = <<<'WITH'
<div class="equip-field-icon">
	<input type="text" name="{name}" id="{id}" value="{value}" {attr}>
	<i class="{icon}"></i>
</div>
WITH;

		$r = [
			'{name}'  => esc_attr( $this->get_name() ),
			'{id}'    => esc_attr( $this->get_id() ),
			'{value}' => $value,
			'{attr}'  => $this->get_attr(),
			'{icon}'  => esc_attr( $settings['icon'] ),
		];

		echo str_replace( array_keys( $r ), array_values( $r ), $is_icon ? $with : $without );
	}

	public function sanitize( $value, $settings, $slug ) {
		return sanitize_text_field( stripslashes( $value ) );
	}

	public function escape( $value, $settings, $slug ) {
		return esc_attr( stripslashes( $value ) );
	}

	public function get_defaults() {
		return [
			'icon' => '',
		];
	}
}