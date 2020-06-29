<?php
/**
 * Theme Options
 *
 * @author 8guild
 */

// exit if accessed directly
if ( ! defined( 'ABSPATH' ) ) {
	die;
}

if ( ! defined( 'EQUIP_VERSION' ) ) {
	return;
}

if ( ! is_admin() ) {
	return;
}

/**
 * Add Theme Options
 *
 * @uses equip_add_options_page()
 */
function startapp_theme_options() {
	try {
		// create a layout for options
		$layout = equip_create_options_layout();

		$layout = startapp_add_colors_options( $layout );
		$layout = startapp_add_typography_options( $layout );
		$layout = startapp_add_header_options( $layout );
		$layout = startapp_add_footer_options( $layout );
		$layout = startapp_add_blog_options( $layout );
		$layout = startapp_add_page_options( $layout );
		$layout = startapp_add_shop_options( $layout );
		$layout = startapp_add_general_options( $layout );
		$layout = startapp_add_advanced_options( $layout );

		// register options through Equip
		equip_add_options_page( STARTAPP_OPTIONS, $layout, array(
			'page_title'  => esc_html__( 'StartApp Options', 'startapp' ),
			'menu_title'  => esc_html__( 'Theme Options', 'startapp' ),
			'capability'  => 'edit_theme_options',
			'menu_slug'   => 'startapp-options',
			'parent_slug' => 'startapp',
			'icon_url'    => '',
			'position'    => '3.33',
			'sass'        => array(
				'endpoint'    => esc_url( 'http://compiler.8guild.com/startapp' ), // no trailing slash, please
				'project_dir' => 'startapp',
				'filename'    => 'compiled.css',
				'option'      => STARTAPP_COMPILED,
				'option_copy' => STARTAPP_COMPILED . '_copy',
			),
		) );

	} catch ( Exception $e ) {
		trigger_error( 'Theme Options: ' . $e->getMessage() );
	}
}

add_action( 'equip/register', 'startapp_theme_options' );

/**
 * Add Colors options section
 *
 * @param \Equip\Layout\OptionsLayout $layout Layout
 *
 * @return \Equip\Layout\OptionsLayout
 */
function startapp_add_colors_options( $layout ) {

	try {
		$colors = $layout->add_section( 'colors', esc_html__( 'Colors', 'startapp' ), array(
			'icon' => 'material-icons invert_colors',
		) );

		//<editor-fold desc="Grayscale Anchor in Global Colors Options">
		$grayscale = $colors->add_anchor( 'colors_grayscale', esc_html__( 'Grayscale', 'startapp' ) );
		$grayscale
			->add_row()
			->add_column( 3 )
			->add_field( 'colors_grayscale_text', 'raw_text', array(
				'default' => esc_html__( 'Customize the grayscale of entire site.', 'startapp' ),
			) )
			->add_column( 3 )
			->add_field( 'colors_gray_darker', 'color', array(
				'label'   => esc_html__( 'Gray Darker', 'startapp' ),
				'default' => '#000000',
				'sass'    => 'gray-darker',
			) )
			->add_column( 3 )
			->add_field( 'colors_gray_dark', 'color', array(
				'label'   => esc_html__( 'Gray Dark', 'startapp' ),
				'default' => '#4c4c4c',
				'sass'    => 'gray-dark',
			) )
			->add_row()
			->add_column( 3 )
			->add_field( 'colors_gray', 'color', array(
				'label'   => esc_html__( 'Gray', 'startapp' ),
				'default' => '#808080',
				'sass'    => 'gray',
			) )
			->add_column( 3 )
			->add_field( 'colors_gray_light', 'color', array(
				'label'   => esc_html__( 'Gray Light', 'startapp' ),
				'default' => '#e5e5e5',
				'sass'    => 'gray-light',
			) )
			->add_column( 3 )
			->add_field( 'colors_gray_lighter', 'color', array(
				'label'   => esc_html__( 'Gray Lighter', 'startapp' ),
				'default' => '#f5f5f5',
				'sass'    => 'gray-lighter',
			) );
		//</editor-fold>

		//<editor-fold desc="Brand Colors Anchor in Global Colors Options">
		$brand = $colors->add_anchor( 'colors_brand', esc_html__( 'Brand Colors', 'startapp' ) );
		$brand
			->add_row()
			->add_column( 3 )
			->add_field( 'colors_default', 'color', array(
				'label'   => esc_html__( 'Default', 'startapp' ),
				'default' => '#000000',
				'sass'    => 'brand-default',
			) )
			->add_column( 3 )
			->add_field( 'colors_primary', 'color', array(
				'label'   => esc_html__( 'Primary', 'startapp' ),
				'default' => '#3f6bbe',
				'sass'    => 'brand-primary',
			) )
			->add_column( 3 )
			->add_field( 'colors_info', 'color', array(
				'label'   => esc_html__( 'Info', 'startapp' ),
				'default' => '#4eabff',
				'sass'    => 'brand-info',
			) )
			->add_row()
			->add_column( 3 )
			->add_field( 'colors_success', 'color', array(
				'label'   => esc_html__( 'Success', 'startapp' ),
				'default' => '#5bc460',
				'sass'    => 'brand-success',
			) )
			->add_column( 3 )
			->add_field( 'colors_warning', 'color', array(
				'label'   => esc_html__( 'Warning', 'startapp' ),
				'default' => '#a9db23',
				'sass'    => 'brand-warning',
			) )
			->add_column( 3 )
			->add_field( 'colors_danger', 'color', array(
				'label'   => esc_html__( 'Danger', 'startapp' ),
				'default' => '#e63030',
				'sass'    => 'brand-danger',
			) );
		//</editor-fold>

		//<editor-fold desc="Type Colors Anchor in Global Colors Options">
		$type = $colors->add_anchor( 'colors_type', esc_html__( 'Type Colors', 'startapp' ) );
		$type
			->add_row()
			->add_column( 3 )
			->add_field( 'colors_body_bg', 'color', array(
				'label'   => esc_html__( 'Body Background', 'startapp' ),
				'default' => '#ffffff',
				'sass'    => 'body-bg',
			) )
			->add_column( 3 )
			->add_field( 'colors_body_text', 'color', array(
				'label'   => esc_html__( 'Body Text', 'startapp' ),
				'default' => '#4c4c4c',
				'sass'    => 'text-color',
				'master'  => array( 'colors_gray_dark' ),
			) )
			->add_column( 3 )
			->add_field( 'colors_headings', 'color', array(
				'label'   => esc_html__( 'Headings', 'startapp' ),
				'default' => '#000000',
				'sass'    => 'headings-color',
				'master'  => array( 'colors_gray_darker' ),
			) )
			->add_row()
			->add_column( 3 )
			->add_field( 'colors_link', 'color', array(
				'label'   => esc_html__( 'Link', 'startapp' ),
				'default' => '#3f6bbe',
				'sass'    => 'link-color',
				'master'  => array( 'colors_primary' ),
			) )
			->add_column( 3 )
			->add_field( 'colors_link_hover', 'color', array(
				'label'   => esc_html__( 'Link Hover', 'startapp' ),
				'default' => '#3f6bbe',
				'sass'    => 'link-hover-color',
				'master'  => array( 'colors_primary' ),
			) )
			->add_row()
			->add_column( 3 )
			->add_field( 'colors_quote_text', 'color', array(
				'label'   => esc_html__( 'Quotation Text', 'startapp' ),
				'default' => '#000000',
				'sass'    => 'quote-font-color',
				'master'  => array( 'colors_gray_darker' ),
			) )
			->add_column( 3 )
			->add_field( 'colors_quote_mark', 'color', array(
				'label'   => esc_html__( 'Quotation Mark', 'startapp' ),
				'default' => '#3f6bbe',
				'sass'    => 'quote-mark-color',
				'master'  => array( 'colors_primary' ),
			) )
			->add_column( 3 )
			->add_field( 'colors_quote_author', 'color', array(
				'label'   => esc_html__( 'Quotation Author', 'startapp' ),
				'default' => '#808080',
				'sass'    => 'color-cite',
				'master'  => array( 'colors_gray' ),
			) );
		//</editor-fold>

		//<editor-fold desc="Navigation Colors Anchor in Global Colors Options">
		$navi = $colors->add_anchor( 'colors_navi', esc_html__( 'Navigation', 'startapp' ) );
		$navi
			->add_row()
			->add_column( 3 )
			->add_field( 'colors_menu_link', 'color', array(
				'label'   => esc_html__( 'Menu Link', 'startapp' ),
				'default' => '#4c4c4c',
				'sass'    => 'menu-link-color',
				'master'  => array( 'colors_gray_dark' ),
			) )
			->add_column( 3 )
			->add_field( 'colors_menu_link_active', 'color', array(
				'label'   => esc_html__( 'Menu Link Hover / Active', 'startapp' ),
				'default' => '#3f6bbe',
				'sass'    => 'menu-link-active-color',
				'master'  => array( 'colors_primary' ),
			) )
			->add_column( 3 )
			->add_field( 'colors_menu_link_hover_bg', 'color', array(
				'label'   => esc_html__( 'Menu Link Hover BG', 'startapp' ),
				'default' => '#f5f5f5',
				'sass'    => 'menu-link-hover-bg',
				'master'  => array( 'colors_gray_lighter' ),
			) )
			->add_row()
			->add_column( 3 )
			->add_field( 'colors_scroller_bg', 'color', array(
				'label'   => esc_html__( 'Scroller Background', 'startapp' ),
				'default' => '#ffffff',
				'sass'    => 'scroller-bg',
			) )
			->add_column( 3 )
			->add_field( 'colors_scroller_dots', 'color', array(
				'label'   => esc_html__( 'Scroller Dots', 'startapp' ),
				'default' => '#3f6bbe',
				'sass'    => 'scroller-dot-color',
				'master'  => array( 'colors_primary' ),
			) )
			->add_row()
			->add_column( 3 )
			->add_field( 'colors_breadcrumb', 'color', array(
				'label'   => esc_html__( 'Breadcrumbs Link', 'startapp' ),
				'default' => '#000000',
				'sass'    => 'breadcrumb-link-color',
				'master'  => array( 'colors_gray_darker' ),
			) )
			->add_column( 3 )
			->add_field( 'colors_breadcrumb_hover', 'color', array(
				'label'   => esc_html__( 'Breadcrumbs Link Hover', 'startapp' ),
				'default' => '#3f6bbe',
				'sass'    => 'breadcrumb-link-hover-color',
				'master'  => array( 'colors_primary' ),
			) )
			->add_row()
			->add_column( 3 )
			->add_field( 'colors_pagination', 'color', array(
				'label'   => esc_html__( 'Pagination Link', 'startapp' ),
				'default' => '#808080',
				'sass'    => 'page-link-color',
				'master'  => array( 'colors_gray' ),
			) )
			->add_column( 3 )
			->add_field( 'colors_pagination_hover', 'color', array(
				'label'   => esc_html__( 'Pagination Link Hover', 'startapp' ),
				'default' => '#3f6bbe',
				'sass'    => 'page-link-hover-color',
				'master'  => array( 'colors_primary' ),
			) )
			->add_row()
			->add_column( 3 )
			->add_field( 'colors_tabs', 'color', array(
				'label'   => esc_html__( 'Tabs / Filters', 'startapp' ),
				'default' => '#4c4c4c',
				'sass'    => 'nav-tabs-link-color',
				'master'  => array( 'colors_gray_dark' ),
			) )
			->add_column( 3 )
			->add_field( 'colors_tabs_hover', 'color', array(
				'label'   => esc_html__( 'Tabs / Filters Hover / Active', 'startapp' ),
				'default' => '#3f6bbe',
				'sass'    => 'nav-tabs-link-active-color',
				'master'  => array( 'colors_primary' ),
			) );
		//</editor-fold>

		//<editor-fold desc="Forms Anchor in Global Colors Options">
		$forms = $colors->add_anchor( 'colors_forms', esc_html__( 'Forms', 'startapp' ) );
		$forms
			->add_row()
			->add_column( 3 )
			->add_field( 'colors_input', 'color', array(
				'label'   => esc_html__( 'Input Color', 'startapp' ),
				'default' => '#4c4c4c',
				'sass'    => 'input-color',
				'master'  => array( 'colors_gray_dark' ),
			) )
			->add_column( 3 )
			->add_field( 'colors_input_bg', 'color', array(
				'label'   => esc_html__( 'Input Background', 'startapp' ),
				'default' => '#f5f5f5',
				'sass'    => 'input-bg-color',
				'master'  => array( 'colors_gray_lighter' ),
			) )
			->add_column( 3 )
			->add_field( 'colors_input_focus_bg', 'color', array(
				'label'   => esc_html__( 'Input Focus Background', 'startapp' ),
				'default' => '#ffffff',
				'sass'    => 'input-focus-bg-color',
			) )
			->add_row()
			->add_column( 3 )
			->add_field( 'colors_input_border', 'color', array(
				'label'   => esc_html__( 'Input Border', 'startapp' ),
				'default' => '#ffffff',
				'sass'    => 'input-border-color',
			) )
			->add_column( 3 )
			->add_field( 'colors_input_focus_border', 'color', array(
				'label'   => esc_html__( 'Input Focus Border', 'startapp' ),
				'default' => '#bfbfbf',
				'sass'    => 'input-focus-border-color',
			) )
			->add_column( 3 )
			->add_field( 'colors_input_placeholder', 'color', array(
				'label'   => esc_html__( 'Input Placeholder', 'startapp' ),
				'default' => '#808080',
				'sass'    => 'input-color-placeholder',
				'master'  => array( 'colors_gray' ),
			) );
		//</editor-fold>

	} catch ( Exception $e ) {
		trigger_error( 'Colors Options: ' . $e->getMessage() );
	}

	return $layout;
}

