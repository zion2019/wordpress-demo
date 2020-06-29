<?php
// exit if accessed directly
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/**
 * Shortcodes
 *
 * @author 8guild
 */
class Startapp_Shortcodes {
	/**
	 * Instance of class
	 *
	 * @var null|Startapp_Shortcodes
	 */
	private static $instance;

	/**
	 * List of shortcodes
	 *
	 * @var array
	 */
	private $shortcodes = array();

	/**
	 * Constructor
	 *
	 * @param array $files A list of file names and path to shortcode output template
	 */
	private function __construct( $files ) {
		/**
		 * Filter the shortcodes list. The best place to add or remove shortcode(s).
		 *
		 * @param array $shortcodes Shortcodes list
		 */
		$this->shortcodes = apply_filters( 'startapp_shortcodes_list', array_keys( $files ) );

		// add shortcodes
		foreach ( $this->shortcodes as $shortcode ) {
			add_shortcode( $shortcode, array( $this, 'render' ) );
		}
	}

	/**
	 * Initialization
	 *
	 * @param array $files A list of file names and path to shortcode output template
	 *
	 * @return Startapp_Shortcodes
	 */
	public static function init( $files ) {
		if ( null === self::$instance ) {
			self::$instance = new self( $files );
		}

		return self::$instance;
	}

	/**
	 * Get shortcode output
	 *
	 * @param array       $atts      Shortcode attributes
	 * @param string|null $content   Shortcode content
	 * @param string      $shortcode Shortcode tag
	 *
	 * @return string Shortcode HTML
	 */
	public function render( $atts = array(), $content = null, $shortcode = '' ) {
		/**
		 * Fires before the shortcode content will be rendered
		 *
		 * @param string $shortcode Shortcode tag
		 * @param array  $atts      Shortcode attributes
		 * @param mixed  $content   Shortcode content
		 */
		do_action( 'startapp_shortcode_render_before', $shortcode, $atts, $content );

		/**
		 * Whether to use a cache?
		 *
		 * This filter allows you to disable shortcode caching
		 *
		 * For example you can completely disable cache for all shortcodes
		 *
		 * @example
		 * add_filter( 'startapp_shortcode_is_cache', '__return_false' )
		 *
		 * Or you can disable cache for a specific shortcode
		 *
		 * @example
		 * add_filter( 'startapp_shortcode_is_cache', function ( $is_cache, $shortcode ) {
		 *   return 'startapp_button' === $shortcode ? false : true;
		 * }, 10, 2 );
		 *
		 * @param bool   $is_cache  By default caching in enabled
		 * @param string $shortcode Shortcode tag
		 */
		if ( apply_filters( 'startapp_shortcode_is_cache', true, $shortcode )
		     && (bool) absint( startapp_get_option( 'cache_is_shortcodes', 1 ) )
		) {
			$is_cache = true;
		} else {
			$is_cache = false;
		}

		if ( $is_cache ) {
			$key = startapp_get_cache_key( array(
				'post'      => get_queried_object_id(),
				'shortcode' => $shortcode,
				'atts'      => $atts,
				'content'   => $content,
			), "{$shortcode}_" );

			$cached = get_transient( $key );
			if ( false === $cached ) {
				$template = $this->locate_template( $shortcode );
				$output   = $this->load_template( $template, $atts, $content, $shortcode );

				/**
				 * Filter the expiration for shortcode cache in seconds.
				 *
				 * Default is 1 day.
				 *
				 * @param int    $expiration Time until expiration in seconds.
				 * @param string $shortcode  Shortcode name
				 */
				$expiration = apply_filters( 'startapp_shortcode_cache_expiration', 86400, $shortcode );
				$value      = startapp_content_encode( $output );
				set_transient( $key, $value, $expiration );

				unset( $template, $expiration, $value );
			} else {
				$output = startapp_content_decode( $cached );
			}

			unset( $key, $cached );
		} else {
			$template = $this->locate_template( $shortcode );
			$output   = $this->load_template( $template, $atts, $content, $shortcode );
			unset( $template );
		}

		/**
		 * Fires when output is ready
		 *
		 * @param string $shortcode Shortcode tag
		 * @param array  $atts      Shortcode attributes
		 * @param mixed  $content   Shortcode content
		 */
		do_action( 'startapp_shortcode_render_after', $shortcode, $atts, $content );

		return $output;
	}

	/**
	 * Locate the shortcode template
	 *
	 * @param string $shortcode Shortcode name
	 *
	 * @return string
	 */
	protected function locate_template( $shortcode ) {
		/**
		 * Filter the list of directories with shortcode templates
		 *
		 * @param array $dirs Directories list
		 */
		$dirs = apply_filters( 'startapp_shortcode_template_dirs', array(
			get_stylesheet_directory() . '/shortcodes',
			get_template_directory() . '/shortcodes',
			STARTAPP_CORE_ROOT . '/shortcodes/templates', // TODO: maybe use \Startapp\Core::path($path) or use concatenation
		) );

		$located = '';
		foreach ( $dirs as $dir ) {
			$dir      = rtrim( $dir, '/\\' );
			$template = "{$dir}/{$shortcode}.php";
			if ( file_exists( $template ) ) {
				$located = $template;
				// break loop after first found template
				break;
			}
		}

		return $located;
	}

	/**
	 * Load the shortcode template
	 *
	 * @param string      $template  Path to shortcode template
	 * @param array       $atts      Shortcode attributes
	 * @param string|null $content   Shortcode content
	 * @param string      $shortcode Shortcode tag
	 *
	 * @return string
	 */
	protected function load_template( $template, $atts = array(), $content = null, $shortcode = '' ) {
		ob_start();
		require $template;
		$output = ob_get_contents();
		ob_end_clean();

		return $output;
	}
}
