<?php
/**
 * Mapping all custom shortcodes in Visual Composer interface
 *
 * @author 8guild
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

if ( ! defined( 'WPB_VC_VERSION' ) ) {
	return;
}

/**
 * Remove all Visual Composer assets from the Front
 *
 * @hooked wp_enqueue_scripts 9
 */
function startapp_vc_deregister_front_assets() {
	wp_deregister_style( 'js_composer_front' );
	wp_deregister_style( 'js_composer_custom_css' );
	wp_deregister_style( 'font-awesome' );

	wp_deregister_script( 'wpb_composer_front_js' );
	wp_deregister_script( 'waypoints' );
}

add_action( 'wp_enqueue_scripts', 'startapp_vc_deregister_front_assets', 9 );

if ( ! is_admin() ) {
	return;
}

/**
 * Integrate custom shortcodes into the VC
 *
 * @uses   vc_lean_map()
 * @hooked vc_mapper_init_after 10
 */
function startapp_vc_mapping() {
	// Simple
	vc_lean_map( 'startapp_button', null, STARTAPP_CORE_ROOT . '/shortcodes/mapping/startapp_button.php' );
	vc_lean_map( 'startapp_block_title', null, STARTAPP_CORE_ROOT . '/shortcodes/mapping/startapp_block_title.php' );
	vc_lean_map( 'startapp_socials', null, STARTAPP_CORE_ROOT . '/shortcodes/mapping/startapp_socials.php' );
	vc_lean_map( 'startapp_separator', null, STARTAPP_CORE_ROOT . '/shortcodes/mapping/startapp_separator.php' );

	// Market Buttons
	vc_lean_map( 'startapp_app_store', null, STARTAPP_CORE_ROOT . '/shortcodes/mapping/startapp_app_store.php' );
	vc_lean_map( 'startapp_google_play', null, STARTAPP_CORE_ROOT . '/shortcodes/mapping/startapp_google_play.php' );
	vc_lean_map( 'startapp_windows_store', null, STARTAPP_CORE_ROOT . '/shortcodes/mapping/startapp_windows_store.php' );
	vc_lean_map( 'startapp_amazon', null, STARTAPP_CORE_ROOT . '/shortcodes/mapping/startapp_amazon.php' );
	vc_lean_map( 'startapp_blackberry', null, STARTAPP_CORE_ROOT . '/shortcodes/mapping/startapp_blackberry.php' );

	// Content
	vc_lean_map( 'startapp_blog_tile', null, STARTAPP_CORE_ROOT . '/shortcodes/mapping/startapp_blog_tile.php' );
	vc_lean_map( 'startapp_blog', null, STARTAPP_CORE_ROOT . '/shortcodes/mapping/startapp_blog.php' );
	vc_lean_map( 'startapp_icon_box', null, STARTAPP_CORE_ROOT . '/shortcodes/mapping/startapp_icon_box.php' );
	vc_lean_map( 'startapp_fancy_list', null, STARTAPP_CORE_ROOT . '/shortcodes/mapping/startapp_fancy_list.php' );
	vc_lean_map( 'startapp_team', null, STARTAPP_CORE_ROOT . '/shortcodes/mapping/startapp_team.php' );
	vc_lean_map( 'startapp_testimonial', null, STARTAPP_CORE_ROOT . '/shortcodes/mapping/startapp_testimonial.php' );
	vc_lean_map( 'startapp_pricing', null, STARTAPP_CORE_ROOT . '/shortcodes/mapping/startapp_pricing.php' );
	vc_lean_map( 'startapp_pricing_table', null, STARTAPP_CORE_ROOT . '/shortcodes/mapping/startapp_pricing_table.php' );
	vc_lean_map( 'startapp_image_carousel', null, STARTAPP_CORE_ROOT . '/shortcodes/mapping/startapp_image_carousel.php' );
	vc_lean_map( 'startapp_timeline', null, STARTAPP_CORE_ROOT . '/shortcodes/mapping/startapp_timeline.php' );
	vc_lean_map( 'startapp_testimonials_carousel', null, STARTAPP_CORE_ROOT . '/shortcodes/mapping/startapp_testimonials_carousel.php' );
	vc_lean_map( 'startapp_logo_carousel', null, STARTAPP_CORE_ROOT . '/shortcodes/mapping/startapp_logo_carousel.php' );
	vc_lean_map( 'startapp_testimonials_slider', null, STARTAPP_CORE_ROOT . '/shortcodes/mapping/startapp_testimonials_slider.php' );
	vc_lean_map( 'startapp_steps', null, STARTAPP_CORE_ROOT . '/shortcodes/mapping/startapp_steps.php' );
	vc_lean_map( 'startapp_teaser', null, STARTAPP_CORE_ROOT . '/shortcodes/mapping/startapp_teaser.php' );
	vc_lean_map( 'startapp_progress_bars', null, STARTAPP_CORE_ROOT . '/shortcodes/mapping/startapp_progress_bars.php' );
	vc_lean_map( 'startapp_contacts_tile', null, STARTAPP_CORE_ROOT . '/shortcodes/mapping/startapp_contacts_tile.php' );
	vc_lean_map( 'startapp_category_tiles', null, STARTAPP_CORE_ROOT . '/shortcodes/mapping/startapp_category_tiles.php' );
	vc_lean_map( 'startapp_map', null, STARTAPP_CORE_ROOT . '/shortcodes/mapping/startapp_map.php' );
	vc_lean_map( 'startapp_video_popup', null, STARTAPP_CORE_ROOT . '/shortcodes/mapping/startapp_video_popup.php' );
	vc_lean_map( 'startapp_custom_links', null, STARTAPP_CORE_ROOT . '/shortcodes/mapping/startapp_custom_links.php' );
	vc_lean_map( 'startapp_animated_digits', null, STARTAPP_CORE_ROOT . '/shortcodes/mapping/startapp_animated_digits.php' );
	vc_lean_map( 'startapp_countdown', null, STARTAPP_CORE_ROOT . '/shortcodes/mapping/startapp_countdown.php' );
	vc_lean_map( 'startapp_portfolio_tile', null, STARTAPP_CORE_ROOT . '/shortcodes/mapping/startapp_portfolio_tile.php' );
	vc_lean_map( 'startapp_portfolio', null, STARTAPP_CORE_ROOT . '/shortcodes/mapping/startapp_portfolio.php' );
	vc_lean_map( 'startapp_gallery', null, STARTAPP_CORE_ROOT . '/shortcodes/mapping/startapp_gallery.php' );
	vc_lean_map( 'startapp_classes', null, STARTAPP_CORE_ROOT . '/shortcodes/mapping/startapp_classes.php' );

	if ( class_exists( 'WooCommerce' ) ) {
		vc_lean_map( 'startapp_product', null, STARTAPP_CORE_ROOT . '/shortcodes/mapping/startapp_product.php' );
		vc_lean_map( 'startapp_products', null, STARTAPP_CORE_ROOT . '/shortcodes/mapping/startapp_products.php' );
	}

}