/**
 * Add Typography options section
 *
 * @param \Equip\Layout\OptionsLayout $layout Layout
 *
 * @return \Equip\Layout\OptionsLayout
 */
function startapp_add_typography_options( $layout ) {

	$translated = array(
		'enable'         => esc_html__( 'Enable', 'startapp' ),
		'disable'        => esc_html__( 'Disable', 'startapp' ),
		'font_size'      => esc_html__( 'Font Size', 'startapp' ),
		'font_weight'    => esc_html__( 'Font Weight', 'startapp' ),
		'text_transform' => esc_html__( 'Text Transform', 'startapp' ),
		'font_style'     => esc_html__( 'Font Style', 'startapp' ),
	);

	$options_font_weight = array(
		'lighter' => 'Lighter',
		'normal'  => 'Normal',
		'bold'    => 'Bold',
		'bolder'  => 'Bolder',
		'100'     => '100',
		'200'     => '200',
		'300'     => '300',
		'400'     => '400',
		'500'     => '500',
		'600'     => '600',
		'700'     => '700',
		'800'     => '800',
		'900'     => '900',
	);

	$options_text_transform = array(
		'none'       => 'None',
		'capitalize' => 'Capitalize',
		'lowercase'  => 'Lowercase',
		'uppercase'  => 'Uppercase'
	);

	$options_font_style = array(
		'normal'  => 'Normal',
		'italic'  => 'Italic',
		'oblique' => 'Oblique',
	);

	try {
		$typography = $layout->add_section( 'typography', esc_html__( 'Typography', 'startapp' ), array(
			'icon' => 'material-icons format_size',
		) );

		//<editor-fold desc="Google Fonts Anchor in Typography Options">
		$google = $typography->add_anchor( 'typography_google_fonts', esc_html__( 'Google Fonts', 'startapp' ) );
		$google
			->add_row()
			->add_column( 3 )
			->add_field( 'typography_is_google_fonts', 'switch', array(
				'label'     => esc_html__( 'Enable Google Fonts?', 'startapp' ),
				'default'   => true,
				'label_on'  => $translated['enable'],
				'label_off' => $translated['disable'],
			) )
			->add_column( 5 )
			->add_field( 'typography_font_for_body', 'text', array(
				'label'       => esc_html__( 'Font for Body', 'startapp' ),
				'description' => wp_kses( 'Go to <a href="https://www.google.com/fonts" target="_blank">google.com/fonts</a>, click "Quick-use" button and follow the instructions. From step 3 copy the "href" value and paste in field above.', array(
					'a' => array( 'href' => true, 'target' => true ),
				) ),
				'default'     => '//fonts.googleapis.com/css?family=Titillium+Web:400,300,700,600',
				'required'    => array( 'typography_is_google_fonts', '=', true ),
			) )
			->add_column( 4 )
			->add_field( 'typography_font_for_headings', 'text', array(
				'label'       => esc_html__( 'Font for Headings', 'startapp' ),
				'description' => esc_html__( 'If empty inherits from Body google link.', 'startapp' ),
				'default'     => '',
				'required'    => array( 'typography_is_google_fonts', '=', true ),
			) );
		//</editor-fold>

		//<editor-fold desc="Font Family Anchor in Typography Options">
		$font_family = $typography->add_anchor( 'typography_font_family', esc_html__( 'Font Family', 'startapp' ) );
		$font_family
			->add_row()
			->add_column( 4 )
			->add_field( 'typography_ff_text', 'raw_text', array(
				'default' => esc_html__( 'Put chosen google font (do not forget about quotation marks) along with fallback fonts, separated by comma. ', 'startapp' ),
			) )
			->add_column( 4 )
			->add_field( 'typography_ff_body', 'text', array(
				'label'   => esc_html__( 'Body Copy', 'startapp' ),
				'default' => '"Titillium Web", Helvetica, Arial, sans-serif',
				'sass'    => 'font-family-base',
			) )
			->add_column( 4 )
			->add_field( 'typography_ff_headings', 'text', array(
				'label'   => esc_html__( 'Headings', 'startapp' ),
				'default' => '',
				'sass'    => 'font-family-headings',
			) );
		//</editor-fold>

		//<editor-fold desc="Font Size Anchor in Typography Options">
		$font_size = $typography->add_anchor( 'typography_font_size', esc_html__( 'Font Size', 'startapp' ) );
		$font_size
			->add_row()
			->add_column( 4 )
			->add_field( 'typography_fs_text', 'raw_text', array(
				'default' => esc_html__( 'Set the global font sizes for body and formats.', 'startapp' ),
				'attr'    => array(
					'style' => 'padding-top:20px;',
				),
			) )
			->add_column( 4 )
			->add_field( 'typography_fs_body', 'slider', array(
				'label'   => esc_html__( 'Body Copy', 'startapp' ),
				'min'     => 0,
				'max'     => 80,
				'default' => 14,
				'sass'    => array( 'var' => 'font-size-base', 'append' => 'px' ),
			) )
			->add_column( 4 )
			->add_field( 'typography_fs_lead', 'slider', array(
				'label'   => esc_html__( 'Lead', 'startapp' ),
				'min'     => 0,
				'max'     => 80,
				'default' => 24,
				'sass'    => array( 'var' => 'font-size-lead', 'append' => 'px' ),
				'master'  => array( 'typography_fs_body', '*', 1.7, 'ceil' ),
			) )
			->add_row()
			->add_column( 4 )
			->add_field( 'typography_fs_lg', 'slider', array(
				'label'   => esc_html__( 'Large', 'startapp' ),
				'min'     => 0,
				'max'     => 80,
				'default' => 16,
				'sass'    => array( 'var' => 'font-size-lg', 'append' => 'px' ),
				'master'  => array( 'typography_fs_body', '*', 1.14, 'ceil' ),
			) )
			->add_column( 4 )
			->add_field( 'typography_fs_sm', 'slider', array(
				'label'   => esc_html__( 'Small', 'startapp' ),
				'min'     => 0,
				'max'     => 80,
				'default' => 12,
				'sass'    => array( 'var' => 'font-size-sm', 'append' => 'px' ),
				'master'  => array( 'typography_fs_body', '*', 0.86, 'floor' ),
			) )
			->add_column( 4 )
			->add_field( 'typography_fs_xs', 'slider', array(
				'label'   => esc_html__( 'Extra Small', 'startapp' ),
				'min'     => 0,
				'max'     => 80,
				'default' => 10,
				'sass'    => array( 'var' => 'font-size-xs', 'append' => 'px' ),
				'master'  => array( 'typography_fs_body', '/', 1.4, 'floor' ),
			) );
		//</editor-fold>

		//<editor-fold desc="Heading 1 (H1) Anchor in Typography Options">
		$heading1 = $typography->add_anchor( 'typography_h1', esc_html__( 'Heading 1 (H1)', 'startapp' ) );
		$heading1
			->add_row()
			->add_column( 3 )
			->add_field( 'typography_h1_font_size', 'slider', array(
				'label'   => $translated['font_size'],
				'min'     => 0,
				'max'     => 80,
				'default' => 48,
				'sass'    => array( 'var' => 'font-size-h1', 'append' => 'px' ),
				'master'  => array( 'typography_fs_body', '*', 3.43, 'floor' ),
			) )
			->add_column( 3 )
			->add_field( 'typography_h1_font_weight', 'select', array(
				'label'   => $translated['font_weight'],
				'default' => '300',
				'sass'    => 'font-weight-h1',
				'options' => $options_font_weight,
			) )
			->add_column( 3 )
			->add_field( 'typography_h1_text_transform', 'select', array(
				'label'   => $translated['text_transform'],
				'default' => 'none',
				'sass'    => 'text-transform-h1',
				'options' => $options_text_transform,
			) )
			->add_column( 3 )
			->add_field( 'typography_h1_font_style', 'select', array(
				'label'   => $translated['font_style'],
				'default' => 'normal',
				'sass'    => 'font-style-h1',
				'options' => $options_font_style,
			) );
		//</editor-fold>

		//<editor-fold desc="Heading 2 (H2) Anchor in Typography Options">
		$heading2 = $typography->add_anchor( 'typography_h2', esc_html__( 'Heading 2 (H2)', 'startapp' ) );
		$heading2
			->add_row()
			->add_column( 3 )
			->add_field( 'typography_h2_font_size', 'slider', array(
				'label'   => $translated['font_size'],
				'min'     => 0,
				'max'     => 80,
				'default' => 36,
				'sass'    => array( 'var' => 'font-size-h2', 'append' => 'px' ),
				'master'  => array( 'typography_fs_body', '*', 2.57, 'ceil' ),
			) )
			->add_column( 3 )
			->add_field( 'typography_h2_font_weight', 'select', array(
				'label'   => $translated['font_weight'],
				'default' => '300',
				'sass'    => 'font-weight-h2',
				'options' => $options_font_weight,
			) )
			->add_column( 3 )
			->add_field( 'typography_h2_text_transform', 'select', array(
				'label'   => $translated['text_transform'],
				'default' => 'none',
				'sass'    => 'text-transform-h2',
				'options' => $options_text_transform,
			) )
			->add_column( 3 )
			->add_field( 'typography_h2_font_style', 'select', array(
				'label'   => $translated['font_style'],
				'default' => 'normal',
				'sass'    => 'font-style-h2',
				'options' => $options_font_style,
			) );
		//</editor-fold>

		//<editor-fold desc="Heading 3 (H3) Anchor in Typography Options">
		$heading3 = $typography->add_anchor( 'typography_h3', esc_html__( 'Heading 3 (H3)', 'startapp' ) );
		$heading3
			->add_row()
			->add_column( 3 )
			->add_field( 'typography_h3_font_size', 'slider', array(
				'label'   => $translated['font_size'],
				'min'     => 0,
				'max'     => 80,
				'default' => 30,
				'sass'    => array( 'var' => 'font-size-h3', 'append' => 'px' ),
				'master'  => array( 'typography_fs_body', '*', 2.14, 'ceil' ),
			) )
			->add_column( 3 )
			->add_field( 'typography_h3_font_weight', 'select', array(
				'label'   => $translated['font_weight'],
				'default' => '300',
				'sass'    => 'font-weight-h3',
				'options' => $options_font_weight,
			) )
			->add_column( 3 )
			->add_field( 'typography_h3_text_transform', 'select', array(
				'label'   => $translated['text_transform'],
				'default' => 'none',
				'sass'    => 'text-transform-h3',
				'options' => $options_text_transform,
			) )
			->add_column( 3 )
			->add_field( 'typography_h3_font_style', 'select', array(
				'label'   => $translated['font_style'],
				'default' => 'normal',
				'sass'    => 'font-style-h3',
				'options' => $options_font_style,
			) );
		//</editor-fold>

		//<editor-fold desc="Heading 4 (H4) Anchor in Typography Options">
		$heading4 = $typography->add_anchor( 'typography_h4', esc_html__( 'Heading 4 (H4)', 'startapp' ) );
		$heading4
			->add_row()
			->add_column( 3 )
			->add_field( 'typography_h4_font_size', 'slider', array(
				'label'   => $translated['font_size'],
				'min'     => 0,
				'max'     => 80,
				'default' => 24,
				'sass'    => array( 'var' => 'font-size-h4', 'append' => 'px' ),
				'master'  => array( 'typography_fs_body', '*', 1.7, 'ceil' ),
			) )
			->add_column( 3 )
			->add_field( 'typography_h4_font_weight', 'select', array(
				'label'   => $translated['font_weight'],
				'default' => '300',
				'sass'    => 'font-weight-h4',
				'options' => $options_font_weight,
			) )
			->add_column( 3 )
			->add_field( 'typography_h4_text_transform', 'select', array(
				'label'   => $translated['text_transform'],
				'default' => 'none',
				'sass'    => 'text-transform-h4',
				'options' => $options_text_transform,
			) )
			->add_column( 3 )
			->add_field( 'typography_h4_font_style', 'select', array(
				'label'   => $translated['font_style'],
				'default' => 'normal',
				'sass'    => 'font-style-h4',
				'options' => $options_font_style,
			) );
		//</editor-fold>

		//<editor-fold desc="Heading 5 (H5) Anchor in Typography Options">
		$heading5 = $typography->add_anchor( 'typography_h5', esc_html__( 'Heading 5 (H5)', 'startapp' ) );
		$heading5
			->add_row()
			->add_column( 3 )
			->add_field( 'typography_h5_font_size', 'slider', array(
				'label'   => $translated['font_size'],
				'min'     => 0,
				'max'     => 80,
				'default' => 18,
				'sass'    => array( 'var' => 'font-size-h5', 'append' => 'px' ),
				'master'  => array( 'typography_fs_body', '*', 1.29, 'floor' ),
			) )
			->add_column( 3 )
			->add_field( 'typography_h5_font_weight', 'select', array(
				'label'   => $translated['font_weight'],
				'default' => '300',
				'sass'    => 'font-weight-h5',
				'options' => $options_font_weight,
			) )
			->add_column( 3 )
			->add_field( 'typography_h5_text_transform', 'select', array(
				'label'   => $translated['text_transform'],
				'default' => 'none',
				'sass'    => 'text-transform-h5',
				'options' => $options_text_transform,
			) )
			->add_column( 3 )
			->add_field( 'typography_h5_font_style', 'select', array(
				'label'   => $translated['font_style'],
				'default' => 'normal',
				'sass'    => 'font-style-h5',
				'options' => $options_font_style,
			) );
		//</editor-fold>

		//<editor-fold desc="Heading 6 (H6) Anchor in Typography Options">
		$heading6 = $typography->add_anchor( 'typography_h6', esc_html__( 'Heading 6 (H6)', 'startapp' ) );
		$heading6
			->add_row()
			->add_column( 3 )
			->add_field( 'typography_h6_font_size', 'slider', array(
				'label'   => $translated['font_size'],
				'min'     => 0,
				'max'     => 80,
				'default' => 16,
				'sass'    => array( 'var' => 'font-size-h6', 'append' => 'px' ),
				'master'  => array( 'typography_fs_body', '*', 1.14, 'ceil' ),
			) )
			->add_column( 3 )
			->add_field( 'typography_h6_font_weight', 'select', array(
				'label'   => $translated['font_weight'],
				'default' => '300',
				'sass'    => 'font-weight-h6',
				'options' => $options_font_weight,
			) )
			->add_column( 3 )
			->add_field( 'typography_h6_text_transform', 'select', array(
				'label'   => $translated['text_transform'],
				'default' => 'none',
				'sass'    => 'text-transform-h6',
				'options' => $options_text_transform,
			) )
			->add_column( 3 )
			->add_field( 'typography_h6_font_style', 'select', array(
				'label'   => $translated['font_style'],
				'default' => 'normal',
				'sass'    => 'font-style-h6',
				'options' => $options_font_style,
			) );
		//</editor-fold>

		//<editor-fold desc="Quotation Anchor in Typography Options">
		$quote = $typography->add_anchor( 'typography_quote', esc_html__( 'Quotation', 'startapp' ) );
		$quote
			->add_row()
			->add_column( 3 )
			->add_field( 'typography_quote_font_size', 'slider', array(
				'label'   => $translated['font_size'],
				'min'     => 0,
				'max'     => 80,
				'default' => 24,
				'sass'    => array( 'var' => 'font-size-quote', 'append' => 'px' ),
				'master'  => array( 'typography_fs_body', '*', 1.7, 'ceil' ),
			) )
			->add_column( 3 )
			->add_field( 'typography_quote_font_weight', 'select', array(
				'label'   => $translated['font_weight'],
				'default' => 'normal',
				'sass'    => 'font-weight-quote',
				'options' => $options_font_weight,
			) )
			->add_column( 3 )
			->add_field( 'typography_quote_text_transform', 'select', array(
				'label'   => $translated['text_transform'],
				'default' => 'none',
				'sass'    => 'text-transform-quote',
				'options' => $options_text_transform,
			) )
			->add_column( 3 )
			->add_field( 'typography_quote_font_style', 'select', array(
				'label'   => $translated['font_style'],
				'default' => 'normal',
				'sass'    => 'font-style-quote',
				'options' => $options_font_style,
			) )
			->add_row()
			->add_column( 3 )
			->add_field( 'typography_quote_mark_font_size', 'slider', array(
				'label'   => esc_html__( 'Mark Font Size', 'startapp' ),
				'min'     => 0,
				'max'     => 80,
				'default' => 24,
				'sass'    => array( 'var' => 'quote-mark-icon', 'append' => 'px' ),
			) )
			->add_column( 3 )
			->add_field( 'typography_quote_author_font_size', 'slider', array(
				'label'   => esc_html__( 'Author Font Size', 'startapp' ),
				'min'     => 0,
				'max'     => 80,
				'default' => 14,
				'sass'    => array( 'var' => 'font-size-cite', 'append' => 'px' ),
				'master'  => array( 'typography_fs_body', '*', 1, 'ceil' ),
			) );
		//</editor-fold>

		//<editor-fold desc="Navigation Anchor in Typography Options">
		$navi = $typography->add_anchor( 'typography_navi', esc_html__( 'Navigation', 'startapp' ) );
		$navi
			->add_row()
			->add_column( 3 )
			->add_field( 'typography_navi_menu_fs', 'slider', array(
				'label'   => esc_html__( 'Top-Level Menu Font Size', 'startapp' ),
				'min'     => 0,
				'max'     => 80,
				'default' => 18,
				'sass'    => array( 'var' => 'menu-link-font-size', 'append' => 'px' ),
				'master'  => array( 'typography_fs_body', '*', 1.29, 'floor' ),
			) )
			->add_column( 3 )
			->add_field( 'typography_navi_menu_fw', 'select', array(
				'label'   => esc_html__( 'Menu Font Weight', 'startapp' ),
				'default' => '600',
				'sass'    => 'menu-link-font-weight',
				'options' => $options_font_weight,
			) )
			->add_column( 3 )
			->add_field( 'typography_navi_submenu_fs', 'slider', array(
				'label'   => esc_html__( 'Sub-Menu Font Size', 'startapp' ),
				'min'     => 0,
				'max'     => 80,
				'default' => 14,
				'sass'    => array( 'var' => 'menu-dropdown-link-font-size', 'append' => 'px' ),
				'master'  => array( 'typography_fs_body', '*', 1, 'ceil' ),
			) )
			->add_column( 3 )
			->add_field( 'typography_navi_scroller', 'slider', array(
				'label'   => esc_html__( 'Scroller Dots Size', 'startapp' ),
				'min'     => 0,
				'max'     => 20,
				'default' => 8,
				'sass'    => array( 'var' => 'scroller-dot-size', 'append' => 'px' ),
			) )
			->add_row()
			->add_column( 3 )
			->add_field( 'typography_navi_tabs_fs', 'slider', array(
				'label'   => esc_html__( 'Tabs / Filters Font Size', 'startapp' ),
				'min'     => 0,
				'max'     => 80,
				'default' => 18,
				'sass'    => array( 'var' => 'nav-tabs-font-size', 'append' => 'px' ),
				'master'  => array( 'typography_fs_body', '*', 1.29, 'floor' ),
			) )
			->add_column( 3 )
			->add_field( 'typography_navi_tabs_fw', 'select', array(
				'label'   => esc_html__( 'Tabs / Filters Font Weight', 'startapp' ),
				'default' => 'normal',
				'sass'    => 'nav-tabs-font-weight',
				'options' => $options_font_weight,
			) )
			->add_column( 3 )
			->add_field( 'typography_navi_tabs_icon_size', 'slider', array(
				'label'   => esc_html__( 'Tabs / Filters Icon Size', 'startapp' ),
				'min'     => 0,
				'max'     => 80,
				'default' => 16,
				'sass'    => array( 'var' => 'nav-tabs-icon-font-size', 'append' => 'px' ),
				'master'  => array( 'typography_fs_body', '*', 1.14, 'ceil' ),
			) )
			->add_row()
			->add_column( 3 )
			->add_field( 'typography_navi_pagination_fs', 'slider', array(
				'label'   => esc_html__( 'Pagination Font Size', 'startapp' ),
				'min'     => 0,
				'max'     => 80,
				'default' => 16,
				'sass'    => array( 'var' => 'page-link-size', 'append' => 'px' ),
				'master'  => array( 'typography_fs_body', '*', 1.14, 'ceil' ),
			) )
			->add_column( 3 )
			->add_field( 'typography_navi_pagination_fw', 'select', array(
				'label'   => esc_html__( 'Pagination Font Weight', 'startapp' ),
				'default' => '600',
				'sass'    => 'page-link-weight',
				'options' => $options_font_weight,
			) )
			->add_column( 3 )
			->add_field( 'typography_navi_breadcrumbs_fs', 'slider', array(
				'label'   => esc_html__( 'Breadcrumbs Font Size', 'startapp' ),
				'min'     => 0,
				'max'     => 80,
				'default' => 14,
				'sass'    => array( 'var' => 'breadcrumb-link-font-size', 'append' => 'px' ),
				'master'  => array( 'typography_fs_body', '*', 1, 'ceil' ),
			) )
			->add_column( 3 )
			->add_field( 'typography_navi_breadcrumbs_fw', 'select', array(
				'label'   => esc_html__( 'Breadcrumbs Font Weight', 'startapp' ),
				'default' => '600',
				'sass'    => 'breadcrumb-link-font-weight',
				'options' => $options_font_weight,
			) );
		//</editor-fold>

		//<editor-fold desc="Buttons Anchor in Typography Options">
		$buttons = $typography->add_anchor( 'typography_buttons', esc_html__( 'Buttons', 'startapp' ) );
		$buttons
			->add_row()
			->add_column( 3 )
			->add_field( 'typography_buttons_lg_fs', 'slider', array(
				'label'   => esc_html__( 'Large Button Font Size', 'startapp' ),
				'min'     => 0,
				'max'     => 80,
				'default' => 18,
				'sass'    => array( 'var' => 'btn-lg-font-size', 'append' => 'px' ),
				'master'  => array( 'typography_fs_body', '*', 1.29, 'floor' ),
			) )
			->add_column( 3 )
			->add_field( 'typography_buttons_nm_fs', 'slider', array(
				'label'   => esc_html__( 'Normal Button Font Size', 'startapp' ),
				'min'     => 0,
				'max'     => 80,
				'default' => 18,
				'sass'    => array( 'var' => 'btn-font-size', 'append' => 'px' ),
				'master'  => array( 'typography_fs_body', '*', 1.29, 'floor' ),
			) )
			->add_column( 3 )
			->add_field( 'typography_buttons_sm_fs', 'slider', array(
				'label'   => esc_html__( 'Small Button Font Size', 'startapp' ),
				'min'     => 0,
				'max'     => 80,
				'default' => 16,
				'sass'    => array( 'var' => 'btn-sm-font-size', 'append' => 'px' ),
				'master'  => array( 'typography_fs_body', '*', 1.14, 'ceil' ),
			) )
			->add_column( 3 )
			->add_field( 'typography_buttons_xs_fs', 'slider', array(
				'label'   => esc_html__( 'Extra Small Button Font Size', 'startapp' ),
				'min'     => 0,
				'max'     => 80,
				'default' => 14,
				'sass'    => array( 'var' => 'btn-xs-font-size', 'append' => 'px' ),
				'master'  => array( 'typography_fs_body', '*', 1, 'floor' ),
			) )
			->add_row()
			->add_column( 3 )
			->add_field( 'typography_buttons_fw', 'select', array(
				'label'   => $translated['font_weight'],
				'default' => '600',
				'sass'    => 'btn-font-weight',
				'options' => $options_font_weight,
			) )
			->add_column( 3 )
			->add_field( 'typography_buttons_tt', 'select', array(
				'label'   => $translated['text_transform'],
				'default' => 'none',
				'sass'    => 'btn-text-transform',
				'options' => $options_text_transform,
			) );
		//</editor-fold>

		//<editor-fold desc="Line Heights Anchor in Typography Options">
		$line_height = $typography->add_anchor( 'typography_line_height', esc_html__( 'Line Heights', 'startapp' ) );
		$line_height
			->add_row()
			->add_column( 3 )
			->add_field( 'typography_lh_text', 'raw_text', array(
				'default' => esc_html__( 'Line height inputs accepts any positive number. Please note these are not pixel values. They work as multiplier: font size * line height.', 'startapp' ),
			) )
			->add_column( 3 )
			->add_field( 'typography_lh_base', 'text', array(
				'label'    => esc_html__( 'Line Height Base', 'startapp' ),
				'default'  => 1.5,
				'sass'     => 'line-height-base',
				'sanitize' => 'startapp_sanitize_float',
				'escape'   => 'startapp_sanitize_float',
			) )
			->add_column( 3 )
			->add_field( 'typography_lh_h1', 'text', array(
				'label'    => esc_html__( 'Heading 1 (H1)', 'startapp' ),
				'default'  => 1.17,
				'sass'     => 'line-height-h1',
				'sanitize' => 'startapp_sanitize_float',
				'escape'   => 'startapp_sanitize_float',
			) )
			->add_column( 3 )
			->add_field( 'typography_lh_h2', 'text', array(
				'label'    => esc_html__( 'Heading 2 (H2)', 'startapp' ),
				'default'  => 1.33,
				'sass'     => 'line-height-h2',
				'sanitize' => 'startapp_sanitize_float',
				'escape'   => 'startapp_sanitize_float',
			) )
			->add_row()
			->add_column( 3 )
			->add_field( 'typography_lh_h3', 'text', array(
				'label'    => esc_html__( 'Heading 3 (H3)', 'startapp' ),
				'default'  => 1.5,
				'sass'     => 'line-height-h3',
				'sanitize' => 'startapp_sanitize_float',
				'escape'   => 'startapp_sanitize_float',
			) )
			->add_column( 3 )
			->add_field( 'typography_lh_h4', 'text', array(
				'label'    => esc_html__( 'Heading 4 (H4)', 'startapp' ),
				'default'  => 1.5,
				'sass'     => 'line-height-h4',
				'sanitize' => 'startapp_sanitize_float',
				'escape'   => 'startapp_sanitize_float',
			) )
			->add_column( 3 )
			->add_field( 'typography_lh_h5', 'text', array(
				'label'    => esc_html__( 'Heading 5 (H5)', 'startapp' ),
				'default'  => 1.5,
				'sass'     => 'line-height-h5',
				'sanitize' => 'startapp_sanitize_float',
				'escape'   => 'startapp_sanitize_float',
			) )
			->add_column( 3 )
			->add_field( 'typography_lh_h6', 'text', array(
				'label'    => esc_html__( 'Heading 6 (H6)', 'startapp' ),
				'default'  => 1.5,
				'sass'     => 'line-height-h6',
				'sanitize' => 'startapp_sanitize_float',
				'escape'   => 'startapp_sanitize_float',
			) );
		//</editor-fold>

	} catch ( Exception $e ) {
		trigger_error( 'Typography Options: ' . $e->getMessage() );
	}

	return $layout;
}

