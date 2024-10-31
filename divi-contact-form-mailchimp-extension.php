<?php

/**
 * The plugin bootstrap file
 *
 * This file is read by WordPress to generate the plugin information in the plugin
 * admin area. This file also includes all of the dependencies used by the plugin,
 * registers the activation and deactivation functions, and defines a function
 * that starts the plugin.
 *
 * @link              https://www.upwork.com/fl/rayhan1
 * @since             1.0.1
 * @package           Divi_Contact_Form_Mailchimp_Rc_Extension
 *
 * @wordpress-plugin
 * Plugin Name:       ReCorp Divi Mailchimp Extension
 * Plugin URI:        https://myrecorp.com/plugins/divi-contact-form-mailchimp-extension
 * Description:       It will help you to integrate mailchimp functionalities into Divi contact form.
 * Version:           1.0.1
 * Author:            ReCorp
 * Author URI:        https://www.upwork.com/fl/rayhan1
 * License:           GPL-2.0+
 * License URI:       http://www.gnu.org/licenses/gpl-2.0.txt
 * Text Domain:       dcfme
 * Domain Path:       /languages
 */

// If this file is called directly, abort.
if ( ! defined( 'WPINC' ) ) {
	die;
}

/**
 * Currently plugin version.
 * Start at version 1.0.1 and use SemVer - https://semver.org
 * Rename this for your plugin and update it as you release new versions.
 */
define( 'DIVI_CONTACT_FORM_MAILCHIMP_EXTENSION_VERSION', '1.0.1' );

/**
 * The code that runs during plugin activation.
 * This action is documented in includes/class-divi-contact-form-mailchimp-extension-activator.php
 */
function activate_divi_contact_form_mailchimp_rc_extension() {
	require_once plugin_dir_path( __FILE__ ) . 'includes/class-divi-contact-form-mailchimp-extension-activator.php';
	Divi_Contact_Form_Mailchimp_Rc_Extension_Activator::activate();
}

/**
 * The code that runs during plugin deactivation.
 * This action is documented in includes/class-divi-contact-form-mailchimp-extension-deactivator.php
 */
function deactivate_divi_contact_form_mailchimp_rc_extension() {
	require_once plugin_dir_path( __FILE__ ) . 'includes/class-divi-contact-form-mailchimp-extension-deactivator.php';
	Divi_Contact_Form_Mailchimp_Rc_Extension_Deactivator::deactivate();
}

register_activation_hook( __FILE__, 'activate_divi_contact_form_mailchimp_rc_extension' );
register_deactivation_hook( __FILE__, 'deactivate_divi_contact_form_mailchimp_rc_extension' );

/**
 * The core plugin class that is used to define internationalization,
 * admin-specific hooks, and public-facing site hooks.
 */
require plugin_dir_path( __FILE__ ) . 'includes/class-divi-contact-form-mailchimp-extension.php';

/**
 * Begins execution of the plugin.
 *
 * Since everything within the plugin is registered via hooks,
 * then kicking off the plugin from this point in the file does
 * not affect the page life cycle.
 *
 * @since    1.0.0
 */
function run_divi_contact_form_mailchimp_rc_extension() {

	$plugin = new Divi_Contact_Form_Mailchimp_Rc_Extension();
	$plugin->run();

}
run_divi_contact_form_mailchimp_rc_extension();


/*Redirect to plugin's settings page when plugin will active*/
register_activation_hook(__FILE__, 'divi_mailchimp_rc_activation_redirect');
add_action('admin_init', 'divi_mailchimp_rc_redirect_to_settings_page');


function divi_mailchimp_rc_activation_redirect() {
    add_option('divi_contact_form_mailchimp_rc_extension_redirect', true);
}


function divi_mailchimp_rc_redirect_to_settings_page() {
    if (get_option('divi_contact_form_mailchimp_rc_extension_redirect', false)) {
        delete_option('divi_contact_form_mailchimp_rc_extension_redirect');
         exit( wp_redirect("options-general.php?page=mailchimp-for-divi&welcome=true") );
    }
}
/*End plugin redirect*/
