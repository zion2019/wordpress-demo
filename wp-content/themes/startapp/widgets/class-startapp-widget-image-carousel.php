<?php

/**
 * Widget "StartApp Image Carousel"
 *
 * Display the site info
 *
 * @uses WP_Widget
 */
class Startapp_Widget_Image_Carousel extends WP_Widget {
	/**
	 * Widget id_base
	 *
	 * @var string
	 */
	private $widget_id = 'startapp_image_carousel';

	public function __construct() {
		$opts = array( 'description' => esc_html__( 'Display the Image Carousel.', 'startapp' ) );
		parent::__construct( $this->widget_id, esc_html__( 'StartApp Image Carousel', 'startapp' ), $opts );
	}

	/**
	 * Display the widget contents
	 *
	 * @param array $args     Widget args described in {@see register_sidebar()}
	 * @param array $instance Widget settings
	 */
	public function widget( $args, $instance ) {
		$instance = wp_parse_args( (array) $instance, array(
			'title'       => '',
			'desc'        => '',
			'images'      => '',
			'count'       => '4',
			'is_controls' => 'disable',
			'is_autoplay' => 'disable',
			'interval'    => '4000',
		) );

		if ( empty( $instance['images'] ) ) {
			return;
		}

		// enqueue Slick
		wp_enqueue_script( 'slick' );

		$title = apply_filters( 'widget_title', $instance['title'], $instance, $this->id_base );

		$images = explode( ',', $instance['images'] );
		$images = array_filter( $images, 'is_numeric' );
		$images = array_map( 'intval', $images );

		$is_controls = ( 'enable' === $instance['is_controls'] );
		$is_autoplay = ( 'enable' === $instance['is_autoplay'] );

		$slick = array(
			'slidesToShow'  => absint( $instance['count'] ),
			'arrows'        => $is_controls,
			'autoplay'      => $is_autoplay,
			'autoplaySpeed' => absint( $instance['interval'] ),
		);

		echo $args['before_widget'];
		if ( $title ) {
			echo $args['before_title'], $title, $args['after_title'];
		}

		if ( ! empty( $instance['desc'] ) ) {
			$description = nl2br( wp_kses( trim( $instance['desc'] ), $this->allowed_description_html() ) );
			echo '<p class="widget-description">', $description, '</p>';
			unset( $description );
		}

		if ( $is_controls ) {
			?>
			<div class="widget-carousel-navs">
				<button type="button" class="slick-prev slick-arrow"></button>
				<button type="button" class="slick-next slick-arrow"></button>
			</div>
			<?php
		}

		echo '<div ', startapp_get_attr( array( 'class' => 'widget-inner', 'data-slick' => $slick ) ), '>';
		foreach ( $images as $image ) {
			echo '<div class="carousel-item">', wp_get_attachment_image( $image, 'medium' ), '</div>';
		}
		echo '</div>';

		echo $args['after_widget'];
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
			'title'       => '',
			'desc'        => '',
			'images'      => '',
			'count'       => '4',
			'is_controls' => 'enable',
			'is_autoplay' => 'disable',
			'interval'    => '4000',
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
			<label for="<?php echo esc_attr( $this->get_field_id( 'desc' ) ); ?>">
				<?php esc_html_e( 'Description', 'startapp' ); ?>
			</label>
			<textarea class="widefat"
			          name="<?php echo esc_attr( $this->get_field_name( 'desc' ) ); ?>"
			          id="<?php echo esc_attr( $this->get_field_id( 'desc' ) ); ?>"
			><?php echo esc_textarea( $instance['desc'] ); ?></textarea>
		</p>
		<p>
			<label for="<?php echo esc_attr( $this->get_field_id( 'count' ) ); ?>">
				<?php esc_html_e( 'Items Count', 'startapp' ); ?>
			</label>
			<input type="text" class="widefat"
			       id="<?php echo esc_attr( $this->get_field_id( 'count' ) ); ?>"
			       name="<?php echo esc_attr( $this->get_field_name( 'count' ) ); ?>"
			       value="<?php echo absint( $instance['count'] ); ?>">
			<span class="description"><?php esc_html_e( 'Any positive integer number', 'startapp' ); ?></span>
		</p>
		<p>
			<label for="<?php echo esc_attr( $this->get_field_id( 'images' ) ); ?>">
				<?php esc_html_e( 'Images', 'startapp' ); ?>
			</label>
			<?php
			$this->media_control(
				esc_attr( $this->get_field_name( 'images' ) ),
				esc_attr( $this->get_field_id( 'images' ) ),
				$instance['images']
			);
			?>
		</p>
		<p>
			<label for="<?php echo esc_attr( $this->get_field_id( 'is_controls' ) ); ?>">
				<?php esc_html_e( 'Controls', 'startapp' ); ?>
			</label>
			<select class="widefat"
			        name="<?php echo esc_attr( $this->get_field_name( 'is_controls' ) ); ?>"
			        id="<?php echo esc_attr( $this->get_field_id( 'is_controls' ) ); ?>"
			>
				<option value="enable" <?php selected( 'enable', $instance['is_controls'] ); ?>><?php esc_html_e( 'Show', 'startapp' ); ?></option>
				<option value="disable" <?php selected( 'disable', $instance['is_controls'] ); ?>><?php esc_html_e( 'Hide', 'startapp' ); ?></option>
			</select>
		</p>
		<p>
			<label for="<?php echo esc_attr( $this->get_field_id( 'is_autoplay' ) ); ?>">
				<?php esc_html_e( 'Autoplay', 'startapp' ); ?>
			</label>
			<select class="widefat"
			        name="<?php echo esc_attr( $this->get_field_name( 'is_autoplay' ) ); ?>"
			        id="<?php echo esc_attr( $this->get_field_id( 'is_autoplay' ) ); ?>"
			>
				<option value="disable" <?php selected( 'disable', $instance['is_autoplay'] ); ?>><?php esc_html_e( 'Disable', 'startapp' ); ?></option>
				<option value="enable" <?php selected( 'enable', $instance['is_autoplay'] ); ?>><?php esc_html_e( 'Enable', 'startapp' ); ?></option>
			</select>
		</p>
		<p>
			<label for="<?php echo esc_attr( $this->get_field_id( 'interval' ) ); ?>">
				<?php esc_html_e( 'Autoplay Interval', 'startapp' ); ?>
			</label>
			<input type="text" class="widefat"
			       id="<?php echo esc_attr( $this->get_field_id( 'interval' ) ); ?>"
			       name="<?php echo esc_attr( $this->get_field_name( 'interval' ) ); ?>"
			       value="<?php echo absint( $instance['interval'] ); ?>">
		</p>
		<?php

		return true;
	}

