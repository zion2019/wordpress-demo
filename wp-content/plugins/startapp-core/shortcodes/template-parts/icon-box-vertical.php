<?php
/**
 * Icon Box
 *
 * Template part for displaying Icon Box shortcode
 * with Vertical layout
 *
 * You can override this template-part by copying it to:
 *     theme-child/template-parts/icon-box-vertical.php
 *     theme/template-parts/icon-box-vertical.php
 *
 * @var array $args Arguments passed to template
 *
 * @author 8guild
 */

?>
<div <?php echo startapp_get_attr( $args['attr'] ); ?>>

	<?php
	if ( $args['is_background'] ) {
		printf( '<span class="icon-box-backdrop" style="background-color: %s;"></span>',
			esc_attr( $args['background_color'] )
		);
	}

	if ( ! empty( $args['icon'] ) ) {
		echo $args['icon'];
	}

	echo startapp_get_text( esc_html( $args['title'] ), '<h3 class="icon-box-title">', '</h3>' );

	if ( ! empty( $args['description'] ) || ! empty( $args['button'] ) ) : ?>
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