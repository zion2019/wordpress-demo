<?php
/**
 * Template part for displaying the "2 + 1 Columns" footer
 *
 * @author 8guild
 */

if ( startapp_is_active_sidebars( array(
	'footer-column-1',
	'footer-column-2',
) ) ) :
	?>
	<div class="footer-row">
		<div class="<?php startapp_footer_fullwidth_class(); ?>">
			<div class="row">
				<div class="col-sm-6">
					<?php dynamic_sidebar( 'footer-column-1' ); ?>
				</div>
				<div class="col-sm-6">
					<?php dynamic_sidebar( 'footer-column-2' ); ?>
				</div>
			</div>
		</div>
	</div>
	<?php
endif;

if ( is_active_sidebar( 'footer-column-3' ) ) :
	?>
	<div class="footer-row second-row">
		<div class="<?php startapp_footer_fullwidth_class(); ?>">
			<?php dynamic_sidebar( 'footer-column-3' ); ?>
		</div>
	</div>
	<?php
endif;
