<?php
/**
 * Custom template tags for this theme.
 *
 * Eventually, some of the functionality here could be replaced by core features.
 *
 * @package Startapp
 */

if ( ! function_exists( 'startapp_is_categorized_blog' ) ) :
	/**
	 * Returns true if a blog has more than 1 category.
	 *
	 * @return bool
	 */
	function startapp_is_categorized_blog() {
		if ( false === ( $all_the_cool_cats = get_transient( STARTAPP_TRANSIENT_CATEGORIES ) ) ) {
			// Create an array of all the categories that are attached to posts.
			$all_the_cool_cats = get_categories( array(
				'fields'     => 'ids',
				'hide_empty' => 1,
				// We only need to know if there is more than one category.
				'number'     => 2,
			) );

			// Count the number of categories that are attached to the posts.
			$all_the_cool_cats = count( $all_the_cool_cats );

			set_transient( STARTAPP_TRANSIENT_CATEGORIES, $all_the_cool_cats );
		}

		if ( $all_the_cool_cats > 1 ) {
			// This blog has more than 1 category so startapp_is_categorized_blog should return true.
			return true;
		} else {
			// This blog has only 1 category so startapp_is_categorized_blog should return false.
			return false;
		}
	}
endif;


if ( ! function_exists( 'startapp_is_woocommerce' ) ) :
	/**
	 * Check if WooCommerce is activated
	 *
	 * @return bool
	 */
	function startapp_is_woocommerce() {
		return class_exists( 'WooCommerce' );
	}
endif;

if ( ! function_exists( 'startapp_is_scroller' ) ) :
	/**
	 * Check if Scroller Menu is enabled
	 *
	 * Based on Page Settings > Theme Options
	 *
	 * @see startapp_add_header_options()
	 *
	 * @return bool
	 */
	function startapp_is_scroller() {
		$is_scroller = (int) startapp_get_setting( 'header_is_scroller', 1 );

		return (bool) $is_scroller;
	}
endif;

if ( ! function_exists( 'startapp_is_page_title' ) ) :
	/**
	 * Check if page title is enabled
	 *
	 * Based on Page Settings > Theme Options
	 *
	 * @see startapp_add_header_options()
	 * @see startapp_add_page_settings_meta_box()
	 * @see startapp_page_title()
	 *
	 * @return bool
	 */
	function startapp_is_page_title() {
		$is_page_title = (int) startapp_get_setting( 'header_is_page_title', 1 );

		return (bool) $is_page_title;
	}
endif;


if ( ! function_exists( 'startapp_is_mobile_logo' ) ) :
	/**
	 * Checks if Mobile Logo is used
	 *
	 * User can upload Mobile Logo in Theme Options
	 *
	 * @see startapp_add_header_options()
	 * @see startapp_header_class()
	 *
	 * @return bool
	 */
	function startapp_is_mobile_logo() {
		$mobile = startapp_get_option( 'header_mobile_logo' );

		return ( ! empty( $mobile ) );
	}
endif;

if ( ! function_exists( 'startapp_is_header_fullwidth' ) ) :
	/**
	 * Check if "Fullwidth Header" is enabled in Theme Options
	 *
	 * @see startapp_add_header_options()
	 * @see startapp_header_class()
	 *
	 * @return bool
	 */
	function startapp_is_header_fullwidth() {
		$is = (bool) startapp_get_option( 'header_is_fullwidth', 0 );

		return $is;
	}
endif;

if ( ! function_exists( 'startapp_is_header_sticky' ) ):
	/**
	 * Check if "Sticky Menu" is enabled in Theme Options
	 *
	 * @see startapp_add_header_options()
	 * @see startapp_header_class()
	 *
	 * @return bool
	 */
	function startapp_is_header_sticky() {
		$is = (bool) startapp_get_option( 'header_navbar_is_sticky', 1 );

		return $is;
	}
endif;

if ( ! function_exists( 'startapp_is_entry_author' ) ) :
	/**
	 * Checks if author widget is enabled in Page Settings for Single Post
	 *
	 * @see startapp_add_post_settings_meta_box()
	 * @see inc/options.php
	 *
	 * @return bool
	 */
	function startapp_is_entry_author() {
		$is_author = (bool) startapp_get_setting( 'single_is_post_author', false );

		return $is_author;
	}
endif;

if ( ! function_exists( 'startapp_is_scroll_to_top' ) ) :
	/**
	 * Check if Scroll to Top button is enabled in Theme Options
	 *
	 * @see inc/options.php
	 * @see startapp_scroll_to_top()
	 *
	 * @return bool
	 */
	function startapp_is_scroll_to_top() {
		$is_scroll = (int) startapp_get_option( 'general_is_scroll_to_top', 1 );

		return (bool) $is_scroll;
	}
endif;

if ( ! function_exists( 'startapp_is_entry_shares' ) ) :
	/**
	 * Check is Sharing Buttons is enabled in Page Settings
	 *
	 * @see inc/options.php
	 * @see startapp_add_page_settings_meta_box()
	 * @see startapp_entry_shares()
	 *
	 * @return bool
	 */
	function startapp_is_entry_shares() {
		$is_shares = (bool) startapp_get_setting( 'single_is_shares', true );

		return $is_shares;
	}
endif;

if ( ! function_exists( 'startapp_is_entry_related' ) ) :
	/**
	 * Checks if the Related Posts widget is enabled for current post
	 *
	 * This template tag should be used within the Loop
	 *
	 * @see startapp_add_related_posts_meta_box()
	 *
	 * @return bool
	 */
	function startapp_is_entry_related() {
		$is_related = (bool) startapp_get_meta( get_the_ID(), '_startapp_related', 'is_enabled', true );

		return $is_related;
	}
endif;

if ( ! function_exists( 'startapp_is_topbar_pos' ) ) :
	/**
	 * Detect the current position (based on current action) and check if
	 * this position equal the current required position.
	 *
	 * Applicable for Topbar.
	 *
	 * @param string $required Required position from the Theme Options
	 *
	 * @return bool
	 */
	function startapp_is_topbar_pos( $required ) {
		$actions = array(
			'startapp_topbar_left'  => 'left',
			'startapp_topbar_right' => 'right',
		);

		$action   = current_action();
		$position = array_key_exists( $action, $actions ) ? $actions[ $action ] : 'left';

		return ( $position === $required );
	}
endif;

if ( ! function_exists( 'startapp_is_google_fonts' ) ) :
	/**
	 * Check if Google Fonts is enabled
	 *
	 * Based on Theme Options
	 *
	 * @see startapp_add_typography_options()
	 * @see startapp_scripts()
	 *
	 * @return bool
	 */
	function startapp_is_google_fonts() {
		$is_fonts = (int) startapp_get_option( 'typography_is_google_fonts', 1 );

		return (bool) $is_fonts;
	}
endif;

if ( ! function_exists( 'startapp_is_footer_parallax' ) ) :
	/**
	 * Check if parallax in Footer is enabled
	 *
	 * Based on Theme Options
	 *
	 * @see startapp_footer_attr()
	 *
	 * @return bool
	 */
	function startapp_is_footer_parallax() {
		$is = (int) startapp_get_option( 'footer_is_parallax', 0 );

		return (bool) $is;
	}
endif;

if ( ! function_exists( 'startapp_the_breadcrumbs' ) ) :
	/**
	 * Display the Breadcrumbs
	 *
	 * @see startapp_page_title()
	 */
	function startapp_the_breadcrumbs() {
		if ( ! function_exists( 'bcn_display' ) || is_search() ) {
			return;
		}

		echo '<div class="breadcrumbs">';
		bcn_display();
		echo '</div>';
	}
endif;

if ( ! function_exists( 'startapp_the_toolbar' ) ) :
	/**
	 * Display the Toolbar
	 *
	 * You can enable/disable tools by passing a flag in $args
	 *
	 * NOTE: Toolbar can be used in the Topbar, Site Info or Navbar sections
	 *
	 * @param array $args Arguments
	 */
	function startapp_the_toolbar( $args = array() ) {
		$args = wp_parse_args( (array) $args, array(
			'is_search'        => false,
			'is_search_mobile' => false,
			'is_cart'          => false,
			'is_cart_mobile'   => false,
			'is_sb_btn'        => false,
			'is_sb_btn_mobile' => false,
			'is_tb_btn'        => false,
			'is_mn_btn'        => false,
		) );

		$tools = array();

		// search
		if ( $args['is_search'] ) {
			$tools[] = '<a href="#" class="site-search-btn tool"><i class="material-icons search"></i></a>';
		}

		// search mobile
		if ( $args['is_search_mobile'] ) {
			$tools[] = '<a href="#" class="site-search-btn tool mobile-view"><i class="material-icons search"></i></a>';
		}

		// cart
		if ( $args['is_cart']
		     && startapp_is_woocommerce()
		     && ( ! is_cart() && ! is_checkout() )
		) {
			ob_start();
			startapp_the_cart();
			$tools[] = ob_get_clean();
		}

		// cart mobile
		if ( $args['is_cart_mobile']
		     && startapp_is_woocommerce()
		     && ( ! is_cart() && ! is_checkout() )
		) {
			ob_start();
			startapp_the_mobile_cart();
			$tools[] = ob_get_clean();
		}

		// off-canvas
		if ( $args['is_sb_btn'] ) {
			$tools[] = '<a href="#" class="sidebar-btn tool"><i class="material-icons more_vert"></i></a>';
		}

		// off-canvas mobile
		if ( $args['is_sb_btn_mobile'] ) {
			$tools[] = '<a href="#" class="sidebar-btn tool mobile-view"><i class="material-icons more_vert"></i></a>';
		}

		// topbar
		$header     = startapp_header_layout();
		$restricted = array( 'horizontal-snj', 'horizontal-snml', 'horizontal-snmr' );
		if ( $args['is_tb_btn'] && ! in_array( $header, $restricted, true ) ) {
			$tools[] = '<a href="#" class="topbar-btn tool"><i class="material-icons more_horiz"></i></a>';
		}
		unset( $header, $restricted );

		// menu
		if ( $args['is_mn_btn'] ) {
			$tools[] = '<a href="#" class="menu-btn tool" data-toggle="offcanvas"><i class="material-icons menu"></i></a>';
		}

		echo '<div class="toolbar">', implode( '', $tools ), '</div>';
	}
