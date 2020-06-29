<?php
/**
 * Testimonial | startapp_testimonial
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
	'background'              => 'none',
	'background_color'        => 'default', // if background = none
	'background_color_custom' => '',
	'alignment'               => 'left',
	'skin'                    => 'dark',
	'avatar'                  => '',
	'shape'                   => 'circle',
	'name'                    => '',
	'position'                => '',
	'company'                 => '',
	'company_link'            => '',
	'animation'               => '',
	'class'                   => '',
), $shortcode ), $atts );

$background = sanitize_key( $a['background'] );
$alignment  = sanitize_key( $a['alignment'] );
$skin       = sanitize_key( $a['skin'] );
$shape      = sanitize_key( $a['shape'] );
$color      = sanitize_key( $a['background_color'] );

$is_background = ( 'none' !== $a['background'] );
$is_company    = ( ! empty( $a['company'] ) );
$is_custom     = ( 'custom' === $a['background_color'] );
$is_avatar     = ( ! empty( $a['avatar'] ) );

$avatar   = $is_avatar ? wp_get_attachment_image( (int) $a['avatar'], 'full' ) : '';
$name     = esc_html( trim( $a['name'] ) );
$position = esc_html( trim( $a['position'] ) );
$company  = esc_html( trim( $a['company'] ) );

// may be add comma after the name
if ( ! empty( $position ) || ! empty( $company ) ) {
	$name = "{$name}, ";
}

// may be wrap company name into the link
if ( $is_company ) {
	$l = startapp_vc_parse_link( $a['company_link'] );
	$c = array();
	if ( ! empty( $l['url'] ) ) {
		$c['href']   = esc_url( trim( $l['url'] ) );
		$c['target'] = empty( $l['target'] ) ? '' : esc_attr( trim( $l['target'] ) );
		$c['title']  = empty( $l['title'] ) ? '' : esc_attr( trim( $l['title'] ) );
		$c['rel']    = empty( $l['rel'] ) ? '' : esc_attr( trim( $l['rel'] ) );

		$company = startapp_get_tag( 'a', $c, $company );
	}
	unset( $l, $c );
}

// prepare the avatar
if ( $is_avatar ) {
	$avatar = startapp_get_text( $avatar, '<div class="testimonial-author-ava">', '</div>' );
}

$class = startapp_get_classes( array(
	'testimonial',
	'testimonial-ava-' . $shape,
	$is_background ? 'testimonial-background' : '',
	$is_background ? 'bg-' . $color : '',
	'text-' . $skin,
	'text-' . $alignment,
	$a['class'],
) );

$attr = startapp_get_attr( array(
	'class'    => esc_attr( $class ),
	'data-aos' => ( ! empty( $a['animation'] ) ) ? esc_attr( $a['animation'] ) : '',
	'style'    => $is_custom ? startapp_css_background_color( $a['background_color_custom'] ) : '',
) );

$template = <<<'T'
<div {attr}>
	<div class="testimonial-body">
		{body}	
	</div>
	<div class="testimonial-cite">
		{avatar}	
		<div class="testimonial-author-info">
			{name} {position} {company}
		</div>
	</div>
</div>
T;

$r = array(
	'{attr}'     => $attr,
	'{body}'     => startapp_do_shortcode( $content, true ),
	'{avatar}'   => $avatar,
	'{name}'     => $name,
	'{position}' => $position,
	'{company}'  => $company,
);

echo str_replace( array_keys( $r ), array_values( $r ), $template );