	/**
	 * Update widget
	 *
	 * @param array $new_instance New values
	 * @param array $old_instance Old values
	 *
	 * @return array
	 */
	public function update( $new_instance, $old_instance ) {
		$instance = $old_instance;

		$instance['title']       = sanitize_text_field( trim( $new_instance['title'] ) );
		$instance['desc']        = wp_kses( $new_instance['desc'], $this->allowed_description_html() );
		$instance['count']       = absint( $new_instance['count'] );
		$instance['images']      = $this->sanitize_images( $new_instance['images'] );
		$instance['is_controls'] = sanitize_key( $new_instance['is_controls'] );
		$instance['is_autoplay'] = sanitize_key( $new_instance['is_autoplay'] );
		$instance['interval']    = absint( $new_instance['interval'] );

		return $instance;
	}

	/**
	 * Display the media control for adding single or multiple images with preview
	 *
	 * @param string $name        Name of hidden input for storing attachment(s) id(s)
	 * @param string $id          Field ID
	 * @param string $attachments A comma separated list of attachment(s) ID(s)
	 */
	private function media_control( $name, $id, $attachments ) {
		// process attachments, should be a list of IDs, separated by comma or a single ID
		$value  = $attachments;

		// If values present - convert to array!
		if ( ! empty( $attachments ) ) {
			$attachments = explode( ',', $attachments );
			$attachments = array_filter( $attachments, 'is_numeric' );
			$attachments = array_map( 'absint', $attachments );
		}

		// If values not present, whatever convert to empty array
		if ( empty( $attachments ) ) {
			$attachments = array();
		}

		$template = startapp_content_encode('
		<li class="startapp-media-item" data-id="{id}" style="{image}">
			<a href="#" class="startapp-media-remove">&times;</a>
		</li>
		');

		?>
		<div class="startapp-media-wrap startapp-media-multiple" data-sortable="true">
			<input type="hidden" class="startapp-media-value"
			       id="<?php echo esc_attr( $id ); ?>"
			       name="<?php echo esc_attr( $name ); ?>"
			       value="<?php echo esc_attr( $value ); ?>">

			<ul class="startapp-media-items">
				<?php
				foreach ( $attachments as $attachmentID ) :
					$r = array(
						'{id}'    => esc_attr( $attachmentID ),
						'{image}' => startapp_css_background_image( $attachmentID, 'medium' ),
					);

					echo str_replace( array_keys( $r ), array_values( $r ), $template );
					unset( $r );
				endforeach;
				?>

				<li class="startapp-media-control">
					<a href="#" class="startapp-media-add" data-multiple="1">&#43;</a>
				</li>
			</ul>
		</div>
		<?php
	}

	/**
	 * Return the allowed HTML for description field
	 *
	 * @see wp_kses()
	 *
	 * @return array
	 */
	private function allowed_description_html() {
		return array(
			'a'      => array( 'href' => true, 'target' => true, 'rel' => true, 'class' => true ),
			'i'      => array( 'class' => true ),
			'span'   => array( 'class' => true ),
			'em'     => true,
			'strong' => true,
			'br'     => true,
		);
	}

	private function sanitize_images( $images ) {
		$images = explode( ',', $images );
		$images = array_filter( $images, 'is_numeric' );
		$images = array_map( 'absint', $images );

		return implode( ',', $images );
	}
}
