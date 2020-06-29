<?php
/**
 * Custom functions that act independently of the theme templates.
 *
 * Eventually, some of the functionality here could be replaced by core features.
 *
 * @author 8guild
 */

/**
 * Modify TinyMCE. Add "style_formats"
 *
 * @link https://codex.wordpress.org/TinyMCE_Custom_Styles#Using_style_formats
 *
 * @param array $init_array
 *
 * @return mixed
 */
function startapp_mce_before_init( $init_array ) {
	$style_formats = array(
		array(
			'title'    => esc_html__( 'Lead text', 'startapp' ),
			'selector' => 'p',
			'classes'  => 'lead'
		),
		array(
			'title'    => esc_html__( 'Large text', 'startapp' ),
			'selector' => 'p',
			'classes'  => 'text-lg'
		),
		array(
			'title'    => esc_html__( 'Small text', 'startapp' ),
			'selector' => 'p',
			'classes'  => 'text-sm'
		),
		array(
			'title'    => esc_html__( 'Extra Small text', 'startapp' ),
			'selector' => 'p',
			'classes'  => 'text-xs'
		),
		array(
			'title'   => esc_html__( 'UPPERCASE text', 'startapp' ),
			'inline'  => 'span',
			'classes' => 'text-uppercase'
		),
		array(
			'title'   => esc_html__( 'Thin text', 'startapp' ),
			'inline'  => 'span',
			'classes' => 'text-thin'
		),
		array(
			'title'   => esc_html__( 'Normal text', 'startapp' ),
			'inline'  => 'span',
			'classes' => 'text-normal'
		),
		array(
			'title'   => esc_html__( 'Semibold text', 'startapp' ),
			'inline'  => 'span',
			'classes' => 'text-semibold'
		),
		array(
			'title'   => esc_html__( 'Bold text', 'startapp' ),
			'inline'  => 'span',
			'classes' => 'text-bold'
		),
		array(
			'title'   => esc_html__( 'Text Default', 'startapp' ),
			'inline'  => 'span',
			'classes' => 'text-default'
		),
		array(
			'title'   => esc_html__( 'Text Primary', 'startapp' ),
			'inline'  => 'span',
			'classes' => 'text-primary'
		),
		array(
			'title'   => esc_html__( 'Text Success', 'startapp' ),
			'inline'  => 'span',
			'classes' => 'text-success'
		),
		array(
			'title'   => esc_html__( 'Text Info', 'startapp' ),
			'inline'  => 'span',
			'classes' => 'text-info'
		),
		array(
			'title'   => esc_html__( 'Text Warning', 'startapp' ),
			'inline'  => 'span',
			'classes' => 'text-warning'
		),
		array(
			'title'   => esc_html__( 'Text Danger', 'startapp' ),
			'inline'  => 'span',
			'classes' => 'text-danger'
		),
		array(
			'title'   => esc_html__( 'Text Gray', 'startapp' ),
			'inline'  => 'span',
			'classes' => 'text-gray'
		),
		array(
			'title'   => esc_html__( 'Text Muted', 'startapp' ),
			'inline'  => 'span',
			'classes' => 'text-muted'
		),
		array(
			'title'   => esc_html__( 'Text Light', 'startapp' ),
			'inline'  => 'span',
			'classes' => 'text-light'
		),
		array(
			'title'   => esc_html__( 'Bg Default', 'startapp' ),
			'inline'  => 'span',
			'classes' => 'bg-default text-light'
		),
		array(
			'title'   => esc_html__( 'Bg Primary', 'startapp' ),
			'inline'  => 'span',
			'classes' => 'bg-primary text-light'
		),
		array(
			'title'   => esc_html__( 'Bg Success', 'startapp' ),
			'inline'  => 'span',
			'classes' => 'bg-success text-light'
		),
		array(
			'title'   => esc_html__( 'Bg Info', 'startapp' ),
			'inline'  => 'span',
			'classes' => 'bg-info text-light'
		),
		array(
			'title'   => esc_html__( 'Bg Warning', 'startapp' ),
			'inline'  => 'span',
			'classes' => 'bg-warning text-light'
		),
		array(
			'title'   => esc_html__( 'Bg Danger', 'startapp' ),
			'inline'  => 'span',
			'classes' => 'bg-danger text-light'
		),
		array(
			'title'   => esc_html__( 'Bg Gray', 'startapp' ),
			'inline'  => 'span',
			'classes' => 'bg-gray text-light'
		),
		array(
			'title'   => esc_html__( 'Bg Muted', 'startapp' ),
			'inline'  => 'span',
			'classes' => 'bg-muted'
		),
		array(
			'title'   => esc_html__( 'Opacity 75', 'startapp' ),
			'inline'  => 'span',
			'classes' => 'opacity-75'
		),
		array(
			'title'   => esc_html__( 'Opacity 50', 'startapp' ),
			'inline'  => 'span',
			'classes' => 'opacity-50'
		),
		array(
			'title'   => esc_html__( 'Opacity 25', 'startapp' ),
			'inline'  => 'span',
			'classes' => 'opacity-25'
		),
		array(
			'title'    => esc_html__( 'Lead List', 'startapp' ),
			'selector' => 'ul, ol',
			'classes'  => 'lead',
		),
		array(
			'title'    => esc_html__( 'Large List', 'startapp' ),
			'selector' => 'ul, ol',
			'classes'  => 'text-lg',
		),
		array(
			'title'    => esc_html__( 'Small List', 'startapp' ),
			'selector' => 'ul, ol',
			'classes'  => 'text-sm',
		),
		array(
			'title'    => esc_html__( 'Extra Small List', 'startapp' ),
			'selector' => 'ul, ol',
			'classes'  => 'text-xs',
		),
		array(
			'title'    => esc_html__( 'Light List', 'startapp' ),
			'selector' => 'ul, ol',
			'classes'  => 'text-light',
		),
		array(
			'title'    => esc_html__( 'Bordered List', 'startapp' ),
			'selector' => 'ul, ol',
			'classes'  => 'list-bordered',
		),
		array(
			'title'    => esc_html__( 'Unstyled List', 'startapp' ),
			'selector' => 'ul, ol',
			'classes'  => 'list-unstyled',
		),
		array(
			'title'  => esc_html__( 'Code', 'startapp' ),
			'inline' => 'code'
		),
	);

	$init_array['style_formats'] = json_encode( $style_formats );

	return $init_array;
}

add_filter( 'tiny_mce_before_init', 'startapp_mce_before_init' );

/**
 * Add "styleselect" button to TinyMCE second row
 *
 * @param array $buttons TinyMCE Buttons
 *
 * @return mixed
 */
function startapp_mce_buttons_2( $buttons ) {
	array_unshift( $buttons, 'styleselect' );

	return $buttons;
}

add_filter( 'mce_buttons_2', 'startapp_mce_buttons_2' );

/**
 * Add styles to TinyMCE
 */
function startapp_add_editor_styles() {
	add_editor_style();
}

add_action( 'admin_init', 'startapp_add_editor_styles' );

/**
 * Some additional mime types
 *
 * @param array $mime_types
 *
 * @return array
 */
function startapp_extended_mime_types( $mime_types ) {
	$extended = array(
		'svg' => 'image/svg+xml'
	);

	foreach ( $extended as $ext => $mime ) {
		$mime_types[ $ext ] = $mime;
	}

	return $mime_types;
}

add_filter( 'upload_mimes', 'startapp_extended_mime_types' );

/**
 * Show favicon preview (from .ico format), not an icon
 *
 * @param string $icon    Path to the mime type icon.
 * @param string $mime    Mime type.
 * @param int    $post_id Attachment ID. Will equal 0 if the function passed
 *                        the mime type.
 *
 * @return mixed
 */
function startapp_mime_type_icon( $icon, $mime, $post_id ) {
	$src   = false;
	$mimes = array(
		'image/x-icon',
		'image/svg+xml',
	);

	if ( in_array( $mime, $mimes, true ) && $post_id > 0 ) {
		$src = wp_get_attachment_image_src( $post_id );
	}

	return is_array( $src ) ? array_shift( $src ) : $icon;
}

add_filter( 'wp_mime_type_icon', 'startapp_mime_type_icon', 10, 3 );

/**
 * Flush out the transients used in startapp_is_categorized_blog().
 *
 * @see startapp_is_categorized_blog()
 */
function startapp_category_transient_flusher() {
	if ( defined( 'DOING_AUTOSAVE' ) && DOING_AUTOSAVE ) {
		return;
	}
	// Like, beat it. Dig?
	delete_transient( STARTAPP_TRANSIENT_CATEGORIES );
}

add_action( 'edit_category', 'startapp_category_transient_flusher' );
add_action( 'save_post', 'startapp_category_transient_flusher' );

/**
 * Returns the Material Icons pack
 *
 * Required for Equip and StartApp. Used in iconpickers. Slug is "material"
 *
 * @see equip_get_icons()
 *
 * @return array
 */
