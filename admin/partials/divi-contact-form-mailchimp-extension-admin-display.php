<?php

/**
 * Provide a admin area view for the plugin
 *
 * This file is used to markup the admin-facing aspects of the plugin.
 *
 * @link       https://www.upwork.com/fl/rayhan1
 * @since      1.0.0
 *
 * @package    Divi_Contact_Form_Mailchimp_Rc_Extension
 * @subpackage Divi_Contact_Form_Mailchimp_Rc_Extension/admin/partials
 */
?>

<!-- This file should primarily consist of HTML with a little bit of PHP. -->

<h3 style="padding-top: 10px "><?php _e('Divi Contact Form Mailchimp Extension', 'dcfme'); ?> <span class="version">v<?php echo DIVI_CONTACT_FORM_MAILCHIMP_EXTENSION_VERSION; ?></span>

    <button type="button" class="btn btn-danger btn-sm upgrade_button" style="position: relative;bottom: 4px;"><?php _e('Upgrade', 'dcfme'); ?></button>

	<a class="float-right pr-2" href="https://myrecorp.com/documentation/divi-contact-form-mailchimp-extension" style="position: relative;bottom: 4px;"><button type="button" class="btn btn-primary btn-sm ml-3"><?php _e('Documentation', 'dcfme'); ?></button></a>

</h3>



<div class="container mailchimp-tabs">
  <ul class="nav nav-tabs" role="tablist">
    <li class="nav-item" role="presentation">
      <button class="nav-link active" id="mailchimp-tab" data-bs-toggle="tab" data-bs-target="#mailchimp" type="button" role="tab" area-selected="true"><?php _e('Mailchimp', 'dcfme'); ?></a>
    </li>
    <li class="nav-item" role="presentation">
      <button class="nav-link" id="forms-tab" data-bs-toggle="tab" data-bs-target="#forms" type="button" role="tab" area-selected="false"><?php _e('Forms', 'dcfme'); ?></button>
    </li>


    <li class="nav-item" role="presentation">
      <button class="nav-link licensing" id="licensing-tab" data-bs-toggle="tab" data-bs-target="#licensing" type="button" role="tab" area-selected="false" style="color: red;"><?php _e('Upgrade', 'dcfme'); ?></a>
    </li>

  </ul>

  <!-- Tab panes -->
  <div class="tab-content">
    <div id="mailchimp" class="container tab-pane active"><br>
      <form action="" method="post">
			<table class="form-table">

				<tbody>
					<tr valign="top">
						<th scope="row">
							<?php _e('Status', 'dcfme'); ?>						
						</th>
						<td>

						<?php
							$api = get_option('dcfme_mailchimp_rc_api');

							if ($api['status'] == 'valid') {
								$api_status = 'CONNECTED';
								$class = 'active';
							} else {
								$api_status = 'NOT CONNECTED';
								$class = '';
							}
						 ?>									
							<span class="status <?php echo $class; ?>"><?php echo $api_status; ?></span>
						</td>
					</tr>


				<tr valign="top">
					<th scope="row"><label for="mailchimp_rc_api_key">API Key</label></th>
					<td>

						<?php

							if ($api['status'] == 'valid') {
								$api_key = $api['api_key'];

								$sub = substr( $api_key, 0, 18);

								$api_key = str_replace( $sub, "******************", $api_key);
							} else {
								$api_key = "";
							}

						 ?>
						<input type="text" class="widefat" placeholder="Your Mailchimp API key" id="mailchimp_rc_api_key" name="mc4wp[api_key]" value="<?php echo $api_key; ?>">
						<p class="invalid" style="color: red; display: none;"><?php _e('Invalid Api Key', 'dcfme'); ?></p>
						<p class="help">
							<?php _e('The API key for connecting with your Mailchimp account.', 'dcfme'); ?>		<a target="_blank" href="https://admin.mailchimp.com/account/api"><?php _e('Get your API key here.', 'dcfme'); ?></a>
						</p>

					</td>
				</tr>

			</tbody>
		</table>

	<div class="submit"><input type="submit" name="submit" id="submit" class="button button-primary" value="Save Changes"> &nbsp; <div class="loadersmall hidden"></div></div>
</form>
<?php
$api = get_option('dcfme_mailchimp_rc_api');
$lists = get_option('dcfme_mailchimp_rc_lists');

if ($api['status'] == 'valid'): ?>
	<hr>
<h3><?php _e('Your Mailchimp Account', 'dcfme'); ?></h3>
<p><?php _e('The table below shows your Mailchimp lists and their details. If you just applied changes to your Mailchimp lists, please use the following button to renew the cached lists configuration.', 'dcfme'); ?></p>


