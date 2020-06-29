<?php
/**
 * Team
 *
 * Template part for displaying Team: Simple Right layout
 *
 * You can override this template-part by copying it to:
 *     theme-child/template-parts/team-simple-right.php
 *     theme/template-parts/team-simple-right.php
 *
 * @var array $args Arguments passed to template
 *
 * @author 8guild
 */

?>
<div <?php echo startapp_get_attr( $args['attr'] ); ?>>
	<div class="teammate-info-wrap">
		<?php
		// Name
		echo startapp_get_text( $args['name'], '<h3 class="teammate-name">', '</h3>' );

		// Position
		echo startapp_get_text( $args['position'], '<span class="teammate-info">', '</span>' );

		// About
		echo startapp_get_text( $args['about'], '<span class="teammate-info">', '</span>' );

		// Socials
		echo $args['socials'];
		?>
	</div>

	<?php
	// Thumbnail
	echo $args['thumb']; ?>

</div>
