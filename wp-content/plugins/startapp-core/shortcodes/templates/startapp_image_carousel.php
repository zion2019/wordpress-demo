<?php
/**
 * Image Carousel | startapp_image_carousel
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
	'images'          => '',
	'is_caption'      => 'disable',
	'is_arrows'       => 'show', // show | hide
	'arrows_shape'    => 'rounded',
	'arrows_size'     => 'sm',
	'arrows_position' => 'on-sides',
	'is_dots'         => 'show',
	'dots_skin'       => 'dark',
	'dots_position'   => 'outside', // outside | inside
	'dots_alignment'  => 'center',
	'transition'      => 'slide',
	'is_loop'         => 'disable',
	'is_autoplay'     => 'disable',
	'autoplay_speed'  => 3000,
	'is_height'       => 'disable',
	'animation'       => '',
	'class'           => '',
), $shortcode ), $atts );

$images     = wp_parse_id_list( $a['images'] );
$is_caption = ( 'enable' === $a['is_caption'] );
$is_arrows  = ( 'show' === $a['is_arrows'] );
$is_dots    = ( 'show' === $a['is_dots'] );

$class   = array();
$class[] = 'image-carousel';

// arrows
if ( $is_arrows ) {
	$class[] = 'carousel-navs-' . sanitize_key( $a['arrows_shape'] );
	$class[] = 'carousel-navs-' . sanitize_key( $a['arrows_size'] );
	$class[] = 'carousel-navs-' . sanitize_key( $a['arrows_position'] );
}

// dots
if ( $is_dots ) {
	$class[] = 'carousel-' . sanitize_key( $a['dots_skin'] );
	$class[] = 'carousel-dots-' . sanitize_key( $a['dots_position'] );
	$class[] = 'carousel-dots-' . sanitize_key( $a['dots_alignment'] );
}

$class[] = $is_caption ? 'with-captions' : 'without-captions';
$class[] = $a['class'];

// settings for carousel
$slick = array(
	'arrows'         => $is_arrows,
	'dots'           => $is_dots,
	'fade'           => ( 'fade' === $a['transition'] ),
	'infinite'       => ( 'enable' === $a['is_loop'] ),
	'autoplay'       => ( 'enable' === $a['is_autoplay'] ),
	'autoplaySpeed'  => absint( $a['autoplay_speed'] ),
	'adaptiveHeight' => ( 'enable' === $a['is_height'] ),
);

// carousel attributes
$attr = startapp_get_attr( array(
	'class'      => esc_attr( startapp_get_classes( $class ) ),
	'data-aos'   => ( ! empty( $a['animation'] ) ) ? esc_attr( $a['animation'] ) : '',
	'data-slick' => $slick,
) );

$template = '<figure class="{class}">{image}{caption}</figure>';

echo '<div ', $attr, '>';
foreach ( $images as $image_id ) {
	$classes = array( 'carousel-item', $is_caption ? 'wp-caption' : '' );
	$classes = implode( ' ', array_filter( $classes ) );
	$caption = '';
	if ( $is_caption ) {
		$attachment = startapp_get_attachment( $image_id );
		if ( ! empty( $attachment['caption'] ) ) {
			$caption = sprintf( '<figcaption class="wp-caption-text">%s</figcaption>', esc_html( $attachment['caption'] ) );
		}
	}

	$r = array(
		'{class}'   => $classes,
		'{image}'   => wp_get_attachment_image( $image_id, 'full' ),
		'{caption}' => $caption,
	);

	echo str_replace( array_keys( $r ), array_values( $r ), $template );
}
echo '</div>';
