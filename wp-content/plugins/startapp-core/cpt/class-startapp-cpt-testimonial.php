<?php

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/**
 * CPT "Testimonials"
 *
 * @author 8guild
 */
class Startapp_CPT_Testimonial extends Startapp_CPT {
	/**
	 * Custom Post Type
	 *
	 * @var string
	 */
	protected $post_type = 'startapp_testimonial';

	/**
	 * Meta Box: Settings
	 *
	 * @var string
	 */
	protected $box_settings = '_startapp_testimonial_settings';

	/**
	 * Meta Box: Button
	 *
	 * @var string
	 */
	protected $box_button = '_startapp_testimonial_button';

	/**#@+
	 * Cache variables
	 *
	 * @see flush_posts_cache
	 */
	private $cache_key_for_posts = 'startapp_testimonials_posts';
	private $cache_group = 'startapp_autocomplete';
	/**#@-*/


	/**
	 * Constructor
	 */
	public function __construct() {}

	public function init() {
		add_action( 'init', array( $this, 'register' ), 0 );

		// taxonomy meta box
		add_action( 'equip/register', array( $this, 'add_meta_boxes' ) );

		// clear cache on adding or deleting portfolio items
		add_action( "save_post_{$this->post_type}", array( $this, 'flush_posts_cache' ) );
		add_action( 'deleted_post', array( $this, 'flush_posts_cache' ) );

		// add custom columns to entries list
		add_filter( "manage_{$this->post_type}_posts_columns", array( $this, 'additional_posts_screen_columns' ) );
		add_action( "manage_{$this->post_type}_posts_custom_column", array( $this, 'additional_posts_screen_content' ), 10, 2 );
	}

	public function register() {
		$this->register_post_type();
	}

	private function register_post_type() {
		$labels = array(
			'name'               => _x( 'Testimonials', 'post type general name', 'startapp' ),
			'singular_name'      => _x( 'Testimonial', 'post type singular name', 'startapp' ),
			'menu_name'          => __( 'Testimonials', 'startapp' ),
			'all_items'          => __( 'All Items', 'startapp' ),
			'view_item'          => __( 'View', 'startapp' ),
			'add_new_item'       => __( 'Add New Item', 'startapp' ),
			'add_new'            => __( 'Add New', 'startapp' ),
			'edit_item'          => __( 'Edit', 'startapp' ),
			'update_item'        => __( 'Update', 'startapp' ),
			'search_items'       => __( 'Search', 'startapp' ),
			'not_found'          => __( 'Not found', 'startapp' ),
			'not_found_in_trash' => __( 'Not found in Trash', 'startapp' ),
		);

		$args = array(
			'label'               => __( 'Testimonials', 'startapp' ),
			'labels'              => $labels,
			'description'         => __( 'A custom post type for Testimonials Slideshow', 'startapp' ),
			'public'              => false,
			'exclude_from_search' => true,
			'publicly_queryable'  => false,
			'show_ui'             => true,
			'show_in_menu'        => true,
			'show_in_nav_menus'   => false,
			'show_in_admin_bar'   => true,
			'menu_position'       => '48.1',
			'menu_icon'           => 'dashicons-testimonial',
			'capability_type'     => 'post',
			'hierarchical'        => false,
			'supports'            => array( 'title' ),
			'has_archive'         => false,
			'rewrite'             => false,
			'query_var'           => false,
			'can_export'          => true,
		);

		register_post_type( $this->post_type, $args );
	}

	/**
	 * Flush object cache for posts
	 *
	 * Fires when post creating, updating or deleting.
	 *
	 * @see shortcodes/mapping/startapp_testimonials_slider.php
	 * @see startapp_core_testimonials_posts()
	 *
	 * @param int $post_id Post ID
	 */
	public function flush_posts_cache( $post_id ) {
		$type = get_post_type( $post_id );
		if ( $this->post_type !== $type ) {
			return;
		}

		wp_cache_delete( $this->cache_key_for_posts, $this->cache_group );
	}

