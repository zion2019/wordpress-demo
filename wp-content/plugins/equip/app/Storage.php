<?php

namespace Equip;

use DeepCopy\DeepCopy;
use Equip\Misc\StorageElement;

/**
 * Registry for elements
 *
 * @author  8guild
 * @package Equip
 */
class Storage {
	/**
	 * Storage
	 *
	 * @var array
	 */
	private static $storage = [];

	/**
	 * Add data to storage
	 *
	 * TODO: maybe returns $map + hash?
	 * TODO: карту с хэшами удобно использовать для поиска по хэшу
	 * TODO: иначе ключ надо где-то хранить в клиентском коде
	 * TODO: find_by_hash, find_by_key, find
	 *
	 * Returns the generated key, according to the provided pattern
	 *
	 * @param mixed $data Data
	 *
	 * @return string $key
	 */
	public static function add( $data ) {
		$data = wp_parse_args( $data, array(
			'slug'    => '',
			'content' => '',
			'args'    => '',
			'module'  => 'custom',
			'pattern' => 'module.slug',
		) );

		$element = new StorageElement( $data );
		$pattern = $data['pattern'];
		$key     = equip_map_pattern( $pattern, $data );

		// update storage
		self::$storage[ $key ] = $element;

		return $key;
	}

	public function __construct() {
	}

	/**
	 * Find the elements in storage
	 *
	 * @param string $pattern
	 * @param array  $where
	 *
	 * @return array|null
	 */
	public static function find( $pattern, $where ) {
		$key   = equip_map_pattern( $pattern, $where );
		$found = preg_grep( "/$key/", array_keys( self::$storage ) );
		if ( empty( $found ) ) {
			return null;
		}

		$deep   = new DeepCopy();
		$result = [];
		foreach ( (array) $found as $single ) {
			$result[] = $deep->copy( self::$storage[ $single ] );
		}

		return $result;
	}

	/**
	 * Return the element by provided key
	 *
	 * @param string $key Unique key
	 *
	 * @return null|\Equip\Misc\StorageElement NULL if key is missing
	 */
	public static function find_by_key( $key ) {
		if ( ! array_key_exists( $key, self::$storage ) ) {
			return null;
		}

		return self::$storage[ $key ];
	}

	public static function find_by_hash( $hash ) {
		return null;
	}

	/**
	 * Update data in storage
	 *
	 * @param string                     $key     Unique key
	 * @param \Equip\Misc\StorageElement $element Storage element
	 *
	 * @return string $hash
	 */
	public function update( $key, StorageElement $element ) {
		// TODO: check if hash is changed
		// TODO: remove old element
		// TODO: generate new hash
		// TODO: return the new hash
		return ''; // hash
	}

	public static function delete( $key, StorageElement $element ) {
		return true; // TODO: false if not found
	}
}