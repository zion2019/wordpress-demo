<?php
/**
 * Custom actions
 *
 * @author 8guild
 */

/**
 * Disable cache for some shortcodes
 *
 * @param bool   $is_cache  Whether to enable or disable cache
 * @param string $shortcode Shortcode tag
 *
 * @return bool
 */
function startapp_core_disable_shortcodes_cache( $is_cache, $shortcode ) {
	// disable cache for this shortcodes
	$shortcodes = array(
		'startapp_button',
	);

	return ( ! in_array( $shortcode, $shortcodes ) );
}

add_filter( 'startapp_shortcode_is_cache', 'startapp_core_disable_shortcodes_cache', 10, 2 );

/**
 * Enqueue the shortcode scripts on demand
 *
 * This function will cache the recently loaded shortcodes.
 * This will guarantee that each shortcode load their assets only once
 *
 * @param string $shortcode Shortcode tag
 */
function startapp_core_enqueue_shortcode_scripts( $shortcode ) {
	$key   = 'loaded_shortcodes';
	$group = 'startapp_shortcodes';

	// check if shortcode is already loaded
	$loaded = wp_cache_get( $key, $group );
	if ( false !== $loaded && is_array( $loaded ) && in_array( $shortcode, $loaded ) ) {
		return;
	}

	switch ( $shortcode ) {
		case 'startapp_progress_bars':
		case 'startapp_animated_digits':
			wp_enqueue_script( 'counterup' );
			break;

		case 'startapp_image_carousel':
		case 'startapp_testimonials_carousel':
		case 'startapp_logo_carousel':
		case 'startapp_testimonials_slider':
		case 'startapp_timeline':
			wp_enqueue_script( 'slick' );
			break;

		case 'startapp_map':
		case 'startapp_contacts_tile':
			wp_enqueue_script( 'gmap3' );
			break;

		case 'startapp_video_popup':
			wp_enqueue_style( 'magnific-popup' );
			wp_enqueue_script( 'magnific-popup' );
			break;

		case 'startapp_blog':
		case 'startapp_portfolio':
		case 'startapp_products':
			wp_enqueue_script( 'isotope' );
			break;

		case 'startapp_gallery':
			wp_enqueue_script( 'isotope' );
			wp_enqueue_style( 'photoswipe' );
			wp_enqueue_style( 'photoswipe-skin' );
			wp_enqueue_script( 'photoswipe' );
			wp_enqueue_script( 'photoswipe-ui' );
			break;

		case 'startapp_countdown':
			wp_enqueue_script( 'countdown' );
			break;
	}

	if ( ! is_array( $loaded ) ) {
		$loaded = array();
	}

	// add recently loaded shortcode to list
	$loaded[] = $shortcode;

	// cache for -1 seconds in case if some persistent storage used
	// (like memcached or redis), in other cases cache will live only
	// during the single request
	wp_cache_set( $key, $loaded, $group, - 1 );
}

add_action( 'startapp_shortcode_render_before', 'startapp_core_enqueue_shortcode_scripts' );

/**
 * Enqueue the custom fonts, previously registered in Visual Composer
 *
 * @param string $shortcode Shortcode tag
 * @param array  $atts      Shortcode attributes
 */
function startapp_core_enqueue_shortcode_fonts( $shortcode, $atts ) {
	if ( empty( $atts ) ) {
		return;
	}

	/*
	 * this is required because the shortcode
	 * may require more than one icon pack
	 *
	 * $library will contain the values of "icon_libraries"
	 */
	$libraries = array();
	foreach ( (array) $atts as $k => $v ) {
		if ( preg_match( '/icon_library/', $k ) ) {
			$libraries[] = $v;
		}
	}
	unset( $k, $v );

	if ( empty( $libraries ) ) {
		return;
	}

	$key    = 'loaded_fonts';
	$group  = 'startapp_shortcodes';
	$loaded = wp_cache_get( $key, $group );

	if ( ! is_array( $loaded ) ) {
		$loaded = array();
	}

	foreach ( array_unique( $libraries ) as $library ) {
		if ( in_array( $library, $loaded ) ) {
			continue;
		}

		startapp_vc_enqueue_icon_font( $library );
		$loaded[] = $library;
	}

	// this cache should be accessible only during the single request
	wp_cache_set( $key, $loaded, $group, - 1 );
}

