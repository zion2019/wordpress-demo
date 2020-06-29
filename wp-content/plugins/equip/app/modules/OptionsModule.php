<?php

namespace Equip\Module;

use Equip\Equip;
use Equip\Factory;
use Equip\Storage;

/**
 * Creates the Options Pages
 *
 * @author  8guild
 * @package Equip\Module
 */
class OptionsModule {

	/**
	 * Constructor
	 */
	public function __construct() {
		add_action( 'admin_menu', [ $this, 'add' ] );
		add_action( 'admin_init', [ $this, 'register' ] );
		add_action( 'wp_ajax_equip_save_options', [ $this, 'save' ] );
		add_action( 'wp_ajax_equip_reset_options', [ $this, 'reset' ] );
		add_action( 'equip/options/saved', [ $this, 'flush' ] );
		add_action( 'equip/options/reseted', [ $this, 'flush' ] );
	}

	/**
	 * Add options to storage
	 *
	 * @param string               $slug
	 * @param \Equip\layout\Layout $layout
	 * @param array                $args
	 */
	public function store( $slug, $layout, $args = [] ) {
		if ( empty( $slug ) ) {
			return;
		}

		$args = wp_parse_args( $args, $this->get_defaults() );

		// use the menu_slug as key, because during
		// the page rendering this is the only info we have
		Storage::add( [
			'slug'    => $slug,
			'layout'  => $layout,
			'args'    => $args,
			'module'  => Equip::OPTIONS,
			'pattern' => 'module.args[menu_slug].slug'
		] );
	}

	/**
	 * Get options from storage in format [menu_slug => $element]
	 *
	 * @param string $key Menu slug
	 *
	 * @todo reveal by slug
	 * @todo reveal by [menu_slug => '', slug => ''], search see bottomline library
	 *
	 * @return array
	 */
	public function reveal( $key = '' ) {
		$options = Storage::find( 'module.menu_slug', [
			'module'    => Equip::OPTIONS,
			'menu_slug' => $key,
		] );

		if ( empty( $options ) ) {
			return [];
		}

		// convert to [menu_slug => $element]
		$converted = [];
		/** @var \Equip\Misc\StorageElement $element */
		foreach ( $options as $element ) {
			$args      = $element->getArgs();
			$menu_slug = $args['menu_slug'];

			$converted[ $menu_slug ] = $element;
		}

		return $converted;
	}

	/**
	 * Add page: top-level or sub-menu
	 */
	public function add() {
		$options = $this->reveal();

		/** @var \Equip\Misc\StorageElement $element */
		foreach ( $options as $element ) {
			$page = $element->getArgs();

			if ( ! empty( $page['parent_slug'] ) ) {
				add_submenu_page(
					$page['parent_slug'],
					$page['page_title'],
					$page['menu_title'],
					$page['capability'],
					$page['menu_slug'],
					[ $this, 'render' ]
				);
			} else {
				add_menu_page(
					$page['page_title'],
					$page['menu_title'],
					$page['capability'],
					$page['menu_slug'],
					[ $this, 'render' ],
					$page['icon_url'],
					$page['position']
				);
			}
		}
	}

	/**
	 * Return options
	 *
	 * If $field is specified will return the field's value
	 *
	 * @param string $slug    Option name
	 * @param string $field   Key of the field
	 * @param mixed  $default Default value
	 *
	 * @return array|null
	 */
	public function get( $slug, $field = 'all', $default = [] ) {
		if ( function_exists( 'is_multisite' ) && is_multisite() ) {
			$site_id   = get_current_blog_id();
			$cache_key = equip_cache_key( $slug, $site_id );
			unset( $site_id );
		} else {
			$cache_key = equip_cache_key( $slug );
		}

		$cache_group = equip_cache_group( Equip::OPTIONS );
		$options     = wp_cache_get( $cache_key, $cache_group );
		if ( false === $options ) {
			$options = get_option( $slug );
			if ( empty( $options ) ) {
				// options was not saved yet
				return $default;
			}

			// cache for 1 day
			wp_cache_set( $cache_key, $options, $cache_group, 86400 );
		}

		if ( ! is_array( $options ) || 'all' === $field ) {
			// return AS IS for non-array values
			// or whole array if $field not specified
			$result = $options;
		} elseif ( array_key_exists( $field, $options ) ) {
			$result = $options[ $field ];
		} else {
			// nothing matched, return default value
			$result = $default;
		}

		return $result;
	}

