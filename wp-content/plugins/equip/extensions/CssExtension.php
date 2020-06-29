<?php

namespace Equip\Extension;

use \Leafo\ScssPhp\Compiler;

/**
 * Extension for compiling the CSS.
 * Required the leafo/scssphp library to compile SASS to CSS.
 *
 * @author  8guild
 * @package Equip\Extension
 */
class CssExtension {

	private static $instance = null;

	protected $settings = [];
	protected $fields = [];
	protected $defaults = [];

	/**
	 * Setup the extension
	 *
	 * @param array $settings
	 *
	 * @return CssExtension
	 */
	public static function setup( $settings = [] ) {
		if ( null === self::$instance ) {
			self::$instance = new self( $settings );
		}

		return self::$instance;
	}

	/**
	 * Constructor
	 *
	 * @param array $settings
	 */
	public function __construct( $settings = [] ) {
		$this->settings = $this->parseSettings( $settings );

		add_action( 'equip/options/saved', array( $this, 'compile' ), 10, 3 );
		add_action( 'equip/options/reseted', array( $this, 'reset' ), 10, 2 );
	}

	/**
	 * Compile the CSS
	 *
	 * Note, all code stored in CSS variable is SASS.
	 * So, leafo/scssphp compiler is used to compile that code to CSS.
	 *
	 * @param string                     $slug      Element name
	 * @param mixed                      $sanitized Already sanitized and updated values
	 * @param \Equip\Misc\StorageElement $element   Storage element
	 *
	 * @return bool
	 */
	public function compile( $slug, $sanitized, $element ) {
		/**
		 * This filter allows to disable CSS compiling
		 *
		 * Default is FALSE which means "Do not disable CSS compilation"
		 *
		 * @example add_filter( 'equip/extension/css/disable', '__return_true' );
		 *
		 * @params  bool $is_compile Enable or disable compilation
		 */
		if ( true === apply_filters( 'equip/extension/css/disable', false ) ) {
			return false;
		}

		$this->fields = $this->getFields( $element->getLayout() );
		if ( empty( $this->fields ) ) {
			return false;
		}

		$this->defaults = $this->getDefaults();

		// Get only options affected the front end.
		$options = array_intersect_key( $sanitized, array_flip( array_keys( $this->fields ) ) );

		if ( ! $this->isCompile( $options ) ) {
			return false;
		}

		// Save the current state
		// to be able to compare options with previous state next time
		$this->updateOptionsCopy( $options );

		// Collect the SASS code
		$sass = [];
		$vars = [];
		foreach ( $this->fields as $key => $field ) {
			$option = $field->get_setting( 'css' );

			// do not add elements to collection if vars or code is missing
			if ( empty( $option['vars'] ) || empty( $option['code'] ) ) {
				continue;
			}

			$value = array_key_exists( $key, $options ) ? $options[ $key ] : $this->defaults[ $key ];
			if ( ! is_array( $value ) ) {
				// compound values does not support "prepend" and "append"
				// TODO: fix this
				if ( ! empty( $option['prepend'] ) ) {
					$value = $option['prepend'] . $value;
				}

				if ( ! empty( $option['append'] ) ) {
					$value = $value . $option['append'];
				}
			}

			if ( ! empty( $option['single'] ) ) {
				// in case when we need only the single key from a compound $value for compiling SASS
				$vars[ $option['vars'] ] = (string) $value[ $option['single'] ];
			} elseif ( is_array( $option['vars'] ) ) {
				// in other cases check if option has more that one variable (for compound options)
				$vars = array_merge( $vars, array_combine( $option['vars'], (array) $value ) );
			} else {
				$vars[ $option['vars'] ] = (string) $value;
			}

			$sass[ $key ] = $option['code']; // sass code

			unset( $option, $value );
		}
		unset( $key, $field );

		try {
			$scss = new Compiler();
			$scss->setVariables( $vars );
			$scss->setFormatter( '\Leafo\ScssPhp\Formatter\Crunched' );

			$css = $scss->compile( implode( '', $sass ) );
		} catch ( \Exception $e ) {
			trigger_error( $e->getMessage() );
			$css = false;
		}

		// Save option
		$this->updateCSS( $css );

		return true;
	}

	/**
	 * Reset the compiled option
	 *
	 * @param string                     $slug    Element name
	 * @param \Equip\Misc\StorageElement $element Storage element
	 *
	 * @return bool
	 */
	public function reset( $slug, $element ) {
		delete_option( $this->settings['option'] );
		delete_option( $this->settings['option_copy'] );

		return true;
	}

	/**
	 * Check if compilation is required
	 *
	 * As compiling is a very resource intensive operation,
	 * check if any options that are affect the front-end was changed.
	 *
	 * @param array $options Saved theme options
	 *
	 * @return bool
	 */
	protected function isCompile( $options ) {
		// Get options copy to compare with previous state
		// If compiler run for a first time get defaults
		$previous = $this->getOptionsCopy();
		if ( empty( $previous ) ) {
			$previous = $this->defaults;
		}

		// just in case
		ksort( $previous, SORT_STRING );
		ksort( $options, SORT_STRING );

		$affected = [];
		foreach ( $options as $key => $value ) {
			if ( array_key_exists( $key, $previous ) && $value != $previous[ $key ] ) {
				$affected[ $key ] = $value;
			}
		}

		return ( count( $affected ) > 0 );
	}

	/**
	 * Get CSS settings
	 *
	 * @param array $settings
	 *
	 * @return array
	 */
	protected function parseSettings( $settings ) {
		return wp_parse_args( $settings, [
			'option'      => 'equip_compiled',
			'option_copy' => 'equip_compiled_copy',
		] );
	}

	/**
	 * Get only fields from the layout
	 *
	 * This step has a special meaning:
	 * only fields with required settings will be added to a collection.
	 * So, this array will containing only fields affected the front end.
	 *
	 * @param \Equip\Layout\Layout $layout
	 *
	 * @return array
	 */
	protected function getFields( $layout ) {
		$fields       = equip_layout_get_fields( $layout );
		$customizable = [];

		/** @var \Equip\Layout\FieldLayout $field */
		foreach ( $fields as $key => $field ) {
			$css = $field->get_setting( 'css' );
			if ( empty( $css ) ) {
				continue;
			}

			$customizable[ $key ] = $field;
		}

		return $customizable;
	}

	/**
	 * Returns the "options copy"
	 *
	 * @return mixed
	 */
	protected function getOptionsCopy() {
		return get_option( $this->settings['option_copy'] );
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
	 * Save the compiled CSS to a database
	 *
	 * This option will be checked on frontend during loading
	 * and outputs its value.
	 *
	 * @param string $css
	 */
	protected function updateCSS( $css ) {
		if ( false === get_option( $this->settings['option'] ) ) {
			add_option( $this->settings['option'], '', '', 'no' );
		}

		// store to the database
		update_option( $this->settings['option'], $css, 'no' );
	}

	/**
	 * Get default values for fields in format [key => default value]
	 *
	 * @return array
	 */
	protected function getDefaults() {
		$defaults = [];

		/** @var \Equip\Layout\FieldLayout $field */
		foreach ( $this->fields as $key => $field ) {
			$defaults[ $key ] = $field->get_setting( 'default', null );
		}

		return $defaults;
	}
}
