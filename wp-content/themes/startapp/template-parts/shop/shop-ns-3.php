<?php
/**
 * Template part for displaying the Shop "No Sidebar 3 Columns"
 *
 * @author 8guild
 */

/**
 * Hook: woocommerce_before_shop_loop.
 *
 * @hooked wc_print_notices - 10
 * @hooked woocommerce_result_count - 20
 * @hooked woocommerce_catalog_ordering - 30
 */
do_action( 'woocommerce_before_shop_loop' );

woocommerce_product_loop_start();

if ( wc_get_loop_prop( 'total' ) ) : ?>
	<div class="masonry-grid col-3">
		<div class="gutter-sizer"></div>
		<div class="grid-sizer"></div>

		<?php
		while ( have_posts() ) :
			the_post();

			/**
			 * Hook: woocommerce_shop_loop.
			 *
			 * @hooked WC_Structured_Data::generate_product_data() - 10
			 */
			do_action( 'woocommerce_shop_loop' );

			echo '<div class="grid-item">';
			wc_get_template_part( 'content', 'product' );
			echo '</div>';
		endwhile;
		?>

	</div>
<?php
endif;

woocommerce_product_loop_end();

/**
 * Hook: woocommerce_after_shop_loop.
 *
 * @hooked woocommerce_pagination - 10
 */
do_action( 'woocommerce_after_shop_loop' );
