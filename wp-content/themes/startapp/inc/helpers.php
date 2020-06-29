<?php
/**
 * Utility & helpers functions
 *
 * @author 8guild
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

if ( ! function_exists( 'startapp_get_classes' ) ) :
	/**
	 * Prepare and sanitize the class set.
	 *
	 * Caution! This function sanitize each class,
	 * but don't escape the returned result.
	 *
	 * E.g. [ 'my', 'cool', 'class' ] or 'my cool class'
	 * will be sanitized and converted to "my cool class".
	 *
	 * @param array|string $classes
	 *
	 * @return string
	 */
	function startapp_get_classes( $classes ) {
		if ( empty( $classes ) ) {
			return '';
		}

		if ( is_string( $classes ) ) {
			$classes = (array) $classes;
		}

		// remove empty elements before loop, if exists
		// and explode array into the flat list
		$classes   = array_filter( $classes );
		$class_set = array();
		foreach ( $classes as $class ) {
			$class = trim( $class );
			if ( false === strpos( $class, ' ' ) ) {
				$class_set[] = $class;

				continue;
			}

			// replace possible multiple whitespaces with single one
			$class = preg_replace( '/\\s\\s+/', ' ', $class );
			foreach ( explode( ' ', $class ) as $subclass ) {
				$class_set[] = trim( $subclass );
			}
			unset( $subclass );
		}
		unset( $class );

		// do not duplicate
		$class_set = array_unique( $class_set );
		$class_set = array_map( 'sanitize_html_class', $class_set );
		$class_set = array_filter( $class_set );

		$set = implode( ' ', $class_set );

		return $set;
	}
endif;

if ( ! function_exists( 'startapp_get_attr' ) ) :
	/**
	 * Return HTML attributes list for given attributes pairs
	 *
	 * Caution!
	 * This function does not escape attribute value or name,
	 * for more flexibility. You should do this manually for
	 * each attribute before calling this function.
	 *
	 * Also you can pass a multidimensional array with one level depth,
	 * this array will be encoded to json format.
	 *
	 * @example
	 * startapp_get_attr(array(
	 *   'class' => 'super-class',
	 *   'title' => 'My cool title',
	 *   'data-settings' => array( 'first' => '', 'second' => '' ),
	 * ));
	 *
	 * Sometimes some attributes are required and should be present in attributes
	 * list. For example, when you build attributes for a link "href" is mandatory.
	 * So if user do not fill this field default values will be used. Should
	 * be an array with the same keys as in $attr.
	 *
	 * @example
	 * startapp_get_attr([href => ''], [href => #]); // returns href="#"
	 *
	 * @param array $attr     Key and value pairs of HTML attributes
	 * @param array $defaults Default values, that should be present in attributes list
	 *
	 * @return string
	 */
	function startapp_get_attr( $attr, $defaults = array() ) {
		$attributes = array();

		foreach ( (array) $attr as $attribute => $value ) {
			$template = '%1$s="%2$s"';

			// if user pass empty value, use one from defaults if same field exists
			// allowed only for scalar types
			if ( is_scalar( $value )
			     && '' === (string) $value
			     && array_key_exists( $attribute, $defaults )
			) {
				$value = $defaults[ $attribute ];
			}

			// convert array to json
			if ( is_array( $value ) ) {
				$template = '%1$s=\'%2$s\'';
				$value    = json_encode( $value );
			}

			if ( is_bool( $value ) ) {
				$template = '%1$s';
			}

			// $value should not be empty
			if ( empty( $value ) ) {
				continue;
			}

			$attributes[] = sprintf( $template, $attribute, $value );
		}

		return implode( ' ', $attributes );
	}
endif;

if ( ! function_exists( 'startapp_get_unique_id' ) ):
	/**
	 * Return the unique ID for general purposes
	 *
	 * @param string $prepend Will be prepended to generated string
	 * @param int    $limit   Limit the number of unique symbols! How many unique symbols should be in a string,
	 *                        maximum is 32 symbols. $prepend not included.
	 *
	 * @return string
	 */
	function startapp_get_unique_id( $prepend = '', $limit = 8 ) {
		$unique = substr( md5( uniqid() ), 0, $limit );

		return $prepend . $unique;
	}
endif;

if ( ! function_exists( 'startapp_get_unique_key' ) ) :
	/**
	 * Return the cache field based on some $slug and $salt
	 *
	 * Should be less than 45 symbols!
	 *
	 * @param string $slug Name of the element which required hashing
	 * @param string $salt Some unique information, e.g. post ID
	 *
	 * @return string Example $slug_8
	 */
	function startapp_get_unique_key( $slug, $salt = '' ) {
		$hash = substr( md5( $salt . $slug ), 0, 8 );

		$slug = preg_replace( '/[^a-z0-9_-]+/i', '-', $slug );
		$slug = str_replace( array( '-', '_' ), '-', $slug );
		$slug = trim( $slug, '-' );
		$slug = str_replace( '-', '_', $slug );

		return "{$slug}_{$hash}";
	}
endif;

if ( ! function_exists( 'startapp_get_image_src' ) ):
	/**
	 * Return the URL of the attachment by given ID.
	 * Perfect for background images or img src attribute.
	 *
	 * @param int    $attachment_id Attachment ID
	 * @param string $size          Image size, can be "full", "large", etc..
	 *
	 * @uses wp_get_attachment_image_src()
	 *
	 * @return string String with url on success, FALSE on fail.
	 */
	function startapp_get_image_src( $attachment_id, $size = 'full' ) {
		if ( empty( $attachment_id ) ) {
			return '';
		}

		$attachment = wp_get_attachment_image_src( $attachment_id, $size );
		if ( false === $attachment ) {
			return '';
		}

		if ( ! empty( $attachment[0] ) ) {
			return $attachment[0];
		}

		return '';
	}
endif;

if ( ! function_exists( 'startapp_get_image_size' ) ):
	/**
	 * Return prepared image size
	 *
	 * @param string $size User specified image size. Default is "full"
	 *
	 * @return array|string Built-in size keyword or array of width and height
	 */
	function startapp_get_image_size( $size = 'full' ) {
		/**
		 * @var array Allowed image sizes and aliases
		 */
		$allowed = array_merge( get_intermediate_image_sizes(), array(
			'thumb',
			'post-thumbnail',
			'full',
		) );

		$out = 'full';
		if ( is_numeric( $size ) ) {
			// user specify single integer
			$size = (int) $size;
			$out  = array( $size, $size );
		} elseif ( false !== strpos( $size, 'x' ) ) {
			// user specify pair of width and height
			$out = array_map( 'absint', explode( 'x', $size ) );
		} elseif ( in_array( $size, $allowed, true ) ) {
			// user specify one of the built-in sizes
			$out = $size;
		}

		return $out;
	}
endif;

if ( ! function_exists( 'startapp_get_dir_contents' ) ) :
	/**
	 * Returns the contents of directory
	 *
	 * Designed for CPTs and shortcodes directories for auto loading
	 * files.
	 *
	 * @param string      $path Absolute path to directory
	 * @param string|null $ext  Suffix for {@see DirectoryIterator::getBasename}
	 *
	 * @return array A list of [filename => path]
	 */
	function startapp_get_dir_contents( $path, $ext = '.php' ) {
		$files = array();
		try {
			$dir = new DirectoryIterator( $path );
			foreach ( $dir as $file ) {
				if ( $file->isDot() || ! $file->isReadable() ) {
					continue;
				}

				$filename = $file->getBasename( $ext );

				// Do not load files if name starts with underscores
				if ( '_' === substr( $filename, 0, 1 ) ) {
					continue;
				}

				// do not load files if name starts with dots
				if ( '.' === substr( $filename, 0, 1 ) ) {
					continue;
				}

				$files[ $filename ] = $file->getPathname();
				unset( $filename );
			}
			unset( $file );
		} catch ( Exception $e ) {
			trigger_error( 'startapp_get_dir_contents(): ' . $e->getMessage() );
		}

		return $files;
	}
endif;

if ( ! function_exists( 'startapp_get_opacity_value' ) ) :
	/**
	 * Return the value for opacity css property
	 *
	 * @param string|int $opacity Opacity in percents from 0 to 100
	 *
	 * @return float
	 */
	function startapp_get_opacity_value( $opacity ) {
		$opacity = str_replace( ',', '.', $opacity );
		$opacity = rtrim( $opacity, '%' );
		$opacity = (float) $opacity;
		$opacity = ( 100 - ( 100 - $opacity ) ) / 100;
		$opacity = round( $opacity, 2 );
		$opacity = ( $opacity > 1 ) ? 1 : $opacity; // in case > 100%

		return (float) $opacity;
	}
