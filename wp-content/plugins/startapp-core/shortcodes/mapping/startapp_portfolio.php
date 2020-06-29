<?php
/**
 * Portfolio | startapp_portfolio
 *
 * @author 8guild
 */

/**
 * Get posts for autocomplete field
 *
 * @see startapp_core_portfolio_posts()
 */
$posts = apply_filters( 'startapp_portfolio_posts_autocomplete', array() );

/**
 * Get post terms for autocomplete field
 *
 * @see startapp_core_portfolio_categories()
 */
$categories = apply_filters( 'startapp_portfolio_categories_autocomplete', array() );

return array(
	'name'     => __( 'Portfolio', 'startapp' ),
	'category' => __( 'StartApp', 'startapp' ),
	'icon'     => '',
	'params'   => startapp_vc_map_params( array(
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
			'param_name' => 'columns',
			'type'       => 'dropdown',
			'weight'     => 10,
			'heading'    => __( 'Number of Columns', 'startapp' ),
			'std'        => 3,
			'value'      => array(
				'1' => 1,
				'2' => 2,
				'3' => 3,
				'4' => 4,
				'5' => 5,
				'6' => 6,
			),
		),
		array(
			'param_name'       => 'is_filters',
			'type'             => 'dropdown',
			'weight'           => 10,
			'heading'          => __( 'Enable filters?', 'startapp' ),
			'description'      => __( 'See tab "Filters" for additional customization options. Appears when you enable filters.', 'startapp' ),
			'edit_field_class' => 'vc_col-sm-6',
			'value'            => array(
				__( 'Yes', 'startapp' ) => 'enable',
				__( 'No', 'startapp' )  => 'disable',
			),
		),
		array(
			'param_name'       => 'pagination',
			'type'             => 'dropdown',
			'weight'           => 10,
			'heading'          => __( 'Pagination', 'startapp' ),
			'description'      => __( 'See tab "Load More" for additional customization options. Appears when you choose Load More option.', 'startapp' ),
			'edit_field_class' => 'vc_col-sm-6',
			'std'              => 'load-more',
			'value'            => array(
				__( 'Disable', 'startapp' )         => 'disable',
				__( 'Load More', 'startapp' )       => 'load-more',
				__( 'Infinite Scroll', 'startapp' ) => 'infinite-scroll',

			),
		),
		array(
			'param_name'  => 'source',
			'type'        => 'dropdown',
			'weight'      => 10,
			'group'       => __( 'Query Builder', 'startapp' ),
			'heading'     => __( 'Data source', 'startapp' ),
			'description' => __( 'Choose the "List of IDs" if you want to retrieve some specific posts. If you choose the "Posts" further you can clarify the request.', 'startapp' ),
			'value'       => array(
				__( 'Categories', 'startapp' ) => 'categories',
				__( 'IDs', 'startapp' )        => 'ids',
			),
		),
		array(
			'param_name'  => 'query_post__in',
			'type'        => 'autocomplete',
			'weight'      => 10,
			'group'       => __( 'Query Builder', 'startapp' ),
			'heading'     => __( 'Posts to retrieve', 'startapp' ),
			'description' => __( 'Specify items you want to retrieve, by title', 'startapp' ),
			'dependency'  => array( 'element' => 'source', 'value' => 'ids' ),
			'settings'    => array(
				'multiple'       => true,
				'unique_values'  => true,
				'display_inline' => false,
				'min_length'     => 2,
				'values'         => $posts,
			),
		),
		array(
			'param_name' => 'query_categories',
			'type'       => 'autocomplete',
			'weight'     => 10,
			'group'      => __( 'Query Builder', 'startapp' ),
			'heading'    => __( 'Categories', 'startapp' ),
			'dependency' => array( 'element' => 'source', 'value' => 'categories' ),
			'settings'   => array(
				'multiple'       => true,
				'unique_values'  => true,
				'display_inline' => true,
				'min_length'     => 2,
				'values'         => $categories,
			),
		),
		array(
			'param_name'  => 'query_post__not_in',
			'type'        => 'autocomplete',
			'weight'      => 10,
			'group'       => __( 'Query Builder', 'startapp' ),
			'heading'     => __( 'Exclude posts', 'startapp' ),
			'description' => __( 'Exclude some posts from results, by title.', 'startapp' ),
			'dependency'  => array( 'element' => 'source', 'value' => 'categories' ),
			'settings'    => array(
				'min_length'    => 2,
				'multiple'      => true,
				'unique_values' => true,
				'values'        => $posts,
			),
		),
		array(
			'param_name'  => 'query_posts_per_page',
			'type'        => 'textfield',
			'weight'      => 10,
			'group'       => __( 'Query Builder', 'startapp' ),
			'heading'     => __( 'Number of posts', 'startapp' ),
			'description' => __( 'Any number or "all" for displaying all posts.', 'startapp' ),
			'value'       => 'all',
			'dependency'  => array( 'element' => 'source', 'value_not_equal_to' => 'ids' ),
		),
		array(
			'param_name'       => 'query_orderby',
			'type'             => 'dropdown',
			'weight'           => 10,
			'group'            => __( 'Query Builder', 'startapp' ),
			'heading'          => __( 'Order by', 'startapp' ),
			'edit_field_class' => 'vc_col-sm-6',
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
			'group'            => __( 'Query Builder', 'startapp' ),
			'heading'          => __( 'Sorting', 'startapp' ),
			'edit_field_class' => 'vc_col-sm-6',
			'std'              => 'DESC',
			'value'            => array(
				__( 'Descending', 'startapp' ) => 'DESC',
				__( 'Ascending', 'startapp' )  => 'ASC',
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
		array(
			'param_name' => 'filters_pos',
			'type'       => 'dropdown',
			'weight'     => 10,
			'group'      => __( 'Filters', 'startapp' ),
			'heading'    => __( 'Filters position', 'startapp' ),
			'dependency' => array( 'element' => 'is_filters', 'value' => 'enable' ),
			'std'        => 'center',
			'value'      => array(
				__( 'Left', 'startapp' )   => 'left',
				__( 'Center', 'startapp' ) => 'center',
				__( 'Right', 'startapp' )  => 'right',
			),
		),
		array(
			'param_name'  => 'filters_exclude',
			'type'        => 'autocomplete',
			'weight'      => 10,
			'group'       => __( 'Filters', 'startapp' ),
			'heading'     => __( 'Exclude from filter list', 'startapp' ),
			'description' => __( 'Enter categories won\'t be shown in the filters list. This option is useful if you specify some categories in General tab.', 'startapp' ),
			'dependency'  => array( 'element' => 'is_filters', 'value' => 'enable' ),
			'settings'    => array(
				'multiple'       => true,
				'min_length'     => 2,
				'unique_values'  => true,
				'display_inline' => true,
				'values'         => $categories,
			),
		),
		array(
			'param_name'  => 'more_text',
			'type'        => 'textfield',
			'weight'      => 10,
			'group'       => __( 'Load More', 'startapp' ),
			'heading'     => __( 'Text', 'startapp' ),
			'description' => __( 'This text will be displayed on the Load More button.', 'startapp' ),
			'value'       => __( 'Load More', 'startapp' ),
			'dependency'  => array( 'element' => 'pagination', 'value' => 'load-more' ),
		),
		array(
			'param_name' => 'more_pos',
			'type'       => 'dropdown',
			'weight'     => 10,
			'group'      => __( 'Load More', 'startapp' ),
			'heading'    => __( 'Position', 'startapp' ),
			'dependency' => array( 'element' => 'pagination', 'value' => 'load-more' ),
			'std'        => 'left',
			'value'      => array(
				__( 'Left', 'startapp' )   => 'left',
				__( 'Center', 'startapp' ) => 'center',
				__( 'Right', 'startapp' )  => 'right',
			),
		),
	), 'startapp_portfolio' ),
);
