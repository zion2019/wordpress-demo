<?php
/**
 * Template part for displaying the content of single Classes page
 *
 * @see single-startapp_classes.php
 *
 * @link    https://codex.wordpress.org/Template_Hierarchy
 *
 * @author  8guild
 * @package Startapp
 */
?>
<article id="post-<?php the_ID(); ?>" <?php post_class(); ?>>
	<div class="class-single-meta">
		<?php startapp_classes_meta(); ?>
	</div>
	<?php the_content(); ?>
</article>
