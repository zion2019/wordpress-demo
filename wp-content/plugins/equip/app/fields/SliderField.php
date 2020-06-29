<?php

namespace Equip\Field;

/**
 * Slider field
 *
 * @author  8guild
 * @package Equip\Field
 */
class SliderField extends Field {

	public function render( $slug, $settings, $value ) {
		// make sure value is integer, may be null
		$value = (int) $value;

		// Field ID
		$field_id = esc_attr( $this->get_id() );

		// slider settings
		$slider = [];

		$slider['class']        = 'equip-slider';
		$slider['data-id']      = $field_id;
		$slider['data-min']     = $this->get_setting( 'min' );
		$slider['data-max']     = $this->get_setting( 'max' );
		$slider['data-step']    = $this->get_setting( 'step' );
		$slider['data-current'] = $value;

		?>
		<div <?php echo $this->get_attr(); ?>>
			<div class="column">
				<div <?php echo equip_get_attr( $slider ); ?>></div>
			</div>
			<div class="column">
				<?php
				/*
				 * 1 - name
				 * 2 - id
				 * 3 - value
				 */
				printf( '<input type="text" name="%1$s" id="%2$s" value="%3$s">',
					esc_attr( $this->get_name() ),
					$field_id,
					$value
				);
				?>
			</div>
			<div class="column">
				<div class="units"><?php echo esc_html( $settings['units'] ); ?></div>
			</div>
		</div>
		<?php
	}

	public function sanitize( $value, $settings, $slug ) {
		return (float) filter_var( $value, FILTER_SANITIZE_NUMBER_FLOAT, FILTER_FLAG_ALLOW_FRACTION );
	}

	public function escape( $value, $settings, $slug ) {
		return (float) filter_var( $value, FILTER_SANITIZE_NUMBER_FLOAT, FILTER_FLAG_ALLOW_FRACTION );
	}

	public function get_default_attr() {
		return [
			'class' => 'equip-slider-unit',
		];
	}

	public function get_defaults() {
		return [
			'min'   => 0,
			'max'   => 100,
			'step'  => 1,
			'units' => 'px',
		];
	}
}