<?php
/**
 * Equip builtin actions
 *
 * @author  8guild
 * @package Equip
 */

/**
 * Built-in fileds
 *
 * @param array $map A list of fields
 *
 * @return array
 */
function _equip_builtin_fields( $map = [] ) {
	return array_merge( array(
		'text'         => EQUIP_FIELDS_DIR . '/TextField.php',
		'textarea'     => EQUIP_FIELDS_DIR . '/TextareaField.php',
		'select'       => EQUIP_FIELDS_DIR . '/SelectField.php',
		'switch'       => EQUIP_FIELDS_DIR . '/SwitchField.php',
		'slider'       => EQUIP_FIELDS_DIR . '/SliderField.php',
		'editor'       => EQUIP_FIELDS_DIR . '/EditorField.php',
		'image_select' => EQUIP_FIELDS_DIR . '/ImageSelectField.php',
		'color'        => EQUIP_FIELDS_DIR . '/ColorField.php',
		'media'        => EQUIP_FIELDS_DIR . '/MediaField.php',
		'icon'         => EQUIP_FIELDS_DIR . '/IconField.php',
		'raw_text'     => EQUIP_FIELDS_DIR . '/RawTextField.php',
		'socials'      => EQUIP_FIELDS_DIR . '/SocialsField.php',
		'combobox'     => EQUIP_FIELDS_DIR . '/ComboboxField.php', // @since 0.7.4
		'custom_css'   => EQUIP_FIELDS_DIR . '/CustomCssField.php', // @since 0.7.5
		'google_fonts' => EQUIP_FIELDS_DIR . '/GoogleFontsField.php', // @since 0.7.8
		'gradient'     => EQUIP_FIELDS_DIR . '/GradientField.php', // @since 0.7.15
	), $map );
}

add_filter( 'equip/factory/field/map', '_equip_builtin_fields', - 1 );

/**
 * Built-in engines
 *
 * @param array $map A list of engines
 *
 * @return array
 */
function _equip_builtin_engines( $map = [] ) {
	return array_merge( [
		'default'  => EQUIP_ENGINES_DIR . '/DefaultEngine.php',
		'options'  => EQUIP_ENGINES_DIR . '/OptionsEngine.php',
		'metabox'  => EQUIP_ENGINES_DIR . '/MetaboxEngine.php',
		'menu'     => EQUIP_ENGINES_DIR . '/MenuEngine.php',
		'user'     => EQUIP_ENGINES_DIR . '/UserEngine.php',
		'section'  => EQUIP_ENGINES_DIR . '/SectionEngine.php',
		'anchor'   => EQUIP_ENGINES_DIR . '/AnchorEngine.php',
		'row'      => EQUIP_ENGINES_DIR . '/RowEngine.php',
		'column'   => EQUIP_ENGINES_DIR . '/ColumnEngine.php',
		'offset'   => EQUIP_ENGINES_DIR . '/OffsetEngine.php',
		'field'    => EQUIP_ENGINES_DIR . '/FieldEngine.php',
		'tab'      => EQUIP_ENGINES_DIR . '/TabEngine.php',
		'category' => EQUIP_ENGINES_DIR . '/CategoryEngine.php', // @since 0.7.10z
	], $map );
}

add_filter( 'equip/factory/engine/map', '_equip_builtin_engines', - 1 );

/**
 * Built-in modules
 *
 * @param array $map
 *
 * @return array
 */
function _equip_builtin_modules( $map = [] ) {
	return array_merge( [
		'options'  => EQUIP_MODULES_DIR . '/OptionsModule.php',
		'metabox'  => EQUIP_MODULES_DIR . '/MetaboxModule.php',
		'menu'     => EQUIP_MODULES_DIR . '/MenuModule.php',
		'user'     => EQUIP_MODULES_DIR . '/UserModule.php',
		'category' => EQUIP_MODULES_DIR . '/CategoryModule.php', // @since 0.7.10
	], $map );
}

add_filter( 'equip/factory/module/map', '_equip_builtin_modules', - 1 );

/**
 * Built-in layouts
 *
 * @param array $map
 *
 * @return array
 */
