(function( $ ) {
	'use strict';

	/**
	 * All of the code for your admin-facing JavaScript source
	 * should reside in this file.
	 *
	 * Note: It has been assumed you will write jQuery code here, so the
	 * $ function reference has been prepared for usage within the scope
	 * of this function.
	 *
	 * This enables you to define handlers, for when the DOM is ready:
	 *
	 * $(function() {
	 *
	 * });
	 *
	 * When the window is loaded:
	 *
	 * $( window ).load(function() {
	 *
	 * });
	 *
	 * ...and/or other possibilities.
	 *
	 * Ideally, it is not considered best practise to attach more than a
	 * single DOM-ready or window-load handler for a particular page.
	 * Although scripts in the WordPress core, Plugins and Themes may be
	 * practising this, we should strive to set a better example in our own work.
	 */


	 $(document).on("click", ".wc_in_de_btn.plus_mc_field", function(){
	 	var mc_new_field = $('.copy_mc_field').html();
	 	$(this).closest('.mailchimp_rc_inputs').append(mc_new_field);
	 });

	 $(document).on("click", ".wc_in_de_btn.minus_mc_field", function(){
	 	$(this).closest('.mailchimp_rc_input').remove();
	 });

function recorp_validURL(str) {
   var regexp =  /^(?:(?:https?|ftp):\/\/)?(?:(?!(?:10|127)(?:\.\d{1,3}){3})(?!(?:169\.254|192\.168)(?:\.\d{1,3}){2})(?!172\.(?:1[6-9]|2\d|3[0-1])(?:\.\d{1,3}){2})(?:[1-9]\d?|1\d\d|2[01]\d|22[0-3])(?:\.(?:1?\d{1,2}|2[0-4]\d|25[0-5])){2}(?:\.(?:[1-9]\d?|1\d\d|2[0-4]\d|25[0-4]))|(?:(?:[a-z\u00a1-\uffff0-9]-*)*[a-z\u00a1-\uffff0-9]+)(?:\.(?:[a-z\u00a1-\uffff0-9]-*)*[a-z\u00a1-\uffff0-9]+)*(?:\.(?:[a-z\u00a1-\uffff]{2,})))(?::\d{2,5})?(?:\/\S*)?$/;
        if (regexp.test(str))
        {
          return true;
        }
        else
        {
          return false;
        }
    }

function divi_contactact_get_mailchimp_rc_urls(inputs){

	var urls = [];
	inputs.each(function(){
		var mc_url = $(this).find('input');

		if (mc_url.val() !== "" && recorp_validURL( mc_url.val() ) ) {
			urls.push(mc_url.val());
			mc_url.css({'border': '1px solid #ddd'});
			mc_url.closest('.mailchimp_rc_input').children(".text-danger").addClass("hidden");
		} else if(mc_url.val() == "") {
			urls.push("");
		} else {
			console.log("Please set a valid url!");
			mc_url.css({'border': '1px solid red'});

			mc_url.closest('.mailchimp_rc_input').children(".text-danger").removeClass("hidden");

			return false;
		}
		
	});

	return urls;
}
	 $(document).on("click", ".mc-data-save", function(){

	 	var form_data = [];
	 	//var mc = new Array();
	 	var mc = new Object();


		 var t = ( $('#mailing_list').val() !== null ) ?  $('#mailing_list').val() : "";
		 var list_id = $('#mailing_list').find('[value="'+t+'"]').data('list_id');

	 	$('.table.mc-table tbody tr').each(function(){
		 	var mailchim_urls 	= [];
		 	//var mc_post 		= [];
		 	var mc_post 		= new Object();
		 	var status 			= [];

		 	var unit = [];


	 		var post_id = $(this).attr('post_id');

	 		mc_post['post_id'] = (post_id);
		 		//post_id.push(post_id);

			/*var t = ( $(this).find($('select')).val() !== null ) ?  $(this).find($('select')).val() : "";

			var list_id = [];
			//var list_id = new Array();

			if (typeof t !== "undefined") {
				for (var i = 0; i < t.length; i++) {
					var id = $(this).find('[value="'+t[i]+'"]').data('list_id');
					list_id.push(id);
				}
			}*/


		 	mc_post.list_id = (list_id);

	 		/*Status*/

	 		var statuses = $(this).find('.switch').find('[type="checkbox"]');

	 		if (statuses.is(':checked')) {
				mc_post.form_status = ('enable');
	 		} else {
				mc_post.form_status = ('disable');
	 		}
			
	 		form_data.push(mc_post);

	 	});

	 	mc.form_data = (form_data);

 		var statuses = $('.user_permission_settings').find('.switch').find('[type="checkbox"]');

 		var settings_status = new Object();

		 settings_status.mailing_list = list_id;

 		if (statuses.is(':checked')) {
			settings_status.user_permission = ('enable');
 		} else {
			settings_status.user_permission = ('disable');
 		}

		 var double = $('#double-optin');

		 if (double.is(':checked')) {
			 settings_status.user_double_optin = ('enable');
		 } else {
			 settings_status.user_double_optin = ('disable');
		 }
		 //mc.settings = double_optin_status;

		 mc.settings = settings_status;

 		 var datas = {
 		  'action': 'divi_contact_for_mailchimp_rc_save_data',
 		  'mc_table': JSON.stringify(mc),
 		  'subscribe_text': $('.mailing_text input').val(),
 		  'rc_nonce': dcfme.nonce
 		};
 		
 		$.ajax({
 		    url: dcfme.ajax_url,
 		    data: datas,
 		    type: 'post',
 		    dataType: 'json',
 		
 		    success: function(r){
 		    	
 		    	if (r.success) {
		 		    $.notify({
							// options
							message: 'Successfully saved!' 
						},{
							// settings
							type: 'success',
							placement: {
								from: "top",
								align: "center"
							},
							animate:{
								enter: "animated fadeInDown",
								exit: "animated fadeOutUp"
							},
							delay: 2000
						}

					);
				}
				else{
					alert('Something went wrong !');
				}

	 		    setTimeout(function() {
	 		    	//window.location.reload();
	 		    }, 2500);
 		    }, error: function(){
 		    	alert('Something went wrong !');
 		  }
 		});
	 });

  //$(".modal").each(function (l) {$(this).on("show.bs.modal", function (l) {var o = $(this).attr("data-easein");"shake" == o ? $(".modal-dialog").velocity("callout." + o) : "pulse" == o ? $(".modal-dialog").velocity("callout." + o) : "tada" == o ? $(".modal-dialog").velocity("callout." + o) : "flash" == o ? $(".modal-dialog").velocity("callout." + o) : "bounce" == o ? $(".modal-dialog").velocity("callout." + o) : "swing" == o ? $(".modal-dialog").velocity("callout." + o) : $(".modal-dialog").velocity("transition." + o);});});


$(document).on("click", "#mailchimp #submit", function(e){
	e.preventDefault();

	var this_ = $(this);
	var mailchimp_rc_api_key = $('#mailchimp_rc_api_key').val();

	var is_valid = mailchimp_rc_api_key.match(/^[0-9a-zA-Z*]{32}-[a-z]{2}[0-9]{1,2}$/);

	if (!is_valid) {

		$('#mailchimp .invalid').show();
		return false;
	}
	$('#mailchimp .invalid').fadeOut();
	 var datas = {
	  'action': 'save_dcfme_mailchimp_rc_api',
	  'key': mailchimp_rc_api_key,
	  'rc_nonce': dcfme.nonce
	};
	
	$.ajax({
	    url: dcfme.ajax_url,
	    data: datas,
	    type: 'post',
	    dataType: 'json',
	
		beforeSend: function(){
			$('#mailchimp .submit .loadersmall').removeClass('hidden');
		},
	    success: function(r){
	    	console.log(r);
	    	$('#mailchimp .submit .loadersmall').addClass('hidden');
	    	if (r.success && r.status == 'connected') {
	    		$('#mailchimp .status').text('CONNECTED');
	    		$('#mailchimp .status').addClass('active');
	    		$('p.invalid').hide();

	    		window.location.reload();
	    	} else if(!r.success && r.status == 'invalid') {
	    		$('p.invalid').show();
	    	}
	    	else{
	    		$('#dcfme-list-fetcher .loadersmall').addClass('hidden');
	    		alert('Something went wrong!');
	    	}
	    	//console.log(r);
	    }, error: function(){
	    	$('#dcfme-list-fetcher .loadersmall').addClass('hidden');
	    	alert('Something went wrong!');
	  }
	});
});


$(document).on("click", "#dcfme-list-fetcher", function(e){
	e.preventDefault();

	 var datas = {
	  'action': 'dcfme_refresh_mailchimp_rc_lists',
	  'rc_nonce': dcfme.nonce
	};
	
	$.ajax({
	    url: dcfme.ajax_url,
	    data: datas,
	    type: 'post',
	    dataType: 'json',
		beforeSend: function(){
			$('#dcfme-list-fetcher .loadersmall').removeClass('hidden');
		},

	    success: function(r){
	    	if (r.success) {
		    	$('#dcfme-list-fetcher .loadersmall').addClass('hidden');
		    	setTimeout(function() {window.location.reload();}, 500);
	    	}else{
	    		$('#dcfme-list-fetcher .loadersmall').addClass('hidden');
	    		alert('Something went wrong!');
	    	}
	    }, error: function(){
	    	$('#dcfme-list-fetcher .loadersmall').addClass('hidden');
	  }
	});
});

$(document).on("click", ".dcfme-mailchimp-list", function(e){
	e.preventDefault();

	var list_id = $(this).data('list-id');

	 var datas = {
	  'action': 'get_dcfme_mailchimp_rc_list_merge_tags',
	  'list_id': list_id,
	  'rc_nonce': dcfme.nonce
	};
	
	$.ajax({
	    url: dcfme.ajax_url,
	    data: datas,
	    type: 'post',
	    dataType: 'json',
	
	    beforeSend: function(){
			$('.list-details').show();
	    },
	    success: function(r){

	    	if (r.success) {
	    		$('.list-details div').html(r.response);
			}else{
				alert('Something went wrong!');
			}
	    	
	    }, error: function(){
	    	
	  }
	});
});


$(function () { 
	
	$('.mailchimp_rc_inputs  select').each(function(){
		$(this).multiselect({ 
	        buttonText: function(options, select) {
	           // console.log(select[0].length);
	            if (options.length === 0) {
	                return 'None selected';
	            }
	            if (options.length === select[0].length && select[0].length != 1) {
	                return 'All selected ('+select[0].length+')';
	            }
	            else if (options.length >=10) {
	                return options.length + ' selected';
	            }
	            else {
	                var labels = [];
	                //console.log(options);
	                options.each(function() {
	                    labels.push($(this).val());
	                });
	                return labels.join(', ') + '';
	            }
	        }
	    
	    });
	});

});

$(document).on("click", ".multiselect.dropdown-toggle", function(){
	$(this).parent().find('.multiselect-container.dropdown-menu').fadeToggle();
});

$(document).click(function(e)
{
    var container = $(".multiselect-container.dropdown-menu, .multiselect.dropdown-toggle");
    if (!container.is(e.target) && container.has(e.target).length === 0)
    {
        $('.multiselect-container.dropdown-menu').hide();
    }
});

$(document).on("click", ".nav.nav-tabs .nav-link", function(){
	$(".nav.nav-tabs .nav-link").removeClass('active');
	$(this).addClass('active');
	
});

$(document).on("click", "#dcfme-list-create", function(e){
	e.preventDefault();
	$('.list_create').slideToggle();
});

$(document).on("click", ".divi_mc_licensing", function(){
	$('.nav-link.licensing').click();
});

$(document).on("click", ".upgrade_button", function(){
	$('#licensing-tab').tab('show');
});

})( jQuery );

