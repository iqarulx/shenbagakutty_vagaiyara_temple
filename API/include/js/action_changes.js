function getNatchathiramListByRasi(rasi, dropdown_name) {
    if(jQuery('#'+dropdown_name+'_list').length > 0) {
        jQuery('#'+dropdown_name+'_list').html('');
    }

	var check_login_session = 1;
	var post_url = "dashboard_changes.php?check_login_session=1";	
	jQuery.ajax({url: post_url, success: function(check_login_session){
		if(check_login_session == 1) {
			var post_url = "action_changes.php?get_natchathiram_list_by_rasi="+rasi+"&natchathiram_dropdown_name="+dropdown_name;
			jQuery.ajax({url: post_url, success: function(result){
				if(jQuery('#'+dropdown_name+'_list').length > 0) {
					jQuery('#'+dropdown_name+'_list').html(result);
				}
			}});
		}
		else {
			window.location.reload();
		}
	}});
}

function getFatherNameByID() {
    if(jQuery('input[name="father_name"]').length > 0) {
        jQuery('input[name="father_name"]').val('');
    }

	var father_id = "";
	if(jQuery('input[name="father_id"]').length > 0) {
		father_id = jQuery('input[name="father_id"]').val();
        father_id = jQuery.trim(father_id);
	}

	var check_login_session = 1;
	var post_url = "dashboard_changes.php?check_login_session=1";	
	jQuery.ajax({url: post_url, success: function(check_login_session){
		if(check_login_session == 1) {
			var post_url = "action_changes.php?get_father_name_by_id="+father_id;
			jQuery.ajax({url: post_url, success: function(result){
				if(jQuery('input[name="father_name"]').length > 0) {
                    jQuery('input[name="father_name"]').val(result);
                }
			}});
		}
		else {
			window.location.reload();
		}
	}});
}

function getIntroducerNameByID() {
    if(jQuery('input[name="introducer_name"]').length > 0) {
        jQuery('input[name="introducer_name"]').val('');
    }

	var introducer_id = "";
	if(jQuery('input[name="introducer_id"]').length > 0) {
		introducer_id = jQuery('input[name="introducer_id"]').val();
        introducer_id = jQuery.trim(introducer_id);
	}

	var check_login_session = 1;
	var post_url = "dashboard_changes.php?check_login_session=1";	
	jQuery.ajax({url: post_url, success: function(check_login_session){
		if(check_login_session == 1) {
			var post_url = "action_changes.php?get_introducer_name_by_id="+introducer_id;
			jQuery.ajax({url: post_url, success: function(result){
				if(jQuery('input[name="introducer_name"]').length > 0) {
                    jQuery('input[name="introducer_name"]').val(result);
                }
			}});
		}
		else {
			window.location.reload();
		}
	}});
}

function ViewProfilePhoto(src) {
	if(jQuery('#ProfilePhotoModal').find('.modal-body').length > 0) {
		jQuery('#ProfilePhotoModal').find('.modal-body').html('');
	}
	if(typeof src != "undefined" && src != null && src != "") {
		if(jQuery('#ProfilePhotoModal').find('.modal-body').length > 0) {
			jQuery('#ProfilePhotoModal').find('.modal-body').html('<img src="'+src+'" class="img-fluid">');
		}
		if(jQuery('.profile_photo_modal_button').length > 0) {
			jQuery('.profile_photo_modal_button').trigger("click");
		}
	}
}

function AddMemberChildRow() {
	var check_login_session = 1;
	var post_url = "dashboard_changes.php?check_login_session=1";	
	jQuery.ajax({url: post_url, success: function(check_login_session){
		if(check_login_session == 1) {

			var boys_count = 0;
			if(jQuery('input[name="boys_count"]').length > 0) {
				boys_count = jQuery('input[name="boys_count"]').val();
				boys_count = jQuery.trim(boys_count);
			}
			var girls_count = 0;
			if(jQuery('input[name="girls_count"]').length > 0) {
				girls_count = jQuery('input[name="girls_count"]').val();
				girls_count = jQuery.trim(girls_count);
			}

			var post_url = "action_changes.php?add_member_child_row=1&boys_count="+boys_count+"&girls_count="+girls_count;
			jQuery.ajax({url: post_url, success: function(result){
				if(jQuery('.member_child_details_table').find('tbody:first').length > 0) {
                    jQuery('.member_child_details_table').find('tbody:first').append(result);
                }
				if(jQuery('.sno').length > 0 ) {
					var sno = 1;
					jQuery('.sno').each(function(){
						jQuery(this).html(sno);
						sno = parseInt(sno) + 1;
					});
				}
			}});
		}
		else {
			window.location.reload();
		}
	}});
}
function getMemberChildWifeDetails(obj, status) {
	var check_login_session = 1;
	var post_url = "dashboard_changes.php?check_login_session=1";	
	jQuery.ajax({url: post_url, success: function(check_login_session){
		if(check_login_session == 1) {
			//console.log('status - '+status);
			if(parseInt(status) == 1) {
				if(jQuery(obj).parent().parent().parent().parent().parent().find('.wife_details').length > 0) {
					jQuery(obj).parent().parent().parent().parent().parent().find('.wife_details').removeClass('d-none');
				}
				else if(jQuery(obj).parent().parent().parent().parent().parent().find('.husband_details').length > 0) {
					jQuery(obj).parent().parent().parent().parent().parent().find('.husband_details').removeClass('d-none');
				}
			}
			else {
				if(jQuery(obj).parent().parent().parent().parent().parent().find('.wife_details').length > 0) {
					jQuery(obj).parent().parent().parent().parent().parent().find('.wife_details').addClass('d-none');
				}
				else if(jQuery(obj).parent().parent().parent().parent().parent().find('.husband_details').length > 0) {
					jQuery(obj).parent().parent().parent().parent().parent().find('.husband_details').addClass('d-none');
				}
			}
		}
		else {
			window.location.reload();
		}
	}});
}