function _equip_builtin_layouts( $map = [] ) {
	return array_merge( [
		'field'    => EQUIP_LAYOUTS_DIR . '/FieldLayout.php',
		'column'   => EQUIP_LAYOUTS_DIR . '/ColumnLayout.php',
		'row'      => EQUIP_LAYOUTS_DIR . '/RowLayout.php',
		'metabox'  => EQUIP_LAYOUTS_DIR . '/MetaboxLayout.php',
		'options'  => EQUIP_LAYOUTS_DIR . '/OptionsLayout.php',
		'user'     => EQUIP_LAYOUTS_DIR . '/UserLayout.php',
		'menu'     => EQUIP_LAYOUTS_DIR . '/MenuLayout.php',
		'section'  => EQUIP_LAYOUTS_DIR . '/SectionLayout.php',
		'anchor'   => EQUIP_LAYOUTS_DIR . '/AnchorLayout.php',
		'offset'   => EQUIP_LAYOUTS_DIR . '/OffsetLayout.php',
		'tab'      => EQUIP_LAYOUTS_DIR . '/TabLayout.php',
		'category' => EQUIP_LAYOUTS_DIR . '/CategoryLayout.php',
	], $map );
}

add_filter( 'equip/factory/layout/map', '_equip_builtin_layouts', - 1 );

/**
 * Built-in services
 *
 * @param array $map
 *
 * @return array
 */
function _equip_builtin_services( $map = [] ) {
	return array_merge( [
		'sanitizer' => EQUIP_SERVICES_DIR . '/Sanitizer.php',
		'escaper'   => EQUIP_SERVICES_DIR . '/Escaper.php',
		'enqueue'   => EQUIP_SERVICES_DIR . '/Enqueue.php',
		'required'  => EQUIP_SERVICES_DIR . '/Required.php',
		'master'    => EQUIP_SERVICES_DIR . '/Master.php',
	], $map );
}

add_filter( 'equip/factory/service/map', '_equip_builtin_services' );

/**
 * Enqueue scripts and styles
 *
 * @param string $hook Current page hook
 */
