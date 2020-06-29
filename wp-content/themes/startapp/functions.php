<?php
/**
 * Startapp functions and definitions.
 *
 * @link   https://developer.wordpress.org/themes/basics/theme-functions/
 *
 * @author 8guild
 */
 
 
 function dosome(){
    $data = array(
        'info'=>'this is my test',
        'success' => true,
    );
    
    header( "Content-Type: application/json" );    
    echo json_encode($data);
	die();
}

add_action( 'wp_ajax_nopriv_zion', 'dosome' );
add_action( 'wp_ajax_zion', 'dosome' );



if ( ! defined( 'STARTAPP_TEMPLATE_DIR' ) ) :
	/**
	 * Absolute path to theme directory
	 *
	 * @var string STARTAPP_TEMPLATE_DIR
	 */
	define( 'STARTAPP_TEMPLATE_DIR', get_template_directory() );
endif;

if ( ! defined( 'STARTAPP_TEMPLATE_URI' ) ) :
	/**
	 * Theme full URI
	 *
	 * @var string STARTAPP_TEMPLATE_URI
	 */
	define( 'STARTAPP_TEMPLATE_URI', get_template_directory_uri() . '/assets' );
endif;

if ( ! defined( 'STARTAPP_STYLESHEET_DIR' ) ) :
	/**
	 * Absolute path the the stylesheet directory
	 *
	 * @var string STARTAPP_STYLESHEET_DIR
	 */
	define( 'STARTAPP_STYLESHEET_DIR', get_stylesheet_directory() );
endif;

if ( ! defined( 'STARTAPP_STYLESHEET_URI' ) ) :
	/**
	 * Stylesheet URI
	 *
	 * @var string STARTAPP_STYLESHEET_URI
	 */
	define( 'STARTAPP_STYLESHEET_URI', get_stylesheet_directory_uri() );
endif;

if ( ! defined( 'STARTAPP_OPTIONS' ) ) :
	/**
	 * STARTAPP theme options name
	 *
	 * @var string STARTAPP_OPTIONS
	 */
	define( 'STARTAPP_OPTIONS', 'startapp_options' );
endif;

if ( ! defined( 'STARTAPP_COMPILED' ) ) :
	/**
	 * STARTAPP compiled SASS results option name
	 *
	 * @var string STARTAPP_COMPILED
	 */
	define( 'STARTAPP_COMPILED', 'startapp_compiled' );
endif;

if ( ! defined( 'STARTAPP_CATEGORY_TRANSIENT' ) ) :
	/**
	 * Transient name for counting categories
	 *
	 * @see startapp_is_categorized_blog()
	 * @see startapp_category_transient_flusher()
	 *
	 * @var string STARTAPP_CATEGORY_TRANSIENT
	 */
	define( 'STARTAPP_TRANSIENT_CATEGORIES', 'startapp_categories' );
endif;

if ( ! defined( 'STARTAPP_PAGE_SETTINGS' ) ) :
	/**
	 * Page Settings meta box name
	 *
	 * @see startapp_get_setting()
	 * @see startapp_add_post_settings_meta_box()
	 * @see startapp_add_page_settings_meta_box()
	 *
	 * @var string STARTAPP_PAGE_SETTINGS
	 */
	define( 'STARTAPP_PAGE_SETTINGS', '_startapp_page_settings' );
endif;

if ( ! isset( $content_width ) ) {
	/**
	 * Filter the template content width
	 *
	 * @param int $content_width Content width in pixels
	 */
	$content_width = apply_filters( 'startapp_content_width', 1170 );
}

/**
 * Sets up theme defaults and registers support for various WordPress features.
 *
 * Note that this function is hooked into the after_setup_theme hook, which
 * runs before the init hook. The init hook is too late for some features, such
 * as indicating support for post thumbnails.
 */
