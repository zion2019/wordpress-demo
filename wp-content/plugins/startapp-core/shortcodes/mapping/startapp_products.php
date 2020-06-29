<?php
/**
 * Products | startapp_products
 *
 * @author 8guild
 */

/**
 * Get posts for autocomplete field
 *
 * @see startapp_core_products_posts()
 * @see startapp_core_flush_products_posts_autocomplete_cache()
 */
$posts = apply_filters( 'startapp_products_posts_autocomplete', array() );

/**
 * Get post terms for autocomplete field
 *
 * @see startapp_core_products_categories()
 * @see startapp_core_flush_products_categories_autocomplete_cache()
 */
$categories = apply_filters( 'startapp_products_categories_autocomplete', array() );

/**
 * Get attributes
 *
 * @see startapp_core_products_attributes()
 */
$attributes = apply_filters( 'startapp_products_attributes', array( __( 'None', 'startapp' ) => 'none' ) );

return array(
	'name'             => __( 'Products', 'startapp' ),
	'category'         => __( 'WooCommerce', 'startapp' ),
	'icon'             => 'icon-wpb-woocommerce',
	'description'      => __( 'Show multiple products.', 'startapp' ),
	'admin_enqueue_js' => STARTAPP_CORE_URI . '/js/woocommerce.js',
	'params'           => startapp_vc_map_params( array(
		array(
			'param_name' => 'columns',
			'type'       => 'dropdown',
			'weight'     => 10,
			'heading'    => __( 'Number of Columns', 'startapp' ),
			'std'        => 4,
			'value'      => array( '2' => 2, '3' => 3, '4' => 4 ),
		),
		array(
			'param_name'  => 'pagination',
			'type'        => 'dropdown',
			'weight'      => 10,
			'heading'     => __( 'Pagination', 'startapp' ),
			'description' => __( 'See tab "Load More" for additional customization options. Appears when you choose Load More option.', 'startapp' ),
			'std'         => 'load-more',
			'value'       => array(
				__( 'Disable', 'startapp' )   => 'disable',
				__( 'Load More', 'startapp' ) => 'load-more',
			),
		),
		array(
			'param_name'  => 'source',
			'type'        => 'dropdown',
			'weight'      => 10,
			'group'       => __( 'Query Builder', 'startapp' ),
			'heading'     => __( 'Data Source', 'startapp' ),
			'description' => __( 'Choose the "List of IDs" if you want to retrieve some specific posts. If you choose the "Posts" further you can clarify the request.', 'startapp' ),
			'value'       => array(
				__( 'Categories', 'startapp' )    => 'categories',
				__( 'Sale Products', 'startapp' ) => 'sale',
				__( 'IDs', 'startapp' )           => 'ids',
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
			'dependency'  => array( 'element' => 'source', 'value' => array( 'categories', 'sale' ) ),
			'settings'    => array(
				'min_length'    => 2,
				'multiple'      => true,
				'unique_values' => true,
				'values'        => $posts,
			),
		),
		array(
			'param_name'       => 'query_featured',
			'type'             => 'checkbox',
			'weight'           => 10,
			'group'            => __( 'Query Builder', 'startapp' ),
			'edit_field_class' => 'vc_col-sm-3',
			'dependency'       => array( 'element' => 'source', 'value' => array( 'categories', 'sale' ) ),
			'std'              => 'disable',
			'value'            => array( esc_html__( 'Featured', 'startapp' ) => 'enable' ),
		),
		array(
			'param_name'       => 'query_best_selling',
			'type'             => 'checkbox',
			'weight'           => 10,
			'group'            => __( 'Query Builder', 'startapp' ),
			'edit_field_class' => 'vc_col-sm-3',
			'dependency'       => array( 'element' => 'source', 'value' => array( 'categories', 'sale' ) ),
			'std'              => 'disable',
			'value'            => array( esc_html__( 'Best Selling', 'startapp' ) => 'enable' ),
		),
		array(
			'param_name'       => 'query_top_rated',
			'type'             => 'checkbox',
			'weight'           => 10,
			'group'            => __( 'Query Builder', 'startapp' ),
			'edit_field_class' => 'vc_col-sm-6',
			'dependency'       => array( 'element' => 'source', 'value' => array( 'categories', 'sale' ) ),
			'std'              => 'disable',
			'value'            => array( esc_html__( 'Top Rated', 'startapp' ) => 'enable' ),
		),
		array(
			'param_name'  => 'query_attribute',
			'type'        => 'dropdown',
			'weight'      => 10,
			'save_always' => true,
			'heading'     => __( 'Attribute', 'startapp' ),
			'group'       => __( 'Query Builder', 'startapp' ),
			'dependency'  => array( 'element' => 'source', 'value' => array( 'categories', 'sale' ) ),
			'std'         => 'none',
			'value'       => $attributes,
		),
		array(
			'param_name'  => 'query_filter',
			'type'        => 'checkbox',
			'weight'      => 10,
			'save_always' => true,
			'group'       => __( 'Query Builder', 'startapp' ),
			'value'       => array( __( 'Empty', 'startapp' ) => 'empty' ),
			'dependency'  => array( 'callback' => 'startappWCProductsAttributeDependencyCallback' ),
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
	), 'startapp_products' ),
);
