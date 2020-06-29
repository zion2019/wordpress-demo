<?php
/**
 * Polylang compatibility
 *
 * @author 8guild
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

if ( ! class_exists( 'Polylang', false ) ) {
	return;
}

/**
 * Returns the strings from Theme Options which
 * should be translated
 *
 * @return array
 */
function startapp_polylang_translatable_strings() {
	return array(
		'header_topbar_info'      => array( 'name' => esc_html__( 'Additional Info', 'startapp' ), 'multiline' => true ),
		'header_contacts_info'    => array( 'name' => esc_html__( 'Contact Info', 'startapp' ), 'multiline' => true ),
		'header_contacts_address' => array( 'name' => esc_html__( 'Address', 'startapp' ), 'multiline' => true ),
		'header_contacts_time'    => array( 'name' => esc_html__( 'Time', 'startapp' ), 'multiline' => true ),
		'footer_copyright'        => array( 'name' => esc_html__( 'Copyright Text', 'startapp' ), 'multiline' => true ),
	);
}

/**
 * Register the strings in Polylang.
 *
 * You can translate them in Settings > Languages > Strings translations
 */
function startapp_polylang_register_strings() {
	if ( ! function_exists( 'pll_register_string' ) ) {
		return;
	}

	$options = startapp_get_option( 'all' );
	$context = esc_html__( 'StartApp', 'startapp' );
	$strings = (array) startapp_polylang_translatable_strings();

	foreach ( $strings as $option => $string ) {
		pll_register_string( $string['name'], empty( $options[ $option ] ) ? '' : $options[ $option ], $context, $string['multiline'] );
	}
}

add_action( 'admin_init', 'startapp_polylang_register_strings' );

/**
 * Translate the previously registered string
 *
 * @param mixed  $value  String value
 * @param string $string String key
 *
 * @return string
 */
function startapp_polylang_translate_string( $value, $string ) {
	if ( ! function_exists( 'pll__' ) ) {
		return $value;
	}

	$strings = startapp_polylang_translatable_strings();
	$strings = array_keys( $strings );

	if ( ! in_array( $string, $strings, true ) ) {
		return $value;
	}

	return pll__( $value );
}

add_filter( 'startapp_get_option', 'startapp_polylang_translate_string', 10, 2 );

/**
 * Allow Polylang to use it's own Language Switcher
 *
 * @hooked startapp_language_switcher_type
 * @see    startapp_the_language_switcher()
 *
 * @param string $type Type
 *
 * @return string
 */
function startapp_polylang_register_switcher( $type ) {
	return function_exists( 'pll_the_languages' ) ? 'polylang' : $type;
}

add_filter( 'startapp_language_switcher_type', 'startapp_polylang_register_switcher' );

if ( ! function_exists( 'startapp_polylang_switcher' ) ) :
	/**
	 * Echoes the markup of Polylang Language Switcher
	 *
	 * @hooked startapp_language_switcher 10
	 * @see    startapp_the_language_switcher()
	 */
	function startapp_polylang_switcher() {
		// make sure all required functions exists
		if ( ! function_exists( 'pll_the_languages' ) || ! function_exists( 'pll_current_language' ) ) {
			return;
		}

		$template = '
		<div class="lang-switcher">
            <span>
                <i class="material-icons language"></i> {current-lang}
            </span>
			<ul class="lang-dropdown">
				{languages}
			</ul>
		</div>';

		$r = array(
			'{current-lang}' => pll_current_language( 'name' ),
			'{languages}'    => pll_the_languages( array( 'echo' => false ) ),
		);

		echo str_replace( array_keys( $r ), array_values( $r ), $template );
	}
endif;

add_action( 'startapp_language_switcher_polylang', 'startapp_polylang_switcher' );
