<?php

namespace Equip\Field;

/**
 * Google Font Field
 *
 * Allows users to choose a font from the library.
 * This field generates the link and font-family.
 *
 * Requires the jquery.equipGoogleFonts.js and google-fonts.json
 *
 * User can specify their own link to google-fonts.json file.
 * Note, that custom list should be generated from the Google Fonts API.
 * @link    https://github.com/jonathantneal/google-fonts-complete
 *
 *
 * Settings:
 * - allow-search  bool   true
 * - include-fonts array  []
 * - exclude-fonts array  []
 * - google-fonts  string ''
 *
 * @author  8guild
 * @package Equip\Field
 */
class GoogleFontsField extends Field {

	/**
	 * Field template
	 *
	 * @var string
	 */
	protected $template = '
	<div class="equip-google-fonts-wrapper" id="{id}">
		<input type="text" class="equip-font-link" name="{lk_name}" value="{lk_value}" placeholder="{lk_placeholder}">
		<input type="text" class="equip-font-family" name="{ff_name}" value="{ff_value}" placeholder="{ff_placeholder}">
		<a href="#" class="equip-font-button" {attr} {settings} tabindex="-1">{choose_btn}</a>
	</div>
	';

	public function render( $slug, $settings, $value ) {
		$options = wp_parse_args( (array) $settings['settings'], $this->getDefaultSettings() );

		if ( empty( $options['google-fonts'] ) ) {
			/**
			 * This filter allows to override the default google-fonts.json file
			 *
			 * NOTE: you have to keep the structure! The list of
			 * fonts have to be generated from the Google Fonts API.
			 * @link https://github.com/jonathantneal/google-fonts-complete
			 *
			 * @param string $path URL to google-fonts.json
			 */
			$options['google-fonts'] = apply_filters( 'equip/fonts', EQUIP_ASSETS_URI . '/fields/google_fonts/google-fonts.json' );
		}

		$options = equip_convert_to_data_attr( $options );
		$name    = $this->get_name();

		if ( empty( $value ) || ! is_array( $value ) ) {
			$value = [ 'link' => '', 'ff' => '' ];
		}

		$r = [
			'{lk_name}'        => esc_attr( $name . '[link]' ),
			'{lk_value}'       => $value['link'],
			'{ff_name}'        => esc_attr( $name . '[ff]' ),
			'{ff_value}'       => $value['ff'],
			'{id}'             => esc_attr( $this->get_id() ),
			'{attr}'           => $this->get_attr(),
			'{settings}'       => equip_get_attr( $options ),
			'{choose_btn}'     => esc_html__( 'Choose', 'equip' ),
			'{lk_placeholder}' => esc_html__( 'Google Fonts Link', 'equip' ),
			'{ff_placeholder}' => esc_html__( 'font-family', 'equip' ),
		];

		echo str_replace( array_keys( $r ), array_values( $r ), $this->template );
	}

	/**
	 * Sanitize the Google Font field before saving to database
	 *
	 * @param mixed  $value
	 * @param array  $settings
	 * @param string $slug
	 *
	 * @return array [link, ff]
	 */
	public function sanitize( $value, $settings, $slug ) {
		$value = wp_parse_args( (array) $value, [ 'link' => '', 'ff' => '' ] );

		$value['link'] = esc_url_raw( $value['link'] );
		$value['ff']   = sanitize_text_field( $value['ff'] );

		return (array) $value;
	}

	/**
	 * Escape the Google Font field before rendering
	 *
	 * @param mixed  $value
	 * @param array  $settings
	 * @param string $slug
	 *
	 * @return array [link, ff]
	 */
	public function escape( $value, $settings, $slug ) {
		$value = wp_parse_args( (array) $value, [ 'link' => '', 'ff' => '' ] );

		$value['link'] = esc_url( $value['link'] );
		$value['ff']   = esc_attr( stripslashes( $value['ff'] ) );

		return (array) $value;
	}

	public function getDefaultSettings() {
		return [
			'show-search'   => true,
			'include-fonts' => [],
			'exclude-fonts' => [],
		];
	}
}