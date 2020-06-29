<?php
/**
 * Inner Row | vc_row_inner
 *
 * @var array                          $atts    Shortcode attributes
 * @var mixed                          $content Shortcode content
 * @var WPBakeryShortCode_VC_Row_Inner $this
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
	'id'        => '',
	'animation' => '',
	'class'     => '',
	'css'       => '',
), 'vc_row_inner' ), $atts );

$class = startapp_get_classes( array(
	'row',
	trim( vc_shortcode_custom_css_class( $a['css'] ) ),
	$a['class'],
) );

$attr = startapp_get_attr( array(
	'id'       => esc_attr( $a['id'] ),
	'class'    => esc_attr( $class ),
	'data-aos' => ( ! empty( $a['animation'] ) ) ? esc_attr( $a['animation'] ) : '',
) );

echo '<div ', $attr, '>';
echo startapp_do_shortcode( $content );
echo '</div>';
