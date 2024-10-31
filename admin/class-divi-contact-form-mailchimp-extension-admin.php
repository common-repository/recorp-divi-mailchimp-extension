<?php

/**
 * The admin-specific functionality of the plugin.
 *
 * @link       https://www.upwork.com/fl/rayhan1
 * @since      1.0.0
 *
 * @package    Divi_Contact_Form_Mailchimp_Rc_Extension
 * @subpackage Divi_Contact_Form_Mailchimp_Rc_Extension/admin
 */

/**
 * The admin-specific functionality of the plugin.
 *
 * Defines the plugin name, version, and two examples hooks for how to
 * enqueue the admin-specific stylesheet and JavaScript.
 *
 * @package    Divi_Contact_Form_Mailchimp_Rc_Extension
 * @subpackage Divi_Contact_Form_Mailchimp_Rc_Extension/admin
 * @author     ReCorp <admin@myrecorp.com>
 */
class Divi_Contact_Form_Mailchimp_Rc_Extension_Admin {

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
	 * @param      string    $plugin_name       The name of this plugin.
	 * @param      string    $version    The version of this plugin.
	 */
	public function __construct( $plugin_name, $version ) {

		$this->plugin_name = $plugin_name;
		$this->version = $version;

	add_action('admin_menu', array($this, 'divi_contact_form_mailchimp') );

	add_action('wp_ajax_divi_contact_for_mailchimp_rc_save_data', array( $this, 'divi_contact_for_mailchimp_rc_save_data'));
	add_action('wp_ajax_nopriv_divi_contact_for_mailchimp_rc_save_data', array( $this, 'divi_contact_for_mailchimp_rc_save_data'));

	add_action('wp_ajax_save_dcfme_mailchimp_rc_api', array( $this, 'save_dcfme_mailchimp_rc_api') );
	add_action('wp_ajax_nopriv_save_dcfme_mailchimp_rc_api', array( $this, 'save_dcfme_mailchimp_rc_api') );

	add_action('wp_ajax_dcfme_refresh_mailchimp_rc_lists', array( $this, 'dcfme_refresh_mailchimp_rc_lists'));
	add_action('wp_ajax_nopriv_dcfme_refresh_mailchimp_rc_lists', array( $this, 'dcfme_refresh_mailchimp_rc_lists'));

	add_action('wp_ajax_get_dcfme_mailchimp_rc_list_merge_tags', array( $this, 'get_dcfme_mailchimp_rc_list_merge_tags'));
	add_action('wp_ajax_nopriv_get_dcfme_mailchimp_rc_list_merge_tags', array( $this, 'get_dcfme_mailchimp_rc_list_merge_tags'));		

	/*Send member subscribe request to the API*/
	add_filter("et_contact_page_email_to", array( $this, "filter_dcfme_email_to_use_mailchimp_rc_api") );

	}

	/**
	 * Register the stylesheets for the admin area.
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

		$current_url = "https://".$_SERVER['HTTP_HOST'].$_SERVER['REQUEST_URI'];

	if(strpos($current_url, 'mailchimp-for-divi') !== false){
		wp_enqueue_style( $this->plugin_name, plugin_dir_url( __FILE__ ) . 'css/divi-contact-form-mailchimp-extension-admin.css', array(), $this->version, 'all' );

		wp_enqueue_style( 'divi_mail_chimp_bootstrap_min', plugin_dir_url( __FILE__ ) . 'css/bootstrap.min.css', array(), $this->version, 'all' );

		wp_enqueue_style( 'dcfme_multi_select', plugin_dir_url( __FILE__ ) . 'css/multi-select.css', array(), $this->version, 'all' );
	}

}

	/**
	 * Register the JavaScript for the admin area.
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
	$current_url = "https://".$_SERVER['HTTP_HOST'].$_SERVER['REQUEST_URI'];

		if(strpos($current_url, 'mailchimp-for-divi') !== false){
			wp_enqueue_script( $this->plugin_name, plugin_dir_url( __FILE__ ) . 'js/divi-contact-form-mailchimp-extension-admin.js', array( 'jquery', 'bootstrap-notify', 'velocity-ui-min'), $this->version, false );

			wp_enqueue_script( 'bootstrap-notify', plugin_dir_url( __FILE__ ) . 'js/bootstrap-notify.js', array( 'jquery', 'bootstrap-min' ), $this->version, false );

			wp_enqueue_script( 'bootstrap-min', plugin_dir_url( __FILE__ ) . 'js/bootstrap.min.js', array( 'jquery' ), $this->version, false );

			wp_enqueue_script( 'velocity-min', plugin_dir_url( __FILE__ ) . 'js/velocity.min.js', array( 'jquery' ), $this->version, false );

			wp_enqueue_script( 'velocity-ui-min', plugin_dir_url( __FILE__ ) . 'js/velocity-ui.min.js', array( 'jquery', 'velocity-min' ), $this->version, false );

			wp_enqueue_script( 'multi-select-dcfme', plugin_dir_url( __FILE__ ) . 'js/multi-select.js', array( 'bootstrap-min' ), $this->version, false );

			wp_add_inline_script($this->plugin_name, '
				/* <!\[CDATA\[ */
					var dcfme = {"theme_url":"'.  get_stylesheet_directory_uri() .'",
						"members_url":"'.  get_home_url() . '/members' .'",
						"home_url":"'.  get_home_url() .'",
						"ajax_url":"'. get_admin_url() . 'admin-ajax.php' . '",
						"nonce": "'.wp_create_nonce( "rc-nonce" ).'",
						};
				/* \]\]> */
			');

