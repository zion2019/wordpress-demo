<?php
/**
 * Button | startapp_button
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
	'text'             => '',
	'link'             => '',
	'type'             => 'solid',
	'shape'            => 'rounded',
	'color'            => 'default',
	'color_custom'     => '', // rgba or hex?
	'size'             => 'nl',
	'alignment'        => 'inline',
	'is_full'          => 'no',
	'is_icon'          => 'disable',
	'icon_library'     => 'fontawesome',
	'icon_fontawesome' => '',
	'icon_material'    => '',
	'icon_custom'      => '',
	'icon_position'    => 'left',
	'is_waves'         => 'disable',
	'waves_skin'       => 'light',
	'animation'        => '',
	'class'            => '',
), $shortcode ), $atts );

$attr  = array();
$class = array();

$type      = esc_attr( $a['type'] );
$shape     = esc_attr( $a['shape'] );
$color     = esc_attr( $a['color'] );
$size      = esc_attr( $a['size'] );
$alignment = esc_attr( $a['alignment'] );
$w_skin    = esc_attr( $a['waves_skin'] );
$i_pos     = esc_attr( $a['icon_position'] );

$is_full      = ( 'yes' === $a['is_full'] );
$is_icon      = ( 'enable' === $a['is_icon'] );
$is_waves     = ( 'enable' === $a['is_waves'] );
$is_inline    = ( 'inline' === $a['alignment'] );
$is_custom    = ( 'custom' === $a['color'] );
$is_right     = ( 'right' === $a['icon_position'] );
$is_animation = ( ! empty( $a['animation'] ) );

$icon = '';
$text = esc_html( $a['text'] );
$link = startapp_vc_parse_link( $a['link'] );

$attr['href']   = empty( $link['url'] ) ? '#' : esc_url( trim( $link['url'] ) );
$attr['target'] = empty( $link['target'] ) ? '' : esc_attr( trim( $link['target'] ) );
$attr['title']  = empty( $link['title'] ) ? '' : esc_attr( trim( $link['title'] ) );
$attr['rel']    = empty( $link['rel'] ) ? '' : esc_attr( trim( $link['rel'] ) );

// default button classes
$class[] = 'btn';
$class[] = 'btn-' . $type;
$class[] = 'btn-' . $shape;
$class[] = 'btn-' . $color;
$class[] = 'btn-' . $size;
$class[] = $is_full ? 'btn-block' : '';
$class[] = $is_waves ? 'waves-effect' : '';
$class[] = $is_waves ? 'waves-' . $w_skin : '';
$class[] = $a['class']; // extra class

if ( $is_icon ) {
	$library = $a['icon_library'];
	$icon    = sprintf( '<i class="%s"></i>', esc_attr( $a["icon_{$library}"] ) );
	$icon    = $is_right ? '&nbsp;' . $icon : $icon . '&nbsp;';
	unset( $library );
}

if ( $is_custom ) {
	$custom_color = $a['color_custom'];
	$custom_class = startapp_get_unique_id( 'btn-custom-' );
	$class[]      = $custom_class;

	$attr['data-custom-color'] = startapp_css_custom_color( $custom_color, $custom_class, $type );
	unset( $custom_color, $custom_class );
}

$attr['class'] = startapp_get_classes( $class );

// convert attributes to string
$attr = startapp_get_attr( $attr );

// prepare the template, 1 = attr, 2 = text, 3 = icon
$tpl = $is_right ? '<a %1$s>%2$s%3$s</a>' : '<a %1$s>%3$s%2$s</a>';

// prepare a.btn
$btn = sprintf( $tpl, $attr, $text, $icon );

// Buttons are placed in line when meet two conditions:
// alignment = inline & animation is disabled. In all other cases (alignment or animation)
// button should be wrapped into div.text-{alignment}+data-aos > a.btn
if ( $is_inline && ! $is_animation ) {
	echo $btn;
} else {
	echo startapp_get_tag( 'div', array(
		'class'    => 'text-' . $alignment,
		'data-aos' => $is_animation ? esc_attr( $a['animation'] ) : '',
	), $btn );
}
