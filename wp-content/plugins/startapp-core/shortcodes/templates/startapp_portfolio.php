<?php
/**
 * Portfolio | startapp_portfolio
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
	'type'                 => 'text-button',
	'columns'              => 3,
	'is_filters'           => 'enable',
	'pagination'           => 'load-more',
	'source'               => 'categories',
	'query_post__in'       => '',
	'query_categories'     => '',
	'query_post__not_in'   => '',
	'query_posts_per_page' => 'all',
	'query_orderby'        => 'date',
	'query_order'          => 'DESC',
	'overlay_color'        => '#000000',
	'overlay_opacity'      => '50',
	'overlay_skin'         => 'light',
	'filters_pos'          => 'center',
	'filters_exclude'      => '',
	'more_text'            => __( 'Load More', 'startapp' ),
	'more_pos'             => 'left',
	'animation'            => '',
	'class'                => '',
), $shortcode ), $atts );


/* Build a Query */

$query_args = startapp_parse_array( $a, 'query_' );
$query_args = startapp_query_build( $query_args, function( $query ) use ( $a ) {
	// hard-code the defaults
	$query['post_type']   = 'startapp_portfolio';
	$query['post_status'] = 'publish';

	$is_by_ids = ( 'ids' === $a['source'] );
	$tax       = 'startapp_portfolio_category';

	// "post__not_in" allowed only for "categories" source type
	// exclude it if exists to correctly handle "by IDs" option
	if ( $is_by_ids && array_key_exists( 'post__not_in', $query ) ) {
		unset( $query['post__not_in'] );
	}

	// otherwise, "post__in" allowed only for "IDs" source type
	// exclude it if exists
	if ( ! $is_by_ids && array_key_exists( 'post__in', $query ) ) {
		unset( $query['post__in'] );
	}

	// if user specify a list of IDs, fetch all posts without pagination
	if ( $is_by_ids ) {
		$query['posts_per_page'] = -1;
	}

	// "categories" allowed only for "categories" source type
	if ( $is_by_ids && array_key_exists( 'categories', $query ) ) {
		unset( $query['categories'] );
	}

	// build a tax_query if getting by categories
	// @see WP_Query
	if ( ! $is_by_ids && array_key_exists( 'categories', $query ) ) {
		$categories = $query['categories'];
		unset( $query['categories'] );

		$query['tax_query'] = startapp_query_single_tax( $categories, $tax );
	}

	return $query;
} );

$query = new WP_Query( $query_args );
if ( ! $query->have_posts() ) {
	return;
}

/* So, we have posts. Handle the attributes and show posts */

$unique_id  = startapp_get_unique_id( 'portfolio-' );
$grid_id    = $unique_id . '-grid';
$filters_id = $unique_id . '-filters';
$tax        = 'startapp_portfolio_category';

$allowed_cols  = range( 1, 6 );
$selected_cols = absint( $a['columns'] );
$columns       = in_array( $selected_cols, $allowed_cols, true ) ? $selected_cols : 3;
unset( $allowed_cols, $selected_cols );

$is_filters = ( 'enable' === $a['is_filters'] );
$layout     = esc_attr( $a['type'] );
$overlay    = startapp_parse_array( $a, 'overlay_' );

$class = startapp_get_classes( array(
	'portfolio-posts',
	'masonry-grid',
	'col-' . $columns,
	'filter-grid',
	$a['class'],
) );

$attr = array(
	'id'       => esc_attr( $grid_id ),
	'class'    => esc_attr( $class ),
	'data-aos' => ( ! empty( $a['animation'] ) ) ? esc_attr( $a['animation'] ) : '',
);

$is_overlay = ( 'overlay' === $a['type'] );
$is_simple  = ( 'simple' === $a['type'] );

// tile class
$tile = startapp_get_classes( array(
	'portfolio-tile',
	$is_simple ? 'portfolio-simple' : '',
	$is_overlay ? 'portfolio-text-overlay' : '',
	$is_overlay ? 'skin-' . esc_attr( $a['overlay_skin'] ) : '',
) );