			wp_enqueue_script( 'freemius-checkout', 'https://checkout.freemius.com/checkout.min.js', array('jquery'), '', true );

			wp_add_inline_script('freemius-checkout', "
				var handler = FS.Checkout.configure({
			        plugin_id:  '6067',
			        plan_id:    '9948',
			        public_key: 'pk_4d0a67ca29c5132d8047ebea037bb',
			        image:      'https://your-plugin-site.com/logo-100x100.png'
			    });
		    
		    jQuery('#purchase').on('click', function (e) {
		    	console.log('test');
		        handler.open({
		            name     : 'Mailchimp for Divi Contact Form',
		            // You can consume the response for after purchase logic.
		            purchaseCompleted  : function (response) {
		                // The logic here will be executed immediately after the purchase confirmation.                                // alert(response.user.email);
		            },
		            success  : function (response) {
		                // The logic here will be executed after the customer closes the checkout, after a successful purchase.                                // alert(response.user.email);
		            }
		        });
		        e.preventDefault();

		        })
			");

		}
	}


	public function divi_contact_form_mailchimp(){

		add_options_page(
			'Mailchimp for Divi',
			__('Mailchimp for Divi', 'different-menus'),
			'manage_options',
			'mailchimp-for-divi',
			array(
				$this,
				'load_admin_dependencies'
			)
		);
	}

	public function load_admin_dependencies(){
		require_once plugin_dir_path( dirname( __FILE__ ) ) . 'admin/partials/divi-contact-form-mailchimp-extension-admin-display.php';

	}
	public function get_all_post_types(){
		
		$need = array();
		foreach (get_post_types() as $key => $value) {
		    if ($value !== 'attachment'&&$value !== 'revision'&&$value !== 'nav_menu_item'&&$value !== 'oembed_cache'&&$value !== 'user_request') {
		        $need[] = $value;
		    }
		    
		}

		return $need;
	}


	public function divi_contact_for_mailchimp_rc_save_data(){
		$data = isset($_POST['mc_table']) ? sanitize_text_field($_POST['mc_table']) : "";
		$subscribe_text = isset($_POST['subscribe_text']) ? sanitize_title($_POST['subscribe_text']) : "";

		$nonce = isset($_POST['rc_nonce']) ? sanitize_key($_POST['rc_nonce']) : "";

		if(!empty($nonce)){
			if(!wp_verify_nonce( $nonce, "rc-nonce" )){
				echo json_encode(array('success' => false, 'status' => 'nonce_verify_error', 'response' => ''));

				die();
			}
		}

		update_option('dcfme_subscribe_text', $subscribe_text);
		

		if (!empty($data)) {
			update_option('recorp_divi_mailchimp', $data);
		}

		echo json_encode(array('success' => true, 'status' => 'success', 'response' => ''));
	
		die();
	}


    function save_dcfme_mailchimp_rc_api(){

    	$nonce = isset($_POST['rc_nonce']) ? sanitize_key($_POST['rc_nonce']) : "";

		if(!empty($nonce)){
			if(!wp_verify_nonce( $nonce, "rc-nonce" )){
				echo json_encode(array('success' => false, 'status' => 'nonce_verify_error', 'response' => ''));

				die();
			}
		}

	$api_key = isset($_POST['key']) ? sanitize_title($_POST['key']) : "";

	// Query String Perameters are here
	// for more reference please vizit http://developer.mailchimp.com/documentation/mailchimp/reference/lists/
	$data = array(
	    'fields' => 'lists', // total_items, _links

	);

	$dc = substr($api_key,strpos($api_key,'-')+1);
	$url = 'https://' . $dc . '.api.mailchimp.com/3.0/lists/';

	$response = $this->connect_mailchimp_rc_account($url, 'GET', $api_key, $data);

	
	if (wp_remote_retrieve_response_code($response) == 200) {
		$result = json_decode(wp_remote_retrieve_body($response));
	} else {
		$result = array();
	}
	

	if( isset($result->lists) && !empty($result->lists) ) {

		$api = array(
			'api_key' => $api_key,
			'status' => 'valid'
		);

		update_option('dcfme_mailchimp_rc_api', $api);

		$lists = array();
	    foreach( $result->lists as $list ){

	        $list2 = array();
	        $list2['id'] = $list->id;
	        $list2['name'] = $list->name;
	        $list2['member_count'] = $list->stats->member_count;

	        $lists[] = $list2;

	    }
	 
	 update_option('dcfme_mailchimp_rc_lists', $lists);

	 echo json_encode( array('success' => true, 'status' => 'connected') );
	 die();

	} elseif ( is_int( $result->status ) ) { // full error glossary is here http://developer.mailchimp.com/documentation/mailchimp/guides/error-glossary/
	    echo json_encode( array('success' => false, 'status' => 'invalid', 'response' => $result->status) );
	}

	echo json_encode( array('success' => false, 'status' => 'invalid', 'response' => $result->status) );


	die();

}


