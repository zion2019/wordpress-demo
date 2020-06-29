<?php
/**
 * The template for displaying all pages.
 *
 * This is the template that displays all pages by default.
 * Please note that this is the WordPress construct of pages
 * and that other 'pages' on your WordPress site may use a
 * different template.
 *
 * @link https://codex.wordpress.org/Template_Hierarchy
 *
 * @package Startapp
 */

get_header();

/**
 * Fires before the page content
 */
do_action( 'startapp_page_before' );

while ( have_posts() ) :
	the_post();
	get_template_part( 'template-parts/page/page', startapp_page_layout() );
endwhile;

/**
 * Fires after the page content
 */
do_action( 'startapp_page_after' );

get_footer();
