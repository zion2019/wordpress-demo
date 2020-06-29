<?php
/**
 * Pricing Plan
 *
 * Template part for displaying Pricing Plan: Version 3
 *
 * You can override this template-part by copying it to:
 *     theme-child/template-parts/pricing-v3.php
 *     theme/template-parts/pricing-v3.php
 *
 * @var array $args Arguments passed to template
 *
 * @author 8guild
 */

?>
<div <?php echo startapp_get_attr( $args['attr'] ); ?>>
	<div class="<?php echo esc_attr( $args['class'] ); ?>">
		<div class="inner">
			<?php

			// image
			if ( ! empty( $args['image'] ) ) {
				echo $args['image'];
			}

			// name & description
			startapp_the_text( esc_html( $args['name'] ), '<h3 class="pricing-plan-name">', '</h3>' );
			startapp_the_text( esc_html( $args['description'] ), '<span class="pricing-plan-description">', '</span>' );

			// badge
			if ( ! empty( $args['badge'] ) ) : ?>
				<div class="pricing-plan-badge-wrap">
					<?php echo $args['badge']; ?>
				</div>
				<?php
			endif;

			// features
			if ( ! empty( $args['features'] ) ) : ?>
				<div class="pricing-plan-features">
					<?php echo $args['features']; ?>
				</div>
			<?php endif; ?>
			<hr>
			<?php

			// price & label
			startapp_the_text( esc_html( $args['price'] ), '<h4 class="pricing-plan-price padding-top-1x">', '</h4>' );
			startapp_the_text( esc_html( $args['label'] ), '<span class="text-gray">/ ', '</span>' );

			// button
			if ( ! empty( $args['button'] ) ) : ?>
				<div class="padding-top-1x">
					<?php echo $args['button']; ?>
				</div>
			<?php endif; ?>
		</div>
	</div>
</div>