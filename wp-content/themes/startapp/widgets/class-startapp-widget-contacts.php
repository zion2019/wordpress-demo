<?php

/**
 * Widget "StartApp Contacts"
 *
 * Display the contacts
 *
 * @uses WP_Widget
 */
class Startapp_Widget_Contacts extends WP_Widget {
	/**
	 * Widget id_base
	 *
	 * @var string
	 */
	private $widget_id = 'startapp_contacts';

	public function __construct() {
		$opts = array( 'description' => esc_html__( 'Display the contacts.', 'startapp' ) );
		parent::__construct( $this->widget_id, esc_html__( 'StartApp Contacts', 'startapp' ), $opts );
	}

	/**
	 * Display the widget contents
	 *
	 * @param array $args     Widget args described in {@see register_sidebar()}
	 * @param array $instance Widget settings
	 */
	public function widget( $args, $instance ) {
		$instance = wp_parse_args( (array) $instance, array(
			'title'    => '',
			'contacts' => '',
		) );

		if ( empty( $instance['contacts'] ) ) {
			return;
		}

		$title    = apply_filters( 'widget_title', esc_html( $instance['title'] ), $instance, $this->id_base );
		$template = startapp_content_encode( '
		<div class="contact-item">
			<div class="contact-icon"><i class="{icon}"></i></div>
			<div class="contact-info">{info}</div>
		</div>' );

		echo $args['before_widget'];
		if ( $title ) {
			echo $args['before_title'], $title, $args['after_title'];
		}

		foreach ( (array) json_decode( $instance['contacts'], true ) as $contact ) {
			if ( empty( $contact['data'] ) ) {
				continue;
			}

			$r = array(
				'{icon}' => $this->get_icon( $contact['type'] ),
				'{info}' => $this->get_info( $contact['data'] ),
			);

			echo str_replace( array_keys( $r ), array_values( $r ), $template );
			unset( $r );
		}

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
			'title'    => '',
			'contacts' => '',
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
			<label for="<?php echo esc_attr( $this->get_field_id( 'contacts' ) ); ?>">
				<?php esc_html_e( 'Contacts', 'startapp' ); ?>
			</label>
			<?php $this->contacts_control( esc_attr( $this->get_field_name( 'contacts' ) ), $instance['contacts'] ); ?>
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

		$instance['title']    = sanitize_text_field( $new_instance['title'] );
		$instance['contacts'] = $this->sanitize( $new_instance['contacts'] );

		return $instance;
	}

	private function sanitize( $contacts ) {
		if ( empty( $contacts ) ) {
			return '';
		}

		if ( empty( $contacts['type'] ) || empty( $contacts['data'] ) ) {
			return '';
		}

		$result = array();
		array_map( function( $type, $data ) use ( &$result ) {
			$result[] = array(
				'type' => $type,
				'data' => strip_tags( $data ),
			);
		}, $contacts['type'], $contacts['data'] );

		return json_encode( $result );
	}

	/**
	 * Render the contacts control
	 *
	 * @param string $name     Name of field
	 * @param string $contacts JSON encoded list of contacts
	 */
	private function contacts_control( $name, $contacts ) {
		echo '<div class="startapp-repeated-fields-wrap">';

		if ( ! empty ($contacts ) ) {
			$contacts = json_decode( $contacts, true );
			$this->render_list( $name, $contacts );
		} else {
			$this->render_empty( $name );
		}

		echo '</div>';
		echo '<br>';
		echo '<a href="#" class="startapp-repeat">', esc_html__( 'Add one more', 'startapp' ), '</a>';
	}

	/**
	 * Render filled list of contacts with controls
	 *
	 * @param string $name     Name
	 * @param array  $contacts List of contacts selected by user
	 */
	private function render_list( $name, $contacts = array() ) {
		$type_name = $this->get_type_name( $name );
		$data_name = $this->get_data_name( $name );
		$template  = $this->get_template();

		foreach ( $contacts as $contact ) {
			$options = '';
			foreach ( (array) $this->get_types() as $type => $name ) {
				$selected = ( $type === $contact['type'] ) ? 'selected' : '';
				$options .= sprintf( '<option value="%1$s" %3$s>%2$s</option>',
					esc_attr( $type ), esc_html( $name ), $selected
				);
			}
			unset( $selected, $type, $name );

			$r = array(
				'{type-name}'  => $type_name,
				'{data-name}'  => $data_name,
				'{data-value}' => esc_textarea( $contact['data'] ),
				'{options}'    => $options,
			);

			echo str_replace( array_keys( $r ), array_values( $r ), $template );
			unset( $r );
		}
		unset( $contact );
	}

	/**
	 * Render the controls for single contact
	 *
	 * @param string $name Name
	 */
	private function render_empty( $name ) {
		$type_name = $this->get_type_name( $name );
		$data_name = $this->get_data_name( $name );

		$options = '';
		foreach ( (array) $this->get_types() as $type => $name ) {
			$options .= sprintf( '<option value="%1$s">%2$s</option>',
				esc_attr( $type ), esc_html( $name )
			);
		}
		unset( $selected, $type, $name );

		$r = [
			'{type-name}'  => $type_name,
			'{data-name}'  => $data_name,
			'{data-value}' => '',
			'{options}'    => $options,
		];

		echo str_replace( array_keys( $r ), array_values( $r ), $this->get_template() );
	}

	/**
	 * Returns a list of types
	 *
	 * @return array
	 */
	private function get_types() {
		$types = array(
			'address' => esc_html__( 'Address', 'startapp' ),
			'phone'   => esc_html__( 'Phone', 'startapp' ),
			'skype'   => esc_html__( 'Skype', 'startapp' ),
			'email'   => esc_html__( 'Email', 'startapp' ),
			'time'    => esc_html__( 'Time', 'startapp' ),
		);

		/**
		 * This filter allows to add more types
		 *
		 * @param array $types A list of types
		 */
		return apply_filters( 'startapp_widget_contacts_types', $types );
	}

	/**
	 * Returns the template for single contact control
	 *
	 * Applicable for backend
	 *
	 * @return string
	 */
	private function get_template() {
		$template = '
			<div class="startapp-repeated-group">
				<select name="{type-name}">{options}</select>
				<textarea name="{data-name}">{data-value}</textarea>
				<a href="#" class="startapp-unrepeat">&times;</a>
			</div>
		';

		return startapp_content_encode( $template );
	}

	/**
	 * Returns the name for "type" field
	 *
	 * @param string $name Name
	 *
	 * @return string
	 */
	private function get_type_name( $name ) {
		return esc_attr( "{$name}[type][]" );
	}

	/**
	 * Returns the name for "data" field
	 *
	 * @param string $name Name
	 *
	 * @return string
	 */
	private function get_data_name( $name ) {
		return esc_attr( "{$name}[data][]" );
	}

	/**
	 * Returns the icon for passed type
	 *
	 * @param string $type Type
	 *
	 * @return string
	 */
	private function get_icon( $type ) {
		switch ( $type ) {
			case 'address':
				$icon = 'material-icons location_on';
				break;

			case 'phone':
				$icon = 'material-icons smartphone';
				break;

			case 'skype':
				$icon = 'socicon-skype';
				break;

			case 'email':
				$icon = 'socicon-mail';
				break;

			case 'time':
				$icon = 'material-icons access_time';
				break;

			default:
				$icon = '';
				break;
		}

		/**
		 * This filter allows to change the icon for passed type
		 *
		 * @param string $icon Icon
		 * @param string $type Type
		 */
		return apply_filters( 'startapp_widget_contacts_icon', $icon, $type );
	}

	/**
	 * Returns the parsed info based on data
	 *
	 * @param string $data
	 *
	 * @return array|string
	 */
	private function get_info( $data ) {
		if ( empty( $data ) ) {
			return '';
		}

		$data = array_filter( explode( "\n", $data ) );
		$info = array();
		foreach ( (array) $data as $item ) {
			$info[] = '<span>' . esc_html( trim( $item ) ) . '</span>';
		}
		$info = implode( '', $info );

		return $info;
	}
}
