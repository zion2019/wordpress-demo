<?php

/**
 * Widget "StartApp Author"
 *
 * Display the Author information
 *
 * @uses WP_Widget
 */
class Startapp_Widget_Author extends WP_Widget {

	/**
	 * Widget id_base
	 *
	 * @var string
	 */
	private $widget_id = 'startapp_author';

	/**
	 * Group for cached widgets
	 *
	 * @var string
	 */
	private $group = 'startapp_widgets';

	public function __construct() {
		$widget_opts = array( 'description' => esc_html__( 'Display the information about the author', 'startapp' ) );
		parent::__construct( $this->widget_id, esc_html__( 'StartApp Author', 'startapp' ), $widget_opts );

		add_action( 'profile_update', array( $this, 'flush' ) );
		add_action( 'user_register', array( $this, 'flush' ) );
		add_action( 'switch_theme', array( $this, 'flush' ) );
	}

	/**
	 * Display the widget contents
	 *
	 * @param array $args     Widget args described in {@see register_sidebar()}
	 * @param array $instance Widget settings
	 */
	public function widget( $args, $instance ) {
		$cached = false;
		if ( ! $this->is_preview() ) {
			$cached = wp_cache_get( $this->widget_id, $this->group );
		}

		if ( is_array( $cached ) && array_key_exists( $this->id, $cached ) ) {
			echo startapp_content_decode( $cached[ $this->id ] );

			return;
		}

		if ( ! is_array( $cached ) ) {
			$cached = array();
		}

		$instance = wp_parse_args( (array) $instance, array(
			'title'         => '',
			'author'        => 0,
			'alignment'     => 'left',
			'is_linked'     => 0,
			'socials_type'  => 'border',
			'socials_shape' => 'rounded',
		) );

		$author = get_user_by( 'id', (int) $instance['author'] );
		if ( false === $author ) {
			return;
		}

		// @see inc/user.php
		$meta = wp_parse_args( get_user_meta( $author->ID, 'startapp_additions', true ), array(
			'avatar'  => 0,
			'socials' => '',
		) );

		$title = apply_filters( 'widget_title', $instance['title'], $instance, $this->id_base );

		// avatar
		$avatar = wp_get_attachment_image( (int) $meta['avatar'], 'full' );
		if ( (int) $instance['is_linked'] ) {
			$href   = esc_url( get_author_posts_url( $author->ID, $author->user_nicename ) );
			$avatar = startapp_get_tag( 'a', array( 'href' => $href ), $avatar );

			unset( $href );
		}

		// socials
		$socials = '';
		if ( ! empty( $meta['socials'] ) ) {
			$sh = startapp_shortcode_build( 'startapp_socials', array(
				'socials'   => $this->socials( $meta['socials'] ),
				'type'      => esc_attr( $instance['socials_type'] ),
				'shape'     => esc_attr( $instance['socials_shape'] ),
				'alignment' => 'inline',
			) );

			$socials = startapp_do_shortcode( $sh );
			unset( $sh );
		}

		$class = startapp_get_classes( array(
			'startapp-author',
			'text-' . esc_attr( $instance['alignment'] ),
		) );

		$r = array(
			'{class}'   => esc_attr( $class ),
			'{avatar}'  => $avatar,
			'{about}'   => startapp_get_text( esc_html( $author->description ), '<p>', '</p>' ),
			'{socials}' => $socials,
		);

		ob_start();

		echo $args['before_widget'];
		if ( $title ) {
			echo $args['before_title'], $title, $args['after_title'];
		}

		echo str_replace( array_keys( $r ), array_values( $r ), $this->template() );
		echo $args['after_widget'];

		if ( ! $this->is_preview() ) {
			$cached[ $this->id ] = startapp_content_encode( ob_get_flush() );
			wp_cache_set( $this->widget_id, $cached, $this->group );
		} else {
			ob_end_flush();
		}
	}

