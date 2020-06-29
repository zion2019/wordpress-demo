<?php
/**
 * The template part for displaying the header "Lateral Navbar"
 *
 * @author 8guild
 */
?>
<header class="<?php startapp_header_class( 'navbar-lateral' ); ?>">
	<?php
	startapp_the_logo();
	startapp_mobile_logo();

	/**
	 * Displays extra elements (tools) in Navbar
	 *
	 * @see startapp_navbar_toolbar() 100
	 */
	do_action( 'startapp_navbar_tools' );

	startapp_vertical_menu();
	startapp_navbar_socials();
	startapp_header_buttons();
	?>
</header>
