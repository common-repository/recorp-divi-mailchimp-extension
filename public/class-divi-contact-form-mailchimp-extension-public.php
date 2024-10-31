<?php

/**
 * The public-facing functionality of the plugin.
 *
 * @link       https://www.upwork.com/fl/rayhan1
 * @since      1.0.0
 *
 * @package    Divi_Contact_Form_Mailchimp_Rc_Extension
 * @subpackage Divi_Contact_Form_Mailchimp_Rc_Extension/public
 */

/**
 * The public-facing functionality of the plugin.
 *
 * Defines the plugin name, version, and two examples hooks for how to
 * enqueue the public-facing stylesheet and JavaScript.
 *
 * @package    Divi_Contact_Form_Mailchimp_Rc_Extension
 * @subpackage Divi_Contact_Form_Mailchimp_Rc_Extension/public
 * @author     ReCorp <admin@myrecorp.com>
 */
class Divi_Contact_Form_Mailchimp_Rc_Extension_Public {

	/**
	 * The ID of this plugin.
	 *
	 * @since    1.0.0
	 * @access   private
	 * @var      string    $plugin_name    The ID of this plugin.
	 */
	private $plugin_name;

	/**
	 * The version of this plugin.
	 *
	 * @since    1.0.0
	 * @access   private
	 * @var      string    $version    The current version of this plugin.
	 */
	private $version;

	/**
	 * Initialize the class and set its properties.
	 *
	 * @since    1.0.0
	 * @param      string    $plugin_name       The name of the plugin.
	 * @param      string    $version    The version of this plugin.
	 */
	public function __construct( $plugin_name, $version ) {

		$this->plugin_name = $plugin_name;
		$this->version = $version;

		
		//add_action('wp_head', array($this, 'agree_dcfme_user_permission'));

	}

	/**
	 * Register the stylesheets for the public-facing side of the site.
	 *
	 * @since    1.0.0
	 */
	public function enqueue_styles() {

		/**
		 * This function is provided for demonstration purposes only.
		 *
		 * An instance of this class should be passed to the run() function
		 * defined in Divi_Contact_Form_Mailchimp_Rc_Extension_Loader as all of the hooks are defined
		 * in that particular class.
		 *
		 * The Divi_Contact_Form_Mailchimp_Rc_Extension_Loader will then create the relationship
		 * between the defined hooks and the functions defined in this
		 * class.
		 */

		wp_enqueue_style( $this->plugin_name, plugin_dir_url( __FILE__ ) . 'css/divi-contact-form-mailchimp-extension-public.css', array(), $this->version, 'all' );

	}

	/**
	 * Register the JavaScript for the public-facing side of the site.
	 *
	 * @since    1.0.0
	 */
	public function enqueue_scripts() {

		/**
		 * This function is provided for demonstration purposes only.
		 *
		 * An instance of this class should be passed to the run() function
		 * defined in Divi_Contact_Form_Mailchimp_Rc_Extension_Loader as all of the hooks are defined
		 * in that particular class.
		 *
		 * The Divi_Contact_Form_Mailchimp_Rc_Extension_Loader will then create the relationship
		 * between the defined hooks and the functions defined in this
		 * class.
		 */
		global $post;
		wp_enqueue_script( $this->plugin_name, plugin_dir_url( __FILE__ ) . 'js/divi-contact-form-mailchimp-extension-public.js', array( 'jquery' ), $this->version, false );
			wp_add_inline_script( $this->plugin_name, '
				/* <!\[CDATA\[ */
					var dcfme = {"theme_url":"'.  get_stylesheet_directory_uri() .'",
						"members_url":"'.  get_home_url() . '/members' .'",
						"home_url":"'.  get_home_url() .'",
						"ajax_url":"'. get_admin_url() . 'admin-ajax.php' . '",
						"nonce": "'.wp_create_nonce( "recorp_nonce" ).'",
						"post_id": '.$post->ID.'
						};
				/* \]\]> */
			');

			wp_add_inline_script($this->plugin_name, $this->agree_dcfme_user_permission(), array('jquery'), '');


	}

		
	public function get_recorp_mc_by_id($mc_id=0, $need="", $list_id = ""){
		$datas = get_option('recorp_divi_mailchimp');

		if (!empty($datas)) {

			$datas = stripslashes($datas);
			$datas = json_decode($datas);

			if (isset($datas->form_data)) {
					
				foreach ($datas->form_data as $key => $data) {

					$post_id = $data->post_id;
					$mc_lists = $data->list_id;
					$status = $data->form_status;


					$p = get_post($post_id);
					$h = get_post($mc_id);

					if ($mc_id == $post_id) {
						if ($need == 'lists') {

							$is = "";
							foreach ($mc_lists as $key => $value2) {
								if ( $value2 == $list_id) {
									$is .= "true";

									break;
								}
							}

							return $is;
							
						}
						if ($need == 'status') {
							return $status;
						}
					}


					elseif ($p->post_type == "divi_overlay" && strpos($h->post_content, 'overlay_unique_id_'.$post_id) ) {
						if ($need == 'lists') {

							$is = "";
							foreach ($mc_lists as $key => $value2) {
								if ( $value2 == $list_id) {
									$is .= "true";

									break;
								}
							}

							return $is;
							
						}
						if ($need == 'status') {
							return $status;
						}
					}

				}

				if (!empty($datas->settings->user_permission)) {
					$user_permission = $datas->settings->user_permission;
					if ($need == 'user_permission') {
						return $user_permission;
					}
				}
			}

		}
	}


	public function agree_dcfme_user_permission(){
		global $post;

		if( $this->get_recorp_mc_by_id('', 'user_permission') == "enable" && $this->get_recorp_mc_by_id($post->ID, 'status') == "enable" ){

			ob_start();
		?>
				jQuery(document).ready(function(){
					var x = 0;
				 	jQuery('.et_pb_contact_form').each(function(){
						jQuery(this).find('.et_contact_bottom_container').before('<p class="et_pb_contact_field"><span class="subscribe_to_mailing_list et_pb_contact_field_checkbox"><input id="mailing_list_'+x+'" type="checkbox" name="add_to_mailing_list"><label for="mailing_list_'+x+'"><i></i> <?php echo get_option('dcfme_subscribe_text') !== "" ? get_option('dcfme_subscribe_text') : 'Subscribe to our mailing list.'; ?>
							</label></span></p>');

						x++;
					}); 

				});

			<?php if (function_exists('my_edit_divi_overlay_columns')): ?>
				
					(function ($) {
						'use strict';

						$(document).ready(function(){
						  	$('.overlay.overlay-hugeinc').each(function(){
						  		var overlay_ = $(this).attr('id');
						  		var overlay_id = overlay_.split('-')[1];

						  		$(this).find('form').append('<input type="hidden" name="divi_overlay_key" value="'+overlay_id+'">')
						  	});
						});
						
					

					})(jQuery);
			<?php endif ?>

		<?php
		}
		else 
		{
		?>
				jQuery(document).ready(function(){
					var x = 0;
				 	jQuery('.et_pb_contact_form').each(function(){
						jQuery(this).append('<p class="et_pb_contact_field"><span class="subscribe_to_mailing_list et_pb_contact_field_checkbox" style="display: none;"><input id="mailing_list_'+x+'" class="input" type="checkbox" name="add_to_mailing_list" checked><label for="mailing_list_'+x+'"><?php echo get_option('dcfme_subscribe_text', 'Subscribe to our mailing list.'); ?>
							</label></span></p>');

						x++;
					}); 
				});
		<?php
		}

		return ob_get_clean();
	}
	

}
