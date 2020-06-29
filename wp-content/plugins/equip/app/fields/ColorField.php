<?php

namespace Equip\Field;

/**
 * Color picker field
 *
 * @link    https://github.com/bgrins/spectrum
 * @link    https://bgrins.github.io/spectrum/
 *
 * @author  8guild
 * @package Equip\Field
 */
class ColorField extends Field {

	/**
	 * Template with placeholders
	 *
	 * @var string
	 */
	protected $template = '<input type="text" name="{name}" id="{id}" value="{value}" {attr} {settings}>';

	public function render( $slug, $settings, $value ) {
		$spectrum = wp_parse_args( $settings['settings'], $this->getDefaultSettings() );
		$spectrum = $this->convertToDataAttributes( $spectrum );

		$data = [
			'{name}'     => esc_attr( $this->get_name() ),
			'{id}'       => esc_attr( $this->get_id() ),
			'{value}'    => $value,
			'{attr}'     => $this->get_attr(),
			'{settings}' => equip_get_attr( $spectrum ),
		];

		echo str_replace( array_keys( $data ), array_values( $data ), $this->template );
	}

	public function get_default_attr() {
		return [
			'class' => 'equip-color'
		];
	}

	/**
	 * Get default values for Spectrum.js plugin options
	 *
	 * @return array
	 */
	protected function getDefaultSettings() {
		return [
			'show-input'             => true,
			'show-initial'           => false,
			'show-alpha'             => false,
			'show-buttons'           => false,
			'allow-empty'            => false,
			'disabled'               => false,
			'show-palette'           => false,
			'show-palette-only'      => false,
			'local-storage-key'      => 'spectrum.equip',
			'toggle-palette-only'    => false,
			'show-selection-palette' => false,
			'palette'                => [],
			'selection-palette'      => [],
			'max-selection-size'     => 10,
			'preferred-format'       => 'hex',
		];
	}

	protected function convertToDataAttributes( $settings ) {
		$keys = array_map( function ( $el ) {
			return 'data-' . (string) $el;
		}, array_keys( $settings ) );

		return array_combine( $keys, array_values( $settings ) );
	}
}