function _equip_admin_enqueue_scripts( $hook ) {

	wp_enqueue_style( 'selectize', EQUIP_ASSETS_URI . '/css/vendor/selectize.css', [], null );
	wp_enqueue_style( 'selectize-default', EQUIP_ASSETS_URI . '/css/vendor/selectize.default.css', [ 'selectize' ], null );
	wp_enqueue_style( 'toast', EQUIP_ASSETS_URI . '/css/vendor/jquery.toast.min.css', null, null );
	wp_enqueue_style( 'codemirror', EQUIP_ASSETS_URI . '/codemirror/lib/codemirror.css', null, null );
	wp_enqueue_style( 'equip', EQUIP_ASSETS_URI . '/css/equip.css', null, null );

	wp_enqueue_script( 'waypoints', EQUIP_ASSETS_URI . '/js/vendor/waypoints.min.js', [ 'jquery' ], null, true );
	wp_enqueue_script( 'jquery-easing', EQUIP_ASSETS_URI . '/js/vendor/jquery.easing.min.js', [ 'jquery' ], null, true );
	wp_enqueue_script( 'scrollspy', EQUIP_ASSETS_URI . '/js/vendor/scrollspy.js', [ 'jquery' ], null, true );
	wp_enqueue_script( 'selectize', EQUIP_ASSETS_URI . '/js/vendor/selectize.min.js', [ 'jquery' ], null, true );
	wp_enqueue_script( 'wNumb', EQUIP_ASSETS_URI . '/js/vendor/wNumb.js', null, null, true );
	wp_enqueue_script( 'nouislider', EQUIP_ASSETS_URI . '/js/vendor/nouislider.min.js', [ 'jquery' ], null, true );
	wp_enqueue_script( 'tabs', EQUIP_ASSETS_URI . '/js/vendor/tabs.min.js', [ 'jquery' ], null, true );
	wp_enqueue_script( 'resize', EQUIP_ASSETS_URI . '/js/vendor/resize.min.js', null, null, true );
	wp_enqueue_script( 'velocity', EQUIP_ASSETS_URI . '/js/vendor/velocity.min.js', [ 'jquery' ], null, true );
	wp_enqueue_script( 'toast', EQUIP_ASSETS_URI . '/js/vendor/jquery.toast.min.js', [ 'jquery' ], null, true );

	wp_enqueue_style( 'popup-js', EQUIP_ASSETS_URI . '/css/vendor/popup.css', [], null );
	wp_register_script( 'popup-js', EQUIP_ASSETS_URI . '/js/vendor/jquery.popup.min.js', [ 'jquery' ], null, true );

	wp_register_script( 'filtr', EQUIP_ASSETS_URI . '/js/vendor/jquery.filtr.min.js', [ 'jquery' ], null, true );

	// color field
	wp_enqueue_style( 'spectrum', EQUIP_ASSETS_URI . '/fields/color/spectrum.css', [], null, false );
	wp_enqueue_script( 'spectrum', EQUIP_ASSETS_URI . '/fields/color/spectrum.js', [ 'jquery' ], null, true );
	wp_enqueue_script( 'spectrum-init', EQUIP_ASSETS_URI . '/fields/color/init.js', [ 'jquery' ], null, true );

	// media field
	wp_enqueue_script( 'equip-media', EQUIP_ASSETS_URI . '/fields/media/jquery.equipMedia.js', [
		'jquery',
		'jquery-ui-sortable'
	], null, true );

	// icon field
	wp_enqueue_script( 'equip-icon', EQUIP_ASSETS_URI . '/fields/icon/jquery.equipIcon.js', [
		'jquery',
		'popup-js',
		'filtr'
	], null, true );

	// Google Fonts field
	wp_enqueue_script( 'equip-google-fonts', EQUIP_ASSETS_URI . '/fields/google_fonts/jquery.equipGoogleFonts.js', [
		'jquery',
		'popup-js',
		'filtr'
	], null, true );

	// Socials field
	wp_enqueue_script( 'equip-socials', EQUIP_ASSETS_URI . '/fields/socials/jquery.equipSocials.js', [ 'jquery' ], null, true );

	// CodeMirror
	wp_enqueue_script( 'codemirror', EQUIP_ASSETS_URI . '/codemirror/lib/codemirror.js', [], null, true );
	wp_enqueue_script( 'codemirror-css', EQUIP_ASSETS_URI . '/codemirror/mode/css/css.js', [ 'codemirror' ], null, true );

	wp_enqueue_script( 'codemirror-addon-closebrackets', EQUIP_ASSETS_URI . '/codemirror/addon/edit/closebrackets.js', [ 'codemirror' ], null, true );
	wp_enqueue_script( 'codemirror-addon-matchbrackets', EQUIP_ASSETS_URI . '/codemirror/addon/edit/matchbrackets.js', [ 'codemirror' ], null, true );
	wp_enqueue_script( 'codemirror-addon-continuelist', EQUIP_ASSETS_URI . '/codemirror/addon/edit/continuelist.js', [ 'codemirror' ], null, true );
	wp_enqueue_script( 'codemirror-addon-active-line', EQUIP_ASSETS_URI . '/codemirror/addon/selection/active-line.js', [ 'codemirror' ], null, true );
	wp_enqueue_script( 'codemirror-addon-show-hint', EQUIP_ASSETS_URI . '/codemirror/addon/hint/show-hint.js', [ 'codemirror' ], null, true );
	wp_enqueue_style( 'codemirror-addon-show-hint', EQUIP_ASSETS_URI . '/codemirror/addon/hint/show-hint.css', [ 'codemirror' ], null );
	wp_enqueue_script( 'codemirror-addon-css-hint', EQUIP_ASSETS_URI . '/codemirror/addon/hint/css-hint.js', [ 'codemirror' ], null, true );

	wp_enqueue_media();

	wp_enqueue_script( 'equip', EQUIP_ASSETS_URI . '/js/equip.js', [ 'jquery' ], null, true );
	wp_localize_script( 'equip', 'equip', [
		'messages' => [
			'success'       => __( 'Success', 'equip' ),
			'error'         => __( 'Error', 'equip' ),
			'optionsSaved'  => __( 'Options saved', 'equip' ),
			'optionsReset'  => __( 'Options reset', 'equip' ),
			'sectionReset'  => __( 'Section reset', 'equip' ),
			'fail'          => __( 'Hmmm.. Something wrong with server', 'equip' ),
			'youAreAwesome' => __( 'You are awesome!', 'equip' ),
		],
	] );
}