	/**
	 * Flush cache after saving the options
	 *
	 * @param string $slug Element name
	 */
	public function flush( $slug ) {
		if ( function_exists( 'is_multisite' ) && is_multisite() ) {
			$site_id   = get_current_blog_id();
			$cache_key = equip_cache_key( $slug, $site_id );
			unset( $site_id );
		} else {
			$cache_key = equip_cache_key( $slug );
		}

		$cache_group = equip_cache_group( Equip::OPTIONS );

		wp_cache_delete( $cache_key, $cache_group );
	}

	/**
	 * Implement the Settings API
	 */
	public function register() {
		$options = $this->reveal();

		/** @var \Equip\Misc\StorageElement $element */
		foreach ( (array) $options as $menu_slug => $element ) {
			$slug = $element->getSlug();
			$page = $element->getArgs();

			// make sure option exists to prevent multiple sanitizing
			if ( false === get_option( $slug ) ) {
				add_option( $slug, array() );
			}

			// register a single settings section per every page
			// "id" and "page" should match the menu_slug option
			add_settings_section( "{$menu_slug}_section", $page['menu_title'], null, $menu_slug );

			// register the setting
			// "option_group" should match the menu_slug for
			// compatibility with default settings pages
			register_setting( "{$menu_slug}_group", $slug );

			// add single field per option
			add_settings_field(
				$slug,
				$page['menu_title'],
				'__return_false',
				$menu_slug,
				'equip_settings',
				$element // pass StorageElement as extra args
			);

			// Extensions loader for Options element
			if ( array_key_exists( 'extensions', $page ) && ! empty( $page['extensions'] ) ) {
				foreach ( (array) $page['extensions'] as $extension ) {
					if ( empty( $extension['callback'] ) || ! is_callable( $extension['callback'] ) ) {
						continue;
					}

					call_user_func( $extension['callback'], empty( $extension['settings'] ) ? [] : $extension['settings'] );
				}
			}
		}
	}

	/**
	 * Engine the Options Page
	 */
	public function render() {
		$key     = isset( $_GET['page'] ) ? $_GET['page'] : 'equip';
		$options = $this->reveal( $key );
		if ( empty( $options ) ) {
			return;
		}

		/** @var \Equip\Misc\StorageElement $element */
		$element = reset( $options );

		$slug   = $element->getSlug();
		$layout = $element->getLayout();
		$values = get_option( $slug );

		$engine = Factory::engine( $layout );
		$engine->render( $slug, $layout, $values );
	}

	/**
	 * Save options
	 */
	public function save() {
		// check the nonce
		if ( ! array_key_exists( 'equip_save_nonce', $_POST )
		     || ! wp_verify_nonce( $_POST['equip_save_nonce'], 'equip_save_options' )
		) {
			wp_send_json_error( esc_html__( 'Bad nonce', 'equip' ) );
		}

		$options = $this->reveal();
		if ( empty( $options ) ) {
			return;
		}

		// get the slug
		$slug = $this->detect_slug();
		// if slug not provided just exit silently
		if ( empty( $slug ) ) {
			return;
		}

		// check capabilities
		if ( ! current_user_can( 'edit_theme_options' ) ) {
			wp_send_json_error( esc_html__( 'You don\'t have sufficient permissions to edit options', 'equip' ) );
		}

		// get current slug data
		$d = array_filter( array_values( $options ), function ( $o ) use ( $slug ) {
			return ( $slug === $o->getSlug() );
		} );

		/** @var \Equip\Misc\StorageElement $element */
		$element = reset( $d );
		$values  = $_POST[ $slug ];
		unset( $d );

		/**
		 * Fires before the option will be saved
		 *
		 * @param string                     $slug    Element name
		 * @param mixed                      $values  Raw values
		 * @param \Equip\Misc\StorageElement $element Storage element
		 */
		do_action( 'equip/options/save', $slug, $values, $element );

		/**
		 * @var \Equip\Service\Sanitizer $sanitizer
		 */
		$sanitizer = Factory::service( Equip::SANITIZER );
		$sanitized = $sanitizer->bulk_sanitize( $values, $element->getLayout(), $slug );
		unset( $sanitizer );

		update_option( $slug, $sanitized );

		/**
		 * Fires when the option already saved
		 * only for provided $slug
		 *
		 * @param mixed                      $sanitized Raw values
		 * @param \Equip\Misc\StorageElement $element   Storage element
		 */
		do_action( "equip/options/{$slug}/saved", $sanitized, $element );

		/**
		 * Fires when the options already saved
		 *
		 * @param string                     $slug      Element name
		 * @param mixed                      $sanitized Already sanitized and updated values
		 * @param \Equip\Misc\StorageElement $element   Storage element
		 */
		do_action( 'equip/options/saved', $slug, $sanitized, $element );

		wp_send_json_success();
	}

