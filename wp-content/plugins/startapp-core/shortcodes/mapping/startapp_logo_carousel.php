<?php
/**
 * Logo Carousel | startapp_logo_carousel
 *
 * @author 8guild
 */

return array(
	'name'     => __( 'Logo Carousel', 'startapp' ),
	'category' => __( 'StartApp', 'startapp' ),
	'icon'     => '',
	'params'   => startapp_vc_map_params( array(
		array(
			'param_name' => 'content',
			'type'       => 'param_group',
			'weight'     => 10,
			'params'     => array(
				array(
					'param_name' => 'logo',
					'type'       => 'attach_image',
					'weight'     => 10,
					'heading'    => __( 'Logo', 'startapp' ),
				),
				array(
					'param_name' => 'link',
					'type'       => 'vc_link',
					'weight'     => 10,
					'heading'    => __( 'Link', 'startapp' ),
				),
				array(
					'param_name'  => 'title',
					'type'        => 'textfield',
					'weight'      => 10,
					'heading'     => __( 'Title', 'startapp' ),
					'admin_label' => true,
				),
				array(
					'param_name' => 'description',
					'type'       => 'textarea',
					'weight'     => 10,
					'heading'    => __( 'Description', 'startapp' ),
				),
			),
		),
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
			'param_name' => 'is_arrows',
			'type'       => 'dropdown',
			'weight'     => 10,
			'heading'    => __( 'Arrows', 'startapp' ),
			'group'      => __( 'Controls', 'startapp' ),
			'std'        => 'enable',
			'value'      => array(
				__( 'Show', 'startapp' ) => 'enable',
				__( 'Hide', 'startapp' ) => 'disable',
			),
		),
		array(
			'param_name' => 'is_dots',
			'type'       => 'dropdown',
			'weight'     => 10,
			'heading'    => __( 'Dots', 'startapp' ),
			'group'      => __( 'Controls', 'startapp' ),
			'std'        => 'enable',
			'value'      => array(
				__( 'Show', 'startapp' ) => 'enable',
				__( 'Hide', 'startapp' ) => 'disable',
			),
		),
		array(
			'param_name' => 'is_loop',
			'type'       => 'dropdown',
			'weight'     => 10,
			'heading'    => __( 'Loop', 'startapp' ),
			'group'      => __( 'Behavior', 'startapp' ),
			'std'        => 'disable',
			'value'      => array(
				__( 'Enable', 'startapp' )  => 'enable',
				__( 'Disable', 'startapp' ) => 'disable',
			),
		),
		array(
			'param_name' => 'is_autoplay',
			'type'       => 'dropdown',
			'weight'     => 10,
			'heading'    => __( 'Autoplay', 'startapp' ),
			'group'      => __( 'Behavior', 'startapp' ),
			'std'        => 'disable',
			'value'      => array(
				__( 'Enable', 'startapp' )  => 'enable',
				__( 'Disable', 'startapp' ) => 'disable',
			),
		),
		array(
			'param_name'  => 'autoplay_speed',
			'type'        => 'textfield',
			'weight'      => 10,
			'heading'     => __( 'Interval', 'startapp' ),
			'description' => __( 'Any positive integer number', 'startapp' ),
			'group'       => __( 'Behavior', 'startapp' ),
			'dependency'  => array( 'element' => 'is_autoplay', 'value' => 'enable' ),
			'value'       => '3000',
		),
		array(
			'param_name' => 'responsiveness_helper',
			'type'       => 'custom_markup',
			'group'      => __( 'Responsiveness', 'startapp' ),
			'value'      => 'Number of Visible Items for:',
		),
		array(
			'param_name'  => 'lg',
			'type'        => 'textfield',
			'weight'      => 10,
			'heading'     => __( 'Large Desktop', 'startapp' ),
			'description' => __( 'Accepts any positive integer number starting from 1', 'startapp' ),
			'group'       => __( 'Responsiveness', 'startapp' ),
			'value'       => 6,
		),
		array(
			'param_name'  => 'md',
			'type'        => 'textfield',
			'weight'      => 10,
			'heading'     => __( 'Small Desktop', 'startapp' ),
			'description' => __( 'Accepts any positive integer number starting from 1', 'startapp' ),
			'group'       => __( 'Responsiveness', 'startapp' ),
			'value'       => 5,
		),
		array(
			'param_name'  => 'sm',
			'type'        => 'textfield',
			'weight'      => 10,
			'heading'     => __( 'Tablet', 'startapp' ),
			'description' => __( 'Accepts any positive integer number starting from 1', 'startapp' ),
			'group'       => __( 'Responsiveness', 'startapp' ),
			'value'       => 3,
		),
		array(
			'param_name'  => 'xs',
			'type'        => 'textfield',
			'weight'      => 10,
			'heading'     => __( 'Mobile', 'startapp' ),
			'description' => __( 'Accepts any positive integer number starting from 1', 'startapp' ),
			'group'       => __( 'Responsiveness', 'startapp' ),
			'value'       => 1,
		),
	), 'startapp_logo_carousel' ),
);
