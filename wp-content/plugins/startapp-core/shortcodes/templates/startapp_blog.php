<?php
/**
 * Blog | startapp_blog
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
	'layout'               => 'list',
	'columns'              => 3, // 1-6 [if layout = grid]
	'source'               => 'posts', // posts | ids
	'query_post__in'       => '',
	'query_taxonomies'     => '',
	'query_post__not_in'   => '',
	'query_posts_per_page' => 'all',
	'query_orderby'        => 'date',
	'query_order'          => 'DESC',
	'pagination'           => 'load-more',
	'more_text'            => __( 'Load More', 'startapp' ),
	'more_pos'             => 'left',
	'animation'            => '',
	'class'                => '',
), $shortcode ), $atts );


/* Build a Query */

$is_by_ids  = ( 'ids' === $a['source'] );
$query_args = startapp_parse_array( $a, 'query_' );
$query_args = startapp_query_build( $query_args, function ( $query ) use ( $is_by_ids ) {

	// hard-code the defaults
	$query['post_type']           = 'post';
	$query['post_status']         = 'publish';
	$query['ignore_sticky_posts'] = true;

	// "post__not_in" allowed only for "posts" source type
	// Exclude it if exists to correctly handle "by IDs" option
	if ( $is_by_ids && array_key_exists( 'post__not_in', $query ) ) {
		unset( $query['post__not_in'] );
	}

	// Otherwise, "post__in" allowed only for "IDs" source type
	// Exclude it if exists
	if ( ! $is_by_ids && array_key_exists( 'post__in', $query ) ) {
		unset( $query['post__in'] );
	}

	// If user specify a list of IDs, fetch all posts without pagination
	if ( $is_by_ids && array_key_exists( 'posts_per_page', $query ) ) {
		$query['posts_per_page'] = - 1;
	}

	// "taxonomies" allowed only for "posts" source type
	if ( $is_by_ids && array_key_exists( 'taxonomies', $query ) ) {
		unset( $query['taxonomies'] );
	}

	// Build the tax_query based on the list of term slugs
	if ( ! $is_by_ids && array_key_exists( 'taxonomies', $query ) ) {
		$terms = $query['taxonomies'];
		unset( $query['taxonomies'] );

		$taxonomies = get_taxonomies( array(
			'public'      => true,
			'object_type' => array( 'post' ),
		), 'objects' );

		// Exclude post_formats
		if ( array_key_exists( 'post_format', $taxonomies ) ) {
			unset( $taxonomies['post_format'] );
		}

		// Get only taxonomies slugs
		$taxonomies         = array_keys( $taxonomies );
		$query['tax_query'] = startapp_query_multiple_tax( $terms, $taxonomies );

		// relations for multiple tax_queries
		if ( count( $query['tax_query'] ) > 1 ) {
			$query['tax_query']['relations'] = 'AND';
		}
	}

	return $query;
} );

$query = new WP_Query( $query_args );
if ( ! $query->have_posts() ) {
	return;
}
unset( $is_by_ids );


/* So, we have posts. OK! Handle the attributes and show posts */

$layout        = sanitize_key( $a['layout'] );
$allowed_cols  = range( 1, 6 );
$selected_cols = absint( $a['columns'] );
$columns       = in_array( $selected_cols, $allowed_cols, true ) ? $selected_cols : 3;
$is_grid       = ( 'grid' === $a['layout'] );

$class = startapp_get_classes( array(
	'blog-posts',
	$layout . '-layout',
	$is_grid ? 'masonry-grid' : '',
	$is_grid ? 'col-' . $columns : '',
	$a['class'],
) );

$attr = array(
	'class'    => esc_attr( $class ),
	'data-aos' => ( ! empty( $a['animation'] ) ) ? esc_attr( $a['animation'] ) : '',
);

switch ( $a['layout'] ) {
	case 'grid':

		?>
		<div <?php echo startapp_get_attr( $attr ); ?>>
			<div class="gutter-sizer"></div>
			<div class="grid-sizer"></div>
			<?php
			while ( $query->have_posts() ) :
				$query->the_post();
				echo '<div class="grid-item">';
				get_template_part( 'template-parts/blog/post', 'tile' );
				echo '</div>';
			endwhile;
			?>
		</div>
		<?php

		break;

	case 'simple':

		echo '<div ', startapp_get_attr( $attr ), '>';
		while ( $query->have_posts() ) :
			$query->the_post();
			get_template_part( 'template-parts/blog/post', 'simple' );
		endwhile;
		echo '</div>';

		break;

	case 'list':
	default:

		echo '<div ', startapp_get_attr( $attr ), '>';
		while ( $query->have_posts() ) :
			$query->the_post();
			get_template_part( 'template-parts/blog/post', 'tile' );
		endwhile;
		echo '</div>';

		break;
}

wp_reset_postdata();
unset( $allowed_cols, $selected_cols, $layout, $columns, $is_grid, $class );


/*
 * Pagination
 *
 * Pagination works when user perform a request by categories
 * and limiting the number of posts.
 *
 * In case if user disable the pagination, or try to load "all" posts,
 * or perform a request by posts, or requested number of posts less
 * than found by provided criteria the pagination won't display.
 */

$is_more = ( 'disable' !== $a['pagination'] );
$is_all  = ( 'all' === strtolower( $a['query_posts_per_page'] ) );
$type    = esc_attr( $a['pagination'] );

if ( $is_more
     && false === $is_all
     && 'posts' === $a['source']
     && $query->max_num_pages > 1
) {
	$class = esc_attr( startapp_get_classes( array(
		'pagination',
		'pagination-' . ( $type === 'infinity-scroll' ? 'infinite' : 'load-more' ),
		'margin-bottom-1x',
		'text-' . esc_attr( $a['more_pos'] ),
	) ) );

	$nav = '';

	if ( 'load-more' === $a['pagination'] ) {
		$total    = (int) $query->found_posts;
		$per_page = (int) $query_args['posts_per_page'];
		$number   = $total - ( 1 * $per_page );
		$number   = ( $number > $per_page ) ? $per_page : $number;
		$text     = esc_html( $a['more_text'] ) . ' <span class="load-more-counter">' . $number . '</span>';

		$nav = startapp_get_tag( 'a', array(
			'href'           => '#',
			'class'          => 'btn btn-transparent btn-primary core-load-more-posts',
			'data-page'      => 2,
			'data-type'      => sanitize_key( $a['layout'] ),
			'data-query'     => startapp_query_encode( $query_args ),
			'data-max-pages' => (int) $query->max_num_pages,
			'data-total'     => $total,
			'data-perpage'   => $query_args['posts_per_page'],
			'rel'            => 'nofollow',
		), $text );

		unset( $total, $per_page, $number, $text );
	} else if ('infinity-scroll' === $a['pagination'] ) {
		$nav = startapp_get_tag( 'a', array(
			'href'           => '#',
			'class'          => 'core-infinite-scroll',
			'data-page'      => 2,
			'data-type'      => sanitize_key( $a['layout'] ),
			'data-query'     => startapp_query_encode( $query_args ),
			'data-max-pages' => (int) $query->max_num_pages,
			'rel'            => 'nofollow',
		), '' );
	}

	echo "
	<section class=\"{$class}\">
		<div class=\"loader\">
			<span class=\"child-1\"></span>
			<span class=\"child-2\"></span>
			<span class=\"child-3\"></span>
		</div>
		{$nav}
	</section>";
}

unset( $is_more, $is_all );
