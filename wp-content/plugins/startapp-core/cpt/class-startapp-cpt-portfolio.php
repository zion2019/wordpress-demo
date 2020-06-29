<?php

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/**
 * CPT "Portfolio"
 * 
 * @author 8guild
 */
class Startapp_CPT_Portfolio extends Startapp_CPT {
	/**
	 * Custom Post Type
	 *
	 * @var string
	 */
	protected $post_type = 'startapp_portfolio';

	/**
	 * Custom taxonomy
	 *
	 * @var string
	 */
	private $taxonomy = 'startapp_portfolio_category';

	/**
	 * Meta Box: Short Description
	 *
	 * @var string
	 */
	private $box_description = '_startapp_portfolio_description';

	/**#@+
	 * Cache variables
	 *
	 * @see flush_cats_cache
	 * @see flush_posts_cache
	 */
	private $cache_key_for_posts = 'startapp_portfolio_posts';
	private $cache_key_for_cats = 'startapp_portfolio_cats';
	private $cache_group = 'startapp_autocomplete';
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

		// add "Portfolio" to "default_editor_post_types" for VC
		add_filter( 'startapp_vc_default_editor_post_types', array( $this, 'enable_vc_editor' ) );

		// AJAX Load More
		if ( is_admin() ) {
			add_action( 'wp_ajax_startapp_portfolio_load_posts', array( $this, 'load_posts' ) );
			add_action( 'wp_ajax_nopriv_startapp_portfolio_load_posts', array( $this, 'load_posts' ) );
		}

		// Display Featured Image in entries list
		add_filter( "manage_{$this->post_type}_posts_columns", array( $this, 'additional_posts_screen_columns' ) );
		add_action( "manage_{$this->post_type}_posts_custom_column", array( $this, 'additional_posts_screen_content' ), 10, 2 );

		// Clear cache on adding or deleting portfolio items
		add_action( "save_post_{$this->post_type}", array( $this, 'flush_posts_cache' ) );
		add_action( 'deleted_post', array( $this, 'flush_posts_cache' ) );

		// and categories
		add_action( "create_{$this->taxonomy}", array( $this, 'flush_cats_cache' ) );
		add_action( "delete_{$this->taxonomy}", array( $this, 'flush_cats_cache' ) );
		// fires for both situations when term is edited and term post count changes
		// @see taxonomy.php :: 3440 wp_update_term()
		// @see taxonomy.php :: 4152 _update_post_term_count
		add_action( 'edit_term_taxonomy', array( $this, 'flush_cats_cache' ), 10, 2 );

