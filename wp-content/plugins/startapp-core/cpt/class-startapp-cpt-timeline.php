<?php

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/**
 * CPT "Timeline"
 *
 * @author 8guild
 */
class Startapp_CPT_Timeline extends Startapp_CPT {
	/**
	 * Custom Post Type
	 *
	 * @var string
	 */
	protected $post_type = 'startapp_timeline';

	/**
	 * Taxonomy: Milestones
	 *
	 * @var string
	 */
	protected $tax_milestone = 'startapp_timeline_milestone';

	/**
	 * Taxonomy: Category
	 *
	 * @var string
	 */
	protected $tax_category = 'startapp_timeline_category';

	/**#@+
	 * Cache variables
	 *
	 * @see flush_cats_cache
	 */
	private $cache_key_for_cats = 'startapp_timeline_cats';
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

		// enable sorting by Milestones in WP_List_Table
		add_filter( "manage_edit-{$this->post_type}_sortable_columns", array( $this, 'additional_sortable_columns' ) );
		add_filter( 'posts_clauses', array( $this, 'additional_columns_sorting' ), 10, 2 );

		// and categories
		add_action( "create_{$this->tax_category}", array( $this, 'flush_cats_cache' ) );
		add_action( "delete_{$this->tax_category}", array( $this, 'flush_cats_cache' ) );
		// fires for both situations when term is edited and term post count changes
		// @see taxonomy.php :: 3440 wp_update_term()
		// @see taxonomy.php :: 4152 _update_post_term_count
		add_action( 'edit_term_taxonomy', array( $this, 'flush_cats_cache' ), 10, 2 );

