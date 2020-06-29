<?php

namespace Equip\Field;

/**
 * Socials Field
 *
 * Displays the control which allow to choose
 * the social network and associate a link with it.
 * For example, this may be used on the frontend to
 * display the social networks with fancy icons.
 *
 * Requires jquery.equipSocials.js
 *
 * Settings:
 * - networks    array  A list of networks slugs, see /misc/socials.ini
 * - more-label  string Text on the "Add one more" button
 * - placeholder string Placeholder for "URL" field
 *
 * TODO: possible new settings
 * - exclude-networks array
 * - sortable         bool
 *
 * @author  8guild
 * @package Equip\Field
 */
class SocialsField extends Field {
	/**
	 * Field template
	 *
	 * @var string
	 */
	protected $template = '<input type="hidden" name="{name}" id="{id}" value="{val}" {attr} {settings}>';

	public function render( $slug, $settings, $value ) {
		$options = wp_parse_args( $settings['settings'], $this->getDefaultSettings() );

		// make sure networks provided
		if ( empty( $options['networks'] ) ) {
			$networks = equip_get_networks();
			if ( empty( $networks ) ) {
				// TODO: throw an exception
				return;
			}

			$_networks = [];
			array_walk( $networks, function ( $data, $network ) use ( &$_networks ) {
				$_networks[ $network ] = $data['name'];
			} );

			$options['networks'] = $_networks;
		}

		$options = equip_convert_to_data_attr( $options );

		$r = [
			'{name}'     => esc_attr( $this->get_name() ),
			'{id}'       => esc_attr( $this->get_id() ),
			'{val}'      => $value,
			'{attr}'     => $this->get_attr(),
			'{settings}' => equip_get_attr( $options ),
		];

		echo str_replace( array_keys( $r ), array_values( $r ), $this->template );
	}

	/**
	 * Convert input to more suitable format
	 *
	 * Input format should be:
	 * network|url,facebook|http://...,twitter|#,linkedin|#
	 *
	 * Output format:
	 * [network => url, facebook => http://..., twitter => #, linkedin => #]
	 *
	 * @param string $value    Socials data, user input
	 * @param array  $settings Settings
	 * @param string $slug     Slug
	 *
	 * @return array
	 */
	public function sanitize( $value, $settings, $slug ) {
		if ( empty( $value ) ) {
			return [];
		}

		$sanitized = [];
		$pairs     = explode( ',', $value );
		$networks  = array_keys( equip_get_networks() );
		foreach ( (array) $pairs as $pair ) {
			list( $network, $url ) = explode( '|', $pair );

			// skip empty links
			if ( empty( $url ) ) {
				continue;
			}

			// do not allow unknown networks
			if ( ! in_array( $network, $networks, true ) ) {
				continue;
			}

			$url     = preg_match( '@^https?://@i', $url ) ? esc_url_raw( $url ) : esc_attr( $url );
			$network = esc_attr( $network );

			$sanitized[ $network ] = $url;
			unset( $network, $url );
		}

		return $sanitized;
	}

	/**
	 * Convert socials data from database in format,
	 * required by equipSocials.js to work properly
	 *
	 * Output format should be:
	 * network|url,facebook|http://...,twitter|#
	 *
	 * @param array  $value    Socials data from database
	 * @param array  $settings Field settings
	 * @param string $slug     Element name
	 *
	 * @return string
	 */
	public function escape( $value, $settings, $slug ) {
		if ( ! is_array( $value ) || empty( $value ) ) {
			return '';
		}

		$pairs = [];
		foreach ( (array) $value as $network => $url ) {
			$network = esc_attr( $network );
			$url     = preg_match( '@^https?://@i', $url ) ? esc_url_raw( $url ) : esc_attr( $url );

			$pairs[] = implode( '|', [ $network, $url ] );
			unset( $network, $url );
		}

		return implode( ',', $pairs );
	}

	public function get_defaults() {
		return [
			'settings' => [],
		];
	}

	public function get_default_attr() {
		return [
			'class' => 'equip-socials',
		];
	}

	protected function getDefaultSettings() {
		return [
			'networks'    => [],
			'more-label'  => esc_html__( 'Add one more', 'equip' ),
			'placeholder' => esc_html__( 'Network URL', 'equip' ),
		];
	}
}
