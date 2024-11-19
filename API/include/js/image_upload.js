jQuery(document).ready(function() {
	if(jQuery('#logo').length > 0) {
		jQuery('#logo').change(function(event) {
			if(jQuery('#logo_cover').find('.alert').length > 0) {
				jQuery('#logo_cover').find('.alert').remove();
			}
			var count = jQuery(this).get(0).files.length;
			if(count != 0) {
				upload_files(this, 'logo', '0');
			}
		});	
	}
	if(jQuery('#profile_photo').length > 0) {
		jQuery('#profile_photo').change(function(event) {
			event.preventDefault();
			if(jQuery('#profile_photo_cover').find('.alert').length > 0) {
				jQuery('#profile_photor_cover').find('.alert').remove();
			}
			var count = jQuery(this).get(0).files.length;
			if(count != 0) {
				var form_data = new Array();
				for(var i = 0; i < count; i++) {
					form_data.push(jQuery(this).get(0).files[i]);
				}
				upload_multiple_files(form_data, 'profile_photo', '0');
				event.target.value = null;
			}
			else {
				return false;
			}
		});	
	}
});

function upload_multiple_files(form_data, field, index) {
	if(parseInt(index) < form_data.length) {
        var file = form_data[index];
		if(typeof file != "undefined" && file != null && file != "") {
			var image_type = file.type;
			var idxDot = file.name.lastIndexOf(".") + 1;
			var extFile = file.name.substr(idxDot, file.name.length).toLowerCase();
			if(extFile=="jpg" || extFile=="jpeg" || extFile=="png" || extFile=="gif") {
				var image_size = file.size;
				if(image_size < 2000000) {
					var fileReader = new FileReader();
					fileReader.readAsDataURL(file);
					fileReader.onload = (function(event) {
						var image = new Image();
						image.src = event.target.result;
						image.onload = function() {
							if(field == "profile_photo") {
								var image_count = 0; var max_count = 3;
								if(jQuery('.multiple_upload_image_list').find('.banner_div').length > 0) {
									image_count = jQuery('.multiple_upload_image_list').find('.banner_div').length;
								}
								//console.log('image_count - '+image_count);
								if(parseInt(image_count) < parseInt(max_count)) {
									//if(this.width == 1500 && this.height == 600) {
										var image_url = event.target.result;
										var request = jQuery.ajax({ url: "image_upload.php", type: "POST", data: {"image_url" : image_url, "image_type" : image_type, "field" : field}});							
										request.done(function(result) {
											var banner_div_id = '<div class="col-sm-6 col-xl-3"><div id="banner_div" class="form-group w-100 px-3 py-3 banner_div">'+result+'</div></div>';
											jQuery('.multiple_upload_image_list').append(banner_div_id);

											setTimeout( function(){ 
												index = parseInt(index) + 1;
												upload_multiple_files(form_data, field, index);
											}, 1000);

										});
									/*}
									else {
										if(jQuery('div.alert').length > 0) {
											jQuery('div.alert').remove();
										}
										jQuery('#'+field+'_cover .cover').before('<div class="alert alert-danger w-100 text-center">Upload image given required size</div>');
									}*/
								}
								else {
									jQuery('#'+field+'_cover .cover').before('<div class="alert alert-danger w-100 text-center">Max.Image Count : '+max_count+'</div>');
								}	
							}
						}
					});
				}
			}
		}
    }
}

function upload_files(obj, field) {
	var fileName = jQuery(obj).get(0).files[0];	
	var image_type = fileName.type;
				
	var idxDot = fileName.name.lastIndexOf(".") + 1;
	var extFile = fileName.name.substr(idxDot, fileName.name.length).toLowerCase();
	if(extFile=="jpg" || extFile=="jpeg" || extFile=="png" || extFile=="gif") {
		var image_size = fileName.size;
		if(image_size < 2000000) {
			var width = ""; var height = "";		
			var reader = new FileReader();				
			reader.readAsDataURL(fileName);					
			reader.onload = function(event) {
				var image = new Image();
				image.src = event.target.result;
				image.onload = function() {
                    if(field == "logo") {
						jQuery("#"+field+"_preview").fadeIn("fast").attr('src',event.target.result);
						var image_url = event.target.result;
						var request = jQuery.ajax({ url: "image_upload.php", type: "POST", data: {"image_url" : image_url, "image_type" : image_type, "field" : field}});							
						request.done(function(result) {
							var msg = result;
							jQuery('#'+field+'_cover .cover').html(msg);
						});						
                    }
				}
			}
		}
		else {
			if(jQuery('div.alert').length > 0) {
				jQuery('div.alert').remove();
			}
			jQuery('#'+field+'_cover .cover').before('<div class="alert alert-danger w-100 text-center">Image size is greater than 2MB</div>');
		}
		
	}
	else {
		if(jQuery('div.alert').length > 0) {
			jQuery('div.alert').remove();
		}
		jQuery('#'+field+'_cover .cover').before('<div class="alert alert-danger w-100 text-center">Please upload only Image</div>');
	}
}

function delete_upload_image_before_save(obj, field, delete_image_file) {
	jQuery(obj).parent().html('<img src="../images/upload_image.png" style="max-width: 150px;" id="'+field+'_preview"/>');
}

function delete_multiple_files(obj, field) {
	if(field == "profile_photo") {
		jQuery(obj).parent().parent().remove();
	}
	else {
		jQuery(obj).parent().remove();
	}
}