add_action( 'admin_enqueue_scripts', '_equip_admin_enqueue_scripts', 10, 1 );

/**
 * Prints the icons in the footer as JS variable
 *
 * Required for IconField
 *
 * @return bool
 */
function _equip_admin_icons() {
	/** Documented in /equip/app/fields/IconField.php */
	$sources = apply_filters( 'equip/icon/source', [] );
	if ( empty( $sources ) ) {
		return false;
	}

	foreach ( (array) $sources as $source => $name ) {
		$source = esc_attr( $source );
		$icons  = [];

		/**
		 * Returns the previously registered icons
		 *
		 * NOTE: the dynamic part `source` refers to the
		 * key, registered with `equip/icon/source` filter.
		 *
		 * @param array $icons A list of icons
		 */
		$icons = apply_filters( "equip/icons/{$source}", $icons );
		if ( empty( $icons ) ) {
			continue;
		}

		$content = "var equip_icons_{$source} = " . wp_json_encode( $icons ) . ';';

		echo "<script type='text/javascript'>\n";
		echo "/* <![CDATA[ */\n";
		echo "{$content}\n";
		echo "/* ]]> */\n";
		echo "</script>\n";

		unset( $content, $icons );
	}

	return true;
}

add_action( 'admin_print_footer_scripts', '_equip_admin_icons', 20 );

/**
 * Sort current layout scope by priority
 *
 * @param \Equip\Layout\Layout $layout Layout
 *
 * @return \Equip\Layout\Layout
 */
function _equip_sort_layout_elements( $layout ) {
	if ( empty( $layout->elements ) ) {
		return $layout;
	}

	$elements = $layout->elements;
	$sortable = array();
	$sorted   = array();

	/**
	 * @var \Equip\Layout\Layout $element
	 */
	foreach ( $elements as $k => $element ) {
		$priority = (int) $element->get_setting( 'priority', 10 );

		$sortable[ $priority ][] = $element;
	}

	ksort( $sortable );
	reset( $sortable );

	do {
		foreach ( current( $sortable ) as $element ) {
			$sorted[] = $element;
		}
	} while ( next( $sortable ) !== false );

	// set the sorted elements
	$layout->elements = $sorted;
	$layout->set_flag( 'sorted', true );

	return $layout;
}

add_filter( 'equip/engine/layout', '_equip_sort_layout_elements' );

/**
 * Handle the "master" fields
 *
 * What is "master" field?
 *
 * @param \Equip\Layout\Layout $layout Layout
 * @param mixed                $values Values
 *
 * @return \Equip\Layout\Layout
 */
function _equip_handle_master_fields( $layout, $values ) {
	// check the whole layout checked at once, that's why only top-level layouts allowed
	if ( ! $layout->is_root() || true === $layout->get_flag( 'mastered' ) ) {
		return $layout;
	}

	/** @var \Equip\Service\Master $master */
	$master = \Equip\Factory::service( 'master' );
	$layout = $master->handle( $layout, $values );
	$layout->set_flag( 'mastered', true );

	return $layout;
}

add_filter( 'equip/engine/layout', '_equip_handle_master_fields', 10, 2 );

/**
 * Handle the "required" fields
 *
 * This function define which fields will be visible when the page loads
 *
 * @param \Equip\Layout\Layout $layout Layout
 * @param mixed                $values Values
 *
 * @return \Equip\Layout\Layout
 */
function _equip_handle_required_fields( $layout, $values ) {
	// check the whole layout checked at once, that's why only top-level layouts allowed
	if ( ! $layout->is_root() || true === $layout->get_flag( 'required' ) ) {
		return $layout;
	}

	/** @var \Equip\Service\Required $required */
	$required = \Equip\Factory::service( 'required' );
	$layout   = $required->handle( $layout, $values );
	$layout->set_flag( 'required', true );

	return $layout;
}

