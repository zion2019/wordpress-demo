<?php
/**
 * Template Part for displaying the Blog with "Simple List" layout
 *
 * @link   https://codex.wordpress.org/Template_Hierarchy
 * @author 8guild
 */

?>
<article id="post-<?php the_ID(); ?>" <?php post_class( 'post-tile-simple' ); ?>>

	<?php
	startapp_entry_header();
	the_title(
		sprintf( '<h3 class="post-title"><a href="%s">', esc_url( get_permalink() ) ),
		'</a></h3>'
	);
	?>

</article>
