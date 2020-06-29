<?php
/**
 * Template part for displaying Portfolio "With Text and Button"
 *
 * You can override this template-part by copying it to:
 *     theme-child/template-parts/portfolio-text-button.php
 *     theme/template-parts/portfolio-text-button.php
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
	<div class="portfolio-info">
		<?php
		the_title(
			sprintf( '<h3 class="portfolio-title"><a href="%s">', esc_url( get_permalink() ) ),
			'</a></h3>'
		);

		startapp_the_text( $args['description'], '<p class="description">', '</p>' );
		?>
		<a href="<?php the_permalink(); ?>" class="btn btn-sm btn-primary btn-ghost">
			<?php esc_html_e( 'View Project', 'startapp' ); ?>
		</a>
	</div>
</article>
