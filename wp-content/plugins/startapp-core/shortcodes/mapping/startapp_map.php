<?php
/**
 * Google Maps | startapp_map
 *
 * @author 8guild
 */

return array(
	'name'     => __( 'Google Maps', 'startapp' ),
	'category' => __( 'StartApp', 'startapp' ),
	'icon'     => 'icon-wpb-map-pin',
	'params'   => startapp_vc_map_params( array(
		array(
			'param_name'  => 'location',
			'type'        => 'textfield',
			'weight'      => 10,
			'heading'     => __( 'Location', 'startapp' ),
			'description' => __( 'Enter any search query, which you can find on Google Maps, e.g. "New York, USA".', 'startapp' ),
		),
		array(
			'param_name'       => 'height',
			'type'             => 'textfield',
			'weight'           => 10,
			'heading'          => __( 'Map height', 'startapp' ),
			'description'      => __( 'Height of the map in pixels.', 'startapp' ),
			'value'            => 500,
			'edit_field_class' => 'vc_col-sm-6',
		),
		array(
			'param_name'       => 'zoom',
			'type'             => 'textfield',
			'weight'           => 10,
			'heading'          => __( 'Zoom', 'startapp' ),
			'description'      => __( 'The initial Map zoom level', 'startapp' ),
			'value'            => 14,
			'edit_field_class' => 'vc_col-sm-6',
		),
		array(
			'param_name'       => 'is_zoom',
			'type'             => 'dropdown',
			'weight'           => 10,
			'heading'          => __( 'Zoom Controls', 'startapp' ),
			'description'      => __( 'Enable or disable map controls like pan, zoom, etc.', 'startapp' ),
			'edit_field_class' => 'vc_col-sm-6',
			'std'              => 'disable',
			'value'            => array(
				__( 'Enable', 'startapp' )  => 'enable',
				__( 'Disable', 'startapp' ) => 'disable',
			),
		),
		array(
			'param_name'       => 'is_scroll',
			'type'             => 'dropdown',
			'weight'           => 10,
			'heading'          => __( 'ScrollWheel', 'startapp' ),
			'description'      => __( 'Enable or disable scrollwheel zooming on the map.', 'startapp' ),
			'edit_field_class' => 'vc_col-sm-6',
			'std'              => 'disable',
			'value'            => array(
				__( 'Enable', 'startapp' )  => 'enable',
				__( 'Disable', 'startapp' ) => 'disable',
			),
		),
		array(
			'param_name'  => 'is_marker',
			'type'        => 'dropdown',
			'weight'      => 10,
			'heading'     => __( 'Custom marker', 'startapp' ),
			'description' => __( 'Enable or disable custom marker on the map.', 'startapp' ),
			'std'         => 'disable',
			'value'       => array(
				__( 'Enable', 'startapp' )  => 'enable',
				__( 'Disable', 'startapp' ) => 'disable',
			),
		),
		array(
			'param_name'       => 'marker_title',
			'type'             => 'textfield',
			'weight'           => 10,
			'heading'          => __( 'Marker Title', 'startapp' ),
			'description'      => __( 'Optional title appears on marker hover.', 'startapp' ),
			'dependency'       => array( 'element' => 'is_marker', 'value' => 'enable' ),
			'edit_field_class' => 'vc_col-sm-6',
		),
		array(
			'param_name'       => 'marker',
			'type'             => 'attach_image',
			'weight'           => 10,
			'heading'          => __( 'Custom marker', 'startapp' ),
			'dependency'       => array( 'element' => 'is_marker', 'value' => 'enable' ),
			'edit_field_class' => 'vc_col-sm-6',
		),
		array(
			'param_name'  => 'style',
			'type'        => 'textarea_raw_html',
			'weight'      => 10,
			'heading'     => __( 'Maps custom styling', 'startapp' ),
			'description' => wp_kses( __( 'Generate your styles in <a href="https://snazzymaps.com/editor" target="_blank">Snazzymaps Editor</a> and paste JavaScript Style Array in field above', 'startapp' ), array(
				'a' => array( 'href' => true, 'target' => true ),
			) ),
		),
	), 'startapp_map' ),
);
