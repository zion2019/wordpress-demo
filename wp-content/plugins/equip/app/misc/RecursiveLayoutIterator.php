<?php

namespace Equip\Misc;

use Equip\Layout\Layout;

/**
 * Iterate through the Layout tree
 *
 * @package Equip\Misc
 */
class RecursiveLayoutIterator extends \ArrayIterator implements \RecursiveIterator {

	public function hasChildren() {
		$current = $this->current();
		if ( 'parent' === $this->key() ) {
			return false;
		}

		return ( is_array( $current ) || $current instanceof Layout );
	}

	public function getChildren() {
		$current = $this->current();

		return new RecursiveLayoutIterator( $current );
	}

}