endif;

if ( ! function_exists( 'startapp_get_text' ) ) :
	/**
	 * Maybe returns some text.
	 *
	 * HTML allowed
	 *
	 * @param string $text   A piece of text
	 * @param string $before Before the text
	 * @param string $after  After the text
	 *
	 * @return string
	 */
	function startapp_get_text( $text, $before = '', $after = '' ) {
		$text = trim( $text );
		if ( empty( $text ) ) {
			return '';
		}

		return $before . $text . $after;
	}
endif;

if ( ! function_exists( 'startapp_get_asset' ) ) :
	/**
	 * Get the unescaped fully qualified uri to the theme asset
	 *
	 * Also you can overwrite file in child-theme
	 *
	 * @uses get_stylesheet_directory_uri
	 *
	 * @param string $path Relative path to asset (img, css, js, etc)
	 *
	 * @return string
	 */
	function startapp_get_asset( $path ) {
		$theme_uri = get_stylesheet_directory_uri();

		$path = ltrim( $path, '/' );
		$uri  = $theme_uri . '/' . $path;

		return $uri;
	}
endif;

if ( ! function_exists( 'startapp_get_meta' ) ) :
	/**
	 * Returns the values of meta box.
	 *
	 * If $field is specified will return the field's value.
	 *
	 * TODO: test with polylang and qtranslate
	 *
	 * @param int         $post_id Post ID
	 * @param string      $slug    Meta box unique name
	 * @param null|string $field   Key of the field
	 * @param mixed       $default Default value
	 *
	 * @return mixed Array with field-value, mixed data if field is specified and the value
	 *               of $default field if nothing found.
	 */
	function startapp_get_meta( $post_id, $slug, $field = null, $default = array() ) {
		// pass to startapp if exists
		if ( function_exists( 'equip_get_meta' ) ) {
			return equip_get_meta( $post_id, $slug, $field, $default );
		}

		$cache_key   = startapp_get_unique_key( $slug, $post_id );
		$cache_group = 'meta_box';

		// Cached value should always be an array
		$values = wp_cache_get( $cache_key, $cache_group );
		if ( false === $values ) {
			$values = get_post_meta( $post_id, $slug, true );
			if ( empty( $values ) ) {
				// possible cases: meta box not saved yet
				// or mistake in $post_id or $slug
				return $default;
			}

			// cache for 1 day
			wp_cache_set( $cache_key, $values, $cache_group, 86400 );
		}

		$result = null;
		if ( ! is_array( $values ) ) {
			// return AS IS for non-array values
			$result = $values;
		} elseif ( null === $field ) {
			// return whole array if $field not specified
			$result = $values;
		} elseif ( array_key_exists( $field, $values ) ) {
			// if specified $field present
			$result = $values[ $field ];
		} else {
			// nothing matched, return default value
			$result = $default;
		}

		return $result;
	}
endif;

if ( ! function_exists( 'startapp_get_tag' ) ) :
	/**
	 * Returns the string representation of HTML tag
	 *
	 * Supports paired and self-closing tags.
	 * If $contents is empty tag will be considered as a self closing.
	 *
	 * @param string $tag     The tag
	 * @param array  $atts    HTML attributes
	 * @param string $content Content
	 * @param string $type    Type of the tag: paired or self-closing
	 *
	 * @return string
	 */
	function startapp_get_tag( $tag, $atts = array(), $content = null, $type = 'self-closing' ) {
		if ( empty( $tag ) ) {
			return '';
		}

		// specify the $content, even the empty string to make tag paired
		if ( 'paired' === $type || null !== $content ) {
			$result = sprintf( '<%1$s %2$s>%3$s</%1$s>',
				$tag,
				startapp_get_attr( $atts ),
				$content
			);
		} else {
			$result = sprintf( '<%1$s %2$s>', $tag, startapp_get_attr( $atts ) );
		}

		return $result;
	}
endif;

if ( ! function_exists( 'startapp_get_option' ) ) :
	/**
	 * Get theme option by its name
	 *
	 * All theme options are stored as an array
	 *
	 * @param string     $field   Option name or "all" for whole bunch of options.
	 * @param bool|mixed $default Option default value
	 *
	 * @return mixed
	 */
	function startapp_get_option( $field = 'all', $default = false ) {
		/**
		 * This filter allows to modify the Theme Options slug name
		 *
		 * @param string $slug Page Settings meta box slug
		 */
		$slug = apply_filters( 'startapp_get_option_slug', '' );

		$cache_key   = is_multisite() ? $slug . '_' . get_current_blog_id() : $slug;
		$cache_group = $slug . '_group';

		$options = wp_cache_get( $cache_key, $cache_group );
		if ( false === $options ) {
			$options = get_option( $slug );
			if ( empty( $options ) ) {
				// options was not saved yet
				return $default;
			}

			/**
			 * Filter the expiration time for theme options cache in seconds.
			 *
			 * Default is 1 hour.
			 *
			 * @param int    $expire Time until expiration in seconds.
			 * @param string $slug   Theme Options slug.
			 */
			$expire = apply_filters( 'startapp_get_option_expire', 3600, $slug );

			wp_cache_set( $cache_key, $options, $cache_group, $expire );
			unset( $expire );
		}

		if ( ! is_array( $options ) || 'all' === $field ) {
			$value = $options;
		} elseif ( array_key_exists( $field, $options ) ) {
			$value = $options[ $field ];
		} else {
			$value = $default;
		}

		/**
		 * Filters the value of an existing option.
		 *
		 * @param mixed  $value Value
		 * @param string $field Required field
		 */
		return apply_filters( 'startapp_get_option', $value, $field );
	}
endif;

if ( ! function_exists( 'startapp_get_options_slice' ) ) :
	/**
	 * Returns only those options which names started with given prefix.
	 *
	 * Based on naming convention when each option should has a global
	 * prefix based on section where this option is added.
	 *
	 * E.g. for typography options prefix should be "typography_",
	 * for colors options prefix should be "color_", etc.
	 *
	 * @param string $prefix Part of option name
	 *
	 * @return array Options names without prefix and its values
	 */
	function startapp_get_options_slice( $prefix ) {
		$options = startapp_get_option();
		if ( empty( $options ) ) {
			return array();
		}

		$prefix = rtrim( $prefix, '_' );
		$prefix .= '_';

		$sliced = array();
		foreach ( (array) $options as $option => $value ) {
			if ( false === strpos( $option, $prefix ) ) {
				continue;
			}

			$option = str_replace( $prefix, '', $option );

			$sliced[ $option ] = $value;
		}

		return $sliced;
	}
endif;

if ( ! function_exists( 'startapp_get_setting' ) ) :
	/**
	 * Get the setting
	 *
	 * Respect the local settings before checking the global
	 *
	 * This function first check the Page Settings by the provided key.
	 * If provided key is absent or result is empty next Theme Options
	 * will be checked. If result fails function returns default value.
	 *
	 * To make sure this function correctly works within The Loop
	 * you have to provide a third parameter $post_id.
	 *
	 * @param string   $setting Setting name, use "all" to get all page settings
	 * @param mixed    $default Default value
	 * @param null|int $post_id [optional] Post ID
	 *
	 * @return mixed
	 */
	function startapp_get_setting( $setting, $default = false, $post_id = null ) {
		// TODO: optimize this function
		// works perfect for pages and single posts
		// have to specify post_id in post loop

		if ( null !== $post_id ) {
			$post = get_post( $post_id );
		} else if ( is_main_query() ) {
			$post = get_queried_object();
		} else {
			$post = get_post();
		}

		$settings = false;
		$value    = false;

		if ( $post instanceof WP_Post ) {
			/**
			 * This filter allows to modify the Page Settings meta box slug name
			 *
			 * @param string $slug Page Settings meta box slug
			 */
			$slug     = apply_filters( 'startapp_get_setting_slug', '' );
			$settings = startapp_get_meta( $post->ID, $slug );
			unset( $slug );
		}

		// do not return empty set
		if ( empty( $settings ) || ! is_array( $settings ) ) {
			$settings = array();
		}

		/**
		 * This filters should be used to get a theme-specific keys
		 * and their default values which appears in Page Settings meta box
		 *
		 * @param array $defaults Default values
		 */
		$defaults = apply_filters( 'startapp_get_setting_defaults', array() );
		$settings = wp_parse_args( $settings, $defaults );

		if ( 'all' === $setting ) {
			return $settings;
		}

		// get value from local settings
		if ( array_key_exists( $setting, $settings ) ) {
			$value = $settings[ $setting ];
		}

		// if option not set of used "default" one check the global
		if ( 'default' === (string) $value || false === $value || is_array( $value ) ) {
			$value = startapp_get_option( $setting, $default );
		}

		return $value;
	}
