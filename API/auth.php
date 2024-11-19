<?php
include 'include.php';

// Header This Application is a JSON APPLICATION
header('Content-Type: application/json; charset=utf-8');

// get Post Form Data on API
$json = file_get_contents('php://input');

// Decode JSON Format
$json_obj = json_decode($json);

// Output Result Array Variable
$output = array();

// Check User Id and Password is isset on API Parameter
if (isset($json_obj->member_login_id) || isset($json_obj->verify_otp_number)) {
    if ($json_obj->member_login_id != $GLOBALS['null_value'] && $json_obj->member_login_id != "") {
        $member_login_id = "";
        $member_login_id_error = "";
        $password = "";
        $password_error = "";
        $member_id = "";
        $member_password = "";
        $otp_verification = 0;

        $valid_member = "";
        $form_name = "frontend_login_form";

        $otp_number = "";
        if (isset($json_obj->otp_number)) {
            $otp_number = $json_obj->otp_number;
            $otp_number = trim($otp_number);
        }
        if (empty($otp_number)) {
            $otp_number = $GLOBALS['null_value'];
        }

        $member_login_id = $json_obj->member_login_id;
        $member_login_id = trim($member_login_id);

        $password = $json_obj->password;
        $password = trim($password);

        $company_list = array();
        $company_name = "";
        $app_expiry_date = "";
        $playstore_link = "";
        $message = "";
        $company_list = $obj->getTableRecords($GLOBALS['company_table'], '', '', '');
        if (!empty($company_list)) {
            foreach ($company_list as $data) {
                if (!empty($data['app_expiry_date']) && $data['app_expiry_date'] != '0000-00-00') {
                    $app_expiry_date = $data['app_expiry_date'];
                }
                if (!empty($data['playstore_link'])) {
                    $playstore_link = $data['playstore_link'];
                }
                if (!empty($data['message'])) {
                    $message = $data['message'];
                }
            }
        }

        $admin_login = 0;
        $admin_member_login_id = "";
        if (!empty($member_login_id)) {
            $admin_member_login_id = $obj->encode_decode('encrypt', $member_login_id);
            $admin_unique_id = "";
            $admin_unique_id = $obj->getTableColumnValue($GLOBALS['user_table'], 'mobile_number', $admin_member_login_id, 'id');
            if (preg_match("/^\d+$/", $admin_unique_id)) {
                $admin_login = 1;
            } else if (empty($admin_unique_id)) {
                $member_unique_id = "";
                $member_unique_id = $obj->getTableColumnValue($GLOBALS['member_table'], 'member_unique_number', $member_login_id, 'id');
                if (preg_match("/^\d+$/", $member_unique_id)) {
                    $member_id = $obj->getTableColumnValue($GLOBALS['member_table'], 'member_unique_number', $member_login_id, 'member_id');
                    $mobile_number = $obj->getTableColumnValue($GLOBALS['member_table'], 'member_unique_number', $member_login_id, 'mobile_number');
                    if (!empty($mobile_number)) {
                        $mobile_number = str_replace(" ", "", $mobile_number);
                    }
                    $mobile_number_error = $valid->valid_mobile_number($mobile_number, "mobile number", "1");
                    if (empty($mobile_number_error)) {
                        $otp_verification = 1;
                    }
                } else {
                    $member_unique_id = $obj->getTableColumnValue($GLOBALS['member_table'], 'mobile_number_without_space', $member_login_id, 'id');
                    if (preg_match("/^\d+$/", $member_unique_id)) {
                        $mobile_number = $member_login_id;
                        if (!empty($mobile_number)) {
                            $mobile_number = str_replace(" ", "", $mobile_number);
                        }
                        $otp_verification = 1;
                        $member_id = $obj->getTableColumnValue($GLOBALS['member_table'], 'mobile_number', $member_login_id, 'member_id');
                    }
                }
                if (!empty($member_id)) {
                    $member_password = $obj->getTableColumnValue($GLOBALS['member_table'], 'member_id', $member_id, 'password');
                    if (empty($member_password)) {
                        $member_password = "Temple123@";
                    }

                    $unique_id = "";
                    $settings_column_name = $member_id . "_login_duration";
                    $unique_id = $obj->getTableColumnValue($GLOBALS['settings_table'], 'name', $settings_column_name, 'id');
                    if (preg_match("/^\d+$/", $unique_id)) {
                        /*$login_duration = "";
						$login_duration = $obj->getTableColumnValue($GLOBALS['settings_table'], 'name', $settings_column_name, 'value');
						if(!empty($login_duration)) {
							$login_duration = explode("$$$", $login_duration);
							$login_from_date = ""; $login_to_date = "";
							if(!empty($login_duration['0'])) {
								$login_from_date = $login_duration['0'];
							}
							if(!empty($login_duration['1'])) {
								$login_to_date = $login_duration['1'];
							}
							if(!empty($login_from_date) && !empty($login_to_date)) {
								$current_date = date("d-m-Y");
								$allow_login = 0;
								if( (strtotime($current_date) >= strtotime($login_from_date)) && (strtotime($current_date) <= strtotime($login_to_date)) ) {
									$allow_login = 1;
								}
								else {
									$member_login_id_error = "Exceed login duration";
								}
							}
						}*/
                    }
                }
            } else {
                $member_login_id_error = "Invalid Login";
            }
        } else {
            $member_login_id_error = "Enter your member ID or Mobile Number";
        }
        if (!empty($member_login_id_error)) {
            $valid_member = $member_login_id_error;
        }

        $member_name = "";
        $profile_photo = "";
        $profile_photo_name = "";
        if (!empty($member_id)) {
            $member_name = $obj->getTableColumnValue($GLOBALS['member_table'], 'member_id', $member_id, 'name');
            $profile_photo = $obj->getTableColumnValue($GLOBALS['member_table'], 'member_id', $member_id, 'profile_photo');
        }

        if (!empty($member_photo_location)) {
            $member_photo_location = $member_photo_location . $member_login_id . "/";
        }

        // if(!empty($profile_photo)){
        //     $profile_photo = explode(",", $profile_photo);
        //     foreach($profile_photo as $key => $photo) {
        //         $position = $key + 1;
        //         if(!empty($photo) && file_exists($member_photo_location.$photo) && $position == 1) {
        //             $profile_photo_name = $member_photo_location.$photo;
        //         }
        //     }
        // }

        if (!empty($password)) {
            if ($password != $member_password) {
                $password_error = "Invalid password";
            }
        } else {
            $password_error = "Enter the password";
        }

        if (!empty($password_error)) {
            $valid_member = $password_error;
        }

        $fcm_id = "";
        if (isset($json_obj->fcm_id)) {
            $fcm_id = $json_obj->fcm_id;
            $fcm_id = trim($fcm_id);
        }

        $result = "";

        if (empty($valid_member)) {

            // $otp_verification = 0;

            if (!empty($otp_verification) && $otp_verification == 1) {
                $otp_number = "";
                $otp_number = $obj->getOTPNumber();

                $created_date_time = $GLOBALS['create_date_time_label'];

                $otp_send_count = 1;
                $otp_send_count_error = "Try again after 30 mins";
                if (!empty($mobile_number)) {
                    $mobile_number = str_replace(" ", "", $mobile_number);
                    $otp_send_count = $obj->getOTPSendCount($created_date_time, $mobile_number);
                    if (!empty($otp_send_count) && $otp_send_count >= 10) {
                        $sms_send_date_time = "";
                        $sms_send_date_time = $obj->getTableColumnValue($GLOBALS['otp_send_phone_numbers_table'], 'phone_number', $mobile_number, 'send_date_time');
                        if (!empty($sms_send_date_time)) {
                            $created_date_time = $GLOBALS['create_date_time_label'];
                            if (!empty($created_date_time)) {
                                $minutes = 0;
                                $minutes = (strtotime($created_date_time) - strtotime($sms_send_date_time)) / 60;
                                if (!empty($minutes)) {
                                    $minutes = $obj->truncate_number($minutes);
                                    if ($minutes > 30) {
                                        $otp_send_count = 0;
                                    }
                                }
                            }
                        }
                    }
                    if (!empty($otp_send_count) && $otp_send_count >= 4) {
                        $output["head"]["code"] = 400;
                        $output["head"]["msg"] = $otp_send_count_error;
                    } else {
                        if (!empty($otp_number)) {
                            $company_list = array();
                            $company_name = "";
                            $company_list = $obj->getTableRecords($GLOBALS['company_table'], '', '', '');
                            if (!empty($company_list)) {
                                foreach ($company_list as $data) {
                                    if (!empty($data['name'])) {
                                        $company_name = $obj->encode_decode('decrypt', $data['name']);
                                    }
                                }
                            }
                            $otp_sms = "";
                            if (!empty($company_name)) {
                                $otp_sms = $otp_number . "|" . $company_name;
                            }
                            if (!empty($otp_sms)) {
                                $otp_insert_id = "";
                                $columns = array('send_date_time', 'phone_number', 'otp_number', 'otp_send_count', 'deleted');
                                $values = array("'" . $created_date_time . "'", "'" . $mobile_number . "'", "'" . $otp_number . "'", "'1'", "'0'");
                                $otp_insert_id = $obj->InsertSQL($GLOBALS['otp_send_phone_numbers_table'], $columns, $values, '', '', '');
                                if (preg_match("/^\d+$/", $otp_insert_id)) {

                                    $member_unique_id = "";
                                    $member_unique_id = $obj->getTableColumnValue($GLOBALS['member_table'], 'member_unique_number', $member_login_id, 'id');
                                    if (preg_match("/^\d+$/", $member_unique_id)) {
                                        if (empty($fcm_id)) {
                                            $fcm_id = $GLOBALS['null_value'];
                                        }

                                        $action = "";
                                        if (!empty($member_login_id)) {
                                            $action = "Member FCM ID Updated. Member ID - " . $member_login_id . ", FCM ID - " . $fcm_id;
                                        }

                                        $columns = array();
                                        $values = array();
                                        $columns = array('fcm_id');
                                        $values = array("'" . $fcm_id . "'");
                                        $update_id = $obj->UpdateSQL($GLOBALS['member_table'], $member_unique_id, $columns, $values, $action);
                                    }

                                    //$result = array('number' => '1', 'msg' => 'Please verify with OTP number', 'otp_send_date_time' => date("Y-m-d", $created_date_time), 'otp_receive_mobile_number' => $mobile_number, 'otp_number' => $otp_number);
                                    // $result = array('number' => '1', 'msg' => 'Please verify with OTP number', 'otp_send_date' => date("Y-m-d", strtotime($created_date_time)));

                                    $sms_response = $obj->send_mobile_details($mobile_number, '131030', $otp_sms);

                                    $output["head"]["code"] = 200;
                                    $output["head"]["msg"] = 'Login Successfully';
                                    $output["head"]["mobile_number"] = $mobile_number;
                                    $output['head']['member_id'] = $member_id;
                                    $output['head']['member_name'] = $member_name;
                                    $output['head']['app_expiry_date'] = $app_expiry_date;
                                    $output['head']['playstore_link'] = $playstore_link;
                                    $output['head']['message'] = $message;
                                    $output["head"]["otp_number"] = $otp_number;
                                    if (!empty($profile_photo) && $profile_photo != $GLOBALS['null_value']) {
                                        $output["head"]["profile_photo"] = 'http://sridemoapps.in/mahendran2022/temple/' . str_replace("../", "", $member_photo_location) . $profile_photo;
                                    } else {
                                        $output["head"]["profile_photo"] = "";
                                    }
                                    $output["head"]["otp"] = true;

                                    $show_receipt_page = false;
                                    $member_volunteer_data_list = array();
                                    if (!empty($member_id)) {
                                        $member_volunteer_data_list = $obj->CheckIsMemberVolunteer($member_id);
                                        if (!empty($member_volunteer_data_list)) {
                                            foreach ($member_volunteer_data_list as $data) {
                                                if (!empty($data['access_pages'])) {
                                                    $selected_access_pages = "";
                                                    $selected_access_pages = explode(",", $data['access_pages']);
                                                    if (!empty($selected_access_pages)) {
                                                        foreach ($selected_access_pages as $access_page) {
                                                            if (!empty($access_page)) {
                                                                $check_receipt_type_unique_id = "";
                                                                $check_receipt_type_unique_id = $obj->getTableColumnValue($GLOBALS['receipt_type_table'], 'name', $access_page, 'id');
                                                                if (preg_match("/^\d+$/", $check_receipt_type_unique_id)) {
                                                                    $show_receipt_page = true;
                                                                }
                                                            }
                                                        }
                                                    }
                                                }
                                            }
                                        }
                                    }

                                    $output["head"]["receipt_volunteer"] = $show_receipt_page;
                                } else {
                                    $output["head"]["code"] = 400;
                                    $output["head"]["msg"] = 'Unable to add OTP details';
                                }
                            }
                        }
                    }
                }
            } else {
                if ((!empty($admin_login) && $admin_login == 1) && !empty($admin_member_login_id)) {
                    $admin_unique_id = "";
                    $admin_unique_id = $obj->getTableColumnValue($GLOBALS['user_table'], 'mobile_number', $admin_member_login_id, 'id');
                    if (preg_match("/^\d+$/", $admin_unique_id)) {
                        $check_users = array();
                        $check_users = $obj->getTableRecords($GLOBALS['user_table'], 'id', $admin_unique_id, '');
                        if (!empty($check_users)) {
                            foreach ($check_users as $data) {
                                $_SESSION[$GLOBALS['site_name_user_prefix'] . '_user_id'] = $data['user_id'];
                                $_SESSION[$GLOBALS['site_name_user_prefix'] . '_user_name'] = $obj->encode_decode('decrypt', $data['name']);
                                $_SESSION[$GLOBALS['site_name_user_prefix'] . '_user_type'] = $GLOBALS['admin_user_type'];
                            }
                        }
                        if (!empty($_SESSION[$GLOBALS['site_name_user_prefix'] . '_user_id']) && isset($_SESSION[$GLOBALS['site_name_user_prefix'] . '_user_name'])) {
                            $create_date_time = $GLOBALS['create_date_time_label'];
                            $ip_address = $_SERVER['REMOTE_ADDR'];
                            $browser = $_SERVER['HTTP_USER_AGENT'];
                            $os_detail = php_uname();

                            $action = "";
                            if (!empty($member_login_id)) {
                                $action = "Admin Login. Login ID - " . $member_login_id . ", IP Address - " . $ip_address;
                            }

                            $loginer_name = $obj->encode_decode('encrypt', $_SESSION[$GLOBALS['site_name_user_prefix'] . '_user_name']);

                            $columns = array('loginer_name', 'login_date_time', 'logout_date_time', 'ip_address', 'browser', 'os_detail', 'type', 'user_id', 'deleted');
                            $values = array("'" . $loginer_name . "'", "'" . $create_date_time . "'", "'0000-00-00 00:00:00'", "'" . $ip_address . "'", "'" . $browser . "'", "'" . $os_detail . "'", "'" . $_SESSION[$GLOBALS['site_name_user_prefix'] . '_user_type'] . "'", "'" . $_SESSION[$GLOBALS['site_name_user_prefix'] . '_user_id'] . "'", "'0'");
                            $user_login_record_id = $obj->InsertSQL($GLOBALS['login_table'], $columns, $values, '', '', $action);
                            if (preg_match("/^\d+$/", $user_login_record_id)) {
                                $_SESSION[$GLOBALS['site_name_user_prefix'] . '_user_login_record_id'] = $user_login_record_id;
                                $_SESSION[$GLOBALS['site_name_user_prefix'] . '_user_ip_address'] = $ip_address;

                                if (!empty($_SESSION[$GLOBALS['site_name_user_prefix'] . '_user_ip_address']) && isset($_SESSION[$GLOBALS['site_name_user_prefix'] . '_user_ip_address'])) {
                                    $output["head"]["code"] = 200;
                                    $output["head"]["msg"] = 'Login Successfully';
                                    $output["head"]["mobile_number"] = '';
                                    $output['head']['member_id'] = $data['user_id'];
                                    $output['head']['name'] = $obj->encode_decode('decrypt', $data['name']);
                                    $output['head']['app_expiry_date'] = $app_expiry_date;
                                    $output['head']['playstore_link'] = $playstore_link;
                                    $output['head']['message'] = $message;
                                    if (!empty($profile_photo) && $profile_photo != $GLOBALS['null_value']) {
                                        $output["head"]["profile_photo"] = 'http://sridemoapps.in/mahendran2022/temple/' . str_replace("../", "", $member_photo_location) . $profile_photo;
                                    } else {
                                        $output["head"]["profile_photo"] = "";
                                    }
                                    $output["head"]["otp_number"] = '';
                                    $output["head"]["otp"] = false;

                                    $show_receipt_page = false;
                                    $member_volunteer_data_list = array();
                                    if (!empty($member_id)) {
                                        $member_volunteer_data_list = $obj->CheckIsMemberVolunteer($member_id);
                                        if (!empty($member_volunteer_data_list)) {
                                            foreach ($member_volunteer_data_list as $data) {
                                                if (!empty($data['access_pages'])) {
                                                    $selected_access_pages = "";
                                                    $selected_access_pages = explode(",", $data['access_pages']);
                                                    if (!empty($selected_access_pages)) {
                                                        foreach ($selected_access_pages as $access_page) {
                                                            if (!empty($access_page)) {
                                                                $check_receipt_type_unique_id = "";
                                                                $check_receipt_type_unique_id = $obj->getTableColumnValue($GLOBALS['receipt_type_table'], 'name', $access_page, 'id');
                                                                if (preg_match("/^\d+$/", $check_receipt_type_unique_id)) {
                                                                    $show_receipt_page = true;
                                                                }
                                                            }
                                                        }
                                                    }
                                                }
                                            }
                                        }
                                    }

                                    $output["head"]["receipt_volunteer"] = $show_receipt_page;
                                }
                            } else {
                                $output["head"]["code"] = 400;
                                $output["head"]["msg"] = $user_login_record_id;
                            }
                        }
                    }
                } else if (!empty($member_id)) {
                    $member_unique_id = "";
                    $member_unique_id = $obj->getTableColumnValue($GLOBALS['member_table'], 'member_id', $member_id, 'id');
                    if (preg_match("/^\d+$/", $member_unique_id)) {
                        $check_members = array();
                        $check_members = $obj->getTableRecords($GLOBALS['member_table'], 'member_id', $member_id, '');
                        if (!empty($check_members)) {
                            foreach ($check_members as $data) {
                                $_SESSION[$GLOBALS['site_name_user_prefix'] . '_user_id'] = $data['member_id'];
                                $_SESSION[$GLOBALS['site_name_user_prefix'] . '_user_name'] = $data['name'];
                                $_SESSION[$GLOBALS['site_name_user_prefix'] . '_user_type'] = $GLOBALS['member_user_type'];
                                $_SESSION[$GLOBALS['site_name_user_prefix'] . '_user_status_base_member_id'] = $data['status_base_member_id'];
                            }
                        }
                        if (!empty($_SESSION[$GLOBALS['site_name_user_prefix'] . '_user_id']) && isset($_SESSION[$GLOBALS['site_name_user_prefix'] . '_user_name'])) {
                            $create_date_time = $GLOBALS['create_date_time_label'];
                            $ip_address = $_SERVER['REMOTE_ADDR'];
                            $browser = $_SERVER['HTTP_USER_AGENT'];
                            $os_detail = php_uname();

                            $action = "";
                            if (!empty($member_login_id)) {
                                $action = "Member Login. Login ID - " . $member_login_id . ", IP Address - " . $ip_address;
                            }

                            $loginer_name = $obj->encode_decode('encrypt', $_SESSION[$GLOBALS['site_name_user_prefix'] . '_user_name']);

                            $columns = array('loginer_name', 'login_date_time', 'logout_date_time', 'ip_address', 'browser', 'os_detail', 'type', 'user_id', 'deleted');
                            $values = array("'" . $loginer_name . "'", "'" . $create_date_time . "'", "'0000-00-00 00:00:00'", "'" . $ip_address . "'", "'" . $browser . "'", "'" . $os_detail . "'", "'" . $_SESSION[$GLOBALS['site_name_user_prefix'] . '_user_type'] . "'", "'" . $_SESSION[$GLOBALS['site_name_user_prefix'] . '_user_id'] . "'", "'0'");
                            $user_login_record_id = $obj->InsertSQL($GLOBALS['login_table'], $columns, $values, '', '', $action);
                            if (preg_match("/^\d+$/", $user_login_record_id)) {
                                $_SESSION[$GLOBALS['site_name_user_prefix'] . '_user_login_record_id'] = $user_login_record_id;
                                $_SESSION[$GLOBALS['site_name_user_prefix'] . '_user_ip_address'] = $ip_address;
                                if (!empty($_SESSION[$GLOBALS['site_name_user_prefix'] . '_user_ip_address']) && isset($_SESSION[$GLOBALS['site_name_user_prefix'] . '_user_ip_address'])) {

                                    if (preg_match("/^\d+$/", $member_unique_id)) {
                                        if (empty($fcm_id)) {
                                            $fcm_id = $GLOBALS['null_value'];
                                        }

                                        $action = "";
                                        if (!empty($member_login_id)) {
                                            $action = "Member FCM ID Updated. Member ID - " . $member_login_id . ", FCM ID - " . $fcm_id;
                                        }

                                        $columns = array();
                                        $values = array();
                                        $columns = array('fcm_id');
                                        $values = array("'" . $fcm_id . "'");
                                        $update_id = $obj->UpdateSQL($GLOBALS['member_table'], $member_unique_id, $columns, $values, $action);
                                    }

                                    $output["head"]["code"] = 200;
                                    $output["head"]["msg"] = 'Login Successfully';
                                    $output["head"]["mobile_number"] = '';
                                    $output['head']['member_id'] = $member_id;
                                    $output['head']['name'] = $data['name'];
                                    $output['head']['app_expiry_date'] = $app_expiry_date;
                                    $output['head']['playstore_link'] = $playstore_link;
                                    $output['head']['message'] = $message;
                                    if (!empty($profile_photo) && $profile_photo != $GLOBALS['null_value']) {
                                        $output["head"]["profile_photo"] = 'http://sridemoapps.in/mahendran2022/temple/' . str_replace("../", "", $member_photo_location) . $profile_photo;
                                    } else {
                                        $output["head"]["profile_photo"] = "";
                                    }
                                    $output["head"]["otp_number"] = '';
                                    $output["head"]["otp"] = false;

                                    $show_receipt_page = false;
                                    $member_volunteer_data_list = array();
                                    if (!empty($member_id)) {
                                        $member_volunteer_data_list = $obj->CheckIsMemberVolunteer($member_id);
                                        if (!empty($member_volunteer_data_list)) {
                                            foreach ($member_volunteer_data_list as $data) {
                                                if (!empty($data['access_pages'])) {
                                                    $selected_access_pages = "";
                                                    $selected_access_pages = explode(",", $data['access_pages']);
                                                    if (!empty($selected_access_pages)) {
                                                        foreach ($selected_access_pages as $access_page) {
                                                            if (!empty($access_page)) {
                                                                $check_receipt_type_unique_id = "";
                                                                $check_receipt_type_unique_id = $obj->getTableColumnValue($GLOBALS['receipt_type_table'], 'name', $access_page, 'id');
                                                                if (preg_match("/^\d+$/", $check_receipt_type_unique_id)) {
                                                                    $show_receipt_page = true;
                                                                }
                                                            }
                                                        }
                                                    }
                                                }
                                            }
                                        }
                                    }

                                    $output["head"]["receipt_volunteer"] = $show_receipt_page;
                                }
                            } else {
                                $output["head"]["code"] = 400;
                                $output["head"]["msg"] = $user_login_record_id;
                            }
                        }
                    }
                }
            }
        } else {
            $output["head"]["code"] = 400;
            $output["head"]["msg"] = $valid_member;
        }
    }

    if (isset($json_obj->verify_otp_number) && $json_obj->verify_otp_number != $GLOBALS['null_value']) {
        $verify_otp_number_error = "";
        $verify_otp_number = "";
        $sms_otp_number = "";
        $sms_send_date_time = "";

        $verify_otp_number = $json_obj->verify_otp_number;
        $verify_otp_number = trim($verify_otp_number);
        if (!empty($verify_otp_number)) {
            if (preg_match('/^[0-9]{4}$/', $verify_otp_number)) {
                $session_mobile_number = "";
                if (isset($json_obj->mobile_number) && !empty($json_obj->mobile_number)) {
                    $session_mobile_number = $json_obj->mobile_number;
                }

                if (!empty($session_mobile_number)) {
                    $sms_otp_number = $obj->getTableColumnValue($GLOBALS['otp_send_phone_numbers_table'], 'phone_number', $session_mobile_number, 'otp_number');
                    $sms_send_date_time = $obj->getTableColumnValue($GLOBALS['otp_send_phone_numbers_table'], 'phone_number', $session_mobile_number, 'send_date_time');
                }
                if ($sms_otp_number != $verify_otp_number) {
                    $verify_otp_number_error = "OTP number is Mismatch";
                }
            } else {
                $verify_otp_number_error = "OTP number is invalid";
            }
        } else {
            $verify_otp_number_error = "OTP number is empty";
        }

        $result = "";
        if (empty($verify_otp_number_error)) {
            if ($sms_otp_number == $verify_otp_number) {
                $created_date_time = $GLOBALS['create_date_time_label'];
                if (!empty($created_date_time) && !empty($sms_send_date_time)) {
                    $minutes = 0;
                    $minutes = (strtotime($created_date_time) - strtotime($sms_send_date_time)) / 60;
                    if (!empty($minutes)) {
                        $minutes = $obj->truncate_number($minutes);
                        if ($minutes < 2) {
                            $member_name = "";
                            $member_id = $json_obj->member_id;
                            $member_name = $obj->getTableColumnValue($GLOBALS['member_table'], 'member_id', $member_id, 'name');

                            $profile_photo = "";
                            $profile_photo_name = "";
                            $profile_photo = $obj->getTableColumnValue($GLOBALS['member_table'], 'member_id', $member_id, 'profile_photo');
                            if (!empty($profile_photo)) {
                                $profile_photo = explode(",", $profile_photo);
                                foreach ($profile_photo as $key => $photo) {
                                    $position = $key + 1;
                                    if (!empty($photo) && file_exists($member_photo_location . $photo) && $position == 1) {
                                        $profile_photo_name = $member_photo_location . $photo;
                                    }
                                }
                            }

                            $output["head"]["code"] = 200;
                            $output["head"]["msg"] = 'Login Successfully';
                            $output["head"]["name"] = $member_name;
                            $output["head"]["mobile_number"] = $session_mobile_number;
                            $output["head"]["member_id"] = $member_id;
                            $output['head']['app_expiry_date'] = $app_expiry_date;
                            $output['head']['playstore_link'] = $playstore_link;
                            $output['head']['message'] = $message;
                            if (!empty($profile_photo) && $profile_photo != $GLOBALS['null_value']) {
                                $output["head"]["profile_photo"] = 'http://sridemoapps.in/mahendran2022/temple/' . str_replace("../", "", $member_photo_location) . $profile_photo;
                            } else {
                                $output["head"]["profile_photo"] = "";
                            }

                            $show_receipt_page = false;
                            $member_volunteer_data_list = array();
                            if (!empty($member_id)) {
                                $member_volunteer_data_list = $obj->CheckIsMemberVolunteer($member_id);
                                if (!empty($member_volunteer_data_list)) {
                                    foreach ($member_volunteer_data_list as $data) {
                                        if (!empty($data['access_pages'])) {
                                            $selected_access_pages = "";
                                            $selected_access_pages = explode(",", $data['access_pages']);
                                            if (!empty($selected_access_pages)) {
                                                foreach ($selected_access_pages as $access_page) {
                                                    if (!empty($access_page)) {
                                                        $check_receipt_type_unique_id = "";
                                                        $check_receipt_type_unique_id = $obj->getTableColumnValue($GLOBALS['receipt_type_table'], 'name', $access_page, 'id');
                                                        if (preg_match("/^\d+$/", $check_receipt_type_unique_id)) {
                                                            $show_receipt_page = true;
                                                        }
                                                    }
                                                }
                                            }
                                        }
                                    }
                                }
                            }

                            $output["head"]["receipt_volunteer"] = $show_receipt_page;
                        } else {
                            $output["head"]["code"] = 400;
                            $output["head"]["msg"] = 'OTP is expired';
                        }
                    }
                }
                $otp_send_list = array();
                if (!empty($session_mobile_number)) {
                    $otp_send_list = $obj->getTableRecords($GLOBALS['otp_send_phone_numbers_table'], 'phone_number', $session_mobile_number, '');
                }
                if (!empty($otp_send_list)) {
                    foreach ($otp_send_list as $data) {
                        if (!empty($data['id'])) {
                            $columns = array();
                            $values = array();
                            $update_id = "";
                            $columns = array('deleted');
                            $values = array("'1'");
                            $update_id = $obj->UpdateSQL($GLOBALS['otp_send_phone_numbers_table'], $data['id'], $columns, $values, '');
                        }
                    }
                }
            }
        } else {
            if (!empty($verify_otp_number_error)) {
                $output["head"]["code"] = 400;
                $output["head"]["msg"] = $verify_otp_number_error;
            }
        }
    }
}


$result = json_encode($output, JSON_NUMERIC_CHECK);

echo $result;
