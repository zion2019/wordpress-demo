<?php
/**
 * Video Popup | startapp_video_popup
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
	'video'     => '',
	'alignment' => 'center',
	'skin'      => 'dark',
	'animation' => '',
	'class'     => '',
), $shortcode ), $atts );

if ( empty( $a['video'] ) ) {
	return;
}

$video     = esc_url( trim( $a['video'] ) );
$alignment = sanitize_key( $a['alignment'] );
$skin      = sanitize_key( $a['skin'] );
$class     = startapp_get_classes( array(
	'video-popup',
	'text-' . $skin,
	'text-' . $alignment,
	$a['class'],
) );

$attr = startapp_get_attr( array(
	'class'    => esc_attr( $class ),
	'data-aos' => ( ! empty( $a['animation'] ) ) ? esc_attr( $a['animation'] ) : '',
) );

$video_attr = array(
	'href'  => $video,
	'class' => 'video-popup-btn',
);

echo '<div ', $attr, '>';
echo startapp_get_tag( 'a', $video_attr, '<i class="material-icons play_circle_filled"></i>' );
echo '</div>';
