<?php

/**
 * Widget "StartApp Subscribe"
 *
 * Display the MailChimp subscribe form
 *
 * @uses WP_Widget
 */
class Startapp_Widget_Subscribe extends WP_Widget {

	/**
	 * Widget id_base
	 *
	 * @var string
	 */
	private $widget_id = 'startapp_subscribe';

	public function __construct() {
		$opts = array( 'description' => esc_html__( 'Subscription form', 'startapp' ) );
		parent::__construct( $this->widget_id, esc_html__( 'StartApp Subscribe', 'startapp' ), $opts );
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
			'url'         => '',
			'description' => '',
			'placeholder' => esc_html__( 'Enter email', 'startapp' ),
		) );

		$title    = apply_filters( 'widget_title', esc_html( $instance['title'] ), $instance, $this->id_base );
		$antispam = '';

		// build MailChimp AntiSPAM
		if ( ! empty( $instance['url'] ) ) {
			$request_uri = parse_url( htmlspecialchars_decode( $instance['url'] ), PHP_URL_QUERY );
			parse_str( $request_uri, $c );
			if ( array_key_exists( 'u', $c ) && array_key_exists( 'id', $c ) ) {
				$antispam = sprintf( 'b_%1$s_%2$s', $c['u'], $c['id'] );
			}
			unset( $request_uri, $c );
		}

		echo $args['before_widget'];
		if ( $title ) {
			echo $args['before_title'], $title, $args['after_title'];
		}

		?>
		<form method="post" action="<?php echo esc_url( $instance['url'] ); ?>"
		      class="subscribe-form" target="_blank" novalidate autocomplete="off">

			<div style="position: absolute; left: -5000px;">
				<input type="text" name="<?php echo esc_attr( $antispam ); ?>" tabindex="-1" value="">
			</div>

			<?php
			// display the description in <p>
			startapp_the_text( esc_html( $instance['description'] ), '<p>', '</p>' ); ?>
			<div class="input-group">
				<i class="material-icons mail_outline"></i>
				<input type="email" name="EMAIL" placeholder="<?php echo esc_html( $instance['placeholder'] ); ?>">
				<button type="submit"><i class="material-icons send"></i></button>
			</div>
		</form>

		<?php
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
			'url'         => '',
			'description' => '',
			'placeholder' => esc_html__( 'Enter email', 'startapp' ),
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
			<label for="<?php echo esc_attr( $this->get_field_id( 'url' ) ); ?>">
				<?php echo esc_html__( 'MailChimp URL', 'startapp' ); ?>
			</label>
			<input type="text" class="widefat"
			       id="<?php echo esc_attr( $this->get_field_id( 'url' ) ); ?>"
			       name="<?php echo esc_attr( $this->get_field_name( 'url' ) ); ?>"
			       value="<?php echo esc_url( trim( $instance['url'] ) ); ?>">
		</p>
		<p class="description">
			<?php esc_html_e( 'This URL can be retrieved from your MailChimp dashboard > Lists > your desired list > list settings > forms. in your form creation page you will need to click on "share it" tab then find "Your subscribe form lives at this URL:". Its a short URL so you will need to visit this link. Once you get into the your created form page, then copy the full address and paste it here in this form. URL look like http://YOUR_USER_NAME.us6.list-manage.com/subscribe?u=d5f4e5e82a59166b0cfbc716f&id=4db82d169b', 'startapp' ); ?>
		</p>
		<p>
			<label for="<?php echo esc_attr( $this->get_field_id( 'description' ) ); ?>">
				<?php echo esc_html__( 'Description', 'startapp' ); ?>
			</label>
			<textarea class="widefat"
			       id="<?php echo esc_attr( $this->get_field_id( 'description' ) ); ?>"
			       name="<?php echo esc_attr( $this->get_field_name( 'description' ) ); ?>"
			><?php echo esc_textarea( trim( $instance['description'] ) ); ?></textarea>
		</p>
		<p>
			<label for="<?php echo esc_attr( $this->get_field_id( 'placeholder' ) ); ?>">
				<?php echo esc_html__( 'Placeholder', 'startapp' ); ?>
			</label>
			<input type="text" class="widefat"
			       id="<?php echo esc_attr( $this->get_field_id( 'placeholder' ) ); ?>"
			       name="<?php echo esc_attr( $this->get_field_name( 'placeholder' ) ); ?>"
			       value="<?php echo esc_html( trim( $instance['placeholder'] ) ); ?>">
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

		$instance['title']       = sanitize_text_field( $new_instance['title'] );
		$instance['url']         = esc_url_raw( $new_instance['url'] );
		$instance['description'] = esc_textarea( $new_instance['description'] );
		$instance['placeholder'] = esc_html( $new_instance['placeholder'] );

		return $instance;
	}
}
