<?php
/**
 * Template part for displaying the "No Sidebar Grid" blog layout
 *
 * @author 8guild
 */

/**
 * Fires right before the blog loop starts
 */
do_action( 'startapp_loop_before' );

?>
<div class="masonry-grid col-3">
	<div class="gutter-sizer"></div>
	<div class="grid-sizer"></div>

	<?php while ( have_posts() ) : the_post(); ?>
		<div class="grid-item">
			<?php get_template_part( 'template-parts/blog/post', 'tile' ); ?>
		</div>
	<?php endwhile; ?>
</div>
<?php

/**
 * Fires right after the blog loop
 *
 * @see startapp_blog_pagination()
 */
do_action( 'startapp_loop_after' );