endif;

if ( ! function_exists( 'startapp_get_post_terms' ) ) :
	/**
	 * Return terms, assigned for specified Post ID,
	 * depending on {@see $context} param: "slug" or "name".
	 *
	 * TODO: add caching
	 *
	 * @param integer $post_id  Post ID.
	 * @param string  $taxonomy The taxonomy for which to retrieve terms.
	 * @param string  $context  [optional] Term slug or name. Default is "slug".
	 *
	 * @return array [ term, term, ... ]
	 */
	function startapp_get_post_terms( $post_id, $taxonomy, $context = 'slug' ) {
		$post_terms = wp_get_post_terms( $post_id, $taxonomy );
		// Catch the WP_Error or if any terms was not assigned to post
		if ( is_wp_error( $post_terms ) || 0 === count( $post_terms ) ) {
			return array();
		}

		$terms = array();
		foreach ( $post_terms as $term ) {
			$terms[] = $term->$context;
		}
		unset( $term, $post_terms );

		return $terms;
	}
endif;

if ( ! function_exists( 'startapp_get_networks' ) ) :
	/**
	 * Get networks list
	 *
	 * @return array
	 */
	function startapp_get_networks() {
		if ( function_exists( 'equip_get_networks' ) ) {
			return equip_get_networks();
		}

		/**
		 * This filter allows you to control the list of directories,
		 * where this function will search for the networks.ini file
		 *
		 * You can add a new path in case if you need completely change the data
		 *
		 * @param string $path Absolute path to networks.ini
		 */
		$paths = apply_filters( 'startapp_path_to_networks_ini', array(
			get_stylesheet_directory() . '/assets/misc/networks.ini',
			get_template_directory() . '/assets/misc/networks.ini',
		) );

		if ( defined( 'STARTAPP_CORE_ROOT' ) ) {
			$path[] = STARTAPP_CORE_ROOT . '/assets/misc/networks.ini';
		}

		$located = false;
		foreach ( (array) $paths as $path ) {
			if ( file_exists( $path ) ) {
				$located = $path;
				break;
			}

			continue;
		}

		unset( $path );

		if ( false === $located ) {
			return array();
		}

		$ini      = wp_normalize_path( $located );
		$networks = parse_ini_file( $ini, true );

		ksort( $networks );

		/**
		 * Filter the networks array
		 *
		 * Useful in cases when you need to add some new networks,
		 * or change the existing data
		 *
		 * @param array $networks Networks list
		 */
		return apply_filters( 'startapp_get_networks', $networks );
	}
endif;

if ( ! function_exists( 'startapp_get_attachment' ) ) :
	/**
	 * Get attachment data
	 *
	 * @param int|WP_Post $attachment Attachment ID or post object.
	 *
	 * @see  wp_prepare_attachment_for_js()
	 * @link https://wordpress.org/ideas/topic/functions-to-get-an-attachments-caption-title-alt-description
	 *
	 * @return array
	 */
	function startapp_get_attachment( $attachment ) {
		$_attachment = $attachment;
		$attachment  = get_post( $attachment );
		if ( ! $attachment ) {
			return array();
		}

		if ( 'attachment' != $attachment->post_type ) {
			return array();
		}

		$data = array(
			'id'          => $attachment->ID,
			'title'       => $attachment->post_title,
			'alt'         => get_post_meta( $attachment->ID, '_wp_attachment_image_alt', true ),
			'author'      => $attachment->post_author,
			'description' => $attachment->post_content,
			'caption'     => $attachment->post_excerpt,
			'name'        => $attachment->post_name,
			'status'      => $attachment->post_status,
			'uploaded_to' => $attachment->post_parent,
			'menu_order'  => $attachment->menu_order,
			'mime'        => $attachment->post_mime_type,
			'date'        => strtotime( $attachment->post_date_gmt ) * 1000,
			'modified'    => strtotime( $attachment->post_modified_gmt ) * 1000,
		);

		/**
		 * Filter the attachment data
		 *
		 * This filters allows you easily add custom attachment data,
		 * like "filename", "url", "link", etc
		 *
		 * @param array       $data        Attachment data
		 * @param WP_Post     $attachment  Attachment post
		 * @param int|WP_Post $_attachment Attachment ID ot post object, passed to the function
		 */
		return apply_filters( 'startapp_get_attachment', $data, $attachment, $_attachment );
	}
endif;


if ( ! function_exists( 'startapp_get_cache_key' ) ) :
	/**
	 * This function is designed for creating a cache keys
	 * for shortcodes and based on shortcodes params.
	 *
	 * @param array  $data   Data for serialization
	 * @param string $prefix Part before the key
	 * @param string $suffix Part after the key
	 *
	 * @return string
	 */
	function startapp_get_cache_key( $data, $prefix = '' , $suffix = '' ) {
		$key = md5( serialize( $data ) );

		return $prefix . $key . $suffix;
	}
endif;

if ( ! function_exists( 'startapp_get_filters' ) ):
	/**
	 * Get the Isotope Filters HTML markup
	 *
	 * NOTE: "taxonomy" key is mandatory!
	 *
	 * @param array $args Arguments
	 *
	 * @return string
	 */
	function startapp_get_filters( $args = array() ) {
		$a = wp_parse_args( $args, array(
			'taxonomy'      => '',
			'exclude'       => '',
			'filters_id'    => '',
			'filters_class' => 'nav-filters',
			'grid_id'       => '',
			'show_all'      => '',
		) );

		if ( empty( $a['taxonomy'] ) ) {
			return '';
		}

		// get categories by provided taxonomy
		// and optionally exclude some of them
		$categories = get_terms( array(
			'taxonomy'     => $a['taxonomy'],
			'hierarchical' => false,
		) );

		if ( is_wp_error( $categories ) ) {
			return '';
		}

		$exclude    = startapp_parse_slugs( $a['exclude'] );
		$categories = array_filter( $categories, function ( $category ) use ( $exclude ) {
			return ( ! in_array( $category->slug, $exclude, true ) );
		} );

		if ( empty( $categories ) ) {
			return '';
		}

		$filters   = array();
		$filters[] = sprintf( '<li class="active"><a href="#" data-filter="*">%s</a></li>', esc_html( $a['show_all'] ) );

		/** @var WP_Term $category */
		foreach ( $categories as $category ) {
			$filters[] = sprintf( '<li><a href="#" data-filter=".%1$s">%2$s</a></li>',
				esc_attr( $category->slug ),
				esc_html( $category->name )
			);
		}

		$r = array(
			'{id}'      => esc_attr( $a['filters_id'] ),
			'{class}'   => esc_attr( $a['filters_class'] ),
			'{grid-id}' => esc_attr( $a['grid_id'] ),
			'{filters}' => implode( '', $filters ),
		);

		$t = '<nav id="{id}" class="{class}" data-grid-id="{grid-id}"><ul>{filters}</ul></nav>';

		return str_replace( array_keys( $r ), array_values( $r ), $t );
	}
endif;

