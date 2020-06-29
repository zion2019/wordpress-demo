<?php
/**
 * Equip patch.
 *
 * Add a compatibility layer to support tabs
 * until users upgrade to the latest version.
 *
 * @author 8Guild
 */

if ( ! defined( 'EQUIP_VERSION' ) ) {
	return;
}

// Do not execute if the current Equip version is larger than 0.7.21
if ( version_compare( EQUIP_VERSION, '0.7.21', '>=' ) ) {
	return;
}

add_filter( 'equip/factory/engine/map', 'startapp_equip_engines', 20 );
add_filter( 'equip/factory/layout/map', 'startapp_equip_layouts', 20 );

/**
 * Add engine to Equip
 *
 * @param array $map Engines map
 *
 * @return array
 */
function startapp_equip_engines( $map ) {
	$map['tab'] = __DIR__ . '/engines/TabEngine.php';

	return $map;
}

/**
 * Add "Category" layout to Equip
 *
 * @param array $map Layouts map
 *
 * @return array
 */
function startapp_equip_layouts( $map ) {
	$map['tab']     = __DIR__ . '/layouts/TabLayout.php';
	$map['metabox'] = __DIR__ . '/layouts/MetaboxLayout.php';

	return $map;
}