function startapp_setup() {
	/*
	 * Make theme available for translation.
	 * Translations can be filed in the /languages/ directory.
	 * If you're building a theme based on Startapp, use a find and replace
	 * to change 'startapp' to the name of your theme in all the template files.
	 */
	load_theme_textdomain( 'startapp', STARTAPP_TEMPLATE_DIR . '/languages' );

	/*
	 * Add default posts and comments RSS feed links to head.
	 */
	add_theme_support( 'automatic-feed-links' );

	/*
	 * Let WordPress manage the document title.
	 * By adding theme support, we declare that this theme does not use a
	 * hard-coded <title> tag in the document head, and expect WordPress to
	 * provide it for us.
	 */
	add_theme_support( 'title-tag' );

	/*
	 * Enable support for Post Thumbnails on posts and pages
	 *
	 * @link https://developer.wordpress.org/themes/functionality/featured-images-post-thumbnails/
	 */
	add_theme_support( 'post-thumbnails' );

	/*
	 * Enable the custom logo for WP4.5+
	 *
	 * @link https://codex.wordpress.org/Theme_Logo
	 */
	add_theme_support( 'custom-logo' );

	/**
	 * Enable WooCommerce support
	 *
	 * @link https://docs.woothemes.com/document/third-party-custom-theme-compatibility/
	 */
	add_theme_support( 'woocommerce' );
	add_theme_support( 'wc-product-gallery-zoom' );
	add_theme_support( 'wc-product-gallery-lightbox' );
	add_theme_support( 'wc-product-gallery-slider' );

	/*
	 * Register the nav menu locations
	 */
	register_nav_menus( array(
		'primary'  => esc_html__( 'Primary', 'startapp' ),
		'topbar'   => esc_html__( 'Topbar', 'startapp' ),
		'footer'   => esc_html__( 'Footer', 'startapp' ),
		'scroller' => esc_html__( 'Scroller', 'startapp' ),
	) );

	/*
	 * Switch default core markup for search form, comment form
	 * and comments to output valid HTML5.
	 */
	add_theme_support( 'html5', array(
		'search-form',
		'comment-form',
		'comment-list',
		'gallery',
		'caption',
	) );

	/*
	 * Enable support for Post Formats
	 *
	 * @link https://developer.wordpress.org/themes/functionality/post-formats/
	 */
	add_theme_support( 'post-formats', array(
		'image',
		'gallery',
		'video',
		'audio',
		'quote',
		'link',
		'chat',
	) );
}

add_action( 'after_setup_theme', 'startapp_setup' );

/**
 * Enqueue scripts and styles.
 */
function startapp_scripts() {

	/* Fonts */

	if ( startapp_is_google_fonts() ) {
		$body = startapp_get_option( 'typography_font_for_body', '//fonts.googleapis.com/css?family=Titillium+Web:400,300,700,600' );
		if ( ! empty( $body ) ) {
			wp_enqueue_style( 'startapp-body-font', startapp_google_font_url( $body ), array(), null, 'screen' );
		}
		unset( $body );

		$headings = startapp_get_option( 'typography_font_for_headings' );
		if ( ! empty( $headings ) ) {
			wp_enqueue_style( 'startapp-headings-font', startapp_google_font_url( $headings ), array(), null, 'screen' );
		}
		unset( $headings );
	}


	/* Styles */

	wp_enqueue_style( 'material-icons', STARTAPP_TEMPLATE_URI . '/stylesheets/vendor/material-icons.min.css', array(), null, 'screen' );
	wp_enqueue_style( 'socicon', STARTAPP_TEMPLATE_URI . '/stylesheets/vendor/socicon.min.css', array(), null, 'screen' );
	wp_enqueue_style( 'bootstrap', STARTAPP_TEMPLATE_URI . '/stylesheets/vendor/bootstrap.min.css', array(), null, 'screen' );

	// maybe enqueue custom icon fonts, @see startapp_add_advanced_options()
	foreach ( (array) startapp_get_custom_font_icons() as $i => $link ) {
		wp_enqueue_style( "startapp-custom-icon-{$i}", esc_url( trim( $link ) ), array(), null );
	}
	unset( $i, $link );

	wp_enqueue_style( 'startapp', startapp_stylesheet_uri(), array(), null, 'screen' );
	wp_add_inline_style( 'startapp', wp_strip_all_tags( startapp_get_option( 'advanced_custom_css', '' ), true ) );


	/* Scripts */

	wp_enqueue_script( 'startapp-modernizr', STARTAPP_TEMPLATE_URI . '/js/vendor/modernizr.custom.js', array(), null );
	if ( ! wp_script_is( 'waypoints' ) ) {
		wp_enqueue_script( 'waypoints', STARTAPP_TEMPLATE_URI . '/js/vendor/jquery.waypoints.min.js', array( 'jquery' ), null, true );
	}

	// isotope in theme is used on blog, archive and shop pages
	// also grid layout should be enabled in blog and archive
	$is_blog = ( ( is_home() || is_archive() ) && false !== strpos( startapp_blog_layout(), 'grid' ) );
	$is_shop = ( startapp_is_woocommerce() && ( is_shop() || is_product_taxonomy() ) );
	if ( ( $is_blog || $is_shop ) && ! wp_script_is( 'isotope' ) ) {
		wp_register_script( 'imagesloaded', STARTAPP_TEMPLATE_URI . '/js/vendor/imagesloaded.pkgd.min.js', array( 'jquery' ), null, true );
		wp_enqueue_script( 'isotope', STARTAPP_TEMPLATE_URI . '/js/vendor/isotope.pkgd.min.js', array( 'jquery', 'imagesloaded' ), null, true );
	}
	unset( $is_blog, $is_shop );

	wp_enqueue_script( 'bootstrap', STARTAPP_TEMPLATE_URI . '/js/vendor/bootstrap.min.js', array( 'jquery' ), null, true );
	wp_enqueue_script( 'jarallax', STARTAPP_TEMPLATE_URI . '/js/vendor/jarallax.min.js', array( 'jquery' ), null, true );
	wp_enqueue_script( 'velocity', STARTAPP_TEMPLATE_URI . '/js/vendor/velocity.min.js', array(), null, true );
	wp_enqueue_script( 'waves', STARTAPP_TEMPLATE_URI . '/js/vendor/waves.min.js', array(), null, true );
	wp_enqueue_script( 'scrollspy', STARTAPP_TEMPLATE_URI . '/js/vendor/scrollspy.js', array(), null, true );

	// @see Startapp_Widget_Image_Carousel::widget()
	if ( ! wp_script_is( 'slick', 'registered' ) ) {
		wp_register_script( 'slick', STARTAPP_TEMPLATE_URI . '/js/vendor/slick.min.js', array( 'jquery' ), null, true );
	}

	// video background
	if ( ! wp_script_is( 'jarallax-video' ) && startapp_is_video_background() ) {
		wp_enqueue_script( 'jarallax-video', STARTAPP_TEMPLATE_URI . '/js/vendor/jarallax-video.min.js', array( 'jarallax' ), null, true );
	}

	// shortcode animations
	if ( ! wp_script_is( 'aos' ) && startapp_is_animation() ) {
		wp_enqueue_style( 'aos', STARTAPP_TEMPLATE_URI . '/stylesheets/vendor/aos.min.css', array(), null );
		wp_enqueue_script( 'aos', STARTAPP_TEMPLATE_URI . '/js/vendor/aos.min.js', array(), null, true );
	}

	if ( is_singular() && comments_open() && get_option( 'thread_comments' ) ) {
		wp_enqueue_script( 'comment-reply' );
	}

	wp_enqueue_script( 'startapp', STARTAPP_TEMPLATE_URI . '/js/startapp-theme.js', array(), null, true );
	wp_localize_script( 'startapp', 'startapp', array(
		'ajaxurl' => admin_url( 'admin-ajax.php' ),
		'nonce'   => wp_create_nonce( 'startapp-ajax' ),
	) );
}