add_action( 'startapp_shortcode_render_before', 'startapp_core_enqueue_shortcode_fonts', 10, 2 );

/**
 * Add mandatory params to each shortcode
 *
 * @param array $params Shortcode params
 *
 * @return array
 */
function startapp_core_mandatory_shortcode_params( $params ) {
	if ( empty( $params ) || ! is_array( $params ) ) {
		$params = array();
	}

	$class_desc = wp_kses( __( 'Add extra classes, divided by whitespace, if you wish to style particular content element differently. We added set of predefined extra classes to use inside this field. You can see the complete list of classes in <a href="%s" target="_blank">Quick Help</a> page.', 'startapp' ), array(
		'a' => array( 'href' => true, 'target' => true )
	) );
	$class_desc = sprintf( $class_desc, get_admin_url( null, 'admin.php?page=startapp-help' ) );

	$mandatory = array(
		array(
			'param_name'  => 'animation',
			'type'        => 'dropdown',
			'heading'     => __( 'Animation', 'startapp' ),
			'description' => __( 'Select type of animation for element to be animated when it "enters" the browsers viewport (Note: works only in modern browsers).', 'startapp' ),
			'std'         => 'none', // should be non-existing value!
			'save_always' => true,
			'weight'      => - 1,
			'value'       => startapp_get_animations(),
		),
		array(
			'param_name'  => 'class',
			'type'        => 'textfield',
			'weight'      => - 2,
			'heading'     => __( 'Extra class name', 'startapp' ),
			'description' => $class_desc,
		),
	);

	return array_merge( $params, $mandatory );
}

add_filter( 'startapp_shortcode_params', 'startapp_core_mandatory_shortcode_params', 999 );

/**
 * Fetch all testimonials posts for autocomplete field.
 *
 * It is safe to use IDs for import, because
 * WP Importer does not change IDs for posts.
 *
 * @see shortcodes/mapping/startapp_testimonials_slider.php
 *
 * @return array
 */
function startapp_core_testimonials_posts() {
	$cache_key   = 'startapp_testimonials_posts';
	$cache_group = 'startapp_autocomplete';

	$data = wp_cache_get( $cache_key, $cache_group );
	if ( false === $data ) {
		$data  = array();
		$posts = get_posts( array(
			'post_type'           => 'startapp_testimonial',
			'post_status'         => 'publish',
			'posts_per_page'      => - 1,
			'no_found_rows'       => true,
			'nopaging'            => true,
			'ignore_sticky_posts' => true,
		) );

		if ( empty( $posts ) || is_wp_error( $posts ) ) {
			return array();
		}

		foreach ( $posts as $post ) {
			$data[] = array(
				'value' => (int) $post->ID,
				'label' => $post->post_title,
			);
		}

		// cache for 1 day
		wp_cache_set( $cache_key, $data, $cache_group, 86400 );
	}

	return $data;
}

add_filter( 'startapp_testimonials_slider_posts_autocomlete', 'startapp_core_testimonials_posts', 1 );

/**
 * Fetch all Blog posts for autocomplete field.
 *
 * It is safe to use IDs for import, because
 * WP Importer does not change IDs for posts.
 *
 * @see shortcodes/mapping/startapp_blog.php
 *
 * @return array
 */
function startapp_core_blog_posts() {
	$cache_key   = 'startapp_blog_posts';
	$cache_group = 'startapp_autocomplete';

	$posts = wp_cache_get( $cache_key, $cache_group );
	if ( false === $posts ) {
		$posts  = array();
		$_posts = get_posts( array(
			'post_type'           => 'post',
			'post_status'         => 'publish',
			'posts_per_page'      => - 1,
			'ignore_sticky_posts' => true,
			'no_found_rows'       => true,
			'nopaging'            => true,
		) );

		if ( ! empty( $_posts ) && ! is_wp_error( $_posts ) ) {
			foreach ( $_posts as $post ) {
				// sanitize the title for json
				if ( ! empty( $post->post_title ) ) {
					$title = strip_tags( $post->post_title );
					$title = preg_replace( "!([\b\t\n\r\f\"\\'])!", "\\\\\\1", $title ); // escape json special characters
				} else {
					$title = esc_html__( '(no-title)', 'startapp' );
				}

				$posts[] = array(
					'value' => (int) $post->ID,
					'label' => $title,
				);
				unset( $title );
			}

			// cache for 1 day
			wp_cache_set( $cache_key, $posts, $cache_group, 86400 );
		}
	}

	return $posts;
}