/**
 * Add Header options section
 *
 * @param \Equip\Layout\OptionsLayout $layout Layout
 *
 * @return \Equip\Layout\OptionsLayout
 */
function startapp_add_header_options( $layout ) {

	// check if custom logo exists, WP 4.5+
	$is_logo = function_exists( 'the_custom_logo' );

	$translated = array(
		'enable'  => esc_html__( 'Enable', 'startapp' ),
		'disable' => esc_html__( 'Disable', 'startapp' ),
	);

	$options_left_right = array(
		'left' 	=> esc_html__( 'Left', 'startapp' ),
		'right' => esc_html__( 'Right', 'startapp' ),
	);

	try {
		$header = $layout->add_section( 'header', esc_html__( 'Header', 'startapp' ), array(
			'icon' => 'material-icons payment',
		) );

		//<editor-fold desc="General Anchor in Header Options">
		$general = $header->add_anchor( 'header-general-anchor', esc_html__( 'General', 'startapp' ) );
		$general
			->add_row()
			->add_column( 4 )
			->add_field( 'header_custom_logo', 'raw_text', array(
				'label'   => esc_html__( 'Logo', 'startapp' ),
				'default' => esc_html__( 'With new WordPress version you can now upload your logo in Appearance > Customize > Site Identity > Logo', 'startapp' ),
				'hidden'  => ! $is_logo,
			) )
			->add_field( 'header_logo', 'media', array(
				'label'  => esc_html__( 'Logo', 'startapp' ),
				'hidden' => $is_logo,
				'media'  => array( 'title' => esc_html__( 'Choose a Logo', 'startapp' ) ),
			) )
			->add_column( 5 )
			->add_field( 'header_logo_width', 'slider', array(
				'label'       => esc_html__( 'Logo Width', 'startapp' ),
				'description' => esc_html__( 'For High Resolution screens is recommended to upload image twice as big as this value.', 'startapp' ),
				'min'         => 30,
				'max'         => 200,
				'default'     => 146,
				'sass'        => array( 'var' => 'site-logo-width', 'append' => 'px' ),
			) )
			->add_row()
			->add_column( 4 )
			->add_field( 'header_mobile_logo', 'media', array(
				'label' => esc_html__( 'Mobile Logo', 'startapp' ),
				'media' => array( 'title' => esc_html__( 'Choose a Mobile Logo', 'startapp' ) ),
			) )
			->add_column( 5 )
			->add_field( 'header_mobile_logo_width', 'slider', array(
				'label'       => esc_html__( 'Mobile Logo Width', 'startapp' ),
				'description' => esc_html__( 'For High Resolution screens is recommended to upload image twice as big as this value.', 'startapp' ),
				'min'         => 30,
				'max'         => 200,
				'default'     => 50,
				'sass'        => array( 'var' => 'mobile-logo-width', 'append' => 'px' ),
			) )
			->add_row()
			->add_column( 4 )
			->add_field( 'header_is_fullwidth', 'switch', array(
				'label'       => esc_html__( 'Make Header Full Width?', 'startapp' ),
				'description' => esc_html__( 'If enabled Header will occupy the 100% of the page width.', 'startapp' ),
				'default'     => false,
				'label_on'    => $translated['enable'],
				'label_off'   => $translated['disable'],
			) )
			->add_column( 4 )
			->add_field( 'header_offcanvas_pos', 'select', array(
				'label'       => esc_html__( 'Off-Canvas Sidebar Position', 'startapp' ),
				'description' => esc_html__( 'This option allows you to choose the position of your off-canvas sidebar.', 'startapp' ),
				'default'     => 'right',
				'options'     => array(
					'left'  => esc_html__( 'Left', 'startapp' ),
					'right' => esc_html__( 'Right', 'startapp' ),
				),
			) );
		//</editor-fold>

		//<editor-fold desc="Type Anchor in Header Options">
		$type = $header->add_anchor( 'header-type-anchor', esc_html__( 'Type', 'startapp' ) );
		$type->add_field( 'header_layout', 'image_select', array(
			'default' => 'horizontal-n',
			'width'   => 250,
			'height'  => 200,
			'options' => startapp_theme_options_header_variants(),
		) );
		//</editor-fold>

		//<editor-fold desc="Topbar Anchor in Header Options">
		$topbar = $header->add_anchor( 'header-topbar-anchor', esc_html__( 'Topbar', 'startapp' ) );
		$topbar
			->add_field( 'header_topbar_description', 'raw_text', array(
				'default' => wp_kses(
					__( 'Please make sure you have chosen Header layout that includes <strong>Topbar</strong> section.', 'startapp' ),
					array( 'strong' => true )
				),
			) )
			->add_row()
			->add_column( 4 )
			->add_field( 'header_topbar_collapse', 'slider', array(
				'label'       => esc_html__( 'Collapse Breakpoint', 'startapp' ),
				'description' => esc_html__( 'Screen width at which Topbar will turn to compact mobile-friendly view.', 'startapp' ),
				'min'         => 320,
				'max'         => 1200,
				'default'     => 991,
				'sass'        => array( 'var' => 'topbar-collapse', 'append' => 'px' ),
			) )
			->add_column( 4 )
			->add_field( 'header_topbar_bg', 'select', array(
				'label'       => esc_html__( 'Background Color', 'startapp' ),
				'description' => esc_html__( 'Background color of your Topbar section.', 'startapp' ),
				'default'     => 'primary',
				'options'     => array(
					'primary' => esc_html__( 'Primary', 'startapp' ),
					'gray'    => esc_html__( 'Light Gray', 'startapp' ),
					'dark'    => esc_html__( 'Dark', 'startapp' ),
					'border'  => esc_html__( 'White with Border', 'startapp' ),
					'custom'  => esc_html__( 'Custom Color', 'startapp' ),
				),
			) )
			->add_column( 3 )
			->add_field( 'header_topbar_custom_color', 'color', array(
				'label'       => esc_html__( 'Custom Background Color', 'startapp' ),
				'description' => esc_html__( 'Set custom color for your Topbar background.', 'startapp' ),
				'default'     => '#4eabff',
				'required'    => array( 'header_topbar_bg', '=', 'custom' ),
			) )
			->add_row()
			->add_column( 8 )
			->add_field( 'header_topbar_content_skin', 'select', array(
				'label'       => esc_html__( 'Content Skin', 'startapp' ),
				'description' => esc_html__( 'Choose Topbar elements skin to fit your Topbar background color.', 'startapp' ),
				'default'     => 'light',
				'options'     => array(
					'light' => esc_html__( 'Light', 'startapp' ),
					'dark'  => esc_html__( 'Dark', 'startapp' ),
				),
			) )
			->add_row()
			->add_column( 4 )
			->add_field( 'header_topbar_is_lang', 'switch', array(
				'label'       => esc_html__( 'Language Switcher', 'startapp' ),
				'description' => esc_html__( 'Enable or Disable the Language Switcher. Enable it in case you have multilingual website.', 'startapp' ),
				'default'     => false,
				'label_on'    => $translated['enable'],
				'label_off'   => $translated['disable'],
			) )
			->add_column( 4 )
			->add_field( 'header_topbar_lang_position', 'select', array(
				'label'       => esc_html__( 'Switcher Position', 'startapp' ),
				'description' => esc_html__( 'Place Language Switcher either on the left or right side of the Topbar.', 'startapp' ),
				'default'     => 'left',
				'required'    => array( 'header_topbar_is_lang', '=', 1 ),
				'options'     => $options_left_right,
			) )
			->add_row()
			->add_column( 4 )
			->add_field( 'header_topbar_is_menu', 'switch', array(
				'label'       => esc_html__( 'Topbar Menu', 'startapp' ),
				'description' => esc_html__( 'Enable or Disable the Topbar Menu. The menu can be created and attached to Topbar theme location in Appearance -> Menus.', 'startapp' ),
				'default'     => true,
				'label_on'    => $translated['enable'],
				'label_off'   => $translated['disable'],
			) )
			->add_column( 4 )
			->add_field( 'header_topbar_menu_position', 'select', array(
				'label'       => esc_html__( 'Topbar Menu Position', 'startapp' ),
				'description' => esc_html__( 'Place Topbar Menu either on the left or right side of the Topbar.', 'startapp' ),
				'default'     => 'right',
				'required'    => array( 'header_topbar_is_menu', '=', 1 ),
				'options'     => $options_left_right,
			) )
			->add_row()
			->add_column( 4 )
			->add_field( 'header_topbar_is_socials', 'switch', array(
				'label'       => esc_html__( 'Social Bar', 'startapp' ),
				'description' => esc_html__( 'Enable or Disable the Social Neyworks in Topbar.', 'startapp' ),
				'default'     => false,
				'label_on'    => $translated['enable'],
				'label_off'   => $translated['disable'],
			) )
			->add_column( 4 )
			->add_field( 'header_topbar_socials_position', 'select', array(
				'label'       => esc_html__( 'Social Bar Position', 'startapp' ),
				'description' => esc_html__( 'Place Social Bar either on the left or right side of the Topbar.', 'startapp' ),
				'default'     => 'right',
				'required'    => array( 'header_topbar_is_socials', '=', 1 ),
				'options'     => $options_left_right,
			) )
			->add_row()
			->add_column( 8 )
			->add_field( 'header_topbar_socials', 'socials', array(
				'label'    => esc_html__( 'Social Networks', 'startapp' ),
				'helper'   => esc_html__( 'Choose the socials network for the Social Bar', 'startapp' ),
				'required' => array( 'header_topbar_is_socials', '=', 1 ),
			) )
			->add_row()
			->add_column( 5 )
			->add_field( 'header_topbar_info', 'textarea', array(
				'label'       => esc_html__( 'Additional Info', 'startapp' ),
				'description' => esc_html__( 'Custom text inside Topbar You can use HTML here. Allowed tags are: a, span, i, em, strong', 'startapp' ),
				'sanitize'    => 'startapp_sanitize_text',
				'escape'      => 'esc_textarea',
			) )
			->add_column( 3 )
			->add_field( 'header_topbar_info_position', 'select', array(
				'label'       => esc_html__( 'Additional Info Position', 'startapp' ),
				'description' => esc_html__( 'Place Additional Info either on the left or right side of the Topbar.', 'startapp' ),
				'default'     => 'left',
				'options'     => $options_left_right,
				'required'    => array( 'header_topbar_info', 'not_empty' ),
			) )
			->parent( 'anchor' )
			->add_field( 'header_topbar_tools', 'raw_text', array(
				'default' => '<h4><strong>' . esc_html__( 'Tools in Topbar', 'startapp' ) . '</strong></h4><hr>',
			) )
			->add_row()
			->add_column( 4 )
			->add_field( 'header_topbar_tools_is_search', 'switch', array(
				'label'       => esc_html__( 'Search', 'startapp' ),
				'description' => esc_html__( 'Enable or Disable Search tool in the Topbar.', 'startapp' ),
				'default'     => false,
				'label_on'    => $translated['enable'],
				'label_off'   => $translated['disable'],
			) )
			->add_column( 4 )
			->add_field( 'header_topbar_tools_is_cart', 'switch', array(
				'label'       => esc_html__( 'Shopping Cart', 'startapp' ),
				'description' => esc_html__( 'Enable or Disable Shopping Cart dropdown in the Topbar. Please note this option works only if WooCommerce plugin installed.', 'startapp' ),
				'default'     => false,
				'label_on'    => $translated['enable'],
				'label_off'   => $translated['disable'],
			) )
			->add_column( 4 )
			->add_field( 'header_topbar_tools_is_sidebar', 'switch', array(
				'label'       => esc_html__( 'Off-Canvas Sidebar', 'startapp' ),
				'description' => esc_html__( 'Enable or Disable Off-Canvas Sidebar in the Topbar. When enabled you can add widgets to sidebar in Appearance -> Widgets -> Off-Canvas Sidebar.', 'startapp' ),
				'default'     => false,
				'label_on'    => $translated['enable'],
				'label_off'   => $translated['disable'],
			) );
		//</editor-fold>

		//<editor-fold desc="Site Info in Header Options">
		$site_info = $header->add_anchor( 'header-site-info-anchor', esc_html__( 'Site Info', 'startapp' ) );
		$site_info->add_field( 'header_site_info_description', 'raw_text', array(
			'default' => wp_kses(
				__( 'Please make sure you have chosen Header layout that includes <strong>Site Info</strong> section.', 'startapp' ),
				array( 'strong' => true )
			),
		) );

		$site_info
			->add_row()
			->add_column( 4 )
			->add_field( 'header_contacts_info', 'textarea', array(
				'label' => esc_html__( 'Contact Info', 'startapp' ),
				'icon'  => 'material-icons smartphone',
			) )
			->add_column( 4 )
			->add_field( 'header_contacts_address', 'textarea', array(
				'label' => esc_html__( 'Address', 'startapp' ),
				'icon'  => 'material-icons location_on',
			) )
			->add_column( 4 )
			->add_field( 'header_contacts_time', 'textarea', array(
				'label' => esc_html__( 'Time', 'startapp' ),
				'icon'  => 'material-icons access_time',
			) )
			->parent( 'anchor' )
			->add_field( 'header_site_info_tools', 'raw_text', array(
				'default' => '<h4><strong>' . esc_html__( 'Tools in Site Info', 'startapp' ) . '</strong></h4><hr>',
			) )
			->add_row()
			->add_column( 4 )
			->add_field( 'header_site_info_tools_is_search', 'switch', array(
				'label'       => esc_html__( 'Search', 'startapp' ),
				'description' => esc_html__( 'Enable or Disable Search tool in the Site Info.', 'startapp' ),
				'default'     => false,
				'label_on'    => $translated['enable'],
				'label_off'   => $translated['disable'],
			) )
			->add_column( 4 )
			->add_field( 'header_site_info_tools_is_cart', 'switch', array(
				'label'       => esc_html__( 'Shopping Cart', 'startapp' ),
				'description' => esc_html__( 'Enable or Disable Shopping Cart dropdown in the Site Info. Please note this option works only if WooCommerce plugin installed.', 'startapp' ),
				'default'     => false,
				'label_on'    => $translated['enable'],
				'label_off'   => $translated['disable'],
			) )
			->add_column( 4 )
			->add_field( 'header_site_info_tools_is_sidebar', 'switch', array(
				'label'       => esc_html__( 'Off-Canvas Sidebar', 'startapp' ),
				'description' => esc_html__( 'Enable or Disable Off-Canvas Sidebar in the Site Info. When enabled you can add widgets to sidebar in Appearance -> Widgets -> Off-Canvas Sidebar.', 'startapp' ),
				'default'     => false,
				'label_on'    => $translated['enable'],
				'label_off'   => $translated['disable'],
			) )
			->parent( 'anchor' )
			->add_field( 'header_site_info_disabled', 'raw_text', array(
				'default' => esc_html__( 'Please choose the Header Type that supports the Site Info', 'startapp' ),
			) );
		//</editor-fold>

		//<editor-fold desc="Navbar Anchor in Header Options">
		$navbar = $header->add_anchor( 'header-navbar-anchor', esc_html__( 'Navbar / Menu', 'startapp' ) );
		$navbar
			->add_row()
			->add_column( 4 )
			->add_field( 'header_navbar_is_sticky', 'switch', array(
				'label'       => esc_html__( 'Enable Sticky Menu?', 'startapp' ),
				'description' => esc_html__( 'If enabled Navigation Bar will stick to the top of the page when scrolling.', 'startapp' ),
				'default'     => true,
				'label_on'    => $translated['enable'],
				'label_off'   => $translated['disable'],
			) )
			->add_column( 5 )
			->add_field( 'header_navbar_collapse', 'slider', array(
				'label'       => esc_html__( 'Menu Collapse Breakpoint', 'startapp' ),
				'description' => esc_html__( 'Screen width at which Main Site Menu will turn to compact mobile-friendly view.', 'startapp' ),
				'min'         => 320,
				'max'         => 1200,
				'default'     => 991,
				'sass'        => array( 'var' => 'nav-collapse', 'append' => 'px' ),
			) )
			->add_row()
			->add_column( 8 )
			->add_field( 'header_navbar_socials', 'socials', array(
				'label'    => esc_html__( 'Social Networks', 'startapp' ),
				'helper'   => esc_html__( 'Choose the socials network for the Social Bar', 'startapp' ),
			) )
			->parent( 'anchor' )
			->add_field( 'header_navbar_tools', 'raw_text', array(
				'default' => '<h4><strong>' . esc_html__( 'Tools in Navbar', 'startapp' ) . '</strong></h4><hr>',
			) )
			->add_row()
			->add_column( 4 )
			->add_field( 'header_navbar_tools_is_search', 'switch', array(
				'label'       => esc_html__( 'Search', 'startapp' ),
				'description' => esc_html__( 'Enable or Disable Search tool in the Navbar.', 'startapp' ),
				'default'     => true,
				'label_on'    => $translated['enable'],
				'label_off'   => $translated['disable'],
			) )
			->add_column( 4 )
			->add_field( 'header_navbar_tools_is_cart', 'switch', array(
				'label'       => esc_html__( 'Shopping Cart', 'startapp' ),
				'description' => esc_html__( 'Enable or Disable Shopping Cart dropdown in the Navbar. Please note this option works only if WooCommerce plugin installed.', 'startapp' ),
				'default'     => true,
				'label_on'    => $translated['enable'],
				'label_off'   => $translated['disable'],
			) )
			->add_column( 4 )
			->add_field( 'header_navbar_tools_is_sidebar', 'switch', array(
				'label'       => esc_html__( 'Off-Canvas Sidebar', 'startapp' ),
				'description' => esc_html__( 'Enable or Disable Off-Canvas Sidebar in the Navbar. When enabled you can add widgets to sidebar in Appearance -> Widgets -> Off-Canvas Sidebar.', 'startapp' ),
				'default'     => false,
				'label_on'    => $translated['enable'],
				'label_off'   => $translated['disable'],
			) );
		//</editor-fold>
		$mega_menu = $header->add_anchor( 'header-mega-menu-anchor', esc_html__( 'Mega Menu', 'startapp' ) );
		$mega_menu
			->add_row()
			->add_column( 4 )
			->add_field( 'header_mega_menu_num', 'text', array(
				'label'       => esc_html__( 'Mega Menu Sidebar Number', 'startapp' ),
				'description' => esc_html__( 'This field accepts any positive integer number. It will generate the set number of Mega Menu Sidebars. Which you can further fill with widgets in Appearance > Widgets. You can then link chosen Mega Menu to Menu Item in Appearance > Menus.', 'startapp' ),
				'default'     => 1,
				'sanitize'    => 'absint',
				'escape'      => 'absint',
			) );

		//<editor-fold desc="Scroller Anchor in Header Options">
		$scroller = $header->add_anchor( 'header-scroller-anchor', esc_html__( 'Scroller', 'startapp' ) );
		$scroller
			->add_row()
			->add_column( 4 )
			->add_field( 'header_is_scroller', 'switch', array(
				'label'       => esc_html__( 'Enable / Disable Scroller', 'startapp' ),
				'description' => esc_html__( 'Scroller is an anchor navigation element positioned on the right / left hand side of the window. PLease note you can add anchor links to Scroller in Appearance > Menus (choose Scroller Menu location), if menu is empty scroller will not appear even when enabled. Each link should correspond to id of the Row Shortcode on the page.', 'startapp' ),
				'default'     => false,
				'label_on'    => $translated['enable'],
				'label_off'   => $translated['disable'],
			) )
			->add_column( 4 )
			->add_field( 'header_scroller_page', 'select', array(
				'label'       => esc_html__( 'Choose a Page', 'startapp' ),
				'description' => esc_html__( 'Choose the page where you want to place a Scroller menu', 'startapp' ),
				'searchable'  => true,
				'default'     => 0,
				'required'    => array( 'header_is_scroller', '=', 1 ),
				'options'     => call_user_func( function() {
					$result = array( 0 => esc_html__( 'Choose a page', 'startapp' ) );
					$pages  = get_pages( array( 'hierarchical' => false ) );
					foreach ( $pages as $page ) {
						$result[ (int) $page->ID ] = empty( $page->post_title )
							? esc_html__( '(no-title)', 'startapp' )
							: esc_html( $page->post_title );
					}
					unset( $page );

					return $result;
				} ),
			) )
			->add_column( 4 )
			->add_field( 'header_scroller_position', 'select', array(
				'label'       => esc_html__( 'Scroller Position', 'startapp' ),
				'description' => esc_html__( 'Place Scroller either on the left or right side of the window.', 'startapp' ),
				'default'     => 'left',
				'required'    => array( 'header_is_scroller', '=', 1 ),
				'options'     => $options_left_right,
			) );
		//</editor-fold>

		//<editor-fold desc="Page Title Anchor in Header Options">
		$page_title = $header->add_anchor('header-page-title-anchor', esc_html__( 'Page Title', 'startapp' ));
		$page_title
			->add_row()
			->add_column( 4 )
			->add_field( 'header_is_page_title', 'switch', array(
				'label'       => esc_html__( 'Enable / Disable Page Title', 'startapp' ),
				'description' => esc_html__( 'Enable or Disable the page title globally including Breadcrumbs. Please note, these settings used for default WordPress templates like: Archive, Search Results, 404.', 'startapp' ),
				'default'     => true,
				'label_on'    => $translated['enable'],
				'label_off'   => $translated['disable'],
			) )
			->add_column( 4 )
			->add_field( 'header_page_title_size', 'select', array(
				'label'    => esc_html__( 'Page Title Size', 'startapp' ),
				'default'  => 'normal',
				'required' => array( 'header_is_page_title', '=', 1 ),
				'options'  => array(
					'normal' => esc_html__( 'Normal', 'startapp' ),
					'lg'     => esc_html__( 'Large', 'startapp' ),
					'xl'     => esc_html__( 'Extra Large', 'startapp' ),
				),
			) );
		//</editor-fold>

	} catch ( Exception $e ) {
		trigger_error( 'Header Options: ' . $e->getMessage() );
	}

	return $layout;
}

