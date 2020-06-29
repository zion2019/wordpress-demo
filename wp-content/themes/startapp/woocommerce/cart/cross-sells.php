<?php
/**
 * Cross-sells
 *
 * This template can be overridden by copying it to yourtheme/woocommerce/cart/cross-sells.php.
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
 * @version     3.0.0
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

if ( $cross_sells ) : ?>

	<div class="cross-sells">

		<h2><?php esc_html_e( 'You may be interested in&hellip;', 'startapp' ); ?></h2>

        <?php
        $i = 1;
        woocommerce_product_loop_start();
        echo '<div class="row">';
        foreach ( $cross_sells as $cross_sell ) :
            $post_object = get_post( $cross_sell->get_id() );
	        setup_postdata( $GLOBALS['post'] =& $post_object );

	        echo '<div class="col-md-3 col-sm-6">';
	        wc_get_template_part( 'content', 'product' );
	        echo '</div>';

	        if ( $i % 2 == 0 ) {
		        // fix the floating
		        echo '<div class="clearfix visible-sm"></div>';
	        }

	        $i ++;
        endforeach;
        echo '</div>';
        wp_reset_postdata();
        woocommerce_product_loop_end();
        unset( $i );
        ?>

	</div>
	<?php
endif;