<div id="dcfme-list-fetcher"><form method="POST"><input type="submit" value="Renew Mailchimp lists" class="button"> &nbsp; <div class="loadersmall hidden"></div></form></div>
<!-- <div id="dcfme-list-create" class="float-right"><form method="POST"><input type="submit" value="Create a Mailchimp lists" class="button"> &nbsp; <div class="loadersmall hidden"></div></form></div>

<br>
<br>
<div class="list_create" style="display: none">
	<input class="list_name float-right" type="text" placeholder="List Name"></input>
	<div class="submit float-right" style="clear: both;padding: 0;padding-bottom: 14px;"><div class="loadersmall hidden"></div><input type="submit" name="submit" id="submit" class="button button-primary" value="Craete"> &nbsp; </div>
</div> -->

<div class="dcfme-lists-overview">
	<p><?php echo sprintf('A total of %s lists were found in your Mailchimp account.',
		count($lists)
	); ?></p>

<table class="widefat striped" id="dcfme-mailchimp-lists-overview">
    <thead>
        <tr>
            <th><?php _e('List Name', 'dcfme'); ?></th>
            <th><?php _e('ID', 'dcfme'); ?></th>
            <th><?php _e('Subscribers', 'dcfme'); ?></th>
        </tr>
    </thead>
    <tbody>
        	<?php 

        		foreach ($lists as $key => $value) {
        			?>
        		<tr>
        			<td><a href="#" class="dcfme-mailchimp-list" data-list-id="<?php echo esc_attr($value['id']); ?>"><?php echo esc_textarea($value['name']); ?></a><span class="row-actions alignright"></span></td>
		            <td><code><?php echo esc_attr($value['id']); ?></code></td>
		            <td><?php echo esc_attr($value['member_count']); ?></td>
        		</tr>
        			<?php
        		}
        	 ?>
            
        <tr class="list-details list-<?php echo esc_attr($value['id']); ?>-details" style="display: none;">
            <td colspan="3" style="padding: 0 20px 40px;">
                <p class="alignright" style="margin: 20px 0;"><a href="https://admin.mailchimp.com/lists/members/?id=<?php echo esc_attr($value['id']); ?>" target="_blank"><span class="dashicons dashicons-edit"></span> <?php _e('Edit this list in Mailchimp', 'dcfme'); ?></a></p>

                <div>
                    <div><?php _e('Loading... Please wait.', 'dcfme'); ?></div>
                </div>
            </td>
        </tr>
    </tbody>
</table>

</div>

<?php endif ?>

    </div>


		<div id="forms" class="container tab-pane fade"><br>

<table class="table mc-table">
  <thead>
    <tr>
      <th scope="col">#</th>
      <th scope="col"><?php _e('Form Title', 'dcfme'); ?></th>
     <!--<th scope="col"><?php /*_e('Mailchimp Lists', 'dcfme'); */?></th>-->
      <th scope="col"><?php _e('Status', 'dcfme'); ?></th>
    </tr>
  </thead>
  <tbody>

  	<?php


$args = array(
  'post_type'   => $this->get_all_post_types(),
  'numberposts' => -1,
);
 
$posts = get_posts( $args );

$h = 1;
$x = 1;

		if(!empty($posts)){
			foreach ($posts as $key => $post) {

				if ( strpos( $post->post_content, 'et_pb_contact') !== false ) {
					//print_r($post);
					$postID = esc_attr($post->ID);
					?>
					    <tr post_id="<?php echo $postID; ?>">
					      <th post_id="<?php echo $postID; ?>" scope="row"><?php echo $x; ?></th>
					      <td post_id="<?php echo $postID; ?>"><a href="<?php echo get_post_permalink($postID); ?>" target="_blank"><?php echo esc_attr($post->post_title); ?></a></td>
					 

					      <td post_id="<?php echo $postID; ?>">

					      	<div class="switch">
					          <label>
					            Off 
					            <input type="checkbox" <?php echo (get_dcfme_recorp_mc_by_id($postID, 'status') == "enable")? 'checked': ''; ?>>
					            <span class="lever"></span> On
					          </label>
					        </div>

					    </td>
					    </tr>

					<?php
					$x++;
					$h *= 0;
				}
				
			} //end foreach
		}
		
	?>


  </tbody>
</table>
<?php
			if ($h === 1) {
				echo '<h3 style="text-align: center; margin-bottom: 40px;">Form not found.</h3>';
			}
		?>

