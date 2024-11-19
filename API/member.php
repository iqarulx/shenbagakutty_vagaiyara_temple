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

if(isset($json_obj->update_member_id)) {
    $type = "";
    if(isset($json_obj->type)){
        $type = $json_obj->type;
    }
    
    $date_of_birth = ""; $date_of_birth_error = ""; $password = ""; $password_error = ""; $rasi = ""; $rasi_error = ""; $natchathiram = ""; 
    $natchathiram_error = ""; $profession = ""; $profession_error = ""; 
    
    $wife_name = ""; $wife_name_error = ""; $wife_education = ""; $wife_education_error = ""; $wife_date_of_birth = ""; $wife_date_of_birth_error = "";
    $wife_rasi = ""; $wife_rasi_error = ""; $wife_natchathiram = ""; $wife_natchathiram_error = ""; $marriage_date = ""; $marriage_date_error = "";
    
    $aadhaar_number = ""; $aadhaar_number_error = ""; $phone_number = ""; $phone_number_error = ""; $mobile_number = ""; $mobile_number_error = "";		

    $address = ""; $address_error = ""; $city = ""; $pincode = ""; $city_pincode_error = ""; $state = ""; $state_error = ""; $country = "";
    $country_error = "";
    
    $company_name = ""; $company_name_error = ""; $remarks = ""; $remarks_error = ""; $histroy = ""; $histroy_error = "";

    $profile_photo_name = array(); $profile_photo_name_positions = array(); $profile_photo = "";

    $valid_member = ""; $form_name = "member_form";

    if(isset($json_obj->update_member_id)) {
        $edit_id = $json_obj->update_member_id;
    }

    $rasi_english_values = $GLOBALS['rasi_english_list']; $natchathiram_english_values = $GLOBALS['natchathiram_english_list'];

    if(empty($type)){
        $valid_member .= "Type Missing";
    }

    $member_child_error = "";

    if($type == "personal"){

        if(isset($json_obj->date_of_birth)) {
            $date_of_birth = $json_obj->date_of_birth;
            $date_of_birth = trim($date_of_birth);
            if(!empty($date_of_birth)) {
                $date_of_birth_error = $valid->valid_date($date_of_birth, "date of birth", "text");
                if(!empty($date_of_birth_error)) {
                    if(!empty($valid_member)) {
                        $valid_member = $valid_member.$date_of_birth_error;
                    }
                    else {
                        $valid_member = $date_of_birth_error;
                    }
                }
            }
        }

        if(isset($json_obj->password)) {
            $password = $json_obj->password;
            $password = trim($password);
            if(!empty($password)) {
                $password_error = $valid->common_validation($password, "password", "text");
                if(!empty($password_error)) {
                    if(!empty($valid_member)) {
                        $valid_member = $valid_member.$password_error;
                    }
                    else {
                        $valid_member = $password_error;
                    }
                }
            }
            else {
                $password = "Temple123@";
            }
        }

        if(isset($json_obj->rasi)) {
            $rasi = $json_obj->rasi;
            $rasi = trim($rasi);
            if(!empty($rasi)) {
                $rasi_error = $valid->common_validation($rasi, "rasi", "text");
                if(!empty($rasi) && empty($rasi_error)) {					
                    if(!in_array($rasi, $rasi_english_values)) {
                        $rasi_error = "Invalid Rasi";
                    }
                }
                if(!empty($rasi_error)) {
                    if(!empty($valid_member)) {
                        $valid_member = $valid_member.$rasi_error;
                    }
                    else {
                        $valid_member = $rasi_error;
                    }
                }
            }
        }
    
        if(isset($json_obj->natchathiram)) {
            $natchathiram = $json_obj->natchathiram;
            $natchathiram = trim($natchathiram);
            if(!empty($natchathiram)) {
                $natchathiram_error = $valid->common_validation($natchathiram, "natchathiram", "text");
                if(!empty($natchathiram) && empty($natchathiram_error)) {					
                    if(!in_array($natchathiram, $natchathiram_english_values)) {
                        $natchathiram_error = "Invalid Natchathiram";
                    }
                }
                if(!empty($natchathiram_error)) {
                    if(!empty($valid_member)) {
                        $valid_member = $valid_member.$natchathiram_error;
                    }
                    else {
                        $valid_member = $natchathiram_error;
                    }
                }
            }
        }
    
        $profession = $json_obj->profession_id;
        $profession = trim($profession);
        if(!empty($profession)) {
            $profession_unique_id = "";
            $profession_unique_id = $obj->getTableColumnValue($GLOBALS['profession_table'], 'profession_id', $profession, 'id');
            if(!preg_match("/^\d+$/", $profession_unique_id)) {
                $profession_error = "Invalid profession";
            }
        }
        if(!empty($profession_error)) {
            if(!empty($valid_member)) {
                $valid_member .= $profession_error;
            }
            else {
                $valid_member = $profession_error;
            }
        }
    
        if(isset($json_obj->wife_name)) {
            $wife_name = $json_obj->wife_name;
            $wife_name = trim($wife_name);
            if(!empty($wife_name)) {
                $wife_name_error = $valid->common_validation($wife_name, "wife name", "text");
                if(!empty($wife_name_error)) {
                    if(!empty($valid_member)) {
                        $valid_member = $valid_member.$wife_name_error;
                    }
                    else {
                        $valid_member = $wife_name_error;
                    }
                }
            }
        }
    
        if(isset($json_obj->wife_education)) {
            $wife_education = $json_obj->wife_education;
            $wife_education = trim($wife_education);
            if(!empty($wife_education)) {
                $wife_education_error = $valid->common_validation($wife_education, "wife education", "text");
                if(!empty($wife_education_error)) {
                    if(!empty($valid_member)) {
                        $valid_member = $valid_member." ".$wife_education_error;
                    }
                    else {
                        $valid_member = $wife_education_error;
                    }
                }
            }
        }

        if(isset($json_obj->wife_date_of_birth)) {
            $wife_date_of_birth = $json_obj->wife_date_of_birth;
            $wife_date_of_birth = trim($wife_date_of_birth);
            if(!empty($wife_date_of_birth)) {
                $wife_date_of_birth_error = $valid->valid_date($wife_date_of_birth, "wife date of birth", "text");
                if(!empty($wife_date_of_birth_error)) {
                    if(!empty($valid_member)) {
                        $valid_member = $valid_member.$wife_date_of_birth_error;
                    }
                    else {
                        $valid_member = $wife_date_of_birth_error;
                    }
                }
            }
        }
        
        if(isset($json_obj->wife_rasi)) {
            $wife_rasi = $json_obj->wife_rasi;
            $wife_rasi = trim($wife_rasi);
            if(!empty($wife_rasi)) {
                $wife_rasi_error = $valid->common_validation($wife_rasi, "wife_rasi", "text");
                if(!empty($wife_rasi) && empty($wife_rasi_error)) {
                    if(!in_array($wife_rasi, $rasi_english_values)) {
                        $wife_rasi_error = "Invalid Rasi";
                    }
                }
                if(!empty($wife_rasi_error)) {
                    if(!empty($valid_member)) {
                        $valid_member = $valid_member." ".$wife_rasi_error;
                    }
                    else {
                        $valid_member = $wife_rasi_error;
                    }
                }
            }
        }
        
        if(isset($json_obj->wife_natchathiram)) {
            $wife_natchathiram = $json_obj->wife_natchathiram;
            $wife_natchathiram = trim($wife_natchathiram);
            if(!empty($wife_natchathiram)) {
                $wife_natchathiram_error = $valid->common_validation($wife_natchathiram, "wife_natchathiram", "text");
                if(!empty($wife_natchathiram) && empty($wife_natchathiram_error)) {
                    if(!in_array($wife_natchathiram, $natchathiram_english_values)) {
                        $wife_natchathiram_error = "Invalid Natchathiram";
                    }
                }
                if(!empty($wife_natchathiram_error)) {
                    if(!empty($valid_member)) {
                        $valid_member = $valid_member." ".$wife_natchathiram_error;
                    }
                    else {
                        $valid_member = $wife_natchathiram_error;
                    }
                }
            }
        }

        if(isset($json_obj->marriage_date)) {
            $marriage_date = $json_obj->marriage_date;
            $marriage_date = trim($marriage_date);
            if(!empty($marriage_date)) {
                $marriage_date_error = $valid->valid_date($marriage_date, "marriage date", "text");
                if(!empty($marriage_date_error)) {
                    if(!empty($valid_member)) {
                        $valid_member = $valid_member.$marriage_date_error;
                    }
                    else {
                        $valid_member = $marriage_date_error;
                    }
                }
            }
        }
        
        if(isset($json_obj->phone_number)) {
            $phone_number = $json_obj->phone_number;
            $phone_number = trim($phone_number);
            if(!empty($phone_number)) {
                $phone_number_error = $valid->common_validation($phone_number, "phone number", "text");
                if(!empty($phone_number_error)) {
                    if(!empty($valid_member)) {
                        $valid_member = $valid_member.$phone_number_error;
                    }
                    else {
                        $valid_member = $phone_number_error;
                    }
                }
            }
        }
    
        if(isset($json_obj->mobile_number)) {
            $mobile_number = $json_obj->mobile_number;
            $mobile_number = trim($mobile_number);
            if(!empty($mobile_number)) {
                $mobile_number_error = $valid->common_validation($mobile_number, "mobile number", "text");
                if(!empty($mobile_number_error)) {
                    if(!empty($valid_member)) {
                        $valid_member = $valid_member.$mobile_number_error;
                    }
                    else {
                        $valid_member = $mobile_number_error;
                    }
                }
            }
        }
    
        if(isset($json_obj->aadhaar_number)) {
            $aadhaar_number = $json_obj->aadhaar_number;
            $aadhaar_number = trim($aadhaar_number);
            if(!empty($aadhaar_number)) {
                $aadhaar_number = str_replace(' ', '', $aadhaar_number);
                $aadhaar_number = chunk_split($aadhaar_number, 4, ' ');
            }
            if(!empty($aadhaar_number)) {
                $aadhaar_number_error = $valid->valid_aadhaar_number($aadhaar_number, "aadhaar number", "1");
                if(!empty($aadhaar_number_error)) {
                    if(!empty($valid_member)) {
                        $valid_member = $valid_member.$aadhaar_number_error;
                    }
                    else {
                        $valid_member = $aadhaar_number_error;
                    }
                }
            }
        }
    }
    else if($type == "address"){
        if(isset($json_obj->address)) {
            $address = $json_obj->address;
            $address = trim($address);
            if(!empty($address)) {
                $address_error = $valid->common_validation($address, "Address", "text");
                if(!empty($address_error)) {
                    if(!empty($valid_member)) {
                        $valid_member = $valid_member." ".$address_error;
                    }
                    else {
                        $valid_member = $address_error;
                    }
                }
            }
        }
    
        if(isset($json_obj->city)) {
            $city = $json_obj->city;
            $city = trim($city);
        }
    
        if(isset($json_obj->pincode)) {
            $pincode = $json_obj->pincode;
            $pincode = trim($pincode);
        }
    
        $city_pincode_error = $valid->valid_city_pincode($city, $pincode);
        /*if(!empty($city) && empty($city_pincode_error)) {
            $city_unique_id = "";
            $city_unique_id = $obj->getTableColumnValue($GLOBALS['city_table'], 'city_name', $city, 'id');
            if(!preg_match("/^\d+$/", $city_unique_id)) {
                $city_pincode_error = "Invalid city";
            }
        }*/
        if(!empty($city_pincode_error)) {
            if(!empty($valid_member)) {
                $valid_member = $valid_member.' '.$city_pincode_error;
            }
            else {
                $valid_member = $city_pincode_error;
            }
        }

        if(isset($json_obj->state)) {
            $state = $json_obj->state;
            $state = trim($state);
            if(!empty($state)) {
                $state_error = $valid->common_validation($state, "state", "text");
                if(!empty($state_error)) {
                    if(!empty($valid_member)) {
                        $valid_member = $valid_member.$state_error;
                    }
                    else {
                        $valid_member = $state_error;
                    }
                }
            }
        }

        if(isset($json_obj->country)) {
            $country = $json_obj->country;
            $country = trim($country);
            if(!empty($country)) {
                $country_error = $valid->common_validation($country, "country", "text");
                if(!empty($country_error)) {
                    if(!empty($valid_member)) {
                        $valid_member = $valid_member.$country_error;
                    }
                    else {
                        $valid_member = $country_error;
                    }
                }
            }
        }
    
        if(isset($json_obj->company_name)) {
            $company_name = $json_obj->company_name;
            $company_name = trim($company_name);
            if(!empty($company_name)) {
                $company_name_error = $valid->common_validation($company_name, "company name", "text");
                if(!empty($company_name_error)) {
                    if(!empty($valid_member)) {
                        $valid_member = $valid_member.$company_name_error;
                    }
                    else {
                        $valid_member = $company_name_error;
                    }
                }
            }
        }
    
        if(isset($json_obj->remarks)) {
            $remarks = $json_obj->remarks;
            $remarks = trim($remarks);
            if(!empty($remarks)) {
                $remarks_error = $valid->common_validation($remarks, "remarks", "text");
                if(!empty($remarks_error)) {
                    if(!empty($valid_member)) {
                        $valid_member = $valid_member." ".$remarks_error;
                    }
                    else {
                        $valid_member = $remarks_error;
                    }
                }
            }
        }
    
        if(isset($json_obj->histroy)) {
            $histroy = $json_obj->histroy;
            $histroy = trim($histroy);
            if(!empty($histroy)) {
                $histroy_error = $valid->common_validation($histroy, "histroy", "text");
                if(!empty($histroy_error)) {
                    if(!empty($valid_member)) {
                        $valid_member = $valid_member." ".$histroy_error;
                    }
                    else {
                        $valid_member = $histroy_error;
                    }
                }
            }
        }
    }
    else if($type == "child"){
        //member child details
        $member_child_id = array(); $member_child_initial = array(); $member_child_name = array(); $member_child_profile_photo_prefix = array();
        $member_child_gender = array(); $member_child_birth_date = array(); $member_child_rasi = array(); $member_child_natchathiram = array();
        $member_child_aadhaar_number = array(); $member_child_mobile_number = array(); $member_child_education = array(); $member_child_job = array();
        $member_child_marriage_status = array(); $member_child_partner_name = array(); $member_child_partner_education = array();
        $member_child_partner_birth_date = array(); $member_child_marriage_date = array(); $member_child_partner_rasi = array();
        $member_child_partner_natchathiram = array();

        $children = ($json_obj->children);

        $j = 0;
        for($i = 0; $i < count($children); $i++){
            foreach($children[$i] as $key=>$val){
                if($key == 'member_child_id'){
                    $member_child_id[$i] = $val;
                }
                if($key == 'member_child_initial'){
                    $member_child_initial[$i] = $val;
                }
                if($key == 'member_child_name'){
                    $member_child_name[$i] = $val;
                }
                if($key == 'member_child_profile_photo_prefix'){
                    $member_child_profile_photo_prefix[$i] = $val;
                }
                if($key == 'member_child_gender'){
                    $member_child_gender[$i] = $val;
                }
                if($key == 'member_child_birth_date'){
                    $member_child_birth_date[$i] = $val;
                }
                if($key == 'member_child_rasi'){
                    $member_child_rasi[$i] = $val;
                }
                if($key == 'member_child_natchathiram'){
                    $member_child_natchathiram[$i] = $val;
                }
                if($key == 'member_child_aadhaar_number'){
                    $member_child_aadhaar_number[$i] = $val;
                }
                if($key == 'member_child_mobile_number'){
                    $member_child_mobile_number[$i] = $val;
                }
                if($key == 'member_child_education'){
                    $member_child_education[$i] = $val;
                }
                if($key == 'member_child_job'){
                    $member_child_job[$i] = $val;
                }
                if($key == 'member_child_marriage_status'){
                    $member_child_marriage_status[$i] = $val;
                }
                if($key == 'member_child_partner_name'){
                    $member_child_partner_name[$i] = $val;
                }
                if($key == 'member_child_partner_education'){
                    $member_child_partner_education[$i] = $val;
                }
                if($key == 'member_child_partner_birth_date'){
                    $member_child_partner_birth_date[$i] = $val;
                }
                if($key == 'member_child_marriage_date'){
                    $member_child_marriage_date[$i] = $val;
                }
                if($key == 'member_child_partner_rasi'){
                    $member_child_partner_rasi[$i] = $val;
                }
                if($key == 'member_child_partner_natchathiram'){
                    $member_child_partner_natchathiram[$i] = $val;
                }
            }
        }

        $member_child_details = array(); $gender_options = $GLOBALS['gender_values'];  
        if(!empty($member_child_name) && is_array($member_child_name)) {
            for($i = 0; $i < count($member_child_name); $i++) {
                $child_name_error = "";
                $member_child_name[$i] = trim($member_child_name[$i]);
                if(!empty($member_child_name[$i])) {
                    $child_name_error = $valid->common_validation($member_child_name[$i], "child name", "text");
                }
                if(empty($child_name_error)) {
                    $child_id_error = "";
                    $member_child_id[$i] = trim($member_child_id[$i]);
                    if(!empty($member_child_id[$i])) {
                        $member_child_unique_id = "";
                        $member_child_unique_id = $obj->getTableColumnValue($GLOBALS['member_child_table'], 'member_child_id', $member_child_id[$i], 'id');
                        if(!preg_match("/^\d+$/", $member_child_unique_id)) {
                            $child_id_error = "Invalid member child";
                        }
                    }    
                    if(empty($child_id_error)) {
                        $child_initial_error = "";
                        $member_child_initial[$i] = trim($member_child_initial[$i]);
                        if(!empty($member_child_initial[$i])) {
                            $child_initial_error = $valid->common_validation($member_child_initial[$i], "child initial", "text");
                        }
                        if(empty($child_initial_error)) {
                            if(empty($member_child_initial[$i])) {
                                $member_child_initial[$i] = $GLOBALS['null_value'];
                            }

                            $child_gender_error = "";
                            $member_child_gender[$i] = trim($member_child_gender[$i]);
                            if(!empty($member_child_gender[$i])) {
                                if(!in_array(ucfirst($member_child_gender[$i]), $gender_options)) {
                                    $child_gender_error = "Invalid child gender";
                                }
                            }
                            if(empty($child_gender_error)) {
                                if(empty($member_child_gender[$i])) {
                                    $member_child_gender[$i] = $GLOBALS['null_value'];
                                }

                                $child_birth_date_error = "";
                                $member_child_birth_date[$i] = trim($member_child_birth_date[$i]);
                                if(!empty($member_child_birth_date[$i])) {
                                    $member_child_birth_date[$i] = date("d-m-Y", strtotime($member_child_birth_date[$i]));
                                    $child_birth_date_error = $valid->valid_date($member_child_birth_date[$i], "child birth date", "1");
                                }
                                if(empty($child_birth_date_error)) {
                                    if(empty($member_child_birth_date[$i])) {
                                        $member_child_birth_date[$i] = "0000-00-00";
                                    }
                                    if(!empty($member_child_birth_date[$i]) && $member_child_birth_date[$i] != "0000-00-00") {
                                        $member_child_birth_date[$i] = date("Y-m-d", strtotime($member_child_birth_date[$i]));
                                    }

                                    $child_rasi_error = "";
                                    $member_child_rasi[$i] = trim($member_child_rasi[$i]);
                                    if(!empty($member_child_rasi[$i])) {
                                        if(!in_array($member_child_rasi[$i], $rasi_english_values)) {
                                            $child_rasi_error = "Invalid child rasi";
                                        }
                                    }
                                    if(empty($child_rasi_error)) {
                                        if(empty($member_child_rasi[$i])) {
                                            $member_child_rasi[$i] = $GLOBALS['null_value'];
                                        }

                                        $child_natchathiram_error = "";
                                        $member_child_natchathiram[$i] = trim($member_child_natchathiram[$i]);
                                        if(!empty($member_child_natchathiram[$i])) {
                                            if(!in_array($member_child_natchathiram[$i], $natchathiram_english_values)) {
                                                $child_natchathiram_error = "Invalid child natchathiram";
                                            }
                                        }
                                        if(empty($child_natchathiram_error)) {
                                            if(empty($member_child_natchathiram[$i])) {
                                                $member_child_natchathiram[$i] = $GLOBALS['null_value'];
                                            }

                                            $child_aadhaar_number_error = "";
                                            $member_child_aadhaar_number[$i] = trim($member_child_aadhaar_number[$i]);
                                            if(!empty($member_child_aadhaar_number[$i])) {
                                                $child_aadhaar_number_error = $valid->valid_aadhaar_number($member_child_aadhaar_number[$i], "child aadhaar number", "1");
                                            }
                                            if(empty($child_aadhaar_number_error)) {
                                                if(empty($member_child_aadhaar_number[$i])) {
                                                    $member_child_aadhaar_number[$i] = $GLOBALS['null_value'];
                                                }

                                                $child_mobile_number_error = "";
                                                $member_child_mobile_number[$i] = trim($member_child_mobile_number[$i]);
                                                if(!empty($member_child_mobile_number[$i])) {
                                                    $child_mobile_number_error = $valid->valid_mobile_number($member_child_mobile_number[$i], "child mobile number", "1");
                                                }
                                                if(empty($child_mobile_number_error)) {
                                                    if(empty($member_child_mobile_number[$i])) {
                                                        $member_child_mobile_number[$i] = $GLOBALS['null_value'];
                                                    }

                                                    $child_education_error = "";
                                                    $member_child_education[$i] = trim($member_child_education[$i]);
                                                    if(!empty($member_child_education[$i])) {
                                                        $child_education_error = $valid->common_validation($member_child_education[$i], "child education", "text");
                                                    }
                                                    if(empty($child_education_error)) {
                                                        if(empty($member_child_education[$i])) {
                                                            $member_child_education[$i] = $GLOBALS['null_value'];
                                                        }

                                                        $child_job_error = "";
                                                        $member_child_job[$i] = trim($member_child_job[$i]);
                                                        if(!empty($member_child_job[$i])) {
                                                            $profession_unique_id = "";
                                                            $profession_unique_id = $obj->getTableColumnValue($GLOBALS['profession_table'], 'profession_id', $member_child_job[$i], 'id');
                                                            if(!preg_match("/^\d+$/", $profession_unique_id)) {
                                                                $child_job_error = "Invalid profession";
                                                            }
                                                        }
                                                        if(empty($child_job_error)) {
                                                            if(empty($member_child_job[$i])) {
                                                                $member_child_job[$i] = $GLOBALS['null_value'];
                                                            }

                                                            $child_marriage_status_error = "";
                                                            $member_child_marriage_status[$i] = trim($member_child_marriage_status[$i]);
                                                            if(!empty($member_child_marriage_status[$i])) {
                                                                if($member_child_marriage_status[$i] != 1 && $member_child_marriage_status[$i] != 2) {
                                                                    $child_marriage_status_error = "Invalid child marriage status";
                                                                }
                                                            }
                                                            if(empty($child_marriage_status_error)) {
                                                                if(empty($member_child_marriage_status[$i])) {
                                                                    $member_child_marriage_status[$i] = 2;
                                                                }

                                                                if($member_child_marriage_status[$i] == 1) {

                                                                    $child_partner_name_error = "";
                                                                    $member_child_partner_name[$i] = trim($member_child_partner_name[$i]);
                                                                    if(!empty($member_child_partner_name[$i])) {
                                                                        $child_partner_name_error = $valid->common_validation($member_child_partner_name[$i], "child partner name", "text");
                                                                    }
                                                                    if(empty($child_partner_name_error)) {
                                                                        if(empty($member_child_partner_name[$i])) {
                                                                            $member_child_partner_name[$i] = $GLOBALS['null_value'];
                                                                        }

                                                                        $child_partner_education_error = "";
                                                                        $member_child_partner_education[$i] = trim($member_child_partner_education[$i]);
                                                                        if(!empty($member_child_partner_education[$i])) {
                                                                            $child_partner_education_error = $valid->common_validation($member_child_partner_education[$i], "child partner education", "text");
                                                                        }
                                                                        if(empty($child_partner_education_error)) {
                                                                            if(empty($member_child_partner_education[$i])) {
                                                                                $member_child_partner_education[$i] = $GLOBALS['null_value'];
                                                                            }

                                                                            $child_partner_date_of_birth_error = "";
                                                                            $member_child_partner_birth_date[$i] = trim($member_child_partner_birth_date[$i]);
                                                                            if(!empty($member_child_partner_birth_date[$i])) {
                                                                                $member_child_partner_birth_date[$i] = date("d-m-Y", strtotime($member_child_partner_birth_date[$i]));
                                                                                $child_partner_date_of_birth_error = $valid->valid_date($member_child_partner_birth_date[$i], "child partner birth date", "1");
                                                                            }
                                                                            if(empty($child_partner_date_of_birth_error)) {
                                                                                if(empty($member_child_partner_birth_date[$i])) {
                                                                                    $member_child_partner_birth_date[$i] = "0000-00-00";
                                                                                }
                                                                                if(!empty($member_child_partner_birth_date[$i]) && $member_child_partner_birth_date[$i] != "0000-00-00") {
                                                                                    $member_child_partner_birth_date[$i] = date("Y-m-d", strtotime($member_child_partner_birth_date[$i]));
                                                                                }

                                                                                $child_marriage_date_error = "";
                                                                                $member_child_marriage_date[$i] = trim($member_child_marriage_date[$i]);
                                                                                if(!empty($member_child_marriage_date)) {
                                                                                    $member_child_marriage_date[$i] = date("d-m-Y", strtotime($member_child_marriage_date[$i]));
                                                                                    $child_marriage_date_error = $valid->valid_date($member_child_marriage_date[$i], "child marriage date", "1");
                                                                                }
                                                                                if(empty($child_marriage_date_error)) {
                                                                                    if(empty($member_child_marriage_date[$i])) {
                                                                                        $member_child_marriage_date[$i] = "0000-00-00";
                                                                                    }
                                                                                    if(!empty($member_child_marriage_date[$i]) && $member_child_marriage_date[$i] != "0000-00-00") {
                                                                                        $member_child_marriage_date[$i] = date("Y-m-d", strtotime($member_child_marriage_date[$i]));
                                                                                    }

                                                                                    $child_partner_rasi_error = "";
                                                                                    $member_child_partner_rasi[$i] = trim($member_child_partner_rasi[$i]);
                                                                                    if(!empty($member_child_partner_rasi[$i])) {
                                                                                        if(!in_array($member_child_partner_rasi[$i], $rasi_english_values)) {
                                                                                            $child_partner_rasi_error = "Invalid child partner rasi";
                                                                                        }
                                                                                    }
                                                                                    if(empty($child_partner_rasi_error)) {
                                                                                        if(empty($member_child_partner_rasi[$i])) {
                                                                                            $member_child_partner_rasi[$i] = $GLOBALS['null_value'];
                                                                                        }

                                                                                        $child_partner_natchathiram_error = "";
                                                                                        $member_child_partner_natchathiram[$i] = trim($member_child_partner_natchathiram[$i]);
                                                                                        if(!empty($member_child_partner_natchathiram[$i])) {
                                                                                            if(!in_array($member_child_partner_natchathiram[$i], $natchathiram_english_values)) {
                                                                                                $child_partner_natchathiram_error = "Invalid child partner natchathiram";
                                                                                            }
                                                                                        }
                                                                                        if(empty($child_partner_natchathiram_error)) {
                                                                                            if(empty($member_child_partner_natchathiram[$i])) {
                                                                                                $member_child_partner_natchathiram[$i] = $GLOBALS['null_value'];
                                                                                            }
                                                                                        }
                                                                                        else {
                                                                                            $member_child_error = $child_partner_natchathiram_error;
                                                                                        }

                                                                                    }
                                                                                    else {
                                                                                        $member_child_error = $child_partner_rasi_error;
                                                                                    }

                                                                                }
                                                                                else {
                                                                                    $member_child_error = $child_marriage_date_error;
                                                                                }

                                                                            }
                                                                            else {
                                                                                $member_child_error = $child_partner_date_of_birth_error;
                                                                            }

                                                                        }
                                                                        else {
                                                                            $member_child_error = $child_partner_education_error;
                                                                        }

                                                                    }
                                                                    else {
                                                                        $member_child_error = $child_partner_name_error;
                                                                    }

                                                                }
                                                                else {
                                                                    $member_child_partner_name[$i] = $GLOBALS['null_value'];
                                                                    $member_child_partner_education[$i] = $GLOBALS['null_value'];
                                                                    $member_child_partner_birth_date[$i] = "0000-00-00";
                                                                    $member_child_marriage_date[$i] = "0000-00-00";
                                                                    $member_child_partner_rasi[$i] = $GLOBALS['null_value'];
                                                                    $member_child_partner_natchathiram[$i] = $GLOBALS['null_value'];
                                                                }

                                                                $member_child_details[] = array('member_child_id' => $member_child_id[$i], 'initial' => $member_child_initial[$i], 'name' => $member_child_name[$i], 'profile_photo_prefix' => $member_child_profile_photo_prefix[$i], 'gender' => $member_child_gender[$i], 'birth_date' => $member_child_birth_date[$i], 'rasi' => $member_child_rasi[$i], 'natchathiram' => $member_child_natchathiram[$i], 'aadhaar_number' => $member_child_aadhaar_number[$i], 'mobile_number' => $member_child_mobile_number[$i], 'education' => $member_child_education[$i], 'job' => $member_child_job[$i], 'marriage_status' => $member_child_marriage_status[$i], 'partner_name' => $member_child_partner_name[$i], 'partner_education' => $member_child_partner_education[$i], 'partner_date_of_birth' => $member_child_partner_birth_date[$i], 'marriage_date' => $member_child_marriage_date[$i], 'partner_rasi' => $member_child_partner_rasi[$i], 'partner_natchathiram' => $member_child_partner_natchathiram[$i]);

                                                            }
                                                            else {
                                                                $member_child_error = $child_marriage_status_error;
                                                            }

                                                        }
                                                        else {
                                                            $member_child_error = $child_job_error;
                                                        }

                                                    }
                                                    else {
                                                        $member_child_error = $child_education_error;
                                                    }

                                                }
                                                else {
                                                    $member_child_error = $child_mobile_number_error;
                                                }

                                            }
                                            else {
                                                $member_child_error = $child_aadhaar_number_error;
                                            }

                                        }
                                        else {
                                            $member_child_error = $child_natchathiram_error;
                                        }

                                    }
                                    else {
                                        $member_child_error = $child_rasi_error;
                                    }

                                }
                                else {
                                    $member_child_error = $child_birth_date_error;
                                }

                            }
                            else {
                                $member_child_error = $child_gender_error;
                            }

                        }
                        else {
                            $member_child_error = $child_initial_error;
                        }
                    }
                    else {
                        $member_child_error = $child_id_error;
                    }
                }
                else {
                    $member_child_error = $child_name_error;
                }
            }
        }
    }
    
    $result = "";    
    if(empty($valid_member) && empty($member_child_error)) {
        $member_unique_id = "";
        if(!empty($edit_id)) {
            $member_unique_id = $obj->getTableColumnValue($GLOBALS['member_table'], 'member_id', $edit_id, 'id');
        }
        if(preg_match("/^\d+$/", $member_unique_id)) {                
            if(empty($profession)) {
                $profession = $GLOBALS['null_value'];
            }
            if(empty($pincode)) {
                $pincode = $GLOBALS['null_value'];
            }

            if(empty($rasi)) {
                /*$rasi = $obj->encode_decode('encrypt', $rasi);
            }
            else {*/
                $rasi = $GLOBALS['null_value'];
            }
            if(empty($natchathiram)) {
                /*$natchathiram = $obj->encode_decode('encrypt', $natchathiram);
            }
            else {*/
                $natchathiram = $GLOBALS['null_value'];
            }

            if(empty($wife_name)) {
                /*$wife_name = $obj->encode_decode('encrypt', $wife_name);
            }
            else {*/
                $wife_name = $GLOBALS['null_value'];
            }
            if(empty($wife_education)) {
                /*$wife_education = $obj->encode_decode('encrypt', $wife_education);
            }
            else {*/
                $wife_education = $GLOBALS['null_value'];
            }

            if(empty($wife_rasi)) {
                /*$wife_rasi = $obj->encode_decode('encrypt', $wife_rasi);
            }
            else {*/
                $wife_rasi = $GLOBALS['null_value'];
            }
            if(empty($wife_natchathiram)) {
                /*$wife_natchathiram = $obj->encode_decode('encrypt', $wife_natchathiram);
            }
            else {*/
                $wife_natchathiram = $GLOBALS['null_value'];
            }

            if(empty($phone_number)) {
                /*$phone_number = $obj->encode_decode('encrypt', $phone_number);
            }
            else {*/
                $phone_number = $GLOBALS['null_value'];
            }
            if(empty($mobile_number)) {
                /*$mobile_number = $obj->encode_decode('encrypt', $mobile_number);
            }
            else {*/
                $mobile_number = $GLOBALS['null_value'];
            }
            if(empty($aadhaar_number)) {
                /*$aadhaar_number = $obj->encode_decode('encrypt', $aadhaar_number);
            }
            else {*/
                $aadhaar_number = $GLOBALS['null_value'];
            }
            
            if(empty($address)) {
                /*$address = $obj->encode_decode('encrypt', $address);
            }
            else {*/
                $address = $GLOBALS['null_value'];
            }
            if(empty($city)) {
                //$city = $obj->encode_decode('encrypt', $city);
                $city = $GLOBALS['null_value'];
            }

            if(empty($company_name)) {
                /*$company_name = $obj->encode_decode('encrypt', $company_name);
            }
            else {*/
                $company_name = $GLOBALS['null_value'];
            }
            if(empty($remarks)) {
                /*$remarks = $obj->encode_decode('encrypt', $remarks);
            }
            else {*/
                $remarks = $GLOBALS['null_value'];
            }
            if(empty($histroy)) {
                /*$histroy = $obj->encode_decode('encrypt', $histroy);
            }
            else {*/
                $histroy = $GLOBALS['null_value'];
            }

            if(!empty($date_of_birth) && $date_of_birth != "0000-00-00") {
                $date_of_birth = date("Y-m-d", strtotime($date_of_birth));
            }
            else {
                $date_of_birth = "0000-00-00";
            }

            if(!empty($wife_date_of_birth) && $wife_date_of_birth != "0000-00-00") {
                $wife_date_of_birth = date("Y-m-d", strtotime($wife_date_of_birth));
            }
            else {
                $wife_date_of_birth = "0000-00-00";
            }

            if(!empty($marriage_date) && $marriage_date != "0000-00-00") {
                $marriage_date = date("Y-m-d", strtotime($marriage_date));
            }
            else {
                $marriage_date = "0000-00-00";
            }

            $created_date_time = $GLOBALS['create_date_time_label']; $creator = $json_obj->creator;
            $creator_name = $obj->encode_decode('encrypt', $json_obj->creator_name);

            $record_id = 0; $child_member_id = "";

            $getUniqueID = "";
            $getUniqueID = $obj->getTableColumnValue($GLOBALS['member_table'], 'member_id', $edit_id, 'id');
            if(preg_match("/^\d+$/", $getUniqueID)) {
                $action = "";
                if(!empty($name)) {
                    $action = "Member Updated. Name - ".$name;
                }
            
                $columns = array(); $values = array();	
                if($type == "personal"){
                    $columns = array('creator_name', 'date_of_birth', 'password', 'rasi', 'natchathiram', 'profession', 'wife_name', 'wife_education', 'wife_date_of_birth', 'wife_rasi', 'wife_natchathiram', 'marriage_date', 'phone_number', 'mobile_number', 'aadhaar_number');
                    $values = array("'".$creator_name."'", "'".$date_of_birth."'", "'".$password."'", "'".$rasi."'", "'".$natchathiram."'", "'".$profession."'", "'".$wife_name."'", "'".$wife_education."'", "'".$wife_date_of_birth."'", "'".$wife_rasi."'", "'".$wife_natchathiram."'", "'".$marriage_date."'", "'".$phone_number."'", "'".$mobile_number."'", "'".$aadhaar_number."'");    
                }
                else if($type == "address"){
                    $columns = array('creator_name', 'address', 'country', '', 'city', 'pincode', 'company_name', 'remarks', 'histroy');
                    $values = array("'".$creator_name."'", "'".$address."'", "'".$country."'", "'".$state."'", "'".$city."'", "'".$pincode."'", "'".$company_name."'", "'".$remarks."'", "'".$histroy."'");
                }
                
                if($columns != "" && $values != ""){
                    $member_update_id = $obj->UpdateSQL($GLOBALS['member_table'], $getUniqueID, $columns, $values, $action);
                    if(preg_match("/^\d+$/", $member_update_id)) {
                        $record_id = $getUniqueID;
                        $output["head"]["code"] = 200;
                        $output["head"]["msg"] = "Updated Successfully";				
                    }
                    else {
                        $output["head"]["code"] = 400;
                        $output["head"]["msg"] = $member_update_id;	
                    }
                }						
            }

            if($type == "child"){
                $record_id = $getUniqueID;
                if(!empty($record_id)) {
                    $child_member_id = "";
                    $child_member_id = $obj->getTableColumnValue($GLOBALS['member_table'], 'id', $record_id, 'member_id');
                    
                    if(!empty($child_member_id)) {
                        
                        $member_child_list = array(); $prev_member_child_ids = array();
                        $member_child_list = $obj->getTableRecords($GLOBALS['member_child_table'], 'member_id', $child_member_id, '');

                        $target_dir = $obj->image_directory();
                        $target_dir = $target_dir."member_photo/";

                        if(!empty($member_child_details)) {
                            $list = array(); $success = 0;
                            foreach($member_child_details as $data) {
                                $member_child_id = ""; $member_update_id = "";
                                if(!empty($data['member_child_id'])) {
                                    $member_child_id = $data['member_child_id'];
                                }

                                $profile_photo = "";
                                $member_unique_number = ""; 
                                $member_unique_number = $obj->getTableColumnValue($GLOBALS['member_table'], 'member_id', $child_member_id, 'id');
                                if(!empty($member_unique_number) && !empty($data['profile_photo_prefix'])) {
                                    if(!empty($member_unique_number)) {
                                        $target_dir = $target_dir.$member_unique_number."/";
                                    }
                                    $image_name = "child_".$profile_photo_prefix.".webp";
                                    if(file_exists($target_dir.$image_name)) {
                                        $profile_photo = $image_name;

                                        $prev_child_image = "";
                                        $prev_child_image = $obj->getTableColumnValue($GLOBALS['member_child_table'], 'profile_photo_prefix', $profile_photo_prefix, 'profile_photo');
                                        if(!empty($prev_child_image) && file_exists($target_dir.$prev_child_image)) {
                                            unlink($target_dir.$prev_child_image);
                                        }
                                    }
                                }
                                if(empty($profile_photo)) {
                                    $profile_photo = $GLOBALS['null_value'];
                                }

                                $getUniqueID = ""; 
                                $getUniqueID = $obj->getTableColumnValue($GLOBALS['member_child_table'], 'member_child_id', $member_child_id, 'id');
                                if(preg_match("/^\d+$/", $getUniqueID)) {
                                    $action = "";
                                    if(!empty($data['name'])) {
                                        $action = "Member Child Updated. Name - ".$data['name'];
                                    }
                                    $columns = array('creator_name', 'initial', 'name', 'gender', 'birth_date', 'rasi', 'natchathiram', 'aadhaar_number', 'mobile_number', 'education', 'job', 'marriage_status', 'partner_name', 'partner_education', 'partner_date_of_birth', 'marriage_date', 'partner_rasi', 'partner_natchathiram');
                                    $values = array("'".$creator_name."'", "'".$data['initial']."'", "'".$data['name']."'", "'".$data['gender']."'", "'".$data['birth_date']."'", "'".$data['rasi']."'", "'".$data['natchathiram']."'", "'".$data['aadhaar_number']."'", "'".$data['mobile_number']."'", "'".$data['education']."'", "'".$data['job']."'", "'".$data['marriage_status']."'", "'".$data['partner_name']."'", "'".$data['partner_education']."'", "'".$data['partner_date_of_birth']."'", "'".$data['marriage_date']."'", "'".$data['partner_rasi']."'", "'".$data['partner_natchathiram']."'");
                                    $member_update_id = $obj->UpdateSQL($GLOBALS['member_child_table'], $getUniqueID, $columns, $values, $action);
                                }
                                else {
                                    $action = "";
                                    if(!empty($data['name'])) {
                                        $action = "New Member Child Created. Name - ".$data['name'];
                                    }
                                    $null_value = $GLOBALS['null_value'];
                                    $columns = array('created_date_time', 'creator', 'creator_name', 'member_child_id', 'member_id', 'initial', 'name', 'profile_photo_prefix', 'profile_photo', 'gender', 'birth_date', 'rasi', 'natchathiram', 'aadhaar_number', 'mobile_number', 'education', 'job', 'marriage_status', 'partner_name', 'partner_education', 'partner_date_of_birth', 'marriage_date', 'partner_rasi', 'partner_natchathiram', 'deleted');
                                    $values = array("'".$created_date_time."'", "'".$creator."'", "'".$creator_name."'", "'".$null_value."'", "'".$child_member_id."'", "'".$data['initial']."'", "'".$data['name']."'", "'".$data['profile_photo_prefix']."'", "'".$profile_photo."'", "'".$data['gender']."'", "'".$data['birth_date']."'", "'".$data['rasi']."'", "'".$data['natchathiram']."'", "'".$data['aadhaar_number']."'", "'".$data['mobile_number']."'", "'".$data['education']."'", "'".$data['job']."'", "'".$data['marriage_status']."'", "'".$data['partner_name']."'", "'".$data['partner_education']."'", "'".$data['partner_date_of_birth']."'", "'".$data['marriage_date']."'", "'".$data['partner_rasi']."'", "'".$data['partner_natchathiram']."'", "'0'");
                                    $member_update_id = $obj->InsertSQL($GLOBALS['member_child_table'], $columns, $values, 'member_child_id', '', $action);
                                }
                                if(preg_match("/^\d+$/", $member_update_id)) {
                                    $success++;
                                    $member_child_id = $obj->getTableColumnValue($GLOBALS['member_child_table'], 'id', $member_update_id, 'member_child_id');
                                }
                                if(!empty($member_child_id)) {
                                    $data['member_child_id'] = $member_child_id;
                                }
                                $list[] = $data;
                            }
                            $member_child_details = $list;
                            if($success == count($member_child_details)) {
                                $output["head"]["code"] = 200;
                                $output["head"]["msg"] = "Updated Successfully";
                            }
                            else {
                                $output["head"]["code"] = 400;
                                $output["head"]["msg"] = "Update Failure";
                            }
                        }
                        
                        if(!empty($member_child_details)) {
                            $member_child_ids = array();
                            foreach($member_child_details as $data) {
                                if(!empty($data['member_child_id'])) {
                                    $member_child_ids[] = $data['member_child_id'];
                                }
                            }
                            if(!empty($member_child_list) && !empty($member_child_ids)) {
                                foreach($member_child_list as $data) {
                                    if(!empty($data['member_child_id']) && !in_array($data['member_child_id'], $member_child_ids)) {
                                        $member_child_unique_id = "";
                                        $member_child_unique_id = $obj->getTableColumnValue($GLOBALS['member_child_table'], 'member_child_id', $data['member_child_id'], 'id');
                                        if(preg_match("/^\d+$/", $member_child_unique_id)) {
                                            $columns = array(); $values = array();	
                                            $columns = array('deleted');
                                            $values = array("'1'");
                                            $member_child_update_id = $obj->UpdateSQL($GLOBALS['member_child_table'], $member_child_unique_id, $columns, $values, '');
                                        }
                                    }
                                }
                            }
                        }

                    }
                }   
            }
        }
    }
    else {
        if(!empty($valid_member)) {
            $output["head"]["code"] = 400;
            $output["head"]["msg"] = $valid_member;
        }
        else if(!empty($member_child_error)) {
            $output["head"]["code"] = 400;
            $output["head"]["msg"] = $member_child_error;
        }
    }
}

$result = json_encode($output, JSON_NUMERIC_CHECK);

echo $result;
?>
