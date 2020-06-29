<?php
/**
 * Blog | startapp_blog
 *
 * @author 8guild
 */

/**
 * Get posts for autocomplete field
 *
 * @see startapp_core_blog_posts()
 */
$posts = apply_filters( 'startapp_blog_posts_autocomplete', array() );

/**
 * Get post terms for autocomplete field
 *
 * @see startapp_core_blog_terms()
 */
$terms = apply_filters( 'startapp_blog_terms_autocomplete', array() );

return array(
	'name'     => __( 'Blog', 'startapp' ),
	'category' => __( 'StartApp', 'startapp' ),
	'icon'     => 'vc_icon-vc-masonry-media-grid',
	'params'   => startapp_vc_map_params( array(
		array(
			'param_name' => 'layout',
			'type'       => 'dropdown',
			'weight'     => 10,
			'heading'    => __( 'Layout', 'startapp' ),
			'value'      => array(
				__( 'List', 'startapp' )        => 'list',
				__( 'Grid', 'startapp' )        => 'grid',
				__( 'Simple List', 'startapp' ) => 'simple',
			),
		),
		array(
			'param_name' => 'columns',
			'type'       => 'dropdown',
			'weight'     => 10,
			'heading'    => __( 'Number of Columns', 'startapp' ),
			'std'        => 3,
			'dependency' => array( 'element' => 'layout', 'value' => 'grid' ),
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
			'param_name'  => 'source',
			'type'        => 'dropdown',
			'weight'      => 10,
			'heading'     => __( 'Data source', 'startapp' ),
			'description' => __( 'Choose the "List of IDs" if you want to retrieve some specific posts. If you choose the "Posts" further you can clarify the request.', 'startapp' ),
			'group'       => __( 'Query Builder', 'startapp' ),
			'value'       => array(
				__( 'Posts', 'startapp' ) => 'posts',
				__( 'IDs', 'startapp' )   => 'ids',
			),
		),
		array(
			'param_name'  => 'query_post__in',
			'type'        => 'autocomplete',
			'weight'      => 10,
			'heading'     => __( 'Posts to retrieve', 'startapp' ),
			'description' => __( 'Specify items you want to retrieve, by title', 'startapp' ),
			'group'       => __( 'Query Builder', 'startapp' ),
			'dependency'  => array( 'element' => 'source', 'value' => 'ids' ),
			'settings'    => array(
				'multiple'       => true,
				'sortable'       => true,
				'unique_values'  => true,
				'display_inline' => false,
				'min_length'     => 2,
				'values'         => $posts,
			),
		),
		array(
			'param_name'  => 'query_taxonomies',
			'type'        => 'autocomplete',
			'weight'      => 10,
			'heading'     => __( 'Specify the source', 'startapp' ),
			'description' => __( 'You can specify post categories, tags or custom taxonomies. NOTE: Try to avoid using terms with the same slug.', 'startapp' ),
			'group'       => __( 'Query Builder', 'startapp' ),
			'dependency'  => array( 'element' => 'source', 'value' => 'posts' ),
			'settings'    => array(
				'multiple'       => true,
				'sortable'       => true,
				'unique_values'  => true,
				'groups'         => true,
				'display_inline' => true,
				'min_length'     => 2,
				'values'         => $terms,
			),
		),
		array(
			'param_name'  => 'query_post__not_in',
			'type'        => 'autocomplete',
			'weight'      => 10,
			'heading'     => __( 'Exclude posts', 'startapp' ),
			'description' => __( 'Exclude some posts from results, by title.', 'startapp' ),
			'group'       => __( 'Query Builder', 'startapp' ),
			'dependency'  => array( 'element' => 'source', 'value' => 'posts' ),
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
			'heading'     => __( 'Number of posts', 'startapp' ),
			'description' => __( 'Any number or "all" for displaying all posts.', 'startapp' ),
			'group'       => __( 'Query Builder', 'startapp' ),
			'value'       => 'all',
			'dependency'  => array( 'element' => 'source', 'value_not_equal_to' => 'ids' ),
		),
		array(
			'param_name'       => 'query_orderby',
			'type'             => 'dropdown',
			'weight'           => 10,
			'heading'          => __( 'Order by', 'startapp' ),
			'group'            => __( 'Query Builder', 'startapp' ),
			'edit_field_class' => 'vc_col-sm-6',
			'std'              => 'date',
			'value'            => array(
				__( 'Post ID', 'startapp' )            => 'ID',
				__( 'Author', 'startapp' )             => 'author',
				__( 'Post name (slug)', 'startapp' )   => 'name',
				__( 'Date', 'startapp' )               => 'date',
				__( 'Last Modified Date', 'startapp' ) => 'modified',
				__( 'Number of comments', 'startapp' ) => 'comment_count',
				__( 'Random', 'startapp' )             => 'rand',
				__( 'Manually', 'startapp' )           => 'post__in',
			),
		),
		array(
			'param_name'       => 'query_order',
			'type'             => 'dropdown',
			'weight'           => 10,
			'heading'          => __( 'Sorting', 'startapp' ),
			'group'            => __( 'Query Builder', 'startapp' ),
			'edit_field_class' => 'vc_col-sm-6',
			'std'              => 'DESC',
			'value'            => array(
				__( 'Descending', 'startapp' ) => 'DESC',
				__( 'Ascending', 'startapp' )  => 'ASC',
			),
		),
		array(
			'param_name' => 'pagination',
			'type'       => 'dropdown',
			'weight'     => 10,
			'heading'    => __( 'Pagination', 'startapp' ),
			'std'        => 'load-more',
			'value'      => array(
				__( 'Disable', 'startapp' )         => 'disable',
				__( 'Load More', 'startapp' )       => 'load-more',
				__( 'Infinity Scroll', 'startapp' ) => 'infinity-scroll',

			),
		),
		array(
			'param_name'       => 'more_text',
			'type'             => 'textfield',
			'weight'           => 10,
			'heading'          => __( 'More Text', 'startapp' ),
			'description'      => __( 'This text will be displayed on the Load More button.', 'startapp' ),
			'edit_field_class' => 'vc_col-sm-6',
			'value'            => __( 'Load More', 'startapp' ),
			'dependency'       => array( 'element' => 'pagination', 'value' => 'load-more' ),
		),
		array(
			'param_name'       => 'more_pos',
			'type'             => 'dropdown',
			'weight'           => 10,
			'heading'          => __( 'More Position', 'startapp' ),
			'edit_field_class' => 'vc_col-sm-6',
			'dependency'       => array( 'element' => 'pagination', 'value' => 'load-more' ),
			'std'              => 'left',
			'value'            => array(
				__( 'Left', 'startapp' )   => 'left',
				__( 'Center', 'startapp' ) => 'center',
				__( 'Right', 'startapp' )  => 'right',
			),
		),

	), 'startapp_blog' ),
);
