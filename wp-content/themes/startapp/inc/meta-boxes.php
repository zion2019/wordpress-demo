<?php
/**
 * Theme Meta Boxes
 *
 * @author 8guild
 */

// Exit if accessed directly
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

if ( ! defined( 'EQUIP_VERSION' ) ) {
	return;
}

/**
 * Add meta box "Page Settings" for post
 *
 * @param WP_Screen $screen Current screen
 */
function startapp_add_post_settings_meta_box( $screen ) {
	$path = STARTAPP_TEMPLATE_URI . '/img/options/blog';

	$sl_options = array(
		'default' => array( 'src' => $path . '/default.png', 'label' => esc_html__( 'Default', 'startapp' ) ),
		'left'    => array( 'src' => $path . '/left.png', 'label' => esc_html__( 'Left Sidebar', 'startapp' ) ),
		'right'   => array( 'src' => $path . '/right.png', 'label' => esc_html__( 'Right Sidebar', 'startapp' ) ),
		'no'      => array( 'src' => $path . '/no.png', 'label' => esc_html__( 'No Sidebar', 'startapp' ) ),
	);

	try {
		$layout = equip_create_meta_box_layout();

		//<editor-fold desc="Layout Tab">
		$post_layout = $layout->add_tab( 'tab_post_layout', esc_html__( 'Post Layout', 'startapp' ) );
		$post_layout
			->add_field( 'single_layout', 'image_select', array(
				'label'   => esc_html__( 'Layout', 'startapp' ),
				'default' => 'default',
				'width'   => 250,
				'height'  => 200,
				'options' => $sl_options,
			) );
		//</editor-fold>

		//<editor-fold desc="Cover Image Tab">
		$cover_image = $layout->add_tab( 'tab_cover_image', esc_html__( 'Cover Image', 'startapp' ) );
		$cover_image
			->add_field( 'cover_sep_before', 'raw_text', array(
				'escape'  => 'trim',
				'default' => wp_kses(
					__( '<h3>Cover Image</h3><p>Post cover image that is displayed at the most top of the page. It occupies the 100% a page width so make sure you upload large enough image.</p><hr>', 'startapp' ),
					array(
						'h3' => true,
						'p'  => true,
						'hr' => true,
					)
				),
			) )
			->add_row()
			->add_column( 2 )
			->add_field( 'cover', 'media', array(
				'label' => esc_html__( 'Choose Images', 'startapp' ),
				'media' => array( 'title' => esc_html__( 'Choose the Cover Image', 'startapp' ) ),
			) )
			->add_column( 3 )
			->add_field( 'cover_height', 'text', array(
				'label'       => esc_html__( 'Cover Image Height', 'startapp' ),
				'description' => esc_html__( 'Any positive integer number', 'startapp' ),
				'default'     => 500,
				'sanitize'    => 'absint',
				'escape'      => 'absint',
			) )
			->add_column( 4 )
			->add_field( 'cover_parallax', 'switch', array(
				'label'       => esc_html__( 'Parallax', 'startapp' ),
				'description' => esc_html__( 'Please note if parallax enabled you have to upload large image at least 1920 x 1080 px in order for parallax work properly.', 'startapp' ),
				'default'     => true,
				'label_on'    => esc_html__( 'Enable', 'startapp' ),
				'label_off'   => esc_html__( 'Disable', 'startapp' ),
			) )
			->add_row()
			->add_column( 5 )
			->add_field( 'cover_parallax_type', 'select', array(
				'label'       => esc_html__( 'Parallax Type', 'startapp' ),
				'description' => esc_html__( 'Choose the Type of the parallax effect applied to the Background of Cover Image.', 'startapp' ),
				'default'     => 'scroll',
				'required'    => array( 'cover_parallax', '=', 1 ),
				'options'     => array(
					'scroll'         => esc_html__( 'Scroll', 'startapp' ),
					'scale'          => esc_html__( 'Scale', 'startapp' ),
					'opacity'        => esc_html__( 'Opacity', 'startapp' ),
					'scroll-opacity' => esc_html__( 'Scroll & Opacity', 'startapp' ),
					'scale-opacity'  => esc_html__( 'Scale & Opacity', 'startapp' ),
				),
			) )
			->add_column( 4 )
			->add_field( 'cover_parallax_speed', 'text', array(
				'label'       => esc_html__( 'Parallax Speed', 'startapp' ),
				'description' => esc_html__( 'Parallax effect speed. Provide numbers from -1.0 to 1.0', 'startapp' ),
				'default'     => 0.4,
				'required'    => array( 'cover_parallax', '=', 1 ),
				'sanitize'    => 'startapp_sanitize_float',
				'escape'      => 'startapp_sanitize_float',
			) )
			->reset();
		//</editor-fold>

		//<editor-fold desc="Other Tab">
		$other = $layout->add_tab( 'tab_other', esc_html__( 'Other', 'startapp' ) );
		$other
			->add_row()
			->add_column( 3 )
			->add_field( 'single_is_tile_author', 'select', array(
				'label'       => esc_html__( 'Author in Post Tile', 'startapp' ),
				'description' => esc_html__( 'This option allows you to disable the author in the post tile.', 'startapp' ),
				'default'     => 'default',
				'options'     => array(
					'default' => esc_html__( 'Default', 'startapp' ),
					1         => esc_html__( 'Enable', 'startapp' ),
					0         => esc_html__( 'Disable', 'startapp' ),
				),
			) )
			->add_column( 3 )
			->add_field( 'single_is_post_author', 'select', array(
				'label'       => esc_html__( 'Author in Single Post', 'startapp' ),
				'description' => esc_html__( 'This option allows you to disable the author widget in the single post.', 'startapp' ),
				'default'     => 'default',
				'options'     => array(
					'default' => esc_html__( 'Default', 'startapp' ),
					1         => esc_html__( 'Enable', 'startapp' ),
					0         => esc_html__( 'Disable', 'startapp' ),
				),
			) )
			->add_column( 3 )
			->add_field( 'single_is_shares', 'select', array(
				'label'       => esc_html__( 'Sharing Buttons', 'startapp' ),
				'description' => esc_html__( 'This option allows you to disable the sharing buttons in the single post.', 'startapp' ),
				'default'     => 'default',
				'options'     => array(
					'default' => esc_html__( 'Default', 'startapp' ),
					1         => esc_html__( 'Enable', 'startapp' ),
					0         => esc_html__( 'Disable', 'startapp' ),
				),
			) )
			->add_row()
			->add_column( 9 )
			->add_field( 'single_thumbnail_position', 'select', array(
				'label'       => esc_html__( 'Featured Image Position', 'startapp' ),
				'description' => esc_html__( 'This option allows you to control the position of the Featured Image in the post tile.', 'startapp' ),
				'default'     => 'default',
				'options'     => array(
					'default' => esc_html__( 'Default', 'startapp' ),
					'top'     => esc_html__( 'Top', 'startapp' ),
					'bottom'  => esc_html__( 'Bottom', 'startapp' ),
				),
			) );
		//</editor-fold>

		equip_add_meta_box( STARTAPP_PAGE_SETTINGS, $layout, array(
			'id'       => 'startapp-page-settings',
			'title'    => esc_html__( 'Page Settings', 'startapp' ),
			'screen'   => 'post',
			'context'  => 'normal',
			'priority' => 'high',
		) );
	} catch ( Exception $e ) {
		trigger_error( $e->getMessage() );
	}
}

