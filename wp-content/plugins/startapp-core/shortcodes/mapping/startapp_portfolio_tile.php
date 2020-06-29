<?php
/**
 * Portfolio Tile | startapp_portfolio_tile
 *
 * @author 8guild
 */

return array(
	'name'        => __( 'Portfolio Tile', 'startapp' ),
	'category'    => __( 'StartApp', 'startapp' ),
	'description' => __( 'Single Portfolio item tile', 'startapp' ),
	'icon'        => '',
	'params'      => startapp_vc_map_params( array(
		array(
			'param_name'  => 'post',
			'type'        => 'dropdown',
			'weight'      => 10,
			'heading'     => __( 'Post', 'startapp' ),
			'description' => __( 'Choose a post from the dropdown list.', 'startapp' ),
			'value'       => call_user_func( function () {
				// get posts
				$_posts = get_posts( array(
					'post_type'           => 'startapp_portfolio',
					'post_status'         => 'publish',
					'posts_per_page'      => - 1,
					'no_found_rows'       => true,
					'nopaging'            => true,
					'suppress_filters'    => true,
				) );

				$posts = array();

				$posts[ __( 'Choose a post', 'startapp' ) ] = 0;

				if ( ! empty( $_posts ) && ! is_wp_error( $_posts ) ) {
					foreach ( $_posts as $post ) {
						$title = $post->post_title ? esc_html( $post->post_title ) : __( '(no-title)', 'startapp' );
						$id    = (int) $post->ID;

						$posts[ $title ] = $id;
						unset( $title, $id );
					}
					unset( $post );
				}

				return $posts;
			} ),
		),
		array(
			'param_name' => 'type',
			'type'       => 'dropdown',
			'weight'     => 10,
			'heading'    => __( 'Type', 'startapp' ),
			'std'        => 'text-button',
			'value'      => array(
				__( 'Simple Image', 'startapp' )         => 'simple',
				__( 'With Text and Button', 'startapp' ) => 'text-button',
				__( 'With Text Overlay', 'startapp' )    => 'overlay',
			),
		),
		array(
			'param_name' => 'overlay_color',
			'type'       => 'colorpicker',
			'weight'     => 10,
			'group'      => __( 'Overlay', 'startapp' ),
			'heading'    => __( 'Color', 'startapp' ),
			'dependency' => array( 'element' => 'type', 'value' => 'overlay' ),
			'value'      => '#000000',
		),
		array(
			'param_name'  => 'overlay_opacity',
			'type'        => 'textfield',
			'weight'      => 10,
			'group'       => __( 'Overlay', 'startapp' ),
			'heading'     => __( 'Opacity', 'startapp' ),
			'description' => __( 'Enter value from 0 to 100%. Where 0 is fully transparent.', 'startapp' ),
			'dependency'  => array( 'element' => 'type', 'value' => 'overlay' ),
			'value'       => 50,
		),
		array(
			'param_name' => 'overlay_skin',
			'type'       => 'dropdown',
			'weight'     => 10,
			'group'      => __( 'Overlay', 'startapp' ),
			'heading'    => __( 'Skin', 'startapp' ),
			'dependency' => array( 'element' => 'type', 'value' => 'overlay' ),
			'std'        => 'light',
			'value'      => array(
				__( 'Dark', 'startapp' )  => 'dark',
				__( 'Light', 'startapp' ) => 'light',
			),
		),
	), 'startapp_portfolio_tile' ),
);
