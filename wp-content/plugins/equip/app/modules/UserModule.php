<?php

namespace Equip\Module;

use Equip\Equip;
use Equip\Factory;
use Equip\Storage;

/**
 * Working with custom fields in user / profile pages
 *
 * @author  8guild
 * @package Equip\Module
 */
class UserModule {

	/**
	 * Possible values for "context" argument and associated hooks
	 *
	 * @var array
	 */
	private $contexts = [
		'profile_personal_options' => 'personal',
		'edit_user_profile'        => 'user',
		'show_user_profile'        => 'profile',
	];

	/**
	 * Constructor
	 */
	public function __construct() {
		add_action( 'edit_user_profile', [ $this, 'render' ] );
		add_action( 'show_user_profile', [ $this, 'render' ] );
		//add_action( 'personal_options', [ $this, 'render' ] );
		//add_action( 'profile_personal_options', [ $this, 'render' ] );

		add_action( 'personal_options_update', [ $this, 'save' ] );
		add_action( 'edit_user_profile_update', [ $this, 'save' ] );
	}

	/**
	 * Add custom user fields to the storage
	 *
	 * $slug will be used to save and retrieve data from database
	 * as second parameter of {@see get_user_meta()}
	 *
	 * Returns stored element key
	 *
	 * @param string               $slug   Unique name
	 * @param \Equip\Layout\Layout $layout Layout
	 * @param array                $args   Arguments
	 *
	 * @return string
	 */
	public function store( $slug, $layout, $args = [] ) {
		$args = wp_parse_args( $args, $this->get_defaults() );
		$data = [
			'slug'    => $slug,
			'layout'  => $layout,
			'args'    => $args,
			'module'  => 'user',
			'pattern' => 'module.slug',
		];

		return Storage::add( $data );
	}

	/**
	 * Return all registered user fields for current context
	 *
	 * @param array $where Where clause
	 *
	 * @return array
	 */
	public function reveal( $where = [] ) {
		$where    = array_merge( $where, [ 'module' => 'user' ] );
		$elements = Storage::find( 'module.context', $where );
		if ( empty( $elements ) ) {
			return [];
		}

		$data = [];
		/** @var \Equip\Misc\StorageElement $element */
		foreach ( $elements as $element ) {
			$data[ $element->getSlug() ] = $element;
		}

		return $data;
	}

	/**
	 * Engine custom fields in user / profile pages
	 *
	 * @param \WP_User $user User object
	 */
	public function render( $user ) {
		//$options = $this->reveal( [ 'context' => $this->get_context( current_action() ) ] );
		$options = $this->reveal();
		if ( empty( $options ) ) {
			return;
		}

		$roles      = array_values( $user->roles );
		$user_id    = (int) $user->data->ID;
		$user_login = $user->data->user_login;
		$user_role  = reset( $roles );

		/** @var \Equip\Misc\StorageElement $element */
		foreach ( $options as $slug => $element ) {
			$args = $element->getArgs();

			if ( ! empty( $args['login'] ) && $user_login !== $args['login'] ) {
				continue;
			}

			if ( ! empty( $args['id'] ) && $user_id !== (int) $args['id'] ) {
				continue;
			}

			if ( ! empty( $args['role'] ) && $user_role !== $args['role'] ) {
				continue;
			}

			$values = get_user_meta( $user->ID, $slug, true );
			$layout = $element->getLayout();
			$engine = Factory::engine( 'user' );

			$engine->render( $slug, $layout, $values );
		}
	}

	/**
	 * Save custom fields from user / profile pages
	 *
	 * @param int $user_id Current user ID
	 *
	 * @since 1.0.0
	 */
	public function save( $user_id ) {
		$options = $this->reveal();
		$keys    = array_keys( $options );
		$current = array_intersect_key( $_POST, array_flip( $keys ) );
		if ( empty( $current ) ) {
			return;
		}

		if ( ! current_user_can( 'edit_users', $user_id ) ) {
			return;
		}


		$sanitizer = Factory::service( Equip::SANITIZER );

		// TODO: add save actions

		foreach ( $current as $slug => $values ) {
			/** @var \Equip\Misc\StorageElement $element */
			$element = $options[ $slug ];
			$values  = $sanitizer->bulk_sanitize( $values, $element->getLayout(), $slug );

			update_user_meta( $user_id, $slug, $values );
		}
		unset( $slug, $values, $element );
	}

	/**
	 * Return the map with context and associated actions
	 *
	 * @return array
	 */
	public function get_contexts() {
		return $this->contexts;
	}

	/**
	 * Returns the context for current action
	 *
	 * If action not exists, method will return default context "user"
	 *
	 * @param string $action Current action
	 *
	 * @return string
	 */
	public function get_context( $action ) {
		return array_key_exists( $action, $this->contexts ) ? $this->contexts[ $action ] : 'user';
	}

	/**
	 * TODO: add filters
	 *
	 * @return array
	 */
	public function get_defaults() {
		$defaults = array(
			'login'   => '',
			'id'      => '',
			'role'    => '',
			'context' => 'user',
		);

		return $defaults;
	}
}