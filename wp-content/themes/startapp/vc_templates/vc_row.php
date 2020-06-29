<?php
/**
 * Row | vc_row
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
	'id'              => '',
	'offset_top'      => 180,
	'layout'          => 'boxed',
	'is_no_gap'       => 'disable',
	'is_overlay'      => 'disable',
	'overlay_opacity' => 65,
	'overlay_color'   => '#000000',
	'is_parallax'     => 'disable',
	'parallax_bg'     => 0,
	'parallax_video'  => '',
	'parallax_type'   => 'scroll',
	'parallax_speed'  => 0.4,
	'animation'       => '',
	'class'           => '',
	'css'             => '',
), 'vc_row' ), $atts );

$is_no_gap   = ( 'enable' === $a['is_no_gap'] );
$is_overlay  = ( 'enable' === $a['is_overlay'] );
$is_parallax = ( 'enable' === $a['is_parallax'] );

// overlay
$overlay = '';
if ( $is_overlay ) {
	$o['class'] = 'overlay';
	$o['style'] = startapp_css_declarations( array(
		'opacity'          => startapp_get_opacity_value( $a['overlay_opacity'] ),
		'background-color' => esc_attr( $a['overlay_color'] ),
	) );

	$overlay = startapp_get_tag( 'span', $o, '' );
	unset( $o );
}

// .fw-section classes
$class = startapp_get_classes( array(
	'fw-section',
	'layout-' . sanitize_key( $a['layout'] ),
	$is_no_gap ? 'section-no-gap' : '',
	$is_overlay ? 'with-overlay' : 'without-overlay',
	$is_parallax ? 'bg-parallax' : 'without-parallax',
	trim( vc_shortcode_custom_css_class( $a['css'] ) ),
	$a['class'],
) );

// .fw-section attributes
$attr                    = array();
$attr['id']              = esc_attr( $a['id'] );
$attr['class']           = esc_attr( $class );
$attr['data-aos']        = ( ! empty( $a['animation'] ) ) ? esc_attr( $a['animation'] ) : '';
$attr['data-offset-top'] = (int) $a['offset_top'];

if ( $is_parallax ) {
	$attr['data-parallax-type']  = esc_attr( $a['parallax_type'] );
	$attr['data-parallax-speed'] = startapp_sanitize_float( $a['parallax_speed'] );
	$attr['data-jarallax-video'] = esc_url( $a['parallax_video'] );
	$attr['style']               = startapp_css_background_image( (int) $a['parallax_bg'] );
}

$attr = startapp_get_attr( $attr );

// container class
switch ( $a['layout'] ) {
	case 'full':
	case 'full-equal':
		$container = 'container-fluid';
		break;

	case 'boxed':
	case 'boxed-equal':
	default:
		$container = 'container';
		break;
}

$tpl = <<<'TEMPLATE'
<section {attr}>
	{overlay}
	<div class="{container-class}">
		<div class="row">
			{content}
		</div>
	</div>
</section>
TEMPLATE;

$r = array(
	'{attr}'            => $attr,
	'{overlay}'         => $overlay,
	'{container-class}' => $container,
	'{content}'         => startapp_do_shortcode( $content ),
);

echo str_replace( array_keys( $r ), array_values( $r ), $tpl );
