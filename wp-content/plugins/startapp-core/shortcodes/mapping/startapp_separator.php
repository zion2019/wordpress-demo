<?php
/**
 * Separator | startapp_separator
 *
 * @author 8guild
 */

return array(
	'name'        => __( 'Separator', 'startapp' ),
	'category'    => __( 'StartApp', 'startapp' ),
	'icon'        => 'icon-wpb-ui-separator',
	'description' => __( 'Horizontal separator line', 'startapp' ),
	'params'      => startapp_vc_map_params( array(
		array(
			'param_name' => 'style',
			'type'       => 'dropdown',
			'weight'     => 10,
			'heading'    => __( 'Select Style', 'startapp' ),
			'std'        => 'solid',
			'value'      => array(
				__( 'Solid', 'startapp' )  => 'solid',
				__( 'Dotted', 'startapp' ) => 'dotted',
				__( 'Dashed', 'startapp' ) => 'dashed',
				__( 'Double', 'startapp' ) => 'double',
			),
		),
		array(
			'param_name' => 'color',
			'type'       => 'dropdown',
			'weight'     => 10,
			'heading'    => __( 'Select Color', 'startapp' ),
			'std'        => 'primary',
			'value'      => array(
				__( 'Default', 'startapp' ) => 'default',
				__( 'Primary', 'startapp' ) => 'primary',
				__( 'Success', 'startapp' ) => 'success',
				__( 'Info', 'startapp' )    => 'info',
				__( 'Warning', 'startapp' ) => 'warning',
				__( 'Danger', 'startapp' )  => 'danger',
				__( 'Gray', 'startapp' )    => 'gray',
				__( 'Light', 'startapp' )   => 'light',
				__( 'Custom', 'startapp' )  => 'custom',
			),
		),
		array(
			'param_name' => 'color_custom',
			'type'       => 'colorpicker',
			'weight'     => 10,
			'heading'    => __( 'Custom Color', 'startapp' ),
			'dependency' => array( 'element' => 'color', 'value' => 'custom' ),
		),
		array(
			'param_name'  => 'border_width',
			'type'        => 'textfield',
			'weight'      => 10,
			'heading'     => __( 'Border Width', 'startapp' ),
			'description' => __( 'Any positive integer number.', 'startapp' ),
			'value'       => 1,
		),
		array(
			'param_name'  => 'opacity',
			'type'        => 'textfield',
			'weight'      => 10,
			'heading'     => __( 'Opacity', 'startapp' ),
			'description' => __( 'Accepts only positive numbers from 0 to 100.', 'startapp' ),
			'value'       => 25,
		),
	), 'startapp_separator' ),
);
