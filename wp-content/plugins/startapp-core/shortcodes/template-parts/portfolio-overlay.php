<?php
/**
 * Portfolio
 *
 * Template part for displaying Portfolio "With Text Overlay"
 *
 * You can override this template-part by copying it to:
 *     theme-child/template-parts/portfolio-overlay.php
 *     theme/template-parts/portfolio-overlay.php
 *
 * @var array $args Arguments passed to template
 *
 * @author 8guild
 */

?>
<article id="post-<?php the_ID(); ?>" <?php post_class( $args['tile_class'] ); ?>>

	<?php
	// overlay
	$color = startapp_color_rgba(
		sanitize_hex_color( $args['color'] ),
		startapp_get_opacity_value( $args['opacity'] )
	);

	startapp_the_tag( 'span', array(
		'class' => 'overlay',
		'style' => startapp_css_background_color( $color ),
	), '' );
	unset( $color );
	?>

	<div class="portfolio-thumb">
		<?php the_post_thumbnail(); ?>
	</div>
	<div class="portfolio-info">
		<?php
		the_title(
			sprintf( '<h3 class="portfolio-title"><a href="%s">', esc_url( get_permalink() ) ),
			'</a></h3>'
		);

		startapp_the_text( $args['description'], '<p class="description">', '</p>' );
		?>

		<a href="<?php the_permalink(); ?>"
		   class="btn btn-sm btn-ghost btn-<?php echo ( $args['skin'] === 'light' ) ? 'light' : 'primary'; ?>">
			<?php esc_html_e( 'View Project', 'startapp' ); ?>
		</a>
	</div>
</article>
