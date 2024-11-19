// JavaScript Document
function table_listing_records_filter() {
	if(jQuery('.add_update_form_content').length > 0) {
		jQuery('.add_update_form_content').html("");
	}
	if(jQuery('#table_listing_records').length > 0) {
		jQuery('#table_records_cover').removeClass('d-none');
		jQuery('#table_listing_records').html('<div class="alert alert-success my-3 mx-3"> Loading... </div>');
	}
	var check_login_session = 1;
	var post_url = "dashboard_changes.php?check_login_session=1";	
	jQuery.ajax({url: post_url, success: function(check_login_session){
		if(check_login_session == 1) {
            var page_title = ""; var post_send_file  = "";
            if(jQuery('input[name="page_title"]').length > 0) {
                page_title = jQuery('input[name="page_title"]').val();
                if(typeof page_title != "undefined" && page_title != "") {
                    page_title = page_title.replaceAll(" ", "_");
                    page_title = page_title.toLowerCase();
                    page_title = jQuery.trim(page_title);
                    post_send_file = page_title+"_changes.php";
                }
            }

			jQuery.ajax({
				url: post_send_file,
				type: "post",
				async: true,
				data: jQuery('form[name="table_listing_form"]').serialize(),
				dataType: 'html',
				contentType: 'application/x-www-form-urlencoded; charset=UTF-8',
				success: function(result) {
					if(jQuery('.alert').length > 0) {
						jQuery('.alert').remove();
					}
					if(jQuery('#table_listing_records').length > 0) {
						jQuery('#table_listing_records').html(result);						
					}
				},
				error: function(jqXHR, textStatus, errorThrown) {
					console.log(textStatus, errorThrown);
				}
			});
		}
		else {
			window.location.reload();
		}
	}}); 
}

function ShowModalContent(page_title, add_edit_id_value) {
	var check_login_session = 1;
	var post_url = "dashboard_changes.php?check_login_session=1";	
	jQuery.ajax({url: post_url, success: function(check_login_session){
		if(check_login_session == 1) {
            var add_edit_id = ""; var post_send_file  = ""; var heading  = "";
            if(typeof page_title != "undefined" && page_title != "") {
				heading = page_title;
				page_title = page_title.replaceAll(" ", "_");
				page_title = page_title.toLowerCase();
                add_edit_id = "show_"+page_title+"_id";
				post_send_file = page_title+"_changes.php";
                page_title = page_title+" Details";
				if(jQuery('.edit_title').length > 0) {
					page_title = page_title.replaceAll("_", " ");
					page_title = page_title.toLowerCase().replace(/\b[a-z]/g, function(string) {
						return string.toUpperCase();
					});
					jQuery('.edit_title').html(page_title);
				}
				if(jQuery('#table_records_cover').length > 0) {
					jQuery('#table_records_cover').addClass('d-none');
				}
            }
			var post_url = post_send_file+"?"+add_edit_id+"="+add_edit_id_value;
			jQuery.ajax({url: post_url, success: function(result){
				if(jQuery('.add_update_form_content').length > 0) {
					jQuery('.add_update_form_content').html("");
					jQuery('.add_update_form_content').html(result);
				}
				jQuery('html, body').animate({
					scrollTop: (jQuery('.add_update_form_content').parent().parent().offset().top)
				}, 500);
			}});
		}
		else {
			window.location.reload();
		}
	}});
}

