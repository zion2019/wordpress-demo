<?php
/**
 * Classes | startapp_classes
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
	'classes'   => 0,
	'animation' => '',
	'class'     => '',
), $shortcode ), $atts );


/* Prepare data */

if ( empty( $a['classes'] ) ) {
	return;
}

$post = get_post( (int) $a['classes'] );
if ( ! $post instanceof WP_Post ) {
	return;
}

// get Classes Attributes
$attr = startapp_get_meta( (int) $a['classes'], '_startapp_classes_atts' );

// prepare label
$label = '';
if ( (bool) $attr['is_label'] ) {
	$l_class = startapp_get_classes( array(
		'badge',
		'badge-' . esc_attr( $attr['label_bg_color'] ),
		'text-' . esc_attr( $attr['label_text_color'] ),
	) );

	$l_attr = array(
		'class' => esc_attr( $l_class ),
		'style' => startapp_css_color( sanitize_hex_color( $attr['label_bg_color_custom'] ) ),
	);

	$label = startapp_get_tag( 'div', $l_attr, esc_html( trim( $attr['label_text'] ) ) );
	unset( $l_class, $l_attr );
}

$args = array(
	'subtitle'       => $attr['subtitle'],
	'description'    => $attr['description'],
	'date'           => $attr['date'],
	'time'           => $attr['time'],
	'seats'          => $attr['seats'],
	'label'          => $label,
	'author_avatar'  => $attr['author_avatar'],
	'author_name'    => $attr['author_name'],
	'author_surname' => $attr['author_surname'],
	'author_link'    => $attr['author_link'],
	'attr'           => array(
		'class'    => esc_attr( startapp_get_classes( array( 'classes-wrap', 'classes-tile' ) ) ),
		'data-aos' => ( ! empty( $a['animation'] ) ) ? esc_attr( $a['animation'] ) : '',
	),
);


/* Setup post and output data */

setup_postdata( $GLOBALS['post'] =& $post );
startapp_shortcode_template( 'classes-tile.php', $args );
wp_reset_postdata();