function startapp_get_material_icons() {
	return array(
		'material-icons d3_rotation',
		'material-icons ac_unit',
		'material-icons access_alarm',
		'material-icons access_alarms',
		'material-icons access_time',
		'material-icons accessibility',
		'material-icons accessible',
		'material-icons account_balance',
		'material-icons account_balance_wallet',
		'material-icons account_box',
		'material-icons account_circle',
		'material-icons adb',
		'material-icons add',
		'material-icons add_a_photo',
		'material-icons add_alarm',
		'material-icons add_alert',
		'material-icons add_box',
		'material-icons add_circle',
		'material-icons add_circle_outline',
		'material-icons add_location',
		'material-icons add_shopping_cart',
		'material-icons add_to_photos',
		'material-icons add_to_queue',
		'material-icons adjust',
		'material-icons airline_seat_flat',
		'material-icons airline_seat_flat_angled',
		'material-icons airline_seat_individual_suite',
		'material-icons airline_seat_legroom_extra',
		'material-icons airline_seat_legroom_normal',
		'material-icons airline_seat_legroom_reduced',
		'material-icons airline_seat_recline_extra',
		'material-icons airline_seat_recline_normal',
		'material-icons airplanemode_active',
		'material-icons airplanemode_inactive',
		'material-icons airplay',
		'material-icons airport_shuttle',
		'material-icons alarm',
		'material-icons alarm_add',
		'material-icons alarm_off',
		'material-icons alarm_on',
		'material-icons album',
		'material-icons all_inclusive',
		'material-icons all_out',
		'material-icons android',
		'material-icons announcement',
		'material-icons apps',
		'material-icons archive',
		'material-icons arrow_back',
		'material-icons arrow_downward',
		'material-icons arrow_drop_down',
		'material-icons arrow_drop_down_circle',
		'material-icons arrow_drop_up',
		'material-icons arrow_forward',
		'material-icons arrow_upward',
		'material-icons art_track',
		'material-icons aspect_ratio',
		'material-icons assessment',
		'material-icons assignment',
		'material-icons assignment_ind',
		'material-icons assignment_late',
		'material-icons assignment_return',
		'material-icons assignment_returned',
		'material-icons assignment_turned_in',
		'material-icons assistant',
		'material-icons assistant_photo',
		'material-icons attach_file',
		'material-icons attach_money',
		'material-icons attachment',
		'material-icons audiotrack',
		'material-icons autorenew',
		'material-icons av_timer',
		'material-icons backspace',
		'material-icons backup',
		'material-icons battery_alert',
		'material-icons battery_charging_full',
		'material-icons battery_full',
		'material-icons battery_std',
		'material-icons battery_unknown',
		'material-icons beach_access',
		'material-icons beenhere',
		'material-icons block',
		'material-icons bluetooth',
		'material-icons bluetooth_audio',
		'material-icons bluetooth_connected',
		'material-icons bluetooth_disabled',
		'material-icons bluetooth_searching',
		'material-icons blur_circular',
		'material-icons blur_linear',
		'material-icons blur_off',
		'material-icons blur_on',
		'material-icons book',
		'material-icons bookmark',
		'material-icons bookmark_border',
		'material-icons border_all',
		'material-icons border_bottom',
		'material-icons border_clear',
		'material-icons border_color',
		'material-icons border_horizontal',
		'material-icons border_inner',
		'material-icons border_left',
		'material-icons border_outer',
		'material-icons border_right',
		'material-icons border_style',
		'material-icons border_top',
		'material-icons border_vertical',
		'material-icons branding_watermark',
		'material-icons brightness_1',
		'material-icons brightness_2',
		'material-icons brightness_3',
		'material-icons brightness_4',
		'material-icons brightness_5',
		'material-icons brightness_6',
		'material-icons brightness_7',
		'material-icons brightness_auto',
		'material-icons brightness_high',
		'material-icons brightness_low',
		'material-icons brightness_medium',
		'material-icons broken_image',
		'material-icons brush',
		'material-icons bubble_chart',
		'material-icons bug_report',
		'material-icons build',
		'material-icons burst_mode',
		'material-icons business',
		'material-icons business_center',
		'material-icons cached',
		'material-icons cake',
		'material-icons call',
		'material-icons call_end',
		'material-icons call_made',
		'material-icons call_merge',
		'material-icons call_missed',
		'material-icons call_missed_outgoing',
		'material-icons call_received',
		'material-icons call_split',
		'material-icons call_to_action',
		'material-icons camera',
		'material-icons camera_alt',
		'material-icons camera_enhance',
		'material-icons camera_front',
		'material-icons camera_rear',
		'material-icons camera_roll',
		'material-icons cancel',
		'material-icons card_giftcard',
		'material-icons card_membership',
		'material-icons card_travel',
		'material-icons casino',
		'material-icons cast',
		'material-icons cast_connected',
		'material-icons center_focus_strong',
		'material-icons center_focus_weak',
		'material-icons change_history',
		'material-icons chat',
		'material-icons chat_bubble',
		'material-icons chat_bubble_outline',
		'material-icons check',
		'material-icons check_box',
		'material-icons check_box_outline_blank',
		'material-icons check_circle',
		'material-icons chevron_left',
		'material-icons chevron_right',
		'material-icons child_care',
		'material-icons child_friendly',
		'material-icons chrome_reader_mode',
		'material-icons class',
		'material-icons clear',
		'material-icons clear_all',
		'material-icons close',
		'material-icons closed_caption',
		'material-icons cloud',
		'material-icons cloud_circle',
		'material-icons cloud_done',
		'material-icons cloud_download',
		'material-icons cloud_off',
		'material-icons cloud_queue',
		'material-icons cloud_upload',
		'material-icons code',
		'material-icons collections',
		'material-icons collections_bookmark',
		'material-icons color_lens',
		'material-icons colorize',
		'material-icons comment',
		'material-icons compare',
		'material-icons compare_arrows',
		'material-icons computer',
		'material-icons confirmation_number',
		'material-icons contact_mail',
		'material-icons contact_phone',
		'material-icons contacts',
		'material-icons content_copy',
		'material-icons content_cut',
		'material-icons content_paste',
		'material-icons control_point',
		'material-icons control_point_duplicate',
		'material-icons copyright',
		'material-icons create',
		'material-icons create_new_folder',
		'material-icons credit_card',
		'material-icons crop',
		'material-icons crop_16_9',
		'material-icons crop_3_2',
		'material-icons crop_5_4',
		'material-icons crop_7_5',
		'material-icons crop_din',
		'material-icons crop_free',
		'material-icons crop_landscape',
		'material-icons crop_original',
		'material-icons crop_portrait',
		'material-icons crop_rotate',
		'material-icons crop_square',
		'material-icons dashboard',
		'material-icons data_usage',
		'material-icons date_range',
		'material-icons dehaze',
		'material-icons delete',
		'material-icons delete_forever',
		'material-icons delete_sweep',
		'material-icons description',
		'material-icons desktop_mac',
		'material-icons desktop_windows',
		'material-icons details',
		'material-icons developer_board',
		'material-icons developer_mode',
		'material-icons device_hub',
		'material-icons devices',
		'material-icons devices_other',
		'material-icons dialer_sip',
		'material-icons dialpad',
		'material-icons directions',
		'material-icons directions_bike',
		'material-icons directions_boat',
		'material-icons directions_bus',
		'material-icons directions_car',
		'material-icons directions_railway',
		'material-icons directions_run',
		'material-icons directions_subway',
		'material-icons directions_transit',
		'material-icons directions_walk',
		'material-icons disc_full',
		'material-icons dns',
		'material-icons do_not_disturb',
		'material-icons do_not_disturb_alt',
		'material-icons do_not_disturb_off',
		'material-icons do_not_disturb_on',
		'material-icons dock',
		'material-icons domain',
		'material-icons done',
		'material-icons done_all',
		'material-icons donut_large',
		'material-icons donut_small',
		'material-icons drafts',
		'material-icons drag_handle',
		'material-icons drive_eta',
		'material-icons dvr',
		'material-icons edit',
		'material-icons edit_location',
		'material-icons eject',
		'material-icons email',
		'material-icons enhanced_encryption',
		'material-icons equalizer',
		'material-icons error',
		'material-icons error_outline',
		'material-icons euro_symbol',
		'material-icons ev_station',
		'material-icons event',
		'material-icons event_available',
		'material-icons event_busy',
		'material-icons event_note',
		'material-icons event_seat',
		'material-icons exit_to_app',
		'material-icons expand_less',
		'material-icons expand_more',
		'material-icons explicit',
		'material-icons explore',
		'material-icons exposure',
		'material-icons exposure_neg_1',
		'material-icons exposure_neg_2',
		'material-icons exposure_plus_1',
		'material-icons exposure_plus_2',
		'material-icons exposure_zero',
		'material-icons extension',
		'material-icons face',
		'material-icons fast_forward',
		'material-icons fast_rewind',
		'material-icons favorite',
		'material-icons favorite_border',
		'material-icons featured_play_list',
		'material-icons featured_video',
		'material-icons feedback',
		'material-icons fiber_dvr',
		'material-icons fiber_manual_record',
		'material-icons fiber_new',
		'material-icons fiber_pin',
		'material-icons fiber_smart_record',
		'material-icons file_download',
		'material-icons file_upload',
		'material-icons filter',
		'material-icons filter_1',
		'material-icons filter_2',
		'material-icons filter_3',
		'material-icons filter_4',
		'material-icons filter_5',
		'material-icons filter_6',
		'material-icons filter_7',
		'material-icons filter_8',
		'material-icons filter_9',
		'material-icons filter_9_plus',
		'material-icons filter_b_and_w',
		'material-icons filter_center_focus',
		'material-icons filter_drama',
		'material-icons filter_frames',
		'material-icons filter_hdr',
		'material-icons filter_list',
		'material-icons filter_none',
		'material-icons filter_tilt_shift',
		'material-icons filter_vintage',
		'material-icons find_in_page',
		'material-icons find_replace',
		'material-icons fingerprint',
		'material-icons first_page',
		'material-icons fitness_center',
		'material-icons flag',
		'material-icons flare',
		'material-icons flash_auto',
		'material-icons flash_off',
		'material-icons flash_on',
		'material-icons flight',
		'material-icons flight_land',
		'material-icons flight_takeoff',
		'material-icons flip',
		'material-icons flip_to_back',
		'material-icons flip_to_front',
		'material-icons folder',
		'material-icons folder_open',
		'material-icons folder_shared',
		'material-icons folder_special',
		'material-icons font_download',
		'material-icons format_align_center',
		'material-icons format_align_justify',
		'material-icons format_align_left',
		'material-icons format_align_right',
		'material-icons format_bold',
		'material-icons format_clear',
		'material-icons format_color_fill',
		'material-icons format_color_reset',
		'material-icons format_color_text',
		'material-icons format_indent_decrease',
		'material-icons format_indent_increase',
		'material-icons format_italic',
		'material-icons format_line_spacing',
		'material-icons format_list_bulleted',
		'material-icons format_list_numbered',
		'material-icons format_paint',
		'material-icons format_quote',
		'material-icons format_shapes',
		'material-icons format_size',
		'material-icons format_strikethrough',
		'material-icons format_textdirection_l_to_r',
		'material-icons format_textdirection_r_to_l',
		'material-icons format_underlined',
		'material-icons forum',
		'material-icons forward',
		'material-icons forward_10',
		'material-icons forward_30',
		'material-icons forward_5',
		'material-icons free_breakfast',
		'material-icons fullscreen',
		'material-icons fullscreen_exit',
		'material-icons functions',
		'material-icons g_translate',
		'material-icons gamepad',
		'material-icons games',
		'material-icons gavel',
		'material-icons gesture',
		'material-icons get_app',
		'material-icons gif',
		'material-icons golf_course',
		'material-icons gps_fixed',
		'material-icons gps_not_fixed',
		'material-icons gps_off',
		'material-icons grade',
		'material-icons gradient',
		'material-icons grain',
		'material-icons graphic_eq',
		'material-icons grid_off',
		'material-icons grid_on',
		'material-icons group',
		'material-icons group_add',
		'material-icons group_work',
		'material-icons hd',
		'material-icons hdr_off',
		'material-icons hdr_on',
		'material-icons hdr_strong',
		'material-icons hdr_weak',
		'material-icons headset',
		'material-icons headset_mic',
		'material-icons healing',
		'material-icons hearing',
		'material-icons help',
		'material-icons help_outline',
		'material-icons high_quality',
		'material-icons highlight',
		'material-icons highlight_off',
		'material-icons history',
		'material-icons home',
		'material-icons hot_tub',
		'material-icons hotel',
		'material-icons hourglass_empty',
		'material-icons hourglass_full',
		'material-icons http',
		'material-icons https',
		'material-icons image',
		'material-icons image_aspect_ratio',
		'material-icons import_contacts',
		'material-icons import_export',
		'material-icons important_devices',
		'material-icons inbox',
		'material-icons indeterminate_check_box',
		'material-icons info',
		'material-icons info_outline',
		'material-icons input',
		'material-icons insert_chart',
		'material-icons insert_comment',
		'material-icons insert_drive_file',
		'material-icons insert_emoticon',
		'material-icons insert_invitation',
		'material-icons insert_link',
		'material-icons insert_photo',
		'material-icons invert_colors',
		'material-icons invert_colors_off',
		'material-icons iso',
		'material-icons keyboard',
		'material-icons keyboard_arrow_down',
		'material-icons keyboard_arrow_left',
		'material-icons keyboard_arrow_right',
		'material-icons keyboard_arrow_up',
		'material-icons keyboard_backspace',
		'material-icons keyboard_capslock',
		'material-icons keyboard_hide',
		'material-icons keyboard_return',
		'material-icons keyboard_tab',
		'material-icons keyboard_voice',
		'material-icons kitchen',
		'material-icons label',
		'material-icons label_outline',
		'material-icons landscape',
		'material-icons language',
		'material-icons laptop',
		'material-icons laptop_chromebook',
		'material-icons laptop_mac',
		'material-icons laptop_windows',
		'material-icons last_page',
		'material-icons launch',
		'material-icons layers',
		'material-icons layers_clear',
		'material-icons leak_add',
		'material-icons leak_remove',
		'material-icons lens',
		'material-icons library_add',
		'material-icons library_books',
		'material-icons library_music',
		'material-icons lightbulb_outline',
		'material-icons line_style',
		'material-icons line_weight',
		'material-icons linear_scale',
		'material-icons link',
		'material-icons linked_camera',
		'material-icons list',
		'material-icons live_help',
		'material-icons live_tv',
		'material-icons local_activity',
		'material-icons local_airport',
		'material-icons local_atm',
		'material-icons local_bar',
		'material-icons local_cafe',
		'material-icons local_car_wash',
		'material-icons local_convenience_store',
		'material-icons local_dining',
		'material-icons local_drink',
		'material-icons local_florist',
		'material-icons local_gas_station',
		'material-icons local_grocery_store',
		'material-icons local_hospital',
		'material-icons local_hotel',
		'material-icons local_laundry_service',
		'material-icons local_library',
		'material-icons local_mall',
		'material-icons local_movies',
		'material-icons local_offer',
		'material-icons local_parking',
		'material-icons local_pharmacy',
		'material-icons local_phone',
		'material-icons local_pizza',
		'material-icons local_play',
		'material-icons local_post_office',
		'material-icons local_printshop',
		'material-icons local_see',
		'material-icons local_shipping',
		'material-icons local_taxi',
		'material-icons location_city',
		'material-icons location_disabled',
		'material-icons location_off',
		'material-icons location_on',
		'material-icons location_searching',
		'material-icons lock',
		'material-icons lock_open',
		'material-icons lock_outline',
		'material-icons looks',
		'material-icons looks_3',
		'material-icons looks_4',
		'material-icons looks_5',
		'material-icons looks_6',
		'material-icons looks_one',
		'material-icons looks_two',
		'material-icons loop',
		'material-icons loupe',
		'material-icons low_priority',
		'material-icons loyalty',
		'material-icons mail',
		'material-icons mail_outline',
		'material-icons map',
		'material-icons markunread',
		'material-icons markunread_mailbox',
		'material-icons memory',
		'material-icons menu',
		'material-icons merge_type',
		'material-icons message',
		'material-icons mic',
		'material-icons mic_none',
		'material-icons mic_off',
		'material-icons mms',
		'material-icons mode_comment',
		'material-icons mode_edit',
		'material-icons monetization_on',
		'material-icons money_off',
		'material-icons monochrome_photos',
		'material-icons mood',
		'material-icons mood_bad',
		'material-icons more',
		'material-icons more_horiz',
		'material-icons more_vert',
		'material-icons motorcycle',
		'material-icons mouse',
		'material-icons move_to_inbox',
		'material-icons movie',
		'material-icons movie_creation',
		'material-icons movie_filter',
		'material-icons multiline_chart',
		'material-icons music_note',
		'material-icons music_video',
		'material-icons my_location',
		'material-icons nature',
		'material-icons nature_people',
		'material-icons navigate_before',
		'material-icons navigate_next',
		'material-icons navigation',
		'material-icons near_me',
		'material-icons network_cell',
		'material-icons network_check',
		'material-icons network_locked',
		'material-icons network_wifi',
		'material-icons new_releases',
		'material-icons next_week',
		'material-icons nfc',
		'material-icons no_encryption',
		'material-icons no_sim',
		'material-icons not_interested',
		'material-icons note',
		'material-icons note_add',
		'material-icons notifications',
		'material-icons notifications_active',
		'material-icons notifications_none',
		'material-icons notifications_off',
		'material-icons notifications_paused',
		'material-icons offline_pin',
		'material-icons ondemand_video',
		'material-icons opacity',
		'material-icons open_in_browser',
		'material-icons open_in_new',
		'material-icons open_with',
		'material-icons pages',
		'material-icons pageview',
		'material-icons palette',
		'material-icons pan_tool',
		'material-icons panorama',
		'material-icons panorama_fish_eye',
		'material-icons panorama_horizontal',
		'material-icons panorama_vertical',
		'material-icons panorama_wide_angle',
		'material-icons party_mode',
		'material-icons pause',
		'material-icons pause_circle_filled',
		'material-icons pause_circle_outline',
		'material-icons payment',
		'material-icons people',
		'material-icons people_outline',
		'material-icons perm_camera_mic',
		'material-icons perm_contact_calendar',
		'material-icons perm_data_setting',
		'material-icons perm_device_information',
		'material-icons perm_identity',
		'material-icons perm_media',
		'material-icons perm_phone_msg',
		'material-icons perm_scan_wifi',
		'material-icons person',
		'material-icons person_add',
		'material-icons person_outline',
		'material-icons person_pin',
		'material-icons person_pin_circle',
		'material-icons personal_video',
		'material-icons pets',
		'material-icons phone',
		'material-icons phone_android',
		'material-icons phone_bluetooth_speaker',
		'material-icons phone_forwarded',
		'material-icons phone_in_talk',
		'material-icons phone_iphone',
		'material-icons phone_locked',
		'material-icons phone_missed',
		'material-icons phone_paused',
		'material-icons phonelink',
		'material-icons phonelink_erase',
		'material-icons phonelink_lock',
		'material-icons phonelink_off',
		'material-icons phonelink_ring',
		'material-icons phonelink_setup',
		'material-icons photo',
		'material-icons photo_album',
		'material-icons photo_camera',
		'material-icons photo_filter',
		'material-icons photo_library',
		'material-icons photo_size_select_actual',
		'material-icons photo_size_select_large',
		'material-icons photo_size_select_small',
		'material-icons picture_as_pdf',
		'material-icons picture_in_picture',
		'material-icons picture_in_picture_alt',
		'material-icons pie_chart',
		'material-icons pie_chart_outlined',
		'material-icons pin_drop',
		'material-icons place',
		'material-icons play_arrow',
		'material-icons play_circle_filled',
		'material-icons play_circle_outline',
		'material-icons play_for_work',
		'material-icons playlist_add',
		'material-icons playlist_add_check',
		'material-icons playlist_play',
		'material-icons plus_one',
		'material-icons poll',
		'material-icons polymer',
		'material-icons pool',
		'material-icons portable_wifi_off',
		'material-icons portrait',
		'material-icons power',
		'material-icons power_input',
		'material-icons power_settings_new',
		'material-icons pregnant_woman',
		'material-icons present_to_all',
		'material-icons print',
		'material-icons priority_high',
		'material-icons public',
		'material-icons publish',
		'material-icons query_builder',
		'material-icons question_answer',
		'material-icons queue',
		'material-icons queue_music',
		'material-icons queue_play_next',
		'material-icons radio',
		'material-icons radio_button_checked',
		'material-icons radio_button_unchecked',
		'material-icons rate_review',
		'material-icons receipt',
		'material-icons recent_actors',
		'material-icons record_voice_over',
		'material-icons redeem',
		'material-icons redo',
		'material-icons refresh',
		'material-icons remove',
		'material-icons remove_circle',
		'material-icons remove_circle_outline',
		'material-icons remove_from_queue',
		'material-icons remove_red_eye',
		'material-icons remove_shopping_cart',
		'material-icons reorder',
		'material-icons repeat',
		'material-icons repeat_one',
		'material-icons replay',
		'material-icons replay_10',
		'material-icons replay_30',
		'material-icons replay_5',
		'material-icons reply',
		'material-icons reply_all',
		'material-icons report',
		'material-icons report_problem',
		'material-icons restaurant',
		'material-icons restaurant_menu',
		'material-icons restore',
		'material-icons restore_page',
		'material-icons ring_volume',
		'material-icons room',
		'material-icons room_service',
		'material-icons rotate_90_degrees_ccw',
		'material-icons rotate_left',
		'material-icons rotate_right',
		'material-icons rounded_corner',
		'material-icons router',
		'material-icons rowing',
		'material-icons rss_feed',
		'material-icons rv_hookup',
		'material-icons satellite',
		'material-icons save',
		'material-icons scanner',
		'material-icons schedule',
		'material-icons school',
		'material-icons screen_lock_landscape',
		'material-icons screen_lock_portrait',
		'material-icons screen_lock_rotation',
		'material-icons screen_rotation',
		'material-icons screen_share',
		'material-icons sd_card',
		'material-icons sd_storage',
		'material-icons search',
		'material-icons security',
		'material-icons select_all',
		'material-icons send',
		'material-icons sentiment_dissatisfied',
		'material-icons sentiment_neutral',
		'material-icons sentiment_satisfied',
		'material-icons sentiment_very_dissatisfied',
		'material-icons sentiment_very_satisfied',
		'material-icons settings',
		'material-icons settings_applications',
		'material-icons settings_backup_restore',
		'material-icons settings_bluetooth',
		'material-icons settings_brightness',
		'material-icons settings_cell',
		'material-icons settings_ethernet',
		'material-icons settings_input_antenna',
		'material-icons settings_input_component',
		'material-icons settings_input_composite',
		'material-icons settings_input_hdmi',
		'material-icons settings_input_svideo',
		'material-icons settings_overscan',
		'material-icons settings_phone',
		'material-icons settings_power',
		'material-icons settings_remote',
		'material-icons settings_system_daydream',
		'material-icons settings_voice',
		'material-icons share',
		'material-icons shop',
		'material-icons shop_two',
		'material-icons shopping_basket',
		'material-icons shopping_cart',
		'material-icons short_text',
		'material-icons show_chart',
		'material-icons shuffle',
		'material-icons signal_cellular_4_bar',
		'material-icons signal_cellular_connected_no_internet_4_bar',
		'material-icons signal_cellular_no_sim',
		'material-icons signal_cellular_null',
		'material-icons signal_cellular_off',
		'material-icons signal_wifi_4_bar',
		'material-icons signal_wifi_4_bar_lock',
		'material-icons signal_wifi_off',
		'material-icons sim_card',
		'material-icons sim_card_alert',
		'material-icons skip_next',
		'material-icons skip_previous',
		'material-icons slideshow',
		'material-icons slow_motion_video',
		'material-icons smartphone',
		'material-icons smoke_free',
		'material-icons smoking_rooms',
		'material-icons sms',
		'material-icons sms_failed',
		'material-icons snooze',
		'material-icons sort',
		'material-icons sort_by_alpha',
		'material-icons spa',
		'material-icons space_bar',
		'material-icons speaker',
		'material-icons speaker_group',
		'material-icons speaker_notes',
		'material-icons speaker_notes_off',
		'material-icons speaker_phone',
		'material-icons spellcheck',
		'material-icons star',
		'material-icons star_border',
		'material-icons star_half',
		'material-icons stars',
		'material-icons stay_current_landscape',
		'material-icons stay_current_portrait',
		'material-icons stay_primary_landscape',
		'material-icons stay_primary_portrait',
		'material-icons stop',
		'material-icons stop_screen_share',
		'material-icons storage',
		'material-icons store',
		'material-icons store_mall_directory',
		'material-icons straighten',
		'material-icons streetview',
		'material-icons strikethrough_s',
		'material-icons style',
		'material-icons subdirectory_arrow_left',
		'material-icons subdirectory_arrow_right',
		'material-icons subject',
		'material-icons subscriptions',
		'material-icons subtitles',
		'material-icons subway',
		'material-icons supervisor_account',
		'material-icons surround_sound',
		'material-icons swap_calls',
		'material-icons swap_horiz',
		'material-icons swap_vert',
		'material-icons swap_vertical_circle',
		'material-icons switch_camera',
		'material-icons switch_video',
		'material-icons sync',
		'material-icons sync_disabled',
		'material-icons sync_problem',
		'material-icons system_update',
		'material-icons system_update_alt',
		'material-icons tab',
		'material-icons tab_unselected',
		'material-icons tablet',
		'material-icons tablet_android',
		'material-icons tablet_mac',
		'material-icons tag_faces',
		'material-icons tap_and_play',
		'material-icons terrain',
		'material-icons text_fields',
		'material-icons text_format',
		'material-icons textsms',
		'material-icons texture',
		'material-icons theaters',
		'material-icons thumb_down',
		'material-icons thumb_up',
		'material-icons thumbs_up_down',
		'material-icons time_to_leave',
		'material-icons timelapse',
		'material-icons timeline',
		'material-icons timer',
		'material-icons timer_10',
		'material-icons timer_3',
		'material-icons timer_off',
		'material-icons title',
		'material-icons toc',
		'material-icons today',
		'material-icons toll',
		'material-icons tonality',
		'material-icons touch_app',
		'material-icons toys',
		'material-icons track_changes',
		'material-icons traffic',
		'material-icons train',
		'material-icons tram',
		'material-icons transfer_within_a_station',
		'material-icons transform',
		'material-icons translate',
		'material-icons trending_down',
		'material-icons trending_flat',
		'material-icons trending_up',
		'material-icons tune',
		'material-icons turned_in',
		'material-icons turned_in_not',
		'material-icons tv',
		'material-icons unarchive',
		'material-icons undo',
		'material-icons unfold_less',
		'material-icons unfold_more',
		'material-icons update',
		'material-icons usb',
		'material-icons verified_user',
		'material-icons vertical_align_bottom',
		'material-icons vertical_align_center',
		'material-icons vertical_align_top',
		'material-icons vibration',
		'material-icons video_call',
		'material-icons video_label',
		'material-icons video_library',
		'material-icons videocam',
		'material-icons videocam_off',
		'material-icons videogame_asset',
		'material-icons view_agenda',
		'material-icons view_array',
		'material-icons view_carousel',
		'material-icons view_column',
		'material-icons view_comfy',
		'material-icons view_compact',
		'material-icons view_day',
		'material-icons view_headline',
		'material-icons view_list',
		'material-icons view_module',
		'material-icons view_quilt',
		'material-icons view_stream',
		'material-icons view_week',
		'material-icons vignette',
		'material-icons visibility',
		'material-icons visibility_off',
		'material-icons voice_chat',
		'material-icons voicemail',
		'material-icons volume_down',
		'material-icons volume_mute',
		'material-icons volume_off',
		'material-icons volume_up',
		'material-icons vpn_key',
		'material-icons vpn_lock',
		'material-icons wallpaper',
		'material-icons warning',
		'material-icons watch',
		'material-icons watch_later',
		'material-icons wb_auto',
		'material-icons wb_cloudy',
		'material-icons wb_incandescent',
		'material-icons wb_iridescent',
		'material-icons wb_sunny',
		'material-icons wc',
		'material-icons web',
		'material-icons web_asset',
		'material-icons weekend',
		'material-icons whatshot',
		'material-icons widgets',
		'material-icons wifi',
		'material-icons wifi_lock',
		'material-icons wifi_tethering',
		'material-icons work',
		'material-icons wrap_text',
		'material-icons youtube_searched_for',
		'material-icons zoom_in',
		'material-icons zoom_out',
		'material-icons zoom_out_map',
	);
}

