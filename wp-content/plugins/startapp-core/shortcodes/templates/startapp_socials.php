<?php
/**
 * Socials | startapp_socials
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
	'socials'           => '',
	'type'              => 'border',
	'shape'             => 'rounded',
	'skin'              => 'dark',
	'alignment'         => 'left',
	'is_tooltips'       => 'disable',
	'tooltips_position' => 'top',
	'is_waves'          => 'disable',
	'waves_color'       => 'light',
	'animation'         => '',
	'class'             => '',
), $shortcode ), $atts );

$socials  = json_decode( urldecode( $a['socials'] ), true );
$networks = startapp_get_networks();
if ( empty( $socials ) || empty( $networks ) ) {
	return;
}

$type        = esc_attr( $a['type'] );
$shape       = esc_attr( $a['shape'] );
$skin        = esc_attr( $a['skin'] );
$alignment   = ( 'inline' === $a['alignment'] ) ? 'inline' : 'text-' . esc_attr( $a['alignment'] );
$is_tooltips = ( 'enable' === $a['is_tooltips'] );
$t_position  = esc_attr( $a['tooltips_position'] );
$is_waves    = ( 'enable' === $a['is_waves'] );
$w_color     = esc_attr( $a['waves_color'] );

// classes for div.social-bar
$w_class = startapp_get_classes( array(
	'social-bar',
	'sb-' . $type,
	'sb-' . $shape,
	'sb-' . $skin . '-skin',
	$alignment,
	$a['class'],
) );

$w_attr = startapp_get_attr( array(
	'class'    => esc_attr( $w_class ),
	'data-aos' => ( ! empty( $a['animation'] ) ) ? esc_attr( $a['animation'] ) : '',
) );

// attributes for each social network link
$attr          = array();
$attr['href']  = '{url}';
$attr['class'] = startapp_get_classes( array(
	'social-btn',
	$is_waves ? 'waves-effect' : '',
	$is_waves ? 'waves-' . $w_color : '',
) );

// may be add tooltips
if ( $is_tooltips ) {
	$attr['data-toggle']    = 'tooltip';
	$attr['data-placement'] = $t_position;
	$attr['title']          = '{name}';
}

// prepare the template for each button
$tpl = startapp_get_tag( 'a', $attr, '<i class="{icon}"></i>' );

echo '<div ', $w_attr, '>';
foreach ( $socials as $social ) {
	// skip items with undefined data
	if ( empty( $social['network'] ) || empty( $social['url'] ) ) {
		continue;
	}

	$network = $social['network'];
	$url     = $social['url'];

	if ( ! array_key_exists( $network, $networks ) ) {
		continue;
	}

	$r = array(
		'{url}'  => preg_match( '@^https?://@i', $url ) ? esc_url( $url ) : esc_attr( $url ),
		'{icon}' => esc_attr( $networks[ $network ]['icon'] ),
		'{name}' => esc_attr( $networks[ $network ]['name'] ),
	);

	echo str_replace( array_keys( $r ), array_values( $r ), $tpl );
}
echo '</div>';
