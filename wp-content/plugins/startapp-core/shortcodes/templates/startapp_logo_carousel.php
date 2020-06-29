<?php
/**
 * Logo Carousel | startapp_logo_carousel
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
	'is_loop'        => 'disable',
	'is_autoplay'    => 'disable',
	'autoplay_speed' => 3000,
	'lg'             => 6,
	'md'             => 5,
	'sm'             => 3,
	'xs'             => 1,
	'animation'      => '',
	'class'          => '',
), $shortcode ), $atts );

$logos = json_decode( urldecode( $content ), true );
if ( empty( $logos ) ) {
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
	'logo-carousel',
	'carousel-navs-ghost',
	'carousel-navs-top-outside',
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
foreach ( $logos as $l ) {
	$l = wp_parse_args( $l, array(
		'logo'        => 0,
		'link'        => '',
		'title'       => '',
		'description' => '',
	) );

	$is_linked = false;
	$image     = wp_get_attachment_image( (int) $l['logo'], 'full' );
	$title     = startapp_get_text( esc_html( $l['title'] ), '<h4 class="logo-item-title">', '</h4>' );
	$desc      = startapp_get_text( esc_html( $l['description'] ), '<p class="logo-item-description">', '</p>' );

	$attr = array();
	$link = startapp_vc_parse_link( $l['link'] );

	if ( ! empty( $link['url'] ) ) {
		$is_linked = true;

		$attr['href']   = preg_match( '@^https?://@i', $link['url'] ) ? esc_url( trim( $link['url'] ) ) : esc_attr( $link['url'] );
		$attr['target'] = empty( $link['target'] ) ? '' : esc_attr( trim( $link['target'] ) );
		$attr['title']  = empty( $link['title'] ) ? '' : esc_attr( trim( $link['title'] ) );
		$attr['rel']    = empty( $link['rel'] ) ? '' : esc_attr( trim( $link['rel'] ) );
	}

	$attr['class'] = 'logo-item';

	echo startapp_get_tag( $is_linked ? 'a' : 'div', $attr, $image . $title . $desc );
}
echo '</div>';

