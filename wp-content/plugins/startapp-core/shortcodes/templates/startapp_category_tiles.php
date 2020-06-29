<?php
/**
 * Category Tiles | startapp_category_tiles
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
	'slug'      => '',
	'number'    => 'all',
	'orderby'   => 'name',
	'order'     => 'ASC',
	'height'    => 335,
	'columns'   => 1,
	'animation' => '',
	'class'     => '',
), $shortcode ), $atts );

$categories = get_terms( array(
	'taxonomy'     => 'category',
	'hide_empty'   => false,
	'hierarchical' => false,
	'slug'         => startapp_parse_slugs( $a['slug'] ),
	'number'       => ( 'all' === $a['number'] || empty( $a['number'] ) ) ? '' : absint( $a['number'] ),
	'orderby'      => sanitize_text_field( $a['orderby'] ),
	'order'        => sanitize_text_field( $a['order'] ),
) );

if ( empty( $categories ) || is_wp_error( $categories ) ) {
	return;
}

// remove "uncategorized" category
$categories = array_filter( $categories, function( $category ) {
	/** @var WP_Term $category */
	return ( 'uncategorized' !== $category->slug );
} );

$class = startapp_get_classes( array(
	'category-tiles',
	'row',
	$a['class'],
) );

$attr = startapp_get_attr( array(
	'class'    => esc_attr( $class ),
	'data-aos' => ( ! empty( $a['animation'] ) ) ? esc_attr( $a['animation'] ) : '',
) );

$tpl = <<<'TPL'
<div class="col-sm-{column}" style="margin-bottom: {margin}px;">
	<a href="{link}" class="category-tile" style="min-height: {height}px;">
		<div class="bg-image" style="{background}"></div>
		<div class="category-tile-info">
			{name}
			{custom-text}
		</div>
	</a>
</div>
TPL;

echo '<div ', $attr, '>';
/** @var WP_Term $category */
foreach ( $categories as $category ) {
	$link = get_term_link( $category );
	$meta = get_term_meta( $category->term_id, 'startapp_additions', true );
	$meta = wp_parse_args( $meta, array( 'bg' => 0 ) );

	$r = array(
		'{column}'      => absint( 12 / $a['columns'] ),
		'{margin}'      => 30,
		'{link}'        => esc_url( $link ),
		'{height}'      => absint( $a['height'] ),
		'{background}'  => startapp_css_background_image( (int) $meta['bg'], 'full' ),
		'{name}'        => startapp_get_text( esc_html( $category->name ), '<h3 class="category-tile-title">', '</h3>' ),
		'{custom-text}' => startapp_get_text( esc_html( $category->description ), '<p class="category-tile-text">', '</p>' ),
	);

	echo str_replace( array_keys( $r ), array_values( $r ), $tpl );
	unset( $link, $meta, $r );
}
unset( $category );
echo '</div>';
