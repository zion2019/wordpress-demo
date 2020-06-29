<?php

namespace Equip\Field;

/**
 * Show the CodeMirror Editor for JS
 *
 * @since   0.7.5
 *
 * @package Equip\Field
 */
class CustomCssField extends Field {

	public function render( $slug, $settings, $value ) {
		$t = '<textarea name="{name}" id="{id}" {attr}>{value}</textarea>';
		$r = [
			'{name}'  => esc_attr( $this->get_name() ),
			'{id}'    => esc_attr( $this->get_id() ),
			'{value}' => $value,
			'{attr}'  => $this->get_attr(),
		];

		echo str_replace( array_keys( $r ), array_values( $r ), $t );
	}

	public function escape( $value, $settings, $slug ) {
		return strip_tags( stripslashes( trim( $value ) ) );
	}

	public function sanitize( $value, $settings, $slug ) {
		//return sanitize_text_field( $value );
		return strip_tags( trim( $value ) );
	}
}