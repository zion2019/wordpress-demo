<?php
/**
 * Template part for displaying Classes layout
 *
 * You can override this template-part by copying it to:
 *     theme-child/template-parts/classes-tile.php
 *     theme/template-parts/classes-tile.php
 *
 * @var array $args Arguments passed to template
 *
 * @author 8guild
 */

?>

<div <?php echo startapp_get_attr( $args['attr'] ); ?>>

	<?php if ( has_post_thumbnail() ) : ?>
		<div class="img-box">
			<div class="overlay"></div>
			<?php the_post_thumbnail(); ?>
			<div class="hero-content">
				<a href="<?php the_permalink(); ?>"
				   class="btn btn-transparent btn-light btn-pill"><?php esc_html_e( 'Apply Now', 'startapp' ); ?></a>
			</div>
		</div>
	<?php endif; ?>

	<header class="classes-tile-header">
		<div class="date-time">

			<?php if ( ! empty( $args['date'] ) ) : ?>
				<div class="date">
					<i class="material-icons date_range"></i>
					<?php echo esc_html( $args['date'] ); ?>
				</div>
			<?php endif; ?>

			<?php if ( ! empty( $args['time'] ) ) : ?>
			<div class="time">
				<i class="material-icons access_time"></i>
				<?php echo esc_html( $args['time'] ); ?>
			</div>
			<?php endif; ?>

		</div>

		<?php if ( ! empty( $args['author_link'] ) ) : ?>
			<a href="<?php echo esc_url( $args['author_link'] ); ?>" class="author">
				<div class="name">
					<?php
					echo startapp_get_text( esc_html( $args['author_name'] ), '<span>', '</span>' );
					echo startapp_get_text( esc_html( $args['author_surname'] ), '<span>', '</span>' );
					?>
				</div>
				<div class="ava">
					<?php echo wp_get_attachment_image( (int) $args['author_avatar'] ); ?>
				</div>
			</a>
		<?php else : ?>
			<div class="author">
				<div class="name">
					<?php
					echo startapp_get_text( esc_html( $args['author_name'] ), '<span>', '</span>' );
					echo startapp_get_text( esc_html( $args['author_surname'] ), '<span>', '</span>' );
					?>
				</div>
				<div class="ava">
					<?php echo wp_get_attachment_image( (int) $args['author_avatar'] ); ?>
				</div>
			</div>
		<?php endif; ?>

	</header>

	<div class="classes-tile-content">
		<?php
		the_title(
			sprintf( '<h3 class="title"><a href="%s">', esc_url( get_the_permalink() ) ),
			'</a></h3>'
		);

		startapp_the_text( esc_html( $args['subtitle'] ), '<div class="subtitle">', '</div>' );
		startapp_the_text( esc_html( $args['description'] ), '<p class="text">', '</p>' );
		?>
	</div>

	<footer class="classes-tile-footer">
		<div class="tickets-left">
			<?php echo startapp_get_text( esc_html( $args['seats'] ), '<i class="material-icons person"></i>' ); ?>
		</div>

		<div class="badge-cont">
			<?php echo startapp_get_text( $args['label'] ); ?>
		</div>
	</footer>
</div>
