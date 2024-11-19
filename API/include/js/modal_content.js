function AddProfession() {
	if(jQuery('#ProfessionModal .modal-body').find('.infos').length > 0) {
		jQuery('#ProfessionModal .modal-body').find('.infos').remove();
	}
	if(jQuery('#ProfessionModal .modal-body').find('.alert').length > 0) {
		jQuery('#ProfessionModal .modal-body').find('.alert').remove();
	}
    if(jQuery('#ProfessionModal .modal-body').find('.valid_error').length > 0) {
		jQuery('#ProfessionModal .modal-body').find('.valid_error').remove();
	}
    if(jQuery('input[name="profession_insert_id"]').length > 0) {
		jQuery('input[name="profession_insert_id"]').val('');
	}
	
	var check_login_session = 1;
	var post_url = "dashboard_changes.php?check_login_session=1";	
	jQuery.ajax({url: post_url, success: function(check_login_session){
		if(check_login_session == 1) {
			if(jQuery('#ProfessionModal .modal-body').find('input[name="name"]').length > 0) {
                jQuery('#ProfessionModal .modal-body').find('input[name="name"]').val('');
            }
			if(jQuery('.profession_modal_button').length > 0) {
				jQuery('.profession_modal_button').trigger('click');
			}	
		}
		else {
			window.location.reload();
		}
	}});
}
function SaveProfession(obj) {
	var check_login_session = 1;
	var post_url = "dashboard_changes.php?check_login_session=1";	
	jQuery.ajax({url: post_url, success: function(check_login_session){
		if(check_login_session == 1) {
			if(jQuery('#ProfessionModal .modal-body').find('.infos').length > 0) {
                jQuery('#ProfessionModal .modal-body').find('.infos').remove();
            }
            if(jQuery('#ProfessionModal .modal-body').find('.alert').length > 0) {
                jQuery('#ProfessionModal .modal-body').find('.alert').remove();
            }
            if(jQuery('#ProfessionModal .modal-body').find('.valid_error').length > 0) {
                jQuery('#ProfessionModal .modal-body').find('.valid_error').remove();
            }
            if(jQuery('input[name="profession_insert_id"]').length > 0) {
                jQuery('input[name="profession_insert_id"]').val('');
            }

            var form_name = "profession_form";

			jQuery.ajax({
				url: 'profession_changes.php',
				type: "post",
				async: true,
				data: jQuery('form[name="'+form_name+'"]').serialize(),
				dataType: 'html',
				contentType: 'application/x-www-form-urlencoded; charset=UTF-8',
				success: function(data) {
					jQuery('#ProfessionModal .modal-content').animate({ scrollTop: 0 }, 500);

                    try {
                        var x = JSON.parse(data);
                    } catch (e) {
                        return false;
                    }

                    if(jQuery('#ProfessionModal .modal-body').find('.infos').length > 0) {
                        jQuery('#ProfessionModal .modal-body').find('.infos').remove();
                    }
                    if(jQuery('#ProfessionModal .modal-body').find('.alert').length > 0) {
                        jQuery('#ProfessionModal .modal-body').find('.alert').remove();
                    }
                    if(jQuery('#ProfessionModal .modal-body').find('.valid_error').length > 0) {
                        jQuery('#ProfessionModal .modal-body').find('.valid_error').remove();
                    }

                    if(x.number == '1') {
                        jQuery('#ProfessionModal').find('form[name="'+form_name+'"]').find('.row:first').before('<div class="alert alert-success"> <button type="button" class="close" data-dismiss="alert">&times;</button> '+x.msg+' </div>');
                        setTimeout(function(){ 
                            jQuery('#ProfessionModal .modal-header .close').trigger("click");
                            if(typeof x.profession_insert_id != "undefined" && x.profession_insert_id != null && x.profession_insert_id != "") {
                                if(jQuery('input[name="profession_insert_id"]').length > 0) {
                                    jQuery('input[name="profession_insert_id"]').val(x.profession_insert_id);
                                }
                            }
							ReloadProfession();
                        }, 1000);
                    }
                    
                    if(x.number == '2') {
                        jQuery('#ProfessionModal').find('form[name="'+form_name+'"]').find('.row:first').before('<div class="alert alert-danger"> <button type="button" class="close" data-dismiss="alert">&times;</button> '+x.msg+' </div>');
                    }
                    
                    if(x.number == '3') {
                        jQuery('#ProfessionModal').find('form[name="'+form_name+'"]').append('<div class="valid_error"> <script type="text/javascript"> '+x.msg+' </script> </div>');
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
function ReloadProfession() {
	if(jQuery('#ProfessionModal .modal-body').find('.infos').length > 0) {
		jQuery('#ProfessionModal .modal-body').find('.infos').remove();
	}
	if(jQuery('#ProfessionModal .modal-body').find('.alert').length > 0) {
		jQuery('#ProfessionModal .modal-body').find('.alert').remove();
	}
    if(jQuery('#ProfessionModal .modal-body').find('.valid_error').length > 0) {
		jQuery('#ProfessionModal .modal-body').find('.valid_error').remove();
	}

	var profession_insert_id = "";
	if(jQuery('input[name="profession_insert_id"]').length > 0) {
		profession_insert_id = jQuery('input[name="profession_insert_id"]').val();
	}

	var check_login_session = 1;
	var post_url = "dashboard_changes.php?check_login_session=1";	
	jQuery.ajax({url: post_url, success: function(check_login_session){
		if(check_login_session == 1) {
			var post_url = "action_changes.php?reload_profession_id="+profession_insert_id+"&location_id=profession_list_cover";
			jQuery.ajax({url: post_url, success: function(result){
				if(jQuery('#profession_list_cover').length > 0) {
					jQuery('#profession_list_cover').html(result);
				}
                if(jQuery('input[name="profession_insert_id"]').length > 0) {
                    jQuery('input[name="profession_insert_id"]').val('');
                }

				var post_url = "action_changes.php?reload_profession_id="+profession_insert_id+"&location_id=child_profession_list_cover";
				jQuery.ajax({url: post_url, success: function(result){
					if(jQuery('.child_profession_list_cover').length > 0) {
						jQuery('.child_profession_list_cover').each(function() {
							jQuery(this).html(result);
						});
					}
				}});
			}});
		}
		else {
			window.location.reload();
		}
	}});
}
function CancelProfession(obj) {
	if(jQuery('#ProfessionModal .modal-body').find('.infos').length > 0) {
		jQuery('#ProfessionModal .modal-body').find('.infos').remove();
	}
	if(jQuery('#ProfessionModal .modal-body').find('.alert').length > 0) {
		jQuery('#ProfessionModal .modal-body').find('.alert').remove();
	}
    if(jQuery('#ProfessionModal .modal-body').find('.valid_error').length > 0) {
		jQuery('#ProfessionModal .modal-body').find('.valid_error').remove();
	}
    if(jQuery('input[name="profession_insert_id"]').length > 0) {
		jQuery('input[name="profession_insert_id"]').val('');
	}
	var check_login_session = 1;
	var post_url = "dashboard_changes.php?check_login_session=1";	
	jQuery.ajax({url: post_url, success: function(check_login_session){
		if(check_login_session == 1) {
			jQuery('#ProfessionModal .modal-header .close').trigger("click");
		}
		else {
			window.location.reload();
		}
	}}); 
}