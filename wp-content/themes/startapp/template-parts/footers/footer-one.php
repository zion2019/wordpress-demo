<?php
/**
 * Template part for displaying the "1 Column" footer
 *
 * @author 8guild
 */

if ( ! is_active_sidebar( 'footer-column-1' ) ) {
	return;
}

?>
<div class="footer-row">
	<div class="<?php startapp_footer_fullwidth_class(); ?>">
		<?php dynamic_sidebar( 'footer-column-1' ); ?>
	</div>
</div>
