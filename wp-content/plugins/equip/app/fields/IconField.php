<?php

namespace Equip\Field;

/**
 * Icon picker
 *
 * @author  8guild
 * @package Equip\Field
 */
class IconField extends Field {

	/**
	 * Field template
	 *
	 * @var string
	 */
	protected $template = '<input type="text" name="{name}" id="{id}" value="{value}" {attr} {settings}>';

	public function render( $slug, $settings, $value ) {
		// picker settings
		$options = wp_parse_args( (array) $settings['settings'], $this->getDefaultSettings() );

		if ( empty( $options['source'] ) ) {
			/**
			 * Filter the sources for icons packs.
			 *
			 * This filter expects the array in format: [ slug => name ],
			 * where `slug` is a unique name of the pack and `name` is what
			 * user will see in a popup.
			 *
			 * NOTE: the dynamic part of `equip/icons/{{source}}` filter
			 * is referred to a `slug` key.
			 *
			 *
			 * @param array $icons A list of icons
			 */
			$options['source'] = apply_filters( 'equip/icon/source', [] );
		}

		$options = equip_convert_to_data_attr( $options );

		$r = [
			'{name}'     => esc_attr( $this->get_name() ),
			'{id}'       => esc_attr( $this->get_id() ),
			'{value}'    => $value,
			'{attr}'     => $this->get_attr(),
			'{settings}' => equip_get_attr( $options ),
		];

		echo str_replace( array_keys( $r ), array_values( $r ), $this->template );
	}

	public function sanitize( $value, $settings, $slug ) {
		return sanitize_text_field( $value );
	}

	public function escape( $value, $settings, $slug ) {
		return esc_attr( $value );
	}

	public function get_default_attr() {
		return [
			'class' => 'equip-icon',
		];
	}

	private function getDefaultSettings() {
		return [
			'show-search'    => true,
			'source'         => [],
			'exclude-source' => [],
		];
	}
}