<?php
/**
 * Shop Tile | startapp_shop_tile
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
	'product'   => 0,
	'animation' => '',
	'class'     => '',
), $shortcode ), $atts );

if ( empty( $a['product'] ) || ! class_exists( 'WooCommerce' ) ) {
	return;
}

$post = get_post( (int) $a['product'] );
if ( ! $post instanceof WP_Post ) {
	return;
}

$class = startapp_get_classes( array(
	'single-product-tile',
	$a['class'],
) );

$attr = startapp_get_attr( array(
	'class'    => esc_attr( $class ),
	'data-aos' => ( ! empty( $a['animation'] ) ) ? esc_attr( $a['animation'] ) : '',
) );

setup_postdata( $GLOBALS['post'] =& $post );

echo '<div ', $attr, '>';
wc_get_template_part( 'content', 'product' );
echo '</div>';

wp_reset_postdata();
