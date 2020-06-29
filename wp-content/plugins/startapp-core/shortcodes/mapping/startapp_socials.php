<?php
/**
 * Social Buttons | startapp_socials
 *
 *
 * @author 8guild
 */

return array(
	'name'     => __( 'Social Buttons', 'startapp' ),
	'category' => __( 'StartApp', 'startapp' ),
	'icon'     => '',
	'params'   => startapp_vc_map_params( array(
		array(
			'param_name'  => 'socials',
			'type'        => 'param_group',
			'heading'     => __( 'Socials', 'startapp' ),
			'description' => __( 'Choose your social networks', 'startapp' ),
			'value'       => urlencode( json_encode( array(
				array(
					'network' => 'twitter',
					'url'     => 'https://twitter.com/8guild',
				),
				array(
					'network' => 'facebook',
					'url'     => '#',
				),
			) ) ),
			'params'      => array(
				array(
					'param_name'  => 'network',
					'type'        => 'dropdown',
					'weight'      => 10,
					'heading'     => __( 'Network', 'startapp' ),
					'description' => __( 'Choose the network from the given list.', 'startapp' ),
					'value'       => call_user_func( function() {
						$networks = startapp_get_networks();
						if ( empty( $networks ) ) {
							return array();
						}

						$result = array();
						foreach ( $networks as $network => $data ) {
							$name            = $data['name'];
							$result[ $name ] = $network;
							unset( $name );
						}
						unset( $network, $data );

						return $result;
					} ),
					'admin_label' => true,
				),
				array(
					'param_name'  => 'url',
					'type'        => 'textfield',
					'weight'      => 10,
					'heading'     => __( 'URL', 'startapp' ),
					'description' => __( 'Enter the link to your social networks', 'startapp' ),
					'admin_label' => true,
				),
			),
		),
		array(
			'param_name'       => 'type',
			'type'             => 'dropdown',
			'weight'           => 10,
			'heading'          => __( 'Type', 'startapp' ),
			'edit_field_class' => 'vc_col-sm-6',
			'value'            => array(
				__( 'Only Border', 'startapp' )      => 'border',
				__( 'Solid Background', 'startapp' ) => 'solid-bg',
			),
		),
		array(
			'param_name'       => 'shape',
			'type'             => 'dropdown',
			'weight'           => 10,
			'heading'          => __( 'Shape', 'startapp' ),
			'edit_field_class' => 'vc_col-sm-6',
			'value'            => array(
				__( 'Rounded', 'startapp' ) => 'rounded',
				__( 'Circle', 'startapp' )  => 'circle',
				__( 'Square', 'startapp' )  => 'square',
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
			'param_name'       => 'alignment',
			'type'             => 'dropdown',
			'weight'           => 10,
			'heading'          => __( 'Alignment', 'startapp' ),
			'edit_field_class' => 'vc_col-sm-6',
			'value'            => array(
				__( 'Left', 'startapp' )   => 'left',
				__( 'Center', 'startapp' ) => 'center',
				__( 'Right', 'startapp' )  => 'right',
				__( 'Inline', 'startapp' ) => 'inline',
			),
		),
		array(
			'param_name'       => 'is_tooltips',
			'type'             => 'dropdown',
			'weight'           => 10,
			'heading'          => __( 'Enable / Disable Tooltips', 'startapp' ),
			'edit_field_class' => 'vc_col-sm-6',
			'std'              => 'disable',
			'value'            => array(
				__( 'Enable', 'startapp' )  => 'enable',
				__( 'Disable', 'startapp' ) => 'disable',
			),
		),
		array(
			'param_name'       => 'is_waves',
			'type'             => 'dropdown',
			'weight'           => 10,
			'heading'          => __( 'Waves Effect', 'startapp' ),
			'edit_field_class' => 'vc_col-sm-4',
			'std'              => 'disable',
			'value'            => array(
				__( 'Enable', 'startapp' )  => 'enable',
				__( 'Disable', 'startapp' ) => 'disable',
			),
		),
		array(
			'param_name'       => 'tooltips_position',
			'type'             => 'dropdown',
			'weight'           => 10,
			'heading'          => __( 'Tooltips Position', 'startapp' ),
			'edit_field_class' => 'vc_col-sm-6',
			'dependency'       => array( 'element' => 'is_tooltips', 'value' => 'enable' ),
			'value'            => array(
				__( 'Top', 'startapp' )    => 'top',
				__( 'Right', 'startapp' )  => 'right',
				__( 'Left', 'startapp' )   => 'left',
				__( 'Bottom', 'startapp' ) => 'bottom',
			),
		),
		array(
			'param_name'       => 'waves_color',
			'type'             => 'dropdown',
			'weight'           => 10,
			'heading'          => __( 'Waves Color', 'startapp' ),
			'dependency'       => array( 'element' => 'is_waves', 'value' => 'enable' ),
			'edit_field_class' => 'vc_col-sm-6',
			'std'              => 'light',
			'value'            => array(
				__( 'Dark', 'startapp' )  => 'dark',
				__( 'Light', 'startapp' ) => 'light',
			),
		),
	), 'startapp_socials' ),
);
