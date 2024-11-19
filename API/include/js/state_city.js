function AddCity() {
	if(jQuery('#CityModal .modal-body').find('.infos').length > 0) {
		jQuery('#CityModal .modal-body').find('.infos').remove();
	}
	if(jQuery('#CityModal .modal-body').find('.alert').length > 0) {
		jQuery('#CityModal .modal-body').find('.alert').remove();
	}
    if(jQuery('input[name="city_insert_id"]').length > 0) {
		jQuery('input[name="city_insert_id"]').val('');
	}
	
	var check_login_session = 1;
	var post_url = "dashboard_changes.php?check_login_session=1";	
	jQuery.ajax({url: post_url, success: function(check_login_session){
		if(check_login_session == 1) {
			if(jQuery('input[name="city_name"]').length > 0) {
				jQuery('input[name="city_name"]').val('');
			}
			if(jQuery('.state_name').length > 0) {
				jQuery('.state_name').html('');
			}
			if(jQuery('.country_name').length > 0) {
				jQuery('.country_name').html('');
			}
			if(jQuery('input[name="state_name"]').length > 0) {
				jQuery('input[name="state_name"]').val('');
			}
			if(jQuery('input[name="country_name"]').length > 0) {
				jQuery('input[name="country_name"]').val('');
			}
			if(jQuery('select[name="state"]').length > 0) {
				state_name = jQuery('select[name="state"]').val();
				state_name = jQuery.trim(state_name);
				if(jQuery('.state_name').length > 0) {
					jQuery('.state_name').html(state_name);
				}
				if(jQuery('input[name="state_name"]').length > 0) {
					jQuery('input[name="state_name"]').val(state_name);
				}
			}
			if(jQuery('select[name="country"]').length > 0) {
				country_name = jQuery('select[name="country"]').val();
				country_name = jQuery.trim(country_name);
				if(jQuery('.country_name').length > 0) {
					jQuery('.country_name').html(country_name);
				}
				if(jQuery('input[name="country_name"]').length > 0) {
					jQuery('input[name="country_name"]').val(country_name);
				}
			}
			if(jQuery('.city_modal_button').length > 0) {
				jQuery('.city_modal_button').trigger('click');
			}	
		}
		else {
			window.location.reload();
		}
	}});
}
function SaveCity(obj) {
	var check_login_session = 1;
	var post_url = "dashboard_changes.php?check_login_session=1";	
	jQuery.ajax({url: post_url, success: function(check_login_session){
		if(check_login_session == 1) {
			if(jQuery('#CityModal .modal-body').find('.alert').length > 0) {
				jQuery('#CityModal .modal-body').find('.alert').remove();
			}
            if(jQuery('#CityModal .modal-body').find('.infos').length > 0) {
                jQuery('#CityModal .modal-body').find('.infos').remove();
            }
            if(jQuery('input[name="city_insert_id"]').length > 0) {
                jQuery('input[name="city_insert_id"]').val('');
            }

			jQuery.ajax({
				url: 'state_city.php',
				type: "post",
				async: true,
				data: jQuery('form[name="city_form"]').serialize(),
				dataType: 'html',
				contentType: 'application/x-www-form-urlencoded; charset=UTF-8',
				success: function(result) {
					jQuery('#CityModal .modal-content').animate({ scrollTop: 0 }, 500);
					var intRegex = /^\d+$/;
					if(intRegex.test(result) == true) {
						jQuery('#CityModal .modal-body').append('<div class="alert alert-success"> <button type="button" class="close" data-dismiss="alert">&times;</button> Successfully Updated </div>');
						setTimeout(function(){ 
							jQuery('#CityModal .modal-header .close').trigger("click");

							var country_name = "";
							if(jQuery('select[name="country"]').length > 0) {
								country_name = jQuery('select[name="country"]').val();
								country_name = jQuery.trim(country_name);					
							}
							var state_name = "";
							if(jQuery('select[name="state"]').length > 0) {
								state_name = jQuery('select[name="state"]').val();
								state_name = jQuery.trim(state_name);					
							}
							if(jQuery('input[name="city_insert_id"]').length > 0) {
								jQuery('input[name="city_insert_id"]').val(result);
							}
							ReloadCity(state_name);
							
						}, 1000);
						
					}
					else {
						jQuery('#CityModal .modal-body').append('<span class="infos w-100 text-center" style="font-size: 15px; font-weight: bold;">'+result+'</span>');
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
function ReloadCity(state_name) {
	if(jQuery('#CityModal .modal-body').find('.infos').length > 0) {
		jQuery('#CityModal .modal-body').find('.infos').remove();
	}
	if(jQuery('#CityModal .modal-body').find('.alert').length > 0) {
		jQuery('#CityModal .modal-body').find('.alert').remove();
	}

	var city_insert_id = "";
	if(jQuery('input[name="city_insert_id"]').length > 0) {
		city_insert_id = jQuery('input[name="city_insert_id"]').val();
	}

	var check_login_session = 1;
	var post_url = "dashboard_changes.php?check_login_session=1";	
	jQuery.ajax({url: post_url, success: function(check_login_session){
		if(check_login_session == 1) {
			var post_url = "state_city.php?reload_city_id="+city_insert_id+"&reload_state_name="+state_name;
			jQuery.ajax({url: post_url, success: function(result){
				if(jQuery('#city_list_cover').length > 0) {
					jQuery('#city_list_cover').html(result);
				}
                if(jQuery('input[name="city_insert_id"]').length > 0) {
                    jQuery('input[name="city_insert_id"]').val('');
                }
			}});
		}
		else {
			window.location.reload();
		}
	}});
}
function CancelCity(obj) {
	if(jQuery('#CityModal .modal-body').find('.infos').length > 0) {
		jQuery('#CityModal .modal-body').find('.infos').remove();
	}
	if(jQuery('#CityModal .modal-body').find('.alert').length > 0) {
		jQuery('#CityModal .modal-body').find('.alert').remove();
	}
	if(jQuery('input[name="city_insert_id"]').length > 0) {
		jQuery('input[name="city_insert_id"]').val('');
	}
	var check_login_session = 1;
	var post_url = "dashboard_changes.php?check_login_session=1";	
	jQuery.ajax({url: post_url, success: function(check_login_session){
		if(check_login_session == 1) {
			jQuery('#CityModal .modal-header .close').trigger("click");
		}
		else {
			window.location.reload();
		}
	}}); 
}