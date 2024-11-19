function getFilterCountryChanges(country) {
    var check_login_session = 1;
    var post_url = "dashboard_changes.php?check_login_session=1";	
    jQuery.ajax({url: post_url, success: function(check_login_session){
        if(check_login_session == 1) {	
            var post_url = "member_filter_changes.php?get_filter_state_list_by_country="+country;
            jQuery.ajax({url: post_url, success: function(result){
                if(jQuery('#country_state_cover').length > 0) {
					jQuery('#country_state_cover').html(result);					
				}
                getFilterStateChanges(country, '');
            }});
        }
        else {
            window.location.reload();
        }
    }});
}
function getFilterStateChanges(country, state) {
    var check_login_session = 1;
    var post_url = "dashboard_changes.php?check_login_session=1";	
    jQuery.ajax({url: post_url, success: function(check_login_session){
        if(check_login_session == 1) {	
            var post_url = "member_filter_changes.php?get_filter_city_list_by_country="+country+"&get_filter_city_list_by_state="+state;
            jQuery.ajax({url: post_url, success: function(result){
                if(jQuery('#state_city_cover').length > 0) {
					jQuery('#state_city_cover').html(result);
                    table_listing_records_filter();					
				}
            }});
        }
        else {
            window.location.reload();
        }
    }});
}

function getFilterInitialChanges(initial) {
    var check_login_session = 1;
    var post_url = "dashboard_changes.php?check_login_session=1";	
    jQuery.ajax({url: post_url, success: function(check_login_session){
        if(check_login_session == 1) {	
            var post_url = "member_filter_changes.php?filter_father_by_initial="+initial;
            jQuery.ajax({url: post_url, success: function(result){
                if(jQuery('#initial_father_cover').length > 0) {
					jQuery('#initial_father_cover').html(result);					
				}
                var post_url = "member_filter_changes.php?filter_introducer_by_initial="+initial;
                jQuery.ajax({url: post_url, success: function(result){
                    if(jQuery('#initial_introducer_cover').length > 0) {
                        jQuery('#initial_introducer_cover').html(result);					
                    }
                    table_listing_records_filter();
                }});
            }});
        }
        else {
            window.location.reload();
        }
    }});
}