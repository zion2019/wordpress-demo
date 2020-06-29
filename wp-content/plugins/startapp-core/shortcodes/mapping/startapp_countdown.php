<?php
/**
 * Countdown | startapp_countdown
 *
 * @author 8guild
 */

return array(
	'name'     => __( 'Countdown', 'startapp' ),
	'category' => __( 'StartApp', 'startapp' ),
	'icon'     => '',
	'params'   => startapp_vc_map_params( array(
		array(
			'param_name'  => 'date',
			'type'        => 'textfield',
			'weight'      => 10,
			'heading'     => __( 'Date', 'startapp' ),
			'description' => __( 'Add a due date in format "Month/Day/Year Hour:Minute:Second", for example 12/15/2016 12:00:00. You can skip the date or time parts.', 'startapp' ),
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

	), 'startapp_countdown' ),
);
