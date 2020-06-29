<?php
/**
 * Icon | vc_icon
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
	'type'              => 'icon',
	'icon_library'      => 'fontawesome',
	'icon_fontawesome'  => '',
	'icon_material'     => '',
	'icon_custom'       => '',
	'icon_color'        => 'default',
	'icon_color_custom' => '',
	'image'             => '',
	'size'              => 24,
	'alignment'         => 'left',
	'animation'         => '',
	'class'             => '',
), 'vc_icon' ), $atts );

$is_icon  = ( 'icon' === $a['type'] );
$is_image = ( 'image' === $a['type'] );
$is_size  = ( ! empty( $a['size'] ) && is_numeric( $a['size'] ) );

$class = startapp_get_classes( array(
	'vc-icon',
	'vc-icon-type-' . esc_attr( $a['type'] ),
	$is_icon ? 'text-' . sanitize_key( $a['icon_color'] ) : '',
	'text-' . sanitize_key( $a['alignment'] ),
	$a['class'],
) );

switch ( $a['type'] ) {
	case 'icon':
		$l = esc_attr( $a['icon_library'] );
		$s = array();
		$i = array();

		if ( 'custom' === $a['icon_color'] ) {
			$s['color'] = esc_attr( $a['icon_color_custom'] );
		}

		if ( $is_size ) {
			$s['font-size'] = absint( $a['size'] ) . 'px';
		}

		// enqueue the stylesheet
		startapp_vc_enqueue_icon_font( $l );

		$i['class'] = esc_attr( $a["icon_{$l}"] );
		$i['style'] = startapp_css_declarations( $s );

		$icon = startapp_get_tag( 'i', $i, '' );
		unset( $l, $s, $i );
		break;

	case 'image':
		$s    = $is_size ? array( 'style' => startapp_css_width( (int) $a['size'] ) ) : '';
		$icon = wp_get_attachment_image( (int) $a['image'], 'full', false, $s );
		unset( $s );
		break;

	default:
		$icon = '&nbsp;';
		break;
}

$attr = array();

$attr['class']    = esc_attr( $class );
$attr['data-aos'] = ( ! empty( $a['animation'] ) ) ? esc_attr( $a['animation'] ) : '';

echo startapp_get_tag( 'div', $attr, $icon );
