<?php

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/**
 * CPT "Classes"
 *
 * @author 8guild
 */
class Startapp_CPT_Classes extends Startapp_CPT {
	/**
	 * Custom Post Type
	 *
	 * @var string
	 */
	protected $post_type = 'startapp_classes';

	/**
	 * Meta Box: Classes Attributes
	 *
	 * @var string
	 */
	private $box_atts = '_startapp_classes_atts';

	/**#@+
	 * Cache variables
	 *
	 * @see flush_posts_cache()
	 */
	private $cache_key = 'startapp_classes_posts';
	private $cache_group = 'startapp';
	/**#@-*/

	/**
	 * Constructor
	 */
	public function __construct() {}

	public function init() {
		add_action( 'init', array( $this, 'register' ), 0 );

		// meta boxes
		add_action( 'equip/register', array( $this, 'add_meta_boxes' ) );

		// add Page Settings meta box to current post type
		add_filter( 'startapp_page_settings_screen', array( $this, 'enable_page_settings' ) );

		// add current post type to "default_editor_post_types" for VC
		add_filter( 'startapp_vc_default_editor_post_types', array( $this, 'enable_vc_editor' ) );

		// display additional info in posts table
		add_filter( "manage_{$this->post_type}_posts_columns", array( $this, 'additional_posts_screen_columns' ) );
		add_action( "manage_{$this->post_type}_posts_custom_column", array( $this, 'additional_posts_screen_content' ), 10, 2 );

		// clear cache on adding or deleting items
		add_action( "save_post_{$this->post_type}", array( $this, 'flush_posts_cache' ) );
		add_action( 'deleted_post', array( $this, 'flush_posts_cache' ) );

		// returns classes for startapp_classes shortcode
		add_filter( 'startapp_classes_posts', array( $this, 'get_classes' ) );
	}

	public function register() {
		$labels = array(
			'name'               => _x( 'Classes', 'post type general name', 'startapp' ),
			'singular_name'      => _x( 'Class', 'post type singular name', 'startapp' ),
			'menu_name'          => __( 'Classes', 'startapp' ),
			'all_items'          => __( 'All Items', 'startapp' ),
			'view_item'          => __( 'View', 'startapp' ),
			'add_new_item'       => __( 'Add New Item', 'startapp' ),
			'add_new'            => __( 'Add New', 'startapp' ),
			'edit_item'          => __( 'Edit', 'startapp' ),
			'update_item'        => __( 'Update', 'startapp' ),
			'search_items'       => __( 'Search', 'startapp' ),
			'not_found'          => __( 'Not found', 'startapp' ),
			'not_found_in_trash' => __( 'Not found in Trash', 'startapp' )
		);

		$rewrite = array(
			'slug'       => 'class-item',
			'with_front' => false,
			'pages'      => true,
			'feeds'      => true,
		);

		$args = array(
			'label'               => __( 'Classes', 'startapp' ),
			'labels'              => $labels,
			'description'         => __( 'Functionality to advertise online classes.', 'startapp' ),
			'public'              => true,
			'exclude_from_search' => false,
			'publicly_queryable'  => true,
			'show_ui'             => true,
			'show_in_menu'        => true,
			'show_in_nav_menus'   => true,
			'show_in_admin_bar'   => true,
			'menu_position'       => '48.1',
			'menu_icon'           => 'dashicons-universal-access',
			'capability_type'     => 'page',
			'hierarchical'        => false,
			'supports'            => array( 'title', 'thumbnail', 'editor' ),
			'has_archive'         => false,
			'rewrite'             => $rewrite,
			'query_var'           => true,
			'can_export'          => true,
		);

		register_post_type( $this->post_type, $args );
	}