add_filter( 'equip/icons/material', 'startapp_get_material_icons' );

/**
 * Returns the Font Awesome icons pack
 *
 * Required for Equip. Used in iconpicker. Slug is "fontawesome"
 *
 * @see equip_get_icons()
 *
 * @return array
 */
function startapp_get_fa_icons() {
	return array(
		'fa fa-glass',
		'fa fa-music',
		'fa fa-search',
		'fa fa-envelope-o',
		'fa fa-heart',
		'fa fa-star',
		'fa fa-star-o',
		'fa fa-user',
		'fa fa-film',
		'fa fa-th-large',
		'fa fa-th',
		'fa fa-th-list',
		'fa fa-check',
		'fa fa-remove',
		'fa fa-close',
		'fa fa-times',
		'fa fa-search-plus',
		'fa fa-search-minus',
		'fa fa-power-off',
		'fa fa-signal',
		'fa fa-gear',
		'fa fa-cog',
		'fa fa-trash-o',
		'fa fa-home',
		'fa fa-file-o',
		'fa fa-clock-o',
		'fa fa-road',
		'fa fa-download',
		'fa fa-arrow-circle-o-down',
		'fa fa-arrow-circle-o-up',
		'fa fa-inbox',
		'fa fa-play-circle-o',
		'fa fa-rotate-right',
		'fa fa-repeat',
		'fa fa-refresh',
		'fa fa-list-alt',
		'fa fa-lock',
		'fa fa-flag',
		'fa fa-headphones',
		'fa fa-volume-off',
		'fa fa-volume-down',
		'fa fa-volume-up',
		'fa fa-qrcode',
		'fa fa-barcode',
		'fa fa-tag',
		'fa fa-tags',
		'fa fa-book',
		'fa fa-bookmark',
		'fa fa-print',
		'fa fa-camera',
		'fa fa-font',
		'fa fa-bold',
		'fa fa-italic',
		'fa fa-text-height',
		'fa fa-text-width',
		'fa fa-align-left',
		'fa fa-align-center',
		'fa fa-align-right',
		'fa fa-align-justify',
		'fa fa-list',
		'fa fa-dedent',
		'fa fa-outdent',
		'fa fa-indent',
		'fa fa-video-camera',
		'fa fa-photo',
		'fa fa-image',
		'fa fa-picture-o',
		'fa fa-pencil',
		'fa fa-map-marker',
		'fa fa-adjust',
		'fa fa-tint',
		'fa fa-edit',
		'fa fa-pencil-square-o',
		'fa fa-share-square-o',
		'fa fa-check-square-o',
		'fa fa-arrows',
		'fa fa-step-backward',
		'fa fa-fast-backward',
		'fa fa-backward',
		'fa fa-play',
		'fa fa-pause',
		'fa fa-stop',
		'fa fa-forward',
		'fa fa-fast-forward',
		'fa fa-step-forward',
		'fa fa-eject',
		'fa fa-chevron-left',
		'fa fa-chevron-right',
		'fa fa-plus-circle',
		'fa fa-minus-circle',
		'fa fa-times-circle',
		'fa fa-check-circle',
		'fa fa-question-circle',
		'fa fa-info-circle',
		'fa fa-crosshairs',
		'fa fa-times-circle-o',
		'fa fa-check-circle-o',
		'fa fa-ban',
		'fa fa-arrow-left',
		'fa fa-arrow-right',
		'fa fa-arrow-up',
		'fa fa-arrow-down',
		'fa fa-mail-forward',
		'fa fa-share',
		'fa fa-expand',
		'fa fa-compress',
		'fa fa-plus',
		'fa fa-minus',
		'fa fa-asterisk',
		'fa fa-exclamation-circle',
		'fa fa-gift',
		'fa fa-leaf',
		'fa fa-fire',
		'fa fa-eye',
		'fa fa-eye-slash',
		'fa fa-warning',
		'fa fa-exclamation-triangle',
		'fa fa-plane',
		'fa fa-calendar',
		'fa fa-random',
		'fa fa-comment',
		'fa fa-magnet',
		'fa fa-chevron-up',
		'fa fa-chevron-down',
		'fa fa-retweet',
		'fa fa-shopping-cart',
		'fa fa-folder',
		'fa fa-folder-open',
		'fa fa-arrows-v',
		'fa fa-arrows-h',
		'fa fa-bar-chart-o',
		'fa fa-bar-chart',
		'fa fa-twitter-square',
		'fa fa-facebook-square',
		'fa fa-camera-retro',
		'fa fa-key',
		'fa fa-gears',
		'fa fa-cogs',
		'fa fa-comments',
		'fa fa-thumbs-o-up',
		'fa fa-thumbs-o-down',
		'fa fa-star-half',
		'fa fa-heart-o',
		'fa fa-sign-out',
		'fa fa-linkedin-square',
		'fa fa-thumb-tack',
		'fa fa-external-link',
		'fa fa-sign-in',
		'fa fa-trophy',
		'fa fa-github-square',
		'fa fa-upload',
		'fa fa-lemon-o',
		'fa fa-phone',
		'fa fa-square-o',
		'fa fa-bookmark-o',
		'fa fa-phone-square',
		'fa fa-twitter',
		'fa fa-facebook-f',
		'fa fa-facebook',
		'fa fa-github',
		'fa fa-unlock',
		'fa fa-credit-card',
		'fa fa-feed',
		'fa fa-rss',
		'fa fa-hdd-o',
		'fa fa-bullhorn',
		'fa fa-bell',
		'fa fa-certificate',
		'fa fa-hand-o-right',
		'fa fa-hand-o-left',
		'fa fa-hand-o-up',
		'fa fa-hand-o-down',
		'fa fa-arrow-circle-left',
		'fa fa-arrow-circle-right',
		'fa fa-arrow-circle-up',
		'fa fa-arrow-circle-down',
		'fa fa-globe',
		'fa fa-wrench',
		'fa fa-tasks',
		'fa fa-filter',
		'fa fa-briefcase',
		'fa fa-arrows-alt',
		'fa fa-group',
		'fa fa-users',
		'fa fa-chain',
		'fa fa-link',
		'fa fa-cloud',
		'fa fa-flask',
		'fa fa-cut',
		'fa fa-scissors',
		'fa fa-copy',
		'fa fa-files-o',
		'fa fa-paperclip',
		'fa fa-save',
		'fa fa-floppy-o',
		'fa fa-square',
		'fa fa-navicon',
		'fa fa-reorder',
		'fa fa-bars',
		'fa fa-list-ul',
		'fa fa-list-ol',
		'fa fa-strikethrough',
		'fa fa-underline',
		'fa fa-table',
		'fa fa-magic',
		'fa fa-truck',
		'fa fa-pinterest',
		'fa fa-pinterest-square',
		'fa fa-google-plus-square',
		'fa fa-google-plus',
		'fa fa-money',
		'fa fa-caret-down',
		'fa fa-caret-up',
		'fa fa-caret-left',
		'fa fa-caret-right',
		'fa fa-columns',
		'fa fa-unsorted',
		'fa fa-sort',
		'fa fa-sort-down',
		'fa fa-sort-desc',
		'fa fa-sort-up',
		'fa fa-sort-asc',
		'fa fa-envelope',
		'fa fa-linkedin',
		'fa fa-rotate-left',
		'fa fa-undo',
		'fa fa-legal',
		'fa fa-gavel',
		'fa fa-dashboard',
		'fa fa-tachometer',
		'fa fa-comment-o',
		'fa fa-comments-o',
		'fa fa-flash',
		'fa fa-bolt',
		'fa fa-sitemap',
		'fa fa-umbrella',
		'fa fa-paste',
		'fa fa-clipboard',
		'fa fa-lightbulb-o',
		'fa fa-exchange',
		'fa fa-cloud-download',
		'fa fa-cloud-upload',
		'fa fa-user-md',
		'fa fa-stethoscope',
		'fa fa-suitcase',
		'fa fa-bell-o',
		'fa fa-coffee',
		'fa fa-cutlery',
		'fa fa-file-text-o',
		'fa fa-building-o',
		'fa fa-hospital-o',
		'fa fa-ambulance',
		'fa fa-medkit',
		'fa fa-fighter-jet',
		'fa fa-beer',
		'fa fa-h-square',
		'fa fa-plus-square',
		'fa fa-angle-double-left',
		'fa fa-angle-double-right',
		'fa fa-angle-double-up',
		'fa fa-angle-double-down',
		'fa fa-angle-left',
		'fa fa-angle-right',
		'fa fa-angle-up',
		'fa fa-angle-down',
		'fa fa-desktop',
		'fa fa-laptop',
		'fa fa-tablet',
		'fa fa-mobile-phone',
		'fa fa-mobile',
		'fa fa-circle-o',
		'fa fa-quote-left',
		'fa fa-quote-right',
		'fa fa-spinner',
		'fa fa-circle',
		'fa fa-mail-reply',
		'fa fa-reply',
		'fa fa-github-alt',
		'fa fa-folder-o',
		'fa fa-folder-open-o',
		'fa fa-smile-o',
		'fa fa-frown-o',
		'fa fa-meh-o',
		'fa fa-gamepad',
		'fa fa-keyboard-o',
		'fa fa-flag-o',
		'fa fa-flag-checkered',
		'fa fa-terminal',
		'fa fa-code',
		'fa fa-mail-reply-all',
		'fa fa-reply-all',
		'fa fa-star-half-empty',
		'fa fa-star-half-full',
		'fa fa-star-half-o',
		'fa fa-location-arrow',
		'fa fa-crop',
		'fa fa-code-fork',
		'fa fa-unlink',
		'fa fa-chain-broken',
		'fa fa-question',
		'fa fa-info',
		'fa fa-exclamation',
		'fa fa-superscript',
		'fa fa-subscript',
		'fa fa-eraser',
		'fa fa-puzzle-piece',
		'fa fa-microphone',
		'fa fa-microphone-slash',
		'fa fa-shield',
		'fa fa-calendar-o',
		'fa fa-fire-extinguisher',
		'fa fa-rocket',
		'fa fa-maxcdn',
		'fa fa-chevron-circle-left',
		'fa fa-chevron-circle-right',
		'fa fa-chevron-circle-up',
		'fa fa-chevron-circle-down',
		'fa fa-html5',
		'fa fa-css3',
		'fa fa-anchor',
		'fa fa-unlock-alt',
		'fa fa-bullseye',
		'fa fa-ellipsis-h',
		'fa fa-ellipsis-v',
		'fa fa-rss-square',
		'fa fa-play-circle',
		'fa fa-ticket',
		'fa fa-minus-square',
		'fa fa-minus-square-o',
		'fa fa-level-up',
		'fa fa-level-down',
		'fa fa-check-square',
		'fa fa-pencil-square',
		'fa fa-external-link-square',
		'fa fa-share-square',
		'fa fa-compass',
		'fa fa-toggle-down',
		'fa fa-caret-square-o-down',
		'fa fa-toggle-up',
		'fa fa-caret-square-o-up',
		'fa fa-toggle-right',
		'fa fa-caret-square-o-right',
		'fa fa-euro',
		'fa fa-eur',
		'fa fa-gbp',
		'fa fa-dollar',
		'fa fa-usd',
		'fa fa-rupee',
		'fa fa-inr',
		'fa fa-cny',
		'fa fa-rmb',
		'fa fa-yen',
		'fa fa-jpy',
		'fa fa-ruble',
		'fa fa-rouble',
		'fa fa-rub',
		'fa fa-won',
		'fa fa-krw',
		'fa fa-bitcoin',
		'fa fa-btc',
		'fa fa-file',
		'fa fa-file-text',
		'fa fa-sort-alpha-asc',
		'fa fa-sort-alpha-desc',
		'fa fa-sort-amount-asc',
		'fa fa-sort-amount-desc',
		'fa fa-sort-numeric-asc',
		'fa fa-sort-numeric-desc',
		'fa fa-thumbs-up',
		'fa fa-thumbs-down',
		'fa fa-youtube-square',
		'fa fa-youtube',
		'fa fa-xing',
		'fa fa-xing-square',
		'fa fa-youtube-play',
		'fa fa-dropbox',
		'fa fa-stack-overflow',
		'fa fa-instagram',
		'fa fa-flickr',
		'fa fa-adn',
		'fa fa-bitbucket',
		'fa fa-bitbucket-square',
		'fa fa-tumblr',
		'fa fa-tumblr-square',
		'fa fa-long-arrow-down',
		'fa fa-long-arrow-up',
		'fa fa-long-arrow-left',
		'fa fa-long-arrow-right',
		'fa fa-apple',
		'fa fa-windows',
		'fa fa-android',
		'fa fa-linux',
		'fa fa-dribbble',
		'fa fa-skype',
		'fa fa-foursquare',
		'fa fa-trello',
		'fa fa-female',
		'fa fa-male',
		'fa fa-gittip',
		'fa fa-gratipay',
		'fa fa-sun-o',
		'fa fa-moon-o',
		'fa fa-archive',
		'fa fa-bug',
		'fa fa-vk',
		'fa fa-weibo',
		'fa fa-renren',
		'fa fa-pagelines',
		'fa fa-stack-exchange',
		'fa fa-arrow-circle-o-right',
		'fa fa-arrow-circle-o-left',
		'fa fa-toggle-left',
		'fa fa-caret-square-o-left',
		'fa fa-dot-circle-o',
		'fa fa-wheelchair',
		'fa fa-vimeo-square',
		'fa fa-turkish-lira',
		'fa fa-try',
		'fa fa-plus-square-o',
		'fa fa-space-shuttle',
		'fa fa-slack',
		'fa fa-envelope-square',
		'fa fa-wordpress',
		'fa fa-openid',
		'fa fa-institution',
		'fa fa-bank',
		'fa fa-university',
		'fa fa-mortar-board',
		'fa fa-graduation-cap',
		'fa fa-yahoo',
		'fa fa-google',
		'fa fa-reddit',
		'fa fa-reddit-square',
		'fa fa-stumbleupon-circle',
		'fa fa-stumbleupon',
		'fa fa-delicious',
		'fa fa-digg',
		'fa fa-pied-piper',
		'fa fa-pied-piper-alt',
		'fa fa-drupal',
		'fa fa-joomla',
		'fa fa-language',
		'fa fa-fax',
		'fa fa-building',
		'fa fa-child',
		'fa fa-paw',
		'fa fa-spoon',
		'fa fa-cube',
		'fa fa-cubes',
		'fa fa-behance',
		'fa fa-behance-square',
		'fa fa-steam',
		'fa fa-steam-square',
		'fa fa-recycle',
		'fa fa-automobile',
		'fa fa-car',
		'fa fa-cab',
		'fa fa-taxi',
		'fa fa-tree',
		'fa fa-spotify',
		'fa fa-deviantart',
		'fa fa-soundcloud',
		'fa fa-database',
		'fa fa-file-pdf-o',
		'fa fa-file-word-o',
		'fa fa-file-excel-o',
		'fa fa-file-powerpoint-o',
		'fa fa-file-photo-o',
		'fa fa-file-picture-o',
		'fa fa-file-image-o',
		'fa fa-file-zip-o',
		'fa fa-file-archive-o',
		'fa fa-file-sound-o',
		'fa fa-file-audio-o',
		'fa fa-file-movie-o',
		'fa fa-file-video-o',
		'fa fa-file-code-o',
		'fa fa-vine',
		'fa fa-codepen',
		'fa fa-jsfiddle',
		'fa fa-life-bouy',
		'fa fa-life-buoy',
		'fa fa-life-saver',
		'fa fa-support',
		'fa fa-life-ring',
		'fa fa-circle-o-notch',
		'fa fa-ra',
		'fa fa-rebel',
		'fa fa-ge',
		'fa fa-empire',
		'fa fa-git-square',
		'fa fa-git',
		'fa fa-y-combinator-square',
		'fa fa-yc-square',
		'fa fa-hacker-news',
		'fa fa-tencent-weibo',
		'fa fa-qq',
		'fa fa-wechat',
		'fa fa-weixin',
		'fa fa-send',
		'fa fa-paper-plane',
		'fa fa-send-o',
		'fa fa-paper-plane-o',
		'fa fa-history',
		'fa fa-circle-thin',
		'fa fa-header',
		'fa fa-paragraph',
		'fa fa-sliders',
		'fa fa-share-alt',
		'fa fa-share-alt-square',
		'fa fa-bomb',
		'fa fa-soccer-ball-o',
		'fa fa-futbol-o',
		'fa fa-tty',
		'fa fa-binoculars',
		'fa fa-plug',
		'fa fa-slideshare',
		'fa fa-twitch',
		'fa fa-yelp',
		'fa fa-newspaper-o',
		'fa fa-wifi',
		'fa fa-calculator',
		'fa fa-paypal',
		'fa fa-google-wallet',
		'fa fa-cc-visa',
		'fa fa-cc-mastercard',
		'fa fa-cc-discover',
		'fa fa-cc-amex',
		'fa fa-cc-paypal',
		'fa fa-cc-stripe',
		'fa fa-bell-slash',
		'fa fa-bell-slash-o',
		'fa fa-trash',
		'fa fa-copyright',
		'fa fa-at',
		'fa fa-eyedropper',
		'fa fa-paint-brush',
		'fa fa-birthday-cake',
		'fa fa-area-chart',
		'fa fa-pie-chart',
		'fa fa-line-chart',
		'fa fa-lastfm',
		'fa fa-lastfm-square',
		'fa fa-toggle-off',
		'fa fa-toggle-on',
		'fa fa-bicycle',
		'fa fa-bus',
		'fa fa-ioxhost',
		'fa fa-angellist',
		'fa fa-cc',
		'fa fa-shekel',
		'fa fa-sheqel',
		'fa fa-ils',
		'fa fa-meanpath',
		'fa fa-buysellads',
		'fa fa-connectdevelop',
		'fa fa-dashcube',
		'fa fa-forumbee',
		'fa fa-leanpub',
		'fa fa-sellsy',
		'fa fa-shirtsinbulk',
		'fa fa-simplybuilt',
		'fa fa-skyatlas',
		'fa fa-cart-plus',
		'fa fa-cart-arrow-down',
		'fa fa-diamond',
		'fa fa-ship',
		'fa fa-user-secret',
		'fa fa-motorcycle',
		'fa fa-street-view',
		'fa fa-heartbeat',
		'fa fa-venus',
		'fa fa-mars',
		'fa fa-mercury',
		'fa fa-intersex',
		'fa fa-transgender',
		'fa fa-transgender-alt',
		'fa fa-venus-double',
		'fa fa-mars-double',
		'fa fa-venus-mars',
		'fa fa-mars-stroke',
		'fa fa-mars-stroke-v',
		'fa fa-mars-stroke-h',
		'fa fa-neuter',
		'fa fa-genderless',
		'fa fa-facebook-official',
		'fa fa-pinterest-p',
		'fa fa-whatsapp',
		'fa fa-server',
		'fa fa-user-plus',
		'fa fa-user-times',
		'fa fa-hotel',
		'fa fa-bed',
		'fa fa-viacoin',
		'fa fa-train',
		'fa fa-subway',
		'fa fa-medium',
		'fa fa-yc',
		'fa fa-y-combinator',
		'fa fa-optin-monster',
		'fa fa-opencart',
		'fa fa-expeditedssl',
		'fa fa-battery-4',
		'fa fa-battery-full',
		'fa fa-battery-3',
		'fa fa-battery-three-quarters',
		'fa fa-battery-2',
		'fa fa-battery-half',
		'fa fa-battery-1',
		'fa fa-battery-quarter',
		'fa fa-battery-0',
		'fa fa-battery-empty',
		'fa fa-mouse-pointer',
		'fa fa-i-cursor',
		'fa fa-object-group',
		'fa fa-object-ungroup',
		'fa fa-sticky-note',
		'fa fa-sticky-note-o',
		'fa fa-cc-jcb',
		'fa fa-cc-diners-club',
		'fa fa-clone',
		'fa fa-balance-scale',
		'fa fa-hourglass-o',
		'fa fa-hourglass-1',
		'fa fa-hourglass-start',
		'fa fa-hourglass-2',
		'fa fa-hourglass-half',
		'fa fa-hourglass-3',
		'fa fa-hourglass-end',
		'fa fa-hourglass',
		'fa fa-hand-grab-o',
		'fa fa-hand-rock-o',
		'fa fa-hand-stop-o',
		'fa fa-hand-paper-o',
		'fa fa-hand-scissors-o',
		'fa fa-hand-lizard-o',
		'fa fa-hand-spock-o',
		'fa fa-hand-pointer-o',
		'fa fa-hand-peace-o',
		'fa fa-trademark',
		'fa fa-registered',
		'fa fa-creative-commons',
		'fa fa-gg',
		'fa fa-gg-circle',
		'fa fa-tripadvisor',
		'fa fa-odnoklassniki',
		'fa fa-odnoklassniki-square',
		'fa fa-get-pocket',
		'fa fa-wikipedia-w',
		'fa fa-safari',
		'fa fa-chrome',
		'fa fa-firefox',
		'fa fa-opera',
		'fa fa-internet-explorer',
		'fa fa-tv',
		'fa fa-television',
		'fa fa-contao',
		'fa fa-500px',
		'fa fa-amazon',
		'fa fa-calendar-plus-o',
		'fa fa-calendar-minus-o',
		'fa fa-calendar-times-o',
		'fa fa-calendar-check-o',
		'fa fa-industry',
		'fa fa-map-pin',
		'fa fa-map-signs',
		'fa fa-map-o',
		'fa fa-map',
		'fa fa-commenting',
		'fa fa-commenting-o',
		'fa fa-houzz',
		'fa fa-vimeo',
		'fa fa-black-tie',
		'fa fa-fonticons',
	);
}

