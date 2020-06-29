<?php
/**
 * Template part for displaying results in search pages.
 *
 * @link    https://codex.wordpress.org/Template_Hierarchy
 *
 * @package Startapp
 */

?>
<article id="post-<?php the_ID(); ?>" <?php post_class( 'post-tile' ); ?>>
	<div class="post-body">

		<?php
		startapp_entry_header();
		the_title(
			sprintf( '<h3 class="post-title"><a href="%s">', esc_url( get_permalink() ) ),
			'</a></h3>'
		);

		the_content( '' );
		?>

	</div>
</article>