add_filter( 'startapp_blog_posts_autocomplete', 'startapp_core_blog_posts', 1 );

/**
 * Fetch all public terms of blog posts for autocomplete field
 *
 * The taxonomy slug used as autocomplete value because
 * of export/import issues. WP Importer creates new
 * categories, tags, taxonomies based on import information
 * with NEW IDs!
 *
 * @see shortcodes/mapping/startapp_blog.php
 *
 * @return array
 */
function startapp_core_blog_terms() {
	$taxonomies = get_taxonomies( array(
		'public'      => true,
		'object_type' => array( 'post' )
	), 'objects' );

	// Exclude post_formats
	if ( array_key_exists( 'post_format', $taxonomies ) ) {
		unset( $taxonomies['post_format'] );
	}

	$terms = get_terms( array(
		'taxonomy'     => array_keys( $taxonomies ),
		'hierarchical' => false,
	) );

	if ( ! is_array( $terms ) || empty( $terms ) ) {
		return array();
	}

	$group_default = __( 'Taxonomies', 'startapp' );

	$data = array();
	foreach ( (array) $terms as $term ) {
		if ( isset( $taxonomies[ $term->taxonomy ] )
		     && isset( $taxonomies[ $term->taxonomy ]->labels )
		     && isset( $taxonomies[ $term->taxonomy ]->labels->name )
		) {
			$group = $taxonomies[ $term->taxonomy ]->labels->name;
		} else {
			$group = $group_default;
		}

		$data[] = array(
			'label'    => $term->name,
			'value'    => $term->slug,
			'group_id' => $term->taxonomy,
			'group'    => $group,
		);
	}

	usort( $data, function ( $i, $j ) {
		$a = strtolower( trim( $i['group'] ) );
		$b = strtolower( trim( $j['group'] ) );;

		if ( $a == $b ) {
			return 0;
		} elseif ( $a > $b ) {
			return 1;
		} else {
			return - 1;
		}
	} );

	return $data;
}

add_filter( 'startapp_blog_terms_autocomplete', 'startapp_core_blog_terms', 1 );

/**
 * Return categories for autocomplete field
 *
 * @see shortcodes/mapping/startapp_cateogry_tiles.php
 *
 * @return array
 */
function startapp_core_categories() {
	$cache_key   = 'startapp_categories';
	$cache_group = 'startapp_autocomplete';

	$data = wp_cache_get( $cache_key, $cache_group );
	if ( false === $data ) {
		$categories = get_terms( array(
			'taxonomy'     => 'category',
			'hide_empty'   => false,
			'hierarchical' => false,
		) );

		if ( empty( $categories ) || is_wp_error( $categories ) ) {
			return array();
		}

		$data = array();
		foreach ( $categories as $category ) {
			$data[] = array(
				'label' => $category->name,
				'value' => $category->slug,
			);
		}
		unset( $category );

		// cache for 1 day
		wp_cache_set( $cache_key, $data, $cache_group, 86400 );
	}

	return $data;
}

add_filter( 'startapp_category_tiles_slug_autocomlete', 'startapp_core_categories', 1 );

/**
 * Add custom fields to Category screen
 *
 * @see equip_create_layout()
 * @see equip_add()
 */
function startapp_core_category_custom_fields() {
	try {
		$layout = equip_create_layout( 'category' );

		$layout->set_setting( 'label', __( 'Additions', 'startapp' ) );
		$layout->set_flag( 'container', false ); // disable the container

		// add fields
		$layout->add_field( 'bg', 'media', array( 'label' => __( 'Featured Image', 'startapp' ) ) );

		equip_add( 'category', 'startapp_additions', $layout, array(
			'description' => __( 'Extra fields in Categories, required by StartApp theme', 'startapp' ),
		) );
	} catch ( Exception $e ) {
		trigger_error( $e->getMessage() );
	}
}

