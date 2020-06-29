<?php

namespace Equip\Service;

use Equip\Equip;
use Equip\Factory;

/**
 * Enqueue scripts and styles
 *
 * @author  8guild
 * @package Equip
 */
class Enqueue {
	/**
	 * @var array Allowed hooks and associated element types
	 */
	private $types = array();

	public function __construct() {
		$this->types = array(
			'post.php'      => Equip::METABOX,
			'post-new.php'  => Equip::METABOX,
			'profile.php'   => Equip::USER,
			'user-edit.php' => Equip::USER,
			'nav-menus.php' => Equip::MENU,
		);
	}

	/**
	 * Enqueue scripts and styles
	 *
	 * TODO: maybe use get_current_screen() too?
	 *
	 * @param string $hook Current screen hook
	 *
	 * @return bool
	 */
	public function enqueue( $hook ) {
		$screen = get_current_screen();
		$type   = $this->detect( $hook );
		if ( null === $type ) {
			return false;
		}

		$module   = Factory::module( $type );
		$elements = $module->reveal();

		if ( ! array_key_exists( $hook, $this->types ) ) {
			return false;
		}

		/*$type = $this->types[ $hook ];
		$storage  = Equip::get( Equip::STORAGE );
		$elements = $storage->get( $type );
		if ( empty( $elements ) ) {
			return false;
		}

		$elements = $this->filter( $type, $elements );
		if ( empty( $elements ) ) {
			return false;
		}

		// $element should contain a list of, e.g. meta boxes, in format
		// [$slug => [$content, $args]]
		foreach( $elements as $slug => $element ) {
			$contents = $element['contents'];

			// do not load any scripts for callbacks
			if ( is_callable( $contents ) ) {
				continue;
			}

			// expand groups and sections and add fields to a flat list
			// does not matter for enqueuing assets
			$contents_copy = $contents;
			foreach( $contents_copy as $settings ) {
				if ( in_array( $settings['field'], array( 'group', 'section' ), true  )
				     && array_key_exists( 'fields', $settings )
				) {
					foreach( $settings['fields'] as $field ) {
						$contents[] = $field;
					}
					unset( $field );
				}

				continue;
			}
			unset( $contents_copy, $settings );

			// no need for duplicating fields
			$unique   = array();
			$contents = array_filter( $contents, function ( $settings ) use ( &$unique ) {
				$field = $settings['field'];

				if ( in_array( $field, array( 'group', 'section' ), true ) ) {
					return false;
				}

				if ( in_array( $field, $unique, true ) ) {
					return false;
				}

				$unique[] = $field;

				return true;
			} );

			foreach ( $contents as $settings ) {
				Equip::field( $settings )->enqueue();
				// TODO: maybe add some actions or filters?
			}
		}
		unset( $slug, $element );*/

		return true;
	}

	/**
	 * Optionally removes wrong elements.
	 *
	 * Enqueue scripts and styles ONLY for current screen, and if this screen
	 * matches the criteria. So, filter elements, based on element type.
	 *
	 * @param string $type     Current element type
	 * @param array  $elements List of fields' settings
	 *
	 * @return array
	 */
	private function filter( $type, $elements ) {
		switch ( $type ) {
			case Equip::METABOX:
				$elements = $this->filter_meta_boxes( $elements );
				break;

			case Equip::USER:
				$elements = $this->filter_users( $elements );
				break;

			case 'page':
			case 'settings':
			default:
				break;
		}

		return $elements;
	}

	/**
	 * Return meta boxes, that matches current post type
	 *
	 * @param array $meta_boxes List of all meta boxes from storage
	 *
	 * @return array List of meta boxes, registered for current post type
	 */
	private function filter_meta_boxes( $meta_boxes ) {
		$post_type = get_post_type();

		// Filter meta boxes, registered through Equip,
		// for current screen. Default screen is "post".
		$meta_boxes = array_filter( $meta_boxes, function ( $meta_box ) use ( $post_type ) {
			$args   = $meta_box['args'];
			$screen = array_key_exists( 'screen', $args ) ? $args['screen'] : 'post';

			return ( $screen === $post_type );
		} );

		return empty( $meta_boxes ) ? array() : $meta_boxes;
	}

	private function filter_users( $elements ) {
		/*
		 * TODO: некоторая логика, основываясь на $args.
		 * Например, соответствует ли текущий пользователь указанному ID и т.д.
		 */
		return $elements;
	}

	private function filter_pages() {
	}

	private function filter_settings() {
	}

	/**
	 * Returns the custom scripts or styles data
	 *
	 * @param array        $all    All custom scripts or styles [handler => uri]
	 * @param string|array $custom Field custom single URI, or array of URIs
	 * @param string       $suffix Suffix in handler name: css or js
	 *
	 * @return array
	 */
	private function get_custom( $all, $custom, $suffix ) {
		if ( is_array( $custom ) ) {

			foreach ( (array) $custom as $k => $uri ) {
				$handler         = "equip-{$suffix}";
				$all[ $handler ] = $uri;

				unset( $handler );
			}
			unset( $k, $uri );

		} elseif ( is_string( $custom ) ) {
			$handler         = "equip-{$suffix}";
			$all[ $handler ] = $custom;

			unset( $handler );
		}

		return $all;
	}

	private function detect( $hook ) {
		if ( array_key_exists( $hook, $this->types ) ) {
			return $this->types[ $hook ];
		}

		if ( false !== stripos( $hook, '_page_' ) ) {
			return Equip::OPTIONS;
		}

		return null;
	}
}