<?php
/**
 * The template for displaying 404 pages (not found).
 *
 * @link    https://codex.wordpress.org/Creating_an_Error_404_Page
 *
 * @package Startapp
 */

get_header();

?>
<div class="container text-center padding-bottom-3x">
	<h3><?php esc_html_e( 'Oops! That page can&rsquo;t be found.', 'startapp' ); ?></h3>
	<p class="padding-bottom-1x"><?php esc_html_e( 'It looks like nothing was found at this location. Maybe try search or go to home page?', 'startapp' ); ?></p>
	<div class="row">
		<div class="col-lg-8 col-md-10 col-lg-offset-2 col-md-offset-1">
			<?php get_search_form(); ?>
			<a href="<?php echo esc_url( home_url( '/' ) ); ?>" class="btn btn-primary waves-effect waves-light">
				<i class="material-icons home"></i>
				<?php esc_html_e( 'Go To Home', 'startapp' ) ?>
			</a>
		</div>
	</div>
</div>
<?php

get_footer();
