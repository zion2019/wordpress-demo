<?php
/**
 * App Store | startapp_app_store
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
	'name'      => __( 'App Store', 'startapp' ),
	'text'      => __( 'Download on the', 'startapp' ),
	'link'      => '',
	'animation' => '',
	'class'     => '',
), $shortcode ), $atts );

/**
 * Filter the path to App Store background
 *
 * @param string $path URI to background image
 */
$image = apply_filters( 'startapp_shortcode_app_store_bg', STARTAPP_CORE_URI . '/img/market-btns/appstore.png' );
$name  = esc_html( $a['name'] );
$text  = startapp_get_text( esc_html( $a['text'] ), '<span>', '</span>' ) . $name;
$link  = startapp_vc_parse_link( $a['link'] );
$class = startapp_get_classes( array(
	'market-btn',
	'btn-appstore',
	$a['class'],
) );

$attributes             = array();
$attributes['href']     = empty( $link['url'] ) ? '#' : esc_url( trim( $link['url'] ) );
$attributes['target']   = empty( $link['target'] ) ? '' : esc_attr( trim( $link['target'] ) );
$attributes['title']    = empty( $link['title'] ) ? '' : esc_attr( trim( $link['title'] ) );
$attributes['rel']      = empty( $link['rel'] ) ? '' : esc_attr( trim( $link['rel'] ) );
$attributes['class']    = esc_attr( $class );
$attributes['data-aos'] = ( ! empty( $a['animation'] ) ) ? esc_attr( $a['animation'] ) : '';
$attributes['style']    = startapp_css_background_image( $image );

echo startapp_get_tag( 'a', $attributes, $text );
