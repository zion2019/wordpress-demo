<?php

/**
 * Widget "StartApp Socials"
 *
 * Display the Social Networks
 *
 * @uses WP_Widget
 */
class Startapp_Widget_Socials extends WP_Widget {
	/**
	 * Widget id_base
	 *
	 * @var string
	 */
	private $widget_id = 'startapp_socials';

	public function __construct() {
		$opts = array( 'description' => esc_html__( 'Social Networks', 'startapp' ) );
		parent::__construct( $this->widget_id, esc_html__( 'StartApp Socials', 'startapp' ), $opts );
	}

	/**
	 * Display the widget contents
	 *
	 * @param array $args     Widget args described in {@see register_sidebar()}
	 * @param array $instance Widget settings
	 */
	public function widget( $args, $instance ) {
		$instance = wp_parse_args( (array) $instance, array(
			'title'             => '',
			'socials'           => '',
			'type'              => 'border',
			'shape'             => 'rounded',
			'skin'              => 'dark',
			'alignment'         => 'left',
			'is_tooltips'       => 'disable',
			'is_waves'          => 'disable',
			'tooltips_position' => 'top',
			'waves_color'       => 'light',
			'class'             => '',
		) );

		if ( empty( $instance['socials'] ) ) {
			return;
		}

		$title = apply_filters( 'widget_title', esc_html( $instance['title'] ), $instance, $this->id_base );
		$atts  = $instance;

		$atts['socials'] = $this->socials( $instance['socials'] );
		$shortcode       = startapp_shortcode_build( 'startapp_socials', $atts );

		echo $args['before_widget'];
		if ( $title ) {
			echo $args['before_title'], $title, $args['after_title'];
		}

		echo startapp_do_shortcode( $shortcode );
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
			'title'             => '',
			'socials'           => '',
			'type'              => 'border',
			'shape'             => 'rounded',
			'skin'              => 'dark',
			'alignment'         => 'left',
			'is_tooltips'       => 'disable',
			'is_waves'          => 'disable',
			'tooltips_position' => 'top',
			'waves_color'       => 'light',
			'class'             => '',
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
			<label for="<?php echo esc_attr($this->get_field_id('socials')); ?>">
				<?php esc_html_e( 'Socials', 'startapp' ); ?>
			</label>
			<?php
			$this->socials_control(
				esc_attr( $this->get_field_name( 'socials' ) ),
				$instance['socials'],
				startapp_get_networks()
			);
			?>
		</p>
		<p>
			<label for="<?php echo esc_attr( $this->get_field_id( 'type' ) ); ?>">
				<?php esc_html_e( 'Type', 'startapp' ); ?>
			</label>
			<select class="widefat"
			        name="<?php echo esc_attr( $this->get_field_name( 'type' ) ); ?>"
			        id="<?php echo esc_attr( $this->get_field_id( 'type' ) ); ?>"
			>
				<option value="border" <?php selected( 'border', $instance['type'] ); ?>><?php esc_html_e( 'Border', 'startapp' ); ?></option>
				<option value="solid-bg" <?php selected( 'solid-bg', $instance['type'] ); ?>><?php esc_html_e( 'Solid Background', 'startapp' ); ?></option>
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
				<option value="circle" <?php selected( 'circle', $instance['shape'] ); ?>><?php esc_html_e( 'Circle', 'startapp' ); ?></option>
				<option value="square" <?php selected( 'square', $instance['shape'] ); ?>><?php esc_html_e( 'Square', 'startapp' ); ?></option>
			</select>
		</p>
		<p>
			<label for="<?php echo esc_attr( $this->get_field_id( 'skin' ) ); ?>">
				<?php esc_html_e( 'Skin', 'startapp' ); ?>
			</label>
			<select class="widefat"
			        name="<?php echo esc_attr( $this->get_field_name( 'skin' ) ); ?>"
			        id="<?php echo esc_attr( $this->get_field_id( 'skin' ) ); ?>"
			>
				<option value="dark" <?php selected( 'dark', $instance['skin'] ); ?>><?php esc_html_e( 'Dark', 'startapp' ); ?></option>
				<option value="light" <?php selected( 'light', $instance['skin'] ); ?>><?php esc_html_e( 'Light', 'startapp' ); ?></option>
			</select>
		</p>
		<p>
			<label for="<?php echo esc_attr( $this->get_field_id( 'alignment' ) ); ?>">
				<?php esc_html_e( 'Alignment', 'startapp' ); ?>
			</label>
			<select class="widefat"
			        name="<?php echo esc_attr( $this->get_field_name( 'alignment' ) ); ?>"
			        id="<?php echo esc_attr( $this->get_field_id( 'alignment' ) ); ?>"
			>
				<option value="left" <?php selected( 'left', $instance['alignment'] ); ?>><?php esc_html_e( 'Left', 'startapp' ); ?></option>
				<option value="center" <?php selected( 'center', $instance['alignment'] ); ?>><?php esc_html_e( 'Center', 'startapp' ); ?></option>
				<option value="right" <?php selected( 'right', $instance['alignment'] ); ?>><?php esc_html_e( 'Right', 'startapp' ); ?></option>
			</select>
		</p>
		<p>
			<label for="<?php echo esc_attr( $this->get_field_id( 'is_tooltips' ) ); ?>">
				<?php esc_html_e( 'Tooltips', 'startapp' ); ?>
			</label>
			<select class="widefat"
			        name="<?php echo esc_attr( $this->get_field_name( 'is_tooltips' ) ); ?>"
			        id="<?php echo esc_attr( $this->get_field_id( 'is_tooltips' ) ); ?>"
			>
				<option value="disable" <?php selected( 'disable', $instance['is_tooltips'] ); ?>><?php esc_html_e( 'Disable', 'startapp' ); ?></option>
				<option value="enable" <?php selected( 'enable', $instance['is_tooltips'] ); ?>><?php esc_html_e( 'Enable', 'startapp' ); ?></option>
			</select>
		</p>
		<p>
			<label for="<?php echo esc_attr( $this->get_field_id( 'tooltips_position' ) ); ?>">
				<?php esc_html_e( 'Tooltips Position', 'startapp' ); ?>
			</label>
			<select class="widefat"
			        name="<?php echo esc_attr( $this->get_field_name( 'tooltips_position' ) ); ?>"
			        id="<?php echo esc_attr( $this->get_field_id( 'tooltips_position' ) ); ?>"
			>
				<option value="top" <?php selected( 'top', $instance['tooltips_position'] ); ?>><?php esc_html_e( 'Top', 'startapp' ); ?></option>
				<option value="right" <?php selected( 'right', $instance['tooltips_position'] ); ?>><?php esc_html_e( 'Right', 'startapp' ); ?></option>
				<option value="left" <?php selected( 'left', $instance['tooltips_position'] ); ?>><?php esc_html_e( 'Left', 'startapp' ); ?></option>
				<option value="bottom" <?php selected( 'bottom', $instance['tooltips_position'] ); ?>><?php esc_html_e( 'Bottom', 'startapp' ); ?></option>
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
			<label for="<?php echo esc_attr( $this->get_field_id( 'waves_color' ) ); ?>">
				<?php esc_html_e( 'Waves Color', 'startapp' ); ?>
			</label>
			<select class="widefat"
			        name="<?php echo esc_attr( $this->get_field_name( 'waves_color' ) ); ?>"
			        id="<?php echo esc_attr( $this->get_field_id( 'waves_color' ) ); ?>"
			>
				<option value="light" <?php selected( 'light', $instance['waves_color'] ); ?>><?php esc_html_e( 'Light', 'startapp' ); ?></option>
				<option value="dark" <?php selected( 'dark', $instance['waves_color'] ); ?>><?php esc_html_e( 'Dark', 'startapp' ); ?></option>
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

		$instance['title']             = sanitize_text_field( $new_instance['title'] );
		$instance['socials']           = $this->sanitize( $new_instance['socials'] );
		$instance['type']              = sanitize_key( $new_instance['type'] );
		$instance['shape']             = sanitize_key( $new_instance['shape'] );
		$instance['skin']              = sanitize_key( $new_instance['skin'] );
		$instance['alignment']         = sanitize_key( $new_instance['alignment'] );
		$instance['is_tooltips']       = sanitize_key( $new_instance['is_tooltips'] );
		$instance['is_waves']          = sanitize_key( $new_instance['is_waves'] );
		$instance['tooltips_position'] = sanitize_key( $new_instance['tooltips_position'] );
		$instance['waves_color']       = sanitize_key( $new_instance['waves_color'] );
		$instance['class']             = esc_attr( $new_instance['class'] );

		return $instance;
	}

