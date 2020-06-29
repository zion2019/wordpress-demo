<?php
/**
 * Team | startapp_team
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
	'image'                     => '',
	'name'                      => '',
	'position'                  => '',
	'company'                   => '',
	'company_link'              => '',
	'about'                     => '',
	'socials_socials'           => '',
	'socials_type'              => 'border',
	'socials_shape'             => 'rounded',
	'socials_skin'              => 'dark',
	'socials_is_tooltips'       => 'disable',
	'socials_tooltips_position' => 'top',
	'socials_is_waves'          => 'disable',
	'socials_waves_color'       => 'light',
	'socials_animation'         => '',
	'socials_class'             => '',
	'type'                      => 'simple-top',
	'skin'                      => 'dark',
	'shape'                     => 'rounded',
	'alignment'                 => 'left',
	'is_linked'                 => 'disable',
	'link'                      => '',
	'animation'                 => '',
	'class'                     => '',
), $shortcode ), $atts );

// prepare general info
$image_id = (int) $a['image'];
$name     = esc_html( trim( $a['name'] ) );
$position = esc_html( trim( $a['position'] ) );
$company  = esc_html( trim( $a['company'] ) );
$about    = esc_html( trim( $a['about'] ) );
$socials  = ''; // keep the socials markup

// shortcode settings
$type      = esc_attr( $a['type'] );
$skin      = sanitize_key( $a['skin'] );
$shape     = sanitize_key( $a['shape'] );
$alignment = sanitize_key( $a['alignment'] );

$is_tile    = ( 'tile' === $type );
$is_top     = ( 'simple-top' === $type );
$is_linked  = ( 'enable' === $a['is_linked'] );
$is_horiz   = in_array( $type, array( 'simple-left', 'simple-right' ), true );
$is_right   = ( 'simple-right' === $type );
$is_company = ( ! empty( $company ) );

// may be add socials
$s_at    = startapp_parse_array( $a, 'socials_' );
$s_sh    = startapp_shortcode_build( 'startapp_socials', array_merge( $s_at, array( 'alignment' => $alignment ) ) );
$socials = startapp_do_shortcode( $s_sh, false );
unset( $s_sh, $s_at );

// prepare the image or placeholder
if ( empty( $image_id ) ) {
	$featured = startapp_get_tag( 'img', array(
		'src' => STARTAPP_CORE_URI . '/img/placeholders/teammate.png',
		'alt' => esc_html__( 'Teammate', 'startapp' ),
	) );
} else {
	$featured = wp_get_attachment_image( $image_id, 'large' );
}

// may be wrap company name into the link
if ( $is_company ) {
	$c_link = startapp_vc_parse_link( $a['company_link'] );

	$c = array();
	if ( ! empty( $c_link['url'] ) ) {
		$c['href']   = esc_url( trim( $c_link['url'] ) );
		$c['target'] = empty( $c_link['target'] ) ? '' : esc_attr( trim( $c_link['target'] ) );
		$c['title']  = empty( $c_link['title'] ) ? '' : esc_attr( trim( $c_link['title'] ) );
		$c['rel']    = empty( $c_link['rel'] ) ? '' : esc_attr( trim( $c_link['rel'] ) );

		$company = startapp_get_tag( 'a', $c, $company );
	}

	unset( $c, $c_link );
}

// a link to teammate, may be a page or profile in social networks
$link = array();
if ( $is_linked ) {
	$t_link = startapp_vc_parse_link( $a['link'] );

	$link['href']   = empty( $t_link['url'] ) ? '#' : esc_url( trim( $t_link['url'] ) );
	$link['target'] = empty( $t_link['target'] ) ? '' : esc_attr( trim( $t_link['target'] ) );
	$link['title']  = empty( $t_link['title'] ) ? '' : esc_attr( trim( $t_link['title'] ) );
	$link['rel']    = empty( $t_link['rel'] ) ? '' : esc_attr( trim( $t_link['rel'] ) );

	unset( $t_link );
}

// may be wrap teammate name into the link
if ( $is_linked ) {
	$name = startapp_get_tag( 'a', $link, $name );
}

// add comma after position if user specified a company
if ( $is_company && ! empty( $position ) ) {
	$position = "{$position}, ";
}

// prepare teammate thumbnail
$thumb = sprintf( '<%1$s %2$s>%3$s</%1$s>',
	$is_linked ? 'a' : 'div',
	startapp_get_attr( array_merge( $link, array( 'class' => 'teammate-thumb' ) ) ),
	$featured
);

// prepare classes
$class = startapp_get_classes( array(
	'teammate',
	'teammate-' . $skin . '-skin',
	$is_horiz ? 'teammate-horizontal' : '',
	$is_right ? 'right-aligned' : '',
	$is_top ? 'text-' . $alignment : '',
	$is_tile ? '' : 'teammate-' . $shape,
	$is_tile ? 'teammate-tile' : '',
	$a['class'],
) );

// wrapper attributes, @see template-parts
$attr = array(
	'class'    => esc_attr( $class ),
	'data-aos' => ( ! empty( $a['animation'] ) ) ? esc_attr( $a['animation'] ) : '',
);

startapp_shortcode_template( "team-{$type}.php", array(
	'attr'     => $attr,
	'thumb'    => $thumb,
	'name'     => $name,
	'position' => $position . $company,
	'about'    => $about,
	'socials'  => $socials,
) );