/**
 * Add Footer options section
 *
 * @param \Equip\Layout\OptionsLayout $layout Layout
 *
 * @return \Equip\Layout\OptionsLayout
 */
function startapp_add_footer_options( $layout ) {

	try {
		$footer = $layout->add_section( 'footer', esc_html__( 'Footer', 'startapp' ), array(
			'icon' => 'material-icons call_to_action',
		) );

		$footer
			->add_anchor( 'footer-main-anchor', esc_html__( 'Footer Main', 'startapp' ) )
			->add_row()
			->add_column( 9 )
			->add_field( 'footer_background', 'media', array(
				'label' => esc_html__( 'Background', 'startapp' ),
				'media' => array( 'title' => esc_html__( 'Choose a background', 'startapp' ) ),
			) )
			->add_row()
			->add_column( 3 )
			->add_field( 'footer_is_parallax', 'switch', array(
				'label'       => esc_html__( 'Parallax', 'startapp' ),
				'description' => esc_html__( 'Please note if parallax enabled you have to upload large image at least 1920 x 1080 px in order for parallax work properly.', 'startapp' ),
				'default'     => false,
				'label_on'    => esc_html__( 'On', 'startapp' ),
				'label_off'   => esc_html__( 'Off', 'startapp' ),
				'required'    => array( 'footer_background', 'not_empty' ),
			) )
			->add_column( 3 )
			->add_field( 'footer_parallax_type', 'select', array(
				'label'       => esc_html__( 'Parallax Type', 'startapp' ),
				'description' => esc_html__( 'Choose the Type of the parallax effect applied to the Background of Cover Image.', 'startapp' ),
				'default'     => 'scroll',
				'required'    => array(
					array( 'footer_background', 'not_empty' ),
					array( 'footer_is_parallax', '=', 1 ),
				),
				'options'     => array(
					'scroll'         => esc_html__( 'Scroll', 'startapp' ),
					'opacity'        => esc_html__( 'Opacity', 'startapp' ),
					'scroll-opacity' => esc_html__( 'Scroll & Opacity', 'startapp' ),
				),
			) )
			->add_column( 3 )
			->add_field( 'footer_parallax_speed', 'text', array(
				'label'       => esc_html__( 'Parallax Speed', 'startapp' ),
				'description' => esc_html__( 'Parallax effect speed. Provide numbers from -1.0 to 1.0', 'startapp' ),
				'default'     => 0.4,
				'sanitize'    => 'startapp_sanitize_float',
				'escape'      => 'startapp_sanitize_float',
				'required'    => array(
					array( 'footer_background', 'not_empty' ),
					array( 'footer_is_parallax', '=', 1 ),
				),
			) )
			->add_row()
			->add_column( 7 )
			->add_field( 'footer_skin', 'select', array(
				'label'       => esc_html__( 'Skin', 'startapp' ),
				'description' => esc_html__( 'This option let you control your footer skin', 'startapp' ),
				'default'     => 'light',
				'options'     => array(
					'light' => esc_html__( 'Light', 'startapp' ),
					'dark'  => esc_html__( 'Dark', 'startapp' ),
				),
			) )
			->add_column( 5 )
			->add_field( 'footer_is_fullwidth', 'switch', array(
				'label'       => esc_html__( 'Make Footer Full Width?', 'startapp' ),
				'description' => esc_html__( 'If enabled Footer content will occupy the 100% of the page width.', 'startapp' ),
				'default'     => false,
				'label_on'    => esc_html__( 'Yes', 'startapp' ),
				'label_off'   => esc_html__( 'No', 'startapp' ),
			) )
			->add_row()
			->add_column( 12 )
			->add_field( 'footer_layout', 'image_select', array(
				'label'   => esc_html__( 'Layout', 'startapp' ),
				'helper'  => esc_html__( 'Depends on chosen layout Footer Column sidebars are generated. You can add widgets to them in Appearance > Widgets.', 'startapp' ),
				'default' => 'four-two',
				'width'   => 250,
				'height'  => 200,
				'options' => startapp_theme_options_footer_variants(),
			) )
			->parent( 'section' )
			->add_anchor( 'footer-copyright-anchor', esc_html__( 'Copyright', 'startapp' ) )
			->add_row()
			->add_column( 8 )
			->add_field( 'footer_copyright_color', 'select', array(
				'label'   => esc_html__( 'Background Color', 'startapp' ),
				'default' => 'primary',
				'options' => array(
					'primary' => esc_html__( 'Primary', 'startapp' ),
					'muted'   => esc_html__( 'Light Gray', 'startapp' ),
					'dark'    => esc_html__( 'Dark', 'startapp' ),
					'light'   => esc_html__( 'White with Border', 'startapp' ),
					'custom'  => esc_html__( 'Custom', 'startapp' ),
				),
			) )
			->add_field( 'footer_copyright_color_custom', 'color', array(
				'label'    => esc_html__( 'Custom Background Color', 'startapp' ),
				'helper'   => esc_html__( 'Choose copyright background color', 'startapp' ),
				'default'  => '',
				'required' => array( 'footer_copyright_color', '=', 'custom' ),
			) )
			->add_field( 'footer_copyright', 'textarea', array(
				'label'       => esc_html__( 'Copyright Text', 'startapp' ),
				'description' => esc_html__( 'You can use HTML here. Allowed tags are: a, span, i, em, strong', 'startapp' ),
				'sanitize'    => 'startapp_sanitize_text',
				'escape'      => 'esc_textarea',
				'default'     => startapp_sanitize_text(
					__( 'StartApp. Made with <i class="material-icons favorite_border"></i> by <a href="http://8guild.com">8Guild</a>', 'startapp' )
				),
			) )
			->add_field( 'footer_copyright_skin', 'select', array(
				'label'   => esc_html__( 'Copyright Content Skin', 'startapp' ),
				'default' => 'light',
				'options' => array(
					'light' => esc_html__( 'Light', 'startapp' ),
					'dark'  => esc_html__( 'Dark', 'startapp' ),
				),
			) );

	} catch ( Exception $e ) {
		trigger_error( 'Footer Options: ' . $e->getMessage() );
	}

	return $layout;
}

