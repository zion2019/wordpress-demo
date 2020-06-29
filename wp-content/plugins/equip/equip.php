<?php
/**
 * Equip
 *
 * Simple, lightweight and fast fields builder for WordPress themes and plugins.
 *
 * Plugin Name:       Equip
 * Plugin URI:        http://8guild.com
 * Description:       Simple, lightweight and fast fields builder for WordPress themes and plugins.
 * Version:           0.7.22
 * Author:            8guild
 * Author URI:        http://8guild.com
 * Text Domain:       equip
 * License:           GPL3+
 * License URI:       http://www.gnu.org/licenses/gpl-3.0.txt
 * Domain Path:       languages
 *
 * @package           Equip
 * @author            8guild <8guild@gmail.com>
 * @license           GNU General Public License, version 3
 * @wordpress-plugin
 */

if ( ! defined( 'ABSPATH' ) ) {
	die;
}

/*
 * WP_CLI
 *
 * @todo Read more about WP_CLI
 * @link https://github.com/Automattic/Co-Authors-Plus/blob/master/co-authors-plus.php#L34
 */
if ( defined( 'WP_CLI' ) && WP_CLI ) {
	return;
}

define( 'EQUIP_VERSION', '0.7.22' );
define( 'EQUIP_REQUIRED_WP_VERSION', '4.5' );
define( 'EQUIP_REQUIRED_PHP_VERSION', '5.4' );
define( 'EQUIP_PLUGIN_BASENAME', plugin_basename( __FILE__ ) );
define( 'EQUIP_PLUGIN_DIR', untrailingslashit( plugin_dir_path( __FILE__ ) ) );
define( 'EQUIP_APP_DIR', EQUIP_PLUGIN_DIR . '/app' );
define( 'EQUIP_ASSETS_DIR', EQUIP_APP_DIR . '/assets' );
define( 'EQUIP_ASSETS_URI', plugins_url( '/app/assets', __FILE__ ) );
define( 'EQUIP_MODULES_DIR', EQUIP_APP_DIR . '/modules' );
define( 'EQUIP_LAYOUTS_DIR', EQUIP_APP_DIR . '/layouts' );
define( 'EQUIP_FIELDS_DIR', EQUIP_APP_DIR . '/fields' );
define( 'EQUIP_ENGINES_DIR', EQUIP_APP_DIR . '/engines' );
define( 'EQUIP_INCLUDES_DIR', EQUIP_APP_DIR . '/includes' );
define( 'EQUIP_SERVICES_DIR', EQUIP_APP_DIR . '/services' );

// Autoload
require EQUIP_PLUGIN_DIR . '/vendor/autoload.php';

// Load helpers functions
require EQUIP_INCLUDES_DIR . '/utils.php';

// Load custom actions
require EQUIP_INCLUDES_DIR . '/actions.php';

// Load the API
require EQUIP_INCLUDES_DIR . '/api.php';

// Activation checks
require EQUIP_PLUGIN_DIR . '/activation.php';

/**
 * A wrapper for plugin
 *
 * @author  8guild
 * @package Equip
 */
class Equip_Plugin {
	/**
	 * @var null|Equip_Plugin
	 */
	private static $instance = null;

	/**
	 * Instance
	 *
	 * @return Equip_Plugin|null
	 */
	public static function instance() {
		if ( null === self::$instance ) {
			self::$instance = new self();
		}

		return self::$instance;
	}

	public function setup() {
		add_action( 'init', array( $this, 'textdomain' ) );
		add_action( 'init', array( $this, 'register' ) );
	}

	/**
	 * Load the plugin textdomain
	 *
	 * @link    https://codex.wordpress.org/Plugin_API/Action_Reference/init
	 * @wp-hook init
	 */
	public function textdomain() {
		load_plugin_textdomain( 'equip', false, dirname( plugin_basename( __FILE__ ) ) . '/languages' );
	}

	/**
	 * Register the elements
	 *
	 * @wp-hook init
	 */
	public function register() {
		do_action( 'equip/register' );
	}
}

add_action( 'plugins_loaded', array( Equip_Plugin::instance(), 'setup' ) );
