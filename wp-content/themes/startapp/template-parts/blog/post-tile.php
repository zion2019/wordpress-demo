<?php
/**
 * Template Part for displaying the tiles in Blog for List / Grid layout
 *
 * @link   https://codex.wordpress.org/Template_Hierarchy
 * @author 8guild
 */

?>
<article id="post-<?php the_ID(); ?>" <?php post_class( 'post-tile' ); ?>>

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
		startapp_entry_header();
		the_title(
			sprintf( '<h3 class="post-title"><a href="%s">', esc_url( get_permalink() ) ),
			'</a></h3>'
		);

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

</article>
