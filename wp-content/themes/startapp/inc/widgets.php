<?php
/**
 * Theme widgets
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

function startapp_default_widgets_init() {

    /* Register Sidebars */

    register_sidebar( array(
        'name'          => esc_html__( 'Blog Sidebar', 'startapp' ),
        'id'            => 'sidebar-blog',
        'description'   => esc_html__( 'Add widgets here.', 'startapp' ),
        'before_widget' => '<section id="%1$s" class="widget %2$s">',
        'after_widget'  => '</section>',
        'before_title'  => '<h2 class="widget-title">',
        'after_title'   => '</h2>',
    ) );

}
add_action( 'widgets_init', 'startapp_default_widgets_init' );