	/**
	 * Add meta boxes through Equip
	 */
	public function add_meta_boxes() {

		// Settings
		try {
			$layout = equip_create_meta_box_layout();
			$layout
				->add_field( 'quotation', 'textarea', array(
					'label' => __( 'Quotation', 'startapp' ),
					'rows'  => 10,
					'attr'  => array( 'tabindex' => 1 ),
				) )
				->add_row()
				->add_column( 4 )
				->add_field( 'bg', 'media', array(
					'label'  => __( 'Background', 'startapp' ),
					'helper' => __( 'This image will be used as a Testimonial background image', 'startapp' ),
					'media'  => array( 'title' => __( 'Select Testimonial Background', 'startapp' ) ),
				) )
				->add_column( 4 )
				->add_field( 'author', 'text', array(
					'label' => __( 'Author', 'startapp' ),
					'attr'  => array( 'tabindex' => 2 ),
				) )
				->add_field( 'company', 'text', array(
					'label' => __( 'Company', 'startapp' ),
					'attr'  => array( 'tabindex' => 4 ),
				) )
				->add_column( 4 )
				->add_field( 'position', 'text', array(
					'label' => __( 'Position', 'startapp' ),
					'attr'  => array( 'tabindex' => 3 ),
				) )
				->add_field( 'company_link', 'text', array(
					'label'       => __( 'Company Website', 'startapp' ),
					'description' => __( 'Add company\'s website URL', 'startapp' ),
					'escape'      => 'esc_url',
					'sanitize'    => 'esc_url_raw',
					'attr'  => array( 'tabindex' => 5 ),
				) )
				->add_row()
				->add_column( 4 )
				->add_field( 'logo', 'media', array(
					'label'  => __( 'Company Logo', 'startapp' ),
					'helper' => __( 'Upload company logo image', 'startapp' ),
					'media'  => array( 'title' => __( 'Select Company Logo', 'startapp' ) ),
				) )
				->add_column( 4 )
				->add_field( 'is_logo_linked', 'switch', array(
					'label'       => __( 'Link Logo to Company Website.', 'startapp' ),
					'description' => __( 'This will make logo clickable', 'startapp' ),
					'required'    => array( 'logo', 'not_empty' ),
					'label_on'    => __( 'Yes', 'startapp' ),
					'label_off'   => __( 'No', 'startapp' ),
				) );

			equip_add_meta_box( $this->box_settings, $layout, array(
				'id'       => 'startapp-testimonial-settings',
				'title'    => __( 'Settings', 'nucleus' ),
				'screen'   => $this->post_type,
				'context'  => 'normal',
				'priority' => 'high',
			) );
			unset( $layout );
		} catch ( Exception $e ) {
			trigger_error( $e->getMessage() );
		}

		// Button
		try {
			$layout = equip_create_meta_box_layout();
			$layout
				->add_row()
				->add_column( 6 )
				->add_field( 'url', 'text', array(
					'label'       => __( 'Link', 'startapp' ),
					'description' => __( 'Leave this field empty if you do not want to add a button', 'startapp' ),
					'sanitize'    => 'esc_url_raw',
					'escape'      => 'esc_url',
				) )
				->add_column( 6 )
				->add_field( 'text', 'text', array(
					'label'       => __( 'Text', 'startapp' ),
					'description' => __( 'This text will be displayed on the button', 'startapp' ),
				) )
				->add_row()
				->add_column( 4 )
				->add_field( 'type', 'select', array(
					'label'   => __( 'Type', 'startapp' ),
					'default' => 'solid',
					'options' => array(
						'solid'       => __( 'Solid', 'startapp' ),
						'ghost'       => __( 'Ghost', 'startapp' ),
						'3d'          => __( '3D', 'startapp' ),
						'transparent' => __( 'Transparent', 'startapp' ),
						'link'        => __( 'Link', 'startapp' ),
					),
				) )
				->add_field( 'size', 'select', array(
					'label'   => __( 'Size', 'startapp' ),
					'default' => 'default',
					'options' => array(
						'lg'      => __( 'Large', 'startapp' ),
						'default' => __( 'Normal', 'startapp' ),
						'sm'      => __( 'Small', 'startapp' ),
						'xs'      => __( 'Extra Small', 'startapp' ),
					),
				) )
				->add_field( 'is_waves', 'select', array(
					'label'   => __( 'Waves Effect', 'startapp' ),
					'default' => 'disable',
					'options' => array(
						'enable'  => __( 'Enable', 'startapp' ),
						'disable' => __( 'Disable', 'startapp' ),
					),
				) )
				->add_column( 4 )
				->add_field( 'shape', 'select', array(
					'label'   => __( 'Shape', 'startapp' ),
					'default' => 'rounded',
					'options' => array(
						'rounded' => __( 'Rounded', 'startapp' ),
						'square'  => __( 'Square', 'startapp' ),
						'pill'    => __( 'Pill', 'startapp' ),
					),
				) )
				->add_field( 'is_full', 'select', array(
					'label'   => __( 'Make button full-width?', 'startapp' ),
					'default' => 'no',
					'options' => array(
						'yes' => __( 'Yes', 'startapp' ),
						'no'  => __( 'No', 'startapp' ),
					),
				) )
				->add_field( 'waves_skin', 'select', array(
					'label'    => __( 'Waves Color', 'startapp' ),
					'default'  => 'light',
					'required' => array( 'is_waves', '=', 'enable' ),
					'options'  => array(
						'dark'  => __( 'Dark', 'startapp' ),
						'light' => __( 'Light', 'startapp' ),
					),
				) )
				->add_column( 4 )
				->add_field( 'color', 'select', array(
					'label'   => __( 'Color', 'startapp' ),
					'options' => array(
						'default' => __( 'Default', 'startapp' ),
						'primary' => __( 'Primary', 'startapp' ),
						'success' => __( 'Success', 'startapp' ),
						'info'    => __( 'Info', 'startapp' ),
						'warning' => __( 'Warning', 'startapp' ),
						'danger'  => __( 'Danger', 'startapp' ),
						'light'   => __( 'Light', 'startapp' ),
					),
				) )
				->add_field( 'alignment', 'select', array(
					'label'   => __( 'Alignment', 'startapp' ),
					'default' => 'inline',
					'options' => array(
						'inline' => __( 'Inline', 'startapp' ),
						'left'   => __( 'Left', 'startapp' ),
						'center' => __( 'Center', 'startapp' ),
						'right'  => __( 'Right', 'startapp' ),
					),
				) );

			equip_add_meta_box( $this->box_button, $layout, array(
				'id'       => 'startapp-testimonial-button',
				'title'    => __( 'Button', 'startapp' ),
				'screen'   => $this->post_type,
				'context'  => 'normal',
				'priority' => 'low',
			) );

		} catch ( Exception $e ) {
			trigger_error( $e->getMessage() );
		}
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
			'cb'              => '<input type="checkbox" />',
			'title'           => __( 'Title', 'startapp' ),
			'quote'           => __( 'Quotation', 'startapp' ),
			'startapp-author' => __( 'Author', 'startapp' ),
			'position'        => __( 'Position', 'startapp' ),
			'company'         => __( 'Company', 'startapp' ),
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
			case 'quote':
				$quote = startapp_get_meta( $post_id, $this->box_settings, 'quotation' );
				echo empty( $quote ) ? '&mdash;' : esc_html( $quote );
				break;

			case 'startapp-author':
				$author = startapp_get_meta( $post_id, $this->box_settings, 'author' );
				echo empty( $author ) ? '&mdash;' : esc_html( $author );
				break;

			case 'position':
				$position = startapp_get_meta( $post_id, $this->box_settings, 'position' );
				echo empty( $position ) ? '&mdash;' : esc_html( $position );
				break;

			case 'company':
				$s = wp_parse_args( startapp_get_meta( $post_id, $this->box_settings ), array(
					'logo'         => 0,
					'company'      => '',
					'company_link' => '',
				) );

				if ( empty( $s['company'] ) && empty( $s['logo'] ) ) {
					echo '&mdash;';
				} else {
					echo '<div class="startapp-testimonial-company">';
					echo startapp_get_tag( empty( $s['company_link'] ) ? 'span' : 'a', array(
						'href' => esc_url( $s['company_link'] ),
					), esc_html( $s['company'] ) );
					echo '</div>';
					echo '<div class="startapp-testimonial-logo">';
					echo wp_get_attachment_image( (int) $s['logo'], 'full' );
					echo '</div>';
				}

				break;
		}
	}
}