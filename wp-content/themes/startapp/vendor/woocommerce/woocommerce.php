<?php
/**
 * Filters and actions related to WooCommerce plugin
 *
 * @author 8guild
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

// skip if WooCommerce is disabled
if ( ! startapp_is_woocommerce() ) {
	return;
}

/**
 * Remove the content wrappers
 *
 * @see woocommerce/archive-product.php
 */
remove_action( 'woocommerce_before_main_content', 'woocommerce_output_content_wrapper', 10 );
remove_action( 'woocommerce_after_main_content', 'woocommerce_output_content_wrapper_end', 10 );

/**
 * Remove the built-in breadcrumbs
 *
 * @see woocommerce/archive-product.php
 */
remove_action( 'woocommerce_before_main_content', 'woocommerce_breadcrumb', 20 );

/**
 * Remove the built-in Archive and Product description
 *
 * @see woocommerce/archive-product.php
 */
remove_action( 'woocommerce_archive_description', 'woocommerce_taxonomy_archive_description', 10 );
remove_action( 'woocommerce_archive_description', 'woocommerce_product_archive_description', 10 );

/**
 * Remove the badge and product thumbnail
 *
 * @see woocommerce/content-product.php
 * @see startapp_wc_item_thumbnail()
 */
remove_action( 'woocommerce_before_shop_loop_item_title', 'woocommerce_show_product_loop_sale_flash', 10 );
remove_action( 'woocommerce_before_shop_loop_item_title', 'woocommerce_template_loop_product_thumbnail', 10 );

if ( ! function_exists( 'startapp_wc_open_wrapper' ) ) :
	/**
	 * Open the global div.container to wrap the shop page
	 *
	 * @see startapp_wc_close_wrapper()
	 * @see woocommerce/archive-product.php
	 */
	function startapp_wc_open_wrapper() {
		echo '<div class="container">';
	}
endif;

add_action( 'woocommerce_before_main_content', 'startapp_wc_open_wrapper', 5 );

if ( ! function_exists( 'startapp_wc_close_wrapper' ) ) :
	/**
	 * Close the global div.container to wrap the shop page
	 *
	 * @see startapp_wc_open_wrapper()
	 * @see woocommerce/archive-product.php
	 */
	function startapp_wc_close_wrapper() {
		echo '</div>';
	}
endif;

add_action( 'woocommerce_after_main_content', 'startapp_wc_close_wrapper', 5 );

if ( ! function_exists( 'startapp_wc_page_title' ) ) :
	/**
	 * Returns the custom Page Title for WooCommerce Pages
	 *
	 * @param string $title Page Title
	 *
	 * @return string
	 */
	function startapp_wc_page_title( $title ) {
		if ( ! startapp_is_woocommerce() ) {
			return $title;
		}

		if ( is_shop() && apply_filters( 'woocommerce_show_page_title', true ) ) {
			$title = woocommerce_page_title( false );
		}

		return $title;
	}
endif;

add_filter( 'startapp_page_title', 'startapp_wc_page_title' );

/**
 * Remove the opening product link
 *
 * @see woocommerce/content-product.php
 */
remove_action( 'woocommerce_before_shop_loop_item', 'woocommerce_template_loop_product_link_open', 10 );

if ( ! function_exists( 'startapp_wc_item_thumbnail' ) ) :
	/**
	 * Display link to product, the flash and product thumbnail
	 *
	 * @hooked woocommerce_before_shop_loop_item 10
	 * @see    woocommerce/content-product.php
	 */
	function startapp_wc_item_thumbnail() {
		echo '<a href="' . esc_url( get_the_permalink() ) . '" class="product-thumb">';
		wc_get_template( 'loop/sale-flash.php' );
		echo woocommerce_get_product_thumbnail( 'large' );
		echo '</a>';
	}
endif;

add_action( 'woocommerce_before_shop_loop_item', 'startapp_wc_item_thumbnail' );

if ( ! function_exists( 'startapp_wc_item_title' ) ) :
	/**
	 * Show the product title in the product loop
	 *
	 * @hooked woocommerce_shop_loop_item_title 10
	 * @see    woocommerce/content-product.php
	 */
	function startapp_wc_item_title() {
		$before = sprintf( '<h3 class="product-title"><a href="%s">', esc_url( get_the_permalink() ) );
		$after  = '</a></h3>';

		echo startapp_get_text( get_the_title(), $before, $after );
	}
endif;

remove_action( 'woocommerce_shop_loop_item_title', 'woocommerce_template_loop_product_title', 10 );
add_action( 'woocommerce_shop_loop_item_title', 'startapp_wc_item_title' );

/**
 * Remove the item rating and price. Change the hook priority
 *
 * @see woocommerce/content-product.php
 */
remove_action( 'woocommerce_after_shop_loop_item_title', 'woocommerce_template_loop_rating', 5 );
remove_action( 'woocommerce_after_shop_loop_item_title', 'woocommerce_template_loop_price', 10 );

