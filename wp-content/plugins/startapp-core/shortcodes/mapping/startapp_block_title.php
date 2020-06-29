<?php
/**
 * Block Title | startapp_block_title
 *
 * @author 8guild
 */

return array(
	'name'     => __( 'Block Title', 'startapp' ),
	'category' => __( 'StartApp', 'startapp' ),
	'icon'     => 'icon-wpb-ui-separator-label',
	'params'   => startapp_vc_map_params( array(
		array(
			'param_name'  => 'title',
			'type'        => 'textfield',
			'weight'      => 10,
			'heading'     => __( 'Title', 'startapp' ),
			'admin_label' => true,
		),
		array(
			'param_name'  => 'subtitle',
			'type'        => 'textfield',
			'weight'      => 10,
			'heading'     => __( 'Subtitle', 'startapp' ),
			'admin_label' => true,
		),
		array(
			'param_name'       => 'tag_title',
			'type'             => 'dropdown',
			'weight'           => 10,
			'heading'          => __( 'Select the Title size', 'startapp' ),
			'edit_field_class' => 'vc_col-sm-6',
			'std'              => 'h2',
			'value'            => array(
				'H1' => 'h1',
				'H2' => 'h2',
				'H3' => 'h3',
				'H4' => 'h4',
				'H5' => 'h5',
				'H6' => 'h6',
			),
		),
		array(
			'param_name'       => 'tag_subtitle',
			'type'             => 'dropdown',
			'weight'           => 10,
			'heading'          => __( 'Select the Subtitle size', 'startapp' ),
			'edit_field_class' => 'vc_col-sm-6',
			'std'              => 'h4',
			'value'            => array(
				'H1' => 'h1',
				'H2' => 'h2',
				'H3' => 'h3',
				'H4' => 'h4',
				'H5' => 'h5',
				'H6' => 'h6',
			),
		),
		array(
			'param_name'       => 'alignment',
			'type'             => 'dropdown',
			'weight'           => 10,
			'heading'          => __( 'Alignment', 'startapp' ),
			'edit_field_class' => 'vc_col-sm-6',
			'std'              => 'left',
			'value'            => array(
				__( 'Left', 'startapp' )   => 'left',
				__( 'Center', 'startapp' ) => 'center',
				__( 'Right', 'startapp' )  => 'right',
			),
		),
		array(
			'param_name'       => 'skin',
			'type'             => 'dropdown',
			'weight'           => 10,
			'heading'          => __( 'Skin', 'startapp' ),
			'edit_field_class' => 'vc_col-sm-6',
			'std'              => 'dark',
			'value'            => array(
				__( 'Dark', 'startapp' )  => 'dark',
				__( 'Light', 'startapp' ) => 'light',
			),
		),
	), 'startapp_block_title' ),
);
