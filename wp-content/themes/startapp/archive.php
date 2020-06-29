<?php
/**
 * The template for displaying archive pages.
 *
 * @link    https://codex.wordpress.org/Template_Hierarchy
 *
 * @package Startapp
 */

get_header();

/**
 * Fires at the most top of the archive page content
 *
 * @see startapp_archive_open_wrapper() 5
 */
do_action( 'startapp_archive_before' );

if ( have_posts() ) :
	get_template_part( 'template-parts/blog/blog', startapp_blog_layout() );
else :
	get_template_part( 'template-parts/none' );
endif;

/**
 * Fires at the most bottom of the archive page content
 *
 * @see startapp_archive_close_wrapper() 5
 */
do_action( 'startapp_archive_after' );

get_footer();