	/**
	 * Render socials control. With networks or empty.
	 * Allow users to choose social networks from the list an fill in the links.
	 *
	 * @param string $name     Name of field, in context {$name}[networks][]
	 * @param array  $socials  List of networks from socials.ini
	 * @param array  $networks List of networks selected by user
	 */
	protected function socials_control( $name, $socials = array(), $networks = array() ) {
		if ( empty( $networks ) ) {
			return;
		}

		echo '<div class="startapp-repeated-fields-wrap">';

		if ( is_array( $socials ) && count( $socials ) > 0 ) {
			$this->render_list( $name, $networks, $socials );
		} else {
			$this->render_empty( $name, $networks );
		}

		echo '</div>';
		echo '<br>';
		echo '<a href="#" class="startapp-repeat">', esc_html__( 'Add one more', 'startapp' ), '</a>';
	}

	/**
	 * Render filled list of social networks with controls
	 *
	 * @access private
	 *
	 * @param string $name     Meta box name
	 * @param array  $networks List of networks from social-networks.ini
	 * @param array  $socials  List of networks selected by user
	 */
	private function render_list( $name, $networks, $socials = array() ) {
		$select_name = esc_attr( "{$name}[networks][]" );
		$input_name  = esc_attr( "{$name}[urls][]" );
		$template    = $this->template();

		foreach ( $socials as $social => $url ) {
			$options = '';
			foreach ( $networks as $network => $data ) {
				$selected = ( $network === $social ) ? 'selected' : '';
				$options .= sprintf( '<option value="%1$s" %3$s>%2$s</option>',
					esc_attr( $network ), esc_html( $data['name'] ), $selected
				);
			}
			unset( $network, $data, $selected );

			$r = array(
				'{select-name}' => $select_name,
				'{input-name}'  => $input_name,
				'{options}'     => $options,
				'{value}'       => preg_match( '@^https?://@i', $url ) ? esc_url( $url ) : esc_attr( $url ),
			);

			echo str_replace( array_keys( $r ), array_values( $r ), $template );
		}
		unset( $social, $url );
	}


