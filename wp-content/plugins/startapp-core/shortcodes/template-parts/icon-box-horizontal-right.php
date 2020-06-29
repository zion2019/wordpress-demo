<?php
/**
 * Icon Box
 *
 * Template part for displaying Icon Box shortcode
 * with Horizontal layout and right icon position
 *
 * You can override this template-part by copying it to:
 *     theme-child/template-parts/icon-box-horizontal-right.php
 *     theme/template-parts/icon-box-horizontal-right.php
 *
 * @var array $args Arguments passed to template
 *
 * @author 8guild
 */

?>
<div <?php echo startapp_get_attr( $args['attr'] ); ?>>
	<div class="icon-box-info-wrap">
		<?php echo startapp_get_text( esc_html( $args['title'] ), '<h3 class="icon-box-title">', '</h3>' ); ?>
		<?php if ( ! empty( $args['description'] ) || ! empty( $args['button'] ) ) : ?>
			<div class="icon-box-description">
				<?php
				if ( ! empty( $args['description'] ) ) {
					echo startapp_do_shortcode( $args['description'], true );
				}

				if ( ! empty( $args['button'] ) ) {
					echo startapp_do_shortcode( $args['button'] );
				}
				?>
			</div>
		<?php endif; ?>
	</div>
	<?php if ( ! empty( $args['icon'] ) ) : echo $args['icon']; endif; ?>
</div>
