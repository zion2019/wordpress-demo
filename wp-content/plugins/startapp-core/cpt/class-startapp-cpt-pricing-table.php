<?php

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/**
 * CPT Pricing Table
 *
 * @author 8guild
 */
class Startapp_CPT_Pricing_Table extends Startapp_CPT {

	/**
	 * Custom Post Type
	 *
	 * @var string
	 */
	protected $post_type = 'startapp_pricing';

	/**
	 * Taxonomy: Properties
	 *
	 * @var string
	 */
	protected $tax_properties = 'startapp_pricing_properties';

	/**
	 * Meta box: Plan Properties
	 *
	 * @var string
	 */
	protected $box_properties = '_startapp_plan_properties';

	/**
	 * Meta box: Plan Settings
	 *
	 * @var string
	 */
	protected $box_button = '_startapp_plan_button';

	/**
	 * Nonce name & action for "Plan Properties" meta box
	 *
	 * @var string
	 */
	private $nonce = 'startapp_pricing_props';
	private $nonce_field = 'startapp_pricing_props_nonce';

	/**
	 * Constructor
	 */
	public function __construct() {
	}

	public function init() {
		add_action( 'init', array( $this, 'register' ), 0 );

		// meta boxes
		add_action( 'equip/register', array( $this, 'add_meta_boxes' ) );
		add_action( "add_meta_boxes_{$this->post_type}", array( $this, 'custom_meta_boxes' ) );
		add_action( "save_post_{$this->post_type}", array( $this, 'save_meta_boxes' ), 10, 2 );
	}

	public function register() {
		$this->register_post_type();
		$this->register_tax_properties();
	}

	private function register_post_type() {
		$labels = array(
			'name'               => _x( 'Pricing Table', 'post type general name', 'startapp' ),
			'singular_name'      => _x( 'Pricing Table', 'post type singular name', 'startapp' ),
			'menu_name'          => __( 'Pricing Table', 'startapp' ),
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
			'label'               => __( 'Pricing Table', 'startapp' ),
			'labels'              => $labels,
			'description'         => __( 'A custom post type for building the nice Pricing Table', 'startapp' ),
			'public'              => false,
			'exclude_from_search' => true,
			'publicly_queryable'  => false,
			'show_ui'             => true,
			'show_in_menu'        => true,
			'show_in_nav_menus'   => false,
			'show_in_admin_bar'   => true,
			'menu_position'       => '48.1',
			'menu_icon'           => 'dashicons-media-spreadsheet',
			'capability_type'     => 'post',
			'hierarchical'        => false,
			'supports'            => array( 'title' ),
			'taxonomies'          => array( $this->tax_properties ),
			'has_archive'         => false,
			'rewrite'             => false,
			'query_var'           => false,
			'can_export'          => true,
		);

		register_post_type( $this->post_type, $args );
	}

	private function register_tax_properties() {
		$labels = array(
			'name'                       => _x( 'Properties', 'taxonomy general name', 'startapp' ),
			'singular_name'              => _x( 'Property', 'taxonomy singular name', 'startapp' ),
			'menu_name'                  => __( 'Properties', 'startapp' ),
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
			'description'        => __( 'Taxonomy used in pricing table for plan properties', 'startapp' ),
			'public'             => false,
			'show_ui'            => true,
			'show_in_nav_menus'  => false,
			'show_tagcloud'      => false,
			'show_in_quick_edit' => true,
			'show_admin_column'  => false,
			'meta_box_cb'        => false, // do not show meta box
			'hierarchical'       => true,
			'query_var'          => false,
			'rewrite'            => false,
		);

		register_taxonomy( $this->tax_properties, array( $this->post_type ), $args );
	}

