<?php

namespace Equip\Engine;

use Equip\Factory;

/**
 * This engine is responsible to render the top-level "options" layout
 *
 * @author  8guild
 * @package Equip\Engine
 */
class OptionsEngine extends Engine {

	/**
	 * Open the wrapper, start the form with action and nonce, open .equip-page-inner
	 *
	 * NOTE: Hidden field "action" is required for ajax saving
	 *
	 * @param string               $slug
	 * @param \Equip\Layout\Layout $layout
	 */
	public function before_elements( $slug, $layout ) {
		?>
		<div class="wrap">
		<h1 class="hidden"></h1>
		<main class="equip-page" data-element="options">
		<form action="options.php" method="post" class="equip-options-form">
		<input type="hidden" id="equip-action" name="action" value="equip_save_options">
		<input type="hidden" id="equip-slug" name="slug" value="<?php echo esc_attr( $slug ); ?>">
		<?php
		wp_nonce_field( 'equip_save_options', 'equip_save_nonce', false );
		wp_nonce_field( 'equip_reset_options', 'equip_reset_nonce' );
		?>
		<div class="equip-page-inner">
		<?php
	}

	public function before_element( $slug, $settings, $layout ) {
		return;
	}

	public function do_element( $slug, $settings, $values, $layout ) {
		$engine = Factory::engine( $layout );
		$engine->render( $slug, $layout, $values );
	}

	public function after_element( $slug, $settings, $layout ) {
		return;
	}

	/**
	 * Close the .equip-page-inner + footer + close the form and wrapper
	 *
	 * @param string               $slug
	 * @param \Equip\Layout\Layout $layout
	 */
	public function after_elements( $slug, $layout ) {
		?>
		</div>
		<footer class="equip-footer stuck">
			<div class="footer-inner">
				<a href="#" class="equip-logo">
					<img src="<?php echo EQUIP_ASSETS_URI . '/img/equip-logo.svg'; ?>" class="logo-desktop" alt="Equip">
					<img src="<?php echo EQUIP_ASSETS_URI . '/img/equip-logo-mobile.svg'; ?>" class="logo-mobile"
					     alt="Equip">
				</a>
				<div class="equip-btn-group">
					<img src="<?php echo EQUIP_ASSETS_URI . '/img/loader.gif'; ?>" alt="Loading" class="equip-loader"
					     style="display: none;">
					<button type="submit" name="submit" class="equip-btn btn-primary">
						<i class="dashicons dashicons-yes"></i>
						<span><?php esc_html_e( 'Save Changes', 'equip' ); ?></span>
					</button>
					<a href="#" class="equip-btn btn-warning" id="equip-options-reset-section">
						<i class="dashicons dashicons-no-alt"></i>
						<span><?php esc_html_e( 'Reset Section', 'equip' ); ?></span>
					</a>
					<a href="#" class="equip-btn btn-danger" id="equip-options-reset">
						<i class="dashicons dashicons-trash"></i>
						<span><?php esc_html_e( 'Reset All', 'equip' ); ?></span>
					</a>
				</div>
			</div>
		</footer>
		</form>
		</main>
		</div>
		<?php
	}
}