function SubmitFormContent(event, form_name, post_send_file, redirection_file) {
	event.preventDefault();
			
	if(jQuery('span.infos').length > 0) {
		jQuery('span.infos').remove();
	}
	if(jQuery('.valid_error').length > 0) {
		jQuery('.valid_error').remove();
	}
	if(jQuery('div.alert').length > 0) {
		jQuery('div.alert').remove();
	}
	
    jQuery('form[name="'+form_name+'"]').find('.row:first').before('<div class="alert alert-danger mb-3"> <button type="button" class="close" data-dismiss="alert">&times;</button> Processing </div>');
	if(jQuery('form[name="'+form_name+'"]').find('.submit_button').length > 0) {
		jQuery('form[name="'+form_name+'"]').find('.submit_button').attr('disabled', true);
	}
	
	jQuery('html, body').animate({
		scrollTop: (jQuery('.add_update_form_content').parent().parent().offset().top)
	}, 500);

	jQuery.ajax({
		url: post_send_file,
		type: "post",
		async: true,
		data: jQuery('form[name="'+form_name+'"]').serialize(),
		dataType: 'html',
		contentType: 'application/x-www-form-urlencoded; charset=UTF-8',
		success: function(data) {
			//console.log(data);
			if(typeof data == "undefined" || data == "" || data == null) {
				if(jQuery('form[name="'+form_name+'"]').find('.submit_button').length > 0) {
					jQuery('form[name="'+form_name+'"]').find('.submit_button').attr('disabled', false);
				}
			}

			try {
				var x = JSON.parse(data);
			} catch (e) {
				return false;
			}
			//console.log(x);

			if(jQuery('span.infos').length > 0) {
                jQuery('span.infos').remove();
            }
            if(jQuery('.valid_error').length > 0) {
                jQuery('.valid_error').remove();
            }
            if(jQuery('div.alert').length > 0) {
                jQuery('div.alert').remove();
            }
			
			if(x.number == '1') {
				jQuery('form[name="'+form_name+'"]').find('.row:first').before('<div class="alert alert-success"> <button type="button" class="close" data-dismiss="alert">&times;</button> '+x.msg+' </div>');
				setTimeout(function(){ 
					if(typeof x.otp_send_date != "undefined" && x.otp_send_date != null && x.otp_send_date != "") {
                        var post_url = "otp_changes.php?show_otp_verification_form=1&check_otp_send_date="+x.otp_send_date+"&check_otp_receive_mobile_number="+x.otp_receive_mobile_number+"&check_otp_number="+x.otp_number+"&otp_form_name="+form_name;
						jQuery.ajax({url: post_url, success: function(result){
							if(jQuery('#otp_form_cover').length > 0) {
								jQuery('#otp_form_cover').html(result);
							}
						}});
                    }
					else if(jQuery('.redirection_form').length > 0) {
						window.location = redirection_file;
					}
					else {
						table_listing_records_filter();
					}
				}, 1000);
			}
			
			if(x.number == '2') {
				jQuery('form[name="'+form_name+'"]').find('.row:first').before('<div class="alert alert-danger"> <button type="button" class="close" data-dismiss="alert">&times;</button> '+x.msg+' </div>');
				if(jQuery('form[name="'+form_name+'"]').find('.submit_button').length > 0) {
					jQuery('form[name="'+form_name+'"]').find('.submit_button').attr('disabled', false);
				}
			}
			
			if(x.number == '3') {
				jQuery('form[name="'+form_name+'"]').append('<div class="valid_error"> <script type="text/javascript"> '+x.msg+' </script> </div>');
				if(jQuery('form[name="'+form_name+'"]').find('.submit_button').length > 0) {
					jQuery('form[name="'+form_name+'"]').find('.submit_button').attr('disabled', false);
				}
			}
		},
		error: function(jqXHR, textStatus, errorThrown) {
			console.log(textStatus, errorThrown);
		}
	});
}

