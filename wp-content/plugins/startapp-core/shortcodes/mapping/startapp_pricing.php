<?php
/**
 * Pricing Plan | startapp_pricing
 *
 * @author 8guild
 */

return array(
	'name'     => __( 'Pricing Plan', 'startapp' ),
	'category' => __( 'StartApp', 'startapp' ),
	'icon'     => '',
	'params'   => startapp_vc_map_params( array_merge(
		array(
			array(
				'param_name' => 'layout',
				'type'       => 'dropdown',
				'weight'     => 10,
				'heading'    => __( 'Layout', 'startapp' ),
				'std'        => 'layout-1',
				'value'      => array(
					__( 'Version 1', 'startapp' ) => 'v1',
					__( 'Version 2', 'startapp' ) => 'v2',
					__( 'Version 3', 'startapp' ) => 'v3',
				),
			),
			array(
				'param_name' => 'image',
				'type'       => 'attach_image',
				'weight'     => 10,
				'heading'    => __( 'Featured Image', 'startapp' ),
			),
			array(
				'param_name'       => 'size',
				'type'             => 'dropdown',
				'weight'           => 10,
				'heading'          => __( 'Image Size', 'startapp' ),
				'edit_field_class' => 'vc_col-sm-6',
				'std'              => 'sm',
				'value'            => array(
					__( 'Large', 'startapp' ) => 'lg',
					__( 'Small', 'startapp' ) => 'sm',
				),
			),
			array(
				'param_name'       => 'is_badge',
				'type'             => 'dropdown',
				'weight'           => 10,
				'heading'          => __( 'Enable / Disable Badge', 'startapp' ),
				'edit_field_class' => 'vc_col-sm-6',
				'std'              => 'disable',
				'value'            => array(
					__( 'Enable', 'startapp' )  => 'enable',
					__( 'Disable', 'startapp' ) => 'disable',
				),
			),
			array(
				'param_name' => 'badge',
				'type'       => 'textfield',
				'weight'     => 10,
				'heading'    => __( 'Badge text', 'startapp' ),
				'group'      => __( 'Badge', 'startapp' ),
				'dependency' => array( 'element' => 'is_badge', 'value' => 'enable' ),
			),
			array(
				'param_name' => 'badge_color',
				'type'       => 'dropdown',
				'weight'     => 10,
				'heading'    => __( 'Color', 'startapp' ),
				'group'      => __( 'Badge', 'startapp' ),
				'dependency' => array( 'element' => 'is_badge', 'value' => 'enable' ),
				'std'        => 'default',
				'value'      => array(
					__( 'Default', 'startapp' ) => 'default',
					__( 'Primary', 'startapp' ) => 'primary',
					__( 'Success', 'startapp' ) => 'success',
					__( 'Info', 'startapp' )    => 'info',
					__( 'Warning', 'startapp' ) => 'warning',
					__( 'Danger', 'startapp' )  => 'danger',
					__( 'Muted', 'startapp' )   => 'muted',
					__( 'Gray', 'startapp' )    => 'gray',
					__( 'Light', 'startapp' )   => 'light',
					__( 'Custom', 'startapp' )  => 'custom',
				),
			),
			array(
				'param_name' => 'badge_color_custom',
				'type'       => 'colorpicker',
				'weight'     => 10,
				'heading'    => __( 'Custom Color', 'startapp' ),
				'group'      => __( 'Badge', 'startapp' ),
				'dependency' => array( 'element' => 'badge_color', 'value' => 'custom' ),
			),
			array(
				'param_name'  => 'name',
				'type'        => 'textfield',
				'weight'      => 10,
				'heading'     => __( 'Name', 'startapp' ),
				'admin_label' => true,
			),
			array(
				'param_name' => 'description',
				'type'       => 'textarea',
				'weight'     => 10,
				'heading'    => __( 'Description', 'startapp' ),
			),
			array(
				'param_name'       => 'price',
				'type'             => 'textfield',
				'weight'           => 10,
				'heading'          => __( 'Price', 'startapp' ),
				'edit_field_class' => 'vc_col-sm-6',
				'admin_label'      => true,
			),
			array(
				'param_name'       => 'label',
				'type'             => 'textfield',
				'weight'           => 10,
				'heading'          => __( 'Price Label', 'startapp' ),
				'edit_field_class' => 'vc_col-sm-6',
			),
			array(
				'param_name' => 'features',
				'type'       => 'param_group',
				'heading'    => __( 'Features', 'startapp' ),
				'params'     => array(
					array(
						'param_name'  => 'title',
						'type'        => 'textfield',
						'weight'      => 10,
						'heading'     => __( 'Title', 'startapp' ),
						'admin_label' => true,
					),
					array(
						'param_name'  => 'description',
						'type'        => 'textarea',
						'weight'      => 10,
						'heading'     => __( 'List', 'startapp' ),
						'description' => __( 'Add a list of features divided by newline', 'startapp' ),
					),
				),
			),
		),
		(array) vc_map_integrate_shortcode( 'startapp_button', 'button_', __( 'Button', 'startapp' ),
			array( 'exclude_regex' => '/animation|icon/' )
		)
	), 'startapp_pricing' ),
);