	/**
	 * Reset options
	 */
	public function reset() {
		// check the nonce
		if ( ! array_key_exists( 'nonce', $_POST )
		     || ! wp_verify_nonce( $_POST['nonce'], 'equip_reset_options' )
		) {
			wp_send_json_error( __( 'Bad nonce', 'equip' ) );
		}

		// get the slug
		$slug = $this->detect_slug();
		// if slug not provided just exit silently
		if ( empty( $slug ) ) {
			return;
		}

		// TODO: reveal( [slug=>$slug] );
		$options = $this->reveal();
		$d       = array_filter( array_values( $options ), function ( $o ) use ( $slug ) {
			return ( $slug === $o->getSlug() );
		} );

		/** @var \Equip\Misc\StorageElement $element */
		$element = reset( $d );

		// check capabilities
		if ( ! current_user_can( 'edit_theme_options' ) ) {
			wp_send_json_error( __( 'You don\'t have sufficient permissions to edit options', 'equip' ) );
		}

		if ( 'section' === $_POST['reset'] ) {
			// remove fields inside the section
			// TODO: array_map | equip_sanitize_key
			$keys    = (array) $_POST['keys'];
			$options = get_option( $slug, [] );
			$options = array_diff_key( $options, array_flip( $keys ) );
			update_option( $slug, $options );
		} else {
			delete_option( $slug );
		}

		/**
		 * Fires when the options already reseted
		 *
		 * @param \Equip\Misc\StorageElement $element Storage element
		 */
		do_action( "equip/options/{$slug}/reseted", $element );

		/**
		 * Fires when options already reseted
		 *
		 * For all options
		 *
		 * @param string                     $slug    Element name
		 * @param \Equip\Misc\StorageElement $element Element storage item
		 */
		do_action( 'equip/options/reseted', $slug, $element );

		wp_send_json_success();
	}

	/**
	 * Returns the default arguments for settings page
	 *
	 * @return array
	 */
	private function get_defaults() {
		return [
			'parent_slug' => null,
			'page_title'  => '',
			'menu_title'  => '',
			'capability'  => 'manage_options',
			'menu_slug'   => 'equip',
			'icon_url'    => '',
			'position'    => null,
		];
	}

	/**
	 * Detect the slug in _POST
	 *
	 * @return null|string
	 */
	private function detect_slug() {
		// check the _POST first
		if ( array_key_exists( 'slug', $_POST ) ) {
			// TODO: equip_sanitize_slug
			$slug = $_POST['slug'];
		} else {
			$options = $this->reveal();
			if ( empty( $options ) ) {
				return null;
			}

			$slug = null;
			$keys = array_keys( $_POST );
			foreach ( $options as $k => $o ) {
				if ( in_array( $o['slug'], $keys ) ) {
					$slug = $o['slug'];
					break;
				}
			}
			unset( $k, $o, $keys );
		}

		return $slug;
	}
}