function CheckPassword(field_name) {
    var password = "";
    if(jQuery('input[name="'+field_name+'"]').length > 0) {
        password = jQuery('input[name="'+field_name+'"]').val();
        password = jQuery.trim(password);
    }

	if(jQuery('#'+field_name+'_cover').length > 0) {
		if(jQuery('#'+field_name+'_cover').find('label').length > 0) {
			jQuery('#'+field_name+'_cover').find('label').addClass('text-danger');
		}
		if(jQuery('#'+field_name+'_cover').find('input[name="length_check"]').length > 0) {
			jQuery('#'+field_name+'_cover').find('input[name="length_check"]').prop('checked', false);
		}
		if(jQuery('#'+field_name+'_cover').find('input[name="letter_check"]').length > 0) {
			jQuery('#'+field_name+'_cover').find('input[name="letter_check"]').prop('checked', false);
		}
		if(jQuery('#'+field_name+'_cover').find('input[name="number_symbol_check"]').length > 0) {
			jQuery('#'+field_name+'_cover').find('input[name="number_symbol_check"]').prop('checked', false);
		}
		if(jQuery('#'+field_name+'_cover').find('input[name="space_check"]').length > 0) {
			jQuery('#'+field_name+'_cover').find('input[name="space_check"]').prop('checked', false);
		}

		var upper_regex = /[A-Z]/; var lower_regex = /[a-z]/; 
		var number_regex = /\d/; var symbol_regex = /[\'\/~`\!@#\$%\^&\*\(\)_\-\+=\{\}\[\]\|;:"\<\>,\.\?\\]/; var no_space_regex = /^\S+$/;

		if(typeof password != "undefined" && password != null && password != "") {
			var password_length = password.length;
			if(parseInt(password_length) >= 8 && parseInt(password_length) <= 20) {
				if(jQuery('#'+field_name+'_cover').find('input[name="length_check"]').length > 0) {
					jQuery('#'+field_name+'_cover').find('input[name="length_check"]').prop('checked', true);
					if(jQuery('#'+field_name+'_cover').find('input[name="length_check"]').parent().find('label').length > 0) {
						jQuery('#'+field_name+'_cover').find('input[name="length_check"]').parent().find('label').removeClass('text-danger');
						jQuery('#'+field_name+'_cover').find('input[name="length_check"]').parent().find('label').addClass('text-success');
					}
				}
			}
			if( (upper_regex.test(password) == true) && (lower_regex.test(password) == true) ) {
				if(jQuery('#'+field_name+'_cover').find('input[name="letter_check"]').length > 0) {
					jQuery('#'+field_name+'_cover').find('input[name="letter_check"]').prop('checked', true);
					if(jQuery('#'+field_name+'_cover').find('input[name="letter_check"]').parent().find('label').length > 0) {
						jQuery('#'+field_name+'_cover').find('input[name="letter_check"]').parent().find('label').removeClass('text-danger');
						jQuery('#'+field_name+'_cover').find('input[name="letter_check"]').parent().find('label').addClass('text-success');
					}
				}
			}
			if( (number_regex.test(password) == true) && (symbol_regex.test(password) == true) ) {
				if(jQuery('#'+field_name+'_cover').find('input[name="number_symbol_check"]').length > 0) {
					jQuery('#'+field_name+'_cover').find('input[name="number_symbol_check"]').prop('checked', true);
					if(jQuery('#'+field_name+'_cover').find('input[name="number_symbol_check"]').parent().find('label').length > 0) {
						jQuery('#'+field_name+'_cover').find('input[name="number_symbol_check"]').parent().find('label').removeClass('text-danger');
						jQuery('#'+field_name+'_cover').find('input[name="number_symbol_check"]').parent().find('label').addClass('text-success');
					}
				}
			}
			if(no_space_regex.test(password) == true) {
				if(jQuery('#'+field_name+'_cover').find('input[name="space_check"]').length > 0) {
					jQuery('#'+field_name+'_cover').find('input[name="space_check"]').prop('checked', true);
					if(jQuery('#'+field_name+'_cover').find('input[name="space_check"]').parent().find('label').length > 0) {
						jQuery('#'+field_name+'_cover').find('input[name="space_check"]').parent().find('label').removeClass('text-danger');
						jQuery('#'+field_name+'_cover').find('input[name="space_check"]').parent().find('label').addClass('text-success');
					}
				}
			}			
		}
	}	
}

function CustomCheckboxToggle(obj, toggle_id) {
    var check_login_session = 1;
    var post_url = "dashboard_changes.php?check_login_session=1";	
    jQuery.ajax({url: post_url, success: function(check_login_session){
        if(check_login_session == 1) {	
            var toggle_value = 2;
			if(jQuery('#'+toggle_id).length > 0) {
				if(jQuery('#'+toggle_id).prop('checked') == true) {
					toggle_value = 1;
				}
				jQuery('#'+toggle_id).val(toggle_value);
			}
        }
        else {
            window.location.reload();
        }
    }});
}

function getCharCount(obj, chat_count_class, chat_total_count_class) {
	var char_total_count = 0;
	if(jQuery('.'+chat_total_count_class).length > 0) {
		char_total_count = jQuery('.'+chat_total_count_class).html();
		char_total_count = char_total_count.trim();
		if(char_total_count != 0 && char_total_count != "" && typeof char_total_count != "undefined") {
			var char_value = jQuery(obj).val();
			char_value = char_value.trim();
			if(char_value != '' && typeof char_value != "undefined") {
				var char_count = char_value.length;
				if(jQuery('.'+chat_count_class).length > 0) {
					if(parseInt(char_count) <= parseInt(char_total_count)) {
						jQuery('.'+chat_count_class).html(char_count);
					}
					else {
						jQuery('.'+chat_count_class).html(char_total_count);
						char_value = char_value.subString(0, char_total_count); 
						jQuery(obj).val(char_value);
					}
				}
			}
			else {
				jQuery('.'+chat_count_class).html('');
			}
		}
		else {
			jQuery('.'+chat_count_class).html('');
		}
	}	
}

function DeleteModalContent(page_title, delete_content_id) {
	var check_login_session = 1;
	var post_url = "dashboard_changes.php?check_login_session=1";	
	jQuery.ajax({url: post_url, success: function(check_login_session){
		if(check_login_session == 1) {		
            if(typeof page_title != "undefined" && page_title != "") {
				jQuery('#DeleteModal .modal-header').find('h4').html("");
				jQuery('#DeleteModal .modal-header').find('h4').html("Delete "+page_title);
                page_title = page_title.toLowerCase();
            }            
			jQuery('.delete_modal_button').trigger("click");	
			
			jQuery('#DeleteModal .modal-body').html('');

			jQuery('#DeleteModal .modal-body').html('Are you surely want to delete this '+page_title+'?');
			
			jQuery('#DeleteModal .modal-footer .yes').attr('id', delete_content_id);
			jQuery('#DeleteModal .modal-footer .no').attr('id', delete_content_id); 
		}
		else {
			window.location.reload();
		}
	}});
}

function confirm_delete_modal(obj) {
	var check_login_session = 1;
	var post_url = "dashboard_changes.php?check_login_session=1";	
	jQuery.ajax({url: post_url, success: function(check_login_session){
		if(check_login_session == 1) {

            if(jQuery('#DeleteModal .modal-body').find('.infos').length > 0) {
                jQuery('#DeleteModal .modal-body').find('.infos').remove();
            }

            var page_title = ""; var post_send_file  = "";
            if(jQuery('input[name="page_title"]').length > 0) {
                page_title = jQuery('input[name="page_title"]').val();
                if(typeof page_title != "undefined" && page_title != "") {
                    page_title = page_title.replaceAll(" ", "_");
                    page_title = page_title.toLowerCase();
                    page_title = jQuery.trim(page_title);
                    post_send_file = page_title+"_changes.php";
                }
            }

			var delete_content_id = jQuery(obj).attr('id');
			var post_url = post_send_file+"?delete_"+page_title+"_id="+delete_content_id;
			jQuery.ajax({url: post_url, success: function(result){
				jQuery('#DeleteModal .modal-content').animate({ scrollTop: 0 }, 500);

				var intRegex = /^\d+$/;
				if(intRegex.test(result) == true) {
					jQuery('#DeleteModal .modal-body').append('<div class="alert alert-success"> <button type="button" class="close" data-dismiss="alert">&times;</button> Successfully Delete the '+page_title.replaceAll("_", " ")+' </div>');
					setTimeout(function(){ 
						jQuery('#DeleteModal .modal-header .close').trigger("click");
						window.location.reload(); 
					}, 1000);
					
				}
				else {
					jQuery('#DeleteModal .modal-body').append('<span class="infos w-100 text-center" style="font-size: 15px; font-weight: bold;">'+result+'</span>');
				}
			}});
		}
		else {
			window.location.reload();
		}
	}});
}

function cancel_delete_modal(obj) {
	var check_login_session = 1;
	var post_url = "dashboard_changes.php?check_login_session=1";	
	jQuery.ajax({url: post_url, success: function(check_login_session){
		if(check_login_session == 1) {
			jQuery('#DeleteModal .modal-header .close').trigger("click");
		}
		else {
			window.location.reload();
		}
	}}); 
}