add_action( 'vc_mapper_init_after', 'startapp_vc_mapping' );

/**
 * Remove unsupported shortcodes
 */
function startapp_vc_remove_unsupported_shortcodes() {
	/**
	 * This filter allows to enable the unsupported shortcodes
	 *
	 * @example
	 * return array_diff($shortcodes, [a, b, c]);
	 *
	 * Where [a, b, c] is a list of shortcodes
	 * which you want to enable.
	 *
	 * @param array $shortcodes Shortcodes list
	 */
	$shortcodes = apply_filters( 'startapp_vc_unsupported_shortcodes', array(
		'vc_section',
		'vc_toggle',
		'vc_text_separator',
		'vc_posts_slider',
		'vc_gallery',
		'vc_images_carousel',
		'vc_basic_grid',
		'vc_media_grid',
		'vc_gmaps',
		'vc_btn',
		'vc_button',
		'vc_button2',
		'vc_cta_button',
		'vc_cta_button2',
		'vc_masonry_grid',
		'vc_masonry_media_grid',
		'vc_message',
		'vc_cta',
		'vc_tabs',
		'vc_tour',
		'vc_accordion',
		'vc_tta_pageable',
		'vc_custom_heading',
		'vc_progress_bar',
		'vc_pie',
		'vc_round_chart',
		'vc_line_chart',
		'vc_flickr',
		'vc_separator',
		'product',
		'products',
		'recent_products',
		'featured_products',
		'sale_products',
		'best_selling_products',
		'top_rated_products',
		'product_category',
		'product_categories',
		'product_attribute',
	) );

	foreach ( $shortcodes as $shortcode ) {
		vc_remove_element( $shortcode );
	}
}

add_action( 'vc_after_init', 'startapp_vc_remove_unsupported_shortcodes' );

/**
 * Customize some Visual Composer default shortcodes
 *
 * @hooked vc_after_init 10
 */
