<?php
/**
 * Plugin Name:  StartApp Core
 * Plugin URI:   http://startapp.8guild.com/
 * Description:  Plugin that extends StartApp theme functionality.
 * Version:      1.4.1
 * Author:       8guild
 * Author URI:   http://8guild.com
 * License:      GNU General Public License v2 or later
 * License URI:  http://www.gnu.org/licenses/gpl-2.0.html
 * Text Domain:  startapp
 *
 * @author 8guild
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/**#@+
 * Plugin constants
 *
 * @since 1.0.0
 */
define( 'STARTAPP_CORE_FILE', __FILE__ );
define( 'STARTAPP_CORE_ROOT', __DIR__ );
define( 'STARTAPP_CORE_URI', plugins_url( '/assets', STARTAPP_CORE_FILE ) );
/**#@-*/

/*
 * Load helpers functions
 */
require STARTAPP_CORE_ROOT . '/inc/helpers.php';

/**
 * Load core classes according to WordPress naming conventions.
 *
 * @param string $class Class name
 *
 * @link  https://make.wordpress.org/core/handbook/coding-standards/php/#naming-conventions
 *
 * @return bool
 */
function startapp_core_loader( $class ) {
	if ( false === stripos( $class, 'Startapp_' ) ) {
		// call next loader
		return true;
	}

	$chunks = array_filter( explode( '_', strtolower( $class ) ) );
	$root   = STARTAPP_CORE_ROOT;
	$subdir = '/classes/';

	$file = 'class-' . implode( '-', $chunks ) . '.php';
	$path = wp_normalize_path( $root . $subdir . $file );
	if ( is_readable( $path ) ) {
		require $path;
	}

	return true;
}

spl_autoload_register( 'startapp_core_loader' );

/**
 * Plugin textdomain
 */
function startapp_core_textdomain() {
	load_plugin_textdomain( 'startapp', false, dirname( plugin_basename( __FILE__ ) ) . '/languages' );
}

add_action( 'init', 'startapp_core_textdomain' );

/**
 * Load custom post types
 */
function startapp_core_cpt() {
	$path  = wp_normalize_path( STARTAPP_CORE_ROOT . '/cpt' );
	$files = startapp_get_dir_contents( $path );

	$loader = Startapp_Cpt_Loader::instance();
	$loader->init( $files );
}

add_action( 'plugins_loaded', 'startapp_core_cpt' );

/**
 * Initialize the CPT's and flushing the rewrite rules
 * on plugin activation
 *
 * @see register_activation_hook()
 */
function startapp_core_activation() {
	$loader = Startapp_Cpt_Loader::instance();

	$path  = wp_normalize_path( STARTAPP_CORE_ROOT . '/cpt' );
	$files = startapp_get_dir_contents( $path );

	$loader->register( $files );
	flush_rewrite_rules();
}

register_activation_hook( STARTAPP_CORE_FILE, 'startapp_core_activation' );

/**
 * Init the shortcodes
 *
 * @hook init
 */
function startapp_core_shortcodes() {
	// collect all shortcodes
	$path  = wp_normalize_path( STARTAPP_CORE_ROOT . '/shortcodes/templates' );
	$files = startapp_get_dir_contents( $path );

	// TODO: shortcode init class
	Startapp_Shortcodes::init( $files );
}

add_action( 'init', 'startapp_core_shortcodes' );

/**
 * Enqueue scripts and styles on front-end
 *
 * @hooked wp_enqueue_scripts
 */
