<?php
/**
 * Animated Digits | startapp_animated_digits
 *
 * @var string $shortcode Shortcode tag
 * @var array  $atts      Shortcode attributes
 * @var mixed  $content   Shortcode content
 *
 * @author 8guild
 */

if ( ! defined( 'ABSPATH' ) ) {
	die;
}

/**
 * Filter the default shortcode attributes
 *
 * @param array  $atts      Pairs of default attributes
 * @param string $shortcode Shortcode tag
 */
$a = shortcode_atts( apply_filters( 'startapp_shortcode_default_atts', array(
	'digit'        => '',
	'unit'         => '',
	'description'  => '',
	'color'        => 'default',
	'color_custom' => '',
	'animation'    => '',
	'class'        => '',
), $shortcode ), $atts );

if ( empty( $a['digit'] ) ) {
	return;
}

$is_custom    = ( 'custom' === $a['color'] );
$color        = sanitize_key( $a['color'] );
$color_custom = sanitize_hex_color( $a['color_custom'] );

$box_custom_color   = '';
$digit_custom_color = '';
if ( $is_custom ) {
	$box_custom_color = startapp_color_rgba( $color_custom, '.05' ); // 5% alpha channel hard-coded
	$box_custom_color = startapp_css_background_color( $box_custom_color );

	$digit_custom_color = startapp_css_color( $color_custom );
}

$class = startapp_get_classes( array(
	'animated-digit-box',
	'skin-' . $color,
	$a['class'],
) );

$box_attr = array(
	'class'    => esc_attr( $class ),
	'data-aos' => ( ! empty( $a['animation'] ) ) ? esc_attr( $a['animation'] ) : '',
	'style'    => $box_custom_color,
);

$digit_attr = array(
	'class' => 'animated-digit',
	'style' => $digit_custom_color,
);

$template = <<<'TEMPLATE'
<div {box-attr}>
	<div {digit-attr}>
		{digit}{unit}
	</div>
	{description}
</div>
TEMPLATE;

$r = array(
	'{box-attr}'    => startapp_get_attr( $box_attr ),
	'{digit-attr}'  => startapp_get_attr( $digit_attr ),
	'{digit}'       => startapp_get_text( absint( $a['digit'] ), '<span class="digit">', '</span>' ),
	'{unit}'        => startapp_get_text( esc_html( $a['unit'] ), '<span class="unit">', '</span>' ),
	'{description}' => startapp_get_text( esc_html( $a['description'] ), '<p class="description">', '</p>' ),
);

echo str_replace( array_keys( $r ), array_values( $r ), $template );
