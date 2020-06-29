<?php
/**
 * The template part for displaying the header "Floating Menu Button Fullscreen"
 *
 * @author 8guild
 */
?>
<header class="<?php startapp_header_class( 'navbar-floating-menu fs-menu' ); ?>">
	<div class="container-fluid">
    <?php
    startapp_the_logo();
    startapp_mobile_logo();
    ?>
		<div class="floating-menu-btn-wrap">
      <div class="inner">
        <div class="floating-menu-btn waves-effect waves-light" data-toggle="fullscreen">
  				<i class="material-icons menu"></i>
  				<i class="material-icons close"></i>
  			</div>
      </div>
		</div>
	</div>
  <div class="fs-menu-wrap">
    <div class="tools">
      <?php startapp_navbar_socials(); ?>
      <div class="text-right">
        <?php
        startapp_header_buttons();
        /**
         * Displays extra elements (tools) in Navbar
         *
         * @see startapp_navbar_toolbar() 100
         */
        do_action( 'startapp_navbar_tools' );
        ?>
      </div>
    </div>
    <?php startapp_fs_menu(); ?>
  </div>
</header>