if ( ! function_exists( 'startapp_wc_item_rating' ) ) :
	/**
	 * Show the product item rating
	 *
	 * @hooked woocommerce_after_shop_loop_item_title 15
	 * @see    woocommerce/content-product.php
	 */
	function startapp_wc_item_rating() {
		wc_get_template( 'loop/rating.php' );
	}
endif;

add_action( 'woocommerce_after_shop_loop_item_title', 'startapp_wc_item_rating', 15 );

if ( ! function_exists( 'startapp_wc_item_price' ) ) :
	/**
	 * Show the product item price
	 *
	 * @hooked woocommerce_after_shop_loop_item_title 20
	 * @see    woocommerce/content-product.php
	 */
	function startapp_wc_item_price() {
		wc_get_template( 'loop/price.php' );
	}
endif;

add_action( 'woocommerce_after_shop_loop_item_title', 'startapp_wc_item_price', 20 );

/**
 * Remove the closing product link tag
 *
 * @see woocommerce/content-product.php
 */
remove_action( 'woocommerce_after_shop_loop_item', 'woocommerce_template_loop_product_link_close', 5 );

if ( ! function_exists( 'startapp_wc_add_to_cart_class' ) ) :
	/**
	 * Remove the .button class from "Add to Cart" link
	 *
	 * @param array $classes Add to cart button classes
	 *
	 * @return array
	 */
	function startapp_wc_add_to_cart_class( $classes ) {
		if ( false !== ( $key = array_search( 'button', $classes ) ) ) {
			unset( $classes[ $key ] );
		}

		return $classes;
	}
endif;

add_filter( 'woocommerce_loop_add_to_cart_class', 'startapp_wc_add_to_cart_class' );

/**
 * Remove the "Result Count" from Shop Catalog
 *
 * @see woocommerce/archive-product.php
 */
remove_action( 'woocommerce_before_shop_loop', 'woocommerce_result_count', 20 );

/**
 * Modify the cart collaterals order
 *
 * @see woocommerce/cart/cart.php
 */
remove_action( 'woocommerce_cart_collaterals', 'woocommerce_cross_sell_display' );
remove_action( 'woocommerce_cart_collaterals', 'woocommerce_cart_totals', 10 );
add_action( 'woocommerce_cart_collaterals', 'woocommerce_cart_totals', 5 );
add_action( 'woocommerce_cart_collaterals', 'woocommerce_cross_sell_display', 10 );

if ( ! function_exists( 'startapp_wc_cross_sells_num' ) ) :
	/**
	 * Returns the max. number of cross sells products
	 *
	 * @return int
	 */
	function startapp_wc_cross_sells_num() {
		return 4;
	}
endif;

add_filter( 'woocommerce_cross_sells_total', 'startapp_wc_cross_sells_num', 50, 0 );

/**
 * Move the "Sale Flash" label inside the .summary
 * Also remove the product title
 *
 * @see woocommerce/content-single-product.php
 */
remove_action( 'woocommerce_before_single_product_summary', 'woocommerce_show_product_sale_flash', 10 );
remove_action( 'woocommerce_single_product_summary', 'woocommerce_template_single_title', 5 );
add_action( 'woocommerce_single_product_summary', 'woocommerce_show_product_sale_flash', 5 );

if ( ! function_exists( 'startapp_wc_cart_fragments' ) ) :
	/**
	 * Add cart fragments
	 *
	 * Ensure cart contents update when products are added to the cart via AJAX
	 *
	 * @param  array $fragments Fragments to refresh via AJAX.
	 *
	 * @return array
	 */
	function startapp_wc_cart_fragments( $fragments ) {
		// normal cart with dropdown
		ob_start();
		startapp_the_cart();
		$cart = ob_get_clean();

		// mobile cart with just a counter
		ob_start();
		startapp_the_mobile_cart();
		$mobile_cart = ob_get_clean();

		$fragments['div.cart-contents']             = $cart;
		$fragments['div.cart-contents.mobile-view'] = $mobile_cart;

		return $fragments;
	}
endif;

if ( defined( 'WC_VERSION' ) && version_compare( WC_VERSION, '2.3', '>=' ) ) {
	add_filter( 'woocommerce_add_to_cart_fragments', 'startapp_wc_cart_fragments' );
} else {
	add_filter( 'add_to_cart_fragments', 'startapp_wc_cart_fragments' );
}

if ( ! function_exists( 'startapp_wc_enable_page_settings' ) ) :
	/**
	 * Enable Page Settings for Product
	 *
	 * @param array $screens Post types
	 *
	 * @return array
	 */
	function startapp_wc_enable_page_settings( $screens ) {
		$screens[] = 'product';

		return $screens;
	}
endif;

add_filter( 'startapp_page_settings_screen', 'startapp_wc_enable_page_settings' );
