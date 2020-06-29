<?php
/**
 * Video Popup | startapp_video_popup
 *
 * @author 8guild
 */

return array(
	'name'     => __( 'Video Popup', 'startapp' ),
	'category' => __( 'StartApp', 'startapp' ),
	'icon'     => '',
	'params'   => startapp_vc_map_params( array(
		array(
			'param_name'  => 'video',
			'type'        => 'textfield',
			'weight'      => 10,
			'heading'     => __( 'Link to Video', 'nucleus' ),
			'description' => __( 'Paste a link to video, for example https://vimeo.com/33984473 or https://www.youtube.com/watch?v=DqO90q0WZ0M', 'startapp' ),
			'admin_label' => true,
		),
		array(
			'param_name' => 'alignment',
			'type'       => 'dropdown',
			'weight'     => 10,
			'heading'    => __( 'Alignment', 'startapp' ),
			'std'        => 'center',
			'value'      => array(
				__( 'Left', 'startapp' )   => 'left',
				__( 'Center', 'startapp' ) => 'center',
				__( 'Right', 'startapp' )  => 'right',
			),
		),
		array(
			'param_name' => 'skin',
			'type'       => 'dropdown',
			'weight'     => 10,
			'heading'    => __( 'Skin', 'startapp' ),
			'std'        => 'dark',
			'value'      => array(
				__( 'Dark', 'startapp' )   => 'dark',
				__( 'Light', 'startapp' ) => 'light',
			),
		),
	), 'startapp_video_popup' ),
);