add_action( 'equip/register', 'startapp_core_category_custom_fields' );

/**
 * Add custom columns to Category taxonomy list
 *
 * @param array $columns
 *
 * @return array
 */
function startapp_core_category_columns( $columns ) {
	$columns['startapp_bg'] = __( 'Background', 'startapp' );

	return $columns;
}

add_filter( 'manage_edit-category_columns', 'startapp_core_category_columns' );

/**
 * Echo the content of custom columns
 *
 * @param string $content     Column content
 * @param string $column_name Column name
 * @param int    $term_id     Term ID
 *
 * @return string
 */
function startapp_core_category_columns_content( $content, $column_name, $term_id ) {
	$output = '';
	switch ( $column_name ) {
		case 'startapp_bg':
			$meta = get_term_meta( $term_id, 'startapp_additions', true );
			if ( ! empty( $meta['bg'] ) ) {
				$output = wp_get_attachment_image( (int) $meta['bg'], array( 50, 50 ) );
			}
			break;
	}

	return empty( $content ) ? $output : "{$content} {$output}";
}

add_filter( 'manage_category_custom_column', 'startapp_core_category_columns_content', 10, 3 );

/**
 * Load More posts in "Blog" shortcode
 *
 * Fires for both pagination types: Load More and Infinity Scroll
 *
 * AJAX callback for action "startapp_core_load_posts"
 */
function startapp_core_load_posts() {
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

	$type  = sanitize_key( $_POST['type'] );
	$posts = array();

	$query = new WP_Query( $query_args );
	if ( ! $query->have_posts() ) {
		wp_send_json_error( _x( 'Posts not found', 'ajax request', 'startapp' ) );
	}

	while ( $query->have_posts() ) :
		$query->the_post();
		ob_start();

		switch ( $type ) {
			case 'grid':
				echo '<div class="grid-item">';
				get_template_part( 'template-parts/blog/post', 'tile' );
				echo '</div>';
				break;

			case 'simple':
				get_template_part( 'template-parts/blog/post', 'simple' );
				break;

			case 'list':
			default:
				get_template_part( 'template-parts/blog/post', 'tile' );
				break;
		}

		$posts[] = str_replace( array( "\r\n", "\r", "\n", "\t" ), '', ob_get_clean() );
	endwhile;
	wp_reset_postdata();

	wp_send_json_success( $posts );
}

if ( is_admin() ) {
	add_action( 'wp_ajax_startapp_core_load_posts', 'startapp_core_load_posts' );
	add_action( 'wp_ajax_nopriv_startapp_core_load_posts', 'startapp_core_load_posts' );
}

/**
 * Load More products in "Products" shortcode
 *
 * AJAX callback for action "startapp_load_products"
 */
function startapp_core_load_products() {
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

	$posts = array();
	while ( $query->have_posts() ) :
		$query->the_post();

		ob_start();

		echo '<div class="grid-item">';
		wc_get_template_part( 'content', 'product' );
		echo '</div>';

		$posts[] = startapp_content_encode( ob_get_clean() );
	endwhile;
	wp_reset_postdata();

	wp_send_json_success( $posts );
}

if ( is_admin() ) {
	add_action( 'wp_ajax_startapp_load_products', 'startapp_core_load_products' );
	add_action( 'wp_ajax_nopriv_startapp_load_products', 'startapp_core_load_products' );
}

/**
 * Fetch all Products for autocomplete field.
 *
 * It is safe to use IDs for import, because
 * WP Importer does not change IDs for posts.
 *
 * @see shortcodes/mapping/startapp_shop_grid.php
 *
 * @return array
 */
