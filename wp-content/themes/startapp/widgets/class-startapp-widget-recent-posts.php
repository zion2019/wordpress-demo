<?php

/**
 * Widget "StartApp Recent Posts"
 *
 * Shows the latest posts in another manner
 *
 * @uses WP_Widget
 */
class Startapp_Widget_Recent_Posts extends WP_Widget {

	/**
	 * Widget id_base
	 *
	 * @var string
	 */
	private $widget_id = 'startapp_recent_posts';

	/**
	 * Widget cache group
	 *
	 * @var string
	 */
	private $group = 'startapp_widgets';

	public function __construct() {
		$opts = array( 'description' => esc_html__( 'Your latest posts in another manner', 'startapp' ) );
		parent::__construct( $this->widget_id, esc_html__( 'StartApp Recent Posts', 'startapp' ), $opts );

		add_action( 'save_post', array( $this, 'flush' ) );
		add_action( 'deleted_post', array( $this, 'flush' ) );
		add_action( 'switch_theme', array( $this, 'flush' ) );
	}

	/**
	 * Show widget
	 *
	 * @param array $args     Widget args described in {@see register_sidebar()}
	 * @param array $instance Widget settings
	 */
	public function widget( $args, $instance ) {
		$cached = false;
		if ( ! $this->is_preview() ) {
			$cached = wp_cache_get( $this->widget_id, $this->group );
		}

		// show cache and exit, if exists
		if ( is_array( $cached ) && array_key_exists( $this->id, $cached ) ) {
			echo startapp_content_decode( $cached[ $this->id ] );

			return;
		}

		// if cache missing convert var to empty array for further usage
		if ( ! is_array( $cached ) ) {
			$cached = array();
		}

		$instance = wp_parse_args( (array) $instance, array(
			'title' => '',
			'count' => 5,
		) );

		$title = apply_filters( 'widget_title', esc_html( $instance['title'] ), $instance, $this->id_base );
		$count = false === (bool) $instance['count'] ? 5 : (int) $instance['count'];

		/**
		 * Filter the argument for querying Recent Posts widget
		 *
		 * @since 1.0.0
		 *
		 * @param array $args An array of arguments for WP_Query
		 */
		$query = new WP_Query( apply_filters( 'startapp_widget_recent_posts_args', array(
			'post_status'         => 'publish',
			'no_found_rows'       => true,
			'posts_per_page'      => $count,
			'ignore_sticky_posts' => true,
		) ) );

		ob_start();

		if ( $query->have_posts() ) :

			echo $args['before_widget'];
			if ( $title ) {
				echo $args['before_title'], $title, $args['after_title'];
			}

			while ( $query->have_posts() ) : $query->the_post(); ?>
				<div class="post-item">

					<?php if ( has_post_thumbnail() ) : ?>
						<a href="<?php the_permalink(); ?>" class="post-item-thumb">
							<?php the_post_thumbnail( 'thumbnail' ); ?>
						</a>
					<?php endif; ?>

					<div class="post-item-info">
						<span class="post-item-date">
							<?php
							/**
							 * This filter allows you to modify the post date format
							 *
							 * @param string $format Date format
							 */
							$format = apply_filters( 'startapp_widget_recent_posts_date_format', 'F j' );
							echo esc_html( get_the_date( $format ) );
							unset( $format );
							?>
						</span>
						<?php the_title(
							sprintf( '<h3 class="post-item-title"><a href="%s" rel="bookmark">', esc_url( get_permalink() ) ),
							'</a></h3>'
						); ?>
					</div>
				</div>
				<?php
			endwhile;

			echo $args['after_widget'];

		endif;
		wp_reset_postdata();

		if ( ! $this->is_preview() ) {
			$cached[ $this->id ] = startapp_content_encode( ob_get_flush() );
			wp_cache_set( $this->widget_id, $cached, $this->group );
		} else {
			ob_end_flush();
		}
	}

	/**
	 * Output the settings update form
	 *
	 * @param array $instance Current settings
	 *
	 * @return bool
	 */
	public function form( $instance ) {
		$instance = wp_parse_args( (array) $instance, array(
			'title' => '',
			'count' => 5,
		) );

		?>
		<p>
			<label for="<?php echo esc_attr( $this->get_field_id( 'title' ) ); ?>">
				<?php echo esc_html_x( 'Title', 'widget title', 'startapp' ); ?>
			</label>
			<input type="text" class="widefat"
			       id="<?php echo esc_attr( $this->get_field_id( 'title' ) ); ?>"
			       name="<?php echo esc_attr( $this->get_field_name( 'title' ) ); ?>"
			       value="<?php echo esc_attr( $instance['title'] ); ?>">
		</p>
		<p>
			<label for="<?php echo esc_attr( $this->get_field_id( 'count' ) ); ?>">
				<?php esc_html_e( 'Number of posts', 'startapp' ); ?>
			</label>
			<input type="number" size="3"
			       id="<?php echo esc_attr( $this->get_field_id( 'count' ) ); ?>"
			       name="<?php echo esc_attr( $this->get_field_name( 'count' ) ); ?>"
			       value="<?php echo esc_attr( $instance['count'] ); ?>">
		</p>
		<?php

		return true;
	}

	/**
	 * Update widget form
	 *
	 * @param array $new_instance New values
	 * @param array $old_instance Old values
	 *
	 * @return array
	 */
	public function update( $new_instance, $old_instance ) {
		$instance = $old_instance;

		$instance['title'] = sanitize_text_field( trim( $new_instance['title'] ) );
		$instance['count'] = absint( $new_instance['count'] );

		// flush cache on update settings
		$this->flush();

		return $instance;
	}

	/**
	 * Delete the widget cache
	 */
	public function flush() {
		wp_cache_delete( $this->widget_id, $this->group );
	}
}
