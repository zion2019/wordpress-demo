<?php
/**
 * Animated Digits | startapp_animated_digits
 *
 * @author 8guild
 */

return array(
	'name'     => __( 'Animated Digits', 'startapp' ),
	'category' => __( 'StartApp', 'startapp' ),
	'icon'     => '',
	'params'   => startapp_vc_map_params( array(
		array(
			'param_name'  => 'digit',
			'type'        => 'textfield',
			'weight'      => 10,
			'heading'     => __( 'Digit', 'startapp' ),
			'admin_label' => true,
		),
		array(
			'param_name'  => 'unit',
			'type'        => 'textfield',
			'weight'      => 10,
			'heading'     => __( 'Unit', 'startapp' ),
			'admin_label' => true,
		),
		array(
			'param_name'  => 'description',
			'type'        => 'textarea',
			'weight'      => 10,
			'heading'     => __( 'Description', 'startapp' ),
			'admin_label' => true,
		),
		array(
			'param_name'         => 'color',
			'type'               => 'dropdown',
			'weight'             => 10,
			'heading'            => __( 'Color Skin', 'startapp' ),
			'group'              => __( 'Design', 'startapp' ),
			'param_holder_class' => 'guild-colored',
			'value'              => array(
				__( 'Default', 'startapp' ) => 'default',
				__( 'Primary', 'startapp' ) => 'primary',
				__( 'Info', 'startapp' )    => 'info',
				__( 'Success', 'startapp' ) => 'success',
				__( 'Warning', 'startapp' ) => 'warning',
				__( 'Danger', 'startapp' )  => 'danger',
				__( 'Light', 'startapp' )   => 'light',
				__( 'Custom', 'startapp' )  => 'custom',
			),
		),
		array(
			'param_name' => 'color_custom',
			'type'       => 'colorpicker',
			'weight'     => 10,
			'heading'    => __( 'Color Skin Custom', 'startapp' ),
			'group'      => __( 'Design', 'startapp' ),
			'dependency' => array( 'element' => 'color', 'value' => 'custom' ),
		),

	), 'startapp_animated_digits' ),
);
