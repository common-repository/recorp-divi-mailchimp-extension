<?php

/**
 * Define the internationalization functionality
 *
 * Loads and defines the internationalization files for this plugin
 * so that it is ready for translation.
 *
 * @link       https://www.upwork.com/fl/rayhan1
 * @since      1.0.0
 *
 * @package    Divi_Contact_Form_Mailchimp_Rc_Extension
 * @subpackage Divi_Contact_Form_Mailchimp_Rc_Extension/includes
 */

/**
 * Define the internationalization functionality.
 *
 * Loads and defines the internationalization files for this plugin
 * so that it is ready for translation.
 *
 * @since      1.0.0
 * @package    Divi_Contact_Form_Mailchimp_Rc_Extension
 * @subpackage Divi_Contact_Form_Mailchimp_Rc_Extension/includes
 * @author     ReCorp <admin@myrecorp.com>
 */
class Divi_Contact_Form_Mailchimp_Rc_Extension_i18n {


	/**
	 * Load the plugin text domain for translation.
	 *
	 * @since    1.0.0
	 */
	public function load_plugin_textdomain() {

		load_plugin_textdomain(
			'dcfme',
			false,
			dirname( dirname( plugin_basename( __FILE__ ) ) ) . '/languages/'
		);

	}



}
