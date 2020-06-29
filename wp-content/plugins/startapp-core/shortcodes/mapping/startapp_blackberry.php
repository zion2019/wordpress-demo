<?php
/**
 * BlackBerry | startapp_blackberry
 *
 * @author 8guild
 */

return array(
	'name'     => __( 'Blackberry', 'startapp' ),
	'category' => __( 'StartApp', 'startapp' ),
	'icon'     => '',
	'params'   => startapp_vc_map_params( array(
		array(
			'param_name'  => 'text',
			'type'        => 'textfield',
			'weight'      => 10,
			'heading'     => __( 'Text', 'startapp' ),
			'admin_label' => true,
			'std'         => __( 'Available on', 'startapp' ),
		),
		array(
			'param_name' => 'link',
			'type'       => 'vc_link',
			'weight'     => 10,
		),
	), 'startapp_blackberry' ),
);