private function connect_mailchimp_rc_account( $url, $request_type, $api_key, $data = array() ) {
   
	 $headers = array(
        'Content-Type' => 'application/json',
        'Authorization' => 'Basic '.base64_encode( 'user:'. $api_key )
    );

    $args = array(
	    'body'        => $data,
	    'timeout'     => '100',
	    'redirection' => '5',
	    'httpversion' => '1.0',
	    'headers'     => $headers,
	    'cookies'     => array(),
	    'method'	  => $request_type
	);

	$response = wp_remote_post( $url, $args );

	return $response;
}

public function dcfme_refresh_mailchimp_rc_lists(){

	$nonce = isset($_POST['rc_nonce']) ? sanitize_key($_POST['rc_nonce']) : "";

	if(!empty($nonce)){
		if(!wp_verify_nonce( $nonce, "rc-nonce" )){
			echo json_encode(array('success' => false, 'status' => 'nonce_verify_error', 'response' => ''));

			die();
		}
	}

	$mailchimp = get_option('dcfme_mailchimp_rc_api');

	if ($mailchimp['status'] == 'valid') {
		
		$api_key = $mailchimp['api_key'];

		$data = array(
	    	'fields' => 'lists', // total_items, _links
		);

		$dc = substr($api_key,strpos($api_key,'-')+1);
		$url = 'https://' . $dc . '.api.mailchimp.com/3.0/lists/';

		$response = $this->connect_mailchimp_rc_account($url, 'GET', $api_key, $data);

		if (wp_remote_retrieve_response_code($response) == 200) {
			
			$result = json_decode(wp_remote_retrieve_body($response));

			if( isset($result->lists) && !empty($result->lists) ) {

				$api = array(
					'api_key' => $api_key,
					'status' => 'valid'
				);

				update_option('dcfme_mailchimp_rc_api', $api);

				$lists = array();
			    foreach( $result->lists as $list ){

			        $list2 = array();
			        $list2['id'] = $list->id;
			        $list2['name'] = $list->name;
			        $list2['member_count'] = $list->stats->member_count;

			        $lists[] = $list2;

			    }
			 
			 	update_option('dcfme_mailchimp_rc_lists', $lists);

			}
		}

		echo json_encode(array('success' => true, 'status' => 'success', 'response' => ''));
		die();
	}
	echo json_encode(array('success' => false, 'status' => 'error', 'response' => ''));
	die();

}