add_filter( 'equip/icons/fontawesome', 'startapp_get_fa_icons' );

/**
 * Adds custom classes to the array of body classes.
 *
 * @param array $classes Classes for the body element.
 *
 * @return array
 */
function startapp_body_classes( $classes ) {
	// Adds a class of group-blog to blogs with more than 1 published author.
	if ( is_multi_author() ) {
		$classes[] = 'group-blog';
	}

	// Adds a class of hfeed to non-singular pages.
	if ( ! is_singular() ) {
		$classes[] = 'hfeed';
	}

	$classes[] = 'body-' . startapp_header_layout();

	return $classes;
}

add_filter( 'body_class', 'startapp_body_classes' );

/**
 * Returns the slug name for Theme Options
 *
 * @see startapp_get_option()
 *
 * @return string
 */
function startapp_theme_options_slug() {
	return STARTAPP_OPTIONS;
}

add_filter( 'startapp_get_option_slug', 'startapp_theme_options_slug' );

/**
 * Returns the slug name for Page Settings meta box
 *
 * @see startapp_get_setting()
 *
 * @return string
 */
function startapp_page_settings_slug() {
	return STARTAPP_PAGE_SETTINGS;
}

add_filter( 'startapp_get_setting_slug', 'startapp_page_settings_slug' );