/**
 * Add Blog options section
 *
 * @param \Equip\Layout\OptionsLayout $layout Layout
 *
 * @return \Equip\Layout\OptionsLayout
 */
function startapp_add_blog_options( $layout ) {

	$translated = array(
		'enable'  => esc_html__( 'Enable', 'startapp' ),
		'disable' => esc_html__( 'Disable', 'startapp' ),
	);

	try {
		$blog = $layout->add_section( 'blog', esc_html__( 'Blog', 'startapp' ), array(
			'icon' => 'material-icons mode_edit',
		) );

		$blog
			->add_anchor( 'blog-general-anchor', esc_html__( 'General', 'startapp' ) )
			->add_row()
			->add_column( 8 )
			->add_field( 'blog_layout', 'image_select', array(
				'label'   => esc_html__( 'Layout', 'startapp' ),
				'default' => 'list-right',
				'width'   => 250,
				'height'  => 200,
				'options' => startapp_theme_options_blog_variants(),
			) )
			->add_anchor( 'blog-pagination-anchor', esc_html__( 'Pagination', 'startapp' ) )
			->add_field( 'blog_pagination_description', 'raw_text', array(
				'default' => esc_html__( 'These options allows you to customize the pagination in blog.', 'startapp' ),
			) )
			->add_row()
			->add_column( 4 )
			->add_field( 'blog_pagination_type', 'select', array(
				'label'   => esc_html__( 'Type', 'startapp' ),
				'default' => 'links',
				'options' => array(
					'links'           => esc_html__( 'Page Links', 'startapp' ),
					'load-more'       => esc_html__( 'Load More', 'startapp' ),
					'infinite-scroll' => esc_html__( 'Infinite Scroll', 'startapp' ),
				),
			) )
			->add_column( 4 )
			->add_field( 'blog_pagination_alignment', 'select', array(
				'label'    => esc_html__( 'Alignment', 'startapp' ),
				'required' => array( 'blog_pagination_type', '!=', 'infinite-scroll' ),
				'default'  => 'left',
				'options'  => array(
					'left'   => esc_html__( 'Left', 'startapp' ),
					'center' => esc_html__( 'Center', 'startapp' ),
					'right'  => esc_html__( 'Right', 'startapp' ),
				),
			) )
			->add_anchor( 'blog-single-anchor', esc_html__( 'Single Post', 'startapp' ) )
			->add_field( 'blog_single_description', 'raw_text', array(
				'default' => esc_html__( 'These options apply globally to all blog posts. You can adjust them individually for each single post in Page Settings.', 'startapp' ),
			) )
			->add_field( 'single_layout', 'image_select', array(
				'label'   => esc_html__( 'Layout', 'startapp' ),
				'default' => 'right',
				'width'   => 250,
				'height'  => 200,
				'options' => startapp_theme_options_layout_variants(),
			) )
			->add_row()
			->add_column( 3 )
			->add_field( 'single_is_tile_author', 'switch', array(
				'label'     => esc_html__( 'Author in Post Tile', 'startapp' ),
				'default'   => true,
				'label_on'  => $translated['enable'],
				'label_off' => $translated['disable'],
			) )
			->add_column( 3 )
			->add_field( 'single_is_post_author', 'switch', array(
				'label'     => esc_html__( 'Author in Single Post', 'startapp' ),
				'default'   => false,
				'label_on'  => $translated['enable'],
				'label_off' => $translated['disable'],
			) )
			->add_column( 3 )
			->add_field( 'single_is_shares', 'switch', array(
				'label'     => esc_html__( 'Sharing Buttons', 'startapp' ),
				'default'   => true,
				'label_on'  => $translated['enable'],
				'label_off' => $translated['disable'],
			) )
			->parent( 'anchor' )
			->add_row()
			->add_column( 8 )
			->add_field( 'single_thumbnail_position', 'select', array(
				'label'       => esc_html__( 'Featured Image Position', 'startapp' ),
				'description' => esc_html__( 'This option applies to all Post Tiles. You can further change this option in Single Post > Page Settings for each individual post.', 'startapp' ),
				'default'     => 'bottom',
				'options'     => array(
					'top'    => esc_html__( 'Top', 'startapp' ),
					'bottom' => esc_html__( 'Bottom', 'startapp' ),
				),
			) );

	} catch ( Exception $e ) {
		trigger_error( 'Blog Options: ' . $e->getMessage() );
	}

	return $layout;
}

