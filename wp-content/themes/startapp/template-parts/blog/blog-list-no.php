<?php
/**
 * Template part for displaying the "No Sidebar List" blog layout
 *
 * @author 8guild
 */

/**
 * Fires right before the blog loop starts
 */
do_action( 'startapp_loop_before' );

while ( have_posts() ) :
	the_post();
	get_template_part( 'template-parts/blog/post', 'horizontal' );
endwhile;

/**
 * Fires right after the blog loop
 *
 * @see startapp_blog_pagination()
 */
do_action( 'startapp_loop_after' );
