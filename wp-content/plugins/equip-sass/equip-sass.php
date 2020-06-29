<?php
/**
 * Equip SASS
 *
 * Plugin Name: Equip SASS
 * Plugin URI:  http://8guild.com
 * Description: SASS compiler for Equip
 * Version:     0.2.0
 * Author:      8guild
 * Author URI:  http://8guild.com
 * Text Domain: equip-sass
 * License:     GPL3+
 * License URI: http://www.gnu.org/licenses/gpl-3.0.txt
 * Domain Path: languages
 *
 * @package Equip
 * @author  8guild <8guild@gmail.com>
 * @license GNU General Public License, version 3
 *
 * @wordpress-plugin
 */

if ( ! defined( 'ABSPATH' ) ) {
	die;
}

require __DIR__ . '/class-guild-logger.php';

/**
 * Extension for compiling the SASS
 *
 * @author  8guild
 * @package Equip\Extension
 */
class Equip_SASS {

	/**
	 * @var Equip_SASS|null
	 */
	private static $instance = null;

	/**
	 * @var Guild_Logger
	 */
	protected $logger;

	/**
	 * Settings for current job
	 *
	 * @var array
	 */
	protected $settings = array();

	/**
	 * A list of all fields
	 *
	 * @var array
	 */
	protected $fields = array();

	/**
	 * SASS variables passed to remote compiler
	 *
	 * @var array
	 */
	protected $variables = array();

	/**
	 * Default values of 'sass related' options
	 *
	 * @var array
	 */
	protected $defaults = array();

	/**
	 * Compiler API endpoint
	 *
	 * @var string
	 */
	protected $endpoint;

	/**
	 * API compile command endpoint
	 *
	 * @var string
	 */
	protected $compileEndpoint = '/compile'; // no trailing slash, please

	/**
	 * API compiled command endpoint
	 *
	 * @var string
	 */
	protected $compiledEndpoint = '/compiled'; // no trailing slash, please

	/**
	 * Return the instance
	 *
	 * @return Equip_SASS|null
	 */
	public static function instance() {
		if ( null === self::$instance ) {
			self::$instance = new self();
		}

		return self::$instance;
	}

	/**
	 * Constructor
	 */
	public function __construct() {
		$this->logger = new Guild_Logger( 'equip-sass', WP_CONTENT_DIR . '/debug.log' );
	}

	/**
	 * Main plugin routine
	 *
	 * Add actions and filters
	 */
	public function setup() {
		add_action( 'init', array( $this, 'textdomain' ) );
		add_action( 'equip/options/saved', array( $this, 'compile' ), 10, 3 );
		add_action( 'equip/options/reseted', array( $this, 'reset' ), 10, 2 );
	}

	/**
	 * Load plugin translation
	 *
	 * @hooked  init 10
	 * @see     setup()
	 */
	public function textdomain() {
		load_plugin_textdomain( 'equip-sass', false, plugin_dir_path( __FILE__ ) . 'languages' );
	}

