<?php
/**
 * Template part for displaying Portfolio "Simple Image"
 *
 * You can override this template-part by copying it to:
 *     theme-child/template-parts/portfolio-simple.php
 *     theme/template-parts/portfolio-simple.php
 *
 * @var array $args Arguments passed to template
 *
 * @author 8guild
 */

?>
<article id="post-<?php the_ID(); ?>" <?php post_class( $args['tile_class'] ); ?>>
	<a href="<?php the_permalink(); ?>" class="portfolio-thumb">
		<?php the_post_thumbnail(); ?>
	</a>
</article>