if ( ! function_exists( 'startapp_get_animations' ) ) :
	/**
	 * Returns the animations
	 *
	 * Used in shortcode params (both vc and our custom).
	 * To make animations work use library AOS
	 *
	 * @link https://github.com/michalsnik/aos
	 *
	 * @return array
	 */
	function startapp_get_animations() {
		$animations = array(
			esc_html__( 'No', 'startapp' )              => '',
			esc_html__( 'Fade Up', 'startapp' )         => 'fade-up',
			esc_html__( 'Fade Down', 'startapp' )       => 'fade-down',
			esc_html__( 'Fade Right', 'startapp' )      => 'fade-right',
			esc_html__( 'Fade Left', 'startapp' )       => 'fade-left',
			esc_html__( 'Fade Up Right', 'startapp' )   => 'fade-up-right',
			esc_html__( 'Fade Up Left', 'startapp' )    => 'fade-up-left',
			esc_html__( 'Fade Down Right', 'startapp' ) => 'fade-down-right',
			esc_html__( 'Fade Down Left', 'startapp' )  => 'fade-down-left',
			esc_html__( 'Flip Left', 'startapp' )       => 'flip-left',
			esc_html__( 'Flip Right', 'startapp' )      => 'flip-right',
			esc_html__( 'Flip Up', 'startapp' )         => 'flip-up',
			esc_html__( 'Flip Down', 'startapp' )       => 'flip-down',
			esc_html__( 'Zoom In', 'startapp' )         => 'zoom-in',
			esc_html__( 'Zoom In Up', 'startapp' )      => 'zoom-in-up',
			esc_html__( 'Zoom In Down', 'startapp' )    => 'zoom-in-down',
			esc_html__( 'Zoom In Left', 'startapp' )    => 'zoom-in-left',
			esc_html__( 'Zoom In Right', 'startapp' )   => 'zoom-in-right',
			esc_html__( 'Zoom Out', 'startapp' )        => 'zoom-out',
			esc_html__( 'Zoom Out Up', 'startapp' )     => 'zoom-out-up',
			esc_html__( 'Zoom Out Down', 'startapp' )   => 'zoom-out-down',
			esc_html__( 'Zoom Out Right', 'startapp' )  => 'zoom-out-right',
			esc_html__( 'Zoom Out Left', 'startapp' )   => 'zoom-out-left',
		);

		/**
		 * Filter the allowed animations
		 *
		 * NOTE: key is used to print text in <option>, and value is an <option> value
		 *
		 * @param array $animations Animations
		 */
		return apply_filters( 'startapp_get_animations', $animations );
	}
endif;

if ( ! function_exists( 'startapp_is_animation' ) ) :
	/**
	 * Check if animation is enabled on a particular page
	 *
	 * @return bool
	 */
	function startapp_is_animation() {
		$post = get_queried_object();
		if ( ! $post instanceof WP_Post ) {
			return false;
		}

		// position can not be 0, so
		return preg_match( '/animation="([a-z-]+)"/', $post->post_content );
	}
endif;

if ( ! function_exists( 'startapp_is_video_background' ) ) :
	/**
	 * Check if Video Background is enabled on a particular page
	 *
	 * User can use Video Bg only in shortcode Row. So this shortcode
	 * will be outputted on any page, post, portfolio, etc. So it is
	 * save to use {@see get_queried_object()} function.
	 *
	 * @return bool
	 */
	function startapp_is_video_background() {
		$post = get_queried_object();
		if ( ! $post instanceof WP_Post || ! is_main_query() ) {
			return false;
		}

		$is_video_in_content    = preg_match( '/parallax_video="([\/:a-z0-9.-_]+)"/i', $post->post_content );
		$is_video_in_page_title = startapp_get_setting( 'page_title_parallax_video' );

		return ( $is_video_in_content || $is_video_in_page_title );
	}
endif;

if ( ! function_exists( 'startapp_is_active_sidebars' ) ) :
	/**
	 * This function acts like {@see is_active_sidebar()},
	 * but supports multiple sidebars.
	 *
	 * NOTE: if at least one sidebar active this function returns true
	 *
	 * Another words you can pass some sidebar IDs and if
	 * one of them is active this function returns true.
	 *
	 * @param array $sidebars A list of sidebars to check
	 *
	 * @return bool
	 */
	function startapp_is_active_sidebars( $sidebars = array() ) {
		if ( empty( $sidebars ) ) {
			return false;
		}

		$sidebars_widgets = wp_get_sidebars_widgets();
		$current_sidebars = array_intersect_key( $sidebars_widgets, array_flip( $sidebars ) );
		$active_sidebars  = array_filter( $current_sidebars );

		return count( $active_sidebars ) > 0;
	}
endif;

if ( ! function_exists( 'startapp_the_tag' ) ) :
	/**
	 * Echoes the HTML tag
	 *
	 * Supports paired and self-closing tags.
	 * If $contents is empty tag will be considered as a self closing.
	 *
	 * @param string $tag     The tag
	 * @param array  $atts    HTML attributes
	 * @param mixed  $content Content
	 * @param string $type    Type of the tag: paired or self-closing
	 */
	function startapp_the_tag( $tag, $atts = array(), $content = null, $type = 'self-closing' ) {
		echo startapp_get_tag( $tag, $atts, $content, $type );
	}
endif;

if ( ! function_exists( 'startapp_the_text' ) ) :
	/**
	 * Maybe echoes some text
	 *
	 * HTML allowed
	 *
	 * @param string $text   A piece of text
	 * @param string $before Before the text
	 * @param string $after  After the text
	 */
	function startapp_the_text( $text, $before = '', $after = '' ) {
		echo startapp_get_text( $text, $before, $after );
	}
endif;

if ( ! function_exists( 'startapp_the_asset' ) ) :
	/**
	 * Echoes the URI to the asset
	 *
	 * @see startapp_get_asset()
	 *
	 * @param string $path Relative path to asset (img, css, js, etc)
	 */
	function startapp_the_asset( $path ) {
		echo esc_url( startapp_get_asset( $path ) );
	}
endif;

if ( ! function_exists( 'startapp_parse_slugs' ) ) :
	/**
	 * Clean up an array, comma- or space-separated list of slugs.
	 *
	 * @example
	 * <pre>
	 * startapp_parse_slugs("a,b,c,d"); // returns [a, b, c, d]
	 * </pre>
	 *
	 * @example
	 * <pre>
	 * startapp_parse_slugs("a b c d"); // returns [a, b, c, d]
	 * </pre>
	 *
	 * @param array|string $list List of slugs
	 *
	 * @return array Sanitized array of slugs
	 */
	function startapp_parse_slugs( $list ) {
		if ( empty( $list ) ) {
			return array();
		}

		if ( ! is_array( $list ) ) {
			$list = preg_split( '/[\s,]+/', $list );
		}

		$list = array_map( 'urldecode', $list );
		$list = array_map( 'sanitize_title', $list );
		$list = array_unique( $list );

		return $list;
	}
endif;

if ( ! function_exists( 'startapp_parse_array' ) ):
	/**
	 * Find and extract the same-prefixed items from the given array
	 *
	 * Should the the associative array and test the keys, not values.
	 * Designed for integrated shortcodes and for logically grouped
	 * set of options.
	 *
	 * Very close to {@see startapp_get_options_slice},
	 * except working with given raw array, not theme options
	 *
	 * @example
	 * <pre>
	 * // integrated shortcode
	 * startapp_parse_array( array(
	 *   'title' => '',
	 *   'type' => '',
	 *   'etc' => '',
	 *   'icon_library' => '',
	 *   'icon_position' => '',
	 *   'icon_alignment' => '',
	 * ), 'icon_' );
	 *
	 * // returns array('library' => '', 'position' => '', 'alignment' => '');
	 * </pre>
	 *
	 * @example
	 * <pre>
	 * // options
	 * startapp_parse_array( array(
	 *   'typography_font_style' => '',
	 *   'typography_font_weight' => '',
	 *   'header_height' => '',
	 *   'header_mobile_height' => '',
	 * ), 'typography_' );
	 *
	 * // returns array('font_style' => '', 'font_weight' => '');
	 * </pre>
	 *
	 * @param array  $data   Some data
	 * @param string $prefix Integrated attributes prefix
	 *
	 * @return array Integrated shortcode attributes without prefix
	 */
	function startapp_parse_array( $data, $prefix ) {
		// prefix should always be appended with underscores,
		// e.g. "prefix_"
		$prefix = rtrim( $prefix, '_' );
		$prefix .= '_';

		$attributes = array();
		foreach ( $data as $k => $v ) {
			if ( false !== strpos( $k, $prefix ) ) {
				$clean                = str_replace( $prefix, '', $k );
				$attributes[ $clean ] = $v;
			}

			continue;
		}

		return $attributes;
	}
endif;

