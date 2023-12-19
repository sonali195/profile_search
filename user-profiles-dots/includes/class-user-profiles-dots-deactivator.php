<?php
/**
 * Fired during plugin deactivation
 *
 * @link       https://#
 * @since      1.0.0
 *
 * @package    Mdot_Profiles
 * @subpackage Mdot_Profiles/includes
 */

/**
 * Fired during plugin deactivation.
 *
 * This class defines all code necessary to run during the plugin's deactivation.
 *
 * @since      1.0.0
 * @package    Mdot_Profiles
 * @subpackage Mdot_Profiles/includes
 * @author     Sonali Prajapati <sonaliprajapati2019@gmail.com>
 */
class User_Profiles_Dots_Deactivator {

	/**
	 * Short Description. (use period)
	 *
	 * Long Description.
	 *
	 * @since    1.0.0
	 */
	public static function deactivate() {
		unregister_post_type( 'user_profiles_dots' );
	}

}
