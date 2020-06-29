<?php
/**
 * Template part for displaying the "No Sidebar" layout for Page
 *
 * @author 8guild
 */

get_template_part( 'template-parts/page/content' );

// If comments are open or we have at least one comment,
// load up the comment template.
if ( comments_open() || get_comments_number() ) :
	echo '<div class="container">';
	comments_template();
	echo '</div>';
endif;