	/**
	 * Compile the SASS
	 *
	 * @param string                     $slug Element name
	 * @param mixed                      $sanitized Already sanitized and updated values
	 * @param \Equip\Misc\StorageElement $element Storage element
	 *
	 * @return bool
	 */
	public function compile( $slug, $sanitized, $element ) {
		/**
		 * This filter allows to disable SASS compiling in a child theme
		 *
		 * Default is FALSE which means "Do not disable SASS compilation"
		 *
		 * @example add_filter( 'equip/sass/disable', '__return_true' );
		 *
		 * @params  bool $is_compile Enable or disable compilation
		 */
		if ( true === apply_filters( 'equip/sass/disable', false ) ) {
			return false;
		}

		$this->settings = $this->parseSettings( $element->getArgs() );
		if ( empty( $this->settings['endpoint'] ) ) {
			$this->logger->addRecord( 'You have to provide the Compiler API Endpoint to make it work' );

			return false;
		}

		$this->endpoint = rtrim( $this->settings['endpoint'], '/\\' );
		$this->fields   = equip_layout_get_fields( $element->getLayout() );
		if ( empty( $this->fields ) ) {
			return false;
		}

		$this->variables = $this->getVariables( $sanitized );
		$this->defaults  = $this->getDefaults( $this->fields );

		if ( ! $this->isCompile( $sanitized ) ) {
			return false;
		}

		// save the current state to "copy"
		// to be able to compare those options with previous state
		$this->updateOptionsCopy( $sanitized );

		// make a remote request to add a compiling into the queue
		$response = wp_remote_post( esc_url_raw( $this->endpoint . $this->compileEndpoint ), array(
			'method'      => 'POST',
			'httpversion' => '1.1',
			'redirection' => 1,
			'headers'     => array( 'Content-Type' => 'application/json; charset=utf-8' ),
			'body'        => json_encode( array(
				'client'    => array( 'url' => get_bloginfo( 'url' ) ), // used for building the response url
				'settings'  => array( 'style' => 'compressed' ),
				'variables' => $this->variables,
			) ),
		) );

		if ( $response instanceof \WP_Error ) {
			$this->logger->addRecord( $response->get_error_message() );

			return false;
		}

		$code = wp_remote_retrieve_response_code( $response );
		$body = wp_remote_retrieve_body( $response );
		if ( 200 !== $code || empty( $body ) ) {
			$this->logger->addRecord( sprintf( 'Something wrong with compiler API. Response code is `%s`.', $code ) );

			return false;
		}

		$body = json_decode( $body, true );
		if ( false == $body['success'] ) {
			$this->logger->addRecord( $body['message'] );

			return false;
		}

		// prepare the path for compiled file
		// compiled css file is stored under the uploads directory
		// optionally plugin make a project dir within the uploads dir,
		// [this is a configurable option, @see project_dir]
		// so, first, make sure al directory exists and writable

		$uploads = wp_upload_dir();
		if ( false !== $uploads['error'] || ! wp_is_writable( $uploads['basedir'] ) ) {
			$this->logger->addRecord( 'Uploads directory is not writable. Check the permissions.' );

			return false;
		}

		$projectDir  = untrailingslashit( $this->settings['project_dir'] );
		$compiledDir = wp_normalize_path( $uploads['basedir'] . DIRECTORY_SEPARATOR . $projectDir );

		$filename = $this->settings['filename'];
		$filePath = $compiledDir . DIRECTORY_SEPARATOR . $filename;
		$fileUri  = $uploads['baseurl'] . '/' . $projectDir . '/' . $filename;
		unset( $filename, $uploads );

		if ( ! $this->makeProjectDir( $compiledDir ) ) {
			$this->logger->addRecord( 'Could not create a project directory in Uploads. Check the permissions.' );

			return false;
		}

		$response = wp_remote_get( esc_url_raw( $this->endpoint . '/' . ltrim( $body['source'], '/\\' ) ), array(
			'method'      => 'GET',
			'httpversion' => '1.1',
			'redirection' => 1,
			'stream'      => true,
			'filename'    => $filePath,
		) );

		if ( $response instanceof \WP_Error ) {
			$this->logger->addRecord( $response->get_error_message() );

			return false;
		}

		$result = array( 'path' => $filePath, 'url' => $fileUri );
		$this->saveOption( $result );

		return true;
	}

	/**
	 * Reset the compiled option
	 *
	 * @param string                     $slug Element name
	 * @param \Equip\Misc\StorageElement $element Storage element
	 *
	 * @return bool
	 */
	public function reset( $slug, $element ) {
		// get SASS settings
		$settings = $this->parseSettings( $element->getArgs() );

		// delete the options copy on reset
		delete_option( $settings['option_copy'] );

		$c = get_option( $settings['option'] );
		if ( is_array( $c )
		     && array_key_exists( 'path', $c )
		     && file_exists( $c['path'] )
		) {
			unlink( $c['path'] );
		}

		return delete_option( $settings['option'] );
	}

	/**
	 * Returns a list of fields
	 *
	 * @param \Equip\Layout\OptionsLayout $layout
	 *
	 * @return array
	 */
	protected function getFields( $layout ) {
		return equip_layout_get_fields( $layout );
	}

	/**
	 * Prepare the SASS variables
	 *
	 * in format:
	 * [
	 *   variable => [prepend, value, append],
	 *   ...
	 * ]
	 *
	 * where:
	 * `variable` is a SASS variable name,
	 * `prepend` is an optional extension before the variable value
	 * `value` is current variable value from Theme Options
	 * `append` is an optional extension after the variable value
	 *
	 * @param array $options Theme options key=>values
	 *
	 * @return array
	 */
	protected function getVariables( $options ) {
		$variables = [];

		/**
		 * @var \Equip\Layout\FieldLayout $field
		 * @var string                    $key Option name. Use it to get options value from $options
		 */
		foreach ( $this->fields as $key => $field ) {
			$sass = $field->get_setting( 'sass' );
			if ( empty( $sass ) ) {
				continue;
			}

			// the $sass variable may be an declared in two ways:
			// as an array: 'sass' => array( 'var' => 'font-size-base', 'append' => 'px', 'prepend' => null )
			// or as a string: 'sass' => 'brand-default',
			if ( is_array( $sass ) ) {
				if ( empty( $sass['var'] ) ) {
					continue;
				}

				$var = $sass['var'];

				$variables[ $var ]['prepend'] = array_key_exists( 'prepend', $sass ) ? $sass['prepend'] : null;
				$variables[ $var ]['value']   = array_key_exists( $key, $options ) ? $options[ $key ] : '';
				$variables[ $var ]['append']  = array_key_exists( 'append', $sass ) ? $sass['append'] : null;
				unset( $var );
			} else {
				$variables[ $sass ] = array(
					'prepend' => null,
					'value'   => array_key_exists( $key, $options ) ? $options[ $key ] : '',
					'append'  => null,
				);
			}
		}

		return $variables;
	}

