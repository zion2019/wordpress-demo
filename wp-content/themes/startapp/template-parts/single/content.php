<?php
/**
 * Template part for displaying posts
 *
 * @link   https://codex.wordpress.org/Template_Hierarchy
 *
 * @author 8guild
 */

?>
<article id="post-<?php the_ID(); ?>" <?php post_class(); ?>>

	<div class="container">
		<?php
		startapp_entry_header();
		the_title( '<h1 class="post-title">', '</h1>' );
		if ( has_post_thumbnail() ) : ?>
			<div class="featured-image padding-bottom-1x">
				<?php the_post_thumbnail( 'full', array( 'class' => 'block-center' ) ); ?>
			</div>
		<?php endif; ?>
	</div>

	<?php the_content(); ?>

	<div class="container">
		<?php
		wp_link_pages( array(
			'before' => '<div class="page-links"><span>' . esc_html__( 'Pages:', 'startapp' ),
			'after'  => '</span></div>',
		) );
		startapp_entry_footer();
		startapp_entry_shares();
		startapp_entry_author();
		startapp_entry_related();
		?>
	</div>

</article>
