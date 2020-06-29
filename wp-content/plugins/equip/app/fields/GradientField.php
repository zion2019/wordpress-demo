<?php

namespace Equip\Field;

/**
 * Gradient field
 *
 * Show two color pickers: "from" and "to"
 * for gradient start and stop colors {@see ColorField}
 *
 * @author  8guild
 * @package Equip\Field
 */
class GradientField extends Field {

	protected $template = '
	<div class="equip-gradient-wrap" id="{id}">
		<span style="padding-right: 7px;">{label-from}</span>
		<input type="text" name="{name}[from]" value="{value-from}" {attr} {settings}>
		<span style="padding: 0 7px;">{label-to}</span>
		<input type="text" name="{name}[to]" value="{value-to}" {attr} {settings}>
	</div>
	';

	public function render( $slug, $settings, $value ) {
		$spectrum = wp_parse_args( $settings['settings'], $this->getDefaultSettings() );
		$spectrum = $this->convertToDataAttributes( $spectrum );

		$value = wp_parse_args( (array) $value, [
			'from' => '',
			'to'   => '',
		] );

		$data = [
			'{name}'       => esc_attr( $this->get_name() ),
			'{id}'         => esc_attr( $this->get_id() ),
			'{value-from}' => $value['from'],
			'{value-to}'   => $value['to'],
			'{label-from}' => esc_html__( 'From:', 'equip' ),
			'{label-to}'   => esc_html__( 'To:', 'equip' ),
			'{attr}'       => $this->get_attr(),
			'{settings}'   => equip_get_attr( $spectrum ),
		];

		echo str_replace( array_keys( $data ), array_values( $data ), $this->template );
	}

	public function get_default_attr() {
		return [
			'class' => 'equip-color'
		];
	}

	public function sanitize( $value, $settings, $slug ) {
		return array_map( 'sanitize_hex_color', (array) $value );
	}

	public function escape( $value, $settings, $slug ) {
		return array_map( 'sanitize_hex_color', (array) $value );
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