endif;

if ( ! function_exists( 'startapp_the_cart' ) ) :
	/**
	 * Display the WooCommerce Cart dropdown for Toolbar
	 *
	 * @see  startapp_the_toolbar()
	 * @uses woocommerce_mini_cart()
	 */
	function startapp_the_cart() {
		?>
		<div class="cart-toggle cart-contents tool">
			<a href="<?php echo esc_url( wc_get_cart_url() ); ?>" class="cart-btn">
				<span class="count"><?php echo esc_html( WC()->cart->get_cart_contents_count() ); ?></span>
				<i class="material-icons shopping_cart"></i>
			</a>
			<div class="cart-dropdown">
				<div class="widget woocommerce widget_shopping_cart">
					<div class="widget_shopping_cart_content">
						<?php woocommerce_mini_cart(); ?>
					</div>
				</div>
			</div>
		</div>
		<?php
	}
endif;

if ( ! function_exists( 'startapp_the_mobile_cart' ) ) :
	/**
	 * Display the WooCommerce Cart mobile icon for Toolbar
	 *
	 * @see startapp_the_toolbar()
	 */
	function startapp_the_mobile_cart() {
		?>
		<div class="cart-toggle cart-contents tool mobile-view">
			<a href="<?php echo esc_url( wc_get_cart_url() ); ?>" class="cart-btn">
				<span class="count"><?php echo esc_html( WC()->cart->get_cart_contents_count() ); ?></span>
				<i class="material-icons shopping_cart"></i>
			</a>
		</div>
		<?php
	}
endif;

if ( ! function_exists( 'startapp_the_offcanvas' ) ) :
	/**
	 * Display the Off-Canvas
	 *
	 * @hooked startapp_header_before 10
	 * @see    header.php
	 */
	function startapp_the_offcanvas() {
		$pos    = startapp_get_option( 'header_offcanvas_pos', 'right' );
		$notice = '';

		/**
		 * Filter the Off-Canvas classes
		 *
		 * You can easily add or remove any class to the off-canvas
		 *
		 * @param array $class A list of off-canvas classes
		 */
		$class = startapp_get_classes( apply_filters( 'startapp_offcanvas_class', array(
			'off-canvas-sidebar',
			$pos . '-positioned',
		) ) );

		if ( ! is_active_sidebar( 'sidebar-offcanvas' ) ) {
			$notice = esc_html__(
				'You currently do not have any widgets inside Off-Canvas sidebar. Add widgets in Appearance > Widgets > Off-Canvas Sidebar.',
				'startapp'
			);
		}

		?>
		<aside class="<?php echo esc_attr( $class ); ?>">
			<span class="close-btn"><i class="material-icons clear"></i></span>
			<?php
			startapp_the_text( $notice, '<p class="padding-top-1x">', '</p>' );
			dynamic_sidebar( 'sidebar-offcanvas' );
			?>
		</aside>
		<?php
	}
endif;

if ( ! function_exists( 'startapp_the_search' ) ) :
	/**
	 * Display the Search Form Popup
	 *
	 * @hooked startapp_header_before 10
	 * @see    header.php
	 */
	function startapp_the_search() {
		/**
		 * Filter the search form classes
		 *
		 * This filter allows you to easily add any custom class to the search form popup
		 *
		 * @param array $class A list of classes
		 */
		$class = startapp_get_classes( apply_filters( 'startapp_search_form_class', array(
			'site-search-form',
		) ) );

		?>
		<div class="<?php echo esc_attr( $class ); ?>">
			<div class="inner">
				<span class="close-btn"><i class="material-icons clear"></i></span>
				<?php get_search_form(); ?>
			</div>
		</div>
		<?php
	}
endif;

if ( ! function_exists( 'startapp_the_scroller' ) ) :
	/**
	 * Display the Scroller Menu
	 *
	 * @hooked startapp_header_before 10
	 * @see    header.php
	 */
	function startapp_the_scroller() {
		if ( ! startapp_is_scroller() || ! has_nav_menu( 'scroller' ) ) {
			return;
		}

		// scroller menu allowed only for pages and front page
		if ( ( is_home() && ! is_front_page() ) || ! is_page() ) {
			return;
		}

		// scroller menu works only for certain page
		// selected in the Theme Options
		if ( get_queried_object_id() !== (int) startapp_get_option( 'header_scroller_page', 0 ) ) {
			return;
		}

		/**
		 * Filter the search form classes
		 *
		 * This filter allows you to easily add any custom class to the search form popup
		 *
		 * @param array $class A list of classes
		 */
		$class = startapp_get_classes( apply_filters( 'startapp_scroller_class', array(
			'scroller',
			'scroller-' . startapp_get_option( 'header_scroller_position', 'right' ),
		) ) );

		?>
		<div class="<?php echo esc_attr( $class ); ?>">
			<?php startapp_scroller_menu(); ?>
		</div>
		<?php
	}
endif;

if ( ! function_exists( 'startapp_the_excerpt' ) ) :
	/**
	 * Remove all HTML tags from the excerpt and returns
	 * the excerpt wrapped in to the p.post-excerpt
	 *
	 * @hooked the_excerpt 20
	 *
	 * @param string $output The excerpt
	 *
	 * @return string
	 */
	function startapp_the_excerpt( $output ) {
		$output = strip_tags( $output );

		return '<p class="post-excerpt">' . $output . '</p>';
	}
endif;

if ( ! function_exists( 'startapp_the_logo' ) ) :
	/**
	 * Display the logo
	 *
	 * @hooked startapp_site_info_left -1
	 *
	 * @uses   the_custom_logo()
	 */
	function startapp_the_logo() {

		// Custom logo. From the Page Settings
		// @see startapp_add_page_settings_meta_box()
		$custom_logo = absint( startapp_get_setting( 'custom_logo', 0 ) );
		if ( ! empty( $custom_logo ) ) {
			printf( '<a href="%1$s" class="site-logo" rel="home" itemprop="url">%2$s</a>',
				esc_url( home_url( '/' ) ),
				wp_get_attachment_image( $custom_logo, 'full', false, array(
					'class'    => 'custom-logo',
					'itemprop' => 'logo',
				) )
			);

			return;
		}

		// Site Logo. From the customizer.
		if ( function_exists( 'the_custom_logo' ) && has_custom_logo() ) {
			the_custom_logo();

			return;
		}

		// Default logo. From the Theme Options
		// @see startapp_add_header_options()
		$default_logo = absint( startapp_get_setting( 'header_logo', 0 ) );
		if ( ! empty( $default_logo ) ) {
			printf( '<a href="%1$s" class="site-logo" rel="home" itemprop="url">%2$s</a>',
				esc_url( home_url( '/' ) ),
				wp_get_attachment_image( $default_logo, 'full', false, array(
					'class'    => 'custom-logo',
					'itemprop' => 'logo',
				) )
			);

			return;
		}

		/**
		 * Filter the URI to logo fallback
		 *
		 * This logo will be loaded if user does not specify the logo
		 * neither through customizer (WP 4.5+), nor in Theme Options
		 *
		 * This filter may be useful if you want to change the default fallback logo
		 *
		 * @param string $uri Logo URI
		 */
		$logo_src = apply_filters( 'startapp_logo_fallback', STARTAPP_TEMPLATE_URI . '/img/logo.png' );

		/**
		 * Filter the fallback logo attributes
		 *
		 * This filter allows you to add, remove or change attributes
		 * for <img> tag, containing the logo
		 *
		 * @param array $attributes Fallback logo attributes
		 */
		$logo = startapp_get_tag( 'img', apply_filters( 'startapp_logo_fallback_atts', array(
			'src'      => esc_url( $logo_src ),
			'alt'      => esc_attr( get_bloginfo( 'name', 'display' ) ),
			'class'    => 'custom-logo',
			'itemprop' => 'logo',
		) ) );

		printf( '<a href="%1$s" class="site-logo" rel="home" itemprop="url">%2$s</a>',
			esc_url( home_url( '/' ) ),
			$logo
		);
	}
endif;

if ( ! function_exists( 'startapp_the_language_switcher' ) ) :
	/**
	 * Display the language switcher
	 *
	 * @see startapp_topbar_lang_switcher()
	 */
	function startapp_the_language_switcher() {
		/**
		 * This filter allows to change a type of Language Switcher
		 *
		 * For example, you can set the type always been "default" or use your own.
		 * If you want to customize the language switcher further with your own callback.
		 *
		 * @param string $type Language switcher type
		 */
		$type = apply_filters( 'startapp_language_switcher_type', 'default' );

		/*
		<div class="lang-switcher">
			<span>
				<i class="material-icons language"></i> English
			</span>
			<ul class="lang-dropdown">
				<li><a href="#">French</a></li>
				<li><a href="#">German</a></li>
				<li><a href="#">Italian</a></li>
			</ul>
		</div>
		*/

		/**
		 * This action allows you to output your own markup for Language Switcher.
		 *
		 * For example, you can use this action to add a multisite-based translations.
		 * See the styled markup above. You can use it without worrying about
		 * breaking the layout.
		 *
		 * Also, I use this action to output Polylang and WPML Language Switchers
		 *
		 * The dynamic part refers to language switcher type.
		 * See filter "startapp_language_switcher_type"
		 */
		do_action( "startapp_language_switcher_{$type}" );

		/**
		 * The same as above, but type passed as parameter
		 *
		 * @param string $type Language switcher type
		 */
		do_action( 'startapp_language_switcher', $type );
	}
endif;