/**
 * Returns the all Page Settings keys and their default values
 *
 * @see startapp_get_setting()
 *
 * @return array
 */
function startapp_page_settings_defaults() {
	return array(
		'single_layout'             => 'default',
		'cover'                     => 0,
		'cover_height'              => 400,
		'cover_parallax'            => 1,
		'cover_parallax_type'       => 'scroll',
		'cover_parallax_speed'      => 0.4,
		'single_is_tile_author'     => 'default',
		'single_is_post_author'     => 'default',
		'single_is_shares'          => 'default',
		'single_thumbnail_position' => 'default',
		'header_is_page_title'      => 'default',
		'page_title_skin'           => 'dark',
		'page_title_size'           => 'normal',
		'page_title_overlap'        => 0,
		'page_title_bg'             => 0,
		'page_title_bg_color'       => '',
		'page_title_parallax'       => 0,
		'page_title_parallax_type'  => 'scroll',
		'page_title_parallax_speed' => 0.4,
		'page_title_parallax_video' => '',
		'custom_logo'               => 0,
		'header_layout'             => 'default',
	);
}

add_filter( 'startapp_get_setting_defaults', 'startapp_page_settings_defaults' );

/**
 * Remove all widgets except StartApp Button from the Navbar Buttons sidebar
 *
 * On both admin and frontend
 *
 * @param array $sidebars_widgets A list of sidebars and their widgets
 *
 * @return array
 */
