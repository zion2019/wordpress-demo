<?php
/**
 * Pricing Plan | startapp_pricing
 *
 * @var string $shortcode Shortcode tag
 * @var array  $atts      Shortcode attributes
 * @var mixed  $content   Shortcode content
 *
 * @author 8guild
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/**
 * Filter the default shortcode attributes
 *
 * @param array  $atts      Pairs of default attributes
 * @param string $shortcode Shortcode tag
 */
$a = shortcode_atts( apply_filters( 'startapp_shortcode_default_atts', array(
	'layout'              => 'v1',
	'image'               => '',
	'size'                => 'sm',
	'is_badge'            => 'disable',
	'badge'               => '',
	'badge_color'         => 'default',
	'badge_color_custom'  => '',
	'name'                => '',
	'description'         => '',
	'price'               => '',
	'label'               => '',
	'features'            => '',
	'button_text'         => '',
	'button_link'         => '',
	'button_type'         => 'solid',
	'button_shape'        => 'rounded',
	'button_color'        => 'default',
	'button_color_custom' => '', // rgba or hex?
	'button_size'         => 'default',
	'button_alignment'    => 'inline',
	'button_is_full'      => 'no',
	'button_is_waves'     => 'disable',
	'button_waves_skin'   => 'light',
	'button_class'        => '',
	'animation'           => '',
	'class'               => '',
), $shortcode ), $atts );

$layout      = sanitize_key( $a['layout'] );
$name        = esc_html( $a['name'] );
$description = esc_html( $a['description'] );
$price       = esc_html( $a['price'] );
$label       = esc_html( $a['label'] );
$image       = '';
$badge       = '';
$button      = '';
$features    = '';

// prepare image
if ( ! empty( $a['image'] ) ) {
	$c = startapp_get_classes( array(
		'pricing-plan-image',
		'image-' . sanitize_key( $a['size'] ),
		( 'v1' === $a['layout'] ) ? 'margin-bottom-1x' : '',
	) );

	$image = wp_get_attachment_image( (int) $a['image'], 'full' );
	$image = startapp_get_tag( 'div', array( 'class' => $c ), $image );
	unset( $c );
}

// build button
if ( ! empty( $a['button_text'] ) ) {
	$b = startapp_parse_array( $a, 'button_' );
	$s = startapp_shortcode_build( 'startapp_button', $b );

	$button = startapp_do_shortcode( $s );
	unset( $b, $s );
}

// prepare badge
if ( 'enable' === $a['is_badge'] && ! empty( $a['badge'] ) ) {
	$badge = startapp_get_tag( 'span', array(
		'class' => 'pricing-plan-badge bg-' . esc_attr( $a['badge_color'] ),
		'style' => startapp_css_background_color( esc_attr( $a['badge_color_custom'] ) ),
	), esc_html( $a['badge'] ) );
}

// prepare features
if ( ! empty( $a['features'] ) ) {
	$f = json_decode( urldecode( $a['features'] ), true );
	foreach ( (array) $f as $s ) {
		$o = '';

		if ( ! empty( $s['title'] ) ) {
			$o .= sprintf( '<h5 class="pricing-plan-feature-title">%s</h5>',
				esc_html( $s['title'] )
			);
		}

		if ( ! empty( $s['description'] ) ) {
			$l = array();
			foreach ( explode( "\n", $s['description'] ) as $d ) {
				$l[] = sprintf( '<li>%s</li>', esc_html( $d ) );
			}
			$o .= '<ul class="text-gray">';
			$o .= implode( '', $l );
			$o .= '</ul>';
			unset( $d, $l );
		}

		$features .= $o;
	}
	unset( $f, $s );
}

// prepare classes
$class = startapp_get_classes( array(
	'pricing-plan',
	'pricing-plan-' . $layout,
	in_array( $a['layout'], array( 'v2', 'v3' ) ) ? 'text-center' : '',
) );

// attributes for wrapper, required for animations, @see template parts
$attr = array(
	'class'    => esc_attr( startapp_get_classes( $a['class'] ) ),
	'data-aos' => ( ! empty( $a['animation'] ) ) ? esc_attr( $a['animation'] ) : '',
);

startapp_shortcode_template( "pricing-{$layout}.php", array(
	'attr'        => $attr,
	'class'       => $class,
	'image'       => $image,
	'name'        => trim( $a['name'] ),
	'description' => trim( $a['description'] ),
	'price'       => trim( $a['price'] ),
	'label'       => trim( $a['label'], '/ ' ),
	'badge'       => $badge,
	'features'    => $features,
	'button'      => $button,
) );