public function get_dcfme_mailchimp_rc_list_merge_tags(){
	$list_id = isset($_POST['list_id']) ? sanitize_key($_POST['list_id']) : "";

	$nonce = isset($_POST['rc_nonce']) ? sanitize_key($_POST['rc_nonce']) : "";

	if(!empty($nonce)){
		if(!wp_verify_nonce( $nonce, "rc-nonce" )){
			echo json_encode(array('success' => false, 'status' => 'nonce_verify_error', 'response' => ''));

			die();
		}
	}

	$mailchimp = get_option('dcfme_mailchimp_rc_api');

	if ($mailchimp['status'] == 'valid') {
		
		$api_key = $mailchimp['api_key'];
	 
		// Query String Perameters are here
		// for more reference please vizit http://developer.mailchimp.com/documentation/mailchimp/reference/lists/
		$data = array(
		    'fields' => 'merge_fields', // total_items, _links

		);
		 
		$url = 'https://' . substr($api_key,strpos($api_key,'-')+1) . '.api.mailchimp.com/3.0/lists/'.$list_id.'/merge-fields';

		$response = $this->connect_mailchimp_rc_account( $url, 'GET', $api_key, $data);

		if (wp_remote_retrieve_response_code($response) == 200) {
			$merge_fields = json_decode( wp_remote_retrieve_body($response) );
		} else {
			$merge_fields = array();
		}
		

		$merged = '<h3 style="font-size: 1.3em;font-weight: bold;margin: 1em 0;">Merge fields</h3>
		<table class="widefat striped">
		    <thead>
		        <tr>
		            <th>Name</th>
		            <th>Tag</th>
		            <th>Type</th>
		        </tr>
		    </thead>
		    <tbody>';

	if(isset($merge_fields->merge_fields) && !empty($merge_fields->merge_fields)){

		foreach ($merge_fields->merge_fields as $key => $merge_field) {
		    $name = $merge_field->name;
		    $tag = $merge_field->tag;
		    $type = $merge_field->type;

		    $required = ($type == "email") ? '<span class="red">*</span>' : "";
		    $bday_pattern = ($type == "birthday") ? "({$merge_field->options->date_format})" : "";

		    $merged .= "<tr>
		                    <td>{$name} {$required}</td>
		                    <td><code>{$tag}</code></td>
		                    <td>{$type} {$bday_pattern} </td>
		                </tr>";
		}
	}


		$merged .= "
		            </tbody>
		        </table>";

		echo json_encode(array('success' => true, 'status' => 'success', 'response' => $merged));
	} else {
		echo json_encode(array('success' => false, 'status' => 'error', 'response' => ''));
	}

		die();
}