add_filter( 'equip/engine/layout', '_equip_handle_required_fields', 100, 2 );

/**
 * Open .equip-container
 *
 * Wrap the highest element of the layout, like "meta_box", "page", etc to wrapper.
 * Required for "required" and "master" feature, to make them work proper with similar
 * layouts like menus or meta-boxes.
 *
 * @param \Equip\Layout\Layout $layout Element layout
 * @param string               $slug   Element slug
 */
function _equip_engine_elements_before( $layout, $slug ) {
	// do not add container if current layout is root
	// or if user specify a special flag "container" and set it to false
	if ( ! $layout->is_root()
	     || ( $layout->has_flag( 'container' ) && ! $layout->get_flag( 'container' ) )
	) {
		return;
	}

	$attr = equip_get_attr( array(
		'class'     => 'equip-container',
		'data-slug' => esc_attr( $slug ),
	) );

	echo '<div ', $attr, '>';
}

add_action( 'equip/engine/elements/before', '_equip_engine_elements_before', - 1, 2 );

/**
 * Close .equip-container
 *
 * @param \Equip\Layout\Layout $layout Element layout
 * @param string               $slug   Element slug
 */
function _equip_engine_elements_after( $layout, $slug ) {
	// @see _equip_engine_elements_before()
	if ( ! $layout->is_root()
	     || ( $layout->has_flag( 'container' ) && ! $layout->get_flag( 'container' ) )
	) {
		return;
	}

	echo '</div>';
}

add_action( 'equip/engine/elements/after', '_equip_engine_elements_after', - 1, 2 );

/**
 * Open a service wrapper .equip-field with data-* attributes
 * required for for JS.
 *
 * This wrapper is mandatory. It is not recommended to remove
 * or override this method.
 *
 * @param string                    $slug     Element name
 * @param array                     $settings Field settings
 * @param \Equip\Layout\FieldLayout $layout   Field layout
 */
function _equip_field_before( $slug, $settings, $layout ) {
	$class = [];
	$attr  = [];

	$class[] = 'equip-field';
	if ( true === $layout->get_flag( 'dependent' ) ) {
		$class[] = 'hidden';
	}

	/**
	 * Filter the field service classes
	 *
	 * @param array                     $class    A list of field service classes
	 * @param \Equip\Layout\FieldLayout $layout   Field layout
	 * @param array                     $settings Field settings
	 * @param string                    $slug     Element slug
	 */
	$class = apply_filters_ref_array( 'equip/field/service/class', [ $class, $layout, $settings, $slug ] );

	$attr['class']        = equip_get_class_set( $class );
	$attr['data-element'] = 'field';
	$attr['data-key']     = $settings['key'];
	$attr['data-field']   = $settings['field'];

	if ( ! empty( $settings['required'] ) ) {
		$attr['data-dependent'] = 'true';
		$attr['data-required']  = $settings['required'];
	}

	if ( ! empty( $settings['master'] ) ) {
		$attr['data-slave']  = 'true';
		$attr['data-master'] = $settings['master'];
	}

	/**
	 * Filter the field attributes, attached to a service wrapper.
	 *
	 * @param array                     $attr     Field attributes
	 * @param \Equip\Layout\FieldLayout $layout   Field layout
	 * @param array                     $settings Field settings
	 * @param string                    $slug     Element slug
	 */
	$attr = apply_filters_ref_array( 'equip/field/service/attr', [ $attr, $layout, $settings, $slug ] );

	echo '<div ', equip_get_attr( $attr ), '>';
}

add_action( 'equip/engine/element/field/before', '_equip_field_before', - 1, 3 );

/**
 * Close the .equip-field wrapper
 *
 * @param string                    $slug     Element name
 * @param array                     $settings Field settings
 * @param \Equip\Layout\FieldLayout $layout   Field layout
 */
function _equip_field_after( $slug, $settings, $layout ) {
	echo '</div>';
}

add_action( 'equip/engine/element/field/after', '_equip_field_after', - 1, 3 );