add_action( 'current_screen', 'startapp_add_post_settings_meta_box' );

/**
 * Add meta box "Page Settings" for pages
 *
 * @param WP_Screen $screen Current screen
 */
function startapp_add_page_settings_meta_box( $screen ) {
	$path = STARTAPP_TEMPLATE_URI . '/img/options/blog';

	$sl_options = array(
		'default' => array( 'src' => $path . '/default.png', 'label' => esc_html__( 'Default', 'startapp' ) ),
		'left'    => array( 'src' => $path . '/left.png', 'label' => esc_html__( 'Left Sidebar', 'startapp' ) ),
		'right'   => array( 'src' => $path . '/right.png', 'label' => esc_html__( 'Right Sidebar', 'startapp' ) ),
		'no'      => array( 'src' => $path . '/no.png', 'label' => esc_html__( 'No Sidebar', 'startapp' ) ),
	);

	try {
		$layout = equip_create_meta_box_layout();

		//<editor-fold desc="Page Header Tab">
		$page_header = $layout->add_tab( 'tab_page_header', esc_html__( 'Header', 'startapp' ) );
		$page_header
			->add_field( 'header_sep_before', 'raw_text', array(
				'default' => wp_kses( __( '<h3>Header</h3><hr>', 'startapp' ), array(
					'h3' => true,
					'hr' => true,
				) ),
			) )
			->add_field( 'custom_logo', 'media', array(
				'label'       => esc_html__( 'Custom Logo', 'startapp' ),
				'helper'      => esc_html__( 'Here you can upload a custom logo.', 'startapp' ),
				'description' => esc_html__( 'This logo will be displayed only on a current page. May be useful if you have a custom Header Type.', 'startapp' ),
				'media'       => array( 'title' => esc_html__( 'Choose a Custom Logo', 'startapp' ) ),
			) )
			->add_field( 'header_layout', 'image_select', array(
				'label'   => esc_html__( 'Type', 'startapp' ),
				'default' => 'default',
				'width'   => 250,
				'height'  => 200,
				'options' => call_user_func( function () {
					// use the existing header variants
					$options = startapp_theme_options_header_variants();
					$default = array(
						'default' => array(
							'src'   => STARTAPP_TEMPLATE_URI . '/img/options/blog/default.png',
							'label' => esc_html__( 'Default', 'startapp' ),
						),
					);

					return array_merge( $default, $options );
				} ),
			) );
		//</editor-fold>

		//<editor-fold desc="Page Layout Tab">
		$page_for_posts = get_option( 'page_for_posts' );

		// current page is set in settings > reading > Posts page 
		if ( ! empty( $_GET['post'] ) && $_GET['post'] == $page_for_posts ) {
			$page_layout = $layout->add_tab( 'tab_page_layout', esc_html__( 'Page Layout', 'startapp' ) );
			$page_layout
				->add_field( 'page_layout', 'image_select', array(
					'label'       => esc_html__( 'Layout', 'startapp' ),
					'description' => wp_kses(
						sprintf(
							__(
								'You can set layout for a blog page in <a href="%s" target="_blank" rel="noopener noreferrer">Theme Options > Blog</a>
								 section.',
								'startapp'
							), get_admin_url( null, 'admin.php?page=startapp-options' )
						),
						array( 'br' => true, 'a' => array( 'href' => true, 'target' => true, 'rel' => true ) )
					)
				) );
		} else {
			$page_layout = $layout->add_tab( 'tab_page_layout', esc_html__( 'Page Layout', 'startapp' ) );
			$page_layout
				->add_field( 'page_layout', 'image_select', array(
					'label'   => esc_html__( 'Layout', 'startapp' ),
					'default' => 'default',
					'width'   => 250,
					'height'  => 200,
					'options' => $sl_options,
				) );
		}
		//</editor-fold>

		//<editor-fold desc="Page Title Tab">
		$page_title = $layout->add_tab( 'tab_page_title', esc_html__( 'Page Title', 'startapp' ) );
		$page_title
			->add_field( 'page_title_sep_before', 'raw_text', array(
				'default' => wp_kses( __( '<h3>Page Title</h3><hr>', 'startapp' ), array(
					'h3' => true,
					'hr' => true,
				) ),
			) )
			->add_row()
			->add_column( 3 )
			->add_field( 'header_is_page_title', 'select', array(
				'label'   => esc_html__( 'Page Title', 'startapp' ),
				'default' => 'default',
				'options' => array(
					'default' => esc_html__( 'Default', 'startapp' ),
					1         => esc_html__( 'Enable', 'startapp' ),
					0         => esc_html__( 'Disable', 'startapp' ),
				),
			) )
			->add_column( 3 )
			->add_field( 'page_title_skin', 'select', array(
				'label'    => esc_html__( 'Page Title Skin', 'startapp' ),
				'default'  => 'dark',
				'required' => array( 'header_is_page_title', 'in_array', array( 'default', 1 ) ),
				'options'  => array(
					'dark'  => esc_html__( 'Dark', 'startapp' ),
					'light' => esc_html__( 'Light', 'startapp' ),
				),
			) )
			->add_column( 3 )
			->add_field( 'header_page_title_size', 'select', array(
				'label'    => esc_html__( 'Page Title Size', 'startapp' ),
				'default'  => 'default',
				'required' => array( 'header_is_page_title', 'in_array', array( 'default', 1 ) ),
				'options'  => array(
					'default' => esc_html__( 'Default', 'startapp' ),
					'normal'  => esc_html__( 'Normal', 'startapp' ),
					'lg'      => esc_html__( 'Large', 'startapp' ),
					'xl'      => esc_html__( 'Extra Large', 'startapp' ),
				),
			) )
			->add_column( 3 )
			->add_field( 'page_title_overlap', 'switch', array(
				'label'       => esc_html__( 'Overlap Content', 'startapp' ),
				'description' => esc_html__( 'Imagine you have nice Full Width image background section at the top of the page and you want you Page Title on top of it. This option is exactly for such situations. Goes well along with Ghost Headers.', 'startapp' ),
				'default'     => false,
				'label_on'    => esc_html__( 'On', 'startapp' ),
				'label_off'   => esc_html__( 'Off', 'startapp' ),
				'required'    => array(
					array( 'header_is_page_title', 'in_array', array( 'default', 1 ) ),
					array( 'header_page_title_size', '=', 'xl' ),
				),
			) )
			->add_row()
			->add_column( 3 )
			->add_field( 'page_title_bg', 'media', array(
				'label'    => esc_html__( 'Page Title Background Image', 'startapp' ),
				'media'    => array( 'title' => esc_html__( 'Choose a background image', 'startapp' ) ),
				'required' => array( 'header_is_page_title', 'in_array', array( 'default', 1 ) ),
			) )
			->add_column( 3 )
			->add_field( 'page_title_bg_color', 'color', array(
				'label'    => esc_html__( 'Page Title Solid Background Color', 'startapp' ),
				'required' => array( 'header_is_page_title', 'in_array', array( 'default', 1 ) ),
			) )
			->add_column( 3 )
			->add_field( 'page_title_fullwidth', 'switch', array(
				'label'       => esc_html__( 'Make Page Title Full Width?', 'startapp' ),
				'description' => esc_html__( 'If enabled Page Title will occupy the 100% of the page width. Looks good with full width header.', 'startapp' ),
				'required'    => array( 'header_is_page_title', 'in_array', array( 'default', 1 ) ),
				'default'     => false,
				'label_on'    => esc_html__( 'On', 'startapp' ),
				'label_off'   => esc_html__( 'Off', 'startapp' ),
			) )
			->add_row()
			->add_column( 3 )
			->add_field( 'page_title_parallax', 'switch', array(
				'label'       => esc_html__( 'Parallax', 'startapp' ),
				'description' => esc_html__( 'Please note if parallax enabled you have to upload large image at least 1920 x 1080 px in order for parallax work properly.', 'startapp' ),
				'default'     => false,
				'label_on'    => esc_html__( 'On', 'startapp' ),
				'label_off'   => esc_html__( 'Off', 'startapp' ),
				'required'    => array(
					array( 'header_is_page_title', 'in_array', array( 'default', 1 ) ),
					array( 'page_title_bg', 'not_empty' ),
				),
			) )
			->add_column( 3 )
			->add_field( 'page_title_parallax_type', 'select', array(
				'label'       => esc_html__( 'Parallax Type', 'startapp' ),
				'description' => esc_html__( 'Choose the Type of the parallax effect applied to the Background of Cover Image.', 'startapp' ),
				'default'     => 'scroll',
				'required'    => array(
					array( 'header_is_page_title', 'in_array', array( 'default', 1 ) ),
					array( 'page_title_bg', 'not_empty' ),
					array( 'page_title_parallax', '=', 1 ),
				),
				'options'     => array(
					'scroll'         => esc_html__( 'Scroll', 'startapp' ),
					'scale'          => esc_html__( 'Scale', 'startapp' ),
					'opacity'        => esc_html__( 'Opacity', 'startapp' ),
					'scroll-opacity' => esc_html__( 'Scroll & Opacity', 'startapp' ),
					'scale-opacity'  => esc_html__( 'Scale & Opacity', 'startapp' ),
				),
			) )
			->add_column( 3 )
			->add_field( 'page_title_parallax_speed', 'text', array(
				'label'       => esc_html__( 'Parallax Speed', 'startapp' ),
				'description' => esc_html__( 'Parallax effect speed. Provide numbers from -1.0 to 1.0', 'startapp' ),
				'default'     => 0.4,
				'sanitize'    => 'startapp_sanitize_float',
				'escape'      => 'startapp_sanitize_float',
				'required'    => array(
					array( 'header_is_page_title', 'in_array', array( 'default', 1 ) ),
					array( 'page_title_bg', 'not_empty' ),
					array( 'page_title_parallax', '=', 1 ),
				),
			) )
			->add_row()
			->add_offset( 3 )
			->add_column( 6 )
			->add_field( 'page_title_parallax_video', 'text', array(
				'label'       => esc_html__( 'Video Background', 'startapp' ),
				'description' => esc_html__( 'You can provide a link to YouTube or Vimeo to play video on background.', 'startapp' ),
				'sanitize'    => 'esc_url_raw',
				'escape'      => 'esc_url',
				'required'    => array(
					array( 'header_is_page_title', 'in_array', array( 'default', 1 ) ),
					array( 'page_title_bg', 'not_empty' ),
					array( 'page_title_parallax', '=', 1 ),
				),
			) )
			->add_row()
			->add_column( 3 )
			->add_field( 'page_title_overlay', 'switch', array(
				'label'       => esc_html__( 'Overlay', 'startapp' ),
				'description' => esc_html__( 'Enable the Overlay on the Page Title.', 'startapp' ),
				'default'     => false,
				'label_on'    => esc_html__( 'On', 'startapp' ),
				'label_off'   => esc_html__( 'Off', 'startapp' ),
				'required'    => array(
					array( 'header_is_page_title', 'in_array', array( 'default', 1 ) ),
					array( 'page_title_bg', 'not_empty' ),
				),
			) )
			->add_column( 3 )
			->add_field( 'page_title_overlay_color', 'color', array(
				'label'    => esc_html__( 'Overlay Color', 'startapp' ),
				'default'  => '#000000',
				'required' => array(
					array( 'header_is_page_title', 'in_array', array( 'default', 1 ) ),
					array( 'page_title_bg', 'not_empty' ),
					array( 'page_title_overlay', '=', 1 ),
				),
			) )
			->add_column( 3 )
			->add_field( 'page_title_overlay_opacity', 'text', array(
				'label'       => esc_html__( 'Overlay Opacity', 'startapp' ),
				'description' => esc_html__( 'Overlay opacity. Provide positive integer numbers from 1 to 100%', 'startapp' ),
				'default'     => 35,
				'sanitize'    => 'absint',
				'escape'      => 'absint',
				'required'    => array(
					array( 'header_is_page_title', 'in_array', array( 'default', 1 ) ),
					array( 'page_title_bg', 'not_empty' ),
					array( 'page_title_overlay', '=', 1 ),
				),
			) )
			->reset();
		//</editor-fold>

		equip_add_meta_box( STARTAPP_PAGE_SETTINGS, $layout, array(
			'id'       => 'startapp-page-settings',
			'title'    => esc_html__( 'Page Settings', 'startapp' ),
			'screen'   => apply_filters( 'startapp_page_settings_screen', array( 'page' ) ),
			'context'  => 'normal',
			'priority' => 'default',
		) );
	} catch ( Exception $e ) {
		trigger_error( $e->getMessage() );
	}
}

