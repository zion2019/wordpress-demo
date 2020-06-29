<?php
/**
 * Contacts Tile | startapp_contacts_tile
 *
 * @var string $shortcode Shortcode tag
 * @var array  $atts      Shortcode attributes
 * @var mixed  $content   Shortcode content
 *
 * @author 8guild
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/**
 * Filter the default shortcode attributes
 *
 * @param array  $atts      Pairs of default attributes
 * @param string $shortcode Shortcode tag
 */
$a = shortcode_atts( apply_filters( 'startapp_shortcode_default_atts', array(
	'flag'             => '', // attachment
	'country'          => '',
	'city'             => '',
	'type'             => 'image',
	'image'            => '',
	'map_location'     => '',
	'map_height'       => 500,
	'map_zoom'         => 14,
	'map_is_zoom'      => 'disable',
	'map_is_scroll'    => 'disable',
	'map_is_marker'    => 'disable',
	'map_marker_title' => '',
	'map_marker'       => '', // attachment ID
	'map_style'        => '', // custom base64 encoded styles
	'animation'        => '',
	'class'            => '',
), $shortcode ), $atts );

$template = <<<'TPL'
<div {attr}>
	<div class="contacts-tile-body">
		<div class="contacts-tile-country">
			{flag}
			{country}
		</div>
		{city}
		<div class="contacts-tile-content">
			{content}
		</div>
	</div>
	{map}
</div>
TPL;

// prepare map
$map = '';
if ( 'image' === $a['type'] ) {
	$map = wp_get_attachment_image( (int) $a['image'], 'full' );
} elseif ( 'map' === $a['type'] ) {
	$s = startapp_parse_array( $a, 'map_' );
	$m = startapp_shortcode_build( 'startapp_map', $s );

	$map = startapp_do_shortcode( $m );
	unset( $s, $m );
}

$class = startapp_get_classes( array(
	'contacts-tile',
	$a['class'],
) );

$attr = startapp_get_attr( array(
	'class'    => esc_attr( $class ),
	'data-aos' => ( ! empty( $a['animation'] ) ) ? esc_attr( $a['animation'] ) : '',
) );

$r = array(
	'{attr}'    => $attr,
	'{flag}'    => startapp_get_text( wp_get_attachment_image( (int) $a['flag'], 'full' ), '<span class="country-flag">', '</span>' ),
	'{country}' => startapp_get_text( esc_html( $a['country'] ), '<span class="country-name">', '</span>' ),
	'{city}'    => startapp_get_text( esc_html( $a['city'] ), '<h3 class="contacts-tile-title">', '</h3>' ),
	'{content}' => startapp_do_shortcode( $content, true ),
	'{map}'     => $map,
);

echo str_replace( array_keys( $r ), array_values( $r ), $template );