add_action( 'wp_enqueue_scripts', 'startapp_scripts', 11 );

/**
 * Enqueue scripts in the WordPress Dashboard
 *
 * @param string $page The current admin page.
 */
function startapp_admin_scripts( $page ) {
	wp_enqueue_style( 'startapp-admin', STARTAPP_TEMPLATE_URI . '/stylesheets/admin.css', array(), null );

	if ( 'widgets.php' === $page ) {
		wp_enqueue_media();
		wp_enqueue_script( 'startapp-widgets', STARTAPP_TEMPLATE_URI . '/js/startapp-widgets.js', array(
			'jquery',
			'jquery-ui-sortable',
		), null, true );
	}
}

add_action( 'admin_enqueue_scripts', 'startapp_admin_scripts' );

/**
 * Register the required plugins for this theme.
 *
 * @uses tgmpa()
 */
function startapp_tgm_init() {
	$dir = 'http://plugin.8guild.com/v1';

	$plugins = array(
		array(
			'name'     => esc_html__( 'StartApp Core', 'startapp' ),
			'slug'     => 'startapp-core',
			'source'   => $dir . '/startapp-core/1.4.1/',
			'version'  => '1.4.1',
			'required' => true,
		),
		array(
			'name'     => esc_html__( 'WPBakery Page Builder', 'startapp' ),
			'slug'     => 'js_composer',
			'source'   => $dir . '/js_composer/5.6/',
			'version'  => '5.6',
			'required' => true,
		),
		array(
			'name'     => esc_html__( 'Equip', 'startapp' ),
			'slug'     => 'equip',
			'source'   => $dir . '/equip/0.7.22/',
			'version'  => '0.7.22',
			'required' => true,
		),
		array(
			'name'     => esc_html__( 'Equip SASS', 'startapp' ),
			'slug'     => 'equip-sass',
			'source'   => $dir . '/equip-sass/0.2.0/',
			'version'  => '0.2.0',
			'required' => false,
		),
		array(
			'name'     => esc_html__( 'Importer by 8Guild', 'startapp' ),
			'slug'     => 'guild-importer',
			'source'   => $dir . '/guild-importer/0.1.0/',
			'version'  => '0.1.0',
			'required' => false,
		),
		array(
			'name'     => esc_html__( 'Revolution Slider', 'startapp' ),
			'slug'     => 'revslider',
			'source'   => $dir . '/revslider/5.4.8/',
			'version'  => '5.4.6',
			'required' => false,
		),
		array(
			'name'     => esc_html__( 'WooCommerce', 'startapp' ),
			'slug'     => 'woocommerce',
			'required' => false,
		),
        array(
            'name'     => esc_html__( 'WooCommerce Blocks', 'startapp' ),
            'slug'     => 'woo-gutenberg-products-block',
            'required' => false,
        ),
		array(
			'name'     => esc_html__( 'Contact Form 7', 'startapp' ),
			'slug'     => 'contact-form-7',
			'required' => false,
		),
		array(
			'name'     => esc_html__( 'Breadcrumb NavXT', 'startapp' ),
			'slug'     => 'breadcrumb-navxt',
			'required' => false,
		),
		array(
			'name'     => esc_html__( 'Black Studio TinyMCE Widget', 'startapp' ),
			'slug'     => 'black-studio-tinymce-widget',
			'required' => false,
		),
        array(
            'name'     => esc_html__( 'Disable Gutenberg', 'startapp' ),
            'slug'     => 'disable-gutenberg',
            'required' => true,
        ),
	);

	$config = array(
		'id'           => 'startapp',
		'menu'         => 'startapp-plugins',
		'has_notices'  => true,
		'dismissable'  => true,
		'is_automatic' => true,
	);

	tgmpa( $plugins, $config );
}

