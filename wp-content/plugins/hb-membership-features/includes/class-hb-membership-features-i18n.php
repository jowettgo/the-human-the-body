<?php

/**
 * Define the internationalization functionality
 *
 * Loads and defines the internationalization files for this plugin
 * so that it is ready for translation.
 *
 * @link       http://www.iquatic.com
 * @since      1.0.0
 *
 * @package    Hb_Membership_Features
 * @subpackage Hb_Membership_Features/includes
 */

/**
 * Define the internationalization functionality.
 *
 * Loads and defines the internationalization files for this plugin
 * so that it is ready for translation.
 *
 * @since      1.0.0
 * @package    Hb_Membership_Features
 * @subpackage Hb_Membership_Features/includes
 * @author     iQuatic <contact@iquatic.com>
 */
class Hb_Membership_Features_i18n {


	/**
	 * Load the plugin text domain for translation.
	 *
	 * @since    1.0.0
	 */
	public function load_plugin_textdomain() {

		load_plugin_textdomain(
			'hb-membership-features',
			false,
			dirname( dirname( plugin_basename( __FILE__ ) ) ) . '/languages/'
		);

	}



}