if ( ! function_exists( 'startapp_mobile_logo' ) ):
	/**
	 * Prints the Mobile Logo
	 *
	 * Always use this function with {@see startapp_the_logo()}
	 *
	 * @hooked startapp_site_info_left -1
	 */
	function startapp_mobile_logo() {
		if ( ! startapp_is_mobile_logo() ) {
			return;
		}

		$logo = startapp_get_option( 'header_mobile_logo' );

		printf( '<a href="%1$s" class="mobile-logo">%2$s</a>',
			esc_url( home_url( '/' ) ),
			wp_get_attachment_image( (int) $logo )
		);
	}
endif;

if ( ! function_exists( 'startapp_header_layout' ) ):
	/**
	 * Returns the template part name for header
	 *
	 * Based on Page Settings and Theme Options
	 *
	 * @see startapp_add_page_settings_meta_box()
	 * @see startapp_add_header_options()
	 * @see header.php
	 * @see template-parts/headers/header-*.php
	 *
	 * @return string
	 */
	function startapp_header_layout() {
		$layout = startapp_get_setting( 'header_layout', 'horizontal-n' );

		/**
		 * Filter the layout type
		 *
		 * NOTE: this is a part of the file name, so if you want to add a custom
		 * layout in the child theme you have to follow the file name convention.
		 * Your file should be named header-{$layout}.php
		 *
		 * You can add your custom template part to
		 * /theme-child/template-parts/headers/header-{$layout}.php
		 *
		 * @param string $layout Layout
		 */
		return esc_attr( apply_filters( 'startapp_header_layout', $layout ) );
	}
endif;

if ( ! function_exists( 'startapp_header_class' ) ) :
	/**
	 * Echoes the Site Header class
	 *
	 * @param string $class Custom classes, e.g. "my custom class"
	 *
	 * @see template-parts/headers/header-{$layout}.php
	 */
	function startapp_header_class( $class = '' ) {
		$classes   = array();
		$classes[] = 'site-header';

		if ( startapp_is_mobile_logo() ) {
			$classes[] = 'use-mobile-logo';
		}

		if ( startapp_is_header_fullwidth() ) {
			$classes[] = 'header-fullwidth';
		}

		if ( startapp_is_header_sticky() ) {
			$classes[] = 'navbar-sticky';
		}

		if ( ! empty( $class ) ) {
			$classes[] = $class;
		}

		/**
		 * Filter the Header classes
		 *
		 * This filter allows you to easily add (or remove)
		 * custom classes to the .site-header
		 *
		 * @param array $classes A list of classes
		 */
		$classes = apply_filters( 'startapp_header_class', $classes );

		echo esc_attr( startapp_get_classes( $classes ) );
	}
endif;

if ( ! function_exists( 'startapp_header_buttons' ) ) :
	/**
	 * Display the Button Widgets inside Header
	 *
	 * @uses Startapp_Widget_Button
	 */
	function startapp_header_buttons() {
		if ( ! is_active_sidebar( 'sidebar-header-buttons' ) ) {
			return;
		}

		echo '<div class="header-buttons">';
		dynamic_sidebar( 'sidebar-header-buttons' );
		echo '</div>';
	}
endif;

if ( ! function_exists( 'startapp_topbar_class' ) ) :
	/**
	 * Echoes the topbar classes
	 *
	 * @see template-tags/headers/
	 */
	function startapp_topbar_class() {
		$classes = array();

		$classes[] = 'topbar';
		$classes[] = 'topbar-' . startapp_get_option( 'header_topbar_bg', 'primary' );
		$classes[] = 'text-' . startapp_get_option( 'header_topbar_content_skin', 'light' );

		/**
		 * Filter the topbar class
		 *
		 * @param array $classes A list of topbar classes
		 */
		$classes = apply_filters( 'startapp_topbar_class', $classes );

		echo esc_attr( startapp_get_classes( $classes ) );
	}
endif;

if ( ! function_exists( 'startapp_topbar_attr' ) ) :
	/**
	 * Echoes the topbar attributes
	 *
	 * @see template-tags/headers/
	 */
	function startapp_topbar_attr() {
		$attr = array();

		if ( 'custom' === startapp_get_option( 'header_topbar_bg' ) ) {
			$color         = startapp_get_option( 'header_topbar_custom_color', '#4eabff' );
			$attr['style'] = startapp_css_background_color( sanitize_hex_color( $color ) );
			unset( $color );
		}

		/**
		 * Filter the topbar element attributes
		 *
		 * @param array $attr Attributes
		 */
		$attr = apply_filters( 'startapp_topbar_attr', $attr );

		// remove class, use "startapp_topbar_class" instead
		$attr = array_diff_key( $attr, array( 'class' => '' ) );

		echo startapp_get_attr( $attr );
	}
endif;

if ( ! function_exists( 'startapp_topbar_lang_switcher' ) ) :
	/**
	 * Displays the Language Switcher
	 *
	 * Language Switcher supports: WPML, Polylang and custom
	 * action in case if you use a multi-site network.
	 *
	 * NOTE: user can disable language switcher in Theme Options
	 * NOTE: you can choose position in Theme Options
	 *
	 * @hooked startapp_topbar_left 5
	 * @hooked startapp_topbar_right 5
	 *
	 * @see    startapp_add_header_options()
	 * @see    template-tags/headers/
	 */
	function startapp_topbar_lang_switcher() {
		if ( false === (bool) startapp_get_option( 'header_topbar_is_lang', 0 ) ) {
			return;
		}

		$position = startapp_get_option( 'header_topbar_lang_position', 'left' );
		if ( ! startapp_is_topbar_pos( $position ) ) {
			return;
		}

		startapp_the_language_switcher();
	}
endif;

if ( ! function_exists( 'startapp_topbar_add_info' ) ) :
	/**
	 * Display the Additional Info in the Topbar
	 *
	 * NOTE: you can choose a position in Theme Options
	 *
	 * @hooked startapp_topbar_left 5
	 * @hooked startapp_topbar_right 5
	 *
	 * @see    startapp_add_header_options()
	 * @see    template-tags/headers/
	 */
	function startapp_topbar_add_info() {
		$position = startapp_get_option( 'header_topbar_info_position', 'left' );
		if ( ! startapp_is_topbar_pos( $position ) ) {
			return;
		}

		$info = startapp_get_option( 'header_topbar_info' );
		$info = startapp_sanitize_text( $info );

		echo startapp_get_text( $info, '<p class="additional-info">', '</p>' );
	}
endif;

if ( ! function_exists( 'startapp_topbar_menu' ) ) :
	/**
	 * Show the topbar navigation
	 *
	 * NOTE: you can choose a position in Theme Options
	 *
	 * @hooked startapp_topbar_left 5
	 * @hooked startapp_topbar_right 5
	 *
	 * @see    startapp_add_header_options()
	 * @see    template-tags/headers/
	 */
	function startapp_topbar_menu() {
		if ( ! has_nav_menu( 'topbar' ) ) {
			return;
		}

		if ( false === (bool) startapp_get_option( 'header_topbar_is_menu', 1 ) ) {
			return;
		}

		$position = startapp_get_option( 'header_topbar_menu_position', 'right' );
		if ( ! startapp_is_topbar_pos( $position ) ) {
			return;
		}

		/**
		 * Filter the menu arguments
		 *
		 * @see https://developer.wordpress.org/reference/functions/wp_nav_menu/
		 *
		 * @param array $args Arguments
		 */
		$args = apply_filters( 'startapp_topbar_menu_args', array(
			'theme_location'  => 'topbar',
			'container'       => 'nav',
			'container_class' => 'topbar-menu',
			'container_id'    => false,
			'fallback_cb'     => false,
			'depth'           => 2,
			'walker'          => new Startapp_Walker_Nav_Menu_Extra(),
		) );

		wp_nav_menu( $args );
	}
endif;

if ( ! function_exists( 'startapp_topbar_socials' ) ) :
	/**
	 * Display the Socials Networks in the Topbar
	 *
	 * NOTE: user can disable Socials in Theme Options
	 * NOTE: user can choose a position in Theme Options
	 *
	 * @hooked startapp_topbar_left 5
	 * @hooked startapp_topbar_right 5
	 *
	 * @see    startapp_add_header_options()
	 * @see    template-tags/headers/
	 */
	function startapp_topbar_socials() {
		if ( false === (bool) startapp_get_option( 'header_topbar_is_socials', 0 ) ) {
			return;
		}

		$position = startapp_get_option( 'header_topbar_socials_position', 'right' );
		if ( ! startapp_is_topbar_pos( $position ) ) {
			return;
		}

		$socials_raw = startapp_get_option( 'header_topbar_socials' );
		if ( empty( $socials_raw ) ) {
			return;
		}

		$socials = array();
		foreach ( (array) $socials_raw as $network => $url ) {
			$socials[] = array( 'network' => $network, 'url' => $url );
		}
		unset( $network, $url );

		$socials   = urlencode( json_encode( $socials ) );
		$shortcode = startapp_shortcode_build( 'startapp_socials', array(
			'socials' => $socials,
			'type'    => 'solid-bg',
		) );

		echo startapp_do_shortcode( $shortcode );
	}
endif;

if ( ! function_exists( 'startapp_topbar_toolbar' ) ) :
	/**
	 * Display the Toolbar inside the Topbar
	 *
	 * NOTE: you can enable/disable tools in Theme Options
	 *
	 * @hooked startapp_topbar_right 100
	 *
	 * @see    startapp_add_header_options()
	 * @see    template-parts/headers/
	 *
	 * @uses   startapp_the_toolbar()
	 */
	function startapp_topbar_toolbar() {
		startapp_the_toolbar( array(
			'is_search' => (bool) startapp_get_option( 'header_topbar_tools_is_search', 0 ),
			'is_cart'   => (bool) startapp_get_option( 'header_topbar_tools_is_cart', 0 ),
			'is_sb_btn' => (bool) startapp_get_option( 'header_topbar_tools_is_sidebar', 0 ),
		) );
	}
endif;

