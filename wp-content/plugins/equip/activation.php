<?php
/**
 * Equip activation checks
 *
 * @author  8guild
 * @package Equip
 */

/**
 * Class Equip_Activation
 */
class Equip_Activation {

	public function __construct() {
		add_action( 'admin_init', array( $this, 'check_version' ) );

		// Don't run anything else in the plugin, if we're on an incompatible WordPress version
		if ( ! self::compatible_version() ) {
			return;
		}
	}

	// The primary sanity check, automatically disable the plugin on activation if it doesn't
	// meet minimum requirements.
	static function activation_check() {
		if ( ! self::compatible_version() ) {
			deactivate_plugins( EQUIP_PLUGIN_BASENAME );
			wp_die( __( 'Equip requires PHP 5.4 or higher!', 'equip' ) );
		}
	}

	// The backup sanity check, in case the plugin is activated in a weird way,
	// or the versions change after activation.
	function check_version() {
		if ( ! self::compatible_version() ) {
			if ( is_plugin_active( EQUIP_PLUGIN_BASENAME ) ) {
				deactivate_plugins( EQUIP_PLUGIN_BASENAME );
				add_action( 'admin_notices', array( $this, 'disabled_notice' ) );
				if ( isset( $_GET['activate'] ) ) {
					unset( $_GET['activate'] );
				}
			}
		}
	}

	function disabled_notice() {
		?>
		<div class="notice notice-error is-dismissible">
			<p><?php
				echo wp_kses( __( '<strong>Equip</strong> requires <strong>PHP 5.4</strong> or higher. Please contact your hosting provider and ask them to increase the PHP version.', 'equip' ), array(
					'strong' => true,
				) );
				?></p>
		</div>
		<?php
	}

	static function compatible_version() {
		if ( version_compare( PHP_VERSION, '5.4', '<' ) ) {
			return false;
		}

		// Add sanity checks for other version requirements here

		return true;
	}
}

new Equip_Activation();

register_activation_hook( __FILE__, array( 'Equip_Activation', 'activation_check' ) );