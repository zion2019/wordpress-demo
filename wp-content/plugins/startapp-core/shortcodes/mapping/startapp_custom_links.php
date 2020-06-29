<?php
/**
 * Custom Links | startapp_custom_links
 *
 * @author 8guild
 */

return array(
	'name'     => __( 'Custom Links', 'startapp' ),
	'category' => __( 'StartApp', 'startapp' ),
	'icon'     => '',
	'params'   => startapp_vc_map_params( array(
		array(
			'param_name' => 'content', // use the content
			'type'       => 'param_group',
			'weight'     => 10,
			'heading'    => __( 'Links', 'startapp' ),
			'params'     => array(
				array(
					'param_name'  => 'uptitle',
					'type'        => 'textfield',
					'weight'      => 10,
					'heading'     => __( 'Uptitle', 'startapp' ),
					'admin_label' => true,
				),
				array(
					'param_name' => 'link',
					'type'       => 'vc_link',
					'weight'     => 10,
				),
			),
		),
	), 'startapp_custom_links' ),
);
