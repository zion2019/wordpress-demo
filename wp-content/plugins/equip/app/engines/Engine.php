<?php

namespace Equip\Engine;

use Equip\Layout\FieldLayout;

/**
 * Abstract engine
 *
 * @author  8guild
 * @package Equip
 */
abstract class Engine {

	/**
	 * Render the contents
	 *
	 * @param string               $slug   Element name
	 * @param \Equip\Layout\Layout $layout Layout for current scope
	 * @param mixed                $values Values from database. May be empty.
	 *
	 * @return bool
	 */
	public function render( $slug, $layout, $values = null ) {
		if ( empty( $slug ) ) {
			trigger_error( 'Engine::render(): Empty $slug not allowed.' );

			return false;
		}

		if ( empty( $values ) ) {
			$values = [];
		}

		/**
		 * Filter the layout
		 *
		 * @param \Equip\Layout\Layout $layout layout for current scope
		 * @param mixed                $values Values
		 * @param string               $slug   Element name
		 */
		$layout = apply_filters( 'equip/engine/layout', $layout, $values, $slug );

		/**
		 * Fires before the all elements in current layout scope
		 *
		 * @param \Equip\Layout\Layout $layout Element layout
		 * @param string               $slug   Element name
		 */
		do_action( 'equip/engine/elements/before', $layout, $slug );

		/**
		 * Fires before all elements in current layout scope
		 *
		 * Dynamic part refers to parent layout type, in other words to container elements.
		 * This may be a top-level layouts, like "metabox" or "options, or nested "row", "column", etc
		 *
		 * @param \Equip\Layout\Layout $layout Element layout
		 * @param string               $slug   Element name
		 */
		do_action( "equip/engine/elements/{$layout->type}/before", $layout, $slug );

		// extract the elements,
		// because we may still need the layout as is
		$elements = $layout->elements;

		$this->before_elements( $slug, $layout );

		do {
			/*
			 * Hack for Fields and Containers
			 *
			 * Equip does not support nested fields, so property "fields"
			 * should be empty array. In this case we use the same Layout
			 * object which we passed into the method.
			 *
			 * On the other hand empty "container" element, like Row, Column,
			 * etc may cause an infinite loop. So I just break the loop.
			 */
			if ( $layout instanceof FieldLayout ) {
				$element = $layout;
			} elseif ( empty( $elements ) ) {
				break;
			} else {
				$element = array_shift( $elements );
			}

			// skip hidden element
			if ( true === $element->get_setting( 'hidden' ) ) {
				continue;
			}

			/**
			 * Fires before the each element
			 *
			 * @param string               $slug     Element name
			 * @param array                $settings Element settings
			 * @param \Equip\Layout\Layout $layout   Element layout
			 */
			do_action( 'equip/engine/element/before', $slug, $element->get_settings(), $element );

			/**
			 * Fires before the each element
			 *
			 * Dynamic part refers to nested element layout type
			 *
			 * @see \Equip\Factory::engine
			 * @see "equip/factory/engine/map" filter
			 *
			 * @param string               $slug     Element name
			 * @param array                $settings Element settings
			 * @param \Equip\Layout\Layout $layout   Element layout
			 */
			do_action( "equip/engine/element/{$layout->type}/before", $slug, $element->get_settings(), $element );

			$this->before_element( $slug, $element->get_settings(), $element );
			$this->do_element( $slug, $element->get_settings(), $values, $element );
			$this->after_element( $slug, $element->get_settings(), $element );

			/**
			 * Fires at the end of each field
			 *
			 * Dynamic part refers to current layout type
			 *
			 * @see \Equip\Factory::engine
			 * @see "equip/factory/engine/map" filter
			 *
			 * @param string               $slug     Element name
			 * @param array                $settings Element settings
			 * @param \Equip\Layout\Layout $layout   Element layout
			 */
			do_action( "equip/engine/element/{$layout->type}/after", $slug, $element->get_settings(), $element );

			/**
			 * Fires at the end of each field
			 *
			 * @param string               $slug    Element name
			 * @param array                $layout  Element settings
			 * @param \Equip\Layout\Layout $element Element layout
			 */
			do_action( 'equip/engine/element/after', $slug, $element->get_settings(), $element );

		} while ( ! empty( $elements ) );

		$this->after_elements( $slug, $layout );

		/**
		 * Fires after all elements in current layout scope
		 *
		 * Dynamic part refers to parent layout type, in other words to container elements.
		 * This may be a top-level layouts, like "metabox" or "options, or nested "row", "column", etc
		 *
		 * @param \Equip\Layout\Layout $layout Element layout
		 * @param string               $slug   Element name
		 */
		do_action( "equip/engine/elements/{$layout->type}/after", $layout, $slug );

		/**
		 * Fires at the end of fields rendering
		 *
		 * @param \Equip\Layout\Layout $layout Element layout
		 * @param string               $slug   Element name
		 */
		do_action( 'equip/engine/elements/after', $layout, $slug );

		return true;
	}

	/**
	 * Start parent element wrapper
	 *
	 * @param string               $slug   Element unique name
	 * @param \Equip\Layout\Layout $layout Current layout scope
	 *
	 * @return void
	 */
	abstract public function before_elements( $slug, $layout );

	/**
	 * Start nested element wrapper
	 *
	 * @param string               $slug     Element unique name
	 * @param array                $settings Element settings
	 * @param \Equip\Layout\Layout $layout   Current layout scope
	 *
	 * @return
	 */
	abstract public function before_element( $slug, $settings, $layout );

	/**
	 * Do the element
	 *
	 * @param string               $slug     Element unique name
	 * @param array                $settings Element settings
	 * @param mixed                $values   Raw element value
	 * @param \Equip\Layout\Layout $layout   Current layout scope
	 *
	 * @return
	 */
	abstract public function do_element( $slug, $settings, $values, $layout );

	/**
	 * End nested element wrapper
	 *
	 * @param string               $slug     Element unique name
	 * @param array                $settings Element settings
	 * @param \Equip\Layout\Layout $layout   Current layout scope
	 *
	 * @return
	 */
	abstract public function after_element( $slug, $settings, $layout );

	/**
	 * End parent element wrapper
	 *
	 * @param string               $slug   Element unique name
	 * @param \Equip\Layout\Layout $layout Current layout scope
	 *
	 * @return void
	 */
	abstract public function after_elements( $slug, $layout );
}