function startapp_core_products_posts() {
	$cache_key   = 'startapp_shop_posts';
	$cache_group = 'startapp_autocomplete';

	$posts = wp_cache_get( $cache_key, $cache_group );
	if ( false === $posts ) {
		$posts = array();
		$data  = get_posts( array(
			'post_type'      => 'product',
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
			wp_cache_set( $cache_key, $posts, $cache_group, 86400 );
		}
	}

	return $posts;
}

add_filter( 'startapp_products_posts_autocomplete', 'startapp_core_products_posts', 1 );

/**
 * Fetch the Product categories for autocomplete field
 *
 * The taxonomy slug used as autocomplete value because
 * of export/import issues. WP Importer creates new
 * categories, tags, taxonomies based on import information
 * with NEW IDs!
 *
 * @see shortcodes/mapping/startapp_shop_grid.php
 *
 * @return array
 */
function startapp_core_products_categories() {
	$cache_key   = 'startapp_shop_cats';
	$cache_group = 'startapp_autocomplete';

	$data = wp_cache_get( $cache_key, $cache_group );
	if ( false === $data ) {
		$categories = get_terms( array(
			'taxonomy'     => 'product_cat',
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
		wp_cache_set( $cache_key, $data, $cache_group, 86400 );
	}

	return $data;
}

add_filter( 'startapp_products_categories_autocomplete', 'startapp_core_products_categories', 1 );

/**
 * Get WooCommerce products attributes
 * to fill in shortcode "query_attribute" param list
 *
 * @hooked startapp_products_attributes
 *
 * @see    shortcode/mapping/startapp_products.php
 *
 * @param array $attributes Attributes
 *
 * @return array
 */
function startapp_core_products_attributes( $attributes ) {
	if ( ! class_exists( 'WooCommerce' ) ) {
		return $attributes;
	}

	$taxonomies  = wc_get_attribute_taxonomies();
	$_attributes = array();
	foreach ( $taxonomies as $tax ) {
		$_attributes[ $tax->attribute_label ] = $tax->attribute_name;
	}
	unset( $tax );

	return array_merge( $attributes, $_attributes );
}

add_filter( 'startapp_products_attributes', 'startapp_core_products_attributes' );

/**
 * Defines default value for param "query_filter" if not provided.
 *
 * Takes from other param value.
 *
 * @hooked vc_form_fields_render_field_startapp_products_query_filter_param
 *
 * @param array $param_settings Param settings array
 * @param mixed $current_value  Current param value
 * @param array $map_settings   Shortcode map settings
 * @param array $atts           Shortcode attributes
 *
 * @return array
 */
function startapp_core_products_filter_default( $param_settings, $current_value, $map_settings, $atts ) {
	if ( ! isset( $atts['query_attribute'] ) || 'none' === $atts['query_attribute'] ) {
		return $param_settings;
	}

	$terms = get_terms( array( 'taxonomy' => 'pa_' . $atts['query_attribute'] ) );
	$value = array();
	if ( ! empty( $terms ) && ! is_wp_error( $terms ) ) {
		foreach ( $terms as $term ) {
			$value[ $term->name ] = $term->slug;
		}
	}

	if ( ! array_key_exists( 'default', $param_settings ) && ! empty( $current_value ) ) {
		$param_settings['default'] = $current_value;
	}

	if ( is_array( $value ) && ! empty( $value ) ) {
		$param_settings['value'] = $value;
	}

	return $param_settings;
}

add_filter( 'vc_form_fields_render_field_startapp_products_query_filter_param', 'startapp_core_products_filter_default', 10, 4 );

/**
 * Return new values for Products query_filter param
 * based on provided attribute value.
 *
 * This is an AJAX callback
 *
 * When user change query_attribute param dependency callback
 * perform an AJAX-request to get a new options.
 *
 * @hooked wp_ajax_startapp_wc_get_attribute_terms
 */
function startapp_core_products_filter_callback() {
	// Check nonce.
	if ( empty( $_POST['nonce'] ) || ! wp_verify_nonce( $_POST['nonce'], 'startapp-core' ) ) {
		wp_send_json_error( _x( 'Wrong nonce', 'ajax request', 'startapp' ) );
	}

	if ( empty( $_POST['attribute'] ) ) {
		wp_send_json_error( _x( 'You should pass "attribute" to load terms.', 'ajax request', 'startapp' ) );
	}

	$attribute = esc_attr( $_POST['attribute'] );
	if ( 'none' === $attribute ) {
		wp_send_json_error( _x( 'Can not load terms for "none" attribute.', 'ajax request', 'startapp' ) );
	}

	$terms = get_terms( 'pa_' . $attribute );
	if ( empty( $terms ) || is_wp_error( $terms ) ) {
		wp_send_json_error( _x( 'Terms for provided attribute not found', 'ajax request', 'startapp' ) );
	}

	$value = array();
	if ( ! empty( $_POST['value'] ) ) {
		$value = array_map( 'esc_attr', explode( ',', $_POST['value'] ) );
	}

	$data = array();
	foreach ( $terms as $term ) {
		$data[ $term->name ] = $term->slug;
	}
	unset( $term );

	$param = array(
		'param_name' => 'query_filter',
		'type'       => 'checkbox',
	);

	$params   = array();
	$template = '<label class="vc_checkbox-label"><input type="checkbox" id="%1$s" name="%2$s" value="%3$s" class="%4$s" %5$s> %6$s</label>';
	foreach ( $data as $label => $v ) {
		$id      = $param['param_name'] . '-' . $v;
		$class   = startapp_get_classes( array( 'wpb_vc_param_value', $param['param_name'], $param['type'] ) );
		$checked = in_array( $v, $value, true ) ? 'checked' : '';

		// 1 = id, 2 = name, 3 = value, 4 = class, 5 = checked, 6 = label
		$params[] = sprintf( $template, $id, $param['param_name'], $v, $class, $checked, $label );
		unset( $id, $class );
	}
	unset( $label, $v );

	wp_send_json_success( implode( '', $params ) );
}

add_action( 'wp_ajax_startapp_wc_get_attribute_terms', 'startapp_core_products_filter_callback' );

/**
 * Flush object cache for blog posts.
 *
 * Fires when post creating, updating or deleting.
 *
 * @see startapp_core_blog_posts()
 *
 * @param int $post_id Post ID
 */
function startapp_core_flush_blog_cache( $post_id ) {
	$type = get_post_type( $post_id );
	if ( 'post' !== $type ) {
		return;
	}

	wp_cache_delete( 'startapp_blog_posts', 'startapp_autocomplete' );
}

add_action( 'save_post_post', 'startapp_core_flush_blog_cache' );
add_action( 'deleted_post', 'startapp_core_flush_blog_cache' );

/**
 * Flush object cache for categories
 *
 * Fires when created, edited, deleted or updated a category
 *
 * @see shortcodes/mapping/startapp_cateogry_tiles.php
 * @see startapp_core_categories()
 *
 * @param int    $term_id  Term ID or Term Taxonomy ID (only for "edited_term_taxonomy" hook)
 * @param string $taxonomy Taxonomy name, exists only for "edit_term_taxonomy" hook
 */
function startapp_core_flush_categories_cache( $term_id, $taxonomy = null ) {
	if ( null === $taxonomy || 'category' === $taxonomy ) {
		wp_cache_delete( 'startapp_categories', 'startapp_autocomplete' );
	}
}

add_action( 'created_category', 'startapp_core_flush_categories_cache' );
add_action( 'delete_category', 'startapp_core_flush_categories_cache' );
add_action( 'edit_term_taxonomy', 'startapp_core_flush_categories_cache', 10, 2 );

/**
 * Flush object cache for products autocomplete.
 *
 * Fires when post creating, updating or deleting.
 *
 * @see startapp_core_products_posts()
 * @see shortcodes/mapping/startapp_products.php
 *
 * @param int $post_id Post ID
 */
function startapp_core_flush_products_posts_autocomplete_cache( $post_id ) {
	$type = get_post_type( $post_id );
	if ( 'product' !== $type ) {
		return;
	}

	wp_cache_delete( 'startapp_shop_posts', 'startapp_autocomplete' );
}

add_action( 'save_post_product', 'startapp_core_flush_products_posts_autocomplete_cache' );
add_action( 'deleted_post', 'startapp_core_flush_products_posts_autocomplete_cache' );

/**
 * Flush object cache for Product categories autocomplete
 *
 * Fires when created, edited, deleted or updated a category
 *
 * @see startapp_core_products_categories()
 * @see shortcodes/mapping/startapp_products.php
 *
 * @see wp_update_term() taxonomy.php :: 3440
 * @see _update_post_term_count() taxonomy.php :: 4152
 *
 * @param int    $term_id  Term ID or Term Taxonomy ID
 * @param string $taxonomy Taxonomy name, exists only for "edit_term_taxonomy"
 */
function startapp_core_flush_products_categories_autocomplete_cache( $term_id, $taxonomy = null ) {
	$cache_key   = 'startapp_shop_cats';
	$cache_group = 'startapp_autocomplete';

	if ( null === $taxonomy || 'product_cat' === $taxonomy ) {
		wp_cache_delete( $cache_key, $cache_group );
	}
}

add_action( 'create_product_cat', 'startapp_core_flush_products_categories_autocomplete_cache' );
add_action( 'delete_product_cat', 'startapp_core_flush_products_categories_autocomplete_cache' );
add_action( 'edit_term_taxonomy', 'startapp_core_flush_products_categories_autocomplete_cache', 10, 2 );


/**
 * Flush object cache for products.
 *
 * Fires when post creating, updating or deleting.
 *
 * @see shortcodes/mapping/startapp_shop_tile.php
 *
 * @param int $post_id Post ID
 */
function startapp_core_flush_product_select_cache( $post_id ) {
	$type = get_post_type( $post_id );
	if ( 'product' !== $type ) {
		return;
	}

	wp_cache_delete( 'startapp_product_posts', 'startapp' );
}

add_action( 'save_post_product', 'startapp_core_flush_product_select_cache' );
add_action( 'deleted_post', 'startapp_core_flush_product_select_cache' );

if ( ! function_exists( 'startapp_photoswipe' ) ) :
	/**
	 * Add PhotoSwipe (.pswp) element to DOM
	 *
	 * @see footer.php
	 */
	function startapp_photoswipe() {
		$post = get_queried_object();
		if ( ! $post instanceof WP_Post ) {
			return;
		}

		if ( false === strpos( $post->post_content, '[startapp_gallery' ) ) {
			return;
		}

		?>
        <div class="pswp" tabindex="-1" role="dialog" aria-hidden="true">
            <div class="pswp__bg"></div>
            <div class="pswp__scroll-wrap">
                <div class="pswp__container">
                    <div class="pswp__item"></div>
                    <div class="pswp__item"></div>
                    <div class="pswp__item"></div>
                </div>
                <div class="pswp__ui pswp__ui--hidden">
                    <div class="pswp__top-bar">
                        <div class="pswp__counter"></div>
                        <button class="pswp__button pswp__button--close"
                                title="<?php esc_html__( 'Close (Esc)', 'startapp' ); ?>"></button>
                        <button class="pswp__button pswp__button--share"
                                title="<?php esc_html__( 'Share', 'startapp' ); ?>"></button>
                        <button class="pswp__button pswp__button--fs"
                                title="<?php esc_html__( 'Toggle fullscreen', 'startapp' ); ?>"></button>
                        <button class="pswp__button pswp__button--zoom"
                                title="<?php esc_html__( 'Zoom in/out', 'startapp' ); ?>"></button>
                        <div class="pswp__preloader">
                            <div class="pswp__preloader__icn">
                                <div class="pswp__preloader__cut">
                                    <div class="pswp__preloader__donut"></div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="pswp__share-modal pswp__share-modal--hidden pswp__single-tap">
                        <div class="pswp__share-tooltip"></div>
                    </div>

                    <button class="pswp__button pswp__button--arrow--left"
                            title="<?php esc_html__( 'Previous (arrow left)', 'startapp' ); ?>"></button>
                    <button class="pswp__button pswp__button--arrow--right"
                            title="<?php esc_html__( 'Next (arrow right)', 'startapp' ); ?>"></button>

                    <div class="pswp__caption">
                        <div class="pswp__caption__center"></div>
                    </div>
                </div>
            </div>
        </div>
		<?php
	}
endif;

add_action( 'startapp_footer_after', 'startapp_photoswipe', 1000 );

/**
 * Remove the Equip scripts
 *
 * @param WP_Screen $screen Current screen
 */
function startapp_core_maybe_dequeue_equip_scripts( $screen ) {
	$revslider_pages = array(
		'toplevel_page_revslider',
		'slider-revolution_page_revslider_navigation',
		'slider-revolution_page_rev_addon',
	);

	if ( in_array( $screen->id, $revslider_pages ) ) {
		remove_action( 'admin_enqueue_scripts', '_equip_admin_enqueue_scripts', 10 );
	}
}

if ( is_admin() ) {
	add_action( 'current_screen', 'startapp_core_maybe_dequeue_equip_scripts' );
}