function startapp_navbar_buttons_sidebar( $sidebars_widgets ) {
	$sidebar = 'sidebar-navbar-buttons';
	if ( empty( $sidebars_widgets[ $sidebar ] ) ) {
		return $sidebars_widgets;
	}

	foreach ( (array) $sidebars_widgets[ $sidebar ] as $k => $widget ) {
		if ( false === stripos( $widget, 'startapp_button' ) ) {
			unset( $sidebars_widgets[ $sidebar ][ $k ] );
		}

		continue;
	}

	return $sidebars_widgets;
}

add_filter( 'sidebars_widgets', 'startapp_navbar_buttons_sidebar' );

/**
 * Restrict the Mega Menu sidebars with the white list of widgets:
 * - Categories
 * - Pages
 * - Custom Menu
 * - StartApp Button
 * - Black Studio TinyMCE
 *
 * @param array $sidebars_widgets A list of sidebars and their widgets
 *
 * @return array
 */
function startapp_mega_menu_sidebar( $sidebars_widgets ) {
	if ( empty( $sidebars_widgets ) ) {
		return $sidebars_widgets;
	}

	/**
	 * Filter the list of allowed widgets
	 */
	$allowed = apply_filters( 'startapp_mega_menu_allowed_widgets', array(
		'startapp_button',
		'categories',
		'nav_menu',
		'pages',
		'black-studio-tinymce',
	) );

	foreach ( $sidebars_widgets as $id => $sidebar ) {
		// only mega menu sidebars
		if ( false === strpos( $id, 'mega-menu' ) || empty( $sidebar ) ) {
			continue;
		}

		$allowed_regex           = '/(' . implode( '|', $allowed ) . ')/i';
		$sidebars_widgets[ $id ] = array_filter( $sidebar, function( $widget ) use ( $allowed_regex ) {
			return preg_match( $allowed_regex, $widget );
		} );
	}

	return $sidebars_widgets;
}

