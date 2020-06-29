<?php

namespace Equip\Misc;

/**
 * Single storable element
 *
 * @package Equip
 */
class StorageElement {

	private $slug;
	private $layout;
	private $args;
	private $module;
	private $pattern;

	public function __construct( array $data ) {
		foreach ( $data as $k => $v ) {
			if ( property_exists( $this, $k ) ) {
				$this->$k = $v;
			}
		}
	}

	public function getSlug() {
		return $this->slug;
	}

	/**
	 * @return \Equip\Layout\Layout
	 */
	public function getLayout() {
		return $this->layout;
	}

	public function getArgs() {
		return empty( $this->args ) ? [] : $this->args;
	}

	public function getModule() {
		return $this->module;
	}

	public function getPattern() {
		return $this->pattern;
	}
}