<?php
/**
 * Testimonials Carousel | startapp_testimonials_carousel
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
	'skin'           => 'dark',
	'is_arrows'      => 'enable',
	'is_dots'        => 'enable',
	'dots_alignment' => 'center',
	'is_loop'        => 'disable',
	'is_autoplay'    => 'disable',
	'autoplay_speed' => 3000,
	'lg'             => 3,
	'md'             => 3,
	'sm'             => 2,
	'xs'             => 1,
	'animation'      => '',
	'class'          => '',
), $shortcode ), $atts );

$testimonials = json_decode( urldecode( $content ), true );
if ( empty( $testimonials ) ) {
	return;
}

$slick = array(
	'dots'          => ( 'enable' === $a['is_dots'] ),
	'arrows'        => ( 'enable' === $a['is_arrows'] ),
	'infinite'      => ( 'enable' === $a['is_loop'] ),
	'autoplay'      => ( 'enable' === $a['is_autoplay'] ),
	'autoplaySpeed' => absint( $a['autoplay_speed'] ),
);

$class = startapp_get_classes( array(
	'testimonials-carousel',
	'carousel-navs-ghost',
	'carousel-navs-top-outside',
	( 'enable' === $a['is_dots'] ) ? 'carousel-dots-' . sanitize_key( $a['dots_alignment'] ) : '',
	'carousel-' . sanitize_key( $a['skin'] ),
	$a['class'],
) );

$attr = startapp_get_attr( array(
	'class'         => esc_attr( $class ),
	'data-slick'    => $slick,
	'data-items-lg' => absint( $a['lg'] ),
	'data-items-md' => absint( $a['md'] ),
	'data-items-sm' => absint( $a['sm'] ),
	'data-items-xs' => absint( $a['xs'] ),
	'data-aos'      => ( ! empty( $a['animation'] ) ) ? esc_attr( $a['animation'] ) : '',
) );

echo '<div ', $attr, '>';
foreach ( $testimonials as $t ) {
	if ( empty( $t['testimonial'] ) ) {
		continue;
	}

	$testimonial = $t['testimonial'];
	unset( $t['testimonial'] );
	$sh = startapp_shortcode_build( 'startapp_testimonial', $t, $testimonial );

	echo '<div class="carousel-item">';
	echo startapp_do_shortcode( $sh );
	echo '</div>';

	unset( $testimonial, $sh );
}
echo '</div>';