if ( ! function_exists( 'startapp_site_info_contacts' ) ) :
	/**
	 * Display the Contact Info in Site Info section
	 *
	 * @hooked startapp_site_info_right 10
	 *
	 * @uses Startapp_Widget_Contacts
	 */
	function startapp_site_info_contacts() {
		$contacts = wp_parse_args( startapp_get_options_slice( 'header_contacts_' ), array(
			'info'    => '',
			'address' => '',
			'time'    => '',
		) );

		if ( count( array_filter( $contacts ) ) === 0 ) {
			return;
		}

		$result = json_encode( array(
			array(
				'type' => 'phone',
				'data' => strip_tags( stripslashes( $contacts['info'] ) ),
			),
			array(
				'type' => 'address',
				'data' => strip_tags( stripslashes( $contacts['address'] ) ),
			),
			array(
				'type' => 'time',
				'data' => strip_tags( stripslashes( $contacts['time'] ) ),
			),
		) );

		the_widget( 'Startapp_Widget_Contacts', array( 'contacts' => $result ) );
	}
endif;

if ( ! function_exists( 'startapp_site_info_toolbar' ) ) :
	/**
	 * Display the Toolbar in Site Info section
	 *
	 * NOTE: you can enable/disable tools in Theme Options
	 *
	 * @hooked startapp_site_info_right 100
	 *
	 * @see    startapp_add_header_options()
	 * @see    template-parts/headers/
	 *
	 * @uses   startapp_the_toolbar()
	 */
	function startapp_site_info_toolbar() {
		startapp_the_toolbar( array(
			'is_search'        => (bool) startapp_get_option( 'header_site_info_tools_is_search', 0 ),
			'is_search_mobile' => (bool) startapp_get_option( 'header_navbar_tools_is_search', 0 ),
			'is_cart'          => (bool) startapp_get_option( 'header_site_info_tools_is_cart', 0 ),
			'is_cart_mobile'   => (bool) startapp_get_option( 'header_navbar_tools_is_cart', 0 ),
			'is_sb_btn'        => (bool) startapp_get_option( 'header_site_info_tools_is_sidebar', 0 ),
			'is_sb_btn_mobile' => (bool) startapp_get_option( 'header_navbar_tools_is_sidebar', 0 ),
			'is_tb_btn'        => true,
			'is_mn_btn'        => true,
		) );
	}
endif;

if ( ! function_exists( 'startapp_navbar_toolbar' ) ) :
	/**
	 * Display the Toolbar in Navbar section
	 *
	 * NOTE: you can enable/disable tools in Theme Options
	 *
	 * @hooked startapp_navbar_tools 100
	 *
	 * @see    startapp_add_header_options()
	 * @see    template-parts/headers/
	 *
	 * @uses   startapp_the_toolbar()
	 */
	function startapp_navbar_toolbar() {
		startapp_the_toolbar( array(
			'is_search' => (bool) startapp_get_option( 'header_navbar_tools_is_search', 1 ),
			'is_cart'   => (bool) startapp_get_option( 'header_navbar_tools_is_cart', 1 ),
			'is_sb_btn' => (bool) startapp_get_option( 'header_navbar_tools_is_sidebar', 0 ),
			'is_tb_btn' => true,
			'is_mn_btn' => true,
		) );
	}
endif;

if ( ! function_exists( 'startapp_navbar_socials' ) ) :
	/**
	 * Display the Socials Networks in the Navbar
	 *
	 * @see startapp_add_header_options()
	 * @see template-tags/headers/
	 */
	function startapp_navbar_socials() {
		$socials_bar = startapp_get_option( 'header_navbar_socials' );
		if ( empty( $socials_bar ) ) {
			return;
		}

		$socials = array();
		foreach ( (array) $socials_bar as $network => $url ) {
			$socials[] = array( 'network' => $network, 'url' => $url );
		}
		unset( $network, $url );

		$socials   = urlencode( json_encode( $socials ) );
		$shortcode = startapp_shortcode_build( 'startapp_socials', array(
			'socials' => $socials,
			'type'    => 'border',
		) );

		echo startapp_do_shortcode( $shortcode );
	}
endif;

if ( ! function_exists( 'startapp_blog_layout' ) ) :
	/**
	 * Returns the template part for blog
	 *
	 * Based on Theme Options
	 *
	 * @see index.php
	 * @see inc/options.php
	 * @see template-parts/blog/blog-*.php
	 *
	 * @return string
	 */
	function startapp_blog_layout() {
		$layout = startapp_get_option( 'blog_layout', 'list-right' );

		/**
		 * Filter the layout type for a Blog
		 *
		 * NOTE: this is a part of the file name, so if you want to add a custom
		 * layout in the child theme you have to follow the file name convention.
		 * Your file should be named blog-{$layout}.php
		 *
		 * You can add your custom template part to
		 * /theme-child/template-parts/blog/blog-{$layout}.php
		 *
		 * @param string $layout Layout
		 */
		return esc_attr( apply_filters( 'startapp_blog_layout', $layout ) );
	}
endif;

if ( ! function_exists( 'startapp_blog_open_wrapper' ) ) :
	/**
	 * Wrap the blog to div.container. Open tag.
	 *
	 * @hooked startapp_blog_before 5
	 * @see    startapp_blog_close_wrapper()
	 * @see    index.php
	 */
	function startapp_blog_open_wrapper() {
		echo '<div class="container padding-top-3x padding-bottom-3x">';
	}
endif;

if ( ! function_exists( 'startapp_blog_close_wrapper' ) ) :
	/**
	 * Wrap the blog to div.container. Close tag.
	 *
	 * @hooked startapp_blog_after 5
	 * @see    startapp_blog_open_wrapper()
	 * @see    index.php
	 */
	function startapp_blog_close_wrapper() {
		echo '</div>';
	}
endif;

if ( ! function_exists( 'startapp_blog_pagination' ) ) :
	/**
	 * Prints the markup for blog posts pagination
	 *
	 * Depends on Theme Options
	 *
	 * @hooked startapp_loop_after 10
	 * @see    startapp_add_blog_options()
	 * @see    template-parts/blog/*
	 */
	function startapp_blog_pagination() {
		global $wp_query;

		$max_pages = isset( $wp_query->max_num_pages ) ? $wp_query->max_num_pages : 1;
		if ( $max_pages < 2 ) {
			return;
		}

		$paged    = get_query_var( 'paged' ) ? (int) get_query_var( 'paged' ) : 1;
		$total    = (int) wp_count_posts()->publish;
		$per_page = (int) get_option( 'posts_per_page' );

		$nav = '';

		// Archive supports only paginate links
		if ( is_archive() ) {
			$type = 'links';
		} else {
			$type = startapp_get_option( 'blog_pagination_type', 'links' );
		}

		switch ( $type ) {
			case 'load-more':
				// Detect how many posts left to load to show on the button.
				// This number should tells user how many posts will be loaded
				// when he click on the button.
				$number = $total - ( $paged * $per_page );

				// If number of posts greater, than per_page option - show per_page value
				// This is required for situation when number of posts less, that per_page options
				$number = ( $number > $per_page ) ? $per_page : $number;
				$button = esc_html__( 'Load More ... %s', 'startapp' ); // translators: %s is a counter

				$nav .= startapp_get_tag( 'a', array(
					'href'         => '#',
					'class'        => 'btn btn-transparent btn-primary load-more-posts',
					'data-page'    => $paged + 1,
					'data-type'    => startapp_blog_layout(),
					'data-total'   => $total,
					'data-perpage' => $per_page,
					'rel'          => 'nofollow',
				), sprintf( $button, '<span class="load-more-counter">' . $number . '</span>' ) );

				break;

			case 'infinite-scroll':
				$nav .= startapp_get_tag( 'a', array(
					'href'           => '#',
					'class'          => 'infinite-scroll',
					'data-page'      => $paged + 1,
					'data-type'      => startapp_blog_layout(),
					'data-max-pages' => $max_pages,
					'rel'            => 'nofollow',
				), '' );

				break;

			case 'links':
			default:
				/**
				 * Filter the arguments passed to {@see paginate_links}
				 *
				 * @param array $args Arguments for {@see paginate_links}
				 */
				$links = paginate_links( apply_filters( 'startapp_blog_pagination_args', array(
					'type'      => 'plain',
					'mid_size'  => 2,
					'prev_next' => true,
					'prev_text' => '<i class="material-icons keyboard_backspace"></i>',
					'next_text' => '<i class="material-icons keyboard_backspace"></i>',
				) ) );

				$nav .= '<div class="nav-links">' . $links . '</div>';
				unset( $links );

				break;
		}

		$classes = array();

		$classes[] = 'pagination';
		$classes[] = ( $type === 'infinite-scroll' ) ? 'pagination-infinite' : '';
		$classes[] = 'margin-bottom-1x';
		$classes[] = 'text-' . startapp_get_option( 'blog_pagination_alignment', 'left' );

		/**
		 * Filter the classes for posts pagination.
		 *
		 * @param array $classes A list of extra classes
		 */
		$classes = apply_filters( 'startapp_blog_pagination_class', $classes );
		$classes = esc_attr( startapp_get_classes( $classes ) );

		echo "
		<section class=\"{$classes}\">
			<div class=\"loader\">
				<span class=\"child-1\"></span>
				<span class=\"child-2\"></span>
				<span class=\"child-3\"></span>
			</div>
			{$nav}
		</section>";
	}
endif;

if ( ! function_exists( 'startapp_entry_sticky' ) ) :
	/**
	 * Display the .sticky-label in the blog post tile
	 *
	 * @hooked startapp_post_body_before 10
	 * @see    template-tags/post-tile.php
	 */
	function startapp_entry_sticky() {
		if ( ! is_sticky() ) {
			return;
		}

		?>
		<div class="sticky-label">
			<i class="material-icons flash_on"></i>
			<?php esc_html_e( 'Sticky Post', 'startapp' ); ?>
		</div>
		<?php
	}
endif;

