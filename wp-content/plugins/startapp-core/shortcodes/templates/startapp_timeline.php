<?php
/**
 * Timeline | startapp_timeline
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
	'categories'   => '',
	'skin'         => 'dark',
	'arrows_shape' => 'rounded',
	'arrows_size'  => 'sm',
	'animation'    => '',
	'class'        => '',
), $shortcode ), $atts );

/**
 * Filter the args passed to {@see get_posts()} for timeline post type
 *
 * For example, here you can pass a custom ordering
 *
 * @param array $args Arguments
 */
$posts = get_posts( apply_filters( 'startapp_timeline_posts_args', array(
	'post_type'        => 'startapp_timeline',
	'post_status'      => 'publish',
	'posts_per_page'   => - 1,
	'no_found_rows'    => true,
	'nopaging'         => true,
	'suppress_filters' => true,
	'orderby'          => 'date',
	'order'            => 'DESC',
	'tax_query'        => startapp_query_single_tax( $a['categories'], 'startapp_timeline_category' ),
) ) );

if ( empty( $posts ) || is_wp_error( $posts ) ) {
	return;
}

$post_ids = array_map( function ( $post ) {
	return (int) $post->ID;
}, $posts );

$terms = wp_get_object_terms( $post_ids, 'startapp_timeline_milestone', array( 'orderby' => 'none' ) );
if ( empty( $terms ) || is_wp_error( $terms ) ) {
	return;
}

$milestones = array();
/** @var WP_Term $term */
foreach ( $terms as $term ) {
	$milestone                     = array();
	$milestone['term_id']          = $term->term_id;
	$milestone['term_taxonomy_id'] = $term->term_taxonomy_id;
	$milestone['slug']             = $term->slug;
	$milestone['name']             = $term->name;
	$milestone['posts']            = array_filter( $posts, function ( $post ) use ( $term ) {
		return has_term( $term->term_id, 'startapp_timeline_milestone', $post );
	} );

	$milestones[ $term->slug ] = $milestone;
}

// make sure the milestones are sorting in natural order
// in case user will use not only years as a slug
// requires php 5.4+
array_multisort( array_keys( $milestones ), SORT_NATURAL, $milestones );

$skin  = sanitize_key( $a['skin'] );
$class = startapp_get_classes( array(
	'timeline',
	'timeline-' . $skin,
	'carousel-' . $skin,
	'carousel-navs-ghost',
	'carousel-navs-top-outside',
	'carousel-navs-' . sanitize_key( $a['arrows_shape'] ),
	'carousel-navs-' . sanitize_key( $a['arrows_size'] ),
	$a['class'],
) );

$slick = array(
	'infinite' => false,
);

$attr = startapp_get_attr( array(
	'class'      => esc_attr( $class ),
	'data-slick' => $slick,
	'data-aos'   => ( ! empty( $a['animation'] ) ) ? esc_attr( $a['animation'] ) : '',
) );

$tpl = <<<'ITEM'
<div class="milestone-item">
	<div class="milestone-body">
		{title}
		{content}
	</div>
</div>
ITEM;

echo '<div ', $attr, '>';
foreach ( $milestones as $milestone ) {
	echo '<div class="milestone">';
	echo startapp_get_text( esc_html( $milestone['name'] ), '<h3 class="milestone-label">', '</h3>' );

	if ( ! empty( $milestone['posts'] ) ) {
		foreach ( $milestone['posts'] as $post ) {
			$r = array(
				'{title}'   => startapp_get_text( esc_html( $post->post_title ), '<h4 class="milestone-date">', '</h4>' ),
				'{content}' => startapp_do_shortcode( $post->post_content ),
			);

			echo str_replace( array_keys( $r ), array_values( $r ), $tpl );
		}
	}

	echo '</div>';
}
echo '</div>';
