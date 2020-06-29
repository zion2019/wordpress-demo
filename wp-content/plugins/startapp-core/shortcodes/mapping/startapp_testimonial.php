<?php
/**
 * Testimonial | startapp_testimonial
 *
 * @author 8guild
 */

return array(
	'name'     => __( 'Testimonial', 'startapp' ),
	'category' => __( 'StartApp', 'startapp' ),
	'icon'     => '',
	'params'   => startapp_vc_map_params( array(
		array(
			'param_name' => 'background',
			'type'       => 'dropdown',
			'weight'     => 10,
			'heading'    => __( 'Background', 'startapp' ),
			'std'        => 'none',
			'value'      => array(
				__( 'None', 'startapp' )        => 'none',
				__( 'Solid Color', 'startapp' ) => 'solid',
			),
		),
		array(
			'param_name' => 'background_color',
			'type'       => 'dropdown',
			'weight'     => 10,
			'heading'    => __( 'Background Color', 'startapp' ),
			'dependency' => array( 'element' => 'background', 'value' => 'solid' ),
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
			'param_name' => 'background_color_custom',
			'type'       => 'colorpicker',
			'weight'     => 10,
			'heading'    => __( 'Custom Background Color', 'startapp' ),
			'dependency' => array( 'element' => 'background_color', 'value' => 'custom' ),
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
		array(
			'param_name' => 'content',
			'type'       => 'textarea_html',
			'weight'     => 10,
			'heading'    => __( 'Quotation', 'startapp' ),
		),
		array(
			'param_name'       => 'avatar',
			'type'             => 'attach_image',
			'weight'           => 10,
			'heading'          => __( 'Author Image', 'startapp' ),
			'edit_field_class' => 'vc_col-sm-6',
		),
		array(
			'param_name'       => 'shape',
			'type'             => 'dropdown',
			'weight'           => 10,
			'heading'          => __( 'Image Shape', 'startapp' ),
			'edit_field_class' => 'vc_col-sm-6',
			'std'              => 'circle',
			'value'            => array(
				__( 'Circle', 'startapp' )  => 'circle',
				__( 'Square', 'startapp' )  => 'square',
				__( 'Rounded', 'startapp' ) => 'rounded',
			),
		),
		array(
			'param_name'       => 'name',
			'type'             => 'textfield',
			'weight'           => 10,
			'heading'          => __( 'Author Name', 'startapp' ),
			'admin_label'      => true,
			'edit_field_class' => 'vc_col-sm-6',
		),
		array(
			'param_name'       => 'position',
			'type'             => 'textfield',
			'weight'           => 10,
			'heading'          => __( 'Author Position', 'startapp' ),
			'admin_label'      => true,
			'edit_field_class' => 'vc_col-sm-6',
		),
		array(
			'param_name' => 'company',
			'type'       => 'textfield',
			'weight'     => 10,
			'heading'    => __( 'Company', 'startapp' ),
		),
		array(
			'param_name'  => 'company_link',
			'type'        => 'vc_link',
			'weight'      => 10,
			'description' => __( 'Link to Company website. Optional. If you set a link your company name will be linked.', 'startapp' ),
			'dependency'  => array( 'element' => 'company', 'not_empty' => true ),
		),
	), 'startapp_testimonial' ),
);