add_filter( 'sidebars_widgets', 'startapp_mega_menu_sidebar' );

/**
 * Remove the Featured Image meta box from pages
 *
 * This theme does not use the featured images on pages
 *
 * @see startapp_page_title()
 */
function startapp_remove_featured_image_on_pages() {
	remove_meta_box( 'postimagediv', 'page', 'side' );
}

if ( is_admin() ) {
	add_action( 'do_meta_boxes', 'startapp_remove_featured_image_on_pages' );
}

/**
 * Returns the array of custom links provided in Theme Options > Advanced section
 *
 * @see startapp_add_advanced_options()
 *
 * @return array
 */
function startapp_get_custom_font_icons() {
	$links = startapp_get_option( 'advanced_custom_font_icons', array() );
	if ( empty( $links ) ) {
		return array();
	}

	return explode( "\r\n", $links );
}

/**
 * Wrap the content to div.container if vc_row shortcode not used.
 *
 * Applicable only for post, page or startapp_portfolio post type.
 *
 * @param string $content Content
 *
 * @return string
 */
function startapp_maybe_wrap_to_container( $content ) {
	if ( is_singular( array( 'post', 'page', 'startapp_portfolio' ) )
	     && false === strpos( $content, 'fw-section' )
	) {
		return '<div class="container">' . $content . '</div>';
	}

	return $content;
}

