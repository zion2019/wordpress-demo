<?php
/**
 * Separator | startapp_separator
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
	'style'        => 'solid',
	'color'        => 'primary',
	'color_custom' => '',
	'border_width' => 1,
	'opacity'      => 25,
	'animation'    => '',
	'class'        => '',
), $shortcode ), $atts );

$style = sanitize_key( $a['style'] );
$color = sanitize_key( $a['color'] );

$class = startapp_get_classes( array(
	'hr-' . $style,
	'hr-' . $color,
	$a['class'],
) );

$attributes             = array();
$attributes['class']    = esc_attr( $class );
$attributes['data-aos'] = ( ! empty( $a['animation'] ) ) ? esc_attr( $a['animation'] ) : '';
$attributes['style']    = startapp_css_declarations( array(
	'border-top-color' => ( 'custom' === $a['color'] ) ? esc_attr( $a['color_custom'] ) : '',
	'border-top-width' => absint( $a['border_width'] ) . 'px',
	'opacity'          => startapp_get_opacity_value( $a['opacity'] ),
) );

echo startapp_get_tag( 'hr', $attributes );