$template_args = array(
	'tile_class' => $tile,
	'color'      => $a['overlay_color'],
	'opacity'    => $a['overlay_opacity'],
	'skin'       => $a['overlay_skin'],
);


/* Maybe display filters */

if ( $is_filters ) {
	echo startapp_get_filters( array(
		'taxonomy'      => $tax,
		'exclude'       => $a['filters_exclude'],
		'filters_id'    => $filters_id,
		'filters_class' => 'nav-filters portfolio-categories text-' . esc_attr( $a['filters_pos'] ),
		'grid_id'       => $grid_id,
		'show_all'      => __( 'Show All', 'startapp' ),
	) );
}


/* Markup Output */

?>
<div <?php echo startapp_get_attr( $attr ); ?>>
	<div class="gutter-sizer"></div>
	<div class="grid-sizer"></div>

	<?php
	while ( $query->have_posts() ) :
		$query->the_post();

		// do not load tile if Featured Image is missing
		if ( ! has_post_thumbnail() ) {
			continue;
		}

		$post_id     = (int) get_the_ID();
		$description = startapp_get_meta( $post_id, '_startapp_portfolio_description', 'text', '' );

		$template_args['description'] = esc_html( stripslashes( trim( $description ) ) );

		// service + terms classes for isotope filtration
		$classes = array_merge( array( 'grid-item' ), startapp_get_post_terms( $post_id, $tax ) );
		$classes = startapp_get_classes( $classes );

		echo '<div class="', esc_attr( $classes ), '">';
		startapp_shortcode_template( "portfolio-{$layout}.php", $template_args );
		echo '</div>';

		unset( $post_id, $description, $classes );
	endwhile;
	wp_reset_postdata();
	?>
</div>
<?php
unset( $unique_id, $is_filters, $class, $attr );


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

if ( $is_more
     && false === $is_all
     && 'categories' === $a['source']
     && (int) $query->max_num_pages > 1
) {
	$class = esc_attr( startapp_get_classes( array(
		'pagination',
		'pagination-' . ( $a['pagination'] === 'infinite-scroll' ? 'infinite' : 'load-more' ),
		'margin-bottom-1x',
		( 'load-more' === $a['pagination'] ? 'text-' . esc_attr( $a['more_pos'] ) : '' ),
	) ) );

	$nav = '';
	switch( $a['pagination'] ) {
		case 'infinite-scroll':
			$nav = startapp_get_tag( 'a', array(
				'href'            => '#',
				'class'           => 'portfolio-infinite-scroll',
				'data-page'       => 2,
				'data-type'       => $layout,
				'data-args'       => $template_args,
				'data-query'      => startapp_query_encode( $query_args ),
				'data-max-pages'  => (int) $query->max_num_pages,
				'data-grid-id'    => $grid_id,
				'data-filters-id' => $filters_id,
				'rel'             => 'nofollow',
			), '' );
			break;

		case 'load-more':
		default:
			$total    = (int) $query->found_posts;
			$per_page = (int) $query_args['posts_per_page'];
			$number   = $total - ( 1 * $per_page );
			$number   = ( $number > $per_page ) ? $per_page : $number;
			$text     = esc_html( $a['more_text'] ) . ' <span class="load-more-counter">' . $number . '</span>';

			$nav = startapp_get_tag( 'a', array(
				'href'            => '#',
				'class'           => 'btn btn-transparent btn-primary portfolio-load-more-posts',
				'data-page'       => 2,
				'data-type'       => $layout,
				'data-args'       => $template_args,
				'data-query'      => startapp_query_encode( $query_args ),
				'data-max-pages'  => (int) $query->max_num_pages,
				'data-total'      => $total,
				'data-perpage'    => $query_args['posts_per_page'],
				'data-grid-id'    => $grid_id,
				'data-filters-id' => $filters_id,
				'rel'             => 'nofollow',
			), $text );

			unset( $total, $per_page, $number, $text );
			break;
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

	unset( $class, $nav );
} // end pagination