if ( ! function_exists( 'startapp_entry_thumbnail' ) ) :
	/**
	 * Display the Featured Images in the blog post tile
	 *
	 * NOTE: user can put the post thumbnail before or after the post body.
	 * User can set the position in Page Settings for each post individually,
	 * or in Theme Options globally.
	 *
	 * @hooked startapp_post_body_before 20
	 * @hooked startapp_post_body_after 20
	 */
	function startapp_entry_thumbnail() {
		if ( ! has_post_thumbnail() ) {
			return;
		}

		$actions = array(
			'startapp_post_body_before' => 'top',
			'startapp_post_body_after'  => 'bottom',
		);

		$current_action   = current_action();
		$current_position = array_key_exists( $current_action, $actions ) ? $actions[ $current_action ] : 'top';

		$position = startapp_get_setting( 'single_thumbnail_position', 'bottom', get_the_ID() );
		if ( $current_position !== $position ) {
			return;
		}

		?>
		<a href="<?php the_permalink(); ?>" class="post-thumb">
			<?php the_post_thumbnail( 'full' ); ?>
		</a>
		<?php
	}
endif;

if ( ! function_exists( 'startapp_entry_header' ) ) :
	/**
	 * Prints HTML with meta information for date, author, categories and comments.
	 */
	function startapp_entry_header() {
		$post_id   = get_the_ID();
		$cat_terms = get_the_terms( $post_id, 'category' );
		?>
		<header class="post-header">
			<div class="column">
				<?php

				// date (linked to a post)
				printf( '<a href="%1$s" class="post-date">%2$s</a>',
					esc_url( get_permalink() ),
					esc_html( get_the_date() )
				);

				// author
				if ( true === (bool) startapp_get_setting( 'single_is_tile_author', 1 ) ) {
					if ( function_exists( 'coauthors_posts_links' ) ) {
						coauthors_posts_links( ', ', ', ', esc_html_x( 'by ', 'post author', 'startapp' ) );
					} else {
						$by = sprintf( '<a href="%1$s">%2$s</a>',
							esc_url( get_author_posts_url( get_the_author_meta( 'ID' ) ) ),
							esc_html( get_the_author() )
						);
						echo sprintf( esc_html_x( 'by %s', 'post author', 'startapp' ), $by );
						unset( $by );
					}
				}

				// if post has categories
				if ( ! empty( $cat_terms ) && ! is_wp_error( $cat_terms ) ) {
					// get categories list separated by comma
					$categories_list = [];
					foreach ( $cat_terms as $cat_term ) {
						$categories_list[] = "<a href='";
						$categories_list[] = esc_url( get_category_link( $cat_term->term_id ) );
						$categories_list[] = "' rel='category tag'>";
						$categories_list[] = esc_html( $cat_term->name );
						$categories_list[] = "</a>, ";
					}

					$categories_list = trim( implode( '', $categories_list ), ' ,' );
					echo '&nbsp;', sprintf( esc_html_x( 'in %s', 'post categories', 'startapp' ), $categories_list );
				}
				unset( $cat_terms );

				?>
			</div>
			<div class="column">
				<?php

				// post comments link. disabled for single posts
				if ( ! is_single()
				     && ! post_password_required()
				     && ( comments_open() || get_comments_number() )
				) {
					comments_popup_link( 0, 1, '%', 'post-comments' );
				}

				// edit post link
				edit_post_link( '<i class="material-icons edit"></i>', '', '', 0, 'edit-link' );

				?>
			</div>
		</header>
		<?php
	}
endif;

if ( ! function_exists( 'startapp_entry_footer' ) ) :
	/**
	 * Prints HTML with meta information for the tags and post format.
	 */
	function startapp_entry_footer() {
		// post format
		$format = get_post_format() ?: '';
		if ( $format ) {
			$format = '<div class="post-format"><i></i>' . get_post_format_string( $format ) . '</div>';
		}

		// tags
		$tags = get_the_tag_list();
		if ( $tags ) {
			$tags = '<div class="tags-links">' . $tags . '</div>';
		}

		echo '<footer class="post-footer">', $format, $tags, '</footer>';
	}
endif;

if ( ! function_exists( 'startapp_entry_shares' ) ) :
	/**
	 * Display the Sharing Buttons in Single Post
	 *
	 * @uses startapp_is_entry_shares()
	 */
	function startapp_entry_shares() {
		if ( ! startapp_is_entry_shares() ) {
			return;
		}

		// collect data about the post
		$data = startapp_get_attr( array(
			'data-text'  => esc_html( get_the_title() ),
			'data-url'   => esc_url( get_the_permalink() ),
			'data-thumb' => has_post_thumbnail() ? startapp_get_image_src( get_post_thumbnail_id() ) : '',
		) );

		/**
		 * Filter the set of Share Buttons
		 *
		 * This filters allows you to add your own networks or remove
		 * existing ones. Your callback should return the array with
		 * keys of the network and text on the button.
		 *
		 * Also note we use the Socicon to display Social Icons,
		 * so you can see the valid keys here {@link http://www.socicon.com/chart.php}
		 *
		 * @param array $shares A list of share buttons
		 */
		$shares = apply_filters( 'startapp_entry_shares', array(
			'twitter'    => esc_html_x( 'Share on Twitter', 'share button', 'startapp' ),
			'facebook'   => esc_html_x( 'Share on Facebook', 'share button', 'startapp' ),
			'googleplus' => esc_html_x( 'Share on Google+', 'share button', 'startapp' ),
			'pinterest'  => esc_html_x( 'Share on Pinterest', 'share button', 'startapp' ),
		) );

		$output = '';
		foreach ( $shares as $network => $label ) {
			$output .= '<div class="column">';
			$output .= sprintf( '<a href="#" class="startapp-share-%1$s" %3$s><i class="socicon-%1$s"></i> %2$s</a>',
				esc_attr( $network ), $label, $data
			);
			$output .= '</div>';
		}

		/**
		 * Filter the outputted HTML for share buttons
		 *
		 * @param string $output HTML output
		 */
		$output = apply_filters( 'startapp_entry_shares_output', $output );

		echo '<div class="post-share-buttons">', $output, '</div>';
	}
endif;

if ( ! function_exists( 'startapp_entry_author' ) ) :
	/**
	 * Display the Author widget in Single Post
	 *
	 * This widget can be disabled in Page Settings
	 *
	 * This template tag should be used within the Loop
	 */
	function startapp_entry_author() {
		if ( ! startapp_is_entry_author() ) {
			return;
		}

		$author_id  = get_the_author_meta( 'ID' );
		$author_url = get_author_posts_url( get_the_author_meta( 'ID' ) );

		// @see startapp_user_additions()
		$meta = wp_parse_args( get_user_meta( $author_id, 'startapp_additions', true ), array(
			'avatar'  => 0,
			'socials' => '',
		) );

		$sh = '';
		if ( ! empty( $meta['socials'] ) ) {
			// convert socials to a shortcode format
			$converted = array();
			foreach ( (array) $meta['socials'] as $network => $url ) {
				$converted[] = array( 'network' => $network, 'url' => $url );
			}
			unset( $network, $url );

			$sh = startapp_shortcode_build( 'startapp_socials', array(
				'socials'   => urlencode( json_encode( $converted ) ),
				'type'      => 'border',
				'shape'     => 'rounded',
				'alignment' => 'inline',
			) );
		}

		?>
		<section class="post-author">

			<?php if ( ! empty( $meta['avatar'] ) ) : ?>
				<div class="post-author-thumb">
					<a href="<?php echo esc_url( $author_url ); ?>">
						<?php echo wp_get_attachment_image( (int) $meta['avatar'], 'full' ); ?>
					</a>
				</div>
			<?php endif; ?>

			<div class="post-author-info">
				<h3 class="post-author-name">
					<a href="<?php echo esc_url( $author_url ); ?>">
						<?php echo esc_html( get_the_author() ); ?>
					</a>
				</h3>
				<?php

				// Author description
				echo startapp_get_text(
					nl2br( strip_tags( get_the_author_meta( 'description' ) ) ),
					'<p>', '</p>'
				);

				// Author socials
				echo startapp_do_shortcode( $sh );
				?>

			</div>
		</section>
		<?php
	}
endif;

if ( ! function_exists( 'startapp_entry_related' ) ) :
	/**
	 * Display the Related Posts widget in Single Post
	 *
	 * This template tag should be used within the Loop
	 *
	 * @see startapp_add_related_posts_meta_box()
	 */
	function startapp_entry_related() {
		if ( ! startapp_is_entry_related() ) {
			return;
		}

		$related = startapp_get_meta( get_the_ID(), '_startapp_related', 'posts', array() );
		if ( empty( $related ) ) {
			return;
		}

		$related = array_filter( $related, 'is_numeric' );
		$related = array_map( 'intval', $related );

		$query = new WP_Query( array(
			'post__in'            => $related,
			'posts_per_page'      => - 1,
			'suppress_filters'    => true,
			'ignore_sticky_posts' => true,
			'no_found_rows'       => true,
		) );

		if ( $query->have_posts() ) : ?>
			<section class="related-posts">
				<h4 class="text-gray margin-bottom-2x"><?php esc_html_e( 'Related Posts', 'startapp' ); ?></h4>

				<?php while ( $query->have_posts() ) : $query->the_post(); ?>
					<div class="related-post-entry">
						<?php
						startapp_entry_header();
						the_title(
							sprintf( '<h3 class="post-title"><a href="%s">', esc_url( get_permalink() ) ),
							'</a></h3>'
						);
						?>
					</div>
				<?php endwhile; ?>

			</section>
		<?php endif;

		wp_reset_postdata();
	}
endif;

if ( ! function_exists( 'startapp_entry_comments_icon' ) ) :
	/**
	 * Append the icon to comments counter
	 *
	 * @param string $output Output
	 * @param int $number The number of post comments.
	 *
	 * @hooked comments_number 10
	 *
	 * @return string
	 */
	function startapp_entry_comments_icon( $output, $number ) {
		return $output . '&nbsp;' . '<i class="material-icons chat_bubble"></i>';
	}
endif;

