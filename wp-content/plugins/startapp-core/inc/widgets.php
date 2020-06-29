<?php
/**
 * Custom widgets
 *
 * @package Startapp
 */

// Exit if accessed directly
if ( ! defined( 'ABSPATH' ) ) {
    exit;
}

/**
 * Register widget area(s)
 *
 * @link https://developer.wordpress.org/themes/functionality/sidebars/#registering-a-sidebar
 */
function startapp_widgets_init() {

    /* Register Sidebars */

    register_sidebar( array(
        'name'          => sprintf( '%1$s (%2$s)', esc_html__( 'Off-Canvas Sidebar', 'startapp' ), ucfirst( startapp_get_option( 'header_offcanvas_pos', 'right' ) ) ),
        'id'            => 'sidebar-offcanvas',
        'description'   => esc_html__( 'Widgets will appear in Off-Canvas Sidebar if enabled. The position of Off-Canvas Sidebar can be changed in StartApp > Header > General', 'startapp' ),
        'before_widget' => '<section id="%1$s" class="widget %2$s">',
        'after_widget'  => '</section>',
        'before_title'  => '<h2 class="widget-title">',
        'after_title'   => '</h2>',
    ) );

    register_sidebar( array(
        'name'          => esc_html__( 'Header Buttons', 'startapp' ),
        'id'            => 'sidebar-header-buttons',
        'description'   => esc_html__( 'This sidebar supports only StartApp Button widgets.', 'startapp' ),
        'before_widget' => '<section id="%1$s" class="widget %2$s">',
        'after_widget'  => '</section>',
        'before_title'  => '<h2 class="widget-title">',
        'after_title'   => '</h2>',
    ) );

    register_sidebar( array(
        'name'          => esc_html__( 'Page Sidebar', 'startapp' ),
        'id'            => 'sidebar-page',
        'description'   => esc_html__( 'Add Page widgets here.', 'startapp' ),
        'before_widget' => '<section id="%1$s" class="widget %2$s">',
        'after_widget'  => '</section>',
        'before_title'  => '<h2 class="widget-title">',
        'after_title'   => '</h2>',
    ) );

    if ( /*startapp_is_woocommerce()*/ false) {
        register_sidebar( array(
            'name'          => esc_html__( 'Shop Sidebar', 'startapp' ),
            'id'            => 'sidebar-shop',
            'description'   => esc_html__( 'Add WooCommerce widgets here.', 'startapp' ),
            'before_widget' => '<section id="%1$s" class="widget %2$s">',
            'after_widget'  => '</section>',
            'before_title'  => '<h2 class="widget-title">',
            'after_title'   => '</h2>',
        ) );
    }


    for ( $i = 1, $n = startapp_get_footer_sidebars(); $i <= $n; $i ++ ) {
        register_sidebar( array(
            'name'          => esc_html__( 'Footer Column ', 'startapp' ) . $i, // whitespace at the end
            'id'            => 'footer-column-' . $i,
            'description'   => esc_html__( 'For use inside Footer', 'startapp' ),
            'before_widget' => '<section id="%1$s" class="widget %2$s">',
            'after_widget'  => '</section>',
            'before_title'  => '<h3 class="widget-title">',
            'after_title'   => '</h3>',
        ) );
    }
    unset( $i, $n );

    for ( $i = 1, $n = startapp_get_widgetised_sidebars(); $i <= $n; $i ++ ) {
        register_sidebar( array(
            'name'          => esc_html__( 'Widgetized Sidebar ', 'startapp' ) . $i, // whitespace at the end
            'id'            => 'widgetized-sidebar-' . $i,
            'description'   => esc_html__( 'For use inside Widgetized Area', 'startapp' ),
            'before_widget' => '<section id="%1$s" class="widget %2$s">',
            'after_widget'  => '</section>',
            'before_title'  => '<h3 class="widget-title">',
            'after_title'   => '</h3>',
        ) );
    }
    unset( $i, $n );

    for ( $i = 1, $n = startapp_get_mega_menu_sidebars(); $i <= $n; $i ++ ) {
        register_sidebar( array(
            'name'          => esc_html__( 'Mega Menu ', 'startapp' ) . $i, // whitespace at the end
            'id'            => 'mega-menu-sidebar-' . $i,
            'description'   => esc_html__( 'You can link this sidebar to Menu Item. Each widget converts to column. Supported widgets: Categories, Custom Menu, Pages, StartApp Button, Visual Editor (Black Studio TinyMCE).', 'startapp' ),
            'before_widget' => '<section id="%1$s" class="widget %2$s">',
            'after_widget'  => '</section>',
            'before_title'  => '<h3 class="widget-title">',
            'after_title'   => '</h3>',
        ) );
    }


    /* Register Widgets */

    register_widget( 'Startapp_Widget_Author' );
    register_widget( 'Startapp_Widget_Subscribe' );
    register_widget( 'Startapp_Widget_Recent_Posts' );
    register_widget( 'Startapp_Widget_Socials' );
    register_widget( 'Startapp_Widget_Button' );
    register_widget( 'Startapp_Widget_Contacts' );
    register_widget( 'Startapp_Widget_Site_Info' );
    register_widget( 'Startapp_Widget_Image_Carousel' );
}