add_action( 'current_screen', 'startapp_add_page_settings_meta_box' );

/**
 * Add meta box "Related Posts"
 *
 * @param WP_Screen $screen Current screen
 */
function startapp_add_related_posts_meta_box( $screen ) {
	try {
		$layout = equip_create_meta_box_layout();
		$layout
			->add_field( 'is_enabled', 'switch', array(
				'default'   => false,
				'label_on'  => esc_html__( 'Enable', 'startapp' ),
				'label_off' => esc_html__( 'Disable', 'startapp' ),
			) )
			->add_field( 'posts', 'combobox', array(
				'label'       => esc_html__( 'Related Posts', 'startapp' ),
				'description' => esc_html__( 'Choose one or more posts in field above', 'startapp' ),
				'attr'        => array( 'multiple' => true ),
				'sanitize'    => 'startapp_sanitize_related_posts',
				'escape'      => 'startapp_sanitize_related_posts',
				'required'    => array( 'is_enabled', '=', 1 ),
				'options'     => call_user_func( function () use ( $screen ) {
					// save a piece of resources, do not get posts if current screen
					// is not a single post
					if ( 'post' !== $screen->id ) {
						return array();
					}

					/**
					 * Filter the arguments passed to {@see get_posts()}
					 *
					 * May be useful if you want to show another posts in "Related Posts" block
					 *
					 * @param array $args Arguments
					 */
					$posts = get_posts( apply_filters( 'startapp_meta_box_related_args', array(
						'post_type'           => 'post',
						'post_status'         => 'publish',
						'posts_per_page'      => - 1,
						'orderby'             => 'ID',
						'order'               => 'ASC',
						// exclude current post
						'exclude'             => empty( $_GET['post'] ) ? 0 : (int) $_GET['post'],
						'suppress_filters'    => true,
						'ignore_sticky_posts' => true,
						'no_found_rows'       => true,
					) ) );

					$result = array();
					foreach ( $posts as $post ) {
						$result[ $post->ID ] = empty( $post->post_title ) ? esc_html__( '(no title)', 'startapp' ) : $post->post_title;
					}

					return $result;
				} ),
			) );

		equip_add_meta_box( '_startapp_related', $layout, array(
			'id'     => 'startapp-related-posts',
			'title'  => esc_html__( 'Related Posts', 'startapp' ),
			'screen' => 'post',
		) );

	} catch ( Exception $e ) {
		trigger_error( $e->getMessage() );
	}
}

add_action( 'current_screen', 'startapp_add_related_posts_meta_box' );

/**
 * Sanitize the Related Posts IDs
 *
 * @param array $value Related post IDs
 *
 * @return mixed
 */
function startapp_sanitize_related_posts( $value ) {
	if ( empty( $value ) ) {
		return array();
	}

	if ( ! is_array( $value ) ) {
		$value = (array) $value;
	}

	$value = array_map( 'intval', $value );
	$value = array_unique( $value );
	$value = array_filter( $value );

	return $value;
}
