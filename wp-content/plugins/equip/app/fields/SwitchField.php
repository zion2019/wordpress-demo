<?php

namespace Equip\Field;

/**
 * Switch field
 *
 * @author  8guild
 * @package Equip\Field
 */
class SwitchField extends Field {

	public function render( $slug, $settings, $value ) {
		$class = [];

		$class[] = 'equip-switch';
		$class[] = array_key_exists( 'class', $settings['attr'] ) ? $settings['attr']['class'] : '';
		$class[] = ( ! empty( $value ) ) ? 'on' : '';

		// get attributes, but remove class, we use it above
		$raw_attr = $this->get_attr_array();
		if ( array_key_exists( 'class', $raw_attr ) ) {
			unset( $raw_attr['class'] );
		}

		$attr = array_merge(
			[ 'class' => equip_get_class_set( $class ) ],
			$raw_attr
		);

		// open .equip-switch
		echo '<div ', equip_get_attr( $attr ), '>';
		?>
		<div class="switch-body">
			<div class="knob"></div>
		</div>
		<?php
		printf( '<input type="hidden" name="%1$s" id="%2$s" value="%3$s">',
			esc_attr( $this->get_name() ),
			esc_attr( $this->get_id() ),
			$this->get_value()
		);

		// close .equip-switch
		echo '</div>';
	}

	public function sanitize( $value, $settings, $slug ) {
		return (int) $value;
	}

	public function escape( $value, $settings, $slug ) {
		return (int) $value;
	}
}