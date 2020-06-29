<?php
/**
 * Pricing Table | startapp_pricing_table
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
	'animation' => '',
	'class'     => '',
), $shortcode ), $atts );

/**
 * Filter the args for {@see get_posts()}
 *
 * @param array $args Arguments
 */
$posts = get_posts( apply_filters( 'startapp_pricing_posts_args', array(
	'post_type'      => 'startapp_pricing',
	'posts_per_page' => -1,
	'orderby'        => 'ID',
	'order'          => 'ASC',
) ) );

if ( empty( $posts ) ) {
	return;
}

/**
 * Documented in /cpt/class-startapp-cpt-pricing-table.php
 *
 * @see Startapp_CPT_Pricing_Table::do_properties_meta_box()
 */
$properties = get_terms( apply_filters( 'startapp_pricing_properties_args', array(
	'taxonomy'     => 'startapp_pricing_properties',
	'hide_empty'   => false,
	'hierarchical' => false,
	'orderby'      => 'term_id',
	'order'        => 'ASC',
) ) );

// collect posts properties
$props = array();
foreach ( $posts as $post ) {
	$props[ $post->ID ] = get_post_meta( $post->ID, '_startapp_plan_properties', true );
}
unset( $post );

// collect rows data
$rows = array();

// titles row
$rows['_titles'] = array_merge( array( '_title' ), array_map( function( $post ) {
	return esc_html( $post->post_title );
}, $posts ) );

// features rows
/** @var WP_Term $property */
foreach ( $properties as $property ) {
	// add property name as a first column value in row
	$rows[ $property->slug ][] = $property->name;

	/** @var WP_Post $post */
	foreach ( $posts as $post ) {
		// a single property value
		$_prop = empty( $props[ $post->ID ][ $property->slug ] ) ? '' : $props[ $post->ID ][ $property->slug ];

		// NOTE: use property name as key
		$rows[ $property->slug ][] = $_prop;
		unset( $_prop );
	}
	unset( $post );
}
unset( $property );

// buttons row
$rows['_buttons'] = array_merge( array( '_button' ), array_map( function( $post ) {
	$btn = startapp_get_meta( $post->ID, '_startapp_plan_button' );
	if ( empty( $btn ) || empty( $btn['url'] ) || empty( $btn['text'] ) ) {
		return '';
	}

	$btn['link'] = startapp_vc_build_link( array( 'url' => $btn['url'] ) );
	unset( $btn['url'] );

	return startapp_shortcode_build( 'startapp_button', $btn );
}, $posts ) );

// remove unnecessary
unset( $posts, $properties, $props );

$class = startapp_get_classes( array(
	'pricing-table',
	$a['class'],
) );

$attr = startapp_get_attr( array(
	'class'    => esc_attr( $class ),
	'data-aos' => ( ! empty( $a['animation'] ) ) ? esc_attr( $a['animation'] ) : '',
) );


/* Start output */

echo '<div ', $attr, '><table>';
foreach ( $rows as $name => $row ) {
	$is_featured = ( ! in_array( $name, array( '_titles', '_buttons' ) ) );

	echo '<tr>';
	foreach ( $row as $k => $cell ) {
		echo '<td>';

		if ( empty( $cell ) || 0 === strpos( $cell, '_' ) ) {
			// skip empty cells or started with underscores
			echo '';
		} elseif ( '_titles' === $name ) {
			// titles have their own markup
			echo startapp_get_text( esc_html( $cell ), '<h3 class="pricing-plan-name">', '</h3>' );
		} elseif ( $is_featured && 0 === $k ) {
			// feature titles have their own markup, too
			echo sprintf( '<div class="text-gray text-lg text-right">%s</div>', esc_html( $cell ) );
		} elseif ( $is_featured && 0 === strpos( $cell, '%' ) ) {
			// special keywords started from %
			switch ( $cell ) {
				case '%available':
					echo '<span class="pricing-mark available"><i class="material-icons check"></i></span>';
					break;

				case '%not-available':
					echo '<span class="pricing-mark not-available"><i class="material-icons clear"></i></span>';
					break;

				default:
					/**
					 * Convert the special keyword
					 *
					 * @param string $content
					 */
					echo apply_filters( 'startapp_pricing_table_special', $cell );
					break;
			}
		} elseif ( $is_featured && false !== strpos( $cell, PHP_EOL ) ) {
			// feature lists divided by newlines
			$list = explode( PHP_EOL, $cell );
			$list = array_map( function( $item ) {
				return '<li>' . wp_kses( trim( $item ), array( 'span' => array( 'class' => true ) ) ) . '</li>';
			}, $list );

			echo '<ul class="pricing-plan-features">', implode( '', $list ), '</ul>';
			unset( $list );
		} elseif ( '_buttons' === $name && false !== strpos( $cell, '[startapp_button' ) ) {
			// buttons are shortcodes, so
			echo startapp_do_shortcode( $cell );
		} else {
			 // in all other cases allow only span with classes
			echo wp_kses( $cell, array(
				'span' => array( 'class' => true ),
			) );
		}

		echo '</td>';
	}
	echo '</tr>';
}
echo '</table></div>';