		// autocomplete, @see shortcodes/templates/startapp_timeline.php
		add_filter( 'startapp_timeline_categories_autocomplete', array( $this, 'get_autocomplete_categories' ), 5 );
	}

	public function register() {
		$this->register_post_type();
		$this->register_tax_category();
		$this->register_tax_milestone();
	}

	private function register_post_type() {
		$labels = array(
			'name'               => _x( 'Timeline', 'post type general name', 'startapp' ),
			'singular_name'      => _x( 'Timeline', 'post type singular name', 'startapp' ),
			'menu_name'          => __( 'Timeline', 'startapp' ),
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
			'label'               => __( 'Timeline', 'startapp' ),
			'labels'              => $labels,
			'description'         => __( 'A custom post type for building the Timeline', 'startapp' ),
			'public'              => false,
			'exclude_from_search' => true,
			'publicly_queryable'  => false,
			'show_ui'             => true,
			'show_in_menu'        => true,
			'show_in_nav_menus'   => false,
			'show_in_admin_bar'   => true,
			'menu_position'       => '48.1',
			'menu_icon'           => 'dashicons-calendar-alt',
			'capability_type'     => 'post',
			'hierarchical'        => false,
			'supports'            => array( 'title', 'editor' ),
			'taxonomies'          => array( $this->tax_milestone, $this->tax_category ),
			'has_archive'         => false,
			'rewrite'             => false,
			'query_var'           => false,
			'can_export'          => true,
		);

		register_post_type( $this->post_type, $args );
	}

	private function register_tax_category() {
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

		register_taxonomy( $this->tax_category, array( $this->post_type ), $args );
	}

	private function register_tax_milestone() {
		$labels = array(
			'name'                       => _x( 'Milestone', 'taxonomy general name', 'startapp' ),
			'singular_name'              => _x( 'Milestone', 'taxonomy singular name', 'startapp' ),
			'menu_name'                  => __( 'Milestones', 'startapp' ),
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
			'not_found'                  => __( 'Not Found', 'startapp' ),
		);

		$args = array(
			'labels'             => $labels,
			'description'        => __( 'Taxonomy used in Timeline to separate posts', 'startapp' ),
			'public'             => false,
			'show_ui'            => true,
			'show_in_nav_menus'  => false,
			'show_tagcloud'      => false,
			'show_in_quick_edit' => true,
			'meta_box_cb'        => false,
			'show_admin_column'  => true,
			'hierarchical'       => true,
			'query_var'          => false,
			'rewrite'            => false,
		);

		register_taxonomy( $this->tax_milestone, array( $this->post_type ), $args );
	}

	/**
	 * Custom meta box to display the "Milestone"
	 * taxonomy as select, not checkboxes.
	 */
	public function add_meta_boxes() {
		try {
			$layout = equip_create_meta_box_layout();
			$layout->add_field( 'startapp_timeline_milestone', 'select', array(
				'sanitize' => 'absint',
				'escape'   => 'intval',
				'default'  => 0,
				'options'  => call_user_func( function() {
					$terms = get_terms( array(
						'taxonomy'   => 'startapp_timeline_milestone',
						'hide_empty' => false,
						'orderby'    => 'slug',
						'order'      => 'ASC',
					) );

					$milestones    = array();
					$milestones[0] = __( 'None', 'startapp' );
					foreach ( $terms as $term ) {
						$milestones[ $term->term_id ] = $term->name;
					}

					return $milestones;
				} ),
			) );

			equip_add_meta_box( 'tax_input', $layout, array(
				'id'       => 'startapp-timeline-milestone',
				'title'    => __( 'Milestone', 'nucleus' ),
				'screen'   => $this->post_type,
				'context'  => 'side',
				'priority' => 'default',
				'save'     => false,
				'values'   => array( $this, 'custom_meta_box_values' ),
			) );
		} catch ( Exception $e ) {
			trigger_error( $e->getMessage() );
		}
	}

	/**
	 * Get custom values for "Milestone" metabox
	 *
	 * @param array   $values Meta box values
	 * @param WP_Post $post   Post object
	 * @param string  $slug   Meta box name
	 *
	 * @return array
	 */
	public function custom_meta_box_values( $values, $post, $slug ) {
		if ( 'tax_input' !== $slug ) {
			return $values;
		}

		$values = array();
		$terms  = wp_get_object_terms( $post->ID, $this->tax_milestone, array( 'fields' => 'ids' ) );

		$values[ $this->tax_milestone ] = ( empty( $terms ) || is_wp_error( $terms ) ) ? 0 : $terms[0];

		return $values;
	}

	/**
	 * Enable custom columns to become sortable
	 *
	 * @param array $columns Columns
	 *
	 * @return array
	 */
	public function additional_sortable_columns( $columns ) {
		$columns["taxonomy-{$this->tax_milestone}"] = "taxonomy-{$this->tax_milestone}";

		return $columns;
	}

	/**
	 * Allow sorting custom columns by the Taxonomy Term
	 *
	 * @param array    $clauses  Clauses
	 * @param WP_Query $wp_query WordPress Query
	 *
	 * @return array
	 */
	public function additional_columns_sorting( $clauses, $wp_query ) {
		global $wpdb;
		if ( isset( $wp_query->query['orderby'] ) && $wp_query->query['orderby'] == "taxonomy-{$this->tax_milestone}" ) {
			$clauses['join'] .= " LEFT OUTER JOIN {$wpdb->term_relationships} ON {$wpdb->posts}.ID={$wpdb->term_relationships}.object_id";
			$clauses['join'] .= " LEFT OUTER JOIN {$wpdb->term_taxonomy} USING (term_taxonomy_id)";
			$clauses['join'] .= " LEFT OUTER JOIN {$wpdb->terms} USING (term_id)";

			$clauses['where'] .= "AND (taxonomy = '{$this->tax_milestone}' OR taxonomy IS NULL)";
			$clauses['groupby'] = "object_id";
			$clauses['orderby'] = "GROUP_CONCAT({$wpdb->terms}.name ORDER BY name ASC)";
			if ( strtoupper( $wp_query->get( 'order' ) ) == 'ASC' ) {
				$clauses['orderby'] .= 'ASC';
			} else {
				$clauses['orderby'] .= 'DESC';
			}
		}

		return $clauses;
	}

	/**
	 * Flush object cache for categories
	 *
	 * Fires when created, edited, deleted or updated a category
	 *
	 * @param int    $term_id  Term ID or Term Taxonomy ID
	 * @param string $taxonomy Taxonomy name, exists only for "edit_term_taxonomy"
	 */
	public function flush_cats_cache( $term_id, $taxonomy = null ) {
		if ( null === $taxonomy || $this->tax_category === $taxonomy ) {
			wp_cache_delete( $this->cache_key_for_cats, $this->cache_group );
		}
	}

	/**
	 * Fetch categories for autocomplete field
	 *
	 * The taxonomy slug used as autocomplete value because
	 * of export/import issues. WP Importer creates new
	 * categories, tags, taxonomies based on import information
	 * with NEW IDs!
	 *
	 * @see shortcodes/mapping/startapp_timeline.php
	 *
	 * @param array $categories Categories
	 *
	 * @return array
	 */
	public function get_autocomplete_categories( $categories ) {
		$data = wp_cache_get( $this->cache_key_for_cats, $this->cache_group );
		if ( false === $data ) {
			$categories = get_terms( array(
				'taxonomy'     => $this->tax_category,
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

		return array_merge( $categories, $data );
	}
}