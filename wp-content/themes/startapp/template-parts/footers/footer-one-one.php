<?php
/**
 * Template part for displaying the "1 + 1 Columns" footer
 *
 * @author 8guild
 */

if ( is_active_sidebar( 'footer-column-1' ) ) :
	?>
	<div class="footer-row">
		<div class="<?php startapp_footer_fullwidth_class(); ?>">
			<?php dynamic_sidebar( 'footer-column-1' ); ?>
		</div>
	</div>
	<?php
endif;

if ( is_active_sidebar( 'footer-column-2' ) ) :
	?>
	<div class="footer-row second-row">
		<div class="<?php startapp_footer_fullwidth_class(); ?>">
			<?php dynamic_sidebar( 'footer-column-2' ); ?>
		</div>
	</div>
	<?php
endif;