<div class="mailchimp_rc_inputs w-100 mb-4">
    <span class="t " style="margin-right: 20px;margin-top: -1px; font-weight: bold;"><?php _e('Select a mailing list', 'dcfme'); ?> </span>
    <select size="5" name="mailing_list" id="mailing_list">
    	
        <?php
        foreach ($lists as $key => $value) {

            $is_select = $this->get_mailchimp_list_id();
            $name = esc_textarea($value['name']);
            $id = esc_attr($value['id']);

            ?>
            <option value="<?php echo $name; ?>" data-list_id="<?php echo $id; ?>" <?php echo ($is_select == $id) ? "selected" : "" ?>><?php echo $name; ?></option>
            <?php
        }
        ?>
    </select>
    <span class="t " style="margin-right: 20px;margin-top: -1px; font-weight: bold;color: red;margin-left: 20px"><?php _e('Upgrade to select multiple mailing list per form. Details in "Upgrade tab".', 'dcfme'); ?> </span>
</div>

<div class="user_permission_settings" style="margin-bottom: 20px;">
	<span class="t float-left" style="margin-right: 20px;margin-top: -1px; font-weight: bold;"><?php _e('Subscribe to our mailing list (user permission)', 'dcfme'); ?> </span> <div class="switch">
      <label>
        <?php _e('Disable', 'dcfme'); ?>
        <input type="checkbox" <?php echo $this->get_user_permission_status() == "enable" ? "checked" : ""; ?>>
        <span class="lever"></span> <?php _e('Enable', 'dcfme'); ?>
      </label>
    </div>

    <p class="help">
        <?php _e('Enable this to add a checkbox in the form "Subscribe to our email list".

        If this option will disable then the user\'s email will automatically store to mailchimp list without the he know.', 'dcfme'); ?>
    </p>
</div>

<div class="user_double_optin" style="margin-bottom: 20px;">
	<span class="t float-left" style="margin-right: 20px;margin-top: -1px; font-weight: bold;"><?php _e('Enable user confirmation email (double opt-in)', 'dcfme'); ?> </span> <div class="switch">
      <label>
        <?php _e('Disable', 'dcfme'); ?>
        <input type="checkbox" id="double-optin" <?php echo $this->get_user_double_optin_status() == "enable" ? "checked" : ""; ?>>
        <span class="lever"></span> <?php _e('Enable', 'dcfme'); ?>
      </label>
    </div>

    <p class="help">
        <?php _e('Enable this to send a confirmation email to the user before subscribe.', 'dcfme'); ?>
    </p>
</div>

<div class="user_permission_text" style="margin-bottom: 20px;">
	<span class="t float-left" style="margin-right: 20px;margin-top: 4px; font-weight: bold;"><?php _e('Subscribe To Our Mailing List Text', 'dcfme'); ?> 
	</span> 

	<div class="mailing_text"><input type="text" name="mailing_text" value="<?php echo esc_textarea(get_option('dcfme_subscribe_text', '')); ?>" placeholder="Enter checkbox text"></div>
</div>

<button type="button" class="btn btn-primary btn-sm mc-data-save"><?php _e('Save Changes', 'dcfme'); ?></button>

<div class="modal" id="divi_mc_licensing"  data-easein="flipXIn" tabindex="3" role="dialog" aria-labelledby="backupAndRestoreTitle" aria-hidden="true">
  <div class="modal-dialog modal-dialog-centered" role="document">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title" id="exampleModalLongTitle"><?php esc_html_e('Divi Contact Form Mailchimp Extension Licensing', 'dcfme'); ?></h5>
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">&times;</span>
        </button>
      </div>
      <div class="modal-body">

      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-secondary" data-dismiss="modal"><?php esc_html_e('Close', 'dcfme'); ?></button>
        <button type="button" class="btn btn-primary dcme_license_activate"><?php esc_html_e('Save Changes', 'dcfme'); ?></button>
      </div>
    </div>
  </div>
</div>

    </div>



    <div id="licensing" class="container tab-pane fade"><br>

    	<div class="pro_features">
            <h3>*Use multiple mailing list per form. </h3>
            <img src="<?php echo plugin_dir_url( dirname( __FILE__ ) ) . 'images/pro_version_layout.png'; ?>" style="width: 90%;border: 3px solid #28a745;" alt="">
        </div>
<div class="buy_button">
    <h4 class="mt-3">Upgarade the plugin from Freemius:</h4>

	<select id="licenses">
	   <option value="1" selected="selected">Single Site License</option>
	   <option value="3">3-Site License</option>
	   <option value="unlimited">Unlimited Sites License</option>
	</select>
	<button id="purchase">Upgrade</button>

    </div>
  </div>
</div>
</div>
