<?php

namespace Equip;

use Equip\Field\Field;
use Equip\Layout\Layout;
use Equip\Engine\Engine;
use Equip\Module;
use Equip\Service;

/**
 * Class Factory
 *
 * @author  8guild
 * @package Equip
 */
class Factory {

	/**
	 * Contains module instances where key is a module name
	 *
	 * @var array
	 */
	private static $modules = [];

	/**
	 * Contains engine instances where key is an engine type
	 *
	 * @var array
	 */
	private static $engines = [];

	/**
	 * Contains field instances where key is a field type
	 *
	 * @var array
	 */
	private static $fields = [];

	/**
	 * Contains service instances where key is a service name
	 *
	 * @var array
	 */
	private static $services = [];

	public static function module( $module ) {

		// do not duplicate modules
		if ( array_key_exists( $module, self::$modules ) ) {
			return self::$modules[ $module ];
		}

		$class = apply_filters( "equip/factory/module/{$module}/class", '\\Equip\\Module\\' . ucfirst( $module ) . 'Module' );

		$map = apply_filters( 'equip/factory/module/map', [] );
		if ( empty( $map ) ) {
			trigger_error( 'Modules map is empty' );
			exit;
		}

		$path = $map[ $module ];

		if ( ! is_readable( $path ) ) {
			trigger_error( "Module {$module} not found in {$path}" );
		}

		require $path;

		self::$modules[ $module ] = new $class();

		return self::$modules[ $module ];
	}

	/**
	 * Return the instance of engine, based on engine type.
	 * Also it is possible to pass an object of Engine.
	 *
	 * @param string|Layout|Engine $engine Engine
	 *
	 * @return Engine Instance of class
	 */
	public static function engine( $engine ) {
		// return as is
		if ( $engine instanceof Engine ) {
			return $engine;
		}

		if ( $engine instanceof Layout ) {
			$engine = $engine->type;
		}

		// TODO: refactoring required here
		if ( empty( $engine ) ) {
			/**
			 * Filter the default rendering engine. By default is "default" engine :)
			 *
			 * @param string $engine Default engine
			 */
			$engine = (string) apply_filters( 'equip/factory/engine/default', 'default' );
		} else {
			$engine = strtolower( trim( $engine ) );
		}

		/*
		 * Engine instances are storing by type, not class names.
		 */
		if ( array_key_exists( $engine, self::$engines ) ) {
			return self::$engines[ $engine ];
		}

		/**
		 * Filter the engine class name. Fires only for given engine.
		 *
		 * @param string $class Class name. Default is "{$engine}Engine" with first capitalized.
		 */
		$class = apply_filters( "equip/factory/engine/{$engine}/class", '\\Equip\\Engine\\' . ucfirst( $engine ) . 'Engine' );

		/**
		 * Filter the engine class name. Fires for every engine.
		 *
		 * @param string $class  Class name
		 * @param string $engine Current engine type
		 */
		//$class = apply_filters( 'equip/factory/engine/class', $class, $engine );

		/**
		 * Filter the engines map.
		 *
		 * This filters expects the array where key is engine type
		 * and value is an absolute path to engine class.
		 *
		 * @param array $map Engines map
		 */
		$map = apply_filters( 'equip/factory/engine/map', array() );
		if ( empty( $map ) ) {
			trigger_error( 'Equip::engine(): engine maps should not be empty.' );
		}

		if ( ! array_key_exists( $engine, $map ) ) {
			trigger_error( "Equip::engine(): engine {$engine} not found in map." );
		}

		$path = $map[ $engine ];
		if ( ! is_readable( $path ) ) {
			trigger_error( "Equip::engine(): path {$path} is not readable or file not exists." );
		}

		// finally load a file
		require $path;

		// and create an instance
		self::$engines[ $engine ] = new $class();

		return self::$engines[ $engine ];
	}

