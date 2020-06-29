<?php
/**
 * Single Product Up-Sells
 *
 * This template can be overridden by copying it to yourtheme/woocommerce/single-product/up-sells.php.
 *
 * HOWEVER, on occasion WooCommerce will need to update template files and you
 * (the theme developer) will need to copy the new files to your theme to
 * maintain compatibility. We try to do this as little as possible, but it does
 * happen. When this occurs the version of the template file will be bumped and
 * the readme will list any important changes.
 *
 * @see           https://docs.woocommerce.com/document/template-structure/
 * @author        WooThemes, 8guild
 * @package       WooCommerce/Templates
 * @version       3.0.0
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

if ( $upsells ) : ?>
	<div class="up-sells upsells products">

		<h2><?php esc_html_e( 'You may also like&hellip;', 'startapp' ) ?></h2>

        <?php
        $i = 1;
        woocommerce_product_loop_start();
        echo '<div class="row">';
        /** @var WC_Product $upsell */
        foreach ( $upsells as $upsell ) :
	        $post_object = get_post( $upsell->get_id() );
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