function startapp_vc_customize_mapping() {

	/* Prepare frequently used variables */

	$animations = startapp_get_animations();

	$desc_class = __( 'Add extra classes, divided by whitespace, if you wish to style particular content element differently. We added set of predefined extra classes to use inside this field. You can see the complete list of classes in <a href="%s" target="_blank">Quick Help</a> page.', 'startapp' );
	$desc_class = wp_kses( $desc_class, array( 'a' => array( 'href' => true, 'target' => true ) ) );
	$desc_class = sprintf( $desc_class, get_admin_url( null, 'admin.php?page=startapp-help' ) );

	$desc_id = wp_kses(
		__( 'Make sure Section ID is unique and valid according to <a href="http://www.w3schools.com/tags/att_global_id.asp" target="_blank">w3c specification</a>. This ID can be used for anchor navigation.', 'startapp' ),
		array( 'a' => array( 'href' => true, 'target' => true ) )
	);

	$desc_icon_custom = __( 'Class should be written without leading dot. There are two options to add custom icons: <ol><li>You can link your custom icon CSS file in Theme Options > Advanced</li><li>You can manually enqueue custom icons CSS file in the Child Theme</li></ol>', 'startapp' );
	$desc_icon_custom = wp_kses( $desc_icon_custom, array( 'ol' => true, 'li' => true ) );

	/**
	 * Row | vc_row
	 */
	vc_map_update( 'vc_row', 'name', __( 'Section', 'startapp' ) );
	vc_map_update( 'vc_row', 'description', __( 'Group content elements inside section', 'startapp' ) );
	startapp_vc_replace_params( 'vc_row', array(
		array(
			'param_name'  => 'id',
			'type'        => 'el_id',
			'weight'      => 11,
			'heading'     => esc_html__( 'Section ID', 'startapp' ),
			'description' => $desc_id,
		),
		array(
			'param_name'  => 'offset_top',
			'type'        => 'textfield',
			'weight'      => 10,
			'heading'     => esc_html__( 'Offset Top', 'startapp' ),
			'description' => esc_html__( 'In case you use anchor navigation and link to this row id. You can control how far it will occur from the top of the page when scrolled to it. This field accepts any positive / negative integer number.', 'startapp' ),
			'value'       => 180,
		),
		array(
			'param_name'       => 'layout',
			'type'             => 'dropdown',
			'weight'           => 10,
			'heading'          => esc_html__( 'Content Layout', 'startapp' ),
			'description'      => esc_html__( 'Choose the layout type. Note: Equal Height version use flexbox model and works only in modern browsers.', 'startapp' ),
			'edit_field_class' => 'vc_col-sm-6',
			'value'            => array(
				esc_html__( 'Boxed', 'startapp' )                   => 'boxed',
				esc_html__( 'Full Width', 'startapp' )              => 'full',
				esc_html__( 'Boxed Equal Height', 'startapp' )      => 'boxed-equal',
				esc_html__( 'Full Width Equal Height', 'startapp' ) => 'full-equal',
			),
		),
		array(
			'param_name'       => 'is_no_gap',
			'type'             => 'checkbox',
			'weight'           => 10,
			'heading'          => esc_html__( 'Remove Gaps?', 'startapp' ),
			'description'      => esc_html__( 'If enabled there will be no side paddings inside columns.', 'startapp' ),
			'edit_field_class' => 'vc_col-sm-6',
			'value'            => array( esc_html__( 'Yes', 'startapp' ) => 'enable' ),
		),
		array(
			'param_name' => 'is_overlay',
			'type'       => 'dropdown',
			'weight'     => 10,
			'heading'    => esc_html__( 'Overlay', 'startapp' ),
			'group'      => esc_html__( 'Overlay', 'startapp' ),
			'value'      => array(
				esc_html__( 'Disable', 'startapp' ) => 'disable',
				esc_html__( 'Enable', 'startapp' )  => 'enable',
			),
		),
		array(
			'param_name'  => 'overlay_opacity',
			'type'        => 'textfield',
			'weight'      => 10,
			'group'       => esc_html__( 'Overlay', 'startapp' ),
			'heading'     => esc_html__( 'Opacity', 'startapp' ),
			'description' => esc_html__( 'Enter value from 0 to 100%. Where 0 is fully transparent', 'startapp' ),
			'dependency'  => array( 'element' => 'is_overlay', 'value' => 'enable' ),
			'value'       => 65,
		),
		array(
			'param_name' => 'overlay_color',
			'type'       => 'colorpicker',
			'weight'     => 10,
			'group'      => esc_html__( 'Overlay', 'startapp' ),
			'heading'    => esc_html__( 'Color', 'startapp' ),
			'dependency' => array( 'element' => 'is_overlay', 'value' => 'enable' ),
			'value'      => '#000000',
		),
		array(
			'param_name' => 'is_parallax',
			'type'       => 'dropdown',
			'weight'     => 10,
			'heading'    => esc_html__( 'Parallax', 'startapp' ),
			'group'      => esc_html__( 'Parallax', 'startapp' ),
			'value'      => array(
				esc_html__( 'Disable', 'startapp' ) => 'disable',
				esc_html__( 'Enable', 'startapp' )  => 'enable',
			),
		),
		array(
			'param_name'  => 'parallax_bg',
			'type'        => 'attach_image',
			'weight'      => 10,
			'heading'     => esc_html__( 'Background', 'startapp' ),
			'description' => esc_html__( 'Choose Background Image for parallax here. Please do not use Design options Background Image control in order for parallax to work properly. Also this is a fallback if you use the Video Background.', 'startapp' ),
			'group'       => esc_html__( 'Parallax', 'startapp' ),
			'dependency'  => array( 'element' => 'is_parallax', 'value' => 'enable' ),
		),
		array(
			'param_name'  => 'parallax_video',
			'type'        => 'textfield',
			'weight'      => 10,
			'heading'     => esc_html__( 'Video Background', 'startapp' ),
			'description' => esc_html__( 'You can provide a link to YouTube or Vimeo to play video on background.', 'startapp' ),
			'group'       => esc_html__( 'Parallax', 'startapp' ),
			'dependency'  => array( 'element' => 'is_parallax', 'value' => 'enable' ),
		),
		array(
			'param_name'  => 'parallax_type',
			'type'        => 'dropdown',
			'weight'      => 10,
			'heading'     => esc_html__( 'Type', 'startapp' ),
			'description' => esc_html__( 'Choose the Type of the parallax effect applied to the Background.', 'startapp' ),
			'group'       => esc_html__( 'Parallax', 'startapp' ),
			'dependency'  => array( 'element' => 'is_parallax', 'value' => 'enable' ),
			'std'         => 'scroll',
			'value'       => array(
				esc_html__( 'Scroll', 'startapp' )           => 'scroll',
				esc_html__( 'Scale', 'startapp' )            => 'scale',
				esc_html__( 'Opacity', 'startapp' )          => 'opacity',
				esc_html__( 'Scroll + Opacity', 'startapp' ) => 'scroll-opacity',
				esc_html__( 'Scale + Opacity', 'startapp' )  => 'scale-opacity',
			),
		),
		array(
			'param_name'  => 'parallax_speed',
			'type'        => 'textfield',
			'weight'      => 10,
			'heading'     => esc_html__( 'Speed', 'startapp' ),
			'description' => esc_html__( 'Parallax effect speed. Provide numbers from -1.0 to 1.0', 'startapp' ),
			'group'       => esc_html__( 'Parallax', 'startapp' ),
			'dependency'  => array( 'element' => 'is_parallax', 'value' => 'enable' ),
			'value'       => '0.4',
		),
		array(
			'param_name'  => 'animation',
			'type'        => 'dropdown',
			'heading'     => esc_html__( 'Animation', 'startapp' ),
			'description' => esc_html__( 'Select type of animation for element to be animated when it "enters" the browsers viewport (Note: works only in modern browsers).', 'startapp' ),
			'weight'      => - 1,
			'value'       => $animations,
		),
		array(
			'param_name'  => 'class',
			'type'        => 'textfield',
			'weight'      => - 2,
			'heading'     => esc_html__( 'Extra Class', 'startapp' ),
			'description' => $desc_class,
		),
		array(
			'param_name' => 'css',
			'type'       => 'css_editor',
			'weight'     => 10,
			'heading'    => esc_html__( 'CSS', 'startapp' ),
			'group'      => esc_html__( 'Design Options', 'startapp' ),
		),
	) );

	/**
	 * Inner Row | vc_row_inner
	 */
	vc_map_update( 'vc_row_inner', 'name', __( 'Inner Section', 'startapp' ) );
	startapp_vc_replace_params( 'vc_row_inner', array(
		array(
			'param_name'  => 'id',
			'type'        => 'el_id',
			'weight'      => 10,
			'heading'     => esc_html__( 'Row ID', 'startapp' ),
			'description' => $desc_id,
		),
		array(
			'param_name'  => 'animation',
			'type'        => 'dropdown',
			'heading'     => esc_html__( 'Animation', 'startapp' ),
			'description' => esc_html__( 'Select type of animation for element to be animated when it "enters" the browsers viewport (Note: works only in modern browsers).', 'startapp' ),
			'weight'      => - 1,
			'value'       => $animations,
		),
		array(
			'param_name'  => 'class',
			'type'        => 'textfield',
			'weight'      => - 2,
			'heading'     => esc_html__( 'Extra class name', 'startapp' ),
			'description' => $desc_class,
		),
		array(
			'param_name' => 'css',
			'type'       => 'css_editor',
			'group'      => esc_html__( 'Design Options', 'startapp' ),
			'weight'     => 10,
			'heading'    => esc_html__( 'CSS', 'startapp' ),
		),
	) );

	/**
	 * Column | vc_column
	 */
	startapp_vc_replace_params( 'vc_column', array(
		array(
			'param_name'  => 'animation',
			'type'        => 'dropdown',
			'heading'     => esc_html__( 'Animation', 'startapp' ),
			'description' => esc_html__( 'Select type of animation for element to be animated when it "enters" the browsers viewport (Note: works only in modern browsers).', 'startapp' ),
			'weight'      => 10,
			'value'       => $animations,
		),
		array(
			'param_name'  => 'class',
			'type'        => 'textfield',
			'weight'      => 10,
			'heading'     => esc_html__( 'Extra Class', 'startapp' ),
			'description' => $desc_class,
		),
		array(
			'param_name' => 'css',
			'type'       => 'css_editor',
			'weight'     => 10,
			'heading'    => esc_html__( 'CSS', 'startapp' ),
			'group'      => esc_html__( 'Design Options', 'startapp' ),
		),
		array(
			'param_name'  => 'width',
			'type'        => 'dropdown',
			'weight'      => 10,
			'heading'     => esc_html__( 'Width', 'startapp' ),
			'description' => esc_html__( 'Select column width.', 'startapp' ),
			'group'       => esc_html__( 'Responsive Options', 'startapp' ),
			'std'         => '1/1',
			'value'       => array(
				esc_html__( '1 column - 1/12', 'startapp' )    => '1/12',
				esc_html__( '2 columns - 1/6', 'startapp' )    => '1/6',
				esc_html__( '3 columns - 1/4', 'startapp' )    => '1/4',
				esc_html__( '4 columns - 1/3', 'startapp' )    => '1/3',
				esc_html__( '5 columns - 5/12', 'startapp' )   => '5/12',
				esc_html__( '6 columns - 1/2', 'startapp' )    => '1/2',
				esc_html__( '7 columns - 7/12', 'startapp' )   => '7/12',
				esc_html__( '8 columns - 2/3', 'startapp' )    => '2/3',
				esc_html__( '9 columns - 3/4', 'startapp' )    => '3/4',
				esc_html__( '10 columns - 5/6', 'startapp' )   => '5/6',
				esc_html__( '11 columns - 11/12', 'startapp' ) => '11/12',
				esc_html__( '12 columns - 1/1', 'startapp' )   => '1/1',
			),
		),
		array(
			'param_name'  => 'offset',
			'type'        => 'column_offset',
			'weight'      => 10,
			'heading'     => esc_html__( 'Responsiveness', 'startapp' ),
			'description' => esc_html__( 'Adjust column for different screen sizes. Control width, offset and visibility settings.', 'startapp' ),
			'group'       => esc_html__( 'Responsive Options', 'startapp' ),
		),
	) );

	/**
	 * Inner Column | vc_column_inner
	 */
	startapp_vc_replace_params( 'vc_column_inner', array(
		array(
			'param_name'  => 'animation',
			'type'        => 'dropdown',
			'heading'     => esc_html__( 'Animation', 'startapp' ),
			'description' => esc_html__( 'Select type of animation for element to be animated when it "enters" the browsers viewport (Note: works only in modern browsers).', 'startapp' ),
			'weight'      => 10,
			'value'       => $animations,
		),
		array(
			'param_name'  => 'class',
			'type'        => 'textfield',
			'weight'      => 10,
			'heading'     => esc_html__( 'Extra Class', 'startapp' ),
			'description' => $desc_class,
		),
		array(
			'param_name' => 'css',
			'type'       => 'css_editor',
			'weight'     => 10,
			'heading'    => esc_html__( 'CSS', 'startapp' ),
			'group'      => esc_html__( 'Design Options', 'startapp' ),
		),
		array(
			'param_name'  => 'width',
			'type'        => 'dropdown',
			'weight'      => 10,
			'heading'     => esc_html__( 'Width', 'startapp' ),
			'description' => esc_html__( 'Select column width.', 'startapp' ),
			'group'       => esc_html__( 'Responsive Options', 'startapp' ),
			'std'         => '1/1',
			'value'       => array(
				esc_html__( '1 column - 1/12', 'startapp' )    => '1/12',
				esc_html__( '2 columns - 1/6', 'startapp' )    => '1/6',
				esc_html__( '3 columns - 1/4', 'startapp' )    => '1/4',
				esc_html__( '4 columns - 1/3', 'startapp' )    => '1/3',
				esc_html__( '5 columns - 5/12', 'startapp' )   => '5/12',
				esc_html__( '6 columns - 1/2', 'startapp' )    => '1/2',
				esc_html__( '7 columns - 7/12', 'startapp' )   => '7/12',
				esc_html__( '8 columns - 2/3', 'startapp' )    => '2/3',
				esc_html__( '9 columns - 3/4', 'startapp' )    => '3/4',
				esc_html__( '10 columns - 5/6', 'startapp' )   => '5/6',
				esc_html__( '11 columns - 11/12', 'startapp' ) => '11/12',
				esc_html__( '12 columns - 1/1', 'startapp' )   => '1/1',
			),
		),
		array(
			'param_name'  => 'offset',
			'type'        => 'column_offset',
			'weight'      => 10,
			'heading'     => esc_html__( 'Responsiveness', 'startapp' ),
			'description' => esc_html__( 'Adjust column for different screen sizes. Control width, offset and visibility settings.', 'startapp' ),
			'group'       => esc_html__( 'Responsive Options', 'startapp' ),
		),
	) );

	/**
	 * Text Block | vc_column_text
	 */
	startapp_vc_replace_params( 'vc_column_text', array(
		array(
			'param_name' => 'content',
			'type'       => 'textarea_html',
			'weight'     => 10,
			'holder'     => 'div',
			'value'      => wp_kses( __( '<p>I am text block. Click edit button to change this text. Lorem ipsum dolor sit amet, consectetur adipiscing elit. Ut elit tellus, luctus nec ullamcorper mattis, pulvinar dapibus leo.</p>', 'startapp' ), array(
				'p' => array(),
			) ),
		),
		array(
			'param_name'  => 'animation',
			'type'        => 'dropdown',
			'heading'     => esc_html__( 'Animation', 'startapp' ),
			'description' => esc_html__( 'Select type of animation for element to be animated when it "enters" the browsers viewport (Note: works only in modern browsers).', 'startapp' ),
			'weight'      => 10,
			'value'       => $animations,
		),
		array(
			'param_name'  => 'class',
			'type'        => 'textfield',
			'weight'      => 10,
			'heading'     => esc_html__( 'Extra Class', 'startapp' ),
			'description' => $desc_class,
		),
		array(
			'param_name' => 'css',
			'type'       => 'css_editor',
			'weight'     => 10,
			'heading'    => esc_html__( 'CSS', 'startapp' ),
			'group'      => esc_html__( 'Design Options', 'startapp' ),
		),
	) );

	/**
	 * Accordion | vc_tta_accordion
	 */
	startapp_vc_replace_params( 'vc_tta_accordion', array(
		array(
			'param_name'  => 'active_section',
			'type'        => 'textfield',
			'weight'      => 10,
			'heading'     => esc_html__( 'Active section', 'startapp' ),
			'description' => esc_html__( 'Enter active section number. To have all sections closed on initial load enter 0 or non-existing number.', 'startapp' ),
			'value'       => 1,
		),
		array(
			'param_name' => 'skin',
			'type'       => 'dropdown',
			'weight'     => 10,
			'heading'    => esc_html__( 'Skin', 'startapp' ),
			'std'        => 'dark',
			'value'      => array(
				esc_html__( 'Dark', 'startapp' )  => 'dark',
				esc_html__( 'Light', 'startapp' ) => 'light',
			),
		),
		array(
			'param_name'  => 'animation',
			'type'        => 'dropdown',
			'heading'     => esc_html__( 'Animation', 'startapp' ),
			'description' => esc_html__( 'Select type of animation for element to be animated when it "enters" the browsers viewport (Note: works only in modern browsers).', 'startapp' ),
			'weight'      => - 1,
			'value'       => $animations,
		),
		array(
			'param_name'  => 'class',
			'type'        => 'textfield',
			'weight'      => - 2,
			'heading'     => esc_html__( 'Extra class name', 'startapp' ),
			'description' => $desc_class,
		),
		array(
			'param_name' => 'css',
			'type'       => 'css_editor',
			'group'      => esc_html__( 'Design Options', 'startapp' ),
			'weight'     => 10,
			'heading'    => esc_html__( 'CSS', 'startapp' ),
		),
	) );

	/**
	 * Tabs | vc_tta_tabs
	 */
	startapp_vc_replace_params( 'vc_tta_tabs', array(
		array(
			'param_name'  => 'alignment',
			'type'        => 'dropdown',
			'weight'      => 10,
			'heading'     => esc_html__( 'Alignment', 'startapp' ),
			'description' => esc_html__( 'Select tabs alignment', 'startapp' ),
			'std'         => 'left',
			'value'       => array(
				esc_html__( 'Left', 'startapp' )   => 'left',
				esc_html__( 'Center', 'startapp' ) => 'center',
				esc_html__( 'Right', 'startapp' )  => 'right',
			),
		),
		array(
			'param_name' => 'skin',
			'type'       => 'dropdown',
			'weight'     => 10,
			'heading'    => esc_html__( 'Skin', 'startapp' ),
			'std'        => 'dark',
			'value'      => array(
				esc_html__( 'Dark', 'startapp' )  => 'dark',
				esc_html__( 'Light', 'startapp' ) => 'light',
			),
		),
		array(
			'param_name'  => 'animation',
			'type'        => 'dropdown',
			'heading'     => esc_html__( 'Animation', 'startapp' ),
			'description' => esc_html__( 'Select type of animation for element to be animated when it "enters" the browsers viewport (Note: works only in modern browsers).', 'startapp' ),
			'weight'      => - 1,
			'value'       => $animations,
		),
		array(
			'param_name'  => 'class',
			'type'        => 'textfield',
			'weight'      => - 2,
			'heading'     => esc_html__( 'Extra class name', 'startapp' ),
			'description' => $desc_class,
		),
		array(
			'param_name' => 'css',
			'type'       => 'css_editor',
			'group'      => esc_html__( 'Design Options', 'startapp' ),
			'weight'     => 10,
			'heading'    => esc_html__( 'CSS', 'startapp' ),
		),
	) );

	/**
	 * Tour | vc_tta_tour
	 */
	startapp_vc_replace_params( 'vc_tta_tour', array(
		array(
			'param_name'  => 'alignment',
			'type'        => 'dropdown',
			'weight'      => 10,
			'heading'     => esc_html__( 'Alignment', 'startapp' ),
			'description' => esc_html__( 'Select tabs alignment', 'startapp' ),
			'value'       => array(
				esc_html__( 'Left', 'startapp' )   => 'left',
				esc_html__( 'Center', 'startapp' ) => 'center',
				esc_html__( 'Right', 'startapp' )  => 'right',
			),
		),
		array(
			'param_name'  => 'position',
			'type'        => 'dropdown',
			'weight'      => 10,
			'heading'     => esc_html__( 'Position', 'startapp' ),
			'description' => esc_html__( 'Select tabs section position', 'startapp' ),
			'value'       => array(
				esc_html__( 'Left', 'startapp' )  => 'left',
				esc_html__( 'Right', 'startapp' ) => 'right',
			),
		),
		array(
			'param_name' => 'skin',
			'type'       => 'dropdown',
			'weight'     => 10,
			'heading'    => esc_html__( 'Skin', 'startapp' ),
			'value'      => array(
				esc_html__( 'Dark', 'startapp' )  => 'dark',
				esc_html__( 'Light', 'startapp' ) => 'light',
			),
		),
		array(
			'param_name'  => 'animation',
			'type'        => 'dropdown',
			'heading'     => esc_html__( 'Animation', 'startapp' ),
			'description' => esc_html__( 'Select type of animation for element to be animated when it "enters" the browsers viewport (Note: works only in modern browsers).', 'startapp' ),
			'weight'      => - 1,
			'value'       => $animations,
		),
		array(
			'param_name'  => 'class',
			'type'        => 'textfield',
			'weight'      => - 2,
			'heading'     => esc_html__( 'Extra class name', 'startapp' ),
			'description' => $desc_class,
		),
		array(
			'param_name' => 'css',
			'type'       => 'css_editor',
			'group'      => esc_html__( 'Design Options', 'startapp' ),
			'weight'     => 10,
			'heading'    => esc_html__( 'CSS', 'startapp' ),
		),
	) );

	/**
	 * Section | vc_tta_section
	 */
	startapp_vc_replace_params( 'vc_tta_section', array(
		array(
			'param_name'  => 'title',
			'type'        => 'textfield',
			'weight'      => 10,
			'heading'     => esc_html__( 'Title', 'startapp' ),
			'description' => esc_html__( 'Enter section title', 'startapp' ),
		),
		array(
			'param_name'  => 'tab_id',
			'type'        => 'el_id',
			'weight'      => 10,
			'settings'    => array( 'auto_generate' => true ),
			'heading'     => esc_html__( 'Section ID', 'startapp' ),
			'description' => $desc_id,
		),
		array(
			'param_name'  => 'animation',
			'type'        => 'dropdown',
			'weight'      => 10,
			'heading'     => esc_html__( 'Animation', 'startapp' ),
			'description' => esc_html__( 'Applicable only for tabs', 'startapp' ),
			'std'         => 'fade',
			'value'       => array(
				esc_html__( 'Fade', 'startapp' )       => 'fade',
				esc_html__( 'Scale', 'startapp' )      => 'scale',
				esc_html__( 'Scale Down', 'startapp' ) => 'scaledown',
				esc_html__( 'Left', 'startapp' )       => 'left',
				esc_html__( 'Right', 'startapp' )      => 'right',
				esc_html__( 'Top', 'startapp' )        => 'top',
				esc_html__( 'Bottom', 'startapp' )     => 'bottom',
				esc_html__( 'Flip', 'startapp' )       => 'flip',
			),
		),
		array(
			'param_name' => 'is_icon',
			'type'       => 'dropdown',
			'weight'     => 10,
			'heading'    => esc_html__( 'Add icon?', 'startapp' ),
			'std'        => 'disable',
			'value'      => array(
				esc_html__( 'No', 'startapp' )  => 'disable',
				esc_html__( 'Yes', 'startapp' ) => 'enable',
			),
		),
		array(
			'param_name'       => 'icon_library',
			'type'             => 'dropdown',
			'weight'           => 10,
			'heading'          => esc_html__( 'Icon Library', 'startapp' ),
			'dependency'       => array( 'element' => 'is_icon', 'value' => 'enable' ),
			'edit_field_class' => 'vc_col-sm-6',
			'std'              => 'none',
			'value'            => array(
				esc_html__( 'Font Awesome', 'startapp' ) => 'fontawesome',
				esc_html__( 'Material', 'startapp' )     => 'material',
				esc_html__( 'Custom', 'startapp' )       => 'custom',
			),
		),
		array(
			'param_name'       => 'icon_position',
			'type'             => 'dropdown',
			'weight'           => 10,
			'heading'          => esc_html__( 'Icon Position', 'startapp' ),
			'dependency'       => array( 'element' => 'is_icon', 'value' => 'enable' ),
			'edit_field_class' => 'vc_col-sm-6',
			'std'              => 'right',
			'value'            => array(
				esc_html__( 'Left', 'startapp' )  => 'left',
				esc_html__( 'Right', 'startapp' ) => 'right',
			),
		),
		array(
			'param_name' => 'icon_fontawesome',
			'type'       => 'iconpicker',
			'weight'     => 10,
			'heading'    => esc_html__( 'Icon', 'startapp' ),
			'settings'   => array( 'type' => 'fontawesome', 'iconsPerPage' => 500 ),
			'dependency' => array( 'element' => 'icon_library', 'value' => 'fontawesome' ),
		),
		array(
			'param_name' => 'icon_material',
			'type'       => 'iconpicker',
			'weight'     => 10,
			'heading'    => esc_html__( 'Icon', 'startapp' ),
			'settings'   => array( 'type' => 'material', 'iconsPerPage' => 500 ),
			'dependency' => array( 'element' => 'icon_library', 'value' => 'material' ),
		),
		array(
			'param_name'  => 'icon_custom',
			'type'        => 'textfield',
			'weight'      => 10,
			'heading'     => esc_html__( 'Icon', 'startapp' ),
			'description' => $desc_icon_custom,
			'dependency'  => array( 'element' => 'icon_library', 'value' => 'custom' ),
		),
		array(
			'param_name'  => 'class',
			'type'        => 'textfield',
			'weight'      => - 2,
			'heading'     => esc_html__( 'Extra class name', 'startapp' ),
			'description' => $desc_class,
		),
	) );

	/**
	 * Icon | vc_icon
	 */
	startapp_vc_replace_params( 'vc_icon', array(
		array(
			'param_name' => 'type',
			'type'       => 'dropdown',
			'weight'     => 10,
			'heading'    => esc_html__( 'Media Type', 'startapp' ),
			'std'        => 'icon',
			'value'      => array(
				esc_html__( 'Icon', 'startapp' )  => 'icon',
				esc_html__( 'Image', 'startapp' ) => 'image',
			)
		),
		array(
			'param_name' => 'icon_library',
			'type'       => 'dropdown',
			'weight'     => 10,
			'heading'    => esc_html__( 'Icon library', 'startapp' ),
			'dependency' => array( 'element' => 'type', 'value' => 'icon' ),
			'std'        => 'none',
			'value'      => array(
				esc_html__( 'Font Awesome', 'startapp' ) => 'fontawesome',
				esc_html__( 'Material', 'startapp' )     => 'material',
				esc_html__( 'Custom', 'startapp' )       => 'custom',
			),
		),
		array(
			'param_name' => 'icon_fontawesome',
			'type'       => 'iconpicker',
			'weight'     => 10,
			'heading'    => esc_html__( 'Icon', 'startapp' ),
			'settings'   => array( 'type' => 'fontawesome', 'iconsPerPage' => 500 ),
			'dependency' => array( 'element' => 'icon_library', 'value' => 'fontawesome' ),
		),
		array(
			'param_name' => 'icon_material',
			'type'       => 'iconpicker',
			'weight'     => 10,
			'heading'    => esc_html__( 'Icon', 'startapp' ),
			'settings'   => array( 'type' => 'material', 'iconsPerPage' => 500 ),
			'dependency' => array( 'element' => 'icon_library', 'value' => 'material' ),
		),
		array(
			'param_name'  => 'icon_custom',
			'type'        => 'textfield',
			'weight'      => 10,
			'heading'     => esc_html__( 'Icon', 'startapp' ),
			'description' => $desc_icon_custom,
			'dependency'  => array( 'element' => 'icon_library', 'value' => 'custom' ),
		),
		array(
			'param_name' => 'icon_color',
			'type'       => 'dropdown',
			'weight'     => 10,
			'heading'    => esc_html__( 'Icon Color', 'startapp' ),
			'dependency' => array( 'element' => 'type', 'value' => 'icon' ),
			'std'        => 'default',
			'value'      => array(
				esc_html__( 'Default', 'startapp' ) => 'default',
				esc_html__( 'Primary', 'startapp' ) => 'primary',
				esc_html__( 'Success', 'startapp' ) => 'success',
				esc_html__( 'Info', 'startapp' )    => 'info',
				esc_html__( 'Warning', 'startapp' ) => 'warning',
				esc_html__( 'Danger', 'startapp' )  => 'danger',
				esc_html__( 'Muted', 'startapp' )   => 'muted',
				esc_html__( 'Gray', 'startapp' )    => 'gray',
				esc_html__( 'Light', 'startapp' )   => 'light',
				esc_html__( 'Custom', 'startapp' )  => 'custom',
			),
		),
		array(
			'param_name' => 'icon_color_custom',
			'type'       => 'colorpicker',
			'weight'     => 10,
			'heading'    => esc_html__( 'Custom Icon Color', 'startapp' ),
			'dependency' => array( 'element' => 'icon_color', 'value' => 'custom' ),
		),
		array(
			'param_name' => 'image',
			'type'       => 'attach_image',
			'weight'     => 10,
			'heading'    => esc_html__( 'Icon', 'startapp' ),
			'dependency' => array( 'element' => 'type', 'value' => 'image' ),
		),
		array(
			'param_name'  => 'size',
			'type'        => 'textfield',
			'weight'      => 10,
			'heading'     => esc_html__( 'Icon Size', 'startapp' ),
			'description' => esc_html__( 'Accepts only positive integer number, px.', 'startapp' ),
			'value'       => 24
		),
		array(
			'param_name' => 'alignment',
			'type'       => 'dropdown',
			'weight'     => 10,
			'heading'    => esc_html__( 'Alignment', 'startapp' ),
			'std'        => 'left',
			'value'      => array(
				esc_html__( 'Left', 'startapp' )   => 'left',
				esc_html__( 'Center', 'startapp' ) => 'center',
				esc_html__( 'Right', 'startapp' )  => 'right',
			),
		),
		array(
			'param_name'  => 'animation',
			'type'        => 'dropdown',
			'heading'     => esc_html__( 'Animation', 'startapp' ),
			'description' => esc_html__( 'Select type of animation for element to be animated when it "enters" the browsers viewport. Note: works only in modern browsers.', 'startapp' ),
			'weight'      => - 1,
			'value'       => $animations,
		),
		array(
			'param_name'  => 'class',
			'type'        => 'textfield',
			'weight'      => - 2,
			'heading'     => esc_html__( 'Extra Class', 'startapp' ),
			'description' => $desc_class,
		),
	) );

	/**
	 * Single Image | vc_single_image
	 */
	startapp_vc_replace_params( 'vc_single_image', array(
		array(
			'param_name' => 'source',
			'type'       => 'dropdown',
			'weight'     => 10,
			'heading'    => esc_html__( 'Image Source', 'startapp' ),
			'value'      => array(
				esc_html__( 'Media Library', 'startapp' ) => 'media',
				esc_html__( 'External Link', 'startapp' ) => 'external',
			),
		),
		array(
			'param_name'  => 'image',
			'type'        => 'attach_image',
			'weight'      => 10,
			'heading'     => esc_html__( 'Image', 'startapp' ),
			'dependency'  => array( 'element' => 'source', 'value' => 'media' ),
			'admin_label' => true,
		),
		array(
			'param_name'  => 'size_media',
			'type'        => 'textfield',
			'weight'      => 10,
			'heading'     => esc_html__( 'Size', 'startapp' ),
			'description' => esc_html__( 'Enter image size "thumbnail", "medium", "large", "full" or other sizes defined by theme. Alternatively enter size in pixels. For example: 200x100 (Width x Height).', 'startapp' ),
			'value'       => 'full',
			'dependency'  => array( 'element' => 'source', 'value' => 'media' ),
		),
		array(
			'param_name'  => 'is_caption',
			'type'        => 'checkbox',
			'weight'      => 10,
			'heading'     => esc_html__( 'Add caption?', 'startapp' ),
			'description' => esc_html__( 'Caption from the Media Library will be used.', 'startapp' ),
			'value'       => array( esc_html__( 'Yes', 'startapp' ) => 'enable' ),
			'dependency'  => array( 'element' => 'source', 'value' => 'media' ),
		),
		array(
			'param_name'  => 'external_src',
			'type'        => 'textfield',
			'weight'      => 10,
			'heading'     => esc_html__( 'External link', 'startapp' ),
			'description' => esc_html__( 'Enter the external link.', 'startapp' ),
			'dependency'  => array( 'element' => 'source', 'value' => 'external' ),
		),
		array(
			'param_name'  => 'size_external',
			'type'        => 'textfield',
			'weight'      => 10,
			'heading'     => esc_html__( 'Image size', 'startapp' ),
			'description' => esc_html__( 'Enter image size in pixels. Example: 200x100 (Width x Height).', 'startapp' ),
			'dependency'  => array( 'element' => 'source', 'value' => 'external' ),
		),
		array(
			'param_name'  => 'caption',
			'type'        => 'textfield',
			'weight'      => 10,
			'heading'     => esc_html__( 'Caption', 'startapp' ),
			'description' => esc_html__( 'Enter text for image caption.', 'startapp' ),
			'dependency'  => array( 'element' => 'source', 'value' => 'external' ),
		),
		array(
			'param_name'  => 'link',
			'type'        => 'href',
			'weight'      => 10,
			'heading'     => esc_html__( 'Link', 'startapp' ),
			'description' => esc_html__( 'Enter URL if you want this image to have a link. Note: parameters like "mailto:" are also accepted.', 'startapp' ),
		),
		array(
			'param_name'  => 'motion',
			'type'        => 'dropdown',
			'weight'      => 10,
			'heading'     => esc_html__( 'Motion', 'startapp' ),
			'description' => esc_html__( 'Add fancy loop animation to the image to make it stand out.', 'startapp' ),
			'value'       => array(
				esc_html__( 'None', 'startapp' )              => '',
				esc_html__( 'Pulse', 'startapp' )             => 'pulse',
				esc_html__( 'Zoom In Out', 'startapp' )       => 'zoomInOut',
				esc_html__( 'Horizontal Motion', 'startapp' ) => 'hMotion',
				esc_html__( 'Vertical Motion', 'startapp' )   => 'vMotion',
			),
		),
		array(
			'param_name'  => 'animation',
			'type'        => 'dropdown',
			'heading'     => esc_html__( 'Animation', 'startapp' ),
			'description' => esc_html__( 'Select type of animation for element to be animated when it "enters" the browsers viewport. Note: works only in modern browsers.', 'startapp' ),
			'weight'      => - 1,
			'value'       => $animations,
		),
		array(
			'param_name'  => 'class',
			'type'        => 'textfield',
			'weight'      => - 2,
			'heading'     => esc_html__( 'Extra Class', 'startapp' ),
			'description' => $desc_class,
		),
	) );
}

