<?php
/**
 * The template for displaying all single Classes posts.
 *
 * @link    https://developer.wordpress.org/themes/basics/template-hierarchy/#single-post
 *
 * @author  8guild
 * @package Startapp
 */

get_header();

while ( have_posts() ) :
	the_post();
	get_template_part( 'template-parts/classes' );
endwhile;

get_footer();
