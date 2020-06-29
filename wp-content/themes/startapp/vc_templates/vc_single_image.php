<?php
/**
 * Single Image | vc_single_image
 *
 * @var array                    $atts    Shortcode attributes
 * @var mixed                    $content Shortcode content
 * @var WPBakeryShortCode_VC_Row $this    Instance of a class
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
	'source'        => 'media',

	// media library
	'image'         => '',
	'size_media'    => 'full',
	'is_caption'    => 'disable',

	// external link
	'external_src'  => '',
	'size_external' => '',
	'caption'       => '',

	'link'          => '',
	'motion'        => '',
	'animation'     => '',
	'class'         => '',
), 'vc_single_image' ), $atts );

$source   = sanitize_key( $a['source'] );
$image    = '';
$caption  = '';
$template = '';

$is_link    = ( '' !== str_replace( array( 'http://', 'https://' ), '', $a['link'] ) );
$is_caption = false;
$is_motion  = ( ! empty( $a['motion'] ) );


/* Prepare the <img> */

if ( 'external' === $source && ! empty( $a['external_src'] ) ) {

	// show the image by external link

	list( $width, $height ) = array_map( 'absint', explode( 'x', $a['size_external'] ) );

	$image = startapp_get_tag( 'img', array(
		'src'    => esc_url( $a['external_src'] ),
		'alt'    => esc_html__( 'Single Image', 'startapp' ),
		'class'  => 'single-image with-external-link',
		'width'  => $width,
		'height' => $height,
	) );

	if ( ! empty( $a['caption'] ) ) {
		$is_caption = true;
		$caption    = esc_html( $a['caption'] );
	}

	unset( $width, $height );
} else {

	// show image from media library

	$attachment = startapp_get_attachment( (int) $a['image'] );
	$size       = startapp_get_image_size( $a['size_media'] );
	$image      = wp_get_attachment_image( (int) $a['image'], $size );

	if ( 'enable' === $a['is_caption'] && ! empty( $attachment['caption'] ) ) {
		$is_caption = true;
		$caption    = esc_html( $attachment['caption'] );
	}

	unset( $attachment, $size );
}

if ( empty( $image ) ) {
	return;
}


/* Prepare classes & animation */

$aos   = ( ! empty( $a['animation'] ) ) ? esc_attr( $a['animation'] ) : '';
$class = startapp_get_classes( array(
	'single-image',
	$is_caption ? 'wp-caption' : '',
	$is_link ? 'linked-image' : '',
	$is_motion ? 'loop-animation' : '',
	$is_motion ? 'type-' . esc_attr( $a['motion'] ) : '',
	$a['class'],
) );


/* Template with/without caption */

$r = array();
if ( $is_caption ) {

	// maybe wrap to link?
	// we have a caption, so we do not require animation here
	if ( $is_link ) {
		$href  = startapp_esc_url( $a['link'] );
		$image = startapp_get_tag( 'a', array( 'href' => $href ), $image );
		unset( $href );
	}

	$attr = startapp_get_attr( array(
		'class'    => esc_attr( $class ),
		'data-aos' => $aos
	) );

	$template = '
	<figure {attr}>
		{image}
		<figcaption class="wp-caption-text">{caption}</figcaption>
	</figure>
	';

	$r['{image}']   = $image;
	$r['{attr}']    = $attr;
	$r['{caption}'] = $caption;

	unset( $attr );
} else {

	// we haven't got a caption, so inject the custom classes directly to <img>
	$image = str_replace( 'class="', 'class="' . $class . ' ', $image );

	// maybe wrap to link?
	// we haven't got a caption, so we should add animation here
	if ( $is_link ) {
		$href  = startapp_esc_url( $a['link'] );
		$image = startapp_get_tag( 'a', array( 'href' => $href, 'data-aos' => $aos ), $image );
		unset( $href );
	} else {
		// but if link is missing, we should inject animation directly to <img>
		$image = preg_replace( '/(class=".+?")/u', '$1 data-aos="' . $aos . '"', $image, 1 );
	}

	$template = '{image}';

	$r['{image}'] = $image;
}

echo str_replace( array_keys( $r ), array_values( $r ), $template );
