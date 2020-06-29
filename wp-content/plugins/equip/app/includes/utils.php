<?php
/**
 * Utilities and helper functions
 *
 * @author  8guild
 * @package Equip\Utils
 */

if ( ! function_exists( 'equip_get_class_set' ) ) :
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
	function equip_get_class_set( $classes ) {
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

if ( ! function_exists( 'equip_get_attr' ) ) :
	/**
	 * Return prepared HTML attributes list for given attributes pairs
	 *
	 * Caution! This function does not escape neither attribute values,
	 * nor attribute names, for more flexibility. You should do this
	 * manually for each attribute before calling this function.
	 *
	 * @example
	 * equip_get_attr([
	 *   'class' => esc_attr( $class ),
	 *   'title' => esc_attr( $title ),
	 *   'value' => esc_html( $value )
	 * ]);
	 *
	 * You can pass an array as value for certain key,
	 * this array will be encoded to json format.
	 *
	 * @example
	 * equip_get_attr([
	 *   'class' => esc_attr( $class ),
	 *   'title' => esc_attr( $title ),
	 *   'value' => esc_html( $value )
	 *   'data-settings' => [ 'first' => '', 'second' => '' ],
	 * ]);
	 *
	 * HTML5 attributes also supported. Use `null` as value to output
	 * only the key.
	 *
	 * @example
	 * equip_get_attr([
	 *   'hidden' => null,     // will output `hidden`
	 *   'hidden' => 'hidden', // will output hidden="hidden"
	 * ]);
	 *
	 * @param array $attr Key-value pairs of HTML attributes
	 *
	 * @return string
	 */
	function equip_get_attr( $attr ) {
		if ( empty( $attr ) ) {
			return '';
		}

		$attributes = array();
		foreach ( (array) $attr as $attribute => $value ) {
			// reset the template
			$template = '%1$s="%2$s"';

			// convert array to json
			if ( is_array( $value ) ) {
				$template = '%1$s=\'%2$s\'';
				$value    = json_encode( $value );
			}

			if ( null === $value ) {
				$template = '%1$s';
			}

			if ( is_bool( $value ) ) {
				$value = $value ? 'true' : 'false';
			}

			// $value should not be empty, except numeric types
			if ( ! is_numeric( $value ) && empty( $value ) ) {
				continue;
			}

			$attributes[] = sprintf( $template, $attribute, $value );
		}

		return implode( ' ', $attributes );
	}
endif;

if ( ! function_exists( 'equip_get_image_src' ) ):
	/**
	 * Return non-escaped URL of the attachment by given ID.
	 * Perfect for background images or img src attribute.
	 *
	 * @param int    $attachment_id Attachment ID
	 * @param string $size          Image size, can be "full", "large", etc..
	 *
	 * @uses wp_get_attachment_image_src
	 *
	 * @return string String with url on success or empty string on fail.
	 */
	function equip_get_image_src( $attachment_id, $size = 'full' ) {
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

if ( ! function_exists( 'equip_get_networks' ) ) :
	/**
	 * Get social networks list
	 *
	 * You can override the networks.ini file by path:
	 *   child-theme/assets/misc/networks.ini
	 *   theme/assets/misc/networks.ini
	 *
	 * @return array
	 */
	function equip_get_networks() {
		/**
		 * This filter allows you to control the list of directories,
		 * where this function will search for the networks.ini file
		 *
		 * You can add a new path in case if you need completely change the data
		 *
		 * @param string $path Absolute path to networks.ini
		 */
		$paths = apply_filters( 'equip/networks/paths', [
			get_stylesheet_directory() . '/assets/misc/networks.ini',
			get_template_directory() . '/assets/misc/networks.ini',
			EQUIP_ASSETS_DIR . '/misc/networks.ini',
		] );

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
			return [];
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
		return apply_filters( 'equip/networks', $networks );
	}
endif;

if ( ! function_exists( 'equip_cache_key' ) ) :
	/**
	 * Get the cache key
	 *
	 * @param string     $key  Unique name of the element
	 * @param string|int $salt Some unique information, e.g. post ID
	 *
	 * @return string
	 */
	function equip_cache_key( $key, $salt = '' ) {
		$hash = substr( md5( $salt . $key ), 0, 8 );

		$key = preg_replace( '/[^a-z0-9_-]+/i', '-', $key );
		$key = str_replace( array( '-', '_' ), '-', $key );
		$key = trim( $key, '-' );
		$key = str_replace( '-', '_', $key );

		$hash = "equip_{$key}_{$hash}";

		return $hash;
	}
endif;

if ( ! function_exists( 'equip_cache_group' ) ) :
	/**
	 * Get cache group
	 *
	 * This function will prepend the 'equip_' prefix
	 * to the given group name
	 *
	 * @param string $group Group name
	 *
	 * @return string
	 */
	function equip_cache_group( $group ) {
		$group = ltrim( $group, '_-' );

		return 'equip_' . $group;
	}
endif;

if ( ! function_exists( 'equip_get_unique_id' ) ):
	/**
	 * Return the unique ID for general purposes
	 *
	 * @param string $prepend Will be prepended to generated string
	 * @param int    $limit   Limit the number of unique symbols!
	 *                        How many unique symbols should be in a string,
	 *                        maximum is 32 symbols. $prepend not included.
	 *
	 * @return string
	 */
	function equip_get_unique_id( $prepend = '', $limit = 8 ) {
		$unique = substr( md5( uniqid() ), 0, $limit );

		return $prepend . $unique;
	}
endif;

if ( ! function_exists( 'equip_get_nonce_name' ) ) :
	/**
	 * Returns meta box nonce name.
	 *
	 * Used as first parameter for {@see wp_nonce_filed}
	 * and second for {@see wp_verify_nonce}
	 *
	 * @link https://codex.wordpress.org/Function_Reference/wp_nonce_field
	 * @link https://codex.wordpress.org/Function_Reference/wp_verify_nonce
	 *
	 * @param string $module Element type: meta box, etc.
	 * @param string $slug   Element unique name
	 *
	 * @return string
	 */
	function equip_get_nonce_name( $module, $slug ) {
		$_slug = ltrim( $slug, '_' );
		$nonce = "equip_{$module}_{$_slug}_nonce";

		/**
		 * Filter the nonce name by its $slug
		 *
		 * Fires only for unique element
		 *
		 * @param string $nonce Nonce name
		 */
		$nonce = apply_filters( "equip/{$slug}/nonce/name", $nonce );

		/**
		 * Filter the nonce name.
		 *
		 * Fires for all elements.
		 *
		 * @param string $nonce Nonce name
		 * @param string $slug  Element unique name
		 */
		$nonce = apply_filters( "equip/nonce/name", $nonce, $slug );

		return $nonce;
	}
endif;

if ( ! function_exists( 'equip_get_nonce_action' ) ) :
	/**
	 * Returns meta box nonce action.
	 *
	 * Used as second parameter for {@see wp_nonce_field} and as a key
	 * for receiving the nonce value from $_POST for {@see wp_verify_nonce}.
	 *
	 * @link https://codex.wordpress.org/Function_Reference/wp_nonce_field
	 * @link https://codex.wordpress.org/Function_Reference/wp_verify_nonce
	 *
	 * @param string $module Element type: meta box, etc.
	 * @param string $slug   Element unique name
	 *
	 * @return string
	 */
	function equip_get_nonce_action( $module, $slug ) {
		$_slug  = ltrim( $slug, '_' );
		$action = "equip_{$module}_{$_slug}_nonce_action";

		/**
		 * Filter the nonce action by $slug.
		 *
		 * Fires only for unique element.
		 *
		 * @param string $action Action name
		 */
		$action = apply_filters( "equip/{$slug}/nonce/action", $action );

		/**
		 * Filter the meta box nonce action. Fires for all meta boxes.
		 *
		 * @link https://codex.wordpress.org/Function_Reference/wp_nonce_field
		 * @link https://codex.wordpress.org/Function_Reference/wp_verify_nonce
		 *
		 * @param string $action Action name
		 * @param string $slug   Meta box unique name
		 */
		$action = apply_filters( 'equip/nonce/action', $action, $slug );

		return $action;
	}
endif;

if ( ! function_exists( 'equip_the_text' ) ) :
	/**
	 * Maybe echoes the text
	 *
	 *  HTML allowed
	 *
	 * @param string $text   A piece of text
	 * @param string $before Before the text
	 * @param string $after  After the text
	 */
	function equip_the_text( $text, $before = '', $after = '' ) {
		if ( empty( $text ) ) {
			return;
		}

		echo $before, $text, $after;
	}
endif;

if ( ! function_exists( 'equip_get_text' ) ) :
	/**
	 * Returns the text
	 *
	 * HTML allowed
	 *
	 * @param string $text   A piece of text
	 * @param string $before Before the text
	 * @param string $after  After the text
	 *
	 * @return string
	 */
	function equip_get_text( $text, $before = '', $after = '' ) {
		return empty( $text ) ? '' : $before . $text . $after;
	}
endif;

if ( ! function_exists( 'equip_the_tag' ) ) :
	/**
	 * Echoes the HTML tag
	 *
	 * Supports paired and self-closing tags.
	 * If $contents is empty tag will be considered as a self closing.
	 *
	 * @param string $tag        The tag
	 * @param array  $attributes HTML attributes
	 * @param string $contents   Content
	 * @param string $type       Type of the tag: paired or self-closing. Default is self-closing.
	 */
	function equip_the_tag( $tag, $attributes = [], $contents = '', $type = 'self-closing' ) {
		if ( empty( $tag ) ) {
			return;
		}

		switch ( $type ) {
			case 'paired':
				printf( '<%1$s %2$s>%3$s</%1$s>',
					$tag,
					equip_get_attr( $attributes ),
					$contents
				);
				break;

			case 'self-closing':
			default:
				printf( '<%1$s %2$s>', $tag, equip_get_attr( $attributes ) );
				break;
		}
	}
endif;

if ( ! function_exists( 'equip_get_tag' ) ) :
	/**
	 * Returns the string representation of HTML tag
	 *
	 * Supports paired and self-closing tags.
	 * If $contents is empty tag will be considered as a self closing.
	 *
	 * @param string $tag        The tag
	 * @param array  $attributes HTML attributes
	 * @param string $contents   Content
	 * @param string $type       Type of the tag: paired or self-closing. Default is self-closing.
	 *
	 * @return string
	 */
	function equip_get_tag( $tag, $attributes = [], $contents = '', $type = 'self-closing' ) {
		if ( empty( $tag ) ) {
			return '';
		}

		switch ( $type ) {
			case 'paired':
				$result = sprintf( '<%1$s %2$s>%3$s</%1$s>',
					$tag,
					equip_get_attr( $attributes ),
					$contents
				);
				break;

			case 'self-closing':
			default:
				$result = sprintf( '<%1$s %2$s>', $tag, equip_get_attr( $attributes ) );
				break;
		}

		return $result;
	}
endif;

if ( ! function_exists( 'equip_datify' ) ) :
	/**
	 * Convert to data-*
	 *
	 * This function will prepend all values in array with "data-" prefix
	 *
	 * @param array $params Params, e.g. [title, button, etc]
	 *
	 * @return array [data-title, data-button, data-etc]
	 */
	function equip_datify( $params ) {
		if ( empty( $params ) ) {
			return [];
		}

		return array_map( function ( $attribute ) {
			return 'data-' . $attribute;
		}, $params );
	}
endif;

if ( ! function_exists( 'equip_css_declarations' ) ) :
	/**
	 * Generate CSS declarations like "width: auto;", "background-color: red;", etc.
	 *
	 * May be used either standalone function or in pair with {@see equip_css_rules}
	 *
	 * @param array $props Array of properties where key is a property name
	 *                     and value is a property value
	 *
	 * @return string
	 */
	function equip_css_declarations( $props ) {
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

if ( ! function_exists( 'equip_css_rules' ) ) :
	/**
	 * Generate CSS rules
	 *
	 * @uses equip_css_declarations
	 *
	 * @param string|array $selectors Classes or tags where properties will be applied to.
	 * @param string|array $props     Array of css rules where key is property name itself
	 *                                and value is a property value. Example: [font-size => 14px].
	 *                                Or string with CSS rules declarations in format: "font-size: 14px;"
	 *
	 * @return string CSS rules in format .selector {property: value;}
	 */
	function equip_css_rules( $selectors, $props ) {
		// Convert to string
		if ( is_array( $selectors ) ) {
			$selectors = implode( ', ', $selectors );
		}

		// convert to string, too
		if ( is_array( $props ) ) {
			$props = equip_css_declarations( $props );
		}

		return sprintf( '%1$s {%2$s}', $selectors, $props );
	}
endif;

if ( ! function_exists( 'equip_css_background_image' ) ) :
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
	 * $attr = equip_get_html_attr(array(
	 *   'class' => 'some-class',
	 *   'style' => equip_css_background_image( 123, 'medium', 'placeholder.jpg' )
	 * ));
	 * </pre>
	 *
	 * @param int    $attachment_id Attachment ID
	 * @param string $size          Image size, like "full", "medium", etc..
	 * @param string $fallback      Full URI to fallback image, good for placeholders.
	 *
	 * @return string
	 */
	function equip_css_background_image( $attachment_id, $size = 'full', $fallback = '' ) {
		$src = equip_get_image_src( $attachment_id, $size );
		if ( empty( $src ) && empty( $fallback ) ) {
			return '';
		}

		if ( empty( $src ) && ! empty( $fallback ) ) {
			$src = $fallback;
		}

		return equip_css_declarations( array(
			'background-image' => sprintf( 'url(%s)', esc_url( $src ) ),
		) );
	}
endif;

if ( ! function_exists( 'equip_sanitize_email' ) ) :
	/**
	 * A simple wrapper for {@see sanitize_email()}
	 *
	 * This function will remove "mailto:" prefix
	 *
	 * @param string $email Email
	 *
	 * @return string
	 */
	function equip_sanitize_email( $email ) {
		if ( false !== strpos( $email, 'mailto:' ) ) {
			$email = str_replace( 'mailto:', '', $email );
		}

		return sanitize_email( $email );
	}
endif;

if ( ! function_exists( 'equip_sanitize_key' ) ) :
	/**
	 * Prepare the field's key
	 *
	 * @param string $key
	 *
	 * @return string
	 */
	function equip_sanitize_key( $key ) {
		return '';
	}
endif;

if ( ! function_exists( 'equip_sanitize_slug' ) ) :
	/**
	 * Prepare element slug
	 *
	 * @param string $slug
	 *
	 * @return string
	 */
	function equip_sanitize_slug( $slug ) {
		return '';
	}
endif;


if ( ! function_exists( 'equip_sanitize_class' ) ) :
	/**
	 * This function will prepare the class name
	 *
	 * Designed for converting elements' slug into the class names
	 * in {@see \Equip\Factory}.
	 *
	 * @example
	 * <pre>
	 * image_select -> ImageSelect
	 * text -> Text
	 * raw_text -> RawText
	 * </pre>
	 *
	 * @param string $slug
	 *
	 * @return string
	 */
	function equip_sanitize_class( $slug ) {
		if ( false === strpos( $slug, '_' ) ) {
			return ucfirst( $slug );
		}

		$slug = explode( '_', $slug );
		$slug = array_map( 'ucfirst', $slug );
		$slug = implode( '', $slug );

		return $slug;
	}
endif;

if ( ! function_exists( 'equip_layout_get_type' ) ) :
	/**
	 * Returns the layout type
	 *
	 * If value is null, something is wrong!
	 *
	 * @param \Equip\Layout\Layout $layout Layout
	 *
	 * @return null|string
	 */
	function equip_layout_get_type( $layout ) {
		return $layout->type;
	}
endif;

if ( ! function_exists( 'equip_layout_get_elements' ) ) :
	/**
	 * Get elements from layout
	 *
	 * Element layout type is defined inside the callback
	 * {@see equip_layout_get_fields()} for example
	 *
	 * @param \Equip\Layout\Layout $layout   Layout
	 * @param callable             $callback The callback
	 *
	 * @return array
	 */
	function equip_layout_get_elements( $layout, $callback ) {
		$elements = [];
		$iterator = new \RecursiveIteratorIterator( new \Equip\Misc\RecursiveLayoutIterator( $layout ),
			\RecursiveIteratorIterator::CHILD_FIRST
		);

		foreach ( $iterator as $value ) {
			if ( $value instanceof \Equip\Layout\Layout ) {
				$elements[] = $value;
			}

			continue;
		}

		return array_filter( (array) $elements, $callback );
	}
endif;

if ( ! function_exists( 'equip_layout_get_fields' ) ) :
	/**
	 * Get fields settings from layout in format [key => [$field], ...]
	 *
	 * @param \Equip\Layout\Layout $layout Layout
	 *
	 * @return array
	 */
	function equip_layout_get_fields( $layout ) {
		$result = [];
		$fields = equip_layout_get_elements( $layout, function ( $element ) {
			return $element instanceof \Equip\Layout\FieldLayout;
		} );

		/** @var \Equip\Layout\FieldLayout $field */
		foreach ( (array) $fields as $field ) {
			$result[ $field->get_setting( 'key' ) ] = $field;
		}

		return $result;
	}
endif;

if ( ! function_exists( 'equip_map_pattern' ) ) :
	/**
	 * Prepare the key using the values from the $map by provided $pattern
	 *
	 * @see \Equip\Storage
	 *
	 * @example
	 * equip_map_pattern( 'module.slug', [module=>a, slug=>b]); // a.b
	 *
	 * @example
	 * equip_map_pattern( 'module.args[screen].slug', [
	 *   'slug' => '_prefix_page_settings'
	 *   'module' => 'metabox',
	 *   'args' => ['title' => 'a', 'screen' => 'post', 'priority' => 'high'],
	 * ]); // metabox.post._prefix_page_settings
	 *
	 * @param string $pattern Pattern. The keys from the $map, divided by dots
	 * @param array  $map     Mapped params
	 *
	 * @return string
	 */
	function equip_map_pattern( $pattern, $map ) {
		$parts = [];
		$keys  = explode( '.', $pattern );
		foreach ( $keys as $k ) {
			$outer = $k;
			$inner = null;
			if ( false !== strpos( $k, '[' ) ) {
				// find outer[inner] key matches
				preg_match( '/([a-zA-Z]+)\[([a-zA-Z_]+)\]/', $k, $m );
				if ( ! empty( $m ) ) {
					$outer = $m[1];
					$inner = $m[2];
				}
			}

			if ( empty( $map[ $outer ] ) ) {
				continue;
			}

			if ( null !== $inner && array_key_exists( $inner, $map[ $outer ] ) ) {
				$parts[] = $map[ $outer ][ $inner ];
			} else {
				$parts[] = $map[ $outer ];
			}
		}

		return implode( '.', $parts );
	}
endif;

if ( ! function_exists( 'equip_convert_to_data_attr' ) ) :
	/**
	 * Convert provided key-value pairs to data-* attributes
	 *
	 * ```
	 * equip_convert_to_data_attr([
	 *   'key1' => 'Val1',
	 *   'key2' => 'Val2',
	 * ]);
	 * ```
	 *
	 * Will be converted to [ 'data-key1' => 'Val1', 'data-key2' => 'Val2' ]
	 *
	 * @param array $settings Settings
	 *
	 * @return array
	 */
	function equip_convert_to_data_attr( $settings ) {
		$keys = array_map( function ( $el ) {
			return 'data-' . (string) $el;
		}, array_keys( $settings ) );

		return array_combine( $keys, array_values( $settings ) );
	}
endif;
