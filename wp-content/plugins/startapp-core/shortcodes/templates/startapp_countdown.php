<?php
/**
 * Countdown | startapp_countdown
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
	'date'         => '',
	'color'        => 'default',
	'color_custom' => '',
	'animation'    => '',
	'class'        => '',
), $shortcode ), $atts );

if ( empty( $a['date'] ) ) {
	return;
}

$is_custom    = ( 'custom' === $a['color'] );
$color        = sanitize_key( $a['color'] );
$color_custom = sanitize_hex_color( $a['color_custom'] );

$box_custom_color   = '';
$digit_custom_color = '';
if ( $is_custom ) {
	$box_custom_color = startapp_color_rgba( $color_custom, '.05' ); // 5% alpha channel hard-coded
	$box_custom_color = startapp_css_background_color( $box_custom_color );

	$digit_custom_color = startapp_css_color( $color_custom );
}

try {
	$datetime = new DateTime( $a['date'] );
	$date = $datetime->format( 'm/d/Y H:i:s' );
} catch ( Exception $e ) {
	trigger_error( $e->getMessage() );

	return;
}

$class = startapp_get_classes( array(
	'countdown',
	'skin-' . $color,
	$a['class'],
) );

$wrap_attr = array(
	'class'          => esc_attr( $class ),
	'data-aos'       => ( ! empty( $a['animation'] ) ) ? esc_attr( $a['animation'] ) : '',
	'data-date-time' => $date,
);

$box_attr   = array( 'style' => $box_custom_color );
$digit_attr = array( 'style' => $digit_custom_color );

?>
<div <?php echo startapp_get_attr( $wrap_attr ); ?>>
	<div class="row">
		<div class="col-md-3 col-sm-6">
			<div class="box" <?php echo startapp_get_attr( $box_attr ); ?>>
				<div class="days digit" <?php echo startapp_get_attr( $digit_attr ); ?>>00</div>
				<h4 class="days_ref description"><?php _e( 'Days', 'startapp' ) ?></h4>
			</div>
		</div>
		<div class="col-md-3 col-sm-6">
			<div class="box" <?php echo startapp_get_attr( $box_attr ); ?>>
				<div class="hours digit" <?php echo startapp_get_attr( $digit_attr ); ?>>00</div>
				<h4 class="hours_ref description"><?php _e( 'Hours', 'startapp' ); ?></h4>
			</div>
		</div>
		<div class="col-md-3 col-sm-6">
			<div class="box" <?php echo startapp_get_attr( $box_attr ); ?>>
				<div class="minutes digit" <?php echo startapp_get_attr( $digit_attr ); ?>>00</div>
				<h4 class="minutes_ref description"><?php _e( 'Minutes', 'startapp' ); ?></h4>
			</div>
		</div>
		<div class="col-md-3 col-sm-6">
			<div class="box" <?php echo startapp_get_attr( $box_attr ); ?>>
				<div class="seconds digit" <?php echo startapp_get_attr( $digit_attr ); ?>>00</div>
				<h4 class="seconds_ref description"><?php _e( 'Seconds', 'startapp' ); ?></h4>
			</div>
		</div>
	</div>
</div>
