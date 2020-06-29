<?php
/**
 * The main template file.
 *
 * This is the most generic template file in a WordPress theme
 * and one of the two required files for a theme (the other being style.css).
 * It is used to display a page when nothing more specific matches a query.
 * E.g., it puts together the home page when no home.php file exists.
 *
 * @link https://codex.wordpress.org/Template_Hierarchy
 *
 * @package Startapp
 */

get_header();

/**
 * Fires at the most top of the blog content
 *
 * @see startapp_blog_open_wrapper() 5
 */
do_action( 'startapp_blog_before' );

if ( have_posts() ) :
	get_template_part( 'template-parts/blog/blog', startapp_blog_layout() );
else :
	get_template_part( 'template-parts/none' );
endif;

/**
 * Fires at the most bottom of the blog content
 *
 * @see startapp_blog_close_wrapper() 5
 */
do_action( 'startapp_blog_after' );

get_footer();