	/**
	 * Get default values for fields in format [key => default value]
	 *
	 * @param array $fields Fields
	 *
	 * @return array
	 */
	protected function getDefaults( $fields ) {
		$defaults = [];

		/** @var \Equip\Layout\FieldLayout $field */
		foreach ( $fields as $key => $field ) {
			$sass = $field->get_setting( 'sass' );
			if ( empty( $sass ) ) {
				continue;
			}

			$defaults[ $key ] = $field->get_setting( 'default', null );
		}

		return $defaults;
	}

	/**
	 * Check if compilation is required
	 *
	 * As compiling sass is a very resource intensive operation,
	 * check if any options that are affect the front-end was changed.
	 *
	 * @param array $options Saved theme options
	 *
	 * @return bool
	 */
	protected function isCompile( $options ) {
		$defaults = $this->defaults;

		/**
		 * Get only those options that are affected the front-end
		 * and required for SASS compiling.
		 *
		 * @var array All settings affecting the front end
		 */
		$customizable = array_intersect_key( $options, $defaults );

		// options copy to compare with previous state
		$previous = $this->getOptionsCopy();
		if ( empty( $previous ) ) {
			$previous = $defaults;
		}

		// just in case
		ksort( $defaults, SORT_STRING );
		ksort( $previous, SORT_STRING );
		ksort( $customizable, SORT_STRING );

		$affected = array();
		foreach ( $customizable as $key => $value ) {
			if ( array_key_exists( $key, $previous ) && $value != $previous[ $key ] ) {
				$affected[ $key ] = $value;
			}
		}

		return ( count( $affected ) > 0 );
	}

	/**
	 * Get SASS settings
	 *
	 * @param array $args Arguments
	 *
	 * @return array
	 */
	protected function parseSettings( $args ) {
		if ( empty( $args['sass'] ) ) {
			return [];
		}

		return wp_parse_args( $args['sass'], [
			'endpoint'    => 'http://compiler.8guild.com/',
			'project_dir' => 'equip',
			'filename'    => 'compiled.css',
			'option'      => 'equip_compiled',
			'option_copy' => 'equip_compiled_copy',
		] );
	}

	/**
	 * Make a directory for storing the compiled css file
	 *
	 * @param string $compiledDirPath Absolute path to compiled directory
	 *
	 * @return bool
	 */
	protected function makeProjectDir( $compiledDirPath ) {
		$credentials = request_filesystem_credentials( site_url() . '/wp-admin/', '', false, false, array() );
		if ( ! WP_Filesystem( $credentials ) ) {
			return false;
		}

		/** @var WP_Filesystem_Direct $wp_filesystem */
		global $wp_filesystem;
		if ( ! $wp_filesystem->exists( $compiledDirPath ) ) {
			$wp_filesystem->mkdir( $compiledDirPath, FS_CHMOD_DIR );
		}

		return true;
	}

	/**
	 * Save the compilation result
	 *
	 * The value of this option will be used
	 *
	 * @param array|bool $result Compilation result
	 */
	protected function saveOption( $result ) {
		if ( false === get_option( $this->settings['option'] ) ) {
			add_option( $this->settings['option'], '', '', 'no' );
		}

		// store to the database
		update_option( $this->settings['option'], $result, 'no' );
	}

	/**
	 * Save options copy
	 *
	 * This copy stores the "previous" copy of options
	 * and used to compare with "current" state to make sure
	 * options was really changed.
	 *
	 * @param array $options Theme Options
	 */
	protected function updateOptionsCopy( $options ) {
		if ( false === get_option( $this->settings['option_copy'] ) ) {
			add_option( $this->settings['option_copy'], '', '', 'no' );
		}

		// store to the database
		update_option( $this->settings['option_copy'], $options, 'no' );
	}

	/**
	 * Returns the "options copy"
	 *
	 * @return mixed
	 */
	protected function getOptionsCopy() {
		return get_option( $this->settings['option_copy'] );
	}
}

add_action( 'plugins_loaded', array( Equip_SASS::instance(), 'setup' ) );
