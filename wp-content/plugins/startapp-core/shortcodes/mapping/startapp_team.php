<?php
/**
 * Team | startapp_team
 *
 * @author 8guild
 */

return array(
	'name'     => __( 'Team', 'startapp' ),
	'category' => __( 'StartApp', 'startapp' ),
	'icon'     => '',
	'params'   => startapp_vc_map_params( array_merge(
		array(
			array(
				'param_name' => 'image',
				'type'       => 'attach_image',
				'weight'     => 10,
				'heading'    => __( 'Featured Image', 'startapp' ),
			),
			array(
				'param_name'  => 'name',
				'type'        => 'textfield',
				'weight'      => 10,
				'heading'     => __( 'Name', 'startapp' ),
				'admin_label' => true,
			),
			array(
				'param_name'       => 'position',
				'type'             => 'textfield',
				'weight'           => 10,
				'heading'          => __( 'Position', 'startapp' ),
				'edit_field_class' => 'vc_col-sm-6',
				'admin_label'      => true,
			),
			array(
				'param_name'       => 'company',
				'type'             => 'textfield',
				'weight'           => 10,
				'heading'          => __( 'Company', 'startapp' ),
				'edit_field_class' => 'vc_col-sm-6',
			),
			array(
				'param_name'  => 'company_link',
				'type'        => 'vc_link',
				'weight'      => 10,
				'description' => __( 'Link to Company website. Optional. If you set a link your company name will be linked.', 'startapp' ),
				'dependency'  => array( 'element' => 'company', 'not_empty' => true ),
			),
			array(
				'param_name' => 'about',
				'type'       => 'textarea',
				'weight'     => 10,
				'heading'    => __( 'About', 'startapp' ),
			),
		),
		(array) vc_map_integrate_shortcode( 'startapp_socials', 'socials_', __( 'Socials', 'startapp' ),
			array( 'exclude' => array( 'animation', 'alignment' ) )
		),
		array(
			array(
				'param_name'       => 'type',
				'type'             => 'dropdown',
				'weight'           => 10,
				'heading'          => __( 'Type', 'startapp' ),
				'edit_field_class' => 'vc_col-sm-6',
				'std'              => 'simple-top',
				'value'            => array(
					__( 'Simple Image Top', 'startapp' )   => 'simple-top',
					__( 'Simple Image Left', 'startapp' )  => 'simple-left',
					__( 'Simple Image Right', 'startapp' ) => 'simple-right',
					__( 'Tile Version', 'startapp' )       => 'tile',
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
				'param_name'       => 'shape',
				'type'             => 'dropdown',
				'weight'           => 10,
				'heading'          => __( 'Shape', 'startapp' ),
				'edit_field_class' => 'vc_col-sm-6',
				'dependency'       => array( 'element' => 'type', 'value_not_equal_to' => 'tile' ),
				'std'              => 'rounded',
				'value'            => array(
					__( 'Rounded', 'startapp' ) => 'rounded',
					__( 'Circle', 'startapp' )  => 'circle',
					__( 'Square', 'startapp' )  => 'square',
				),
			),
			array(
				'param_name'       => 'alignment',
				'type'             => 'dropdown',
				'weight'           => 10,
				'heading'          => __( 'Alignment', 'startapp' ),
				'edit_field_class' => 'vc_col-sm-6',
				'dependency'       => array( 'element' => 'type', 'value' => 'simple-top' ),
				'std'              => 'left',
				'value'            => array(
					__( 'Left', 'startapp' )   => 'left',
					__( 'Center', 'startapp' ) => 'center',
					__( 'Right', 'startapp' )  => 'right',
				),
			),
			array(
				'param_name' => 'is_linked',
				'type'       => 'dropdown',
				'weight'     => 10,
				'heading'    => __( 'Link to Post', 'startapp' ),
				'std'        => 'disable',
				'value'      => array(
					__( 'Enable', 'startapp' )  => 'enable',
					__( 'Disable', 'startapp' ) => 'disable',
				),
			),
			array(
				'param_name'  => 'link',
				'type'        => 'vc_link',
				'weight'      => 10,
				'description' => __( 'Optional. If you set a link your teammate\'s avatar and name will be clickable.', 'startapp' ),
				'dependency'  => array( 'element' => 'is_linked', 'value' => 'enable' ),
			),
		)
	), 'startapp_team' ),
);
