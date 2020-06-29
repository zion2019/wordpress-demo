<?php
/**
 * Classes | startapp_classes
 *
 * @author 8guild
 */

/**
 * Get posts for dropdown field
 *
 * @see Startapp_CPT_Classes::get_classes()
 */
$classes = apply_filters( 'startapp_classes_posts', array( __( 'Choose classes', 'startapp' ) => 0 ) );

return array(
	'name'     => __( 'Classes', 'startapp' ),
	'category' => __( 'StartApp', 'startapp' ),
	'icon'     => '',
	'params'   => startapp_vc_map_params( array(
		array(
			'param_name'  => 'classes',
			'type'        => 'dropdown',
			'weight'      => 10,
			'heading'     => __( 'Classes', 'startapp' ),
			'description' => __( 'Choose classes from the dropdown list.', 'startapp' ),
			'value'       => $classes,
		),
	), 'startapp_classes' ),
);
