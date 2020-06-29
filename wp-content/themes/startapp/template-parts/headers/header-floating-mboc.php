<?php
/**
 * The template part for displaying the header "Floating Menu Button Off-canvas"
 *
 * @author 8guild
 */
?>
<header class="<?php startapp_header_class( 'navbar-floating-menu' ); ?>">
	<div class="container-fluid">
		<?php
		startapp_the_logo();
		startapp_mobile_logo();
		?>
		<div class="floating-menu-btn-wrap">
			<div class="floating-menu-btn waves-effect waves-light" data-toggle="offcanvas">
				<i class="material-icons menu"></i>
				<i class="material-icons close"></i>
			</div>
		</div>
	</div>
</header>
