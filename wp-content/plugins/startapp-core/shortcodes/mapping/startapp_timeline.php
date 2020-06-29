<?php
/**
 * Timeline | startapp_timeline
 *
 * @author 8guild
 */

/**
 * Get post terms for autocomplete field
 *
 * @see Startapp_CPT_Timeline::get_autocomplete_categories()
 */
$categories = apply_filters( 'startapp_timeline_categories_autocomplete', array() );

return array(
	'name'     => __( 'Timeline', 'startapp' ),
	'category' => __( 'StartApp', 'startapp' ),
	'icon'     => '',
	'params'   => startapp_vc_map_params( array(
		array(
			'param_name' => 'categories',
			'type'       => 'autocomplete',
			'weight'     => 10,
			'heading'    => __( 'Categories', 'startapp' ),
			'settings'   => array(
				'multiple'       => true,
				'unique_values'  => true,
				'display_inline' => true,
				'min_length'     => 2,
				'values'         => $categories,
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
			'param_name' => 'arrows_shape',
			'type'       => 'dropdown',
			'weight'     => 10,
			'heading'    => __( 'Arrows Shape', 'startapp' ),
			'group'      => __( 'Controls', 'startapp' ),
			'std'        => 'rounded',
			'value'      => array(
				__( 'Rounded', 'startapp' ) => 'rounded',
				__( 'Circle', 'startapp' )  => 'circle',
				__( 'Square', 'startapp' )  => 'square',
			),
		),
		array(
			'param_name' => 'arrows_size',
			'type'       => 'dropdown',
			'weight'     => 10,
			'heading'    => __( 'Arrows Size', 'startapp' ),
			'group'      => __( 'Controls', 'startapp' ),
			'std'        => 'sm',
			'value'      => array(
				__( 'Small', 'startapp' ) => 'sm',
				__( 'Large', 'startapp' ) => 'lg',
			),
		),
	), 'startapp_timeline' ),
);
