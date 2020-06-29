<?php
/**
 * Fancy List | startapp_fancy_list
 *
 * @author 8guild
 */

return array(
	'name'     => __( 'Fancy List', 'startapp' ),
	'category' => __( 'StartApp', 'startapp' ),
	'icon'     => '',
	'params'   => startapp_vc_map_params( array(
		array(
			'param_name'       => 'style',
			'type'             => 'dropdown',
			'weight'           => 10,
			'heading'          => __( 'List Style', 'startapp' ),
			'edit_field_class' => 'vc_col-sm-6',
			'std'              => 'unordered',
			'value'            => array(
				__( 'Unordered', 'startapp' ) => 'unordered',
				__( 'Ordered', 'startapp' )   => 'ordered',
				__( 'Image', 'startapp' )     => 'image',
				__( 'Icon', 'startapp' )      => 'icon',
				__( 'Unstyled', 'startapp' )  => 'unstyled',
			),
		),
		array(
			'param_name'       => 'is_separator',
			'type'             => 'dropdown',
			'weight'           => 10,
			'heading'          => __( 'Add Separators', 'startapp' ),
			'edit_field_class' => 'vc_col-sm-6',
			'std'              => 'no',
			'value'            => array(
				__( 'No', 'startapp' )  => 'no',
				__( 'Yes', 'startapp' ) => 'yes',
			),
		),
		array(
			'param_name'       => 'size',
			'type'             => 'dropdown',
			'weight'           => 10,
			'heading'          => __( 'List Font Size', 'startapp' ),
			'edit_field_class' => 'vc_col-sm-6',
			'std'              => 'normal',
			'value'            => array(
				__( 'Large', 'startapp' )       => 'lg',
				__( 'Lead', 'startapp' )        => 'lead',
				__( 'Normal', 'startapp' )      => 'normal',
				__( 'Small', 'startapp' )       => 'sm',
				__( 'Extra Small', 'startapp' ) => 'xs',
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
		array(
			'param_name' => 'items',
			'type'       => 'param_group',
			'heading'    => __( 'Items', 'startapp' ),
			'dependency' => array( 'element' => 'style', 'value' => array( 'unordered', 'ordered', 'unstyled' ) ),
			'params'     => array(
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
			'param_name' => 'items_image',
			'type'       => 'param_group',
			'heading'    => __( 'Items', 'startapp' ),
			'dependency' => array( 'element' => 'style', 'value' => 'image' ),
			'params'     => array(
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
				array(
					'param_name' => 'image',
					'type'       => 'attach_image',
					'weight'     => 10,
					'heading'    => __( 'Icon', 'startapp' ),
				),
			),
		),
		array(
			'param_name' => 'items_icon',
			'type'       => 'param_group',
			'heading'    => __( 'Items', 'startapp' ),
			'dependency' => array( 'element' => 'style', 'value' => 'icon' ),
			'params'     => array(
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
				array(
					'param_name' => 'icon_library',
					'type'       => 'dropdown',
					'weight'     => 10,
					'group'      => __( 'Icon', 'startapp' ),
					'heading'    => __( 'Icon Library', 'startapp' ),
					'dependency' => array( 'element' => 'is_icon', 'value' => 'enable' ),
					'value'      => array(
						__( 'Material', 'startapp' ) => 'material',
						__( 'Custom', 'startapp' )   => 'custom',
					),
				),
				array(
					'param_name' => 'icon_material',
					'type'       => 'iconpicker',
					'weight'     => 10,
					'group'      => __( 'Icon', 'startapp' ),
					'heading'    => __( 'Icon', 'startapp' ),
					'settings'   => array( 'type' => 'material', 'iconsPerPage' => 500 ),
					'dependency' => array( 'element' => 'icon_library', 'value' => 'material' ),
				),
				array(
					'param_name'  => 'icon_custom',
					'type'        => 'textfield',
					'weight'      => 10,
					'heading'     => __( 'Icon', 'startapp' ),
					'dependency'  => array( 'element' => 'icon_library', 'value' => 'custom' ),
					'description' => wp_kses( __( 'Class should be written without leading dot. There are two options to add custom icons: <ol><li>You can link your custom icon CSS file in Theme Options > Advanced</li><li>You can manually enqueue custom icons CSS file in the Child Theme</li></ol>', 'startapp' ), array(
						'ol' => true,
						'li' => true,
					) ),
				),
				array(
					'param_name' => 'icon_color',
					'type'       => 'dropdown',
					'weight'     => 10,
					'heading'    => __( 'Icon Color', 'startapp' ),
					'dependency' => array( 'element' => 'style', 'value' => 'icon' ),
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
					'param_name' => 'icon_color_custom',
					'type'       => 'colorpicker',
					'weight'     => 10,
					'heading'    => __( 'Custom Icon Color', 'startapp' ),
					'dependency' => array( 'element' => 'icon_color', 'value' => 'custom' ),
				),
			),
		),
	), 'startapp_fancy_list' ),
);