if ( ! function_exists( 'startapp_css_declarations' ) ) :
	/**
	 * Generate CSS declarations like "width: auto;", "background-color: red;", etc.
	 *
	 * May be used either standalone function or in pair with {@see startapp_css_rules}
	 *
	 * @param array $props Array of properties where field is a property name
	 *                     and value is a property value
	 *
	 * @return string
	 */
	function startapp_css_declarations( $props ) {
		$declarations = array();

		foreach ( $props as $name => $value ) {
			if ( is_scalar( $value ) ) {
				$declarations[] = "{$name}: {$value};";
				continue;
			}

			/*
			 * $value may be an array, not only scalar,
			 * in case of multiple declarations, like background gradients, etc.
			 *
			 * background: white;
			 * background: -moz-linear-gradient....
			 *
			 * $sub (sub value) should be a string!
			 */
			foreach ( (array) $value as $sub ) {
				$declarations[] = "{$name}: {$sub};";
			}
			unset( $sub );
		}
		unset( $name, $value );

		return implode( ' ', $declarations );
	}
endif;

if ( ! function_exists( 'startapp_css_rules' ) ) :
	/**
	 * Generate CSS rules in format .selector {property: value;}
	 *
	 * @uses startapp_css_declarations()
	 *
	 * @example
	 * startapp_css_declarations('a', 'color:red; text-transform:uppercase;');
	 *
	 * Output:
	 * a {color:red;text-transform:uppercase;}
	 *
	 * @example
	 * startapp_css_declarations('.custom-class', array(
	 *   'font-size' => '14px',
	 *   'color' => '#f0f0f0',
	 * ));
	 *
	 * Output:
	 * .custom-class {font-size:14px; color:#f0f0f0;}
	 *
	 * @example
	 * startapp_css_declaration(
	 *   array( 'a', '.custom-class' ),
	 *   array( 'font-size' => '14px', 'color' => '#f0f0f0' )
	 * );
	 *
	 * Output:
	 * a, .custom-class {font-size:14px; color:#f0f0f0;}
	 *
	 * @param string|array $selectors  Classes or tags, array or divided by whitespace
	 * @param string|array $props      Array of css rules
	 *
	 * @return string
	 */
	function startapp_css_rules( $selectors, $props ) {
		// Convert to string
		if ( is_array( $selectors ) ) {
			$selectors = implode( ', ', $selectors );
		}

		// convert to string, too
		if ( is_array( $props ) ) {
			$props = startapp_css_declarations( $props );
		}

		return sprintf( '%1$s {%2$s}', $selectors, $props );
	}
endif;

if ( ! function_exists( 'startapp_css_background_image' ) ) :
	/**
	 * Returns ready-to-use and escaped "background-image: %" string
	 * for style attribute.
	 *
	 * Useful for situations when you need only a background image.
	 * Specify the fallback if you do not want see element without
	 * the background.
	 *
	 * @example
	 * <pre>
	 * $attr = startapp_get_attr(array(
	 *   'class' => 'some-class',
	 *   'style' => startapp_css_background_image( 123, 'medium', 'placeholder.jpg' )
	 * ));
	 * </pre>
	 *
	 * @param int    $attachment Attachment ID or URI to the image
	 * @param string $size       Image size, like "full", "medium", etc..
	 * @param string $fallback   Full URI to fallback image, good for placeholders.
	 *
	 * @return string
	 */
	function startapp_css_background_image( $attachment, $size = 'full', $fallback = '' ) {
		if ( is_numeric( $attachment ) ) {
			$src = startapp_get_image_src( $attachment, $size );
		} else {
			$src = $attachment;
		}

		if ( empty( $src ) && empty( $fallback ) ) {
			return '';
		}

		if ( empty( $src ) && ! empty( $fallback ) ) {
			$src = $fallback;
		}

		return startapp_css_declarations( array(
			'background-image' => sprintf( 'url(%s)', esc_url( $src ) ),
		) );
	}
endif;

if ( ! function_exists( 'startapp_css_color' ) ):
	/**
	 * Returns the "color: $color" string for style attribute
	 *
	 * @example
	 * $attr['style'] = startapp_css_color( $color );
	 *
	 * @param string $color Any valid css color property
	 *
	 * @return string
	 */
	function startapp_css_color( $color ) {
		if ( '' === $color ) {
			return '';
		}

		return startapp_css_declarations( array( 'color' => $color ) );
	}
endif;

if ( ! function_exists( 'startapp_css_background_color' ) ):
	/**
	 * Returns the "background-color: $color" string for style attribute
	 *
	 * You have to sanitize/escape value before passing to this function
	 *
	 * @example
	 * $attr['style'] = startapp_css_background_color( $color );
	 *
	 * @param string $color Any valid css color property
	 *
	 * @return string
	 */
	function startapp_css_background_color( $color ) {
		if ( '' === $color ) {
			return '';
		}

		return startapp_css_declarations( array(
			'background-color' => $color,
		) );
	}
endif;

if ( ! function_exists( 'startapp_css_width' ) ) :
	/**
	 * Returns the "width: $width" string for style attribute
	 *
	 * @param int|float $width  Width
	 * @param string    $length px, %, inm pt, etc
	 *
	 * @return string
	 */
	function startapp_css_width( $width, $length = 'px' ) {
		if ( empty( $width ) ) {
			return '';
		}

		return startapp_css_declarations( array(
			'width' => $width . $length,
		) );
	}
endif;

if ( ! function_exists( 'startapp_css_custom_color' ) ) :
	/**
	 * Returns the CSS Rules for button custom color.
	 *
	 * The results is depending on button type, so
	 * this function has limited use.
	 *
	 * @see shortcodes/startapp_button.php
	 *
	 * @param string $color Custom color. Should be HEX.
	 * @param string $class Custom class. Unique per element.
	 * @param string $type  Button type
	 *
	 * @return string
	 */
	function startapp_css_custom_color( $color, $class, $type ) {
		$css = array();

		switch ( $type ) {
			case 'solid':
				$css[] = startapp_css_rules( ".{$class}", array(
					'background-color' => $color,
				) );

				$css[] = startapp_css_rules( ".{$class}:hover", array(
					'background-color' => startapp_color_darken( $color, 10 ),
				) );
				break;

			case 'ghost':
				$css[] = startapp_css_rules( ".{$class}", array(
					'border-color' => startapp_color_rgba( $color, '.15' ),
					'color'        => $color,
				) );

				$css[] = startapp_css_rules( ".{$class}:hover", array(
					'border-color'     => startapp_color_rgba( $color, '.15' ),
					'background-color' => $color,
					'color'            => '#ffffff',
				) );
				break;

			case '3d':
				$css[] = startapp_css_rules( ".{$class}", array(
					'background-color' => "{$color} !important",
					'box-shadow'       => sprintf( '0 4px 0 %s !important', startapp_color_darken( $color, 15 ) ),
				) );

				$css[] = startapp_css_rules( ".{$class}:hover", array(
					'background-color' => startapp_color_darken( $color, 10 ) . ' !important',
				) );

				$css[] = startapp_css_rules( ".{$class}:active", array(
					'box-shadow' => sprintf( '0 2px 0 %s !important', startapp_color_darken( $color, 15 ) ),
				) );
				break;

			case 'transparent':
				$css[] = startapp_css_rules( ".{$class}", array(
					'background-color' => startapp_color_rgba( $color, '.15' ),
					'color'            => $color,
				) );

				$css[] = startapp_css_rules( ".{$class}:hover", array(
					'background-color' => $color,
					'color'            => '#ffffff',
				) );
				break;

			case 'link':
				$css[] = startapp_css_rules( ".{$class}", array(
					'color' => $color,
				) );

				$css[] = startapp_css_rules( ".{$class}:hover", array(
					'color' => startapp_color_darken( $color, 10 ),
				) );
				break;
		}

		return implode( PHP_EOL, array_filter( $css ) );
	}
endif;

if ( ! function_exists( 'startapp_content_encode' ) ) :
	/**
	 * Encode the content before caching.
	 *
	 * @param string $content Some content, usually HTML string.
	 *
	 * @return string
	 */
	function startapp_content_encode( $content ) {
		return str_replace( array( "\r\n", "\r", "\n", "\t" ), '', $content );
	}
endif;

if ( ! function_exists( 'startapp_content_decode' ) ) :
	/**
	 * Decode the previously encoded content
	 *
	 * @param string $content Encoded and cached value
	 *
	 * @return string
	 */
	function startapp_content_decode( $content ) {
		return $content;
	}
endif;

if ( ! function_exists( 'startapp_query_encode' ) ) :
	/**
	 * Encoding the query args for passing into the html
	 *
	 * @see bnb_query_decode
	 *
	 * @param array $query Query args for WP_Query
	 *
	 * @return string
	 */
	function startapp_query_encode( $query ) {
		return (array) $query;
	}
