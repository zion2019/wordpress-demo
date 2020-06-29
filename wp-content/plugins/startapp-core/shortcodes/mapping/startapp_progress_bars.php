<?php
/**
 * Progress Bars | startapp_progress_bars
 *
 * @author 8guild
 */

return array(
	'name'     => __( 'Progress Bars', 'startapp' ),
	'category' => __( 'StartApp', 'startapp' ),
	'icon'     => 'icon-wpb-graph',
	'params'   => startapp_vc_map_params( array(
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
			'param_name' => 'is_units',
			'type'       => 'dropdown',
			'weight'     => 10,
			'heading'    => __( 'Show units?', 'startapp' ),
			'std'        => 'no',
			'value'      => array(
				__( 'Yes', 'startapp' ) => 'yes',
				__( 'No', 'startapp' )  => 'no',
			),
		),
		array(
			'param_name' => 'is_animation',
			'type'       => 'dropdown',
			'weight'     => 10,
			'heading'    => __( 'Animate on scroll', 'startapp' ),
			'std'        => 'no',
			'value'      => array(
				__( 'Yes', 'startapp' ) => 'yes',
				__( 'No', 'startapp' )  => 'no',
			),
		),
		array(
			'param_name' => 'bars',
			'type'       => 'param_group',
			'heading'    => __( 'Bars', 'startapp' ),
			'params'     => array(
				array(
					'param_name'  => 'value',
					'type'        => 'textfield',
					'weight'      => 10,
					'heading'     => __( 'Progress value', 'startapp' ),
					'description' => __( 'Positive integer number from 0 till 100', 'startapp' ),
					'admin_label' => true,
				),
				array(
					'param_name'  => 'label',
					'type'        => 'textfield',
					'weight'      => 10,
					'heading'     => __( 'Label', 'startapp' ),
					'admin_label' => true,
				),
				array(
					'param_name' => 'color',
					'type'       => 'dropdown',
					'weight'     => 10,
					'heading'    => __( 'Color', 'startapp' ),
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
			),
		),
	), 'startapp_progress_bars' ),
);
