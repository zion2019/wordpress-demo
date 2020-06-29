<?php
/**
 * Category Tiles | startapp_category_tiles
 *
 * @author 8guild
 */

return array(
	'name'     => __( 'Category Tiles', 'startapp' ),
	'category' => __( 'StartApp', 'startapp' ),
	'icon'        => 'icon-wpb-application-icon-large',
	'params'   => startapp_vc_map_params( array(
		array(
			'param_name'  => 'slug',
			'type'        => 'autocomplete',
			'weight'      => 10,
			'heading'     => __( 'Categories', 'startapp' ),
			'description' => __( 'Specify categories you want to retrieve, by names. Or leave this field empty to get all categories.', 'startapp' ),
			'settings'    => array(
				'multiple'       => true,
				'sortable'       => false,
				'min_length'     => 1,
				'display_inline' => true,
				'values'         => apply_filters( 'startapp_category_tiles_slug_autocomlete', array() ),
			),
		),
		array(
			'param_name'  => 'number',
			'type'        => 'textfield',
			'weight'      => 10,
			'heading'     => __( 'Number of categories', 'startapp' ),
			'description' => __( 'Any positive integer or keyword "all" for fetching all categories.', 'startapp' ),
			'value'       => 'all',
		),
		array(
			'param_name'       => 'orderby',
			'type'             => 'dropdown',
			'weight'           => 10,
			'heading'          => __( 'Order by', 'startapp' ),
			'edit_field_class' => 'vc_column vc_col-sm-6',
			'std'              => 'name',
			'value'            => array(
				__( 'ID', 'startapp' )         => 'term_id',
				__( 'Slug', 'startapp' )       => 'slug',
				__( 'Name', 'startapp' )       => 'name',
				__( 'Popularity', 'startapp' ) => 'count',
				__( 'None', 'startapp' )       => 'none',
			),
		),
		array(
			'param_name'       => 'order',
			'type'             => 'dropdown',
			'weight'           => 10,
			'heading'          => __( 'Sorting', 'startapp' ),
			'edit_field_class' => 'vc_col-sm-6',
			'std'              => 'ASC',
			'value'            => array(
				__( 'Descending', 'startapp' ) => 'DESC',
				__( 'Ascending', 'startapp' )  => 'ASC',
			),
		),
		array(
			'param_name'  => 'height',
			'type'        => 'textfield',
			'weight'      => 10,
			'heading'     => __( 'Tiles Min Height', 'startapp' ),
			'description' => __( 'Any positive integer number.', 'startapp' ),
			'value'       => '335',
		),
		array(
			'param_name'  => 'columns',
			'type'        => 'dropdown',
			'weight'      => 10,
			'heading'     => __( 'Columns', 'startapp' ),
			'description' => __( 'Choose the number of columns', 'startapp' ),
			'value'       => array( 1, 2, 3, 4 ),
		),
	), 'startapp_category_tiles' ),
);
