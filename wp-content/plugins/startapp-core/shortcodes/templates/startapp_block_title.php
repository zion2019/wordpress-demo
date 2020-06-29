<?php
/**
 * Block Title | startapp_block_title
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
	'title'        => '',
	'subtitle'     => '',
	'tag_title'    => 'h2',
	'tag_subtitle' => 'h4',
	'alignment'    => 'left',
	'skin'         => 'dark',
	'animation'    => '',
	'class'        => '',
), $shortcode ), $atts );

$title    = esc_html( $a['title'] );
$subtitle = esc_html( $a['subtitle'] );

$is_subtitle = ( ! empty( $subtitle ) );

$allowed_tags = array( 'h1', 'h2', 'h3', 'h4', 'h5', 'h6' );
$tag_title    = in_array( $a['tag_title'], $allowed_tags, true ) ? $a['tag_title'] : 'h2';
$tag_subtitle = in_array( $a['tag_subtitle'], $allowed_tags, true ) ? $a['tag_subtitle'] : 'h4';
$skin         = esc_attr( $a['skin'] );
$alignment    = esc_attr( $a['alignment'] );
$animation    = ( ! empty( $a['animation'] ) ) ? esc_attr( $a['animation'] ) : '';

$class = startapp_get_classes( array(
	'block-title',
	'text-' . $skin,
	'text-' . $alignment,
	$a['class'],
) );

if ( $is_subtitle ) {
	$subtitle = startapp_get_tag( 'small', array( 'class' => $tag_subtitle ), $subtitle );
}

echo startapp_get_tag( $tag_title, array(
	'class'    => $class,
	'data-aos' => $animation,
), $title . $subtitle );
