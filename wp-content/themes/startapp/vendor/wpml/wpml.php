<?php
/**
 * WPML Compatibility
 *
 * @author 8guild
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

if ( ! defined( 'ICL_SITEPRESS_VERSION' ) ) {
	return;
}

/**
 * Allow WPML to use it's own Language Switcher
 *
 * @hooked startapp_language_switcher_type
 * @see    startapp_the_language_switcher()
 *
 * @param string $type Type
 *
 * @return string
 */
function startapp_wpml_register_switcher( $type ) {
	return function_exists( 'wpml_get_active_languages_filter' ) ? 'wpml' : $type;
}

add_filter( 'startapp_language_switcher_type', 'startapp_wpml_register_switcher' );

if ( ! function_exists( 'startapp_wpml_switcher' ) ) :
	/**
	 * Echoes the markup of WPML Language Switcher
	 *
	 * @hooked startapp_language_switcher 10
	 * @see    startapp_the_language_switcher()
	 */
	function startapp_wpml_switcher() {
		if ( ! function_exists( 'wpml_get_active_languages_filter' ) ) {
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

		$languages = wpml_get_active_languages_filter( null );
		$current   = array_filter( $languages, function( $language ) {
			return ( 1 === (int) $language['active'] );
		} );
		$current = reset( $current );

		$languages_html = array();
		foreach ( $languages as $language ) {
			$languages_html[] = sprintf( '<li><a href="%1$s">%2$s</a></li>',
				esc_url( $language['url'] ),
				esc_html( $language['native_name'] )
			);
		}
		unset( $language );

		$r = array(
			'{current-lang}' => esc_html( $current['native_name'] ),
			'{languages}'    => implode( '', $languages_html ),
		);

		echo str_replace( array_keys( $r ), array_values( $r ), $template );
	}
endif;

add_action( 'startapp_language_switcher_wpml', 'startapp_wpml_switcher' );
