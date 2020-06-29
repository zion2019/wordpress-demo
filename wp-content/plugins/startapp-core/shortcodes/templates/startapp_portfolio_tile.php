<?php
/**
 * Portfolio Tile | startapp_portfolio_tile
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
	'post'            => 0,
	'type'            => 'text-button',
	'overlay_color'   => '#000000',
	'overlay_opacity' => '50',
	'overlay_skin'    => 'light',
	'animation'       => '',
	'class'           => '',
), $shortcode ), $atts );

if ( empty( $a['post'] ) ) {
	return;
}

$post = get_post( (int) $a['post'] );
if ( ! $post instanceof WP_Post ) {
	return;
}

// do not load tile if Featured Image is missing
if ( ! has_post_thumbnail( $post ) ) {
	return;
}

$is_overlay = ( 'overlay' === $a['type'] );
$is_simple  = ( 'simple' === $a['type'] );

$type      = esc_attr( $a['type'] );
$wrap_attr = startapp_get_attr( array(
	'class'    => esc_attr( startapp_get_classes( $a['class'] ) ),
	'data-aos' => ( ! empty( $a['animation'] ) ) ? esc_attr( $a['animation'] ) : '',
) );

$tile_class = startapp_get_classes( array(
	'portfolio-tile',
	$is_simple ? 'portfolio-simple' : '',
	$is_overlay ? 'portfolio-text-overlay' : '',
	$is_overlay ? 'skin-' . esc_attr( $a['overlay_skin'] ) : '',
) );

$description = startapp_get_meta( $post->ID, '_startapp_portfolio_description', 'text', '' );
$description = esc_html( stripslashes( trim( $description ) ) );

$template_args = array(
	'tile_class'  => $tile_class,
	'color'       => $a['overlay_color'],
	'opacity'     => $a['overlay_opacity'],
	'skin'        => $a['overlay_skin'],
	'description' => $description,
);

setup_postdata( $GLOBALS['post'] =& $post );

echo '<div ', $wrap_attr, '>';
startapp_shortcode_template( "portfolio-{$type}.php", $template_args );
echo '</div>';

wp_reset_postdata();
