<?php
/**
 * Testimonials Slider | startapp_testimonials_slider
 *
 * @var string $shortcode Shortcode tag
 * @var array  $atts      Shortcode attributes
 * @var mixed  $content   Shortcode content
 * @var string $template  Path to shortcode template
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
	'query_post__in'       => '',
	'query_post__not_in'   => '',
	'query_posts_per_page' => '',
	'query_orderby'        => 'date',
	'query_order'          => 'DESC',
	'alignment'            => 'left',
	'is_dots'              => 'show',
	'skin'                 => 'dark',
	'transition'           => 'slide',
	'is_loop'              => 'disable',
	'is_autoplay'          => 'disable',
	'autoplay_speed'       => '3000',
	'animation'            => '',
	'class'                => '',
), $shortcode ), $atts );

$q = startapp_parse_array( $a, 'query_' );
$q = startapp_query_build( array_merge( $q, array(
	'post_type'   => 'startapp_testimonial',
	'post_status' => 'publish',
) ) );

$query = new WP_Query( $q );
if ( $query->have_posts() ) {
	$is_dots   = ( 'show' === $a['is_dots'] );
	$is_fade   = ( 'fade' === $a['transition'] );
	$skin      = sanitize_key( $a['skin'] );
	$alignment = sanitize_key( $a['alignment'] );

	$class = startapp_get_classes( array(
		'testimonials-slider',
		'carousel-' . $skin,
		'text-' . $alignment,
		'text-' . $skin,
		$is_dots ? 'carousel-dots-inside' : '',
		$is_dots ? 'carousel-dots-' . $alignment : '',
		'carousel-effect-' . sanitize_key( $a['transition'] ),
		'carousel-autoplay-' . sanitize_key( $a['is_loop'] ),
		$a['class'],
	) );

	$slick = array(
		'arrows'        => false,
		'dots'          => $is_dots,
		'infinite'      => ( 'enable' === $a['is_loop'] ),
		'fade'          => ( 'fade' === $a['transition'] ),
		'autoplay'      => ( 'enable' === $a['is_autoplay'] ),
		'autoplaySpeed' => absint( $a['autoplay_speed'] ),
	);

	$attr = startapp_get_attr( array(
		'class'      => esc_attr( $class ),
		'data-slick' => $slick,
		'data-aos'   => ( ! empty( $a['animation'] ) ) ? esc_attr( $a['animation'] ) : '',
	) );

	$tpl = <<<'TEMPLATE'
<div {attr}">
	<div class="container">
		<div class="inner">
			{logo}
			{title}
			<blockquote>
				{quote}
				{cite}
			</blockquote>
			{button}
		</div>
	</div>
</div>
TEMPLATE;

	echo '<div ', $attr, '>';
	while ( $query->have_posts() ) {
		$query->the_post();
		$post_id = get_the_ID();

		// slide settings
		$settings = wp_parse_args( startapp_get_meta( $post_id, '_startapp_testimonial_settings' ), array(
			'quotation'      => '',
			'bg'             => 0,
			'author'         => '',
			'company'        => '',
			'position'       => '',
			'company_link'   => '',
			'logo'           => 0,
			'is_logo_linked' => 0,
		) );

		// slide attributes
		$attributes = startapp_get_attr( array(
			'class' => 'testimonial-slide padding-top-3x padding-bottom-5x',
			'style' => startapp_css_background_image( (int) $settings['bg'] ),
		) );

		// prepare the slide logo
		$logo = '';
		if ( ! empty( $settings['logo'] ) ) {
			$is_logo_linked = (bool) $settings['is_logo_linked'];
			$logo = startapp_get_tag( $is_logo_linked ? 'a' : 'div', array(
				'href'  => esc_url( $settings['company_link'] ),
				'class' => 'testimonial-logo',
			), wp_get_attachment_image( (int) $settings['logo'], 'full' ) );
		}

		// prepare the cite
		$author   = esc_html( $settings['author'] );
		$position = esc_html( $settings['position'] );
		$company  = esc_html( $settings['company'] );
		if ( ! empty( $position ) || ! empty( $company ) ) {
			$author = "{$author}, ";
		}

		if ( ! empty( $settings['company'] ) && ! empty( $settings['company_link'] ) ) {
			$company = startapp_get_tag( 'a', array( 'href' => esc_url( $settings['company_link'] ) ), $company );
		}

		$cite = startapp_get_text( "{$author} {$position} {$company}", '<cite>', '</cite>' );
		unset( $author, $position, $company );

		// prepare button
		$btn_attr = wp_parse_args( startapp_get_meta( $post_id, '_startapp_testimonial_button' ), array(
			'url' => '',
		) );

		$button = '';
		if ( ! empty( $btn_attr['url'] ) ) {
			$btn_attr['link'] = startapp_vc_build_link( $btn_attr );

			$s      = startapp_shortcode_build( 'startapp_button', $btn_attr );
			$button = startapp_do_shortcode( $s );
			unset( $s );
		}
		unset( $btn_attr );

		$r = array(
			'{attr}'   => $attributes,
			'{logo}'   => $logo,
			'{title}'  => startapp_get_text( esc_html( get_the_title() ), '<h3 class="h2">', '</h3>' ),
			'{quote}'  => startapp_get_text( esc_html( $settings['quotation'] ), '<p>', '</p>' ),
			'{cite}'   => $cite,
			'{button}' => $button,
		);

		echo str_replace( array_keys( $r ), array_values( $r ), $tpl );
		unset( $post_id, $settings, $attributes, $logo, $cite, $button, $r );
	}
	echo '</div>';
}
wp_reset_postdata();
