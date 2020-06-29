<?php
/**
 * Product loop sale flash
 *
 * This template can be overridden by copying it to yourtheme/woocommerce/loop/sale-flash.php.
 *
 * HOWEVER, on occasion WooCommerce will need to update template files and you
 * (the theme developer) will need to copy the new files to your theme to
 * maintain compatibility. We try to do this as little as possible, but it does
 * happen. When this occurs the version of the template file will be bumped and
 * the readme will list any important changes.
 *
 * @see 	    https://docs.woocommerce.com/document/template-structure/
 * @author 		WooThemes, 8guild
 * @package 	WooCommerce/Templates
 * @version     1.6.4
 */

// Exit if accessed directly
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

global $post, $product;

if ( $product->is_on_sale() ) :
	$badge = '<span class="badge"><i class="material-icons local_offer"></i> ' . esc_html__( 'Sale', 'startapp' ) . '</span>';

	/**
	 * Filter the WooCommerce Sale Flash badge
	 *
	 * @param string     $sale    Sale flash HTML
	 * @param WP_Post    $post    Post object
	 * @param WC_Product $product Product object
	 */
	echo apply_filters( 'woocommerce_sale_flash', $badge, $post, $product );
	unset( $badge );
endif;
