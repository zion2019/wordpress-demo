<?php
/**
 * Gallery | startapp_gallery
 *
 * @author 8guild
 */

return array(
	'name'     => __( 'Gallery', 'startapp' ),
	'category' => __( 'StartApp', 'startapp' ),
	'icon'     => 'icon-wpb-images-stack',
	'params'   => startapp_vc_map_params( array(
		array(
			'param_name' => 'images',
			'type'       => 'attach_images',
			'weight'     => 10,
			'heading'    => __( 'Images', 'startapp' ),
		),
		array(
			'param_name' => 'is_caption',
			'type'       => 'dropdown',
			'weight'     => 10,
			'heading'    => __( 'Caption', 'startapp' ),
			'std'        => 'disable',
			'value'      => array(
				__( 'Enable', 'startapp' )  => 'enable',
				__( 'Disable', 'startapp' ) => 'disable',
			),
		),
		array(
			'param_name' => 'grid_type',
			'type'       => 'dropdown',
			'weight'     => 10,
			'heading'    => __( 'Grid Type', 'startapp' ),
			'edit_field_class' => 'vc_col-sm-6',
			'std'        => 'masonry',
			'value'      => array(
				__( 'Masonry Grid', 'startapp' )        => 'masonry-grid',
				__( 'Masonry Grid No Gap', 'startapp' ) => 'masonry-grid grid-no-gap',
				__( 'Justified Grid', 'startapp' )      => 'grid-justified',
			),
		),
		array(
			'param_name' => 'columns',
			'type'       => 'dropdown',
			'weight'     => 10,
			'heading'    => __( 'Grid Columns', 'startapp' ),
			'edit_field_class' => 'vc_col-sm-6',
			'dependency'  => array( 'element' => 'grid_type', 'value' => array( 'masonry-grid', 'masonry-grid grid-no-gap' ) ),
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
	), 'startapp_gallery' ),
);