endif;

if ( ! function_exists( 'startapp_query_decode' ) ):
	/**
	 * Decoding the encoded string with query args for WP_Query
	 *
	 * @see bnb_query_encode
	 *
	 * @param string $query Encoded string with query args
	 *
	 * @return array|null
	 */
	function startapp_query_decode( $query ) {
		return is_array( $query ) ? $query : json_decode( $query, true );
	}
endif;

if ( ! function_exists( 'startapp_query_per_page' ) ) :
	/**
	 * Handle the "posts_per_page" option for WP_Query.
	 *
	 * Return -1 for "all" posts, absolute number or if valid value not given
	 * returns the value from Settings > Reading option.
	 *
	 * @param mixed $per_page
	 *
	 * @return int
	 */
	function startapp_query_per_page( $per_page ) {
		if ( 'all' === strtolower( $per_page ) ) {
			$pp = - 1;
		} elseif ( is_numeric( $per_page ) ) {
			$pp = (int) $per_page;
		} else {
			$pp = (int) get_option( 'posts_per_page' );
		}

		return $pp;
	}
endif;

if ( ! function_exists( 'startapp_query_single_tax' ) ) :
	/**
	 * Build a tax_query with a single taxonomy for WP_Query
	 *
	 * Use the taxonomy slug because of export/import issues.
	 * During the import process WordPress creates new taxonomy
	 * (with new ID) based on import information.
	 *
	 * @param string $terms    A comma-separated list of slugs, directly from a shortcode attr
	 * @param string $taxonomy Taxonomy name
	 *
	 * @return array A read-to-use tax_query
	 */
	function startapp_query_single_tax( $terms, $taxonomy ) {
		if ( empty( $terms ) ) {
			return array();
		}

		$tax_queries   = array();
		$tax_queries[] = array(
			'taxonomy' => $taxonomy,
			'field'    => 'slug',
			'terms'    => startapp_parse_slugs( $terms ),
		);

		return $tax_queries;
	}
endif;

if ( ! function_exists( 'startapp_query_multiple_tax' ) ) :
	/**
	 * Build a tax_query for WP_Query with multiple number of taxonomies
	 *
	 * @param string $terms      A comma-separated list of terms slugs, directly from a shortcode attr.
	 * @param array  $taxonomies A list of taxonomies, like "category", "post_tag", custom tax.
	 *
	 * @return array
	 */
	function startapp_query_multiple_tax( $terms, $taxonomies ) {
		$_terms = get_terms( array(
			'taxonomy'     => $taxonomies,
			'hierarchical' => false,
			'slug'         => startapp_parse_slugs( $terms ),
		) );

		if ( ! is_array( $_terms ) || empty( $_terms ) ) {
			return array();
		}

		/*
		 * Build the taxonomies array for use in tax_query
		 *
		 * If taxonomy already exists in list, just add value to terms array.
		 * Otherwise add a new taxonomy to $tax_queries array.
		 */
		$tax_queries = array();
		foreach ( $_terms as $t ) {
			if ( array_key_exists( $t->taxonomy, $tax_queries ) ) {
				$tax_queries[ $t->taxonomy ]['terms'][] = $t->term_id;
			} else {
				$tax_queries[ $t->taxonomy ] = array(
					'taxonomy' => $t->taxonomy,
					'field'    => 'term_id',
					'terms'    => array( (int) $t->term_id ),
				);
			}
		}
		unset( $t );

		return array_values( $tax_queries );
	}
endif;

if ( ! function_exists( 'startapp_query_build' ) ) :
	/**
	 * Building an query for using in WP_Query
	 *
	 * If callback is specified the parsed query should be passed into it,
	 * so you can use some extra logic to process the query args before it
	 * will be returned. A good place for passing an anonymous function.
	 *
	 * @param array         $args     Key-value pairs for using inside WP_Query
	 * @param null|callable $callback Process query args with extra logic before returning
	 *
	 * @return array|mixed
	 */
	function startapp_query_build( $args, $callback = null ) {
		$query = array();

		$args = array_filter( $args );
		foreach ( $args as $param => $value ) {
			switch ( $param ) {
				case 'post__in':
				case 'post__not_in':
					$query[ $param ] = wp_parse_id_list( $value );
					break;

				case 'orderby':
				case 'order':
					$query[ $param ] = sanitize_text_field( $value );
					break;

				case 'posts_per_page':
					$query[ $param ] = startapp_query_per_page( $value );
					break;

				case 'categories':
				case 'taxonomies':
					// these are service keys, that are used for
					// building the tax_query, they should be processing in a callback,
					// so pass as is.
					$query[ $param ] = $value;
					break;

				default:
					$query[ $param ] = $value;
					break;
			}
		}
		unset( $param, $value );

		if ( is_callable( $callback ) ) {
			$query = call_user_func( $callback, $query );
		}

		// remove empty values
		$query = array_filter( $query );

		return $query;
	}
endif;

if ( ! function_exists( 'startapp_do_shortcode' ) ) :
	/**
	 * Parse TinyMCE content. Maybe do_shortcode().
	 *
	 * @param string     $content Shortcode content
	 * @param bool|false $autop   Use {@see wpautop()} or not
	 *
	 * @return string
	 */
	function startapp_do_shortcode( $content, $autop = false ) {
		if ( empty( $content ) ) {
			return '';
		}

		if ( $autop ) {
			$content = wpautop( preg_replace( '/<\/?p\>/', "\n", $content ) . "\n" );
		}

		return do_shortcode( shortcode_unautop( $content ) );
	}
endif;

if ( ! function_exists( 'startapp_shortcode_build' ) ) :
	/**
	 * Build a [shortcode] tag
	 *
	 * @param       $tag
	 * @param array $atts
	 * @param null  $content
	 *
	 * @return string
	 */
	function startapp_shortcode_build( $tag, $atts = array(), $content = null ) {
		$attributes = array();
		$enclosed   = '';
		if ( count( $atts ) > 0 ) {
			foreach ( $atts as $param => $value ) {
				$attributes[] = sprintf( '%1$s="%2$s"', $param, $value );
			}

			$attributes = implode( ' ', $attributes );
		}

		if ( null !== $content ) {
			$enclosed .= $content;
			$enclosed .= "[/{$tag}]";
		}

		return sprintf( '[%1$s %2$s]%3$s', $tag, $attributes, $enclosed );
	}
endif;


if ( ! function_exists( 'startapp_shortcode_template' ) ):
	/**
	 * Includes the shortcode template part
	 *
	 * @param string $template Template file name, e.g. icon-box-vertical.php
	 * @param array  $args     Arguments, required in template
	 *
	 * @return bool
	 */
	function startapp_shortcode_template( $template, $args = array() ) {
		if ( empty( $template ) ) {
			return false;
		}

		/**
		 * Filter the list of directories with shortcode template parts
		 *
		 * @param array $dirs Directories list
		 */
		$dirs = apply_filters( 'startapp_shortcode_parts_dirs', array(
			get_stylesheet_directory() . '/template-parts',
			get_template_directory() . '/template-parts',
			STARTAPP_CORE_ROOT . '/shortcodes/template-parts',
		) );

		foreach ( $dirs as $dir ) {
			$path = rtrim( $dir, '/\\' );
			$part = "{$path}/{$template}";
			if ( ! file_exists( $part ) ) {
				continue;
			}

			include $part;
			break; // break after first found template part
		}

		return true;
	}
endif;

if ( ! function_exists( 'startapp_vc_reset_params' ) ) :
	/**
	 * Remove all VC shortcode built-in params
	 *
	 * Make sure to avoid any unpredictable behaviour
	 * due to parameters come with new updates
	 *
	 * @param string $tag Shortcode tag
	 */
	function startapp_vc_reset_params( $tag ) {
		$shortcode = vc_get_shortcode( $tag );
		if ( ! is_array( $shortcode ) || ! is_array( $shortcode['params'] ) || empty( $shortcode['params'] ) ) {
			return;
		}

		foreach ( $shortcode['params'] as $param ) {
			if ( ! isset( $param['param_name'] ) ) {
				continue;
			}

			vc_remove_param( $tag, $param['param_name'] );
		}
	}
endif;

