<?php
/**
 * Teaser | startapp_teaser
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
	'image'               => '',
	'title'               => '',
	'tag'                 => 'h3',
	'alignment'           => '',
	'button_text'         => '',
	'button_link'         => '',
	'button_type'         => 'solid',
	'button_shape'        => 'rounded',
	'button_color'        => 'default',
	'button_color_custom' => '',
	'button_size'         => 'default',
	'button_alignment'    => 'inline',
	'button_is_full'      => 'no',
	'button_is_waves'     => 'disable',
	'button_waves_skin'   => 'light',
	'button_class'        => '',
	'animation'           => '',
	'class'               => '',
), $shortcode ), $atts );

$image     = empty( $a['image'] ) ? '' : wp_get_attachment_image( (int) $a['image'], 'full' );
$title     = esc_html( $a['title'] );
$allowed   = array( 'h1', 'h2', 'h3', 'h4', 'h5', 'h6' );
$tag       = in_array( $a['tag'], $allowed ) ? $a['tag'] : 'h3';
$alignment = esc_attr( $a['alignment'] );
$button    = '';

if ( ! empty( $a['button_text'] ) ) {
	$b = startapp_parse_array( $a, 'button_' );
	$s = startapp_shortcode_build( 'startapp_button', $b );

	$button = startapp_do_shortcode( $s );
}

$class = startapp_get_classes( array(
	'teaser',
	'text-' . $alignment,
	$a['class'],
) );

$attr = startapp_get_attr( array(
	'class'    => esc_attr( $class ),
	'data-aos' => ( ! empty( $a['animation'] ) ) ? esc_attr( $a['animation'] ) : '',
) );

$r = array(
	'{attr}'   => $attr,
	'{image}'  => $image,
	'{title}'  => startapp_get_tag( $tag, array(), $title ),
	'{body}'   => startapp_do_shortcode( $content, true ),
	'{button}' => $button,
);

$tpl = '<div {attr}>{image}{title}{body}{button}</div>';
echo str_replace( array_keys( $r ), array_values( $r ), $tpl );