add_action( 'widgets_init', 'startapp_widgets_init' );

/**
 * Autoloader for Widgets
 *
 * @param string $widget Widget class
 *
 * @return bool
 */
function startapp_widgets_loader( $widget ) {
    if ( false === stripos( $widget, 'Startapp_Widget' ) ) {
        return true;
    }

    // convert class name to file
    $chunks = array_filter( explode( '_', strtolower( $widget ) ) );

    /**
     * Filter the widget file name
     *
     * @param string $file File name according to WP coding standards
     * @param string $widget Class name
     */
    $class = apply_filters( 'startapp_widget_file', 'class-' . implode( '-', $chunks ) . '.php', $widget );

    /**
     * Filter the directories where widgets class will be loaded
     *
     * @param array $targets Directories
     */
    $targets = apply_filters( 'startapp_widget_directories', array(
        STARTAPP_STYLESHEET_DIR . '/widgets',
        STARTAPP_TEMPLATE_DIR . '/widgets',
    ) );

    foreach ( $targets as $target ) {
        if ( file_exists( $target . '/' . $class ) ) {
            require $target . '/' . $class;
            break;
        }
    }

    return true;
}

spl_autoload_register( 'startapp_widgets_loader' );

/**
 * Get footer sidebars
 *
 * Based on Theme Options > Footer > Layout
 *
 * @see inc/options.php
 * @see startapp_widgets_init()
 *
 * @return int
 */
function startapp_get_footer_sidebars() {
    $layout = esc_attr( startapp_get_option( 'footer_layout', 'four-two' ) );
    $map    = array(
        'copyright'   => 0,
        'one'         => 1,
        'two'         => 2,
        'three'       => 3,
        'four'        => 4,
        'one-one'     => 2,
        'two-one'     => 3,
        'three-one'   => 4,
        'four-one'    => 5,
        'one-two'     => 3,
        'two-two'     => 4,
        'three-two'   => 5,
        'four-two'    => 6,
        'one-three'   => 4,
        'two-three'   => 5,
        'three-three' => 6,
        'four-three'  => 7,
        'one-four'    => 5,
        'two-four'    => 6,
        'three-four'  => 7,
        'four-four'   => 8,
    );

    $num = array_key_exists( $layout, $map ) ? $map[ $layout ] : 6;

    /**
     * Filter the number of sidebars appears in Footer
     *
     * @param int $num Number of sidebars
     * @param string $layout Layout from Theme Options
     */
    return (int) apply_filters( 'startapp_footer_sidebars', $num, $layout );
}

/**
 * Get widgetised sidebars number
 *
 * @see startapp_widgets_init()
 *
 * @return int
 */
function startapp_get_widgetised_sidebars() {
    $num = startapp_get_option( 'advanced_widgetised_sidebars_num', 4 );

    /**
     * Filter the number of Widgetized sidebars
     *
     * @param int $num Number of sidebars
     */
    return absint( apply_filters( 'startapp_widgetized_sidebars', $num ) );
}

/**
 * Get Mega Menu sidebars number
 *
 * @see startapp_widgets_init()
 *
 * @return int
 */
function startapp_get_mega_menu_sidebars() {
    $num = startapp_get_option( 'header_mega_menu_num', 1 );

    /**
     * Filter the number of Mega Menu sidebars
     *
     * @param int $num Number of sidebars
     */
    return absint( apply_filters( 'startapp_mega_menu_sidebars', $num ) );
}

/**
 * Apply a custom walker to every nav_menu widget
 *
 * @param array $args Arguments passed to {@see wp_nav_menu()
 *
 * @return array
 */
function startapp_widget_nav_menu_args( $args ) {
    $args['fallback_cb'] = false;
    $args['depth']       = 1;
    $args['walker']      = new Startapp_Walker_Nav_Menu_Extra();

    return $args;
}

add_filter( 'widget_nav_menu_args', 'startapp_widget_nav_menu_args' );