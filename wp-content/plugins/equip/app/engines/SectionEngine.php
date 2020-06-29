<?php

namespace Equip\Engine;

use Equip\Factory;
use Equip\Layout\AnchorLayout;
use Equip\Layout\SectionLayout;

/**
 * Engine for rendering "section" layout
 *
 * @see     \Equip\Layout\SectionLayout
 *
 * @author  8guild
 * @package Equip\Engine
 */
class SectionEngine extends Engine {

	/**
	 * Start parent element wrapper
	 *
	 * @param string        $slug   Element unique name
	 * @param SectionLayout $layout Current layout scope
	 *
	 * @return void
	 */
	public function before_elements( $slug, $layout ) {
		$settings = $layout->get_settings();

		// render navigation, should fires only once
		// flags used to prevent multiple navigation tabs
		if ( ! $layout->parent->get_flag( 'navigation' ) ) {
			$this->renderTabs( $layout );
			$layout->parent->set_flag( 'navigation', true );
		}

		// wrap every panel into div.tab-pane wrapper
		$isActive = ( true === $settings['is_active'] );
		$section  = [
			'role'         => 'tabpanel',
			'id'           => esc_attr( 'equip_' . $settings['id'] ),
			'class'        => esc_attr( 'tab-pane transition fade' . ( $isActive ? ' active in' : '' ) ),
			'data-element' => 'section',
		];

		echo '<div ', equip_get_attr( $section ), '>';
		echo equip_get_text( esc_html( $settings['title'] ), '<h2>', '</h2>' );
	}

	/**
	 * Start nested element wrapper
	 *
	 * @param string               $slug     Element unique name
	 * @param array                $settings Element settings
	 * @param \Equip\Layout\Layout $layout   Current layout scope
	 *
	 * @return void
	 */
	public function before_element( $slug, $settings, $layout ) {
		return;
	}

	/**
	 * Do the element
	 *
	 * @param string               $slug     Element unique name
	 * @param array                $settings Element settings
	 * @param mixed                $values   Raw element value
	 * @param \Equip\Layout\Layout $layout   Current layout scope
	 */
	public function do_element( $slug, $settings, $values, $layout ) {
		$engine = Factory::engine( $layout );
		$engine->render( $slug, $layout, $values );
	}

	/**
	 * End nested element wrapper
	 *
	 * @param string               $slug     Element unique name
	 * @param array                $settings Element settings
	 * @param \Equip\Layout\Layout $layout   Current layout scope
	 *
	 * @return void
	 */
	public function after_element( $slug, $settings, $layout ) {
		return;
	}

	/**
	 * End parent element wrapper
	 *
	 * @param string               $slug   Element unique name
	 * @param \Equip\Layout\Layout $layout Current layout scope
	 *
	 * @return void
	 */
	public function after_elements( $slug, $layout ) {
		echo '</div>'; // close div.tab-pane

		// global wrapper
		$i = (int) $layout->parent->get_setting( '_current_section_index', 0 );
		$n = (int) $layout->parent->get_setting( '_sections_num' );

		$i ++;
		if ( $i < $n ) {
			$layout->parent->set_setting( '_current_section_index', $i );

			return;
		}

		echo '</div>'; // close div.tab-content
		echo '</div>'; // close div.equip-content
	}

	/**
	 * Show the tabs navigation section
	 *
	 * @param SectionLayout $layout
	 */
	private function renderTabs( $layout ) {
		$sections = $layout->parent()->elements;
		$sections = array_filter( $sections, function ( $section ) {
			return ( $section instanceof SectionLayout );
		} );

		if ( empty( $sections ) ) {
			return;
		}

		// count the number of sections
		// this is required to close tags correctly
		// @see after_elements()
		$layout->parent->set_setting( '_sections_num', count( $sections ) );

		// side section
		echo '<aside class="equip-sidebar">';

		/**
		 * Allows to add content in the Sections' header part
		 *
		 * Example markup:
		 * <div class="equip-project-name">
		 *   <h1>{title} <small>{version}</small></h1>
		 * </div>
		 *
		 * @param SectionLayout $layout
		 */
		do_action( 'equip/engine/element/section/header', $layout );

		echo '<nav class="equip-navi">';
		echo '<div class="equip-navi-inner">';
		echo '<ul class="nav nav-tabs" role="tablist">';

		/** @var SectionLayout $section */
		foreach ( $sections as $i => $section ) {
			$isActive = ( (bool) $section->get_setting( 'is_active' ) || $i === 0 );
			echo '<li', ( $isActive ? ' class="active"' : '' ), '>';
			printf( '<a href="#%1$s" role="tab" data-toggle="tab">%3$s%2$s</a>',
				esc_attr( 'equip_' . $section->get_setting( 'id' ) ),
				esc_html( $section->get_setting( 'title' ) ),
				equip_get_text( esc_attr( $section->get_setting( 'icon' ) ), '<i class="', '"></i>' )
			);

			// section may contains anchors
			$anchors = $section->elements;
			$anchors = array_filter( (array) $anchors, function ( $element ) {
				return ( $element instanceof AnchorLayout );
			} );

			if ( ! empty( $anchors ) ) {
				echo '<ul class="sub-navi">';

				/** @var AnchorLayout $anchor */
				foreach ( $anchors as $k => $anchor ) {
					printf( '<li %3$s><a href="#%1$s">%2$s</a></li>',
						esc_attr( 'equip_' . $anchor->get_setting( 'id' ) ),
						esc_html( $anchor->get_setting( 'title' ) ),
						( $k === 0 ) ? 'class="active"' : ''
					);
				}
				unset( $k, $anchor );
				echo '</ul>';
			}

			echo '</li>';
			unset( $isActive, $anchors );
		}
		unset( $i, $section );

		echo '</ul>'; // ul.nav.nav-tabs
		echo '</div>'; // div.equip-navi-inner
		echo '</nav>'; // nav.equip-navi
		echo '</aside>';

		// content section
		echo '<div class="equip-content">';
		echo '<div class="tab-content">';
	}
}