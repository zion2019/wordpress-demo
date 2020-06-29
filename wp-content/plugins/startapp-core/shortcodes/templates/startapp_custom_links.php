<?php
/**
 * Custom Links | startapp_custom_links
 *
 * @var string $shortcode Shortcode tag
 * @var array  $atts      Shortcode attributes
 * @var mixed  $content   Shortcode content
 *
 * @author 8guild
 */

if ( ! defined( 'ABSPATH' ) ) {
	die;
}

/**
 * Filter the default shortcode attributes
 *
 * @param array  $atts      Pairs of default attributes
 * @param string $shortcode Shortcode tag
 */
$a = shortcode_atts( apply_filters( 'startapp_shortcode_default_atts', array(
	'animation' => '',
	'class'     => '',
), $shortcode ), $atts );

if ( empty( $content ) ) {
	return;
}

$links  = json_decode( urldecode( $content ), true );
$output = array();

foreach ( $links as $link ) {
	// make sure all keys are present
	$link = wp_parse_args( $link, array(
		'uptitle' => '',
		'link'    => '',
	) );

	if ( empty( $link['link'] ) ) {
		continue;
	}

	$_link = startapp_vc_parse_link( $link['link'] );
	if ( empty( $_link['url'] ) ) {
		continue;
	}

	$tag_text = empty( $_link['title'] ) ? '' : esc_html( trim( $_link['title'] ) );
	$tag_attr = array(
		'href'   => esc_url( trim( $_link['url'] ) ),
		'target' => esc_attr( trim( $_link['target'] ) ),
		'rel'    => esc_attr( trim( $_link['rel'] ) ),
	);

	$uptitle  = startapp_get_text( esc_html( $link['uptitle'] ), '<span>', '</span>' );
	$link_tag = startapp_get_tag( 'a', $tag_attr, $tag_text );

	$output[] = '<li>' . $uptitle . $link_tag . '</li>';
	unset( $_link, $tag_text, $tag_attr, $uptitle, $link_tag );
}
unset( $link );

if ( empty( $output ) ) {
	return;
}

$class = startapp_get_classes( array(
	'custom-links',
	$a['class'],
) );

$attr = startapp_get_attr( array(
	'class'    => esc_attr( $class ),
	'data-aos' => ( ! empty( $a['animation'] ) ) ? esc_attr( $a['animation'] ) : '',
) );

echo sprintf( '<div %1$s><ul>%2$s</ul></div>', $attr, implode( '', $output ) );
