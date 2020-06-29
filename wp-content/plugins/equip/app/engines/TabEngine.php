<?php

namespace Equip\Engine;

use Equip\Factory;
use Equip\Layout\Layout;
use Equip\Layout\TabLayout;

/**
 * Class TabEngine
 * @package Equip\Engine
 */
class TabEngine extends Engine {

	/**
	 * Start parent element wrapper
	 *
	 * @param string               $slug   Element unique name
	 * @param \Equip\Layout\Layout $layout Current layout scope
	 *
	 * @return void
	 */
	public function before_elements( $slug, $layout ) {
		$settings = $layout->get_settings();

		// tabs navigation, should fires only once
		if ( ! $layout->parent->get_flag( 'navigation' ) ) {
			$this->renderTabs( $layout );

			// mark tabs navigation done to prevent multiple tabs
			$layout->parent->set_flag( 'navigation', true );
		}

		// wrap every panel into div.tab-pane wrapper
		$isA = true === (bool) $settings['is_active'];
		$tab = [
			'role'  => 'tabpanel',
			'id'    => esc_attr( 'equip_' . $settings['id'] ),
			'class' => esc_attr( 'tab-pane transition fade' . ( $isA ? ' active in' : '' ) ),
		];

		echo '<div ', equip_get_attr( $tab ), '>';
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
		$i = (int) $layout->parent()->get_setting( '_current_tab_index', 0 );
		$n = (int) $layout->parent()->get_setting( '_tabs_num' );

		$i ++;
		if ( $i < $n ) {
			$layout->parent()->set_setting( '_current_tab_index', $i );

			return;
		}

		echo '</div>'; // close div.tab-content
		echo '</div>'; // close div.equip-tabs
	}

	/**
	 * Show the tabs navigation section
	 *
	 * @param Layout $layout
	 */
	private function renderTabs( $layout ) {
		$tabs = $layout->parent()->elements;
		$tabs = array_filter( $tabs, function ( $tab ) {
			return ( $tab instanceof TabLayout );
		} );

		if ( empty( $tabs ) ) {
			return;
		}

		// count the number of tabs
		// this is required to close tags correctly
		// @see after_elements()
		$layout->parent->set_setting( '_tabs_num', count( $tabs ) );

		echo '<div class="equip-tabs">';
		echo '<ul class="tabs nav-tabs" role="tablist">';

		/** @var TabLayout $tab */
		foreach ( $tabs as $i => $tab ) {
			$isActive = ( (bool) $tab->get_setting( 'is_active' ) || $i === 0 );
			printf( '<li %3$s><a href="#%1$s" role="tab" data-toggle="tab">%2$s</a></li>',
				esc_attr( 'equip_' . $tab->get_setting( 'id' ) ),
				esc_html( $tab->get_setting( 'title' ) ),
				$isActive ? 'class="active"' : ''
			);
			unset( $isActive );
		}
		unset( $tab, $i );

		echo '</ul>';
		echo '<div class="tab-content">';
	}
}