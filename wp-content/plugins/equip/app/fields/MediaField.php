<?php

namespace Equip\Field;

/**
 * Single image field
 *
 * @author  8guild
 * @package Equip\Field
 */
class MediaField extends Field {

	/**
	 * Field template
	 *
	 * @var string
	 */
	protected $template = '<input type="hidden" name="{name}" id="{id}" value="{value}" {attr} {settings}>';

	public function render( $slug, $settings, $value ) {
		// prepare field settings
		$options = wp_parse_args( $settings['settings'], $this->getDefaultSettings() );

		$options['multiple'] = ( true === $settings['multiple'] ) ? 'true' : 'false';
		$options['sortable'] = ( true === $settings['sortable'] ) ? 'true' : 'false';

		if ( ! empty( $value ) ) {
			$options['preview'] = array_map( function ( $attachment_id ) {
				return esc_url( equip_get_image_src( $attachment_id, 'medium' ) );
			}, (array) $value );
		} else {
			$options['preview'] = '';
		}

		$options = $this->convertToDataAttributes( $options );

		// prepare value
		if ( empty( $value ) ) {
			$value = '';
		}

		if ( is_array( $value ) ) {
			$value = implode( ',', $value );
		}

		$data = [
			'{name}'     => esc_attr( $this->get_name() ),
			'{id}'       => esc_attr( $this->get_id() ),
			'{value}'    => $value,
			'{attr}'     => $this->get_attr(),
			'{settings}' => equip_get_attr( $options ),
		];

		echo str_replace( array_keys( $data ), array_values( $data ), $this->template );
	}

	/**
	 * If "multiple" key is enabled the value will be converted from
	 * comma-separated string into the array of integers. If disabled,
	 * the value will be a single integer
	 *
	 * @param mixed  $value
	 * @param array  $settings
	 * @param string $slug
	 *
	 * @return array|int
	 */
	public function sanitize( $value, $settings, $slug ) {
		if ( array_key_exists( 'multiple', $settings )
		     && true === $settings['multiple']
		) {
			$attachments = explode( ',', $value );
			$attachments = array_filter( $attachments, 'is_numeric' );
			$attachments = array_map( 'intval', $attachments );

			$value = $attachments;
		} else {
			// for single image
			$value = (int) $value;
		}

		return $value;
	}

	/**
	 * If "multiple" key is enabled the returned value will be an array,
	 * else value will be a single integer
	 *
	 * @param mixed  $value
	 * @param array  $settings
	 * @param string $slug
	 *
	 * @return array|int
	 */
	public function escape( $value, $settings, $slug ) {
		if ( array_key_exists( 'multiple', $settings )
		     && true === $settings['multiple']
		) {
			// NOTE: before inserting into the control field value
			// should be converted into the comma-separated string
			$value = (array) $value;
			$value = array_filter( $value, 'is_numeric' );
			$value = array_map( 'intval', $value );
		} else {
			$value = (int) $value;
		}

		return $value;
	}

	public function get_defaults() {
		return [
			'multiple' => false,
			'sortable' => false,
		];
	}

	public function get_default_attr() {
		return [
			'class' => 'equip-media',
		];
	}

	protected function getDefaultSettings() {
		return [
			'title'  => esc_html__( 'Media Library', 'equip' ),
			'button' => esc_html__( 'Select', 'equip' ),
		];
	}

	protected function convertToDataAttributes( $settings ) {
		$keys = array_map( function ( $el ) {
			return 'data-' . (string) $el;
		}, array_keys( $settings ) );

		return array_combine( $keys, array_values( $settings ) );
	}
}