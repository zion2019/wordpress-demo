<?php

/**
 * Widget "StartApp Button"
 *
 * Display the simple button with description
 *
 * @uses WP_Widget
 */
class Startapp_Widget_Button extends WP_Widget {
	/**
	 * Widget id_base
	 *
	 * @var string
	 */
	private $widget_id = 'startapp_button';

	public function __construct() {
		$opts = array( 'description' => esc_html__( 'The custom button.', 'startapp' ) );
		parent::__construct( $this->widget_id, esc_html__( 'StartApp Button', 'startapp' ), $opts );
	}

	/**
	 * Display the widget contents
	 *
	 * @param array $args     Widget args described in {@see register_sidebar()}
	 * @param array $instance Widget settings
	 */
	public function widget( $args, $instance ) {
		$button_atts = array(
			'text'          => '',
			'link'          => '',
			'target'        => 'off',
			'rel'           => '',
			'type'          => 'solid',
			'shape'         => 'rounded',
			'color'         => 'default',
			'size'          => 'default',
			'is_icon'       => 'disable',
			'icon_library'  => 'material',
			'icon_material' => '',
			'icon_position' => 'left',
			'is_waves'      => 'disable',
			'waves_skin'    => 'light',
			'class'         => '',
		);

		$defaults = array(
			'title'       => '',
			'description' => '',
		);

		$instance = wp_parse_args( (array) $instance, $defaults + $button_atts );
		if ( empty( $instance['link'] ) ) {
			return;
		}

		$title = apply_filters( 'widget_title', $instance['title'], $instance, $this->id_base );

		// Get button atts
		$atts = array();
		foreach ( $button_atts as $name => $default ) {
			if ( array_key_exists( $name, $instance ) ) {
				$atts[ $name ] = $instance[ $name ];
			} else {
				$atts[ $name ] = $default;
			}
		}

		$atts['is_full'] = 'yes'; // button always full-width
		if ( 'on' === $instance['target'] ) {
			$atts['link'] = startapp_vc_build_link( array(
				'url'    => esc_url( $instance['link'] ),
				'target' => '_blank',
				'rel'    => 'noopener noreferrer'
			) );
		} else {
			$atts['link'] = startapp_vc_build_link( array( 'url' => esc_url( $instance['link'] ) ) );
		}

		$shortcode = startapp_shortcode_build( 'startapp_button', $atts );

		echo $args['before_widget'];
		if ( $title ) {
			echo $args['before_title'], $title, $args['after_title'];
		}

		echo startapp_do_shortcode( $shortcode );
		echo '<span>' . nl2br( wp_kses( trim( $instance['description'] ) . '</span>', $this->allowed_description_html() ) );
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
			'title'         => '',
			'description'   => '',
			'text'          => '',
			'link'          => '',
			'target'        => '',
			'type'          => 'solid',
			'shape'         => 'rounded',
			'color'         => 'default',
			'size'          => 'default',
			'is_icon'       => 'disable',
			'icon_material' => '',
			'icon_position' => 'left',
			'is_waves'      => 'disable',
			'waves_skin'    => 'light',
			'class'         => '',
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
			<label for="<?php echo esc_attr( $this->get_field_id( 'description' ) ); ?>">
				<?php esc_html_e( 'Description', 'startapp' ); ?>
			</label>
			<textarea class="widefat"
			          name="<?php echo esc_attr( $this->get_field_name( 'description' ) ); ?>"
			          id="<?php echo esc_attr( $this->get_field_id( 'description' ) ); ?>"
			><?php echo esc_textarea( $instance['description'] ); ?></textarea>
		</p>
		<p>
			<label for="<?php echo esc_attr( $this->get_field_id( 'text' ) ); ?>">
				<?php esc_html_e( 'Text', 'startapp' ); ?>
			</label>
			<input type="text" class="widefat"
			       id="<?php echo esc_attr( $this->get_field_id( 'text' ) ); ?>"
			       name="<?php echo esc_attr( $this->get_field_name( 'text' ) ); ?>"
			       value="<?php echo esc_html( trim( $instance['text'] ) ); ?>">
			<span class="description"><?php esc_html_e( 'Text on the button.', 'startapp' ); ?></span>
		</p>
		<p>
			<label for="<?php echo esc_attr( $this->get_field_id( 'link' ) ); ?>">
				<?php esc_html_e( 'Link', 'startapp' ); ?>
			</label>
			<input type="text" class="widefat"
			       id="<?php echo esc_attr( $this->get_field_id( 'link' ) ); ?>"
			       name="<?php echo esc_attr( $this->get_field_name( 'link' ) ); ?>"
			       value="<?php echo esc_html( trim( $instance['link'] ) ); ?>">
			<span class="description"><?php esc_html_e( 'Enter the button link here.', 'startapp' ); ?></span>
		</p>
		<p>
			<input class="checkbox" type="checkbox" <?php checked( $instance['target'], 'on' ); ?> id="<?php echo $this->get_field_id( 'target' ); ?>" name="<?php echo $this->get_field_name( 'target' ); ?>" />
			<label for="<?php echo $this->get_field_id( 'target' ); ?>"><?php esc_html_e( 'Open link in a new tab', 'startapp' ); ?></label>
		</p>
		<p>
			<label for="<?php echo esc_attr( $this->get_field_id( 'type' ) ); ?>">
				<?php esc_html_e( 'Type', 'startapp' ); ?>
			</label>
			<select class="widefat"
			        name="<?php echo esc_attr( $this->get_field_name( 'type' ) ); ?>"
			        id="<?php echo esc_attr( $this->get_field_id( 'type' ) ); ?>"
			>
				<option value="solid" <?php selected( 'solid', $instance['type'] ); ?>><?php esc_html_e( 'Solid', 'startapp' ); ?></option>
				<option value="ghost" <?php selected( 'ghost', $instance['type'] ); ?>><?php esc_html_e( 'Ghost', 'startapp' ); ?></option>
				<option value="3d" <?php selected( '3d', $instance['type'] ); ?>><?php esc_html_e( '3D', 'startapp' ); ?></option>
				<option value="transparent" <?php selected( 'transparent', $instance['type'] ); ?>><?php esc_html_e( 'Transparent', 'startapp' ); ?></option>
				<option value="link" <?php selected( 'link', $instance['type'] ); ?>><?php esc_html_e( 'Link', 'startapp' ); ?></option>
			</select>
		</p>
		<p>
			<label for="<?php echo esc_attr( $this->get_field_id( 'shape' ) ); ?>">
				<?php esc_html_e( 'Shape', 'startapp' ); ?>
			</label>
			<select class="widefat"
			        name="<?php echo esc_attr( $this->get_field_name( 'shape' ) ); ?>"
			        id="<?php echo esc_attr( $this->get_field_id( 'shape' ) ); ?>"
			>
				<option value="rounded" <?php selected( 'rounded', $instance['shape'] ); ?>><?php esc_html_e( 'Rounded', 'startapp' ); ?></option>
				<option value="square" <?php selected( 'square', $instance['shape'] ); ?>><?php esc_html_e( 'Square', 'startapp' ); ?></option>
				<option value="pill" <?php selected( 'pill', $instance['shape'] ); ?>><?php esc_html_e( 'Pill', 'startapp' ); ?></option>
			</select>
		</p>
		<p>
			<label for="<?php echo esc_attr( $this->get_field_id( 'color' ) ); ?>">
				<?php esc_html_e( 'Color', 'startapp' ); ?>
			</label>
			<select class="widefat"
			        name="<?php echo esc_attr( $this->get_field_name( 'color' ) ); ?>"
			        id="<?php echo esc_attr( $this->get_field_id( 'color' ) ); ?>"
			>
				<option value="default" <?php selected( 'default', $instance['color'] ); ?>><?php esc_html_e( 'Default', 'startapp' ); ?></option>
				<option value="primary" <?php selected( 'primary', $instance['color'] ); ?>><?php esc_html_e( 'Primary', 'startapp' ); ?></option>
				<option value="success" <?php selected( 'success', $instance['color'] ); ?>><?php esc_html_e( 'Success', 'startapp' ); ?></option>
				<option value="info" <?php selected( 'info', $instance['color'] ); ?>><?php esc_html_e( 'Info', 'startapp' ); ?></option>
				<option value="warning" <?php selected( 'warning', $instance['color'] ); ?>><?php esc_html_e( 'Warning', 'startapp' ); ?></option>
				<option value="danger" <?php selected( 'danger', $instance['color'] ); ?>><?php esc_html_e( 'Danger', 'startapp' ); ?></option>
				<option value="light" <?php selected( 'light', $instance['color'] ); ?>><?php esc_html_e( 'Light', 'startapp' ); ?></option>
			</select>
		</p>
		<p>
			<label for="<?php echo esc_attr( $this->get_field_id( 'size' ) ); ?>">
				<?php esc_html_e( 'Size', 'startapp' ); ?>
			</label>
			<select class="widefat"
			        name="<?php echo esc_attr( $this->get_field_name( 'size' ) ); ?>"
			        id="<?php echo esc_attr( $this->get_field_id( 'size' ) ); ?>"
			>
				<option value="lg" <?php selected( 'lg', $instance['size'] ); ?>><?php esc_html_e( 'Large', 'startapp' ); ?></option>
				<option value="default" <?php selected( 'default', $instance['size'] ); ?>><?php esc_html_e( 'Normal', 'startapp' ); ?></option>
				<option value="sm" <?php selected( 'sm', $instance['size'] ); ?>><?php esc_html_e( 'Small', 'startapp' ); ?></option>
				<option value="xs" <?php selected( 'xs', $instance['size'] ); ?>><?php esc_html_e( 'Extra Small', 'startapp' ); ?></option>
			</select>
		</p>
		<p>
			<label for="<?php echo esc_attr( $this->get_field_id( 'is_icon' ) ); ?>">
				<?php esc_html_e( 'Enable / Disable Icon', 'startapp' ); ?>
			</label>
			<select class="widefat"
			        name="<?php echo esc_attr( $this->get_field_name( 'is_icon' ) ); ?>"
			        id="<?php echo esc_attr( $this->get_field_id( 'is_icon' ) ); ?>"
			>
				<option value="disable" <?php selected( 'disable', $instance['is_icon'] ); ?>><?php esc_html_e( 'Disable', 'startapp' ); ?></option>
				<option value="enable" <?php selected( 'enable', $instance['is_icon'] ); ?>><?php esc_html_e( 'Enable', 'startapp' ); ?></option>
			</select>
		</p>
		<p>
			<label for="<?php echo esc_attr( $this->get_field_id( 'icon_material' ) ); ?>">
				<?php esc_html_e( 'Icon', 'startapp' ); ?>
			</label>
			<input type="text" class="widefat"
			       id="<?php echo esc_attr( $this->get_field_id( 'icon_material' ) ); ?>"
			       name="<?php echo esc_attr( $this->get_field_name( 'icon_material' ) ); ?>"
			       value="<?php echo esc_attr( trim( $instance['icon_material'] ) ); ?>">
		</p>
		<p class="description"><?php
			echo wp_kses( __( 'You can use icons from the <a href="https://design.google.com/icons/" target="_blank">Material Icons</a> pack. For example, <code>material-icons face</code> or <code>material-icons mail_outline</code>.', 'startapp' ), array(
				'a'    => array( 'href' => true, 'target' => true ),
				'code' => true,
			) ); ?>
		</p>
		<p>
			<label for="<?php echo esc_attr( $this->get_field_id( 'icon_position' ) ); ?>">
				<?php esc_html_e( 'Icon Position', 'startapp' ); ?>
			</label>
			<select class="widefat"
			        name="<?php echo esc_attr( $this->get_field_name( 'icon_position' ) ); ?>"
			        id="<?php echo esc_attr( $this->get_field_id( 'icon_position' ) ); ?>"
			>
				<option value="left" <?php selected( 'left', $instance['icon_position'] ); ?>><?php esc_html_e( 'Left', 'startapp' ); ?></option>
				<option value="right" <?php selected( 'right', $instance['icon_position'] ); ?>><?php esc_html_e( 'Right', 'startapp' ); ?></option>
			</select>
		</p>
		<p>
			<label for="<?php echo esc_attr( $this->get_field_id( 'is_waves' ) ); ?>">
				<?php esc_html_e( 'Waves', 'startapp' ); ?>
			</label>
			<select class="widefat"
			        name="<?php echo esc_attr( $this->get_field_name( 'is_waves' ) ); ?>"
			        id="<?php echo esc_attr( $this->get_field_id( 'is_waves' ) ); ?>"
			>
				<option value="disable" <?php selected( 'disable', $instance['is_waves'] ); ?>><?php esc_html_e( 'Disable', 'startapp' ); ?></option>
				<option value="enable" <?php selected( 'enable', $instance['is_waves'] ); ?>><?php esc_html_e( 'Enable', 'startapp' ); ?></option>
			</select>
		</p>
		<p>
			<label for="<?php echo esc_attr( $this->get_field_id( 'waves_skin' ) ); ?>">
				<?php esc_html_e( 'Waves Color', 'startapp' ); ?>
			</label>
			<select class="widefat"
			        name="<?php echo esc_attr( $this->get_field_name( 'waves_skin' ) ); ?>"
			        id="<?php echo esc_attr( $this->get_field_id( 'waves_skin' ) ); ?>"
			>
				<option value="light" <?php selected( 'light', $instance['waves_skin'] ); ?>><?php esc_html_e( 'Light', 'startapp' ); ?></option>
				<option value="dark" <?php selected( 'dark', $instance['waves_skin'] ); ?>><?php esc_html_e( 'Dark', 'startapp' ); ?></option>
			</select>
		</p>
		<p>
			<label for="<?php echo esc_attr( $this->get_field_id( 'class' ) ); ?>">
				<?php esc_html_e( 'Custom class', 'startapp' ); ?>
			</label>
			<input type="text" class="widefat"
			       id="<?php echo esc_attr( $this->get_field_id( 'class' ) ); ?>"
			       name="<?php echo esc_attr( $this->get_field_name( 'class' ) ); ?>"
			       value="<?php echo esc_html( trim( $instance['class'] ) ); ?>">
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

		$instance['title']         = sanitize_text_field( trim( $new_instance['title'] ) );
		$instance['description']   = wp_kses( $new_instance['description'], $this->allowed_description_html() );
		$instance['text']          = sanitize_text_field( trim( $new_instance['text'] ) );
		$instance['link']          = esc_url_raw( trim( $new_instance['link'] ) );
		$instance['target']        = esc_attr( $new_instance['target'] );
		$instance['type']          = sanitize_key( $new_instance['type'] );
		$instance['shape']         = sanitize_key( $new_instance['shape'] );
		$instance['color']         = sanitize_key( $new_instance['color'] );
		$instance['size']          = sanitize_key( $new_instance['size'] );
		$instance['is_icon']       = sanitize_key( $new_instance['is_icon'] );
		$instance['icon_material'] = esc_attr( trim( $new_instance['icon_material'] ) );
		$instance['icon_position'] = sanitize_key( $new_instance['icon_position'] );
		$instance['is_waves']      = sanitize_key( $new_instance['is_waves'] );
		$instance['waves_skin']    = sanitize_key( $new_instance['waves_skin'] );
		$instance['class']         = esc_attr( $new_instance['class'] );

		return $instance;
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
}
