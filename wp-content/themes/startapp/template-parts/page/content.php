<?php
/**
 * Template part for displaying pages
 *
 * @link   https://codex.wordpress.org/Template_Hierarchy
 *
 * @author 8guild
 */

?>
<article id="post-<?php the_ID(); ?>" <?php post_class(); ?>>
	<?php the_content(); ?>
</article>

