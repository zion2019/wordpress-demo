<?php

/**
 * Modified version of {@see Walker_Nav_Menu}.
 *
 * Inject the custom fields into the menu item.
 * Allowed for all menu item level.
 *
 * @uses Walker_Nav_Menu
 */
class Startapp_Walker_Nav_Menu_Top extends Walker_Nav_Menu {

	/**
	 * Start the element output.
	 *
	 * @see   Walker::start_el()
	 *
	 * @since 3.0.0
	 * @since 4.4.0 'nav_menu_item_args' filter was added.
	 *
	 * @param string $output Passed by reference. Used to append additional content.
	 * @param object $item   Menu item data object.
	 * @param int    $depth  Depth of menu item. Used for padding.
	 * @param array  $args   An array of arguments. @see wp_nav_menu()
	 * @param int    $id     Current item ID.
	 */
	public function start_el( &$output, $item, $depth = 0, $args = array(), $id = 0 ) {
		$indent = ( $depth ) ? str_repeat( "\t", $depth ) : '';

		// make sure all "StartApp Additions" fields are present
		$additions = isset( $item->startapp_additions ) ? $item->startapp_additions : array();
		$additions = wp_parse_args( $additions, array(
			'icon'      => '',
			'is_anchor' => 'disable',
		) );

		// make sure "StartApp Top" fields are present
		$top = isset( $item->startapp_top ) ? $item->startapp_top : array();
		$top = wp_parse_args( $top, array(
			'mega_menu' => 'none',
		) );

		$classes   = empty( $item->classes ) ? array() : (array) $item->classes;
		$classes[] = 'menu-item-' . $item->ID;
		$classes[] = ( 'none' !== $top['mega_menu'] && is_active_sidebar( $top['mega_menu'] ) ) ? 'has-mega-menu' : '';

		/**
		 * Filter the arguments for a single nav menu item.
		 *
		 * @since 4.4.0
		 *
		 * @param array  $args  An array of arguments.
		 * @param object $item  Menu item data object.
		 * @param int    $depth Depth of menu item. Used for padding.
		 */
		$args = apply_filters( 'nav_menu_item_args', $args, $item, $depth );

		/**
		 * Filter the CSS class(es) applied to a menu item's list item element.
		 *
		 * @since 3.0.0
		 * @since 4.1.0 The `$depth` parameter was added.
		 *
		 * @param array  $classes The CSS classes that are applied to the menu item's `<li>` element.
		 * @param object $item    The current menu item.
		 * @param array  $args    An array of {@see wp_nav_menu()} arguments.
		 * @param int    $depth   Depth of menu item. Used for padding.
		 */
		$class_names = join( ' ', apply_filters( 'nav_menu_css_class', array_filter( $classes ), $item, $args, $depth ) );
		$class_names = $class_names ? ' class="' . esc_attr( $class_names ) . '"' : '';

		/**
		 * Filter the ID applied to a menu item's list item element.
		 *
		 * @since 3.0.1
		 * @since 4.1.0 The `$depth` parameter was added.
		 *
		 * @param string $menu_id The ID that is applied to the menu item's `<li>` element.
		 * @param object $item    The current menu item.
		 * @param array  $args    An array of {@see wp_nav_menu()} arguments.
		 * @param int    $depth   Depth of menu item. Used for padding.
		 */
		$id = apply_filters( 'nav_menu_item_id', 'menu-item-' . $item->ID, $item, $args, $depth );
		$id = $id ? ' id="' . esc_attr( $id ) . '"' : '';

		$output .= $indent . '<li' . $id . $class_names . '>';

		$atts           = array();
		$atts['title']  = ! empty( $item->attr_title ) ? $item->attr_title : '';
		$atts['target'] = ! empty( $item->target ) ? $item->target : '';
		$atts['rel']    = ! empty( $item->xfn ) ? $item->xfn : '';
		$atts['href']   = ! empty( $item->url ) ? $item->url : '';

		// enable Link to Anchor feature
		// this class should be present on <a>
		if ( 'enable' === $additions['is_anchor'] ) {
			$atts['class'] = 'scroll-to';
		}

		/**
		 * Filter the HTML attributes applied to a menu item's anchor element.
		 *
		 * @since 3.6.0
		 * @since 4.1.0 The `$depth` parameter was added.
		 *
		 * @param array  $atts   {
		 *                       The HTML attributes applied to the menu item's `<a>` element, empty strings are ignored.
		 *
		 * @type string  $title  Title attribute.
		 * @type string  $target Target attribute.
		 * @type string  $rel    The rel attribute.
		 * @type string  $href   The href attribute.
		 * }
		 *
		 * @param object $item   The current menu item.
		 * @param array  $args   An array of {@see wp_nav_menu()} arguments.
		 * @param int    $depth  Depth of menu item. Used for padding.
		 */
		$atts = apply_filters( 'nav_menu_link_attributes', $atts, $item, $args, $depth );

		$attributes = '';
		foreach ( $atts as $attr => $value ) {
			if ( ! empty( $value ) ) {
				$value = ( 'href' === $attr ) ? esc_url( $value ) : esc_attr( $value );
				$attributes .= ' ' . $attr . '="' . $value . '"';
			}
		}

		/** This filter is documented in wp-includes/post-template.php */
		$title = apply_filters( 'the_title', $item->title, $item->ID );

		/**
		 * Filter a menu item's title.
		 *
		 * @since 4.4.0
		 *
		 * @param string $title The menu item's title.
		 * @param object $item  The current menu item.
		 * @param array  $args  An array of {@see wp_nav_menu()} arguments.
		 * @param int    $depth Depth of menu item. Used for padding.
		 */
		$title = apply_filters( 'nav_menu_item_title', $title, $item, $args, $depth );

		// check if icon is used for current menu item
		// and prepare the icon, it should be inside <a>
		$icon = '';
		if ( ! empty( $additions['icon'] ) ) {
			$class = esc_attr( $additions['icon'] );
			$icon  = startapp_get_tag( 'i', array( 'class' => $class ), '' );

			unset( $class );
		}

		// Mega Menu
		$mega_menu = '';
		if ( 'none' !== $top['mega_menu'] && is_active_sidebar( $top['mega_menu'] ) ) {
			ob_start();
			dynamic_sidebar( $top['mega_menu'] );
			$contents = ob_get_clean();

			$mega_menu .= '<div class="mega-menu">';
			$mega_menu .= '<div class="mega-menu-inner">';
			$mega_menu .= $contents;
			$mega_menu .= '</div>';
			$mega_menu .= '</div>';

			unset( $contents );
		}

		$item_output = $args->before;
		$item_output .= '<a' . $attributes . '>';
		$item_output .= $icon;
		$item_output .= $args->link_before . $title . $args->link_after;
		$item_output .= '</a>';
		$item_output .= $mega_menu;
		$item_output .= $args->after;

		/**
		 * Filter a menu item's starting output.
		 *
		 * The menu item's starting output only includes `$args->before`, the opening `<a>`,
		 * the menu item's title, the closing `</a>`, and `$args->after`. Currently, there is
		 * no filter for modifying the opening and closing `<li>` for a menu item.
		 *
		 * @since 3.0.0
		 *
		 * @param string $item_output The menu item's starting HTML output.
		 * @param object $item        Menu item data object.
		 * @param int    $depth       Depth of menu item. Used for padding.
		 * @param array  $args        An array of {@see wp_nav_menu()} arguments.
		 */
		$output .= apply_filters( 'walker_nav_menu_start_el', $item_output, $item, $depth, $args );
	}
}