public function save_dcfme_mailchimp_rc_cookie(){

	ob_start();

$cookie_name = "dcfmec";
	$cookie_value = "yy";
	//setcookie($cookie_name, $cookie_value, time() + (86400 * 30), "/");


ob_end_flush();
	//echo $_COOKIE[$cookie_name];
	

	die();
}

	private function insert_email_to_mailchimp(){

		if( ( $this->get_user_permission_status() == "enable" && isset($_POST['add_to_mailing_list'])) || $this->get_user_permission_status() == "disable" ){

			$email = "";
			$first_name = "";
			$last_name = "";
			$address = "";
			$bday = "";
			$phone = "";

			if ($this->get_field_data('et_pb_contact_email') !== false) {
				$email = $this->get_field_data('et_pb_contact_email');
			}


			if ($this->get_field_data('et_pb_contact_name') !== false) {
				$first_name = $this->get_field_data('et_pb_contact_name');
			}elseif ($this->get_field_data('et_pb_contact_first_name') !== false) {
				$first_name = $this->get_field_data('et_pb_contact_first_name');
			}elseif ($this->get_field_data('et_pb_contact_fname') !== false) {
				$first_name = $this->get_field_data('et_pb_contact_fname');
			}

			if ($this->get_field_data('et_pb_contact_last_name') !== false) {
				$last_name = $this->get_field_data('et_pb_contact_last_name');
			}elseif ($this->get_field_data('et_pb_contact_fname') !== false) {
				$last_name = $this->get_field_data('et_pb_contact_lname');
			}


			if ($this->get_field_data('et_pb_contact_address') !== false) {
				$address = $this->get_field_data('et_pb_contact_address');
			}

			if ($this->get_field_data('et_pb_contact_birth_day') !== false) {
				$bday = $this->get_field_data('et_pb_contact_birth_day');
			}

			if ($this->get_field_data('et_pb_contact_phone') !== false) {
				$phone = $this->get_field_data('et_pb_contact_phone');
			}


			$merge_fields = array();
			if (isset($_POST)&&!empty($_POST)) {
			    foreach ($_POST as $key => $value) {
			        //$merged = (!empty($this->get_string_between($key, 'et_pb_contact_', '_0'))) ? $this->get_string_between($key, 'et_pb_contact_', '_0') : $this->get_string_between($key, 'et_pb_contact_', '_1');

			        $last_number = explode('_', $key);
			        $last_number = '_'. end($last_number);
			        $merged = $this->get_string_between($key, 'et_pb_contact_', $last_number);

			        $merged = strtoupper($merged);

			        if (!empty($merged) && $merged !== "MESSAGE" &&  $merged !== "CAPTCHA" &&  $merged !== "EMAIL_FIELDS" && $merged !== "EMAIL_HIDDEN_FIELDS"  && $merged !== "EMAIL" ) {
			            $merge_fields[$merged] = sanitize_text_field($value);
			        }
			    }
			}

			if (empty($first_name)) {
				$first_name = "Name not found";
			}

			$merge_fields['FNAME'] = $first_name;

			if (!empty($last_name)) {
				$merge_fields['LNAME'] = $last_name;
			}

			$status = $this->get_user_double_optin_status() == 'enable' ? 'pending' : 'subscribed';
			$data = array(
				'email_address' => $email,
				'status' => $status,
				'merge_fields' => $merge_fields
			);
			

			$mailchimp = get_option('dcfme_mailchimp_rc_api');

			if ( isset($mailchimp['status']) && $mailchimp['status'] == 'valid') {

				$_wp_http_referer = isset($_POST['_wp_http_referer']) ? sanitize_text_field($_POST['_wp_http_referer']) : "";
				$post_id = url_to_postid($_wp_http_referer);


				if (isset($_POST['divi_overlay_key'])) {
					$lists = $this->get_dcfme_mailchimp_rc_list_by_post_id(sanitize_key($_POST['divi_overlay_key']));
				}
				elseif ($post_id == 0) {
					$frontPageId = get_option( 'page_on_front' );
					$PostPageId = get_option( 'page_for_posts' );

					$front_page_list = $this->get_dcfme_mailchimp_rc_list_by_post_id($frontPageId);
					$post_page_list = $this->get_dcfme_mailchimp_rc_list_by_post_id($PostPageId);


					if ( !empty($front_page_list) ) {
						$lists = $front_page_list;
					} elseif ( !empty($post_page_list) ) {
						$lists = $post_page_list;
					}
				} else {
					$lists = $this->get_dcfme_mailchimp_rc_list_by_post_id($post_id);
				}

				

				if ( !empty($_wp_http_referer) && !empty($lists)) {


					$api_key = $mailchimp['api_key'];
				
					$url = 'https://' . substr($api_key,strpos($api_key,'-')+1) . '.api.mailchimp.com/3.0/lists/'.$lists.'/members/';

					$this->connect_mailchimp_rc_account( $url, 'POST', $api_key, json_encode($data) );


				}
				
			}

		}
	}

	private function get_field_data($nfield=""){
		if (!isset($_POST) && empty($_POST)) {
			return false;
		}

		foreach ($_POST as $key => $field) {
			if (strpos( sanitize_key($key), $nfield) !== false) {
				return sanitize_text_field($field);
				break;
			}
		}

		return false;
	}

	public function filter_dcfme_email_to_use_mailchimp_rc_api($email){
		$this->insert_email_to_mailchimp();
		return $email;
	}

	private function get_dcfme_mailchimp_rc_list_by_post_id($post_id){
	    $datas = get_option('recorp_divi_mailchimp');

	    if (!empty($datas)) {
	    	$datas = stripslashes($datas);
			$datas = json_decode($datas);

			if (!empty($datas->form_data)) {
				foreach ( $datas->form_data as $key => $value) {
			        if ($value->post_id == $post_id) {
			            return $value->list_id;
			            break;
			        }
			    }
			}
		    
		}

	}

	private function get_string_between($string, $start, $end){
	    $string = ' ' . $string;
	    $ini = strpos($string, $start);
	    if ($ini == 0) return '';
	    $ini += strlen($start);
	    $len = strpos($string, $end, $ini) - $ini;
	    return substr($string, $ini, $len);
	}

	public function get_user_permission_status(){
		$datas = get_option('recorp_divi_mailchimp');

		if (!empty($datas)) {

			$datas = stripslashes($datas);
			$datas = json_decode($datas);

			return $datas->settings->user_permission;
			
		}
	}

	public function get_user_double_optin_status(){
		$datas = get_option('recorp_divi_mailchimp');

		if (!empty($datas)) {

			$datas = stripslashes($datas);
			$datas = json_decode($datas);

            if (isset($datas->settings->user_double_optin)) {
                return $datas->settings->user_double_optin;
            }

		}
	}

	public function get_mailchimp_list_id(){
		$datas = get_option('recorp_divi_mailchimp');

		if (!empty($datas)) {

			$datas = stripslashes($datas);
			$datas = json_decode($datas);

            if (isset($datas->settings->mailing_list)) {
                return $datas->settings->mailing_list;
            }

		}
	}
}


function get_dcfme_recorp_mc_by_id($mc_id=0, $need="", $list_id = ""){
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

				if (!empty($data->settings->user_permission)) {
					$user_permission = $data->settings->user_permission;
					if ($need == 'user_permission') {
						return $user_permission;
					}
				}

			}
		}

	}
}