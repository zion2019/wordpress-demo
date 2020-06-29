<?php
/**
 * Template part for displaying the "Sidebar Left Grid" blog layout
 *
 * @author 8guild
 */
?>

<div class="row">
	<div class="col-md-9 col-sm-8 col-md-push-3 col-sm-push-4">

		<?php
		/**
		 * Fires right before the blog loop starts
		 */
		do_action( 'startapp_loop_before' );
		?>

		<div class="masonry-grid col-2">
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
		?>

	</div>
	<div class="col-md-3 col-sm-4 col-md-pull-9 col-sm-pull-8">
		<div class="padding-top-2x visible-sm visible-xs"></div>
		<?php get_sidebar(); ?>
	</div>
</div>
