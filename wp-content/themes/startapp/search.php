<?php
/**
 * The template for displaying search results pages.
 *
 * @link https://developer.wordpress.org/themes/basics/template-hierarchy/#search-result
 *
 * @package Startapp
 */

get_header();

/**
 * Fires at the most top of the Search page
 *
 * @see startapp_search_open_wrapper() 5
 */
do_action( 'startapp_search_before' );

if ( have_posts() ) :

	while ( have_posts() ) :
		the_post();
		get_template_part( 'template-parts/search' );
	endwhile;

	the_posts_navigation();

else :

	get_template_part( 'template-parts/none' );

endif;

/**
 * Fires at the most bottom of the Search page
 *
 * @see startapp_search_close_wrapper() 5
 */
do_action( 'startapp_search_after' );

get_footer();