add_action( 'vc_after_init', 'startapp_vc_customize_mapping' );

/**
 * Remove the built-in VC Default Templates
 *
 * @link   https://wpbakery.atlassian.net/wiki/pages/viewpage.action?pageId=524300
 * @hooked vc_load_default_templates 11
 *
 * @param array $templates Default templates
 *
 * @return array
 */
function startapp_vc_remove_templates( $templates ) {
	return array();
}

add_filter( 'vc_load_default_templates', 'startapp_vc_remove_templates', 11 );

/**
 * Unregister the VC's "not_responsive_css" option.
 * This option is not supported by our theme.
 *
 * @hooked admin_init 10
 */
function startapp_vc_unregister_settings() {
	unregister_setting( 'wpb_js_composer_settings_general', 'wpb_js_not_responsive_css' );
}

add_action( 'admin_init', 'startapp_vc_unregister_settings' );

/**
 * Remove the "not_responsive_css" options from VC Settings
 *
 * Use carefully, because I modify the global variable directly
 *
 * @hooked vc_page_settings_render-vc-general 10
 */
function startapp_vc_remove_settings() {
	global $wp_settings_fields;

	$page    = 'vc_settings_general';
	$section = 'wpb_js_composer_settings_general';
	$option  = 'wpb_js_not_responsive_css';

	if ( ! isset( $wp_settings_fields[ $page ][ $section ] ) ) {
		return;
	}

	if ( ! array_key_exists( $option, $wp_settings_fields[ $page ][ $section ] ) ) {
		return;
	}

	unset( $wp_settings_fields[ $page ][ $section ][ $option ] );

	return;
}

add_action( 'vc_page_settings_render-vc-general', 'startapp_vc_remove_settings' );
