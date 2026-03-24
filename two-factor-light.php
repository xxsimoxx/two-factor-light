<?php
/**
 * Plugin Name:       Two Factor Light
 * Description:       Enable Two-Factor Authentication using email and backup verification codes.
 * Requires at least: 6.2
 * Version:           0.0.1
 * Requires PHP:      7.4
 * Requires CP:       2.0
 * Author:            Simone Fioravanti
 * License:           GPL-2.0-or-later
 * License URI:       https://spdx.org/licenses/GPL-2.0-or-later.html
 * Text Domain:       two-factor
 * Network:           True
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly.
}

/**
 * Don't run if original plugin is active.
 */
if ( is_plugin_active( 'two-factor/two-factor.php' ) ) {
	add_action( 
		'after_plugin_row', 
		function ( $plugin_file, $plugin_data ) {
			if ( $plugin_file !== 'x-two-factor/x-two-factor.php' ) {
				return;
			}

			$shadow        = '';
			$notice_msg    = esc_html__( 'xxsimoxx Two Factor is not running because Two Factor is.', 'two-factor' );
			$notice_args   = array(
				'type'               => 'error',
				'additional_classes' => array( 'inline' ),
			);
			?>
			<tr class="plugin-update-tr active">
				<td colspan="10" class="plugin-update colspanchange">
					<?php wp_admin_notice( $notice_msg, $notice_args ); ?>
				</td>
				<script>
					document.querySelector('tr[data-plugin="<?php echo $plugin_file; ?>"').classList.add('update');
				</script>
			</tr>
			<?php
		}, 
		10, 2 );
	return;
}



/**
 * Shortcut constant to the path of this file.
 */
define( 'TWO_FACTOR_DIR', plugin_dir_path( __FILE__ ) );

/**
 * Version of the plugin.
 */
define( 'TWO_FACTOR_VERSION', '0.0.1' );

/**
 * Include the base class here, so that other plugins can also extend it.
 */
require_once TWO_FACTOR_DIR . 'providers/class-two-factor-provider.php';

/**
 * Include the core that handles the common bits.
 */
require_once TWO_FACTOR_DIR . 'class-two-factor-core.php';
Two_Factor_Core::add_hooks();

// Delete our options and user meta during uninstall.
register_uninstall_hook( __FILE__, array( Two_Factor_Core::class, 'uninstall' ) );
