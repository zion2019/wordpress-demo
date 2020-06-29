<?php
/**
 * Blog Post Tile | startapp_blog_tile
 *
 * @author 8guild
 */

return array(
	'name'        => __( 'Blog Post Tile', 'startapp' ),
	'category'    => __( 'StartApp', 'startapp' ),
	'description' => __( 'Displays the single post tile', 'startapp' ),
	'params'      => startapp_vc_map_params( array(
		array(
			'param_name'  => 'post',
			'type'        => 'dropdown',
			'weight'      => 10,
			'heading'     => __( 'Post', 'startapp' ),
			'description' => __( 'Choose a post from the dropdown list.', 'startapp' ),
			'value'       => call_user_func( function () {
				// get posts
				$posts = get_posts( array(
					'post_type'           => 'post',
					'post_status'         => 'publish',
					'posts_per_page'      => - 1,
					'no_found_rows'       => true,
					'nopaging'            => true,
					'ignore_sticky_posts' => true,
					'suppress_filters'    => true,
				) );

				if ( empty( $posts ) || is_wp_error( $posts ) ) {
					return array();
				}

				$p = array();

				// first post empty
				$p[ __( 'Choose a post', 'startapp' ) ] = 0;

				foreach ( $posts as $post ) {
					$title = $post->post_title ? esc_html( $post->post_title ) : __( '(no-title)', 'startapp' );
					$id    = (int) $post->ID;

					$p[ $title ] = $id;
					unset( $title, $id );
				}
				unset( $post );

				return $p;
			} ),
		),
	), 'startapp_blog_tile' ),
);
