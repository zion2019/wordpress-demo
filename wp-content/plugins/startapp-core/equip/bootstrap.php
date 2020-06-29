<?php
/**
 * Add a "Category" module in Equip.
 * Planning to add some extra fields to categories.
 *
 * @author     8guild
 * @package    Startapp\Core
 * @subpackage Equip
 */

if ( ! defined( 'EQUIP_VERSION' ) ) {
	return;
}

// Do not execute if the current Equip version is larger than 0.7.21
if ( version_compare( EQUIP_VERSION, '0.7.21', '>=' ) ) {
	return;
}

/**
 * Add "Category" module to Equip
 *
 * @param array $map Modules map
 *
 * @return array
 */
function startapp_core_equip_modules( $map ) {
	$map['category'] = STARTAPP_CORE_ROOT . '/equip/app/modules/CategoryModule.php';

	return $map;
}

add_filter( 'equip/factory/module/map', 'startapp_core_equip_modules' );

/**
 * Change the default module class name
 *
 * @param string $class Module class name
 *
 * @return string
 */
function startapp_core_equip_category_module_class( $class ) {
	return 'CategoryModule';
}

add_filter( 'equip/factory/module/category/class', 'startapp_core_equip_category_module_class' );

/**
 * Add "Category" engine to Equip
 *
 * @param array $map Engines map
 *
 * @return array
 */
function startapp_core_equip_engines( $map ) {
	$map['category'] = STARTAPP_CORE_ROOT . '/equip/app/engines/CategoryEngine.php';

	return $map;
}

add_filter( 'equip/factory/engine/map', 'startapp_core_equip_engines' );

/**
 * Change the default engine class name
 *
 * @param string $class Engine class name
 *
 * @return string
 */
function startapp_core_equip_category_engine_class( $class ) {
	return 'CategoryEngine';
}

add_filter( 'equip/factory/engine/category/class', 'startapp_core_equip_category_engine_class' );

/**
 * Add "Category" layout to Equip
 *
 * @param array $map Layouts map
 *
 * @return array
 */
function startapp_core_equip_layouts( $map ) {
	$map['category'] = STARTAPP_CORE_ROOT . '/equip/app/layouts/CategoryLayout.php';

	return $map;
}

add_filter( 'equip/factory/layout/map', 'startapp_core_equip_layouts' );

/**
 * Change the default layout class name
 *
 * @param string $class Layout class name
 *
 * @return string
 */
function startapp_core_equip_category_layout_class( $class ) {
	return 'CategoryLayout';
}

add_filter( 'equip/factory/layout/category/class', 'startapp_core_equip_category_layout_class' );