	/**
	 * Render empty list of social networks (controls for single network)
	 *
	 * @access private
	 *
	 * @param string $name     Meta box name
	 * @param array  $networks List of networks from social-networks.ini
	 */
	private function render_empty( $name, $networks ) {
		$select_name = esc_attr( "{$name}[networks][]" );
		$input_name  = esc_attr( "{$name}[urls][]" );

		// prepare options dropdown
		$options = '';
		foreach( $networks as $network => $data ) {
			$options .= sprintf( '<option value="%1$s">%2$s</option>',
				esc_attr( $network ), esc_html( $data['name'] )
			);
		}
		unset( $network, $data );

		$r = [
			'{select-name}' => $select_name,
			'{input-name}'  => $input_name,
			'{options}'     => $options,
			'{value}'       => '',
		];

		echo str_replace( array_keys( $r ), array_values( $r ), $this->template() );
	}

	private function template() {
		$template = '
		<div class="startapp-repeated-group">
			<select name="{select-name}">{options}</select>
			<input type="text" name="{input-name}" value="{value}">
			<a href="#" class="startapp-unrepeat">&times;</a>
		</div>
		';

		return startapp_content_encode( $template );
	}


	/**
	 * Convert input array of user social networks to more suitable format.
	 *
	 * @param array $socials Expected multidimensional array with two keys [networks] and [urls],
	 *                       both contains equal number of elements.
	 *
	 * <code>
	 * [
	 *   networks => array( facebook, twitter, ... ),
	 *   urls     => array( url1, url2, ... ),
	 * ];
	 * </code>
	 *
	 *
	 * @return array New format of input array
	 *
	 * <code>
	 * [
	 *   network  => url,
	 *   facebook => url,
	 *   twitter  => url
	 * ];
	 * </code>
	 */
	private function sanitize( $socials ) {
		if ( empty( $socials ) ) {
			return array();
		}

		// Return empty if networks or url not provided.
		if ( empty( $socials['networks'] ) || empty( $socials['urls'] ) ) {
			return array();
		}

		$combined = array_combine( $socials['networks'], $socials['urls'] );
		$result   = array();
		foreach ( $combined as $network => $url ) {
			// skip if $url not provided
			if ( empty( $url ) ) {
				continue;
			}

			if ( 'email' === $network ) {
				$result[ $network ] = 'mailto:' . $this->sanitize_email( $url );
			} else {
				$result[ $network ] = esc_url_raw( $url );
			}
		}
		unset( $combined, $network, $url );

		return $result;
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

	/**
	 * A simple wrapper for {@see sanitize_email()}
	 *
	 * This function will remove "mailto:" prefix
	 *
	 * @param string $email Email
	 *
	 * @return string
	 */
	private function sanitize_email( $email ) {
		if ( false !== strpos( $email, 'mailto:' ) ) {
			$email = str_replace( 'mailto:', '', $email );
		}

		return sanitize_email( $email );
	}
}