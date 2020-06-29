<?php
/**
 * The template for displaying all single posts.
 *
 * @link    https://developer.wordpress.org/themes/basics/template-hierarchy/#single-post
 *
 * @author  8guild
 * @package Startapp
 */

get_header();

/**
 * Fires before the single post content
 *
 * @see startapp_single_cover()
 */
do_action( 'startapp_single_before' );

while ( have_posts() ) :
	the_post();
	get_template_part( 'template-parts/single/single', startapp_single_layout() );
endwhile;

/**
 * Fires after the single post content
 */
do_action( 'startapp_single_after' );

get_footer();