add_filter( 'the_content', 'startapp_maybe_wrap_to_container', 100 );

/**
 * Load More posts in blog
 *
 * AJAX callback for action "startapp_load_posts"
 */
function startapp_load_posts() {
	if ( empty( $_POST['nonce'] ) || ! wp_verify_nonce( $_POST['nonce'], 'startapp-ajax' ) ) {
		wp_send_json_error( esc_html_x( 'Wrong nonce', 'ajax request', 'startapp' ) );
	}

	$per_page = (int) get_option( 'posts_per_page' );
	$paged    = (int) $_POST['page'];
	$type     = sanitize_key( $_POST['type'] );
	$tile     = ( $type === 'list-no' ) ? 'horizontal' : 'tile';

	$is_isotope = in_array( $type, array( 'grid-left', 'grid-right', 'grid-no' ) );

	$query = new WP_Query( array(
		'post_type'           => 'post',
		'post_status'         => 'publish',
		'paged'               => $paged,
		'posts_per_page'      => $per_page,
		'ignore_sticky_posts' => true,
	) );

	if ( $query->have_posts() ) {
		$posts = array();
		while( $query->have_posts() ) {
			$query->the_post();
			ob_start();
			if ( $is_isotope ) {
				echo '<div class="grid-item">';
				get_template_part( 'template-parts/blog/post', 'tile' );
				echo '</div>';
			} else {
				get_template_part( 'template-parts/blog/post', $tile );
			}

			$posts[] = startapp_content_encode( ob_get_clean() );
		}
		wp_reset_postdata();
		wp_send_json_success( $posts );
	}

	wp_send_json_error( esc_html_x( 'Posts not found', 'ajax request', 'startapp' ) );
}

if ( is_admin() ) {
	add_action( 'wp_ajax_startapp_load_posts', 'startapp_load_posts' );
	add_action( 'wp_ajax_nopriv_startapp_load_posts', 'startapp_load_posts' );
}

/**
 * Replace class .navbar-ghost-light with .navbar-ghost-dark for pages:
 * blog, search, archive and 404
 *
 * Because "Navbar Ghost Light" do not work correctly
 * without the background image on the Page Title
 *
 * @param array $classes CSS classes
 *
 * @return mixed
 */
function startapp_get_rid_of_light_header( $classes ) {
	// do nothing if we don't have class .navbar-ghost-light
	$key = array_search( 'navbar-ghost-light', $classes, true );
	if ( false === $key ) {
		return $classes;
	}

	if ( ( is_front_page() && is_home() ) || is_search() || is_archive() || is_404() ) {
		unset( $classes[ $key ] );
		$classes[] = 'navbar-ghost-dark';
	}

	return $classes;
}

add_filter( 'startapp_header_class', 'startapp_get_rid_of_light_header' );

/**
 * Returns the path to stylesheet.
 *
 * May return path to compiled CSS (with high priority)
 * or normal css as a fallback.
 *
 * @see startapp_theme_options()
 * @see startapp_scripts()
 * @see STARTAPP_COMPILED
 *
 * @return string
 */
function startapp_stylesheet_uri() {
	// Maybe enqueue compiled css or a fallback
	$c = get_option( STARTAPP_COMPILED );
	if ( is_array( $c )
	     && array_key_exists( 'path', $c )
	     && is_readable( $c['path'] )
	     && 0 !== filesize( $c['path'] )
	) {
		$stylesheet = esc_url( $c['url'] );
	} else {
		$stylesheet = STARTAPP_TEMPLATE_URI . '/stylesheets/theme.min.css';
	}

	return $stylesheet;
}

/**
 * Highlight the search results
 *
 * Center and highlight the matching results and trim the string.
 *
 * @link http://stackoverflow.com/questions/1292121/how-to-generate-the-snippet-like-generated-by-google-with-php-and-mysql
 *
 * @param string $content Excerpt
 *
 * @return string
 */
function startapp_search_snippet_highlight( $content ) {
	if ( ! empty( $content ) && is_search() && in_the_loop() ) {
		$content = strip_tags( $content );
		$search  = trim( get_search_query() );
		$radius  = 100;
		$ending  = '...';

		if ( empty( $search ) ) {
			$excerpt_length = apply_filters( 'excerpt_length', 55 );
			$excerpt_more   = apply_filters( 'excerpt_more', ' ' . '[&hellip;]' );

			return wp_trim_words( $content, $excerpt_length, $excerpt_more );
		}

		$s_length = strlen( $search );
		$e_length = strlen( $content );

		if ( $radius < $s_length ) {
			$radius = $s_length;
		}

		$pos     = 0;
		$phrases = array_filter( explode( ' ', $search ) );
		foreach ( (array) $phrases as $phrase ) {
			$pos = strpos( strtolower( $content ), strtolower( $phrase ) );
			if ( $pos > - 1 ) {
				break;
			}
		}
		unset( $phrase, $phrases );

		$start = 0;
		if ( $pos > $radius ) {
			$start = $pos - $radius;
		}

		$end = $pos + $s_length + $radius;
		if ( $end >= $e_length ) {
			$end = $e_length;
		}

		$snippet = substr( $content, $start, $end - $start );
		if ( $start != 0 ) {
			$snippet = substr_replace( $snippet, $ending, 0, $s_length );
		}

		if ( $end != $e_length ) {
			$snippet = substr_replace( $snippet, $ending, - $s_length );
		}

		$keys    = implode( '|', explode( ' ', $search ) );
		$snippet = preg_replace( '/(' . $keys . ')/iu', '<span class="search-highlight">$0</span>', $snippet );
		$snippet = '<p class="post-excerpt">' . $snippet . '</p>';

		$content = $snippet;

		unset( $snippet, $s_length, $e_length, $start, $end );
	}

	return $content;
}

add_filter( 'the_content', 'startapp_search_snippet_highlight', 100 );

/**
 * Highlight the search results in title
 *
 * @param string $title Title
 *
 * @return mixed
 */
function startapp_search_title_highlight( $title ) {
	if ( ! empty( $title ) && is_search() && in_the_loop() ) {
		$keys  = implode( '|', explode( ' ', get_search_query() ) );
		$title = preg_replace( '/(' . $keys . ')/iu', '<span class="search-highlight">$0</span>', $title );
	}

	return $title;
}

add_filter( 'the_title', 'startapp_search_title_highlight', 100 );

/**
 * Fix the empty search
 *
 * @param array $query_vars
 *
 * @return mixed
 */
function startapp_prevent_empty_search( $query_vars ) {
	if ( isset( $_GET['s'] ) && empty( $_GET['s'] ) ) {
		$query_vars['s'] = " ";
	}

	return $query_vars;
}

add_filter( 'request', 'startapp_prevent_empty_search' );

/**
 * Disable the premium plugins update notifications
 *
 * @param object $value
 *
 * @return mixed
 */
function startapp_no_premium_update( $value ) {
	if ( empty( $value->response ) ) {
		return $value;
	}

	$blocked = array(
		'js_composer/js_composer.php',
		'revslider/revslider.php'
	);

	$value->response = array_diff_key( $value->response, array_flip( $blocked ) );

	return $value;
}

add_filter( 'site_transient_update_plugins', 'startapp_no_premium_update' );

