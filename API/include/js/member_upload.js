function UploadMemberExcel() {
    var check_login_session = 1;
    var post_url = "dashboard_changes.php?check_login_session=1";	
    jQuery.ajax({url: post_url, success: function(check_login_session){
        if(check_login_session == 1) {	
            var post_url = "member_upload.php?upload_member_excel=1";
            jQuery.ajax({url: post_url, success: function(result){
                if(jQuery('.add_update_form_content').length > 0) {
                    jQuery('.add_update_form_content').html("");
                    jQuery('.add_update_form_content').html(result);
                }
                if(jQuery('input[name="member_upload"]').length > 0) {
                    jQuery('input[name="member_upload"]').trigger("click");
                }
            }});
        }
        else {
            window.location.reload();
        }
    }});
}

function UpdateMemberList(obj, form_name) {
    var check_login_session = 1;
    var post_url = "dashboard_changes.php?check_login_session=1";	
    jQuery.ajax({url: post_url, success: function(check_login_session){
        if(check_login_session == 1) {	
            if(jQuery('div.alert').length > 0) {
                jQuery('div.alert').remove();
            }
            jQuery('form[name="'+form_name+'"]').find('.row:first').before('<div class="alert alert-danger mb-3"> <button type="button" class="close" data-dismiss="alert">&times;</button> Processing </div>');
            var count = jQuery(obj).get(0).files.length;
            //console.log('count - '+count);
            if(parseInt(count) > 0) {
                var fileName = jQuery(obj).get(0).files[0];	
                var idxDot = fileName.name.lastIndexOf(".") + 1;
                var extFile = fileName.name.substr(idxDot, fileName.name.length).toLowerCase();
                //console.log('extFile - '+extFile);
                if(extFile == "xlsx" || extFile == "xls") {					
                    var reader = new FileReader();				
                    reader.readAsArrayBuffer(fileName);					
                    reader.onload = function(event) {
                        var data = new Uint8Array(event.target.result);	
                        //console.log(data);
                        var workbook = "";
                        if(extFile == "xlsx") {
                            workbook = XLSX.read(data, { type: 'array' });
                        }
                        else if(extFile == "xls") {
                            workbook = XLS.read(data, { type: 'array' });
                        }
                       // console.log(workbook);
                        //var workbook = XLSX.read(data, {type: 'array'});
                        var sheet_name_list = workbook.SheetNames;
                        //console.log(sheet_name_list);
                        var s = 0;
                        sheet_name_list.forEach(function (y) {
                            var Sheet = workbook.Sheets[workbook.SheetNames[s]];
                            var result = XLSX.utils.sheet_to_json(Sheet, { header: 1 });
                            
                            var pages = JSON.stringify(result);
                            //console.log(pages);
                            var data_array = $.parseJSON(pages);
                            //console.log(data_array);
                            var category_index = 0; var product_index = 0; 
                            var each_product = new Array(); var product_list = new Array(); var category_product_list = new Array();                      
                            if(data_array.length > 0 && data_array != '' && typeof data_array != 'undefined') {
                                if(jQuery('.upload_member_details_table tbody').find('tr').length > 0) {
                                    jQuery('.upload_member_details_table tbody').find('tr').each(function(){
                                        jQuery(this).remove();
                                    });
                                }
                                getUploadlistRow('1', data_array);
                            }
                        });    
                        s = parseInt(s) + 1;
                        if(jQuery('div.alert').length > 0) {
                            jQuery('div.alert').remove();
                        }
                        jQuery('html, body').animate({
                            scrollTop: (jQuery('.submit_button').parent().offset().top)
                        }, 500);
                        if(jQuery('.submit_button').length > 0) {
                            jQuery('.submit_button').attr('disabled', false);
                        }
                    }; 
                }
                else {
                    jQuery('form[name="'+form_name+'"]').find('.row:first').before('<div class="alert alert-danger"> <button type="button" class="close" data-dismiss="alert">&times;</button> Upload excel files only </div>');
                    if(jQuery('.submit_button').length > 0) {
                        jQuery('.submit_button').attr('disabled', true);
                    }
                }
            }
        }
        else {
            window.location.reload();
        }
    }});
}

function clear_values(string) {
    if(typeof string != "undfined" && string != null && string != "") {
        if(string.indexOf("&") != -1) {
            string = string.replaceAll("&", "AND");
        }
        if(string.indexOf("'") != -1) {
            string = string.replaceAll("'", "$");
        }
        if(string.indexOf('"') != -1) {
            string = string.replace('"', "$");
        }
    }
    return string;
}

function getUploadlistRow(row_index, data_array) {
    var row_values = data_array[row_index];
    //console.log('row_index - '+row_index);
    if(row_values.length > 0 && row_values != '' && typeof row_values != 'undefined') {
        //console.log(row_values.length+' / '+row_values);

        var upload_member_details = new Array();

        for(var i = 0; i < 22; i++) {
            var value = "NULL";
            row_values[i] = jQuery.trim(row_values[i]);
            if(typeof row_values[i] != "undfined" && row_values[i] != null && row_values[i] != "") {
                value = jQuery.trim(clear_values(row_values[i]));
            }
            upload_member_details.push(value);
        }

        if(upload_member_details.length > 0) {
            upload_member_details = upload_member_details.join("@@@");
        }

        //console.log(upload_member_details);        

        var post_url = "member_upload.php?upload_member_details="+upload_member_details;
        jQuery.ajax({url: post_url, success: function(result){
            if(jQuery('.upload_member_details_table').length > 0) {
                jQuery('.upload_member_details_table').find('tbody').append(result);
                setTimeout(function(){ 
                    row_index = parseInt(row_index) + 1;
                    getUploadlistRow(row_index, data_array);
                }, 500);
            }
        }});
    }
}