		// autocomplete, @see shortcodes/templates/startapp_portfolio.php
		add_filter( 'startapp_portfolio_posts_autocomplete', array( $this, 'get_autocomplete_posts' ), 1 );
		add_filter( 'startapp_portfolio_categories_autocomplete', array( $this, 'get_autocomplete_categories' ), 1 );
	}

	public function register() {
		$this->register_post_type();
		$this->register_taxonomy();
	}

	private function register_post_type() {
		$labels = array(
			'name'               => _x( 'Portfolio', 'post type general name', 'startapp' ),
			'singular_name'      => _x( 'Portfolio', 'post type singular name', 'startapp' ),
			'menu_name'          => __( 'Portfolio', 'startapp' ),
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
			'slug'       => 'portfolio-item',
			'with_front' => false,
			'pages'      => true,
			'feeds'      => true,
		);

		$args = array(
			'label'               => __( 'Portfolio', 'startapp' ),
			'labels'              => $labels,
			'description'         => __( 'A fancy portfolio with filters.', 'startapp' ),
			'public'              => true,
			'exclude_from_search' => false,
			'publicly_queryable'  => true,
			'show_ui'             => true,
			'show_in_menu'        => true,
			'show_in_nav_menus'   => true,
			'show_in_admin_bar'   => true,
			'menu_position'       => '48.1',
			'menu_icon'           => 'dashicons-portfolio',
			'capability_type'     => 'page',
			'hierarchical'        => false,
			'supports'            => array( 'title', 'thumbnail', 'editor' ),
			'taxonomies'          => array( $this->taxonomy ),
			'has_archive'         => false,
			'rewrite'             => $rewrite,
			'query_var'           => true,
			'can_export'          => true,
		);

		register_post_type( $this->post_type, $args );
	}

	private function register_taxonomy() {
		$labels = array(
			'name'                       => _x( 'Categories', 'taxonomy general name', 'startapp' ),
			'singular_name'              => _x( 'Category', 'taxonomy singular name', 'startapp' ),
			'menu_name'                  => __( 'Categories', 'startapp' ),
			'all_items'                  => __( 'All Items', 'startapp' ),
			'parent_item'                => __( 'Parent Item', 'startapp' ),
			'parent_item_colon'          => __( 'Parent Item:', 'startapp' ),
			'new_item_name'              => __( 'New Item Name', 'startapp' ),
			'add_new_item'               => __( 'Add New', 'startapp' ),
			'edit_item'                  => __( 'Edit', 'startapp' ),
			'update_item'                => __( 'Update', 'startapp' ),
			'separate_items_with_commas' => __( 'Separate with commas', 'startapp' ),
			'search_items'               => __( 'Search', 'startapp' ),
			'add_or_remove_items'        => __( 'Add or remove items', 'startapp' ),
			'choose_from_most_used'      => __( 'Choose from the most used items', 'startapp' ),
			'not_found'                  => __( 'Not Found', 'startapp' )
		);

		$args = array(
			'labels'             => $labels,
			'description'        => __( 'For filtration and building queries', 'startapp' ),
			'public'             => false,
			'show_ui'            => true,
			'show_in_nav_menus'  => false,
			'show_tagcloud'      => false,
			'show_in_quick_edit' => true,
			'show_admin_column'  => true,
			'hierarchical'       => true,
			'query_var'          => false,
			'rewrite'            => false,
		);

		register_taxonomy( $this->taxonomy, array( $this->post_type ), $args );
	}

	/**
	 * Add meta boxes through Equip
	 */
	public function add_meta_boxes() {
		try {
			$layout = equip_create_meta_box_layout();
			$layout->add_field( 'text', 'textarea', array(
				'escape'      => 'esc_textarea',
				'sanitize'    => 'sanitize_text_field',
				'description' => __( 'This description is used inside Portfolio tile.', 'startapp' ),
			) );

			equip_add_meta_box( $this->box_description, $layout, array(
				'id'       => 'startapp-portfolio-description',
				'title'    => __( 'Short Description', 'startapp' ),
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
	 * AJAX handler for portfolio "Load More" button
	 *
	 * Outputs HTML
	 */
	public function load_posts() {
		// Check nonce.
		if ( empty( $_POST['nonce'] ) || ! wp_verify_nonce( $_POST['nonce'], 'startapp-core-ajax' ) ) {
			wp_send_json_error( _x( 'Wrong nonce', 'ajax request', 'startapp' ) );
		}

		if ( empty( $_POST['query'] ) ) {
			wp_send_json_error( _x( 'Param "query" required to load posts', 'ajax request', 'startapp' ) );
		}

		$query_args = startapp_query_decode( $_POST['query'] );
		if ( null === $query_args ) {
			wp_send_json_error( _x( 'Invalid "query" param', 'ajax request', 'startapp' ) );
		}

		$query_args['paged'] = (int) $_POST['page'];

		$query = new WP_Query( $query_args );
		if ( ! $query->have_posts() ) {
			wp_send_json_error( _x( 'Posts not found', 'ajax request', 'startapp' ) );
		}

		$layout = esc_attr( $_POST['type'] );
		$args   = wp_parse_args( $_POST['template_args'], array(
			'tile_class' => '',
			'color'      => '#000000',
			'opacity'    => '50',
			'skin'       => 'light',
		) );

		$posts = array();
		while ( $query->have_posts() ) {
			$query->the_post();

			// service + terms classes for isotope filtration
			$classes = array_merge( array( 'grid-item' ), startapp_get_post_terms( get_the_ID(), $this->taxonomy ) );
			$classes = startapp_get_classes( $classes );

			ob_start();

			echo '<div class="', esc_attr( $classes ), '">';
			startapp_shortcode_template( "portfolio-{$layout}.php", $args );
			echo '</div>';

			$posts[] = startapp_content_encode( ob_get_clean() );
		}
		wp_reset_postdata();

		if ( count( $posts ) > 0 ) {
			wp_send_json_success( $posts );
		} else {
			wp_send_json_error( 'Posts not found' );
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
	 * Fires when portfolio posts creating, updating or deleting.
	 *
	 * @see inc/vc-map.php
	 * @see startapp_core_portfolio_posts
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
	 * Flush object cache for categories
	 *
	 * Fires when created, edited, deleted or updated a category
	 *
	 * @see inc/vc-map.php
	 * @see startapp_core_portfolio_categories
	 *
	 * @param int    $term_id  Term ID or Term Taxonomy ID
	 * @param string $taxonomy Taxonomy name, exists only for "edit_term_taxonomy"
	 */
	public function flush_cats_cache( $term_id, $taxonomy = null ) {
		if ( null === $taxonomy || $this->taxonomy === $taxonomy ) {
			wp_cache_delete( $this->cache_key_for_cats, $this->cache_group );
		}
	}

	/**
	 * Fetch all portfolio posts for autocomplete field.
	 *
	 * It is safe to use IDs for import, because
	 * WP Importer does not change IDs for posts.
	 *
	 * @see shortcodes/mapping/startapp_portfolio.php
	 *
	 * @return array
	 */
	public function get_autocomplete_posts() {
		$posts = wp_cache_get( $this->cache_key_for_posts, $this->cache_group );
		if ( false === $posts ) {
			$posts = array();
			$data  = get_posts( array(
				'post_type'      => $this->post_type,
				'post_status'    => 'publish',
				'posts_per_page' => - 1,
				'no_found_rows'  => true,
				'nopaging'       => true,
			) );

			if ( ! empty( $data ) && ! is_wp_error( $data ) ) {
				foreach ( $data as $item ) {
					// sanitize the title for json
					if ( ! empty( $item->post_title ) ) {
						$title = strip_tags( $item->post_title );
						$title = preg_replace( "!([\b\t\n\r\f\"\\'])!", "\\\\\\1", $title ); // escape json special characters
					} else {
						$title = esc_html__( '(no-title)', 'startapp' );
					}

					$posts[] = array(
						'value' => (int) $item->ID,
						'label' => $title,
					);
					unset( $title );
				}

				// cache for 1 day
				wp_cache_set( $this->cache_key_for_posts, $posts, $this->cache_group, 86400 );
			}
		}

		return $posts;
	}

	/**
	 * Fetch the portfolio categories for autocomplete field
	 *
	 * The taxonomy slug used as autocomplete value because
	 * of export/import issues. WP Importer creates new
	 * categories, tags, taxonomies based on import information
	 * with NEW IDs!
	 *
	 * @see shortcodes/mapping/startapp_portfolio.php
	 *
	 * @return array
	 */
	function get_autocomplete_categories() {
		$data = wp_cache_get( $this->cache_key_for_cats, $this->cache_group );
		if ( false === $data ) {
			$categories = get_terms( array(
				'taxonomy'     => $this->taxonomy,
				'hierarchical' => false,
			) );

			if ( is_wp_error( $categories ) || empty( $categories ) ) {
				return array();
			}

			$data = array();
			foreach ( $categories as $category ) {
				$data[] = array(
					'value' => $category->slug,
					'label' => $category->name,
				);
			}

			// cache for 1 day
			wp_cache_set( $this->cache_key_for_cats, $data, $this->cache_group, 86400 );
		}

		return $data;
	}
}