add_action( 'tgmpa_register', 'startapp_tgm_init' );

/**
 * One click demo import
 *
 * Requires "Guild Importer" plugin
 */
function startapp_importer_init() {
	if ( ! function_exists( 'gi_register' ) ) {
		return;
	}

	$dir  = STARTAPP_TEMPLATE_DIR . '/demo';
	$vars = array(
		array(
			'key'     => 'financial',
			'title'   => esc_html__( 'Financial', 'startapp' ),
			'link'    => esc_url( 'http://startapp.8guild.com/financial/' ),
			'preview' => STARTAPP_TEMPLATE_URI . '/img/import/financial.jpg',
			'import'  => array(
				'xml'       => $dir . '/financial/demo.xml',
				'extra'     => $dir . '/financial/extra.json',
				'revslider' => array( $dir . '/financial/revslider/financial-home.zip' ),
			),
		),
		array(
			'key'     => 'consulting',
			'title'   => esc_html__( 'Consulting', 'startapp' ),
			'link'    => esc_url( 'http://startapp.8guild.com/consulting/' ),
			'preview' => STARTAPP_TEMPLATE_URI . '/img/import/consulting.jpg',
			'import'  => array(
				'xml'   => $dir . '/consulting/demo.xml',
				'extra' => $dir . '/consulting/extra.json',
			),
		),
		array(
			'key'     => 'saas',
			'title'   => esc_html__( 'SaaS', 'startapp' ),
			'link'    => esc_url( 'http://startapp.8guild.com/saas/' ),
			'preview' => STARTAPP_TEMPLATE_URI . '/img/import/saas.jpg',
			'import'  => array(
				'xml'       => $dir . '/saas/demo.xml',
				'extra'     => $dir . '/saas/extra.json',
				'revslider' => array( $dir . '/saas/revslider/saas-home-hero.zip' ),
			),
		),
		array(
			'key'     => 'agency',
			'title'   => esc_html__( 'Agency', 'startapp' ),
			'link'    => esc_url( 'http://startapp.8guild.com/agency/' ),
			'preview' => STARTAPP_TEMPLATE_URI . '/img/import/agency.jpg',
			'import'  => array(
				'xml'       => $dir . '/agency/demo.xml',
				'extra'     => $dir . '/agency/extra.json',
				'revslider' => array( $dir . '/agency/revslider/hero.zip' ),
			),
		),
		array(
			'key'     => 'app',
			'title'   => esc_html__( 'App Showcase', 'startapp' ),
			'link'    => esc_url( 'http://startapp.8guild.com/app-showcase/' ),
			'preview' => STARTAPP_TEMPLATE_URI . '/img/import/app.jpg',
			'import'  => array(
				'xml'   => $dir . '/app/demo.xml',
				'extra' => $dir . '/app/extra.json',
			),
		),
		array(
			'key'     => 'event',
			'title'   => esc_html__( 'Event', 'startapp' ),
			'link'    => esc_url( 'http://startapp.8guild.com/event/' ),
			'preview' => STARTAPP_TEMPLATE_URI . '/img/import/event.jpg',
			'import'  => array(
				'xml'   => $dir . '/event/demo.xml',
				'extra' => $dir . '/event/extra.json',
			),
		),
		array(
			'key'     => 'coworking',
			'title'   => esc_html__( 'Coworking', 'startapp' ),
			'link'    => esc_url( 'http://startapp.8guild.com/coworking/' ),
			'preview' => STARTAPP_TEMPLATE_URI . '/img/import/coworking.jpg',
			'import'  => array(
				'xml'   => $dir . '/coworking/demo.xml',
				'extra' => $dir . '/coworking/extra.json',
			),
		),
		array(
			'key'     => 'photography',
			'title'   => esc_html__( 'Aerial Photography', 'startapp' ),
			'link'    => esc_url( 'http://startapp.8guild.com/photography/' ),
			'preview' => STARTAPP_TEMPLATE_URI . '/img/import/photography.jpg',
			'import'  => array(
				'xml'   => $dir . '/photography/demo.xml',
				'extra' => $dir . '/photography/extra.json',
			),
		),
		array(
			'key'     => 'agritech',
			'title'   => esc_html__( 'Agri-Tech', 'startapp' ),
			'link'    => esc_url( 'http://startapp.8guild.com/agritech/' ),
			'preview' => STARTAPP_TEMPLATE_URI . '/img/import/agritech.jpg',
			'import'  => array(
				'xml'   => $dir . '/agritech/demo.xml',
				'extra' => $dir . '/agritech/extra.json',
			),
		),
	);

	gi_register( $vars, array(
		'parent_slug' => 'startapp',
		'page_title'  => esc_html__( 'Demo Import', 'startapp' ),
		'menu_title'  => esc_html__( 'Demo Import', 'startapp' ),
		'menu_slug'   => 'startapp-import',
		'nonce'       => 'startapp_import',
		'nonce_field' => 'startapp_import_nonce',
	) );
}

