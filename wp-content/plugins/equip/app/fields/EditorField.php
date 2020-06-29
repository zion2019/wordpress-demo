<?php

namespace Equip\Field;

/**
 * Show the editor
 *
 * @package Equip\Field
 */
class EditorField extends Field {

	public function render( $slug, $settings, $value ) {
		// default values for $editor_settings
		$defaults = [
			'wpautop'          => true,
			'media_buttons'    => true,
			'rows'             => 10,
			'editor_class'     => '',
			'teeny'            => false,
			'tinymce'          => true,
			'quicktags'        => true,
			'drag_drop_upload' => false,
		];

		$editor_settings = array_intersect_key( $settings, $defaults );
		$editor_settings = wp_parse_args( $editor_settings, $defaults );
		$editor_id       = esc_attr( str_replace( '-', '_', $this->get_id() ) );

		// should not be accessible by user
		$editor_settings['textarea_name'] = esc_attr( $this->get_name() );

		wp_editor( $this->get_value(), $editor_id, $editor_settings );
	}

	public function escape( $value, $settings, $slug ) {
		return wp_kses_post( $value );
	}

	public function sanitize( $value, $settings, $slug ) {
		return wp_kses_post( $value );
	}

	public function get_defaults() {
		return [
			'wpautop'          => true,
			'media_buttons'    => true,
			'rows'             => 10,
			'editor_class'     => '',
			'teeny'            => false,
			'tinymce'          => true,
			'quicktags'        => true,
			'drag_drop_upload' => false,
		];
	}
}