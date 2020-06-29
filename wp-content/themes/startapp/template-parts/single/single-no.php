<?php
/**
 * Template part for displaying the "No Sidebar" layout for Single Post
 *
 * @author 8guild
 */
?>
<div class="padding-top-3x">
	<?php get_template_part( 'template-parts/single/content' ); ?>
</div>

<?php
// If comments are open or we have at least one comment,
// load up the comment template.
if ( comments_open() || get_comments_number() ) : ?>
	<div class="container padding-bottom-3x">
		<?php comments_template(); ?>
	</div>
<?php endif;
