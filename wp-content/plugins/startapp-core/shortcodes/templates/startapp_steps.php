<?php
/**
 * Steps | startapp_steps
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
	'skin'                     => 'dark',

	// step 1
	'first_label'              => __( 'Step 1', 'startapp' ),
	'first_type'               => 'image',
	'first_icon_library'       => 'fontawesome',
	'first_icon_color'         => 'default',
	'first_icon_color_custom'  => '',
	'first_icon_fontawesome'   => '',
	'first_icon_material'      => '',
	'first_icon_custom'        => '',
	'first_image'              => '',
	'first_title'              => '',
	'first_description'        => '',
	'first_hover'              => '#000000',
	'first_is_button'          => 'no',
	'first_btn_text'           => '',
	'first_btn_link'           => '',
	'first_btn_type'           => 'solid',
	'first_btn_shape'          => 'rounded',
	'first_btn_color'          => 'default',
	'first_btn_color_custom'   => '',
	'first_btn_size'           => 'default',
	'first_btn_alignment'      => 'inline',
	'first_btn_is_full'        => 'no',
	'first_btn_is_waves'       => 'disable',
	'first_btn_waves_skin'     => 'light',
	'first_btn_class'          => '',

	// step 2
	'second_label'             => __( 'Step 2', 'startapp' ),
	'second_type'              => 'image',
	'second_icon_library'      => 'fontawesome',
	'second_icon_color'        => 'default',
	'second_icon_color_custom' => '',
	'second_icon_fontawesome'  => '',
	'second_icon_material'     => '',
	'second_icon_custom'       => '',
	'second_image'             => '',
	'second_title'             => '',
	'second_description'       => '',
	'second_hover'             => '#000000',
	'second_is_button'         => 'no',
	'second_btn_text'          => '',
	'second_btn_link'          => '',
	'second_btn_type'          => 'solid',
	'second_btn_shape'         => 'rounded',
	'second_btn_color'         => 'default',
	'second_btn_color_custom'  => '',
	'second_btn_size'          => 'default',
	'second_btn_alignment'     => 'inline',
	'second_btn_is_full'       => 'no',
	'second_btn_is_waves'      => 'disable',
	'second_btn_waves_skin'    => 'light',
	'second_btn_class'         => '',

	// step 3
	'third_label'              => __( 'Step 3', 'startapp' ),
	'third_type'               => 'image',
	'third_icon_library'       => 'fontawesome',
	'third_icon_color'         => 'default',
	'third_icon_color_custom'  => '',
	'third_icon_fontawesome'   => '',
	'third_icon_material'      => '',
	'third_icon_custom'        => '',
	'third_image'              => '',
	'third_title'              => '',
	'third_description'        => '',
	'third_hover'              => '#000000',
	'third_is_button'          => 'no',
	'third_btn_text'           => '',
	'third_btn_link'           => '',
	'third_btn_type'           => 'solid',
	'third_btn_shape'          => 'rounded',
	'third_btn_color'          => 'default',
	'third_btn_color_custom'   => '',
	'third_btn_size'           => 'default',
	'third_btn_alignment'      => 'inline',
	'third_btn_is_full'        => 'no',
	'third_btn_is_waves'       => 'disable',
	'third_btn_waves_skin'     => 'light',
	'third_btn_class'          => '',

	// step 4
	'fourth_label'             => __( 'Step 4', 'startapp' ),
	'fourth_type'              => 'image',
	'fourth_icon_library'      => 'fontawesome',
	'fourth_icon_color'        => 'default',
	'fourth_icon_color_custom' => '',
	'fourth_icon_fontawesome'  => '',
	'fourth_icon_material'     => '',
	'fourth_icon_custom'       => '',
	'fourth_image'             => '',
	'fourth_title'             => '',
	'fourth_description'       => '',
	'fourth_hover'             => '#000000',
	'fourth_is_button'         => 'no',
	'fourth_btn_text'          => '',
	'fourth_btn_link'          => '',
	'fourth_btn_type'          => 'solid',
	'fourth_btn_shape'         => 'rounded',
	'fourth_btn_color'         => 'default',
	'fourth_btn_color_custom'  => '',
	'fourth_btn_size'          => 'default',
	'fourth_btn_alignment'     => 'inline',
	'fourth_btn_is_full'       => 'no',
	'fourth_btn_is_waves'      => 'disable',
	'fourth_btn_waves_skin'    => 'light',
	'fourth_btn_class'         => '',

	'animation' => '',
	'class'     => '',
), $shortcode ), $atts );

$steps   = array();
$steps[] = startapp_parse_array( $a, 'first_' );
$steps[] = startapp_parse_array( $a, 'second_' );
$steps[] = startapp_parse_array( $a, 'third_' );
$steps[] = startapp_parse_array( $a, 'fourth_' );

$skin     = sanitize_key( $a['skin'] );
$is_light = ( 'light' === $a['skin'] );

$class = startapp_get_classes( array(
	'steps',
	'steps-' . $skin,
	$a['class'],
) );

$attr = startapp_get_attr( array(
	'class'    => esc_attr( $class ),
	'data-aos' => ( ! empty( $a['animation'] ) ) ? esc_attr( $a['animation'] ) : '',
) );

$template = <<<'TPL'
<div class="step">
	{label}
	<div class="step-body">
		<div class="icon-box icon-box-bg-enabled hover-fade text-center">
			<span class="icon-box-backdrop" style="background-color: {hover};"></span>
			{icon}
			{title}
			<div class="icon-box-description">
				<p class="text-gray">{desc}</p>
				{button}
			</div>
		</div>
	</div>
</div>
TPL;

echo '<div ', $attr, '><div class="inner">';
foreach ( $steps as $step ) {
	if ( empty( $step['label'] ) ) {
		continue;
	}

	$icon   = '';
	$button = '';

	// prepare the icon
	if ( 'image' === $step['type'] ) {
		$icon = startapp_get_text( wp_get_attachment_image( (int) $step['image'], 'full' ),
			'<div class="icon-box-icon">', '</div>'
		);
	} elseif ( 'icon' === $step['type'] ) {
		$i   = startapp_parse_array( $step, 'icon_' );
		$lib = esc_attr( $i['library'] );

		$icon = startapp_get_tag( 'i', array(
			'class' => esc_attr( $i[ $lib ] ),
			'style' => ( 'custom' === $i['color'] ) ? startapp_css_color( $i['color_custom'] ) : '',
		), '' );

		$icon = sprintf( '<div class="icon-box-icon text-%1$s">%2$s</div>',
			sanitize_key( $i['color'] ), $icon
		);
		unset( $i, $lib );
	}

	// prepare the button
	if ( 'yes' === $step['is_button'] ) {
		$b = startapp_parse_array( $step, 'btn_' );
		$s = startapp_shortcode_build( 'startapp_button', $b );

		$button = startapp_do_shortcode( $s );
		unset( $b, $s );
	}

	$r = array(
		'{label}'  => startapp_get_text( esc_html( $step['label'] ), '<h4 class="step-label">', '</h4>' ),
		'{hover}'  => $is_light ? '#fff' : esc_attr( $step['hover'] ),
		'{icon}'   => $icon,
		'{title}'  => startapp_get_text( esc_html( $step['title'] ), '<h3 class="icon-box-title">', '</h3>' ),
		'{desc}'   => esc_html( $step['description'] ),
		'{button}' => $button,
	);

	echo str_replace( array_keys( $r ), array_values( $r ), $template );
	unset( $icon, $button, $r );
}
echo '</div></div>'; // close div.steps > div.inner