	/**
	 * Output the widget settings form
	 *
	 * @param array $instance Current settings
	 *
	 * @return bool
	 */
	public function form( $instance ) {
		$instance = wp_parse_args( (array) $instance, array(
			'title'         => '',
			'author'        => 0,
			'alignment'     => 'left',
			'is_linked'     => 0,
			'socials_type'  => 'border',
			'socials_shape' => 'rounded',
		) );

		?>
		<p>
			<label for="<?php echo esc_attr( $this->get_field_id( 'title' ) ); ?>">
				<?php echo esc_html_x( 'Title', 'widget title', 'startapp' ); ?>
			</label>
			<input type="text" class="widefat"
			       id="<?php echo esc_attr( $this->get_field_id( 'title' ) ); ?>"
			       name="<?php echo esc_attr( $this->get_field_name( 'title' ) ); ?>"
			       value="<?php echo esc_html( trim( $instance['title'] ) ); ?>">
		</p>
		<p>
			<label for="<?php echo esc_attr( $this->get_field_id( 'author' ) ); ?>">
				<?php echo esc_html__( 'Author', 'startapp' ); ?>
			</label>
			<select class="widefat"
			        name="<?php echo esc_attr( $this->get_field_name( 'author' ) ); ?>"
			        id="<?php echo esc_attr( $this->get_field_id( 'author' ) ); ?>">
				<?php
				/** @var WP_User $user */
				foreach ( get_users() as $user ) : ?>
					<option value="<?php echo (int) $user->ID; ?>" <?php selected( $user->ID, $instance['author'] ); ?>><?php echo esc_html( $user->display_name ); ?></option>
				<?php endforeach; ?>
			</select>
		</p>
		<p>
			<label for="<?php echo esc_attr( $this->get_field_id( 'alignment' ) ); ?>">
				<?php echo esc_html__( 'Alignment', 'startapp' ); ?>
			</label>
			<select class="widefat"
			        name="<?php echo esc_attr( $this->get_field_name( 'alignment' ) ); ?>"
			        id="<?php echo esc_attr( $this->get_field_id( 'alignment' ) ); ?>">
				<option value="left" <?php selected( 'left', $instance['alignment'] ); ?>><?php esc_html_e( 'Left', 'startapp' ); ?></option>
				<option value="center" <?php selected( 'center', $instance['alignment'] ); ?>><?php esc_html_e( 'Center', 'startapp' ); ?></option>
				<option value="right" <?php selected( 'right', $instance['alignment'] ); ?>><?php esc_html_e( 'Right', 'startapp' ); ?></option>
			</select>
		</p>
		<p>
			<label for="<?php echo esc_attr( $this->get_field_id( 'is_linked' ) ); ?>">
				<?php echo esc_html__( 'Link to Author Page?', 'startapp' ); ?>
			</label>
			<select class="widefat"
			        name="<?php echo esc_attr( $this->get_field_name( 'is_linked' ) ); ?>"
			        id="<?php echo esc_attr( $this->get_field_id( 'is_linked' ) ); ?>">
				<option value="1" <?php selected( 1, (int) $instance['is_linked'] ); ?>><?php esc_html_e( 'Yes', 'startapp' ); ?></option>
				<option value="0" <?php selected( 0, (int) $instance['is_linked'] ); ?>><?php esc_html_e( 'No', 'startapp' ); ?></option>
			</select>
		</p>
		<p>
			<label for="<?php echo esc_attr( $this->get_field_id( 'socials_type' ) ); ?>">
				<?php echo esc_html__( 'Socials: Type', 'startapp' ); ?>
			</label>
			<select class="widefat"
			        name="<?php echo esc_attr( $this->get_field_name( 'socials_type' ) ); ?>"
			        id="<?php echo esc_attr( $this->get_field_id( 'socials_type' ) ); ?>">
				<option value="border" <?php selected( 'border', $instance['socials_type'] ); ?>><?php esc_html_e( 'Only Border', 'startapp' ); ?></option>
				<option value="solid-bg" <?php selected( 'solid-bg', $instance['socials_type'] ); ?>><?php esc_html_e( 'Solid Background', 'startapp' ); ?></option>
			</select>
		</p>
		<p>
			<label for="<?php echo esc_attr( $this->get_field_id( 'socials_shape' ) ); ?>">
				<?php echo esc_html__( 'Socials: Shape', 'startapp' ); ?>
			</label>
			<select class="widefat"
			        name="<?php echo esc_attr( $this->get_field_name( 'socials_shape' ) ); ?>"
			        id="<?php echo esc_attr( $this->get_field_id( 'socials_shape' ) ); ?>">
				<option value="rounded" <?php selected( 'rounded', $instance['socials_shape'] ); ?>><?php esc_html_e( 'Rounded', 'startapp' ); ?></option>
				<option value="circle" <?php selected( 'circle', $instance['socials_shape'] ); ?>><?php esc_html_e( 'Circle', 'startapp' ); ?></option>
				<option value="square" <?php selected( 'square', $instance['socials_shape'] ); ?>><?php esc_html_e( 'Square', 'startapp' ); ?></option>
			</select>
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

		$instance['title']         = sanitize_text_field( trim( $new_instance['title'] ) );
		$instance['author']        = absint( $new_instance['author'] );
		$instance['alignment']     = esc_attr( $new_instance['alignment'] );
		$instance['is_linked']     = absint( $new_instance['is_linked'] );
		$instance['socials_type']  = esc_attr( $new_instance['socials_type'] );
		$instance['socials_shape'] = esc_attr( $new_instance['socials_shape'] );

		$this->flush();

		return $instance;
	}

	/**
	 * Flush the cache
	 */
	public function flush() {
		wp_cache_delete( $this->widget_id, $this->group );
	}

	/**
	 * Return the template for Author snippet
	 *
	 * @return string
	 */
	protected function template() {
		return '<div class="{class}">{avatar}{about}{socials}</div>';
	}

	/**
	 * Returns socials for "startapp_socials" shortcode in the appropriate format
	 *
	 * @param array $socials A network=>url pairs
	 *
	 * @return string
	 */
	protected function socials( $socials ) {
		$converted = array();
		foreach ( (array) $socials as $network => $url ) {
			$converted[] = array( 'network' => $network, 'url' => $url );
		}
		unset( $network, $url );

		return urlencode( json_encode( $converted ) );
	}
}