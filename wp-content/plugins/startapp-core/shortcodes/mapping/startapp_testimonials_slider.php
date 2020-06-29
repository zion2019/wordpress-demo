<?php
/**
 * Testimonials Slider | startapp_testimonials_slider
 *
 * @author 8guild
 */

return array(
	'name'     => __( 'Testimonials Slider', 'startapp' ),
	'category' => __( 'StartApp', 'startapp' ),
	'icon'     => '',
	'params'   => startapp_vc_map_params( array(
		array(
			'param_name'  => 'query_post__in',
			'type'        => 'autocomplete',
			'weight'      => 10,
			'heading'     => __( 'Specify posts to retrieve', 'startapp' ),
			'description' => __( 'Specify items you want to retrieve, by title', 'startapp' ),
			'settings'    => array(
				'multiple'       => true,
				'sortable'       => true,
				'min_length'     => 1,
				'display_inline' => false,
				'values'         => apply_filters( 'startapp_testimonials_slider_posts_autocomlete', array() )
			),
		),
		array(
			'param_name'  => 'query_post__not_in',
			'type'        => 'autocomplete',
			'weight'      => 10,
			'heading'     => __( 'Exclude posts', 'startapp' ),
			'description' => __( 'Exclude some posts from results, by title. Useful if you want to display all posts except some ones.', 'startapp' ),
			'settings'    => array(
				'multiple'       => true,
				'sortable'       => false,
				'min_length'     => 1,
				'display_inline' => true,
				'values'         => apply_filters( 'startapp_testimonials_slider_posts_autocomlete', array() )
			),
		),
		array(
			'param_name'  => 'query_posts_per_page',
			'type'        => 'textfield',
			'weight'      => 10,
			'heading'     => __( 'Number of posts', 'startapp' ),
			'description' => __( 'Any positive integer or keyword "all" for displaying all posts.', 'startapp' ),
			'value'       => 'all',
		),
		array(
			'param_name'       => 'query_orderby',
			'type'             => 'dropdown',
			'weight'           => 10,
			'heading'          => __( 'Order by', 'startapp' ),
			'edit_field_class' => 'vc_column vc_col-sm-6',
			'std'              => 'date',
			'value'            => array(
				__( 'Post ID', 'startapp' )            => 'ID',
				__( 'Author', 'startapp' )             => 'author',
				__( 'Post name (slug)', 'startapp' )   => 'name',
				__( 'Date', 'startapp' )               => 'date',
				__( 'Last Modified Date', 'startapp' ) => 'modified',
				__( 'Number of comments', 'startapp' ) => 'comment_count',
				__( 'Manually', 'startapp' )           => 'post__in',
				__( 'Random', 'startapp' )             => 'rand',
			),
		),
		array(
			'param_name'       => 'query_order',
			'type'             => 'dropdown',
			'weight'           => 10,
			'heading'          => __( 'Sorting', 'startapp' ),
			'edit_field_class' => 'vc_col-sm-6',
			'std'              => 'DESC',
			'value'            => array(
				__( 'Descending', 'startapp' ) => 'DESC',
				__( 'Ascending', 'startapp' )  => 'ASC',
			),
		),
		array(
			'param_name'       => 'alignment',
			'type'             => 'dropdown',
			'weight'           => 10,
			'heading'          => __( 'Content Alignment', 'startapp' ),
			'edit_field_class' => 'vc_col-sm-6',
			'std'              => 'left',
			'value'            => array(
				__( 'Left', 'startapp' )   => 'left',
				__( 'Center', 'startapp' ) => 'center',
				__( 'Right', 'startapp' )  => 'right',
			),
		),
		array(
			'param_name'       => 'is_dots',
			'type'             => 'dropdown',
			'weight'           => 10,
			'heading'          => __( 'Show Dots', 'startapp' ),
			'edit_field_class' => 'vc_col-sm-6',
			'std'              => 'show',
			'value'            => array(
				__( 'Show', 'startapp' ) => 'show',
				__( 'Hide', 'startapp' ) => 'hide',
			),
		),
		array(
			'param_name' => 'skin',
			'type'       => 'dropdown',
			'weight'     => 10,
			'heading'    => __( 'Skin', 'startapp' ),
			'std'        => 'dark',
			'value'      => array(
				__( 'Dark', 'startapp' )  => 'dark',
				__( 'Light', 'startapp' ) => 'light',
			),
		),
		array(
			'param_name' => 'transition',
			'type'       => 'dropdown',
			'weight'     => 10,
			'heading'    => __( 'Transition Effect', 'startapp' ),
			'group'      => __( 'Behavior', 'startapp' ),
			'std'        => 'slide',
			'value'      => array(
				__( 'Slide', 'startapp' ) => 'slide',
				__( 'Fade', 'startapp' )  => 'fade',
			),
		),
		array(
			'param_name' => 'is_loop',
			'type'       => 'dropdown',
			'weight'     => 10,
			'heading'    => __( 'Loop', 'startapp' ),
			'group'      => __( 'Behavior', 'startapp' ),
			'std'        => 'disable',
			'value'      => array(
				__( 'Enable', 'startapp' )  => 'enable',
				__( 'Disable', 'startapp' ) => 'disable',
			),
		),
		array(
			'param_name' => 'is_autoplay',
			'type'       => 'dropdown',
			'weight'     => 10,
			'heading'    => __( 'Autoplay', 'startapp' ),
			'group'      => __( 'Behavior', 'startapp' ),
			'std'        => 'disable',
			'value'      => array(
				__( 'Enable', 'startapp' )  => 'enable',
				__( 'Disable', 'startapp' ) => 'disable',
			),
		),
		array(
			'param_name'  => 'autoplay_speed',
			'type'        => 'textfield',
			'weight'      => 10,
			'heading'     => __( 'Autoplay Speed', 'startapp' ),
			'description' => __( 'Any positive integer number', 'startapp' ),
			'group'       => __( 'Behavior', 'startapp' ),
			'dependency'  => array( 'element' => 'is_autoplay', 'value' => 'enable' ),
			'value'       => '3000',
		),
	), 'startapp_testimonials_slider' ),
);