if ( ! function_exists( 'startapp_single_cover' ) ) :
	/**
	 * Display the Cover Image in Single Post
	 *
	 * @hooked startapp_single_before 10
	 * @see    single.php
	 */
	function startapp_single_cover() {
		$cover = startapp_get_setting( 'cover', 0 );
		if ( empty( $cover ) ) {
			return;
		}

		$is_parallax    = (bool) startapp_get_setting( 'cover_parallax', 1 );
		$parallax_type  = startapp_get_setting( 'cover_parallax_type', 'scroll' );
		$parallax_speed = startapp_get_setting( 'cover_parallax_speed', 0.4 );

		$style                     = array();
		$style['background-image'] = sprintf( 'url(%s)', esc_url( startapp_get_image_src( (int) $cover ) ) );
		$style['height']           = (int) startapp_get_setting( 'cover_height', 500 ) . 'px';

		/**
		 * This filter allows to easily add custom inline styles
		 * for Cover Image in Single Post
		 *
		 * @param array $style A list of declarations for style attribute
		 */
		$style = startapp_css_declarations( apply_filters( 'startapp_single_cover_style', $style ) );

		/**
		 * This filter allows to easily change the attributes for
		 * Cover Image in Single Post
		 *
		 * @param array $attr HTML attributes for Cover Image
		 */
		$attr = startapp_get_attr( apply_filters( 'startapp_single_cover_attr', array(
			'class'               => 'single-cover-image' . ( $is_parallax ? ' bg-parallax' : '' ),
			'style'               => $style,
			'data-parallax-type'  => esc_attr( $parallax_type ),
			'data-parallax-speed' => startapp_sanitize_float( $parallax_speed ),
		) ) );

		echo '<section ', $attr, '></section>';
	}
endif;

if ( ! function_exists( 'startapp_single_layout' ) ) :
	/**
	 * Returns the template part for Single Post
	 *
	 * Based on Page Settings to control the layout per each post
	 * and Theme Options to control the layout globally.
	 *
	 * @see single.php
	 * @see inc/meta-boxes.php
	 * @see inc/options.php
	 * @see template-parts/single/single-*.php
	 *
	 * @return string
	 */
	function startapp_single_layout() {
		$layout = startapp_get_setting( 'single_layout', 'right' );

		/**
		 * Filter the layout type for Single Post
		 *
		 * NOTE: this is a part of the file name, so if you want to add a custom
		 * layout in the Child Theme you have to follow the file name convention.
		 * Your file should be named single-{$layout}.php
		 *
		 * You can add your custom template part to
		 * /theme-child/template-parts/single/single-{$layout}.php
		 *
		 * @param string $layout Layout
		 */
		return esc_attr( apply_filters( 'startapp_single_layout', $layout ) );
	}
endif;

if ( ! function_exists( 'startapp_page_layout' ) ) :
	/**
	 * Returns the template part for Page
	 *
	 * Based on Page Settings to control the layout per each page
	 * and Theme Options to control the layout globally.
	 *
	 * @see page.php
	 * @see inc/meta-boxes.php
	 * @see inc/options.php
	 * @see template-parts/page/page-*.php
	 *
	 * @return string
	 */
	function startapp_page_layout() {
		$layout = startapp_get_setting( 'page_layout', 'no' );

		/**
		 * Filter the layout type for Page
		 *
		 * NOTE: this is a part of the file name, so if you want to add a custom
		 * layout in the Child Theme you have to follow the file name convention.
		 * Your file should be named page-{$layout}.php
		 *
		 * You can add your custom template part to
		 * /theme-child/template-parts/page/page-{$layout}.php
		 *
		 * @param string $layout Layout
		 */
		return esc_attr( apply_filters( 'startapp_page_layout', $layout ) );
	}
endif;

if ( ! function_exists( 'startapp_search_open_wrapper' ) ) :
	/**
	 * Wrap the Search page to section.container. Open tag.
	 *
	 * @hooked startapp_search_before 5
	 * @see    startapp_search_close_wrapper()
	 * @see    search.php
	 */
	function startapp_search_open_wrapper() {
		echo '<section class="container search-results padding-bottom-3x">';
	}
endif;

if ( ! function_exists( 'startapp_search_close_wrapper' ) ) :
	/**
	 * Wrap the Search page to section.container. Close tag.
	 *
	 * @hooked startapp_search_after 5
	 * @see    startapp_search_open_wrapper()
	 * @see    search.php
	 */
	function startapp_search_close_wrapper() {
		echo '</section>';
	}
endif;

if ( ! function_exists( 'startapp_footer_menu' ) ) :
	/**
	 * Display the footer menu
	 */
	function startapp_footer_menu() {
		if ( ! has_nav_menu( 'footer' ) ) {
			return;
		}

		/**
		 * Filter the menu arguments
		 *
		 * @see https://developer.wordpress.org/reference/functions/wp_nav_menu/
		 *
		 * @param array $args Arguments
		 */
		$args = apply_filters( 'startapp_footer_menu_args', array(
			'theme_location'  => 'footer',
			'container'       => 'nav',
			'container_class' => 'footer-menu',
			'container_id'    => false,
			'fallback_cb'     => false,
			'depth'           => 1,
			'walker'          => new Startapp_Walker_Nav_Menu_Extra(),
		) );

		wp_nav_menu( $args );
	}
endif;

if ( ! function_exists( 'startapp_footer_class' ) ) :
	/**
	 * Echoes the footer class
	 *
	 * @see footer.php
	 */
	function startapp_footer_class() {
		$classes = array();

		$classes[] = 'site-footer';
		$classes[] = 'footer-' . sanitize_key( startapp_get_option( 'footer_skin', 'light' ) );
		$classes[] = startapp_is_footer_parallax() ? 'bg-parallax' : '';

		/**
		 * Filter the footer class
		 *
		 * @param array $classes A list of footer classes
		 */
		$classes = apply_filters( 'startapp_footer_class', $classes );

		echo esc_attr( startapp_get_classes( $classes ) );
	}
endif;

if ( ! function_exists( 'startapp_footer_attr' ) ) :
	/**
	 * Echoes the footer attributes
	 *
	 * @see footer.php
	 */
	function startapp_footer_attr() {
		$attr = array();

		$background_id = (int) startapp_get_option( 'footer_background', 0 );
		$attr['style'] = startapp_css_background_image( $background_id );

		// footer parallax
		if ( ! empty( $background_id ) && startapp_is_footer_parallax() ) {
			$type  = startapp_get_option( 'footer_parallax_type', 'scroll' );
			$speed = startapp_get_option( 'footer_parallax_speed', 0.4 );

			$attr['data-parallax-type']  = esc_attr( $type );
			$attr['data-parallax-speed'] = startapp_sanitize_float( $speed );

			unset( $type, $speed );
		}

		/**
		 * Filter the footer attributes
		 *
		 * @param array $attr Footer attributes
		 */
		$attr = apply_filters( 'startapp_footer_attr', $attr );

		// remove class, use "startapp_footer_class" instead
		$attr = array_diff_key( $attr, array( 'class' => '' ) );

		echo startapp_get_attr( $attr );
	}
endif;

if ( ! function_exists( 'startapp_footer_fullwidth_class' ) ) :
	/**
	 * Check if Footer Fullwidth option enabled
	 * and adds "-fluid" to ".container" inside Footer
	 *
	 * Based on Page Settings > Theme Options
	 *
	 * @see startapp_add_footer_options()
	 */
	function startapp_footer_fullwidth_class() {
		$is = (int) startapp_get_option( 'footer_is_fullwidth', 0 );

		echo (bool) $is ? 'container-fluid' : 'container';
	}
endif;

if ( ! function_exists( 'startapp_footer_layout' ) ):
	/**
	 * Returns the template part name for footer
	 *
	 * Based on Theme Options
	 *
	 * @see footer.php
	 * @see inc/options.php
	 * @see template-parts/footers/footer-*.php
	 *
	 * @return string
	 */
	function startapp_footer_layout() {
		$layout = startapp_get_option( 'footer_layout', 'four-two' );

		/**
		 * Filter the layout type
		 *
		 * NOTE: this is a part of the file name, so if you want to add a custom
		 * layout in the child theme you have to follow the file name convention.
		 * Your file should be named footer-{$layout}.php
		 *
		 * You can add your custom template part to
		 * /theme-child/template-parts/footers/footer-{$layout}.php
		 *
		 * @param string $layout Layout
		 */
		return esc_attr( apply_filters( 'startapp_footer_layout', $layout ) );
	}
endif;

if ( ! function_exists( 'startapp_footer_copyright' ) ) :
	/**
	 * Echoes the copyright in footer area
	 *
	 * @see footer.php
	 */
	function startapp_footer_copyright() {
		$copyright = startapp_get_option( 'footer_copyright', '' );
		if ( ! empty( $copyright ) ) {
			$copyright = nl2br( startapp_sanitize_text( $copyright ) );
		} else {
			$copyright = startapp_sanitize_text(
				__( 'StartApp. Made with <i class="material-icons favorite_border"></i> by <a href="http://8guild.com">8Guild</a>', 'startapp' )
			);
		}

		echo startapp_get_text( $copyright, '<p class="copyright-text">', '</p>' );
	}
endif;

if ( ! function_exists( 'startapp_footer_backdrop' ) ) :
	/**
	 * Echoes the footer backdrop
	 *
	 * Should be right before the footer. Required for offcanvas, etc.
	 *
	 * @hooked startapp_footer_before 100
	 * @see    footer.php
	 */
	function startapp_footer_backdrop() {
		echo '<div class="site-backdrop"></div>';
	}
endif;

if ( ! function_exists( 'startapp_copyright_class' ) ) :
	/**
	 * Echoes the copyright class
	 *
	 * @see footer.php
	 */
	function startapp_copyright_class() {
		$classes = array();

		$classes[] = 'copyright';
		$classes[] = 'bg-' . startapp_get_option( 'footer_copyright_color', 'primary' );
		$classes[] = 'text-' . startapp_get_option( 'footer_copyright_skin', 'light' );

		/**
		 * Filter the copyright section class
		 *
		 * @param array $classes A list of copyright classes
		 */
		$classes = apply_filters( 'startapp_copyright_class', $classes );

		echo esc_attr( startapp_get_classes( $classes ) );
	}
endif;

