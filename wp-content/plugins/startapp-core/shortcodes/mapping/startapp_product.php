<?php
/**
 * Product | startapp_product
 *
 * @author 8guild
 */

return array(
	'name'        => __( 'Product', 'startapp' ),
	'category'    => __( 'WooCommerce', 'startapp' ),
	'description' => __( 'Show a single product.', 'startapp' ),
	'icon'        => 'icon-wpb-woocommerce',
	'params'      => startapp_vc_map_params( array(
		array(
			'param_name'  => 'product',
			'type'        => 'dropdown',
			'weight'      => 10,
			'heading'     => __( 'Product', 'startapp' ),
			'description' => __( 'Choose a product from the dropdown list.', 'startapp' ),
			'value'       => call_user_func( function () {
				$cache_key   = 'startapp_product_posts';
				$cache_group = 'startapp';

				$data = wp_cache_get( $cache_key, $cache_group );
				if ( false === $data ) {
					// get posts
					$posts = get_posts( array(
						'post_type'           => 'product',
						'post_status'         => 'publish',
						'posts_per_page'      => - 1,
						'no_found_rows'       => true,
						'nopaging'            => true,
						'ignore_sticky_posts' => true,
						'suppress_filters'    => true,
					) );

					if ( ! empty( $posts ) && ! is_wp_error( $posts ) ) {
						$data = array();

						// first post empty
						$data[ __( 'Choose a product', 'startapp' ) ] = 0;

						foreach ( $posts as $post ) {
							$title = $post->post_title ? esc_html( $post->post_title ) : __( '(no-title)', 'startapp' );
							$id    = (int) $post->ID;

							$data[ $title ] = $id;
							unset( $title, $id );
						}
						unset( $post );

						// cache for 1 day
						wp_cache_set( $cache_key, $data, $cache_group, 86400 );
					}
				}

				return $data;
			} ),
		),
	), 'startapp_product' ),
);