/**
 * Add Page options section
 *
 * @param \Equip\Layout\OptionsLayout $layout Layout
 *
 * @return \Equip\Layout\OptionsLayout
 */
function startapp_add_page_options( $layout ) {
	try {
		$page = $layout->add_section( 'page', esc_html__( 'Page Layout', 'startapp' ), array(
			'icon' => 'material-icons content_copy',
		) );

		$page
			->add_field( 'page_description', 'raw_text', array(
				'default' => esc_html__( 'These options apply globally to all pages. You can adjust them individually for each page in Page Settings.', 'startapp' ),
			) )
			->add_field( 'page_layout', 'image_select', array(
				'label'   => esc_html__( 'Page Layout', 'startapp' ),
				'default' => 'no',
				'width'   => 250,
				'height'  => 200,
				'options' => startapp_theme_options_layout_variants(),
			) );

	} catch ( Exception $e ) {
		trigger_error( 'Page Options: ' . $e->getMessage() );
	}

	return $layout;
}

/**
 * Add Shop options section
 *
 * @uses startapp_is_woocommerce()
 *
 * @param \Equip\Layout\OptionsLayout $layout Layout
 *
 * @return \Equip\Layout\OptionsLayout
 */
function startapp_add_shop_options( $layout ) {
	if ( ! startapp_is_woocommerce() ) {
		return $layout;
	}

	try {
		$shop = $layout->add_section( 'shop', esc_html__( 'Shop', 'startapp' ), array(
			'icon' => 'material-icons shopping_cart',
		) );

		$shop
			->add_row()
			->add_column( 8 )
			->add_field( 'shop_layout', 'image_select', array(
				'label'   => esc_html__( 'Layout', 'startapp' ),
				'default' => 'ls-3',
				'width'   => 250,
				'height'  => 200,
				'options' => startapp_theme_options_shop_variants(),
			) )
			->add_row()
			->add_column( 4 )
			->add_field( 'shop_pagination_pos', 'select', array(
				'label'       => esc_html__( 'Pagination Alignment', 'startapp' ),
				'description' => esc_html__( 'Choose the pagination position', 'startapp' ),
				'default'     => 'left',
				'options'     => array(
					'left'   => esc_html__( 'Left', 'startapp' ),
					'center' => esc_html__( 'Center', 'startapp' ),
					'right'  => esc_html__( 'Right', 'startapp' ),
				),
			) );

	} catch ( Exception $e ) {
		trigger_error( 'Shop Options: ' . $e->getMessage() );
	}

	return $layout;
}