function startapp_core_front_scripts() {
	// remove isotope, registered by VC
	if ( wp_script_is( 'isotope', 'registered' ) ) {
		wp_deregister_script( 'isotope' );
	}

	// remove waypoints, registered by VC
	if ( wp_script_is( 'waypoints', 'registered' ) ) {
		wp_deregister_script( 'waypoints' );
	}

	wp_register_style( 'photoswipe', STARTAPP_CORE_URI . '/css/vendor/photoswipe.min.css', array(), null );
	wp_register_style( 'photoswipe-skin', STARTAPP_CORE_URI . '/css/vendor/default-skin/default-skin.min.css', array(), null );
	wp_register_style( 'magnific-popup', STARTAPP_CORE_URI . '/css/vendor/magnific-popup.min.css', array(), null );

	wp_register_script( 'photoswipe', STARTAPP_CORE_URI . '/js/vendor/photoswipe.min.js', array( 'jquery' ), null, true );
	wp_register_script( 'photoswipe-ui', STARTAPP_CORE_URI . '/js/vendor/photoswipe-ui-default.min.js', array( 'jquery' ), null, true );
	wp_register_script( 'magnific-popup', STARTAPP_CORE_URI . '/js/vendor/jquery.magnific-popup.min.js', array( 'jquery' ), null, true );
	wp_register_script( 'counterup', STARTAPP_CORE_URI . '/js/vendor/counterup.min.js', array( 'jquery' ), null, true );
	wp_register_script( 'slick', STARTAPP_CORE_URI . '/js/vendor/slick.min.js', array( 'jquery' ), null, true );

	$gmaps_key = startapp_get_option('general_custom_google_maps_api_key');
    if ( defined('STARTAPP_GOOGLE_MAPS_API_KEY') ) {
        wp_register_script('googleapis-maps', '//maps.googleapis.com/maps/api/js?key=' . STARTAPP_GOOGLE_MAPS_API_KEY, null, null);
        wp_register_script('gmap3', STARTAPP_CORE_URI . '/js/vendor/gmap3.min.js', array('googleapis-maps'), null, true);
    }elseif ( !empty( $gmaps_key ) ) {
        wp_register_script('googleapis-maps', '//maps.googleapis.com/maps/api/js?key=' . $gmaps_key, null, null);
        wp_register_script('gmap3', STARTAPP_CORE_URI . '/js/vendor/gmap3.min.js', array('googleapis-maps'), null, true);
    }

	wp_register_script( 'imagesloaded', STARTAPP_CORE_URI . '/js/vendor/jquery.imagesloaded.pkgd.min.js', array( 'jquery' ), null, true );
	wp_register_script( 'isotope', STARTAPP_CORE_URI . '/js/vendor/isotope.pkgd.min.js', array( 'jquery', 'imagesloaded' ), null, true );
	wp_register_script( 'countdown', STARTAPP_CORE_URI . '/js/vendor/jquery.downCount.min.js', array( 'jquery' ), null, true );

	if ( startapp_is_animation() ) {
		wp_enqueue_style( 'aos', STARTAPP_CORE_URI . '/css/vendor/aos.min.css', array(), null );
		wp_enqueue_script( 'aos', STARTAPP_CORE_URI . '/js/vendor/aos.min.js', array(), null, true );
	}

	wp_enqueue_script( 'startapp-core', STARTAPP_CORE_URI . '/js/startapp-core.js', array( 'jquery' ), null, true );

	// nonce and ajaxurl for AJAX calls
	// attach to "jQuery" because I need this variable in header
	wp_localize_script( 'jquery', 'startappCore', array(
		'ajaxurl' => esc_url( admin_url( 'admin-ajax.php' ) ),
		'nonce'   => wp_create_nonce( 'startapp-core-ajax' ),
	) );
}

add_action( 'wp_enqueue_scripts', 'startapp_core_front_scripts' );

/**
 * Enqueue scripts and styles on front-end
 *
 * @hooked admin_enqueue_scripts
 */
function startapp_core_admin_scripts() {
	wp_enqueue_style( 'startapp', STARTAPP_CORE_URI . '/css/admin.css', array(), null );

	if ( ! wp_style_is( 'material-icons' ) ) {
		wp_enqueue_style( 'material-icons', STARTAPP_CORE_URI . '/css/vendor/material-icons.min.css', array(), null );
	}

	wp_localize_script( 'jquery', 'startappCore', array(
		'nonce' => wp_create_nonce( 'startapp-core' ),
	) );
}

add_action( 'admin_enqueue_scripts', 'startapp_core_admin_scripts' );

/*
 * Load equip bootstrap file
 */
require STARTAPP_CORE_ROOT . '/equip/bootstrap.php';

/*
 * Visual Composer custom shortcodes mapping
 */
require STARTAPP_CORE_ROOT . '/shortcodes/vc-map.php';

/*
 * Custom actions
 */
require STARTAPP_CORE_ROOT . '/inc/actions.php';

/*
 * Admin pages
 */
require STARTAPP_CORE_ROOT . '/inc/admin-pages.php';

/*
 * Theme preloader
 */
require STARTAPP_CORE_ROOT . '/inc/preloader.php';

/*
 * Custom widgets
 */
require STARTAPP_CORE_ROOT . '/inc/widgets.php';