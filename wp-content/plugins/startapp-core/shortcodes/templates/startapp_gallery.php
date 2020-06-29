<?php
/**
 * Gallery | startapp_gallery
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
	'images'     => '',
	'is_caption' => 'disable',
	'grid_type'  => 'masonry-grid',
	'columns'    => 3,
	'animation'  => '',
	'class'      => '',
), $shortcode ), $atts );

if ( empty( $a['images'] ) ) {
	return;
}


/* Prepare variables */

$images    = wp_parse_id_list( $a['images'] );
$grid_type = esc_attr( $a['grid_type'] );

$is_caption   = ( 'enable' === $a['is_caption'] );
$is_justified = ( 'grid-justified' === $a['grid_type'] );

$allowed_cols  = range( 1, 6 );
$selected_cols = absint( $a['columns'] );
$columns       = in_array( $selected_cols, $allowed_cols, true ) ? $selected_cols : 3;
unset( $allowed_cols, $selected_cols );

$grid_class = startapp_get_classes( array(
	'gallery-grid',
	$grid_type,
	( ! $is_justified ) ? 'col-' . $columns : '',
	$a['class'],
) );

$grid_attr = startapp_get_attr( array(
	'class'    => esc_attr( $grid_class ),
	'data-aos' => ( ! empty( $a['animation'] ) ) ? esc_attr( $a['animation'] ) : '',
) );


/* Prepare gallery items */

$items    = array();
$template = <<<'TEMPLATE'
<div class="grid-item">
	<a href="{href}" class="gallery-tile" data-size="{size}">
		<figure class="{class}">
			{image}
			{caption}
		</figure>
	</a>
</div>
TEMPLATE;

foreach ( $images as $image_id ) {
	$caption = '';
	if ( $is_caption ) {
		$attachment = startapp_get_attachment( $image_id );
		if ( ! empty( $attachment['caption'] ) ) {
			$caption = sprintf( '<figcaption class="wp-caption-text">%1$s<span>%2$s</span></figcaption>',
				esc_html( $attachment['caption'] ),
				esc_html( $attachment['description'] )
			);
		}
		unset( $attachment );
	}

	// in case of justified grid use background image
	if ( $is_justified ) {
		$image = startapp_get_tag( 'div', array(
			'class' => 'image',
			'style' => startapp_css_background_image( $image_id, 'large' ),
		), '' );
	} else {
		$image = wp_get_attachment_image( $image_id, 'large' );
	}

	// prepare the size
	$metadata = wp_get_attachment_metadata( $image_id, true );
	if ( empty( $metadata['width'] ) || empty( $metadata['height'] ) ) {
		$metadata = wp_parse_args( $metadata, array(
			'width'  => get_option( 'large_size_w', 1024 ),
			'height' => get_option( 'large_size_h', 1024 ),
		) );
	}

	$size = implode( 'x', array( $metadata['width'], $metadata['height'] ) );

	$r = array(
		'{href}'    => esc_url( startapp_get_image_src( $image_id ) ),
		'{class}'   => $is_caption ? 'wp-caption' : 'without-caption',
		'{size}'    => $size,
		'{image}'   => $image,
		'{caption}' => $caption,
	);

	$items[] = str_replace( array_keys( $r ), array_values( $r ), $template );
	unset( $r, $caption, $image, $metadata, $size );
}

$items = implode( '', $items );


/* Output */

if ( $is_justified ) {
	echo "<div {$grid_attr}>{$items}</div>";
} else {
	echo "
	<div {$grid_attr}>
		<div class=\"gutter-sizer\"></div>
		<div class=\"grid-sizer\"></div>
		{$items}
	</div>
	";
}
