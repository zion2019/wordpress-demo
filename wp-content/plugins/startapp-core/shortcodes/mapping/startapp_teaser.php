<?php
/**
 * Teaser | startapp_teaser
 *
 * @author 8guild
 */

return array(
	'name'     => __( 'Teaser', 'startapp' ),
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
				'param_name'  => 'title',
				'type'        => 'textfield',
				'weight'      => 10,
				'heading'     => __( 'Title', 'startapp' ),
				'admin_label' => true,
			),
			array(
				'param_name' => 'tag',
				'type'       => 'dropdown',
				'weight'     => 10,
				'heading'    => __( 'Title Size', 'startapp' ),
				'std'        => 'h3',
				'value'      => array(
					'H1' => 'h1',
					'H2' => 'h2',
					'H3' => 'h3',
					'H4' => 'h4',
					'H5' => 'h5',
					'H6' => 'h6',
				),
			),
			array(
				'param_name' => 'content',
				'type'       => 'textarea_html',
				'weight'     => 10,
				'heading'    => __( 'Body Text', 'startapp' ),
			),
			array(
				'param_name' => 'alignment',
				'type'       => 'dropdown',
				'weight'     => 10,
				'heading'    => __( 'Alignment', 'startapp' ),
				'std'        => 'left',
				'value'      => array(
					__( 'Left', 'startapp' )   => 'left',
					__( 'Center', 'startapp' ) => 'center',
					__( 'Right', 'startapp' )  => 'right',
				),
			),
		),
		(array) vc_map_integrate_shortcode( 'startapp_button', 'button_', __( 'Button', 'startapp' ),
			array( 'exclude_regex' => '/(animation|icon)/' )
		)
	), 'startapp_teaser' ),
);