if ( ! function_exists( 'startapp_vc_replace_params' ) ) :
	/**
	 * Wrapper for {@see vc_add_params()}
	 *
	 * This function will replace default params
	 *
	 * @param string $tag    Shortcode tag
	 * @param array  $params Shortcode params
	 */
	function startapp_vc_replace_params( $tag, $params ) {
		startapp_vc_reset_params( $tag );

		/**
		 * This filter allow users to modify the mapping
		 * of default Visual Composer shortcodes.
		 *
		 * @param array  $params Shortcode params
		 * @param string $tag    Shortcode tag
		 */
		$params = apply_filters( 'startapp_shortcode_vc_params', $params, $tag );

		vc_add_params( $tag, $params );
	}
endif;

if ( ! function_exists( 'startapp_vc_map_params' ) ):
	/**
	 * Map params into the Visual Composer interface
	 *
	 * @param array  $params    Shortcode params
	 * @param string $shortcode Shortcode tag
	 *
	 * @return array
	 */
	function startapp_vc_map_params( $params, $shortcode ) {
		/**
		 * This filter allows you to modify the shortcode params.
		 *
		 * For example, you can add some new params, if you need.
		 * Or remove the existing one (or more).
		 *
		 * @see startapp_shortcode_default_atts hook
		 *
		 * @param array  $params    Shortcode params
		 * @param string $shortcode Shortcode tag
		 */
		$params = apply_filters( 'startapp_shortcode_params', $params, $shortcode );

		return $params;
	}
endif;

if ( ! function_exists( 'startapp_vc_parse_link' ) ) :
	/**
	 * Parse string like "title:Hello world|weekday:Monday" to [title => 'Hello World', weekday => 'Monday']
	 *
	 * This function is a fallback to Visual Composer's {@see vc_build_link()} function.
	 * Necessary for situations, when user disable VC but do not remove the shortcode.
	 *
	 * @param string $link Encoded link from TinyMCE link builder
	 *
	 * @return array
	 */
	function startapp_vc_parse_link( $link ) {
		if ( function_exists( 'vc_build_link' ) ) {
			return vc_build_link( $link );
		}

		$pairs = explode( '|', $link );
		if ( 0 === count( $pairs ) ) {
			return array( 'url' => '', 'title' => '', 'target' => '', 'rel' => '' );
		}

		$result = array();
		foreach ( $pairs as $pair ) {
			$param = preg_split( '/\:/', $pair );
			if ( ! empty( $param[0] ) && isset( $param[1] ) ) {
				$result[ $param[0] ] = rawurldecode( $param[1] );
			}
		}

		return $result;
	}
endif;

if ( ! function_exists( 'startapp_vc_build_link' ) ) :
	/**
	 * Convert a link in VC compatible format
	 *
	 * Array [url, title, target, rel] will be encoded and
	 * converter into format url:%|title:%|target:%|rel:%
	 *
	 * @param array $attr Attributes
	 *
	 * @return string
	 */
	function startapp_vc_build_link( $attr ) {
		if ( empty( $attr ) ) {
			return '';
		}

		if ( ! is_array( $attr ) ) {
			$attr = array();
		}

		$allowed = array( 'url', 'title', 'target', 'rel' );
		$attr    = array_intersect_key( $attr, array_flip( $allowed ) );

		$link = array();
		foreach ( $attr as $k => $v ) {
			$link[] = $k . ':' . rawurlencode( $v );
		}
		unset( $k, $v );

		return implode( '|', array_filter( $link ) );
	}
endif;

if ( ! function_exists( 'startapp_vc_column_width' ) ):
	/**
	 * Returns the small column class
	 *
	 * Parse the VC's column width attribute in format "1/12",
	 * "1/6", "7/12", etc and returns the .col-sm-{width} class
	 *
	 * @param string $width Width template "1/12"
	 *
	 * @return array
	 */
	function startapp_vc_column_width( $width ) {
		preg_match( '/(\d+)\/(\d+)/', $width, $matches );

		$class = 'col-sm-12'; // by default
		if ( ! empty( $matches ) ) {
			$part_x = (int) $matches[1];
			$part_y = (int) $matches[2];
			if ( $part_x > 0 && $part_y > 0 ) {
				$value = ceil( $part_x / $part_y * 12 );
				if ( $value > 0 && $value <= 12 ) {
					$class = 'col-sm-' . $value;
				}
			}
		}

		return (array) $class;
	}
endif;

if ( ! function_exists( 'startapp_vc_column_offset' ) ):
	/**
	 * Returns the column responsive classes
	 *
	 * Based on "Responsiveness" parameter inside
	 * the "Responsive Options"
	 *
	 * @param string $offset Classes
	 *
	 * @return array
	 */
	function startapp_vc_column_offset( $offset ) {
		if ( empty( $offset ) ) {
			return array();
		}

		$offset = str_replace( 'vc_', '', $offset );
		$result = explode( ' ', $offset );

		return $result;
	}
endif;

if ( ! function_exists( 'startapp_vc_column_class' ) ):
	/**
	 * Returns the set of column classes
	 *
	 * Include the column width and offset parameters
	 *
	 * @param string $width
	 * @param string $offset
	 *
	 * @return string
	 */
	function startapp_vc_column_class( $width, $offset ) {
		$width  = startapp_vc_column_width( $width );
		$offset = startapp_vc_column_offset( $offset );

		$class = array_merge( $width, $offset );
		sort( $class, SORT_STRING );

		return implode( ' ', $class );
	}
endif;

if ( ! function_exists( 'startapp_vc_enqueue_icon_font' ) ):
	/**
	 * Enqueue the font icons elements
	 *
	 * Use this function in all custom shortcode template
	 * to correctly enqueue fonts like "font-awesome".
	 *
	 * @param string $font Font icons pack
	 */
	function startapp_vc_enqueue_icon_font( $font ) {
		if ( 'custom' === $font ) {
			return;
		}

		/**
		 * Enqueue the custom icon fonts on the frontend
		 *
		 * @param string $font Fonts icon pack
		 */
		do_action( 'vc_enqueue_font_icon_element', $font );
	}
endif;

if ( ! function_exists( 'startapp_color_hex2rgb' ) ) :
	/**
	 * Convert HEX to RGB
	 *
	 * Returns an array with the rgb values
	 *
	 * @param string $hex Hex color
	 *
	 * @return array
	 */
	function startapp_color_hex2rgb( $hex ) {
		$hex = str_replace( "#", "", $hex );

		if ( strlen( $hex ) == 3 ) {
			$r = hexdec( substr( $hex, 0, 1 ) . substr( $hex, 0, 1 ) );
			$g = hexdec( substr( $hex, 1, 1 ) . substr( $hex, 1, 1 ) );
			$b = hexdec( substr( $hex, 2, 1 ) . substr( $hex, 2, 1 ) );
		} else {
			$r = hexdec( substr( $hex, 0, 2 ) );
			$g = hexdec( substr( $hex, 2, 2 ) );
			$b = hexdec( substr( $hex, 4, 2 ) );
		}
		$rgb = array( $r, $g, $b );

		return $rgb; // returns an array with the rgb values
	}
endif;

if ( ! function_exists( 'startapp_color_rgb2hex' ) ):
	/**
	 * Convert RGB to HEX
	 *
	 * Returns the hex value including the number sign (#)
	 *
	 * @param array $rgb RGB values
	 *
	 * @return string
	 */
	function startapp_color_rgb2hex( $rgb ) {
		$hex = "#";
		$hex .= str_pad( dechex( $rgb[0] ), 2, "0", STR_PAD_LEFT );
		$hex .= str_pad( dechex( $rgb[1] ), 2, "0", STR_PAD_LEFT );
		$hex .= str_pad( dechex( $rgb[2] ), 2, "0", STR_PAD_LEFT );

		return $hex;
	}
endif;

if ( ! function_exists( 'startapp_color_rgb2hsl' ) ):
	/**
	 * Convert RGB to HSL
	 *
	 * @param int $red
	 * @param int $green
	 * @param int $blue
	 *
	 * @return array
	 */
	function startapp_color_rgb2hsl( $red, $green, $blue ) {
		$min = min( $red, $green, $blue );
		$max = max( $red, $green, $blue );
		$l   = $min + $max;
		$d   = $max - $min;
		if ( (int) $d === 0 ) {
			$h = $s = 0;
		} else {
			if ( $l < 255 ) {
				$s = $d / $l;
			} else {
				$s = $d / ( 510 - $l );
			}
			if ( $red == $max ) {
				$h = 60 * ( $green - $blue ) / $d;
			} elseif ( $green == $max ) {
				$h = 60 * ( $blue - $red ) / $d + 120;
			} elseif ( $blue == $max ) {
				$h = 60 * ( $red - $green ) / $d + 240;
			}
		}

		return array( fmod( $h, 360 ), $s * 100, $l / 5.1 );
	}