if ( ! function_exists( 'startapp_copyright_attr' ) ) :
	/**
	 * Echoes the copyright attributes
	 *
	 * @see footer.php
	 */
	function startapp_copyright_attr() {
		$attr = array();

		if ( 'custom' === startapp_get_option( 'footer_copyright_color' ) ) {
			$color         = sanitize_hex_color( startapp_get_option( 'footer_copyright_color_custom' ) );
			$attr['style'] = startapp_css_background_color( $color );
			unset( $color );
		}

		/**
		 * Filter the footer attributes
		 *
		 * @param array $attr Footer attributes
		 */
		$attr = apply_filters( 'startapp_copyright_attr', $attr );

		// remove class, use "startapp_copyright_class" instead
		$attr = array_diff_key( $attr, array( 'class' => '' ) );

		echo startapp_get_attr( $attr );
	}
endif;

if ( ! function_exists( 'startapp_primary_menu' ) ) :
	/**
	 * Show the main navigation
	 */
	function startapp_primary_menu() {
		if ( ! has_nav_menu( 'primary' ) ) {
			return;
		}

		/**
		 * Filter the main menu arguments
		 *
		 * @see https://developer.wordpress.org/reference/functions/wp_nav_menu/
		 *
		 * @param array $args Arguments
		 */
		$args = apply_filters( 'startapp_primary_menu_args', array(
			'theme_location'  => 'primary',
			'container'       => 'nav',
			'container_class' => 'main-navigation',
			'container_id'    => false,
			'fallback_cb'     => false,
			'depth'           => 3,
			'walker'          => new Startapp_Walker_Nav_Menu_Top(),
		) );

		wp_nav_menu( $args );
	}
endif;

if ( ! function_exists( 'startapp_vertical_menu' ) ) :
	/**
	 * Show the main navigation inside Off-Canvas Navbar / Lateral Navbar
	 */
	function startapp_vertical_menu() {
		if ( ! has_nav_menu( 'primary' ) ) {
			return;
		}

		/**
		 * Filter the main menu arguments
		 *
		 * @see https://developer.wordpress.org/reference/functions/wp_nav_menu/
		 *
		 * @param array $args Arguments
		 */
		$args = apply_filters( 'startapp_vertical_menu_args', array(
			'theme_location'  => 'primary',
			'container'       => 'nav',
			'container_class' => 'vertical-navigation',
			'container_id'    => false,
			'fallback_cb'     => false,
			'depth'           => 3,
			'link_after'      => '<span class="arrow"><i class="material-icons keyboard_arrow_down"></i></span>',
			'walker'          => new Startapp_Walker_Nav_Menu_Top(),
		) );

		wp_nav_menu( $args );
	}
endif;

if ( ! function_exists( 'startapp_fs_menu' ) ) :
	/**
	 * Show the main navigation inside Full Screen Menu
	 */
	function startapp_fs_menu() {
		if ( ! has_nav_menu( 'primary' ) ) {
			return;
		}

		/**
		 * Filter the main menu arguments
		 *
		 * @see https://developer.wordpress.org/reference/functions/wp_nav_menu/
		 *
		 * @param array $args Arguments
		 */
		$args = apply_filters( 'startapp_fs_menu_args', array(
			'theme_location'  => 'primary',
			'container'       => 'nav',
			'container_class' => 'fs-navigation',
			'container_id'    => false,
			'fallback_cb'     => false,
			'depth'           => 1,
			'walker'          => new Startapp_Walker_Nav_Menu_Extra(),
		) );

		wp_nav_menu( $args );
	}
endif;

if ( ! function_exists( 'startapp_scroller_menu' ) ) :
	/**
	 * Display the Scroller menu
	 */
	function startapp_scroller_menu() {
		if ( ! has_nav_menu( 'scroller' ) ) {
			return;
		}

		/**
		 * Filter the menu arguments
		 *
		 * @see https://developer.wordpress.org/reference/functions/wp_nav_menu/
		 *
		 * @param array $args Arguments
		 */
		$args = apply_filters( 'startapp_scroller_menu_args', array(
			'theme_location'  => 'scroller',
			'container'       => 'nav',
			'container_class' => 'scroller-menu',
			'container_id'    => false,
			'fallback_cb'     => false,
			'depth'           => 1,
		) );

		wp_nav_menu( $args );
	}
endif;

if ( ! function_exists( 'startapp_offcanvas_menu' ) ):
	/**
	 * Display the Off-Canvas Menu
	 *
	 * @hooked startapp_header_before 10
	 * @see    header.php
	 */
	function startapp_offcanvas_menu() {
		if ( ! has_nav_menu( 'primary' ) ) {
			return;
		}

		/**
		 * Filter the search form classes
		 *
		 * This filter allows you to easily add any custom class to the search form popup
		 *
		 * @param array $class A list of classes
		 */
		$class = startapp_get_classes( apply_filters( 'startapp_offcanvas_menu_class', array(
			'off-canvas-menu',
		) ) );

		?>
		<aside class="<?php echo esc_attr( $class ); ?>">
			<span class="close-btn"><i class="material-icons clear"></i></span>
			<?php
			startapp_vertical_menu();
			startapp_navbar_socials();
			startapp_header_buttons();
			?>
		</aside>
		<?php
	}
endif;

if ( ! function_exists( 'startapp_open_page_wrap' ) ) :
	/**
	 * Open the main.page-wrap
	 *
	 * This tag wraps every page. Should be opened right after the header
	 * and closed after the footer (yes, footer should be inside the .page-wrap)
	 *
	 * @hooked startapp_header_after -1
	 * @see    startapp_close_page_wrap()
	 * @see    header.php
	 */
	function startapp_open_page_wrap() {
		echo '<main class="page-wrap">';
	}
endif;

if ( ! function_exists( 'startapp_close_page_wrap' ) ) :
	/**
	 * Close the main.page-wrap tag
	 *
	 * @hooked startapp_footer_after 999
	 * @see    startapp_open_page_wrap()
	 * @see    footer.php
	 */
	function startapp_close_page_wrap() {
		echo '</main>';
	}
endif;

if ( ! function_exists( 'startapp_page_title' ) ) :
	/**
	 * Display the page title
	 *
	 * May be used within or outside the Loop
	 *
	 * @hooked startapp_header_after 10
	 * @see    header.php
	 */
	function startapp_page_title() {
		if ( ! startapp_is_page_title() || is_singular( 'post' ) ) {
			return;
		}

		if ( 'posts' == get_option( 'show_on_front' ) && is_home() ) {
			// for home page without static page
			$title = esc_html__( 'Blog', 'startapp' );
		} elseif ( is_home() || is_front_page() || is_page() ) {
			// applicable for home with static page, for front page and single page
			$title = single_post_title( '', false );
		} elseif ( is_search() ) {
			// search results
			// NOTE: translators, there is a space after "Results: "
			$title = startapp_get_text( esc_html( get_search_query() ), esc_html__( 'Results for: ', 'startapp' ) );
		} elseif ( is_archive() ) {
			// archive page
			$title = get_the_archive_title();
		} elseif ( is_404() ) {
			$title = '404';
		} else {
			$title = get_the_title();
		}

		/**
		 * Filter the Page Title
		 *
		 * @param string $title Page Title
		 */
		$title = apply_filters( 'startapp_page_title', $title );

		// show nothing if page title not provided
		if ( empty( $title ) ) {
			return;
		}

		$background_id = (int) startapp_get_setting( 'page_title_bg' );

		$is_bg        = ( ! empty( $background_id ) );
		$is_overlap   = (bool) startapp_get_setting( 'page_title_overlap', 0 );
		$is_fullwidth = (bool) startapp_get_setting( 'page_title_fullwidth', 0 );
		$is_parallax  = (bool) startapp_get_setting( 'page_title_parallax', 0 );
		$is_overlay   = (bool) startapp_get_setting( 'page_title_overlay', 1 );

		// prepare classes
		$classes   = array();
		$classes[] = 'page-title';
		$classes[] = 'title-size-' . startapp_get_setting( 'header_page_title_size', 'normal' );
		$classes[] = 'text-' . startapp_get_setting( 'page_title_skin', 'dark' );
		$classes[] = $is_overlap ? 'title-floating' : '';
		$classes[] = $is_fullwidth ? 'title-fullwidth' : '';
		$classes[] = $is_parallax ? 'bg-parallax' : '';

		/**
		 * Filter the page title class
		 *
		 * @param array $classes Page title classes
		 */
		$class = startapp_get_classes( apply_filters( 'startapp_page_title_class', $classes ) );
		unset( $classes );

		// prepare attributes
		$attr          = array();
		$attr['class'] = esc_attr( $class );

		if ( $is_parallax && $is_bg ) {
			$attr['data-parallax-type']  = esc_attr( startapp_get_setting( 'page_title_parallax_type', 'scroll' ) );
			$attr['data-parallax-speed'] = startapp_sanitize_float( startapp_get_setting( 'page_title_parallax_speed', 0.4 ) );
			$attr['data-jarallax-video'] = esc_url( startapp_get_setting( 'page_title_parallax_video' ) );
		}

		// prepare styles
		$styles                     = array();
		$styles['background-color'] = sanitize_hex_color( startapp_get_setting( 'page_title_bg_color', '' ) );
		$styles['background-image'] = $is_bg ? sprintf( 'url(%s)', esc_url( startapp_get_image_src( $background_id ) ) ) : '';

		/**
		 * Filter the Page Title style attribute
		 *
		 * Callback MUST return an array, where key is a css property,
		 * and "value" is a valid value of this property. For example:
		 *
		 * ```
		 * return [
		 *   'max-height' => '250px',
		 *   'background-color' => 'red',
		 * ]
		 * ```
		 *
		 * NOTE: don't use semicolon at the end of property value
		 *
		 * @param array $styles Styles
		 */
		$styles = apply_filters( 'startapp_page_title_style', $styles );
		$styles = array_filter( $styles, function ( $style ) {
			return '' !== $style; // remove empty styles
		} );

		$attr['style'] = startapp_css_declarations( $styles );
		unset( $styles );

		/**
		 * Filter the page title attributes
		 *
		 * @param array $attr Page title attr
		 */
		$attr = apply_filters( 'startapp_page_title_attr', $attr );

		$overlay = array();
		if ( $is_overlay && $is_bg ) {
			$color   = startapp_get_setting( 'page_title_overlay_color', '#000000' );
			$opacity = startapp_get_setting( 'page_title_overlay_opacity', 35 );
			$style   = startapp_css_declarations( array(
				'background-color' => sanitize_hex_color( $color ),
				'opacity'          => startapp_get_opacity_value( $opacity ),
			) );

			$overlay['class'] = 'overlay';
			$overlay['style'] = $style;

			unset( $color, $opacity, $style );
		}

		?>
		<div <?php echo startapp_get_attr( $attr ); ?>>

			<?php
			// overlay
			if ( $is_overlay && $is_bg ) : echo startapp_get_tag( 'span', $overlay, '' ); endif; ?>

			<div class="container">
				<div class="inner">
					<div class="column">
						<?php startapp_the_text( $title, '<h1>', '</h1>' ); ?>
					</div>
					<div class="column">
						<?php startapp_the_breadcrumbs(); ?>
					</div>
				</div>
			</div>
		</div>
		<?php
	}