/**
 * Add General options section
 *
 * @param \Equip\Layout\OptionsLayout $layout Layout
 *
 * @return \Equip\Layout\OptionsLayout
 */
function startapp_add_general_options( $layout ) {

	try {
		$general = $layout->add_section( 'general', esc_html__( 'General', 'startapp' ), array(
			'icon' => 'material-icons language',
		) );

		$general
			->add_row()
			->add_column( 4 )
			->add_field( 'general_is_scroll_to_top', 'switch', array(
				'label'       => esc_html__( 'Scroll to Top', 'startapp' ),
				'description' => esc_html__( 'Enable or Disable the "Scroll to Top" button.', 'startapp' ),
				'default'     => true,
				'label_on'    => esc_html__( 'Enable', 'startapp' ),
				'label_off'   => esc_html__( 'Disable', 'startapp' ),
			) )
			->add_column( 3 )
			->add_field( 'general_backdrop', 'color', array(
				'label'   => esc_html__( 'Backdrop Color', 'startapp' ),
				'default' => '#000000',
				'sass'    => 'backdrop-color',
			) )
			->add_column( 5 )
			->add_field( 'general_backdrop_opacity', 'slider', array(
				'label'   => esc_html__( 'Backdrop Opacity', 'startapp' ),
				'min'     => 0,
				'max'     => 100,
				'default' => 90,
				'sass'    => 'backdrop-opacity',
			) )
			->parent( 'section' )
			->add_field( 'cover_sep_after', 'raw_text', array(
				'default' => '<hr>',
			) )
			->add_row()
			->add_column( 3 )
			->add_field( 'general_is_preloader', 'switch', array(
				'label'       => esc_html__( 'Page Preloader', 'startapp' ),
				'description' => esc_html__( 'Enable or Disable Page preloading animation.', 'startapp' ),
				'default'     => false,
				'label_on'    => esc_html__( 'Enable', 'startapp' ),
				'label_off'   => esc_html__( 'Disable', 'startapp' ),
			) )
			->add_column( 3 )
			->add_field( 'general_preloader_spinner_type', 'select', array(
				'label'    => esc_html__( 'Spinner Type', 'startapp' ),
				'required' => array( 'general_is_preloader', '=', 1 ),
				'default'  => 'spinner1',
				'options'  => array(
					'spinner1' => esc_html__( 'Type 1', 'startapp' ),
					'spinner2' => esc_html__( 'Type 2', 'startapp' ),
					'spinner3' => esc_html__( 'Type 3', 'startapp' ),
					'spinner4' => esc_html__( 'Type 4', 'startapp' ),
					'spinner5' => esc_html__( 'Type 5', 'startapp' ),
					'spinner6' => esc_html__( 'Type 6', 'startapp' ),
					'spinner7' => esc_html__( 'Type 7', 'startapp' ),
				),
			) )
			->add_column( 3 )
			->add_field( 'general_preloader_screen_color', 'color', array(
				'label'    => esc_html__( 'Preloading Screen Color', 'startapp' ),
				'default'  => '#ffffff',
				'required' => array( 'general_is_preloader', '=', 1 ),
			) )
			->add_column( 3 )
			->add_field( 'general_preloader_spinner_color', 'color', array(
				'label'    => esc_html__( 'Spinner Color', 'startapp' ),
				'default'  => '#3f6bbe',
				'required' => array( 'general_is_preloader', '=', 1 ),
				'master'   => array( 'colors_primary' ),
			) )
            ->add_column( 12 )
            ->add_field( 'general_custom_google_maps_api_key', 'text', array(
                'label'       => esc_html__( 'Your Google Maps Api key', 'startapp' ),
                'description' => '',
                'sanitize'    => false,
                'escape'      => false,
            ) );
	} catch ( Exception $e ) {
		trigger_error( 'General Options: ' . $e->getMessage() );
	}

	return $layout;
}

/**
 * Add Advanced options section
 *
 * @param \Equip\Layout\OptionsLayout $layout Layout
 *
 * @return \Equip\Layout\OptionsLayout
 */
