<?php
/**
 * Progress Bars | startapp_progress_bars
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
	'skin'         => 'dark',
	'is_units'     => 'no',
	'is_animation' => 'no',
	'bars'         => '',
	'animation'    => '',
	'class'        => '',
), $shortcode ), $atts );

$bars = json_decode( urldecode( $a['bars'] ), true );
if ( empty( $bars ) ) {
	return;
}

$skin         = sanitize_key( $a['skin'] );
$is_units     = ( 'yes' === $a['is_units'] );
$is_animation = ( 'yes' === $a['is_animation'] );
$is_light     = ( 'light' === $a['skin'] );

$class = startapp_get_classes( array(
	'progress-bars',
	$is_units ? 'with-units' : '',
	$is_animation ? 'animated' : '',
	$a['class'],
) );

$attr = startapp_get_attr( array(
	'class'    => esc_attr( $class ),
	'data-aos' => ( ! empty( $a['animation'] ) ) ? esc_attr( $a['animation'] ) : '',
) );

$template = <<<'TPL'
<div {attr}>
	<div class="progress-bar">
		{value}
		{bar}
		<span class="rails"></span>
	</div>
	{label}
</div>
TPL;

echo '<div ', $attr, '>';
foreach ( (array) $bars as $bar ) {
	$bar = wp_parse_args( $bar, array(
		'value'        => '',
		'label'        => '',
		'color'        => 'default',
		'color_custom' => '',
	) );

	if ( empty( $bar['value'] ) || ! is_numeric( $bar['value'] ) ) {
		continue;
	}

	$value     = absint( $bar['value'] );
	$value     = ( $value > 100 ) ? 100 : $value;
	$color     = sanitize_key( $bar['color'] );
	$is_custom = ( 'custom' == $bar['color'] );
	$width     = $is_animation ? '' : $value . '%';

	// note: there is a space before percent
	$value_units = sprintf( '<i>%1$s</i>%2$s', $value, $is_units ? ' %' : '' );

	$_class = startapp_get_classes( array(
		'progress',
		'progress-' . $skin,
		$is_animation ? 'progress-animated' : '',
	) );

	// style for .value
	$_v_style = array();
	if ( $is_custom ) {
		$_v_style['color'] = esc_attr( $bar['color_custom'] );
	}

	if ( ! $is_animation ) {
		$_v_style['width'] = $value . '%';
	}

	$_v_style = startapp_css_declarations( $_v_style );

	// .value tag
	$_value = startapp_get_tag( 'span', array(
		'class' => $is_light ? 'value text-light' : 'value text-' . $color,
		'style' => $_v_style,
	), $value_units );
	unset( $_v_style, $value_units );

	// style for .bar
	$_b_style = array();
	if ( $is_custom ) {
		$_b_style['background-color'] = esc_attr( $bar['color_custom'] );
	}

	if ( ! $is_animation ) {
		$_b_style['width'] = $value . '%';
	}

	$_b_style = startapp_css_declarations( $_b_style );

	// .bar tag
	$_bar = startapp_get_tag( 'span', array(
		'class' => 'bar bg-' . $color,
		'style' => $_b_style,
	), '' );
	unset( $_b_style );

	$_attr = startapp_get_attr( array(
		'class'              => esc_attr( $_class ),
		'data-current-value' => $value,
	) );

	$r = array(
		'{attr}'  => $_attr,
		'{value}' => $_value,
		'{bar}'   => $_bar,
		'{label}' => startapp_get_text( esc_html( $bar['label'] ), '<h4 class="progress-bar-label">', '</h4>' ),
	);
	unset( $_class, $_attr, $_value, $_bar );

	echo str_replace( array_keys( $r ),  array_values( $r ), $template );
	unset( $value, $color, $is_custom, $width, $r );
}
echo '</div>';
