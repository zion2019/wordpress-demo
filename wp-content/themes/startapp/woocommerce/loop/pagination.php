<?php
/**
 * Pagination - Show numbered pagination for catalog pages
 *
 * This template can be overridden by copying it to yourtheme/woocommerce/loop/pagination.php.
 *
 * HOWEVER, on occasion WooCommerce will need to update template files and you
 * (the theme developer) will need to copy the new files to your theme to
 * maintain compatibility. We try to do this as little as possible, but it does
 * happen. When this occurs the version of the template file will be bumped and
 * the readme will list any important changes.
 *
 * @see     https://docs.woocommerce.com/document/template-structure/
 * @package WooCommerce/Templates
 * @version 3.3.1
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

$total   = isset( $total ) ? $total : wc_get_loop_prop( 'total_pages' );
$current = isset( $current ) ? $current : wc_get_loop_prop( 'current_page' );
$base    = isset( $base ) ? $base : esc_url_raw( str_replace( 999999999, '%#%', remove_query_arg( 'add-to-cart', get_pagenum_link( 999999999, false ) ) ) );
$format  = isset( $format ) ? $format : '';

if ( $total <= 1 ) {
	return;
}

/**
 * Filter the arguments passed to {@see paginate_links} for Shop Catalog.
 *
 * @param array $args Arguments for {@see paginate_links}
 */
$links = paginate_links( apply_filters( 'woocommerce_pagination_args', array(
	'base'      => $base,
	'format'    => $format,
	'add_args'  => false,
	'current'   => max( 1, $current ),
	'total'     => $total,
	'type'      => 'plain',
	'end_size'  => 3,
	'mid_size'  => 3,
	'prev_next' => true,
	'prev_text' => '<i class="material-icons keyboard_backspace"></i>',
	'next_text' => '<i class="material-icons keyboard_backspace"></i>',
) ) );

$class = array();

$class[] = 'pagination';
$class[] = 'margin-bottom-1x';
$class[] = 'text-' . startapp_get_option( 'shop_pagination_pos', 'left' );

/**
 * Filter the classes for Shop Catalog pagination.
 *
 * @param array $class A list of extra classes
 */
$class = apply_filters( 'startapp_shop_pagination_class', $class );
$class = esc_attr( startapp_get_classes( $class ) );

echo '<section class="', $class, '"><div class="nav-links">', $links, '</div></section>';

unset( $links, $class );
