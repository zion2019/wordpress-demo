<?php
/**
 * Wrapper shortcode for vc_tta_tabs & vc_tta_accordion
 *
 * @var array                                                            $atts    Shortcode attributes
 * @var mixed                                                            $content Shortcode content
 * @var WPBakeryShortCode_VC_Tta_Accordion|WPBakeryShortCode_VC_Tta_Tabs $this
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
	// for tabs & tour
	'alignment'      => 'left',
	'position'       => 'left',

	// for accordions
	'active_section' => 1,

	// both
	'skin'           => 'dark',
	'animation'      => '',
	'class'          => '',
	'css'            => '',
	'unique'         => startapp_get_unique_id( 'tta-' ),
), 'vc_tta_global' ), $atts );

$this->resetVariables( $a, $content );
$this->setGlobalTtaInfo();

// without this tabs & accordions won't work!
$content = $this->getTemplateVariable( 'content' );

/*
 * Tabs
 */
if ( 'vc_tta_tabs' === $this->shortcode ) {
	$alignment = sanitize_key( $a['alignment'] );
	$class     = startapp_get_classes( array(
		'tabs',
		'tabs-horizontal',
		'tabs-horizontal-' . $alignment,
		trim( vc_shortcode_custom_css_class( $a['css'] ) ),
		$a['class'],
	) );

	$tabs = array();
	foreach ( WPBakeryShortCode_VC_Tta_Section::$section_info as $nth => $tab ) {
		$is_active = ( $nth === 0 );

		$title = esc_html( $tab['title'] );
		if ( 'enable' === $tab['is_icon'] ) {
			$lib  = esc_attr( $tab['icon_library'] );
			$icon = startapp_get_tag( 'i', array( 'class' => esc_attr( $tab["icon_{$lib}"] ) ), '' );

			// enqueue the stylesheet
			startapp_vc_enqueue_icon_font( $lib );

			if ( 'left' === $tab['icon_position'] ) {
				$title = "{$icon} {$title}"; // note, there is a whitespace after the icon
			} else {
				$title = "{$title} {$icon}";
			}
			unset( $lib, $icon );
		}

		$attr = array(
			'href'        => '#' . esc_attr( $tab['tab_id'] ),
			'role'        => 'tab',
			'data-toggle' => 'tab',
		);

		$link   = startapp_get_tag( 'a', $attr, $title );
		$tabs[] = startapp_get_tag( 'li', array( 'class' => $is_active ? 'active' : '' ), $link );
		unset( $is_active, $title, $attr, $link );
	}
	unset( $nth, $tab );

	$tpl = <<<'TPL'
<div {attr}>
	<ul class="nav-tabs nav-tabs-{skin} text-{alignment}" role="tablist">
		{tabs}
	</ul>
	<div class="tab-content text-{skin}">
		{content}
	</div>
</div>
TPL;

	$attr = startapp_get_attr( array(
		'id'       => esc_attr( $a['unique'] ),
		'class'    => esc_attr( $class ),
		'data-aos' => ( ! empty( $a['animation'] ) ) ? esc_attr( $a['animation'] ) : '',
	) );

	$r = array(
		'{attr}'      => $attr,
		'{skin}'      => sanitize_key( $a['skin'] ),
		'{alignment}' => $alignment,
		'{tabs}'      => implode( '', $tabs ),
		'{content}'   => $content,
	);

	echo str_replace( array_keys( $r ), array_values( $r ), $tpl );
	unset( $tpl, $right, $r, $t );
}

/*
 * Tour
 */
if ( 'vc_tta_tour' === $this->shortcode ) {
	$position  = sanitize_key( $a['position'] );
	$alignment = sanitize_key( $a['alignment'] );
	$class     = startapp_get_classes( array(
		'tabs',
		'tabs-vertical',
		'tabs-vertical-' . $position,
		trim( vc_shortcode_custom_css_class( $a['css'] ) ),
		$a['class'],
	) );

	$tabs = array();
	foreach ( WPBakeryShortCode_VC_Tta_Section::$section_info as $nth => $tab ) {
		$is_active = ( $nth === 0 );

		$title = esc_html( $tab['title'] );
		if ( 'enable' === $tab['is_icon'] && function_exists( 'vc_icon_element_fonts_enqueue' ) ) {
			$lib  = esc_attr( $tab['icon_library'] );
			$icon = startapp_get_tag( 'i', array( 'class' => esc_attr( $tab["icon_{$lib}"] ) ), '' );

			// enqueue the stylesheet
			startapp_vc_enqueue_icon_font( $lib );

			if ( 'left' === $tab['icon_position'] ) {
				$title = "{$icon} {$title}"; // note, there is a whitespace after the icon
			} else {
				$title = "{$title} {$icon}";
			}
			unset( $lib, $icon );
		}

		$attr = array(
			'href'        => '#' . esc_attr( $tab['tab_id'] ),
			'role'        => 'tab',
			'data-toggle' => 'tab',
		);

		$link   = startapp_get_tag( 'a', $attr, $title );
		$tabs[] = startapp_get_tag( 'li', array( 'class' => $is_active ? 'active' : '' ), $link );
		unset( $is_active, $title, $attr, $link );
	}
	unset( $nth, $tab );

	$left = <<<'LEFT'
<div {attr}>
	<ul class="nav-tabs nav-tabs-{skin} text-{alignment}" role="tablist">
		{tabs}
	</ul>
	<div class="tab-content text-{skin}">
		{content}
	</div>
</div>
LEFT;

	$right = <<<'RIGHT'
<div {attr}>
	<div class="tab-content text-{skin}">
		{content}
	</div>
	<ul class="nav-tabs nav-tabs-{skin} text-{alignment}" role="tablist">
		{tabs}
	</ul>
</div>
RIGHT;

	$attr = startapp_get_attr( array(
		'id'       => esc_attr( $a['unique'] ),
		'class'    => esc_attr( $class ),
		'data-aos' => ( ! empty( $a['animation'] ) ) ? esc_attr( $a['animation'] ) : '',
	) );

	$t = ( 'right' === $position ) ? $right : $left;
	$r = array(
		'{attr}'      => $attr,
		'{skin}'      => sanitize_key( $a['skin'] ),
		'{alignment}' => $alignment,
		'{tabs}'      => implode( '', $tabs ),
		'{content}'   => $content,
	);

	echo str_replace( array_keys( $r ), array_values( $r ), $t );
	unset( $left, $right, $r, $t );
}

/*
 * Accordion
 */
if ( 'vc_tta_accordion' == $this->shortcode ) {
	$class = startapp_get_classes( array(
		'panel-group',
		'panel-group-' . sanitize_key( $a['skin'] ),
		trim( vc_shortcode_custom_css_class( $a['css'] ) ),
		$a['class'],
	) );

	$attr = startapp_get_attr( array(
		'id'       => esc_attr( $a['unique'] ),
		'class'    => esc_attr( $class ),
		'data-aos' => ( ! empty( $a['animation'] ) ) ? esc_attr( $a['animation'] ) : '',
	) );

	echo '<div ', $attr, '>';
	echo startapp_content_encode( $content );
	echo '</div>';

	unset( $class, $attr );
}
