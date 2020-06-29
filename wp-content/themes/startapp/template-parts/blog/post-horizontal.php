<?php
/**
 * Template Part for displaying the posts in Blog for List No Sidebar layout only
 *
 * @link   https://codex.wordpress.org/Template_Hierarchy
 * @author 8guild
 */
?>
<article id="post-<?php the_ID(); ?>" <?php post_class( 'post-horizontal' ); ?>>
	<div class="row">
		<div class="col-sm-4">

			<?php
			the_title(
				sprintf( '<h2 class="post-title"><a href="%s">', esc_url( get_permalink() ) ),
				'</a></h2>'
			);
			startapp_entry_header();
			?>

		</div>
		<div class="col-sm-8">

			<?php
			/**
			 * Fires before the div.post-body in the blog post tile
			 *
			 * @see startapp_entry_sticky() 10
			 * @see startapp_entry_thumbnail() 20
			 */
			do_action( 'startapp_post_body_before' );

			?>
			<div class="post-body">

				<?php
				the_excerpt();
				startapp_entry_footer();
				?>

			</div>
			<?php

			/**
			 * Fires right after the div.post-body in the blog post tile
			 *
			 * @see startapp_entry_thumbnail() 20
			 */
			do_action( 'startapp_post_body_after' );
			?>

		</div>
	</div>
</article>