	/**
	 * Return the instance of field, depending on field type
	 *
	 * @param array $settings Field settings
	 *
	 * @return \Equip\Field\Field Instance of class or {@see exit}
	 */
	public static function field( $settings ) {
		if ( null === $settings ) {
			trigger_error( 'Equip::field(): can\'t create field object from nothing.' );
			exit( 'Equip::field(): can\'t create field object from nothing.' );
		}

		if ( ! array_key_exists( 'field', $settings ) ) {
			trigger_error( 'Equip::field(): can\'t create field object. Key "field" is absent in field settings.' );
			exit( 'Equip::field(): can\'t create field object. Key "field" is absent in field settings.' );
		}

		// If Field object passed return AS IS
		if ( $settings['field'] instanceof Field ) {
			return $settings['field'];
		}

		$field = strtolower( trim( $settings['field'] ) );

		/*
		 * In my opinion it is a great idea to store field objects
		 * by types, not by their class names. In this case objects
		 * won't be affected by class names, because class name can
		 * be changed.
		 */
		if ( array_key_exists( $field, self::$fields ) ) {
			return self::$fields[ $field ];
		}

		/**
		 * Filter the fields map.
		 *
		 * This filter expects the array where key is a field type,
		 * and value is an absolute path to field class.
		 *
		 * @param array $map Fields map
		 */
		$map = apply_filters( 'equip/factory/field/map', array() );
		if ( empty( $map ) ) {
			trigger_error( 'Equip::field(): can\'t create field object. Fields $map is empty.' );
			exit( 'Equip::field(): can\'t create field object. Fields $map is empty.' );
		}

		if ( ! array_key_exists( $field, $map ) ) {
			trigger_error( "Equip::field(): field {$field} not found in \$map." );
			exit( "Field \"{$field}\" not found in \$map." );
		}

		$path = $map[ $field ];
		if ( ! is_readable( $path ) ) {
			trigger_error( "Equip::field(): path {$path} is not readable or file not exists." );
			exit( "Equip::field(): path {$path} is not readable or file not exists." );
		}

		// require the file
		require $path;

		// prepare the class name
		$class = equip_sanitize_class( $field );
		$class = '\\Equip\\Field\\' . $class . 'Field';

		/**
		 * Filter the class name of given field by its type.
		 *
		 * Field type is the string, passed as "field" in field settings.
		 *
		 * @param string $class Class name. Default is: "{$field}Field" with first capitalized.
		 */
		$class = apply_filters( "equip/factory/field/{$field}/class", $class );

		/**
		 * Filter the class name for given field by it's type.
		 *
		 * Fires for all fields
		 *
		 * @param string $class Class name
		 * @param string $field Field type. This is the string, passed as "field" key in field settings.
		 */
		$class = apply_filters( 'equip/factory/field/class', $class, $field );

		// and store it for further usage
		self::$fields[ $field ] = new $class();

		return self::$fields[ $field ];
	}

	/**
	 * @param string $layout
	 * @param array  $args
	 *
	 * @return \Equip\layout\layout
	 */
	public static function layout( $layout, $args = [] ) {

		$class = apply_filters( "equip/factory/layout/{$layout}/class", '\\Equip\\Layout\\' . ucfirst( $layout ) . 'Layout' );
		$map   = apply_filters( 'equip/factory/layout/map', [] );
		if ( empty( $map ) ) {
			trigger_error( 'Layouts map is empty' );
			exit;
		}

		$path = $map[ $layout ];
		if ( ! is_readable( $path ) ) {
			trigger_error( "Layout {$layout} not found in {$path}" );
			exit;
		}

		require_once $path;

		/*
		if ( version_compare( PHP_VERSION, '5.6.0' ) >= 0 ) {
			// argument unpacking is available in PHP 5.6+
			return new $class( ...$args );
		}
		*/

		// TODO: add try..catch
		// TODO: test with wrong class name
		$reflection = new \ReflectionClass( $class );
		$instance   = $reflection->newInstanceArgs( $args );

		return $instance;
	}

	/**
	 * @param string $service
	 *
	 * @return mixed
	 */
	public static function service( $service ) {

		if ( array_key_exists( $service, self::$services ) ) {
			return self::$services[ $service ];
		}

		$map = apply_filters( 'equip/factory/service/map', [] );
		if ( empty( $map ) ) {
			trigger_error( 'Service map is empty' );
			exit;
		}

		if ( ! array_key_exists( $service, $map ) ) {
			trigger_error( "Service {$service} not found in map" );
			exit;
		}

		$path = $map[ $service ];
		if ( ! is_readable( $path ) ) {
			trigger_error( "Service {$service} not found in {$path}" );
			exit;
		}

		require $path;

		$class = apply_filters( "equip/factory/service/{$service}/class", '\\Equip\\Service\\' . ucfirst( $service ) );

		self::$services[ $service ] = new $class();

		return self::$services[ $service ];
	}
}