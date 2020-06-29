<?php
/**
 * The header for our theme.
 *
 * @link    https://developer.wordpress.org/themes/basics/template-files/#template-partials
 *
 * @package Startapp
 */

?><!DOCTYPE html>
<html itemscope itemtype="http://schema.org/WebPage" <?php language_attributes(); ?>>
<head>
	<meta charset="<?php bloginfo( 'charset' ); ?>">
	<meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0, user-scalable=no">

	<?php wp_head(); ?>
</head>

<body <?php body_class(); ?>>

<?php
/**
 * Fires right before the <header>
 *
 * @see startapp_the_preloader()
 * @see startapp_the_offcanvas()
 * @see startapp_the_seach()
 * @see startapp_the_scroller()
 * @see startapp_offcanvas_menu()
 */
do_action( 'startapp_header_before' );

get_template_part( 'template-parts/headers/header', startapp_header_layout() );

/**
 * Fires right after the .site-header
 *
 * @see startapp_open_page_wrap() -1
 * @see startapp_page_title() 10
 */
do_action( 'startapp_header_after' );
