<?php
/**
 * The plugin bootstrap file
 *
 * This file is read by WordPress to generate the plugin information in the plugin
 * admin area. This file also includes all of the dependencies used by the plugin,
 * registers the activation and deactivation functions, and defines a function
 * that starts the plugin.
 *
 * @link              #
 * @since             1.0.0
 * @package           User_Profiles_Dots
 *
 * @wordpress-plugin
 * Plugin Name:       User Profiles Dots
 * Plugin URI:        #
 * Description:       Profile listing search and sorting functionality developed for multidots as a practical
 * Version:           1.0.0
 * Author:            Sonali Prajapati
 * Author URI:        #
 * License:           GPL-2.0+
 * License URI:       http://www.gnu.org/licenses/gpl-2.0.txt
 * Text Domain:       user-profiles-dots
 * Domain Path:       /languages
 */

// If this file is called directly, abort.
if ( ! defined( 'WPINC' ) ) {
	die;
}

/**
 * Currently plugin version.
 * Start at version 1.0.0 and use SemVer - https://semver.org
 * Rename this for your plugin and update it as you release new versions.
 */
define( 'USER_PROFILES_DOTS_VERSION', '1.0.0' );

/**
 * The code that runs during plugin activation.
 * This action is documented in includes/class-user-profiles-dots-activator.php
 */
function activate_User_Profiles_Dots() {
	require_once plugin_dir_path( __FILE__ ) . 'includes/class-user-profiles-dots-activator.php';
	User_Profiles_Dots_Activator::activate();
}

/**
 * This action is documented in includes/class-user-profiles-dots-deactivator.php
 * The code that runs during plugin deactivation.
 */
function deactivate_User_Profiles_Dots() {
	require_once plugin_dir_path( __FILE__ ) . 'includes/class-user-profiles-dots-deactivator.php';
	User_Profiles_Dots_Deactivator::deactivate();
}

register_activation_hook( __FILE__, 'activate_User_Profiles_Dots' );
register_deactivation_hook( __FILE__, 'deactivate_User_Profiles_Dots' );

/**
 * The core plugin class that is used to define internationalization,
 * admin-specific hooks, and public-facing site hooks.
 */
require plugin_dir_path( __FILE__ ) . 'includes/class-user-profiles-dots.php';

/**
 * Begins execution of the plugin.
 *
 * Since everything within the plugin is registered via hooks,
 * then kicking off the plugin from this point in the file does
 * not affect the page life cycle.
 *
 * @since    1.0.0
 */
function run_User_Profiles_Dots() {

	$plugin = new User_Profiles_Dots();
	$plugin->run();

}
run_User_Profiles_Dots();
