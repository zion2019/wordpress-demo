<?php
/**
 * Inner Column | vc_column_inner
 *
 * @var array                    $atts    Shortcode attributes
 * @var mixed                    $content Shortcode content
 * @var WPBakeryShortCode_VC_Row $this    Instance of a class
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
	'width'     => '1/1',
	'offset'    => '',
	'animation' => '',
	'class'     => '',
	'css'       => '',
), 'vc_column_inner' ), $atts );

$class = startapp_get_classes( array(
	startapp_vc_column_class( $a['width'], $a['offset'] ),
	trim( vc_shortcode_custom_css_class( $a['css'] ) ),
	$a['class'],
) );

$attr = startapp_get_attr( array(
	'class'    => esc_attr( $class ),
	'data-aos' => ( ! empty( $a['animation'] ) ) ? esc_attr( $a['animation'] ) : '',
) );

echo '<div ', $attr, '>';
echo startapp_do_shortcode( $content );
echo '</div>';
