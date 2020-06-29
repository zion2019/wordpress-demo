<?php

namespace Equip\Engine;

use Equip\Equip;
use Equip\Factory;

/**
 * This engine is responsible for rendering the "field" contents
 *
 * @author  8guild
 * @package Equip\Engine
 */
class FieldEngine extends Engine {

	/**
	 * This method will wrap every field
	 * @see \Equip\Engine\Engine::render
	 *
	 * @param string               $slug
	 * @param \Equip\Layout\Layout $layout
	 */
	public function before_elements( $slug, $layout ) {
		return;
	}

	/**
	 * Show the field label and helper
	 *
	 * @param string               $slug
	 * @param array                $settings
	 * @param \Equip\Layout\Layout $layout
	 */
	public function before_element( $slug, $settings, $layout ) {
		// show label
		if ( ! empty( $settings['label'] ) ) {
			$id = sprintf( 'equip-%1$s-%2$s', $slug, $settings['key'] );
			$id = str_replace( '_', '-', $id );

			// remove possible duplicated dashes
			$id = preg_replace( '/--+/', '-', $id );

			echo sprintf( '<label class="equip-field-label" for="%1$s">%2$s</label>',
				esc_attr( $id ),
				esc_html( $settings['label'] )
			);
		}

		// show helper
		if ( ! empty( $settings['helper'] ) ) {
			echo sprintf( '<span class="equip-helper">%s</span>',
				esc_html( $settings['helper'] )
			);
		}
	}

	/**
	 * Render the field
	 *
	 * This method call the {@see \Equip\Field\Field::render}
	 *
	 * @param string                    $slug     Element slug
	 * @param array                     $settings Field settings
	 * @param mixed                     $values   The whole bunch of element values. Usually an array.
	 * @param \Equip\Layout\FieldLayout $layout   Element layout
	 */
	public function do_element( $slug, $settings, $values, $layout ) {
		$key   = $settings['key'];
		$field = Factory::field( $settings );

		// merge settings with defaults to make sure all settings are present
		$settings = wp_parse_args( $settings, $field->get_defaults() );

		/*
		 * Check if current $key is present in $values
		 * If $key is missing the default will be used
		 * Finally the value will be escaped before rendering
		 */
		if ( is_array( $values ) && array_key_exists( $key, $values ) ) {
			$value = $values[ $key ];
		} elseif ( array_key_exists( 'default', $settings ) ) {
			// make sure the default is not removed by filter
			// @see FieldLayout::get_defaults
			$value = $settings['default'];
		} else {
			$value = null;
		}

		/** @var \Equip\Service\Escaper $escaper */
		$escaper = Factory::service( Equip::ESCAPER );
		$value   = $escaper->escape( $value, $settings, $field, $slug );

		$field->setup( $slug, $settings, $value );
		$field->set_context( $layout->reset()->type );
		$field->render( $slug, $settings, $value );
		$field->reset();
	}

	/**
	 * Show the description
	 *
	 * @param string               $slug
	 * @param array                $settings
	 * @param \Equip\Layout\Layout $layout
	 */
	public function after_element( $slug, $settings, $layout ) {
		// show description
		if ( ! empty( $settings['description'] ) ) {
			echo '<p class="equip-description">';
			echo wp_kses( $settings['description'], [
				'i'      => [ 'class' => true ],
				'span'   => [ 'class' => true ],
				'strong' => [ 'class' => true ],
				'em'     => [ 'class' => true ],
				'b'      => [ 'class' => true ],
				'br'     => [ 'class' => true ],
				'a'      => [ 'class' => true, 'href' => true, 'target' => true ],
			] );
			echo '</p>';
		}
	}

	public function after_elements( $slug, $layout ) {
		return;
	}
}