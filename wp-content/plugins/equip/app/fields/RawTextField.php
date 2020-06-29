<?php

namespace Equip\Field;

/**
 * Show the raw text
 *
 * Useful for messages
 *
 * @package Equip\Field
 */
class RawTextField extends Field {

	public function render( $slug, $settings, $value ) {
		echo equip_get_tag( 'div', $this->get_attr_array(), $value, 'paired' );
	}

	public function get_default_attr() {
		return [
			'class' => 'equip-raw-text',
		];
	}

	public function escape( $value, $settings, $slug ) {
		return wp_kses( $value, [
			'h1'         => [ 'class' => true ],
			'h2'         => [ 'class' => true ],
			'h3'         => [ 'class' => true ],
			'h4'         => [ 'class' => true ],
			'h5'         => [ 'class' => true ],
			'h6'         => [ 'class' => true ],
			'p'          => [ 'class' => true ],
			'span'       => [ 'class' => true ],
			'em'         => [ 'class' => true ],
			'i'          => [ 'class' => true ],
			'strong'     => [ 'class' => true ],
			'b'          => [ 'class' => true ],
			'blockquote' => [ 'class' => true ],
			'hr'         => [ 'class' => true ],
		] );
	}
}