	/**
	 * Add meta boxes through Equip
	 */
	public function add_meta_boxes() {
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
				'id'       => 'startapp-pricing-button',
				'title'    => __( 'Plan Button', 'startapp' ),
				'screen'   => $this->post_type,
				'context'  => 'normal',
				'priority' => 'low',
			) );

		} catch ( Exception $e ) {
			trigger_error( $e->getMessage() );
		}
	}

	/**
	 * Add custom meta box "Plan Properties"
	 *
	 * I can not use Equip because of the complexity
	 *
	 * @param WP_Post $post
	 */
	public function custom_meta_boxes( $post ) {
		add_meta_box(
			'startapp-plan-properties',
			__( 'Plan Properties', 'startapp' ),
			array( $this, 'do_properties_meta_box' ),
			$this->post_type,
			'normal',
			'high'
		);
	}

	/**
	 * Render meta box: Plan Properties
	 *
	 * @param WP_Post $post Post object
	 */
	public function do_properties_meta_box( $post ) {
		wp_nonce_field( $this->nonce, $this->nonce_field );

		$values = get_post_meta( $post->ID, $this->box_properties, true );
		if ( empty( $values ) ) {
			$values = array();
		}

		/**
		 * Filter the plan properties args
		 *
		 * Useful, if user wants to change the order
		 * or other arguments
		 *
		 * @param array $args Arguments
		 */
		$properties = get_terms( apply_filters( 'startapp_pricing_properties_args', array(
			'taxonomy'     => $this->tax_properties,
			'hide_empty'   => false,
			'hierarchical' => false,
			'orderby'      => 'term_id',
			'order'        => 'ASC',
		) ) );

		if ( empty( $properties ) || is_wp_error( $properties ) ) {
			_e( 'You should add some properties in Pricing Table > Properties', 'startapp' );

			return;
		}

		$template = <<<'PROPERTY'
<div class="startapp-pricing-property">
	<label for="{id}">{label}</label>
	<textarea name="{name}" class="widefat" id="{id}" rows="10">{value}</textarea>
</div>
PROPERTY;

		echo '<div class="startapp-pricing-plan-properties">';
		/** @var WP_Term $property Property */
		foreach ( $properties as $property ) {
			$r = array(
				'{id}'    => 'startapp-pricing-property-' . $property->slug,
				'{label}' => $property->name,
				'{name}'  => sprintf( '%1$s[%2$s]', $this->box_properties, $property->slug ),
				'{value}' => empty( $values[ $property->slug ] ) ? '' : $this->sanitize_property( $values[ $property->slug ] ),
			);

			echo str_replace( array_keys( $r ), array_values( $r ), $template );
			unset( $r );
		}
		unset( $property );
		echo '</div>';
		echo '<br>';
		echo '<p class="description">', __( 'You can use some special keywords, like "%infinity", "%available", "%not-available". Also when you create new line you wrap text with <code>&lt;ul&gt;</code> to list features. To make text bold just wrap string with <code>&lt;span class="text-bold"&gt;&lt;/span&gt;</code>. Please see all available helper classes in StartApp > Quick Help', 'startapp' ), '</p>';

	}

	/**
	 * Save post metadata when a post of {@see $this->post_type} is saved.
	 *
	 * @since 1.0.0
	 *
	 * @param int     $post_id The ID of the post.
	 * @param WP_Post $post    Post object
	 *
	 * @return void
	 */
	public function save_meta_boxes( $post_id, $post ) {
		// No auto-drafts, please
		if ( isset( $post->post_status ) && 'auto-draft' === $post->post_status ) {
			return;
		}

		if ( $this->post_type !== $post->post_type ) {
			return;
		}

		// only for properties
		if ( ! array_key_exists( $this->box_properties, $_POST ) ) {
			return;
		}

		// check the nonce
		if ( ! array_key_exists( $this->nonce_field, $_POST ) ) {
			return;
		}

		if ( ! wp_verify_nonce( $_POST[ $this->nonce_field ], $this->nonce ) ) {
			return;
		}

		if ( ! current_user_can( 'edit_post', $post_id ) ) {
			return;
		}

		// Check the auto-save and revisions
		if ( wp_is_post_autosave( $post_id ) || wp_is_post_revision( $post_id ) ) {
			return;
		}

		$values = array();
		array_walk( $_POST[ $this->box_properties ], function( $value, $property ) use ( &$values ) {
			$values[ $property ] = $this->sanitize_property( $value );
		} );

		update_post_meta( $post_id, $this->box_properties, $values );
	}

	/**
	 * Sanitize the value of each property in Plan Properties meta box
	 *
	 * @see Startapp_CPT_Pricing_Table::do_properties_meta_box()
	 * @see Startapp_CPT_Pricing_Table::save_meta_boxes()
	 *
	 * @param mixed $value The value
	 *
	 * @return string
	 */
	private function sanitize_property( $value ) {
		return empty( $value ) ? '' : wp_kses( $value, array(
			'span' => array( 'class' => true ),
		) );
	}
}