add_action( 'guild/importer/register', 'startapp_importer_init' );

/*
 * Helpers and utilities
 */
require STARTAPP_TEMPLATE_DIR . '/inc/helpers.php';

/*
 * Custom template tags for this theme
 */
require STARTAPP_TEMPLATE_DIR . '/inc/template-tags.php';

/*
 * The parts of theme
 */
require STARTAPP_TEMPLATE_DIR . '/inc/structure.php';

/*
 * Custom actions and filters
 */
require STARTAPP_TEMPLATE_DIR . '/inc/extras.php';

/*
 * Theme Options
 */
require STARTAPP_TEMPLATE_DIR . '/inc/options.php';

/*
 * Theme meta boxes
 */
require STARTAPP_TEMPLATE_DIR . '/inc/meta-boxes.php';

/*
 * User additions
 */
require STARTAPP_TEMPLATE_DIR . '/inc/user.php';

/*
 * Theme widgets
 */
require STARTAPP_TEMPLATE_DIR . '/inc/widgets.php';

/*
 * Menu customizations
 */
require STARTAPP_TEMPLATE_DIR . '/inc/menus.php';

/*
 * Customizer additions
 */
require STARTAPP_TEMPLATE_DIR . '/inc/customizer.php';

/*
 * Theme custom walkers
 */
require STARTAPP_TEMPLATE_DIR . '/inc/walkers.php';

/*
 * Comments filters and actions
 */
require STARTAPP_TEMPLATE_DIR . '/inc/comments.php';

/*
 * Add the TGM_Plugin_Activation class
 */
require_once STARTAPP_TEMPLATE_DIR . '/vendor/tgm/class-tgm-plugin-activation.php';

/*
 * Visual Composer additions
 */
require_once STARTAPP_TEMPLATE_DIR . '/vendor/visual-composer/vc.php';

/*
 * Polylang compatibility
 */
require_once STARTAPP_TEMPLATE_DIR . '/vendor/polylang/polylang.php';

/*
 * WPML Compatibility
 */
require_once STARTAPP_TEMPLATE_DIR . '/vendor/wpml/wpml.php';

/*
 * WooCommerce
 */
require_once STARTAPP_TEMPLATE_DIR . '/vendor/woocommerce/woocommerce.php';

/*
 * Equip patch
 */
require_once STARTAPP_TEMPLATE_DIR . '/vendor/equip/bootstrap.php';
