<?php
/**
 * Fancy List | startapp_fancy_list
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
	'style'        => 'unordered',
	'is_separator' => 'no',
	'size'         => 'normal',
	'skin'         => 'dark',
	'items'        => '', // param_group for unordered, ordered, unstyled
	'items_image'  => '', // param_group for style = image
	'items_icon'   => '', // param_group for style = icon
	'animation'    => '',
	'class'        => '',
), $shortcode ), $atts );

if ( 'image' === $a['style'] ) {
	$items = json_decode( urldecode( $a['items_image'] ), true );
} elseif ( 'icon' === $a['style'] ) {
	$items = json_decode( urldecode( $a['items_icon'] ), true );
} else {
	$items = json_decode( urldecode( $a['items'] ), true );
}

if ( empty( $items ) ) {
	return;
}

$style = esc_attr( $a['style'] );
$size  = esc_attr( $a['size'] );
$skin  = esc_attr( $a['skin'] );

$is_sep     = ( 'yes' === $a['is_separator'] );
$is_ordered = ( 'ordered' === $a['style'] );
$is_image   = ( 'image' === $a['style'] );
$is_icon    = ( 'icon' === $a['style'] );

$class = esc_attr( startapp_get_classes( array(
	'list-' . $style,
	$is_sep ? 'list-bordered' : '',
	'text-' . $size,
	'text-' . $skin,
	$a['class'],
) ) );

$output = '';
foreach ( $items as $item ) {
	$item = wp_parse_args( $item, array(
		'title'             => '',
		'description'       => '',
		'image'             => '',
		'icon_library'      => 'material',
		'icon_material'     => '',
		'icon_custom'       => '',
		'icon_color'        => 'default',
		'icon_color_custom' => '',
	) );

	$icon        = '';
	$title       = esc_html( $item['title'] );
	$description = startapp_get_text( esc_html( $item['description'] ), '<small>', '</small>' );

	if ( $is_image ) {
		$icon = wp_get_attachment_image( $item['image'], 'full' );
	}

	if ( $is_icon ) {
		$i_attr  = array();
		$library = $item['icon_library'];
		$color   = empty( $item['icon_color'] ) ? 'default' : $item['icon_color'];

		$i_attr['class'] = startapp_get_classes( array( $item["icon_{$library}"], 'text-' . $color ) );

		if ( 'custom' === $item['icon_color'] ) {
			$i_attr['style'] = startapp_css_color( $item['icon_color_custom'] );
		}

		$icon = startapp_get_tag( 'i', $i_attr, '' );
		unset( $i_attr, $library, $color );
	}

	$output .= sprintf( '<li>%1$s%2$s%3$s</li>', $icon, $title, $description );
}

echo startapp_get_tag( $is_ordered ? 'ol' : 'ul', array(
	'class'    => $class,
	'data-aos' => ( ! empty( $a['animation'] ) ) ? esc_attr( $a['animation'] ) : '',
), $output );