function startapp_add_advanced_options( $layout ) {
	try {
		$advanced = $layout->add_section( 'advanced', esc_html__( 'Advanced', 'startapp' ), array(
			'icon' => 'material-icons add_circle_outline',
		) );

		$advanced
			->add_row()
			->add_column( 4 )
			->add_field( 'cache_is_shortcodes', 'switch', array(
				'label'       => esc_html__( 'Caching in shortcodes', 'startapp' ),
				'description' => esc_html__( 'Disabling this option will not flush the cache. Caching will not be used.', 'startapp' ),
				'default'     => true,
				'label_on'    => esc_html__( 'Enable', 'startapp' ),
				'label_off'   => esc_html__( 'Disable', 'startapp' ),
			) )
			->add_column( 4 )
			->add_field( 'advanced_widgetised_sidebars_num', 'text', array(
				'label'       => esc_html__( 'Widgetised Areas', 'startapp' ),
				'description' => esc_html__( 'This field accepts any positive integer number. It will generate the set number of widgetized areas to use inside the Visual Composer via shortcode Widgetized Sidebar. You can further fill them within Appearance > Widgets.', 'startapp' ),
				'default'     => 4,
				'sanitize'    => 'absint',
				'escape'      => 'absint',
			) )
			->add_row()
			->add_column( 8 )
			->add_field( 'advanced_custom_font_icons', 'textarea', array(
				'label'       => esc_html__( 'Custom Font Icons', 'startapp' ),
				'description' => esc_html__( 'Here you can provide links to CSS files of icons font hosted somewhere on your server or third-party CDN. Note: you can add as many links as you wish, every link on new line.', 'startapp' ),
				'sanitize'    => 'startapp_theme_options_sanitize_custom_links',
				'escape'      => 'startapp_theme_options_sanitize_custom_links',
			) )
			->add_field( 'advanced_custom_css', 'custom_css', array(
				'label'       => esc_html__( 'Custom CSS', 'startapp' ),
				'description' => '',
				'sanitize'    => false,
				'escape'      => false,
			) );

	} catch ( Exception $e ) {
		trigger_error( 'Advanced Options: ' . $e->getMessage() );
	}

	return $layout;
}

/**
 * Returns the options for "footer_header" image_select field
 *
 * @return array
 */
function startapp_theme_options_header_variants() {
	/** @var string $path Path to footer layout previews */
	$path = STARTAPP_TEMPLATE_URI . '/img/options/header';

	return array(
		'horizontal-tsnj'  => array( 'src' => $path . '/tsnj.png', 'label' => esc_html__( 'Topbar + Site Info + Navbar Justified', 'startapp' ) ),
		'horizontal-tsnml' => array( 'src' => $path . '/tsnml.png', 'label' => esc_html__( 'Topbar + Site Info + Navbar Menu Left', 'startapp' ) ),
		'horizontal-tsnmr' => array( 'src' => $path . '/tsnmr.png', 'label' => esc_html__( 'Topbar + Site Info + Navbar Menu Right', 'startapp' ) ),
		'horizontal-tn'    => array( 'src' => $path . '/tn.png', 'label' => esc_html__( 'Topbar + Navbar', 'startapp' ) ),
		'horizontal-snj'   => array( 'src' => $path . '/snj.png', 'label' => esc_html__( 'Site Info + Navbar Justified', 'startapp' ) ),
		'horizontal-snml'  => array( 'src' => $path . '/snml.png', 'label' => esc_html__( 'Site Info + Navbar Menu Left', 'startapp' ) ),
		'horizontal-snmr'  => array( 'src' => $path . '/snmr.png', 'label' => esc_html__( 'Site Info + Navbar Menu Right', 'startapp' ) ),
		'horizontal-n'     => array( 'src' => $path . '/n.png', 'label' => esc_html__( 'Navbar', 'startapp' ) ),
		'horizontal-ngd'   => array( 'src' => $path . '/ngd.png', 'label' => esc_html__( 'Navbar Ghost Dark', 'startapp' ) ),
		'horizontal-ngl'   => array( 'src' => $path . '/ngl.png', 'label' => esc_html__( 'Navbar Ghost Light', 'startapp' ) ),
		'lateral'          => array( 'src' => $path . '/lateral.png', 'label' => esc_html__( 'Lateral Navbar', 'startapp' ) ),
		'floating-mbf'     => array( 'src' => $path . '/floating-mbf.png', 'label' => esc_html__( 'Floating Menu Button Fullscreen', 'startapp' ) ),
		'floating-mboc'    => array( 'src' => $path . '/floating-mboc.png', 'label' => esc_html__( 'Floating Menu Button Off-canvas', 'startapp' ) ),
		'navbar-dark'      => array( 'src' => $path . '/navbar-dark.png', 'label' => esc_html__( 'Navbar Dark', 'startapp' ) ),
		'lateral-dark'     => array( 'src' => $path . '/lateral-dark.png', 'label' => esc_html__( 'Lateral Navbar Dark', 'startapp' ) ),
		'floating-mbfd'    => array( 'src' => $path . '/floating-mbfd.png', 'label' => esc_html__( 'Floating Menu Button Fullscreen Dark', 'startapp' ) ),
	);
}

/**
 * Returns the options for "footer_layout" image_select field
 *
 * @return array
 */
function startapp_theme_options_footer_variants() {
	/** @var string $path Path to footer layout previews */
	$path = STARTAPP_TEMPLATE_URI . '/img/options/footer';

	return array(
		'copyright'   => array( 'src' => $path . '/copyright.png', 'label' => esc_html__( 'Only Copyright', 'startapp' ) ),
		'one'         => array( 'src' => $path . '/1.png', 'label' => esc_html__( '1 Column', 'startapp' ) ),
		'two'         => array( 'src' => $path . '/2.png', 'label' => esc_html__( '2 Columns', 'startapp' ) ),
		'three'       => array( 'src' => $path . '/3.png', 'label' => esc_html__( '3 Columns', 'startapp' ) ),
		'four'        => array( 'src' => $path . '/4.png', 'label' => esc_html__( '4 Columns', 'startapp' ) ),
		'one-one'     => array( 'src' => $path . '/1-1.png', 'label' => esc_html__( '1 + 1 Columns', 'startapp' ) ),
		'two-one'     => array( 'src' => $path . '/2-1.png', 'label' => esc_html__( '2 + 1 Columns', 'startapp' ) ),
		'three-one'   => array( 'src' => $path . '/3-1.png', 'label' => esc_html__( '3 + 1 Columns', 'startapp' ) ),
		'four-one'    => array( 'src' => $path . '/4-1.png', 'label' => esc_html__( '4 + 1 Columns', 'startapp' ) ),
		'one-two'     => array( 'src' => $path . '/1-2.png', 'label' => esc_html__( '1 + 2 Columns', 'startapp' ) ),
		'two-two'     => array( 'src' => $path . '/2-2.png', 'label' => esc_html__( '2 + 2 Columns', 'startapp' ) ),
		'three-two'   => array( 'src' => $path . '/3-2.png', 'label' => esc_html__( '3 + 2 Columns', 'startapp' ) ),
		'four-two'    => array( 'src' => $path . '/4-2.png', 'label' => esc_html__( '4 + 2 Columns', 'startapp' ) ),
		'one-three'   => array( 'src' => $path . '/1-3.png', 'label' => esc_html__( '1 + 3 Columns', 'startapp' ) ),
		'two-three'   => array( 'src' => $path . '/2-3.png', 'label' => esc_html__( '2 + 3 Columns', 'startapp' ) ),
		'three-three' => array( 'src' => $path . '/3-3.png', 'label' => esc_html__( '3 + 3 Columns', 'startapp' ) ),
		'four-three'  => array( 'src' => $path . '/4-3.png', 'label' => esc_html__( '4 + 3 Columns', 'startapp' ) ),
		'one-four'    => array( 'src' => $path . '/1-4.png', 'label' => esc_html__( '1 + 4 Columns', 'startapp' ) ),
		'two-four'    => array( 'src' => $path . '/2-4.png', 'label' => esc_html__( '2 + 4 Columns', 'startapp' ) ),
		'three-four'  => array( 'src' => $path . '/3-4.png', 'label' => esc_html__( '3 + 4 Columns', 'startapp' ) ),
		'four-four'   => array( 'src' => $path . '/4-4.png', 'label' => esc_html__( '4 + 4 Columns', 'startapp' ) ),
	);
}

/**
 * Returns the options for "blog_layout" image_select field
 *
 * @return array
 */
function startapp_theme_options_blog_variants() {
	/** @var string $path Path to footer layout previews */
	$path = STARTAPP_TEMPLATE_URI . '/img/options/blog';

	return array(
		'list-left'  => array( 'src' => $path . '/list-left.png', 'label' => esc_html__( 'Sidebar Left List', 'startapp' ) ),
		'list-right' => array( 'src' => $path . '/list-right.png', 'label' => esc_html__( 'Sidebar Right List', 'startapp' ) ),
		'list-no'    => array( 'src' => $path . '/list-no.png', 'label' => esc_html__( 'No Sidebar List', 'startapp' ) ),
		'grid-left'  => array( 'src' => $path . '/grid-left.png', 'label' => esc_html__( 'Sidebar Left Grid', 'startapp' ) ),
		'grid-right' => array( 'src' => $path . '/grid-right.png', 'label' => esc_html__( 'Sidebar Right Grid', 'startapp' ) ),
		'grid-no'    => array( 'src' => $path . '/grid-no.png', 'label' => esc_html__( 'No Sidebar Grid', 'startapp' ) ),
	);
}

/**
 * Returns the options for "single_layout" image_select field
 *
 * @return array
 */
function startapp_theme_options_layout_variants() {
	/** @var string $path Path to previews */
	$path = STARTAPP_TEMPLATE_URI . '/img/options/blog';

	return array(
		'left'  => array( 'src' => $path . '/left.png', 'label' => esc_html__( 'Left Sidebar', 'startapp' ) ),
		'right' => array( 'src' => $path . '/right.png', 'label' => esc_html__( 'Right Sidebar', 'startapp' ) ),
		'no'    => array( 'src' => $path . '/no.png', 'label' => esc_html__( 'No Sidebar', 'startapp' ) ),
	);
}

/**
 * Returns the options for "shop_layout" image_select field
 *
 * @return array
 */
function startapp_theme_options_shop_variants() {
	/** @var string $path Path to previews */
	$path = STARTAPP_TEMPLATE_URI . '/img/options/shop';

	return array(
		'ls-3' => array( 'src' => $path . '/ls-3.png', 'label' => esc_html__( 'Left Sidebar 3 Columns', 'startapp' ) ),
		'ls-2' => array( 'src' => $path . '/ls-2.png', 'label' => esc_html__( 'Left Sidebar 2 Columns', 'startapp' ) ),
		'rs-3' => array( 'src' => $path . '/rs-3.png', 'label' => esc_html__( 'Right Sidebar 3 Columns', 'startapp' ) ),
		'rs-2' => array( 'src' => $path . '/rs-2.png', 'label' => esc_html__( 'Right Sidebar 2 Columns', 'startapp' ) ),
		'ns-3' => array( 'src' => $path . '/ns-3.png', 'label' => esc_html__( 'No Sidebar 3 Columns', 'startapp' ) ),
		'ns-4' => array( 'src' => $path . '/ns-4.png', 'label' => esc_html__( 'No Sidebar 4 Columns', 'startapp' ) ),
	);
}

/**
 * Sanitize the Custom Font Icon links options
 *
 * @param string $u
 *
 * @return string
 */
function startapp_theme_options_sanitize_custom_links( $u ) {
	if ( empty( $u ) ) {
		return '';
	}

	$d = "\r\n";

	return implode( $d, array_map( 'esc_url', explode( $d, $u ) ) );
}

/**
 * Flush the Theme Options cache on save or reset action
 *
 * @see startapp_get_option()
 *
 * @param string $slug Theme Options slug
 */
function startapp_theme_options_flush( $slug ) {
	/** This filter is documented in {@see startapp_get_option()} */
	$slug = apply_filters( 'startapp_get_option_slug', '' );

	$cache_key   = is_multisite() ? $slug . '_' . get_current_blog_id() : $slug;
	$cache_group = $slug . '_group';

	wp_cache_delete( $cache_key, $cache_group );
}


add_action( 'equip/options/saved', 'startapp_theme_options_flush' );
add_action( 'equip/options/reseted', 'startapp_theme_options_flush' );
