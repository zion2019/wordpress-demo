<?php

namespace Equip\Field;

/**
 * Image select field
 *
 * @author  8guild
 * @package Equip\Field
 */
class ImageSelectField extends Field {

	public function render( $slug, $settings, $value ) {
		// open .equip-image-select-unit
		echo '<div ', $this->get_attr(), '>';
		echo $this->get_options();

		// hidden input to keep a real value
		printf( '<input type="hidden" name="%1$s" id="%2$s" value="%3$s">',
			esc_attr( $this->get_name() ),
			esc_attr( $this->get_id() ),
			$value
		);

		// close .equip-image-select-unit
		echo '</div>';
	}

	public function get_options() {
		$options = '';

		$width  = $this->get_setting( 'width', 150 );
		$height = $this->get_setting( 'height', 150 );

		// prepare the template
		$tpl = '<div id="{id}" class="{class}" data-value="{val}">{img}{label}</div>';

		foreach ( (array) $this->get_setting( 'options' ) as $key => $data ) {
			$is_active = ( (string) $this->get_value() === (string) $key );
			$data      = wp_parse_args( $data, [ 'src' => '', 'label' => '' ] );
			$src       = esc_url( $data['src'] );
			$alt       = esc_attr( $data['label'] );
			$label     = esc_html( $data['label'] );
			$class     = [ 'equip-image-select', $is_active ? 'active' : '' ];
			$key       = esc_attr( $key );

			$image = [
				'{id}'    => equip_get_unique_id( 'equip-image-select-' ) . '-' . $key,
				'{val}'   => $key,
				'{class}' => equip_get_class_set( $class ),
				'{img}'   => equip_get_tag( 'img', [ 'src'    => $src,
				                                     'alt'    => $alt,
				                                     'width'  => $width,
				                                     'height' => $height
				] ),
				'{label}' => equip_get_text( $label, '<span class="is-label">', '</span>' ),
			];

			$options .= str_replace( array_keys( $image ), array_values( $image ), $tpl );
			unset( $is_active, $src, $alt, $label, $class, $image );
		}
		unset( $key, $data );

		return $options;
	}

	public function sanitize( $value, $settings, $slug ) {
		return esc_attr( $value );
	}

	public function escape( $value, $settings, $slug ) {
		return esc_attr( $value );
	}

	public function get_default_attr() {
		return [
			'class' => 'equip-image-select-unit',
		];
	}

	public function get_defaults() {
		return [
			'options' => [],
			'width'   => '',
			'height'  => '',
		];
	}
}