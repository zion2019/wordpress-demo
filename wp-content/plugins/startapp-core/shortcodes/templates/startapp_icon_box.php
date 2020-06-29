<?php
/**
 * Icon Box | startapp_icon_box
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
	'layout'              => 'vertical',
	'alignment'           => 'left', // if layout = vertical
	'type'                => 'image',
	'icon_position'       => 'left', // if layout = horizontal
	'icon_library'        => 'fontawesome', // if type = icon
	'icon_fontawesome'    => '',
	'icon_material'       => '',
	'icon_custom'         => '',
	'icon_color'          => 'default',
	'icon_color_custom'   => '',
	'image'               => '', // if type = image
	'title'               => '',
	'background'          => 'no', // if layout = vertical
	'background_color'    => '', // if background = hover & static, ignore for light skin
	'background_effect'   => 'fade', // if background = hover
	'skin'                => 'dark',
	'is_button'           => 'no',
	'button_text'         => '',
	'button_link'         => '',
	'button_type'         => 'solid',
	'button_shape'        => 'rounded',
	'button_color'        => 'default',
	'button_color_custom' => '', // rgba or hex?
	'button_size'         => 'default',
	'button_alignment'    => 'inline',
	'button_is_full'      => 'no',
	'button_is_waves'     => 'disable',
	'button_waves_skin'   => 'light',
	'button_class'        => '',
	'animation'           => '',
	'class'               => '',
), $shortcode ), $atts );

$layout     = sanitize_key( $a['layout'] );
$alignment  = sanitize_key( $a['alignment'] );
$type       = sanitize_key( $a['type'] );
$background = sanitize_key( $a['background'] );
$effect     = esc_attr( $a['background_effect'] );
$skin       = sanitize_key( $a['skin'] );
$position   = sanitize_key( $a['icon_position'] );

$icon   = '';
$button = '';

$is_horizontal = ( 'horizontal' === $a['layout'] );
$is_background = in_array( $a['background'], array( 'static', 'hover' ) );

$class = startapp_get_classes( array(
	'icon-box',
	'icon-box-' . $layout,
	'icon-box-' . $skin,
	$is_background ? 'icon-box-bg-enabled' : '',
	'icon-box-type-' . $type,
	'bg-' . $background,
	( 'hover' === $a['background'] ) ? 'hover-' . $effect : '',
	$is_horizontal ? 'icon-' . $position : '',
	( $is_horizontal && 'right' === $position ) ? 'text-right' : '',
	( 'vertical' === $a['layout'] ) ? 'text-' . $alignment : '',
	$a['class'],
) );

// wrapper attributes, @see template tags
$attr = array(
	'class'    => esc_attr( $class ),
	'data-aos' => ( ! empty( $a['animation'] ) ) ? esc_attr( $a['animation'] ) : '',
);

// Prepare icon
$i_tpl = '<div class="{class}">{icon}</div>';
if ( 'image' === $a['type'] && ! empty( $a['image'] ) ) {
	$r = array(
		'{class}' => 'icon-box-icon',
		'{icon}'  => wp_get_attachment_image( (int) $a['image'], 'full' ),
	);

	$icon = str_replace( array_keys( $r ), array_values( $r ), $i_tpl );
	unset( $r );
} elseif ( 'icon' === $a['type'] ) {
	$library = $a['icon_library'];

	$i = array();

	$i['class'] = $a["icon_{$library}"];
	if ( 'custom' === $a['icon_color'] ) {
		$i['style'] = startapp_css_color( $a['icon_color_custom'] );
	}

	$r = array(
		'{class}' => 'icon-box-icon text-' . esc_attr( $a['icon_color'] ),
		'{icon}'  => startapp_get_tag( 'i', $i, '' ),
	);

	$icon = str_replace( array_keys( $r ), array_values( $r ), $i_tpl );
	unset( $library, $i, $r );
}
unset( $i_tpl );

// Prepare button
if ( 'yes' === $a['is_button'] ) {
	$b = startapp_parse_array( $a, 'button_' );
	$s = startapp_shortcode_build( 'startapp_button', $b );

	$button = startapp_do_shortcode( $s, false );
	unset( $b, $s );
}

// Prepare the template
if ( 'horizontal' === $a['layout'] ) {
	$template = 'icon-box-horizontal-' . $a['icon_position'] . '.php';
} else {
	$template = 'icon-box-vertical.php';
}

startapp_shortcode_template( $template, array(
	'attr'             => $attr,
	'title'            => $a['title'],
	'description'      => $content,
	'button'           => $button,
	'icon'             => $icon,
	'is_background'    => $is_background,
	'background_color' => ( 'light' === $a['skin'] ) ? '#fff' : $a['background_color'],
) );

