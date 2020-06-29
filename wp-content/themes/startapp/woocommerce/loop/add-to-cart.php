<?php
/**
 * Loop Add to Cart
 *
 * This template can be overridden by copying it to yourtheme/woocommerce/loop/add-to-cart.php.
 *
 * HOWEVER, on occasion WooCommerce will need to update template files and you
 * (the theme developer) will need to copy the new files to your theme to
 * maintain compatibility. We try to do this as little as possible, but it does
 * happen. When this occurs the version of the template file will be bumped and
 * the readme will list any important changes.
 *
 * @see 	    https://docs.woocommerce.com/document/template-structure/
 * @author 		WooThemes
 * @package 	WooCommerce/Templates
 * @version     3.3.0
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

global $product;

// Classes
$atc_class = array(
	'btn',
	'btn-sm',
	'btn-ghost',
	'btn-primary',
);

if ( isset( $args['class'] ) ) {
	$atc_class = array_merge( explode( ' ', $args['class'] ), $atc_class );
}

/**
 * Filter the "Add to Cart" button class
 *
 * @param array      $class   Button class
 * @param WC_Product $product Product object
 */
$atc_class = apply_filters( 'woocommerce_loop_add_to_cart_class', $atc_class, $product );

/**
 * Filter the "Add to Cart" button icon
 *
 * @param string     $icon    Icon HTML
 * @param WC_Product $product Product object
 */
$atc_icon = apply_filters( 'woocommerce_loop_add_to_cart_icon', '<i class="material-icons shopping_cart"></i>', $product );

// Attributes
$atc_attr = array(
	'href'          => esc_url( $product->add_to_cart_url() ),
	'data-quantity' => esc_attr( isset( $args['quantity'] ) ? $args['quantity'] : 1 ),
	'class'         => startapp_get_classes( $atc_class ),
);

if ( isset( $args['attributes'] ) ) {
	$atc_attr = array_merge( $args['attributes'], $atc_attr );
}

/**
 * Filter the "Add to Cart" button attributes
 *
 * @param array      $attr    Button attributes
 * @param WC_Product $product Product object
 */
$atc_attr = apply_filters( 'woocommerce_loop_add_to_cart_attr', $atc_attr, $product );

/**
 * Filter the "Add to Cart" button HTML
 *
 * @param string     $button  Button HTML
 * @param WC_Product $product Product object
 */
echo apply_filters( 'woocommerce_loop_add_to_cart_link',
	startapp_get_tag( 'a', $atc_attr, $atc_icon . '&nbsp;' . esc_html( $product->add_to_cart_text() ) ),
	$product
);

unset( $atc_class, $atc_icon, $atc_attr );