	/**
	 * Add meta boxes through Equip
	 */
	public function add_meta_boxes() {
		try {
			$layout = equip_create_meta_box_layout();
			$layout
				->add_row()
				->add_column( 9 )
				->add_field( 'subtitle', 'text', array(
					'label'  => __( 'Subtitle', 'startapp' ),
					'escape' => 'esc_html',
				) )
				->add_field( 'description', 'textarea', array(
					'label'       => __( 'Short Description', 'startapp' ),
					'description' => __( 'This will be visible inside Classes Tile shortcode.', 'startapp' ),
					'escape'      => 'esc_textarea',
					'sanitize'    => 'sanitize_text_field',
				) )
				->add_row()
				->add_column( 3 )
				->add_field( 'date', 'text', array(
					'label'  => __( 'Date', 'startapp' ),
					'escape' => 'esc_html',
					'icon'   => 'material-icons date_range',
				) )
				->add_column( 3 )
				->add_field( 'time', 'text', array(
					'label'  => __( 'Time', 'startapp' ),
					'escape' => 'esc_html',
					'icon'   => 'material-icons access_time',
				) )
				->add_column( 3 )
				->add_field( 'seats', 'text', array(
					'label'  => __( 'Number of Seats', 'startapp' ),
					'escape' => 'esc_html',
					'icon'   => 'material-icons person',
				) )
				->add_row()
				->add_column( 9 )
				->add_field( 'label_sep', 'raw_text', array(
					'default' => '<h4><strong>' . __( 'Label', 'startapp' ) . '</strong></h4><hr>',
				) )
				->add_field( 'is_label', 'switch', array(
					'label'     => __( 'Label', 'startapp' ),
					'helper'    => __( 'Enable / Disable Label', 'startapp' ),
					'default'   => false,
					'label_on'  => __( 'On', 'startapp' ),
					'label_off' => __( 'Off', 'startapp' ),
				) )
				->add_row()
				->add_column( 3 )
				->add_field( 'label_text', 'text', array(
					'label'    => __( 'Text', 'startapp' ),
					'escape'   => 'esc_html',
					'required' => array( 'is_label', '=', 1 ),
				) )
				->add_column( 3 )
				->add_field( 'label_text_color', 'select', array(
					'label'    => __( 'Text Color', 'startapp' ),
					'required' => array( 'is_label', '=', 1 ),
					'default'  => 'dark',
					'options'  => array(
						'dark'  => __( 'Dark', 'startapp' ),
						'light' => __( 'Light', 'startapp' ),
					),
				) )
				->add_column( 3 )
				->add_field( 'label_bg_color', 'select', array(
					'label'    => __( 'Background Color', 'startapp' ),
					'required' => array( 'is_label', '=', 1 ),
					'default'  => 'default',
					'options'  => array(
						'default' => __( 'Default', 'startapp' ),
						'primary' => __( 'Primary', 'startapp' ),
						'info'    => __( 'Info', 'startapp' ),
						'success' => __( 'Success', 'startapp' ),
						'warning' => __( 'Warning', 'startapp' ),
						'danger'  => __( 'Danger', 'startapp' ),
						'gray'    => __( 'Gray', 'startapp' ),
						'custom'  => __( 'Custom', 'startapp' ),
					),
				) )
				->add_column( 3 )
				->add_field( 'label_bg_color_custom', 'color', array(
					'label'    => __( 'Custom Color', 'startapp' ),
					'required' => array(
						array( 'is_label', '=', 1 ),
						array( 'label_bg_color', '=', 'custom' ),
					),
				) )
				->add_row()
				->add_column( 9 )
				->add_field( 'author_sep', 'raw_text', array(
					'default' => '<h4><strong>' . __( 'Author', 'startapp' ) . '</strong></h4><hr>',
				) )
				->add_field( 'author_avatar', 'media', array(
					'label' => __( 'Avatar', 'startapp' ),
					'media' => array( 'title' => __( 'Choose Author Avatar', 'startapp' ) ),
				) )
				->add_row()
				->add_column( 3 )
				->add_field( 'author_name', 'text', array(
					'label'  => __( 'First Name', 'startapp' ),
					'escape' => 'esc_html',
				) )
				->add_column( 3 )
				->add_field( 'author_surname', 'text', array(
					'label'  => __( 'Last Name', 'startapp' ),
					'escape' => 'esc_html',
				) )
				->add_column( 3 )
				->add_field( 'author_link', 'text', array(
					'label'    => __( 'Link to Author', 'startapp' ),
					'sanitize' => 'esc_url_raw',
					'escape'   => 'startapp_esc_url',
				) );

			equip_add_meta_box( $this->box_atts, $layout, array(
				'id'       => 'startapp-classes-atts',
				'title'    => __( 'Classes Attributes', 'startapp' ),
				'screen'   => $this->post_type,
				'context'  => 'normal',
				'priority' => 'high',
			) );

		} catch ( Exception $e ) {
			trigger_error( $e->getMessage() );
		}
	}

