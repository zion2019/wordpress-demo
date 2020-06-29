<?php
/**
 * Contacts Tile | startapp_contacts_tile
 *
 * @author 8guild
 */

return array(
	'name'     => __( 'Contacts Tile', 'startapp' ),
	'category' => __( 'StartApp', 'startapp' ),
	'icon'     => '',
	'params'   => startapp_vc_map_params( array_merge(
		array(
			array(
				'param_name' => 'flag',
				'type'       => 'attach_image',
				'weight'     => 10,
				'heading'    => __( 'Choose Country Flag', 'startapp' ),
			),
			array(
				'param_name'       => 'country',
				'type'             => 'textfield',
				'weight'           => 10,
				'heading'          => __( 'Country', 'startapp' ),
				'admin_label'      => true,
				'edit_field_class' => 'vc_col-sm-6',
			),
			array(
				'param_name'       => 'city',
				'type'             => 'textfield',
				'weight'           => 10,
				'heading'          => __( 'City', 'startapp' ),
				'admin_label'      => true,
				'edit_field_class' => 'vc_col-sm-6',
			),
			array(
				'param_name' => 'content',
				'type'       => 'textarea_html',
				'weight'     => 10,
				'heading'    => __( 'Info', 'startapp' ),
			),
			array(
				'param_name' => 'type',
				'type'       => 'dropdown',
				'weight'     => 10,
				'heading'    => __( 'Choose Media Type', 'startapp' ),
				'std'        => 'image',
				'value'      => array(
					__( 'Image', 'startapp' )       => 'image',
					__( 'Google Maps', 'startapp' ) => 'map',
				),
			),
			array(
				'param_name' => 'image',
				'type'       => 'attach_image',
				'weight'     => 10,
				'heading'    => __( 'Map Screenshot', 'startapp' ),
				'dependency' => array( 'element' => 'type', 'value' => 'image' )
			),
		),
		(array) vc_map_integrate_shortcode( 'startapp_map', 'map_', '',
			array( 'exclude_regex' => '/animation|class/' ),
			array( 'element' => 'type', 'value' => 'map' )
		)
	), 'startapp_contacts_tile' ),
);