endif;

if ( ! function_exists( 'startapp_color_hue2rgb' ) ):
	/**
	 * Hue to RGB helper
	 *
	 * @param float $m1
	 * @param float $m2
	 * @param float $h
	 *
	 * @return float
	 */
	function startapp_color_hue2rgb( $m1, $m2, $h ) {
		if ( $h < 0 ) {
			$h += 1;
		} elseif ( $h > 1 ) {
			$h -= 1;
		}
		if ( $h * 6 < 1 ) {
			return $m1 + ( $m2 - $m1 ) * $h * 6;
		}
		if ( $h * 2 < 1 ) {
			return $m2;
		}
		if ( $h * 3 < 2 ) {
			return $m1 + ( $m2 - $m1 ) * ( 2 / 3 - $h ) * 6;
		}

		return $m1;
	}
endif;

if ( ! function_exists( 'startapp_color_hsl2rgb' ) ):
	/**
	 * Convert HSL to RGB
	 *
	 * @param integer $hue        H from 0 to 360
	 * @param integer $saturation S from 0 to 100
	 * @param integer $lightness  L from 0 to 100
	 *
	 * @return array
	 */
	function startapp_color_hsl2rgb( $hue, $saturation, $lightness ) {
		if ( $hue < 0 ) {
			$hue += 360;
		}
		$h   = $hue / 360;
		$s   = min( 100, max( 0, $saturation ) ) / 100;
		$l   = min( 100, max( 0, $lightness ) ) / 100;
		$m2  = $l <= 0.5 ? $l * ( $s + 1 ) : $l + $s - $l * $s;
		$m1  = $l * 2 - $m2;
		$r   = startapp_color_hue2rgb( $m1, $m2, $h + 1 / 3 ) * 255;
		$g   = startapp_color_hue2rgb( $m1, $m2, $h ) * 255;
		$b   = startapp_color_hue2rgb( $m1, $m2, $h - 1 / 3 ) * 255;
		$out = [ $r, $g, $b ];

		return $out;
	}
endif;

if ( ! function_exists( 'startapp_color_darken' ) ):
	/**
	 * Makes a color darker
	 *
	 * Takes a color and a number between 0 and 100, and returns
	 * a color with the lightness decreased by that amount.
	 *
	 * @example startapp_color_darken( '#3f6bbe', 10 );
	 *
	 * @param string $hex        HEX color, for example #fff
	 * @param int    $percentage Integer from 0 to 100
	 *
	 * @return string
	 */
	function startapp_color_darken( $hex, $percentage ) {
		$rgb = startapp_color_hex2rgb( $hex );
		$hsl = startapp_color_rgb2hsl( $rgb[0], $rgb[1], $rgb[2] );

		// adjust lightness
		$hsl[2] -= $percentage;

		// convert back to RGB
		$rgb = startapp_color_hsl2rgb( $hsl[0], $hsl[1], $hsl[2] );
		$rgb = array_map( 'round', $rgb );

		$out = startapp_color_rgb2hex( $rgb );

		return $out;
	}
endif;

if ( ! function_exists( 'startapp_color_lighten' ) ) :
	/**
	 * Makes a color lighter
	 *
	 * Takes a color and a number between 0 and 100, and returns
	 * a color with the lightness increased by that amount.
	 *
	 * @param string $hex        HEX color, for example #fff
	 * @param int    $percentage Integer from 0 to 100
	 *
	 * @return string
	 */
	function startapp_color_lighten( $hex, $percentage ) {
		$rgb = startapp_color_hex2rgb( $hex );
		$hsl = startapp_color_rgb2hsl( $rgb[0], $rgb[1], $rgb[2] );

		// adjust lightness
		$hsl[2] += $percentage;

		// convert back to RGB
		$rgb = startapp_color_hsl2rgb( $hsl[0], $hsl[1], $hsl[2] );
		$rgb = array_map( 'round', $rgb );

		$out = startapp_color_rgb2hex( $rgb );

		return $out;
	}
endif;

if ( ! function_exists( 'startapp_color_rgba' ) ):
	/**
	 * Creates a RGBA from red, green, blue, and alpha values.
	 *
	 * @param string       $hex   HEX color, for example #fff
	 * @param string|float $alpha Alpha value from 0 to 1, or '.15'
	 *
	 * @return string
	 */
	function startapp_color_rgba( $hex, $alpha ) {
		$rgb   = startapp_color_hex2rgb( $hex );
		$rgb[] = $alpha;

		return 'rgba(' . implode( ', ', $rgb ) . ')';
	}
endif;

if ( ! function_exists( 'startapp_esc_url' ) ):
	/**
	 * Escapes the URL
	 *
	 * Unlike the WordPress built-in {@see esc_url()} this function
	 * also accepts parameters like "mailto:", "skype:", etc
	 *
	 * @param $url
	 *
	 * @return string|void
	 */
	function startapp_esc_url( $url ) {
		return preg_match( '@^https?://@i', $url ) ? esc_url( $url ) : esc_attr( $url );
	}
endif;

if ( ! function_exists( 'startapp_sanitize_text' ) ) :
	/**
	 * Sanitize the text with {@see wp_kses} and allowed tags
	 *
	 * List of allowed tags with their attributes:
	 *   a      [href target rel class]
	 *   i      [class]
	 *   em
	 *   span   [class]
	 *   strong
	 *
	 * @param string $text Raw text
	 *
	 * @return string
	 */
	function startapp_sanitize_text( $text ) {
		return wp_kses( stripslashes( trim( $text ) ), array(
			'a'      => array( 'href' => true, 'target' => true, 'class' => true, 'rel' => true ),
			'span'   => array( 'class' => true ),
			'i'      => array( 'class' => true ),
			'em'     => true,
			'strong' => true,
		) );
	}
endif;

if ( ! function_exists( 'startapp_sanitize_email' ) ) :
	/**
	 * A simple wrapper for {@see sanitize_email()}
	 *
	 * This function will remove "mailto:" prefix
	 *
	 * @param string $email Email
	 *
	 * @return string
	 */
	function startapp_sanitize_email( $email ) {
		if ( false !== strpos( $email, 'mailto:' ) ) {
			$email = str_replace( 'mailto:', '', $email );
		}

		return sanitize_email( $email );
	}
endif;

if ( ! function_exists( 'startapp_sanitize_float' ) ) :
	/**
	 * Sanitize number float
	 *
	 * NOTE: you can use only the "." (dot) as a delimiter.
	 *
	 * @param mixed $float Maybe a float number
	 *
	 * @return float
	 */
	function startapp_sanitize_float( $float ) {
		$float = str_replace( ',', '.', $float );
		$float = filter_var( $float, FILTER_SANITIZE_NUMBER_FLOAT, FILTER_FLAG_ALLOW_FRACTION );

		return (float) $float;
	}
endif;


if ( ! function_exists( 'hidden' ) ) :
	/**
	 * Outputs the html hidden attribute.
	 *
	 * Compares the first two arguments and if identical marks as hidden.
	 *
	 * @param mixed $hidden  One of the values to compare
	 * @param mixed $current The other value to compare if not just true
	 * @param bool  $echo    Whether to echo or just return the string
	 *
	 * @return string
	 */
	function hidden( $hidden, $current = true, $echo = true ) {
		if ( (string) $hidden === (string) $current ) {
			$result = 'hidden';
		} else {
			$result = '';
		}

		if ( $echo ) {
			echo esc_attr( $result );
		}

		return $result;
	}
endif;

if ( ! function_exists( 'startapp_google_font_url' ) ) :
	/**
	 * Prepare the link for Google Fonts the right way
	 *
	 * @param string $url A url to a Google Font
	 *
	 * @return string
	 */
	function startapp_google_font_url( $url ) {
		$query = parse_url( $url, PHP_URL_QUERY );
		if ( null === $query ) {
			return '';
		}

		parse_str( $query, $out );
		if ( ! array_key_exists( 'family', $out ) || empty( $out['family'] ) ) {
			return '';
		}

		$url = add_query_arg( 'family', urlencode( $out['family'] ), '//fonts.googleapis.com/css' );

		return esc_url( $url );
	}
endif;
