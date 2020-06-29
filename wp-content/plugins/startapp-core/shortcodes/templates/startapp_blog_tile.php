<?php
/**
 * Blog Post Tile | startapp_blog_tile
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
	'post'      => 0,
	'animation' => '',
	'class'     => '',
), $shortcode ), $atts );

if ( empty( $a['post'] ) ) {
	return;
}

$post = get_post( (int) $a['post'] );
if ( ! $post instanceof WP_Post ) {
	return;
}

$class = startapp_get_classes( array(
	'blog-post-tile',
	$a['class'],
) );

$attr = startapp_get_attr( array(
	'class'    => esc_attr( $class ),
	'data-aos' => ( ! empty( $a['animation'] ) ) ? esc_attr( $a['animation'] ) : '',
) );

setup_postdata( $GLOBALS['post'] =& $post );

echo '<div ', $attr, '>';
get_template_part( 'template-parts/blog/post', 'tile' );
echo '</div>';

wp_reset_postdata();
