<?php	
	class validation {

		public function StringToLower($string) {
			if(!empty($string)) {
				$string_array = "";
				$string_array = explode(" ", $string);
				if(is_array($string_array)) {
					for($n = 0; $n < count($string_array); $n++) {
						if(!empty($string_array[$n])) {
							$string_array[$n] = trim($string_array[$n]);
							$string_array[$n] = strtolower($string_array[$n]);
							$string_array[$n] = ucfirst($string_array[$n]);
						}
						else {
							unset($string_array[$n]);
						}
					}
					$string = implode(" ", $string_array);
				}
			}
			return $string;
		}

		public function common_validation($field_value, $field_name, $field_type) {
			$result = "";
			$field_value = trim($field_value);
			if(!empty($field_value)) {
				if(preg_match("/[\"\'\>\<]/", $field_value)) {
					$result = "(&lsquo; &ldquo; > <) not allowed";
				}
			}
			else {
				if($field_type == "select") {
					$result = "Select the ".$field_name;
				}
				else {
					$result = "Enter the ".$field_name;
				}
			}
			return $result;
		}

		public function valid_date($field_value, $field_name, $required) {
			$result = "";
			$field_value = trim($field_value);
			if(!empty($field_value)) {
				$result = $this->common_validation($field_value, $field_name, '');
				if(empty($result)) {
					if(date('d-m-Y', strtotime($field_value)) != $field_value) {
						$result = "Invalid ".$field_name;
					}
				}
			}
			else {
				if($required == 1) {
					$result = "Enter the ".$field_name;
				}
			}
			return $result;
		}
		
		public function valid_datetime($date, $time, $field_name, $required) {
			$result = "";
			$date = trim($date);
			$time = trim($time);
			if(!empty($date) && !empty($time)) {
				$field_value = $date." ".$time.":00";
				if(date('H:i', strtotime($field_value)) != $time) {
					$result = "Invalid ".$field_name;
				}
			}
			else {
				if($required == 1) {
					if(empty($date)) {
						$result = "Select the Date";
					}
					if(empty($time)) {
						$result = "Select the ".$field_name;
					}
				}
			}
			return $result;
		}

		public function valid_name($field_value, $field_name, $required) {
			$result = "";
			$field_value = trim($field_value);
			if(!empty($field_value)) {
				$result = $this->common_validation($field_value, $field_name, '');
				if(empty($result)) {
					if(!preg_match("/^[a-zA-Z\s .]+$/", $field_value)) {
						$result = "Invalid ".$field_name;
					}
				}
			}
			else {
				if($required == 1) {
					$result = "Enter the ".$field_name;
				}
			}
			return $result;
		}

		public function valid_city_pincode($city, $pincode) {
			$result = "";
			$city = trim($city);
			$pincode = trim($pincode);
			if(empty($city)) {
				$result = "Select the city";
			}
			else if(!empty($city) || !empty($pincode)) {
				$result = $this->valid_name($city, 'city', '1');
				if(empty($result) && !empty($pincode)) {
					$result = $this->valid_number($pincode, 'pincode', '1');
				}
			}
			return $result;
		}

		public function valid_landline_number($field_value, $field_name, $required) {
			$result = "";
			$field_value = trim($field_value);
			if(!empty($field_value)) {
				$result = $this->common_validation($field_value, $field_name, '');
				if(empty($result)) {
					if(!preg_match('/^[\+0-9\-\(\)\s]*$/', $field_value)) {
						$result = "Invalid ".$field_name;
					}
				}
			}
			else {
				if($required == 1) {
					$result = "Enter the ".$field_name;
				}
			}
			return $result;
		}

		public function valid_mobile_number($field_value, $field_name, $required) {
			$result = "";
			$field_value = trim($field_value);
			if(!empty($field_value)) {
				$result = $this->common_validation($field_value, $field_name, '');
				if(empty($result)) {
					if(!preg_match('/^[0-9]{10}$/', $field_value) && !preg_match('/^[0-9]{5}(\s)[0-9]{5}$/', $field_value)) {
						$result = "Invalid ".$field_name;
					}
				}
			}
			else {
				if($required == 1) {
					$result = "Enter the ".$field_name;
				}
			}
			return $result;
		}

		public function valid_email($field_value, $field_name, $required) {
			$result = "";
			$field_value = trim($field_value);
			if(!empty($field_value)) {
				$result = $this->common_validation($field_value, $field_name, '');
				if(empty($result)) {
					if(!preg_match('/^([A-Za-z0-9_\-\.])+\@([A-Za-z0-9_\-\.])+\.([A-Za-z]{2,4})$/', $field_value)) {
						$result = "Invalid ".$field_name;
					}
				}
			}
			else {
				if($required == 1) {
					$result = "Enter the ".$field_name;
				}
			}
			return $result;
		}

		public function valid_username($field_value, $field_name, $required) {
			$result = "";
			$field_value = trim($field_value);
			if(!empty($field_value)) {
				$result = $this->common_validation($field_value, $field_name, '');
				if(!empty($result)) {
					if(!preg_match("/^([A-Za-z0-9_\-\.])+\@([A-Za-z0-9_\-\.])+\.([A-Za-z]{2,4})$/", $field_value) && !preg_match("/^\(?([0-9]{3})\)?[-. ]?([0-9]{3})[-. ]?([0-9]{4})$/", $field_value)) {
						$result = "Invalid ".$field_name;
					}
				}
			}
			else {
				if($required == 1) {
					$result = "Enter the ".$field_name;
				}
			}
			return $result;
		}
		
		public function PasswordRequirements($post_name) {
			$password_success = 0; $password_match = 0;
			if(preg_match("/\d/", $post_name)) {
				$password_match++;
			}
			if(preg_match("/[A-Z]/", $post_name)) {
				$password_match++;
			}
			if(preg_match("/[a-z]/", $post_name)) {
				$password_match++;
			}
			if(preg_match('/[\'\/~`\!@#\$%\^&\*\(\)_\-\+=\{\}\[\]\|;:"\<\>,\.\?\\\]/', $post_name)) {
				$password_match++;
			}
			if(preg_match('/^\S+$/', $post_name)) {
				$password_match++;
			}
			if($password_match == 5) {
				$password_success = 1;
			}
			return $password_success;
		}

		public function valid_password($field_value, $field_name, $required) {
			$result = "";
			$field_value = trim($field_value);
			if(!empty($field_value)) {
				$result = $this->common_validation($field_value, $field_name, '');
				if(empty($result)) {
					$result = $this->PasswordRequirements($field_value);
					if($result != 1) {
						$result = "Password not match for required conditions";
					}
					else { $result = ""; }
				}
			}
			else {
				if($required == 1) {
					$result = "Enter the ".$field_name;
				}
			}
			return $result;
		}

		public function valid_aadhaar_number($field_value, $field_name, $required) {
			$result = "";
			$field_value = trim($field_value);
			if(!empty($field_value)) {			
				$result = $this->common_validation($field_value, $field_name, '');
				if(empty($result)) {	
					if(!preg_match('/^\d{4}\s\d{4}\s\d{4}$/', $field_value)) {
						$result = "Invalid ".$field_name;
					}
				}
			}
			else {
				if($required == 1) {
					$result = "Enter the ".$field_name;
				}
			}
			return $result;
		}

		public function valid_driving_license_number($field_value, $field_name, $required) {
			$result = "";
			$field_value = trim($field_value);
			if(!empty($field_value)) {
				$result = $this->common_validation($field_value, $field_name, '');
				if(empty($result)) {
					if(!preg_match('/^([a-zA-Z]){2}\s([0-9]){2}\s([0-9]){11}?$/', $field_value)) {
						$result = "Invalid ".$field_name;
					}
				}
			}
			else {
				if($required == 1) {
					$result = "Enter the ".$field_name;
				}
			}
			return $result;
		}

		public function valid_pancard_number($field_value, $field_name, $required) {
			$result = "";
			$field_value = trim($field_value);
			if(!empty($field_value)) {
				$result = $this->common_validation($field_value, $field_name, '');
				if(empty($result)) {
					if(!preg_match('/^([a-zA-Z]){5}([0-9]){4}([a-zA-Z]){1}?$/', $field_value)) {
						$result = "Invalid ".$field_name;
					}
				}
			}
			else {
				if($required == 1) {
					$result = "Enter the ".$field_name;
				}
			}
			return $result;
		}

		public function valid_voter_id_number($field_value, $field_name, $required) {
			$result = "";
			$field_value = trim($field_value);
			if(!empty($field_value)) {
				$result = $this->common_validation($field_value, $field_name, '');
				if(empty($result)) {
					if(!preg_match('/^([a-zA-Z]){3}([0-9]){7}+$/', $field_value)) {
						$result = "Invalid ".$field_name;
					}
				}
			}
			else {
				if($required == 1) {
					$result = "Enter the ".$field_name;
				}
			}
			return $result;
		}

		public function valid_gst_number($field_value, $field_name, $required) {
			$result = "";
			$field_value = trim($field_value);
			if(!empty($field_value)) {
				$result = $this->common_validation($field_value, $field_name, '');
				if(empty($result)) {
					if(!preg_match("/^[0-9]{2}[A-Z]{5}[0-9]{4}[A-Z]{1}[1-9A-Z]{1}Z[0-9A-Z]{1}$/", $field_value)) {
						$result = "Invalid ".$field_name;
					}
				}
			}
			else {
				if($required == 1) {
					$result = "Enter the ".$field_name;
				}
			}
			return $result;
		}

		public function valid_ifsc_code($field_value, $field_name, $required) {
			$result = "";
			$field_value = trim($field_value);
			if(!empty($field_value)) {
				$result = $this->common_validation($field_value, $field_name, '');
				if(empty($result)) {
					if(!preg_match('/^[A-Za-z]{4}\d{7}$/', $field_value)) {
						$result = "Invalid ".$field_name;
					}
				}
			}
			else {
				if($required == 1) {
					$result = "Enter the ".$field_name;
				}
			}
			return $result;
		}

		public function valid_text($field_value, $field_name, $required) {
			$result = "";
			$field_value = trim($field_value);
			if(!empty($field_value)) {
				$result = $this->common_validation($field_value, $field_name, '');
				if(empty($result)) {
					if(!preg_match("/^[a-zA-Z\s]+$/", $field_value)) {
						$result = "Invalid ".$field_name;
					}
				}
			}
			else {
				if($required == 1) {
					$result = "Enter the ".$field_name;
				}
			}
			return $result;
		}

		public function valid_number($field_value, $field_name, $required) {
			$result = "";
			$field_value = trim($field_value);
			if(!empty($field_value)) {
				$result = $this->common_validation($field_value, $field_name, '');
				if(empty($result)) {
					if(!preg_match("/^[0-9]*$/", $field_value)) {
						$result = "Invalid ".$field_name;
					}
				}
			}
			else {
				if($required == 1) {
					$result = "Enter the ".$field_name;
				}
			}
			return $result;
		}

		public function valid_text_number($field_value, $field_name, $required) {
			$result = "";
			$field_value = trim($field_value);
			if(!empty($field_value)) {
				$result = $this->common_validation($field_value, $field_name, '');
				if(empty($result)) {
					if(!preg_match("/^[a-zA-Z0-9 ]+$/", $field_value)) {
						$result = "Invalid ".$field_name;
					}
				}
			}
			else {
				if($required == 1) {
					$result = "Enter the ".$field_name;
				}
			}
			return $result;
		}

		public function valid_price($field_value, $field_name, $required) {
			$result = "";
			$field_value = trim($field_value);
			if(!empty($field_value)) {
				$result = $this->common_validation($field_value, $field_name, '');
				if(empty($result)) {
					if(!preg_match("/^[0-9]+(\\.[0-9]+)?$/", $field_value)) {
						$result = "Invalid ".$field_name;
					}
				}
			}
			else {
				if($required == 1) {
					$result = "Enter the ".$field_name;
				}
			}
			return $result;
		}

		public function valid_percentage($field_value, $field_name, $required) {
			$result = "";
			$field_value = trim($field_value);
			if(!empty($field_value)) {
				$result = $this->common_validation($field_value, $field_name, '');
				if(empty($result)) {
					$field_value = str_replace("%", "", $field_value);
					if(!preg_match("/^[0-9]+(\\.[0-9]+)?$/", $field_value)) {
						$result = "Invalid ".$field_name;
					}
				}
			}
			else {
				if($required == 1) {
					$result = "Enter the ".$field_name;
				}
			}
			return $result;
		}

		public function valid_time($field_value, $field_name, $required) {
			$result = "";
			$field_value = trim($field_value);
			if(!empty($field_value)) {
				$result = $this->common_validation($field_value, $field_name, '');
				if(empty($result)) {
					if(!preg_match("/^(?:2[0-4]|[01][1-9]|10):([0-5][0-9])$/", $field_value)) {
						$result = "Invalid ".$field_name;
					}
				}
			}
			else {
				if($required == 1) {
					$result = "Enter the ".$field_name;
				}
			}
			return $result;
		}

		public function valid_ip_address($field_value, $field_name, $required) {
			$result = "";
			$field_value = trim($field_value);
			if(!empty($field_value)) {
				$result = $this->common_validation($field_value, $field_name, '');
				if(empty($result)) {
					if(!preg_match("/^\d{1,3}\.\d{1,3}\.\d{1,3}\.\d{1,3}$/", $field_value)) {
						$result = "Invalid ".$field_name;
					}
				}
			}
			else {
				if($required == 1) {
					$result = "Enter the ".$field_name;
				}
			}
			return $result;
		}		
		
		public function error_display($form_name, $field, $error, $type) {
			$result = "";
			if(!empty($error)) {
				if($type == "text" || $type == "form_check" || $type == "form_radio") {
					$result = "if(jQuery('form[name=".'"'.$form_name.'"'."] input[name=".'"'.$field.'"'."]').parent().parent().find('span.infos').length == 0) {
									jQuery('form[name=".'"'.$form_name.'"'."] input[name=".'"'.$field.'"'."]').parent().after('<span class=".'"infos"'."> <i class=".'"fa fa-exclamation-circle"'."></i> &nbsp; ".$error."</span>');
								}";
				}
				if($type == "radio") {
					$result = "if(jQuery('form[name=".'"'.$form_name.'"'."] input[name=".'"'.$field.'"'."]').parent().parent().parent().find('span.infos').length == 0) {
									jQuery('form[name=".'"'.$form_name.'"'."] input[name=".'"'.$field.'"'."]').parent().parent().after('<span class=".'"infos w-100"'."> <i class=".'"fa fa-exclamation-circle"'."></i> &nbsp; ".$error."</span>');
								}";
				}
				if($type == "textarea") {
					$result = "if(jQuery('form[name=".'"'.$form_name.'"'."] textarea[name=".'"'.$field.'"'."]').parent().parent().find('span.infos').length == 0) {
									jQuery('form[name=".'"'.$form_name.'"'."] textarea[name=".'"'.$field.'"'."]').parent().after('<span class=".'"infos"'."> <i class=".'"fa fa-exclamation-circle"'."></i> &nbsp; ".$error."</span>');
								}";
				}
				if($type == "input_group") {
					if($field == "pincode") {
						$result = "if(jQuery('form[name=".'"'.$form_name.'"'."] input[name=".'"'.$field.'"'."]').parent().parent().parent().find('span.infos').length == 0) {
									jQuery('form[name=".'"'.$form_name.'"'."] input[name=".'"'.$field.'"'."]').parent().parent().parent().after('<span class=".'"infos"'."> <i class=".'"fa fa-exclamation-circle"'."></i> &nbsp; ".$error."</span>');
								}
								else {
									jQuery('form[name=".'"'.$form_name.'"'."] input[name=".'"'.$field.'"'."]').parent().parent().parent().after('<span class=".'"infos"'."> <i class=".'"fa fa-exclamation-circle"'."></i> &nbsp; ".$error."</span>');
								}";
					}
					else {
						$result = "if(jQuery('form[name=".'"'.$form_name.'"'."] input[name=".'"'.$field.'"'."]').parent().parent().parent().find('span.infos').length == 0) {
									jQuery('form[name=".'"'.$form_name.'"'."] input[name=".'"'.$field.'"'."]').parent().parent().after('<span class=".'"infos"'."> <i class=".'"fa fa-exclamation-circle"'."></i> &nbsp; ".$error."</span>');
								}";
					}			
				}
				if($type == "input_group_array") {
					$result = "if(jQuery('form[name=".'"'.$form_name.'"'."] input[name=".'"'.$field.'"'."]').parent().parent().parent().find('span.infos').length == 0) {
									jQuery('form[name=".'"'.$form_name.'"'."] input[name=".'"'.$field.'"'."]').parent().parent().parent().append('<span class=".'"infos"'."> <i class=".'"fa fa-exclamation-circle"'."></i> &nbsp; ".$error."</span>');
								}";
				}
				if($type == "select") {
					$result = "if(jQuery('form[name=".'"'.$form_name.'"'."] select[name=".'"'.$field.'"'."]').parent().parent().find('span.infos').length == 0) {
									jQuery('form[name=".'"'.$form_name.'"'."] select[name=".'"'.$field.'"'."]').parent().parent().append('<span class=".'"infos"'."> <i class=".'"fa fa-exclamation-circle"'."></i> &nbsp; ".$error."</span>');
								}";
				}
				if($type == "custom_radio") {
					$result = "if(jQuery('form[name=".'"'.$form_name.'"'."] input[name=".'"'.$field.'"'."]').parent().parent().parent().parent().find('span.infos').length == 0) {
									jQuery('form[name=".'"'.$form_name.'"'."] input[name=".'"'.$field.'"'."]').parent().parent().parent().after('<span class=".'"infos"'."> <i class=".'"fa fa-exclamation-circle"'."></i> &nbsp; ".$error."</span>');
								}";
				}
				if($type == "upload_modal_button") {
					$result = "if(jQuery('form[name=".'"'.$form_name.'"'."] button[name=".'"'.$field.'"'."]').parent().parent().find('span.infos').length == 0) {
									jQuery('form[name=".'"'.$form_name.'"'."] button[name=".'"'.$field.'"'."]').parent().after('<span class=".'"w-100 infos"'."> <i class=".'"fa fa-exclamation-circle"'."></i> &nbsp; ".$error."</span>');
								}";
				}
				if($type == "on_off_checkbox") {
					$result = "if(jQuery('form[name=".'"'.$form_name.'"'."] input[name=".'"'.$field.'"'."]').parent().parent().parent().find('span.infos').length == 0) {
									jQuery('form[name=".'"'.$form_name.'"'."] input[name=".'"'.$field.'"'."]').parent().parent().after('<span class=".'"infos"'."> <i class=".'"fa fa-exclamation-circle"'."></i> &nbsp; ".$error."</span>');
								}";
				}
			}
			
			return $result;
		}
		
	}

?>