endif;

if ( ! function_exists( 'startapp_scroll_to_top' ) ) :
	/**
	 * Display scroll to top button
	 *
	 * @hooked startapp_footer_before 10
	 * @see    footer.php
	 *
	 * @uses   startapp_is_scroll_to_top()
	 */
	function startapp_scroll_to_top() {
		if ( ! startapp_is_scroll_to_top() ) {
			return;
		}

		?>
		<a href="#" class="scroll-to-top-btn">
			<i class="material-icons keyboard_arrow_up"></i>
		</a>
		<?php
	}
endif;

if ( ! function_exists( 'startapp_excerpt_more' ) ) :
	/**
	 * Returns  the string in the "more" link displayed after a trimmed excerpt
	 *
	 * @hooked excerpt_more 10
	 *
	 * @return string
	 */
	function startapp_excerpt_more() {
		return '...';
	}
endif;

if ( ! function_exists( 'startapp_edit_post_link' ) ) :
	/**
	 * Add a tooltip to the edit post link
	 *
	 * @param string $link Edit link HTML tag
	 * @param int $post_id Post ID
	 * @param string $text Text passed to the {@see edit_post_link()}
	 *
	 * @hooked edit_post_link 10
	 *
	 * @return string
	 */
	function startapp_edit_post_link( $link, $post_id, $text ) {
		return str_replace(
			'class="edit-link"',
			sprintf( 'class="edit-link" data-toggle="tooltip" title="%s"', esc_html__( 'Edit', 'startapp' ) ),
			$link
		);
	}
endif;

if ( ! function_exists( 'startapp_shop_layout' ) ) :
	/**
	 * Returns the template part for Shop
	 *
	 * Based on Theme Options
	 *
	 * @see startapp_add_shop_options()
	 * @see woocommerce/archive-product.php
	 * @see template-parts/shop/shop-*.php
	 *
	 * @return string
	 */
	function startapp_shop_layout() {
		$layout = startapp_get_setting( 'shop_layout', 'ls-3' );

		/**
		 * Filter the layout type for Shop
		 *
		 * NOTE: this is a part of the file name, so if you want to add a custom
		 * layout in the Child Theme you have to follow the file name convention.
		 * Your file should be named shop-{$layout}.php
		 *
		 * You can add your custom template part to
		 * /theme-child/template-parts/shop/shop-{$layout}.php
		 *
		 * @param string $layout Layout
		 */
		return esc_attr( apply_filters( 'startapp_shop_layout', $layout ) );
	}
endif;

if ( ! function_exists( 'startapp_classes_meta' ) ) :
	/**
	 * Display the Classes meta block when viewing a single post
	 *
	 * Should be used within the Loop
	 *
	 * @see single-startapp_classes.php
	 * @see Startapp_CPT_Classes
	 */
	function startapp_classes_meta() {
		// get Classes meta and make sure all required fields are present
		$atts = startapp_get_meta( get_the_ID(), '_startapp_classes_atts' );
		$atts = wp_parse_args( $atts, array(
			'subtitle'              => '',
			'date'                  => '',
			'time'                  => '',
			'seats'                 => '',
			'is_label'              => 0,
			'label_text'            => '',
			'label_text_color'      => 'dark',
			'label_bg_color'        => 'default',
			'label_bg_color_custom' => '',
			'author_avatar'         => 0,
			'author_name'           => '',
			'author_surname'        => '',
			'author_link'           => '',
		) );

		// author link
		$link = $atts['author_link'];

		// label
		$label = '';
		if ( (bool) $atts['is_label'] ) {
			$l_class = startapp_get_classes( array(
				'badge',
				'badge-' . esc_attr( $atts['label_bg_color'] ),
				'text-' . esc_attr( $atts['label_text_color'] ),
			) );

			$l_attr = array(
				'class' => esc_attr( $l_class ),
				'style' => startapp_css_color( sanitize_hex_color( $atts['label_bg_color_custom'] ) ),
			);

			$label = startapp_get_tag( 'div', $l_attr, esc_html( trim( $atts['label_text'] ) ) );
			unset( $l_class, $l_attr );
		}

		?>
		<div class="container classes-wrap">
			<?php echo startapp_get_text( esc_html( $atts['subtitle'] ), '<h2 class="class-subtitle">', '</h2>' ); ?>
			<header class="classes-tile-header">
				<?php if ( ! empty( $link ) ) : ?>
					<a href="<?php echo esc_url( $link ); ?>" class="author">
						<div class="ava">
							<?php echo wp_get_attachment_image( (int) $atts['author_avatar'] ); ?>
						</div>
						<div class="name">
							<?php
							echo startapp_get_text( esc_html( $atts['author_name'] ), '<span>', '</span>' );
							echo startapp_get_text( esc_html( $atts['author_surname'] ), '<span>', '</span>' );
							?>
						</div>
					</a>
				<?php else : ?>
					<div class="author">
						<div class="ava">
							<?php echo wp_get_attachment_image( (int) $atts['author_avatar'] ); ?>
						</div>
						<div class="name">
							<?php
							echo startapp_get_text( esc_html( $atts['author_name'] ), '<span>', '</span>' );
							echo startapp_get_text( esc_html( $atts['author_surname'] ), '<span>', '</span>' );
							?>
						</div>
					</div>
				<?php endif; ?>

				<div class="date-time">

					<?php if ( ! empty( $atts['date'] ) ) : ?>
						<div class="date">
							<i class="material-icons date_range"></i>
							<?php echo esc_html( $atts['date'] ); ?>
						</div>
					<?php endif; ?>

					<?php if ( ! empty( $atts['time'] ) ) : ?>
						<div class="time">
							<i class="material-icons access_time"></i>
							<?php echo esc_html( $atts['time'] ); ?>
						</div>
					<?php endif; ?>

					<?php if ( ! empty( $atts['seats'] ) ) : ?>
						<div class="tickets-left">
							<i class="material-icons person"></i>
							<?php echo esc_html( $atts['seats'] ); ?>
						</div>
					<?php endif; ?>

				</div>

				<div class="badge-cont">
					<?php echo startapp_get_text( $label ); ?>
				</div>

			</header>
		</div>
		<?php
	}
endif;

if ( ! function_exists( 'startapp_archive_open_wrapper' ) ) :
	/**
	 * Wrap the archive to div.container. Open tag.
	 *
	 * @hooked startapp_archive_before 5
	 * @see    startapp_archive_close_wrapper()
	 * @see    archive.php
	 */
	function startapp_archive_open_wrapper() {
		echo '<div class="container padding-top-3x padding-bottom-3x">';
	}
endif;

if ( ! function_exists( 'startapp_archive_close_wrapper' ) ) :
	/**
	 * Wrap the archive to div.container. Close tag.
	 *
	 * @hooked startapp_archive_after 5
	 * @see    startapp_archive_open_wrapper()
	 * @see    archive.php
	 */
	function startapp_archive_close_wrapper() {
		echo '</div>';
	}
endif;

if ( ! function_exists( 'startapp_author_coauthor' ) ) :
	/**
	 * Display the Co-Author snippet.
	 *
	 * @hooked startapp_loop_after - 100
	 */
	function startapp_author_coauthor() {
		if ( ! is_author() || ! function_exists( 'coauthors_get_avatar' ) ) {
			return;
		}

		// Display only on first page
		if ( is_paged() ) {
			return;
		}

		$author = get_queried_object();
		$data   = array();

		$data['{avatar}'] = coauthors_get_avatar( $author, 150 );
		$data['{name}']   = startapp_get_text( esc_html( $author->display_name ), '<h3 class="post-author-name">', '</h3>' );
		$data['{bio}']    = startapp_get_text( esc_html( $author->description ), '<p>', '</p>' );

		/**
		 * Filter the Co-Author data
		 *
		 * @param array    $data
		 * @param stdClass $author Co-Author object
		 */
		$r = apply_filters( 'startapp_coauthor_data', $data, $author );

		echo str_replace( array_keys( $r ), array_values( $r ), '
		<section class="post-author">
			<div class="post-author-thumb" style="padding: 24px;">
				{avatar}
			</div>
			<div class="post-author-info">
				{name}
				{bio}
			</div>
		</section>
		' );
	}
endif;

if ( ! function_exists( 'startapp_author_title' ) ) :
	/**
	 * Fix the title on Author archive page.
	 *
	 * Page Title shows incorrect author because it is not in the Loop.
	 *
	 * @param string $title Original title
	 *
	 * @return string
	 */
	function startapp_author_title( $title ) {
		if ( is_author() ) {
			$author = get_queried_object();
			$title  = sprintf( esc_html__( 'Author: %s', 'startapp' ), '<span class="vcard">' . esc_html( $author->display_name ) . '</span>' );
		}

		return $title;
	}
endif;