var numbers_regex = /^\d+$/;
var price_regex = /^(\d*\.)?\d+$/;

function UpdateUploadMemberList(form_name) {
    if(jQuery('div.alert').length > 0) {
        jQuery('div.alert').remove();
    }
    if(jQuery('span.infos').length > 0) {
        jQuery('span.infos').remove();
    }
    if(jQuery('.submit_button').length > 0) {
        jQuery('.submit_button').attr('disabled', true);
    }
    jQuery('html, body').animate({
        scrollTop: (jQuery('.add_update_form_content').parent().parent().offset().top)
    }, 500);
    jQuery('form[name="'+form_name+'"]').find('.row:first').before('<div class="alert alert-danger mb-3"> <button type="button" class="close" data-dismiss="alert">&times;</button> Processing </div>');

    var check_login_session = 1;
    var post_url = "dashboard_changes.php?check_login_session=1";	
    jQuery.ajax({url: post_url, success: function(check_login_session){
        if(check_login_session == 1) {
            var count = 0;
            if(jQuery('.upload_member_details_table tbody').find('tr').length > 0) {
                count = jQuery('.upload_member_details_table tbody').find('tr').length;
                if(jQuery('.upload_total_count').length > 0) {
                    jQuery('.upload_total_count').html(count);
                }
            }	
            setTimeout(function(){ 
                start_upload_row(form_name, '1');
            }, 500);
        }
        else {
            window.location.reload();
        }
    }});
}

function start_upload_row(form_name, row_index) {
    var check_login_session = 1;
    var post_url = "dashboard_changes.php?check_login_session=1";	
    jQuery.ajax({url: post_url, success: function(check_login_session){
        if(check_login_session == 1) {	
            if(numbers_regex.test(row_index) == true) {
                if(jQuery('.upload_member_details_table tbody').find('tr:nth-child('+row_index+')').length > 0) {

                    if(row_index != 1) {
                        var pos = "";
                        pos = parseInt(row_index) - 1;
                        jQuery('html, body').animate({
                            scrollTop: (jQuery('.upload_member_details_table tbody').find('tr:nth-child('+pos+')').offset().top)
                        }, 500);
                    }

                    var upload_row = "";
                    upload_row = jQuery('.upload_member_details_table tbody').find('tr:nth-child('+row_index+')');
                    if(jQuery(upload_row).find('input').length > 0) {
                        var form_data = new Array();
                        jQuery(upload_row).find('input').each(function(){
                            var field_name = ""; var field_value = "";
                            field_name = jQuery(this).attr('name');
                            field_value = jQuery(this).attr('value');
                            if(typeof field_name != "undefined" && field_name != null && field_name != "") {
                                form_data.push(field_name+'@@@'+field_value);
                            }
                        });
                        //console.log(form_data);
                        if(typeof form_data != "undefined" && form_data != null && form_data != "") {
                            form_data = JSON.stringify(form_data);
                            jQuery(upload_row).find('.status').html('<i class="fa fa-spinner fa-spin" style="color: blue; font-size: 15px; line-height: 15px;"></i>');
                            jQuery.post( "member_upload.php", { post_data:form_data })
                            .done(function( result ) {
                                if(numbers_regex.test(result) == true) {
                                    jQuery(upload_row).find('.status').html('<i class="fa fa-check" style="color: green; font-size: 15px; line-height: 15px;"></i>');
                                    setTimeout(function(){ 
                                        if(jQuery('.member_upload_details').length > 0) {
                                            jQuery('.member_upload_details').css({"display" : "block"});
                                            if(jQuery('.upload_count').length > 0) {
                                                jQuery('.upload_count').html(row_index);
                                            }
                                        }
                                        row_index = parseInt(row_index) + 1;
                                        start_upload_row(form_name, row_index);
                                    }, 500);
                                    
                                }
                                else {
                                    jQuery(upload_row).find('.status').html('<span class="infos"><i class="fa fa-close"></i> '+result+'</span>');
                                }
                            });
                        }
                    }              
                }
                else {
                    jQuery('html, body').animate({
                        scrollTop: (jQuery('.add_update_form_content').parent().parent().offset().top)
                    }, 500);
                    if(jQuery('div.alert').length > 0) {
                        jQuery('div.alert').remove();
                    }
                    jQuery('form[name="'+form_name+'"]').find('.row:first').before('<div class="alert alert-success"> <button type="button" class="close" data-dismiss="alert">&times;</button> Updated Successfully </div>');
                    setTimeout(function(){ window.location.reload(); }, 1000);
                }
            }
        }
        else {
            window.location.reload();
        }
    }});   
}