	/**
	 * Add "Page Settings" meta box for current post type
	 *
	 * @see startapp_add_page_settings_meta_box()
	 *
	 * @param array $screens Screens where Page Settings meta box should apply
	 *
	 * @return array
	 */
	public function enable_page_settings( $screens ) {
		$screens[] = $this->post_type;

		return $screens;
	}

	/**
	 * Enable the Visual Composer editor for current post type
	 *
	 * @see startapp_vc_before_init()
	 * @see vc_set_default_editor_post_types()
	 *
	 * @param array $post_types Post types
	 *
	 * @return array
	 */
	public function enable_vc_editor( $post_types ) {
		$post_types[] = $this->post_type;

		return $post_types;
	}

	/**
	 * Add extra columns to a post type screen
	 *
	 * @param array $columns Current Posts Screen columns
	 *
	 * @return array New Posts Screen columns.
	 */
	public function additional_posts_screen_columns( $columns ) {
		return array_merge( array(
			'cb'     => '<input type="checkbox" />',
			'image'  => __( 'Featured Image', 'startapp' ),
			'title'  => __( 'Title', 'startapp' ),
		), $columns );
	}

	/**
	 * Show data in extra columns
	 *
	 * @param string $column  Column slug
	 * @param int    $post_id Post ID
	 */
	public function additional_posts_screen_content( $column, $post_id ) {
		switch ( $column ) {
			case 'image':
				$image_id = get_post_thumbnail_id( $post_id );
				echo wp_get_attachment_image( $image_id, array( 75, 75 ) );
				break;
		}
	}

	/**
	 * Flush object cache for posts
	 *
	 * Fires when the posts creating, updating or deleting.
	 *
	 * @param int $post_id Post ID
	 */
	public function flush_posts_cache( $post_id ) {
		$type = get_post_type( $post_id );
		if ( $this->post_type !== $type ) {
			return;
		}

		wp_cache_delete( $this->cache_key, $this->cache_group );
	}

	/**
	 * Returns the Classes
	 *
	 * Uses in "StartApp Classes" shortcode
	 * @see shortcodes/mapping/startapp_classes.php
	 *
	 * @hooked startapp_classes_posts 10
	 *
	 * @param array $classes A list of classes
	 *
	 * @return array
	 */
	public function get_classes( $classes ) {
		$data = wp_cache_get( $this->cache_key, $this->cache_group );
		if ( false === $data ) {
			$data = array();

			// get posts
			$posts = get_posts( array(
				'post_type'           => $this->post_type,
				'post_status'         => 'publish',
				'posts_per_page'      => - 1,
				'no_found_rows'       => true,
				'nopaging'            => true,
				'ignore_sticky_posts' => true,
				'suppress_filters'    => true,
			) );

			if ( ! empty( $posts ) && ! is_wp_error( $posts ) ) {

				foreach ( $posts as $post ) {
					$title = $post->post_title ? esc_html( $post->post_title ) : __( '(no-title)', 'startapp' );
					$id    = (int) $post->ID;

					$data[ $title ] = $id;
					unset( $title, $id );
				}
				unset( $post );

				// cache for 1 day
				wp_cache_set( $this->cache_key, $data, $this->cache_group, 86400 );
			}
			unset( $posts );
		}

		return array_merge( $classes, $data );
	}
}