<?php
/**
 * Template part for displaying the "Sidebar Right List" blog
 *
 * NOTE: This is a fallback in case if a template part is missing
 *
 * @see    template-parts/blog/blog-list-right.php
 * @link   https://codex.wordpress.org/Template_Hierarchy
 *
 * @author 8guild
 */

?>
<div class="row">
	<div class="col-md-9 col-sm-8">

		<?php
		/**
		 * Fires right before the blog loop starts
		 */
		do_action( 'startapp_loop_before' );

		while ( have_posts() ):
			the_post();
			get_template_part( 'template-parts/blog/post', 'tile' );
		endwhile;

		/**
		 * Fires after the blog loop
		 *
		 * @see startapp_blog_pagination()
		 */
		do_action( 'startapp_loop_after' );
		?>

	</div>

	<div class="col-md-3 col-sm-4">
		<div class="padding-top-2x visible-sm visible-xs"></div>
		<?php get_sidebar(); ?>
	</div>
</div>
