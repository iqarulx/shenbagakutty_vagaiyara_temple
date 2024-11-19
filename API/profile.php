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
if(!empty($json_obj->login_member_id)) {

    function searchForId($id, $array) {
        for($i = 0; $i <count($array); $i++){
            if (strpos($array[$i], $id) !== false) {
                return $array[$i];
            }
        }
        
        return null;
    }

    $login_member_id = $json_obj->login_member_id;
    $profession_count = 0;
    $login_from_date = date("d-m-Y"); $login_to_date = "";
    $login_to_date = date('d-m-Y', strtotime('+7 days', strtotime($login_from_date)));

    /*$settings_column_name = $login_member_id."_login_duration";
    $unique_id = "";
    $unique_id = $obj->getTableColumnValue($GLOBALS['settings_table'], 'name', $settings_column_name, 'id');
    if(empty($unique_id)) {
        $login_status_base_member_id = "";
        $login_status_base_member_id = $obj->getTableColumnValue($GLOBALS['member_table'], 'member_id', $login_member_id, 'status_base_member_id');
        $action = "";
        $action = "Member login Duration : Member ID ".$login_status_base_member_id." - From ".$login_from_date." To ".$login_to_date;
    
        $columns = array(); $values = array(); $insert_id = "";
        $columns = array('name', 'value', 'deleted');
        $values = array("'".$settings_column_name."'", "'".$login_from_date."$$$".$login_to_date."'", "'0'");
        $insert_id = $obj->InsertSQL($GLOBALS['settings_table'], $columns, $values, '', '', $action);
    }*/

    $member_id = $login_member_id;
    $member_unique_id = $obj->getTableColumnValue($GLOBALS['member_table'], 'member_id', $login_member_id, 'id');
    if(preg_match("/^\d+$/", $member_unique_id)) {

        $profession_list = array();
        $profession_list = $obj->getTableRecords($GLOBALS['profession_table'], '', '', '');

        $output["head"]['code'] = 200;
        $output['head']['msg'] = "Success";
        
        if(!empty($profession_list)){
            foreach($profession_list as $profession){
                $profession_name = ""; $profession_id = "";
                if(!empty($profession['name'])){
                    $profession_name = $obj->encode_decode('decrypt', $profession['name']);
                }
                if(!empty($profession['profession_id'])){
                    $profession_id = $profession['profession_id'];
                }
                $output['body']['profession'][$profession_count]['profession_id'] = $profession_id;
                $output['body']['profession'][$profession_count]['name'] = $profession_name;
                $profession_count++;
            }
        }

        $member_list = array();
        $member_list = $obj->getTableRecords($GLOBALS['member_table'], 'member_id', $login_member_id, '');
        if(!empty($member_list)) {
            $password = ""; $name = ""; $initial = ""; $date_of_birth = ""; $rasi = ""; $natchathiram = ""; $profile_photo = ""; $family_photo = "";
            $profession = ""; $wife_name = ""; $wife_education = ""; $wife_date_of_birth = ""; $wife_rasi = ""; $wife_natchathiram = ""; $marriage_date = "";
            $father_id = ""; $father_name = ""; $family_order = ""; $phone_number = ""; $mobile_number = ""; $aadhaar_number = ""; $introducer_id = "";
            $introducer_relationship = "";

            $address = ""; $city = ""; $pincode = ""; $state = ""; $country = ""; $district = "";

            $status_id = ""; $date_of_joining = ""; $date_of_deletion = ""; $date_of_rejoin = ""; $company_name = ""; $remarks = ""; $member_history = "";
            $member_profile_photo = ""; $member_family_photos = ""; $member_wife_photo = "";
            
            $status_base_member_id = ""; $member_unique_number = ""; $fcm_id = "";
            
            foreach($member_list as $data) {
                if(!empty($data['member_unique_number'])) {
                    $member_unique_number = $data['member_unique_number'];
                }
                if(!empty($data['password'])) {
                    $password = $data['password'];
                }
                if(!empty($data['status_base_member_id'])) {
                    $status_base_member_id = $data['status_base_member_id'];
                }
                if(!empty($data['name'])) {
                    $name = $data['name'];
                }
                if(!empty($data['initial']) && $data['initial'] != $GLOBALS['null_value']) {
                    $initial = $data['initial'];
                }
                if(!empty($data['date_of_birth']) && $data['date_of_birth'] != "0000-00-00") {
                    $date_of_birth = date("d-m-Y", strtotime($data['date_of_birth']));
                }
                if(!empty($data['rasi']) && $data['rasi'] != $GLOBALS['null_value']) {
                    $rasi = $data['rasi'];
                }
                if(!empty($data['natchathiram']) && $data['natchathiram'] != $GLOBALS['null_value']) {
                    $natchathiram = $data['natchathiram'];
                }
                if(!empty($data['profile_photo']) && $data['profile_photo'] != $GLOBALS['null_value']) {
                    $member_profile_photo = $data['profile_photo'];
                }
                if(!empty($data['wife_photo']) && $data['wife_photo'] != $GLOBALS['null_value']) {
                    $member_wife_photo = $data['wife_photo'];
                }
                if(!empty($data['family_photo']) && $data['family_photo'] != $GLOBALS['null_value']) {
                    $member_family_photos = $data['family_photo'];
                }
                if(!empty($data['profession']) && $data['profession'] != $GLOBALS['null_value']) {
                    $profession = $data['profession'];
                }
                if(!empty($data['wife_name']) && $data['wife_name'] != $GLOBALS['null_value']) {
                    $wife_name = $data['wife_name'];
                }
                if(!empty($data['wife_education']) && $data['wife_education'] != $GLOBALS['null_value']) {
                    $wife_education = $data['wife_education'];
                }
                if(!empty($data['wife_date_of_birth']) && $data['wife_date_of_birth'] != "0000-00-00") {
                    $wife_date_of_birth = date("d-m-Y", strtotime($data['wife_date_of_birth']));
                }
                if(!empty($data['wife_rasi']) && $data['wife_rasi'] != $GLOBALS['null_value']) {
                    $wife_rasi = $data['wife_rasi'];
                }
                if(!empty($data['wife_natchathiram']) && $data['wife_natchathiram'] != $GLOBALS['null_value']) {
                    $wife_natchathiram = $data['wife_natchathiram'];
                }
                if(!empty($data['marriage_date']) && $data['marriage_date'] != "0000-00-00") {
                    $marriage_date = date("d-m-Y", strtotime($data['marriage_date']));
                }
                if(!empty($data['father_id']) && $data['father_id'] != $GLOBALS['null_value']) {
                    $father_id = $data['father_id'];
                }
                if(!empty($data['family_order'])) {
                    $family_order = $data['family_order'];
                }
                if(!empty($data['phone_number']) && $data['phone_number'] != $GLOBALS['null_value']) {
                    $phone_number = $data['phone_number'];
                }
                if(!empty($data['mobile_number']) && $data['mobile_number'] != $GLOBALS['null_value']) {
                    $mobile_number = $data['mobile_number'];
                }
                if(!empty($data['aadhaar_number']) && $data['aadhaar_number'] != $GLOBALS['null_value']) {
                    $aadhaar_number = $data['aadhaar_number'];
                }
                if(!empty($data['introducer_id']) && $data['introducer_id'] != $GLOBALS['null_value']) {
                    $introducer_id = $data['introducer_id'];
                }
                if(!empty($data['introducer_relationship']) && $data['introducer_relationship'] != $GLOBALS['null_value']) {
                    $introducer_relationship = $data['introducer_relationship'];
                }

                if(!empty($data['address']) && $data['address'] != $GLOBALS['null_value']) {
                    $address = $data['address'];
                }
                if(!empty($data['city'])) {
                    $city = $data['city'];
                }
                if(!empty($data['district'])) {
                    $district = $data['district'];
                }
                if(!empty($data['pincode']) && $data['pincode'] != $GLOBALS['null_value']) {
                    $pincode = $data['pincode'];
                }
                if(!empty($data['state']) && $data['state'] != $GLOBALS['null_value']) {
                    $state = $data['state'];
                }
                if(!empty($data['country']) && $data['country'] != $GLOBALS['null_value']) {
                    $country = $data['country'];
                }

                if(!empty($data['status_id'])) {
                    $status_id = $data['status_id'];
                }
                if(!empty($data['company_name']) && $data['company_name'] != $GLOBALS['null_value']) {
                    $company_name = $data['company_name'];
                }
                if(!empty($data['remarks']) && $data['remarks'] != $GLOBALS['null_value']) {
                    $remarks = $data['remarks'];
                }
                if(!empty($data['member_history']) && $data['member_history'] != $GLOBALS['null_value']) {
                    $member_history = $data['member_history'];
                }

                if(!empty($data['date_of_joining']) && $data['date_of_joining'] != "0000-00-00") {
                    $date_of_joining = date("d-m-Y", strtotime($data['date_of_joining']));
                }
                if(!empty($data['date_of_deletion']) && $data['date_of_deletion'] != "0000-00-00") {
                    $date_of_deletion = date("d-m-Y", strtotime($data['date_of_deletion']));
                }
                if(!empty($data['date_of_rejoin']) && $data['date_of_rejoin'] != "0000-00-00") {
                    $date_of_rejoin = date("d-m-Y", strtotime($data['date_of_rejoin']));
                }
                if(!empty($data['fcm_id']) && $data['fcm_id'] != $GLOBALS['null_value']) {
                    $fcm_id = $data['fcm_id'];
                }
            }

            $father_initial = ""; $father_name = ""; $get_father_name_by_id = "";
            if(!empty($father_id)) {
                $get_father_name_by_id = $father_id;
                if(!empty($get_father_name_by_id)) {
                    //$get_father_name_by_id = $obj->encode_decode('encrypt', $get_father_name_by_id);
                    $father_initial = $obj->getTableColumnValue($GLOBALS['member_table'], 'member_unique_number', $get_father_name_by_id, 'initial');
                    $father_name = $obj->getTableColumnValue($GLOBALS['member_table'], 'member_unique_number', $get_father_name_by_id, 'name');
                    if(!empty($father_name)) {
                        //$father_name = $obj->encode_decode('decrypt', $father_name);
                        if(!empty($father_initial) && $father_initial != $GLOBALS['null_value']) {
                            //$father_initial = $obj->encode_decode('decrypt', $father_initial);
                            $father_name = $father_initial.' . '.$father_name;
                        }
                    }
                }
            }

            $introducer_initial = ""; $introducer_name = ""; $get_introducer_name_by_id = "";
            if(!empty($introducer_id)) {
                $get_introducer_name_by_id = $introducer_id;
                if(!empty($get_introducer_name_by_id)) {
                    //$get_introducer_name_by_id = $obj->encode_decode('encrypt', $get_introducer_name_by_id);
                    $introducer_initial = $obj->getTableColumnValue($GLOBALS['member_table'], 'member_unique_number', $get_introducer_name_by_id, 'initial');
                    $introducer_name = $obj->getTableColumnValue($GLOBALS['member_table'], 'member_unique_number', $get_introducer_name_by_id, 'name');
                    if(!empty($introducer_name)) {
                        //$introducer_name = $obj->encode_decode('decrypt', $introducer_name);
                        if(!empty($introducer_initial) && $introducer_initial != $GLOBALS['null_value']) {
                            //$introducer_initial = $obj->encode_decode('decrypt', $introducer_initial);
                            $introducer_name = $introducer_initial.$introducer_name;
                        }
                    }
                }
            }

            if(!empty($status_id)){
                $status_name = $obj->getTableColumnValue($GLOBALS['status_table'], 'status_id', $status_id, 'name');
                $status_name = $obj->encode_decode('decrypt', $status_name);
            }
            
            
            $output['body']['status_base_member_id'] = $status_base_member_id;
            $output['body']['password'] = $password;
            $output['body']['name'] = $name;
            $output['body']['initial'] = $initial;
            $output['body']['date_of_birth'] = $date_of_birth;
            $output['body']['rasi'] = $rasi;
            $output['body']['natchathiram'] = $natchathiram;
            $output['body']['profession_id'] = $profession;
            $output['body']['wife_name'] = $wife_name;
            $output['body']['wife_education'] = $wife_education;
            $output['body']['wife_date_of_birth'] = $wife_date_of_birth;
            $output['body']['wife_rasi'] = $wife_rasi;
            $output['body']['wife_natchathiram'] = $wife_natchathiram;
            $output['body']['marriage_date'] = $marriage_date;
            $output['body']['father_id'] = $father_id;
            $output['body']['father_name'] = $father_name;
            $output['body']['family_order'] = $family_order;
            $output['body']['phone_number'] = $phone_number;
            $output['body']['mobile_number'] = $mobile_number;
            $output['body']['aadhaar_number'] = $aadhaar_number;
            $output['body']['introducer_id'] = $introducer_id;
            $output['body']['introducer_name'] = $introducer_name;
            $output['body']['introducer_relationship'] = $introducer_relationship;
            $output['body']['address'] = $address;
            $output['body']['city'] = $city;
            $output['body']['district'] = $district;
            $output['body']['pincode'] = $pincode;
            $output['body']['state'] = $state;
            $output['body']['country'] = $country;

            $country_index = "";
            $country_array = array ('Afghanistan','Albania','Algeria','American Samoa','Angola','Anguilla','Antartica','Antigua and Barbuda','Argentina','Armenia','Aruba','Ashmore and Cartier Island','Australia','Austria','Azerbaijan','Bahamas','Bahrain','Bangladesh','Barbados','Belarus','Belgium','Belize','Benin','Bermuda','Bhutan','Bolivia','Bosnia and Herzegovina','Botswana','Brazil','British Virgin Islands','Brunei','Bulgaria','Burkina Faso','Burma','Burundi','Cambodia','Cameroon','Canada','Cape Verde','Cayman Islands','Central African Republic','Chad','Chile','China','Christmas Island','Clipperton Island','Cocos (Keeling) Islands','Colombia','Comoros','Congo, Democratic Republic of the','Congo, Republic of the','Cook Islands','Costa Rica','Cote d\'Ivoire','Croatia','Cuba','Cyprus','Czeck Republic','Denmark','Djibouti','Dominica','Dominican Republic','Ecuador','Egypt','El Salvador','Equatorial Guinea','Eritrea','Estonia','Ethiopia','Europa Island','Falkland Islands (Islas Malvinas)','Faroe Islands','Fiji','Finland','France','French Guiana','French Polynesia','French Southern and Antarctic Lands','Gabon','Gambia, The','Gaza Strip','Georgia','Germany','Ghana','Gibraltar','Glorioso Islands','Greece','Greenland','Grenada','Guadeloupe','Guam','Guatemala','Guernsey','Guinea','Guinea-Bissau','Guyana','Haiti','Heard Island and McDonald Islands','Holy See (Vatican City)','Honduras','Hong Kong','Howland Island','Hungary','Iceland','India','Indonesia','Iran','Iraq','Ireland','Ireland, Northern','Israel','Italy','Jamaica','Jan Mayen','Japan','Jarvis Island','Jersey','Johnston Atoll','Jordan','Juan de Nova Island','Kazakhstan','Kenya','Kiribati','Korea, North','Korea, South','Kuwait','Kyrgyzstan','Laos','Latvia','Lebanon','Lesotho','Liberia','Libya','Liechtenstein','Lithuania','Luxembourg','Macau','Macedonia, Former Yugoslav Republic of','Madagascar','Malawi','Malaysia','Maldives','Mali','Malta','Man, Isle of','Marshall Islands','Martinique','Mauritania','Mauritius','Mayotte','Mexico','Micronesia, Federated States of','Midway Islands','Moldova','Monaco','Mongolia','Montserrat','Morocco','Mozambique','Namibia','Nauru','Nepal','Netherlands','Netherlands Antilles','New Caledonia','New Zealand','Nicaragua','Niger','Nigeria','Niue','Norfolk Island','Northern Mariana Islands','Norway','Oman','Pakistan','Palau','Panama','Papua New Guinea','Paraguay','Peru','Philippines','Pitcaim Islands','Poland','Portugal','Puerto Rico','Qatar','Reunion','Romainia','Russia','Rwanda','Saint Helena','Saint Kitts and Nevis','Saint Lucia','Saint Pierre and Miquelon','Saint Vincent and the Grenadines','Samoa','San Marino','Sao Tome and Principe','Saudi Arabia','Scotland','Senegal','Seychelles','Sierra Leone','Singapore','Slovakia','Slovenia','Solomon Islands','Somalia','South Africa','South Georgia and South Sandwich Islands','Spain','Spratly Islands','Sri Lanka','Sudan','Suriname','Svalbard','Swaziland','Sweden','Switzerland','Syria','Taiwan','Tajikistan','Tanzania','Thailand','Tobago','Toga','Tokelau','Tonga','Trinidad','Tunisia','Turkey','Turkmenistan','Tuvalu','Uganda','Ukraine','United Arab Emirates','United Kingdom','Uruguay','USA','Uzbekistan','Vanuatu','Venezuela','Vietnam','Virgin Islands','Wales','Wallis and Futuna','West Bank','Western Sahara','Yemen','Yugoslavia','Zambia','Zimbabwe');
            if(!empty($country_array) && !empty($country)) {
                for($i = 0; $i < count($country_array); $i++) {
                    if(!empty($country_array[$i]) && $country_array[$i] == $country) {
                        $country_index = $i;
                    }
                    if(!empty($country_index)) { break; }
                }
                $output['body']['country_index'] = $country_index;
                if(!empty($country_index)){
                    $output['body']['country'] = $country_index.','.$country;
                }
                else{
                    $output['body']['country'] = $country;
                }
            }

            $output['body']['status_id'] = $status_id;
            $output['body']['status_name'] = $status_name;
            $output['body']['company_name'] = $company_name;
            $output['body']['remarks'] = $remarks;
            $output['body']['member_history'] = $member_history;
            $output['body']['date_of_joining'] = $date_of_joining;
            $output['body']['date_of_deletion'] = $date_of_deletion;
            $output['body']['date_of_rejoin'] = $date_of_rejoin;
            $output['body']['fcm_id'] = $fcm_id;
            
            $common_profile_photo = "";

            if(!empty($member_photo_location)) {
                $member_photo_location = $member_photo_location.$member_unique_number."/";
            }

            if(!empty($member_profile_photo) && file_exists($member_photo_location.$member_profile_photo)){
                $output["body"]["member_profile_photo"] = 'http://sridemoapps.in/mahendran2022/temple/'.str_replace("../", "", $member_photo_location).$member_profile_photo;
            }else{
                $output["body"]["member_profile_photo"] = "";
            }

            if(!empty($member_wife_photo) && file_exists($member_photo_location.$member_wife_photo)){
                $output["body"]["member_wife_photo"] = 'http://sridemoapps.in/mahendran2022/temple/'.str_replace("../", "", $member_photo_location).$member_wife_photo;
            }else{
                $output["body"]["member_wife_photo"] = "";
            }

            if(!empty($member_family_photos)){
                $member_family_photos = explode(",", $member_family_photos);
                foreach($member_family_photos as $photo) {
                    if(!empty($photo) && file_exists($member_photo_location.$photo)) {
                        $family_photo_name = "";
                        $family_photo_name = str_replace("../", "", $member_photo_location).$photo;
                        $output["body"]["member_family_photos"][] = 'http://sridemoapps.in/mahendran2022/temple/'.$family_photo_name;
                    }
                    else{
                        $output["body"]["member_family_photos"] = [];
                    }
                }
            }else{
                $output["body"]["member_family_photos"] = [];
            }

            $output["body"]["member_common_photos"] = [];
            $output["body"]["member_signature"] = '';

            if(!empty($login_member_id)) {
                $member_child_list = array();
                $member_child_list = $obj->getTableRecords($GLOBALS['member_child_table'], 'member_id', $login_member_id, 'ASC');
                if(!empty($member_child_list)) {
                    $child_count = 0;
                    foreach($member_child_list as $key => $child1) {
                        if(!empty($child1['member_child_id'])) {
                            $member_child_id = ""; $member_child_initial = ""; $member_child_name = ""; $member_child_profile_photo = "";
                            $member_child_gender = ""; $member_child_birth_date = ""; $member_child_rasi = ""; $member_child_natchathiram = "";
                            $member_child_aadhaar_number = ""; $member_child_mobile_number = ""; $member_child_education = ""; $member_child_job = "";
                            $member_child_marriage_status = ""; $member_child_partner_name = ""; $member_child_partner_education = "";
                            $member_child_partner_birth_date = ""; $member_child_marriage_date = ""; $member_child_partner_rasi = "";
                            $member_child_partner_natchathiram = "";

                            $div_id = ($key + 1)."_".strtotime(date("d-m-Y H:i:s"));
                            if(!empty($child1['member_child_id']) && $child1['member_child_id'] != $GLOBALS['null_value']) {
                                $member_child_id = $child1['member_child_id'];
                            }
                            if(!empty($child1['initial']) && $child1['initial'] != $GLOBALS['null_value']) {
                                $member_child_initial = $child1['initial'];
                            }
                            if(!empty($child1['name'])) {
                                $member_child_name = $child1['name'];
                            }
                            if(!empty($child1['profile_photo']) && $child1['profile_photo'] != $GLOBALS['null_value']) {
                                $member_child_profile_photo = $child1['profile_photo'];
                            }
                            if(!empty($child1['gender']) && $child1['gender'] != $GLOBALS['null_value']) {
                                $member_child_gender = $child1['gender'];
                            }
                            if(!empty($child1['birth_date']) && $child1['birth_date'] != "0000-00-00") {
                                $member_child_birth_date = date("d-m-Y", strtotime($child1['birth_date']));
                            }
                            if(!empty($child1['rasi']) && $child1['rasi'] != $GLOBALS['null_value']) {
                                $member_child_rasi = $child1['rasi'];
                            }
                            if(!empty($child1['natchathiram']) && $child1['natchathiram'] != $GLOBALS['null_value']) {
                                $member_child_natchathiram = $child1['natchathiram'];
                            }

                            if(!empty($child1['aadhaar_number']) && $child1['aadhaar_number'] != $GLOBALS['null_value']) {
                                $member_child_aadhaar_number = $child1['aadhaar_number'];
                            }
                            if(!empty($child1['mobile_number']) && $child1['mobile_number'] != $GLOBALS['null_value']) {
                                $member_child_mobile_number = $child1['mobile_number'];
                            }
                            if(!empty($child1['education']) && $child1['education'] != $GLOBALS['null_value']) {
                                $member_child_education = $child1['education'];
                            }
                            if(!empty($child1['job']) && $child1['job'] != $GLOBALS['null_value']) {
                                $member_child_job = $child1['job'];
                            }
                            if(!empty($child1['marriage_status'])) {
                                $member_child_marriage_status = $child1['marriage_status'];
                            }

                            if(!empty($child1['partner_name']) && $child1['partner_name'] != $GLOBALS['null_value']) {
                                $member_child_partner_name = $child1['partner_name'];
                            }
                            if(!empty($child1['partner_education']) && $child1['partner_education'] != $GLOBALS['null_value']) {
                                $member_child_partner_education = $child1['partner_education'];
                            }
                            if(!empty($child1['partner_date_of_birth']) && $child1['partner_date_of_birth'] != "0000-00-00") {
                                $member_child_partner_birth_date = date("d-m-Y", strtotime($child1['partner_date_of_birth']));
                            }
                            if(!empty($child1['marriage_date']) && $child1['marriage_date'] != "0000-00-00") {
                                $member_child_marriage_date = date("d-m-Y", strtotime($child1['marriage_date']));
                            }
                            if(!empty($child1['partner_rasi']) && $child1['partner_rasi'] != $GLOBALS['null_value']) {
                                $member_child_partner_rasi = $child1['partner_rasi'];
                            }
                            if(!empty($child1['partner_natchathiram']) && $child1['partner_natchathiram'] != $GLOBALS['null_value']) {
                                $member_child_partner_natchathiram = $child1['partner_natchathiram'];
                            }

                            $profile_photo = "";
                            if(!empty($child1['profile_photo']) && $child1['profile_photo'] != $GLOBALS['null_value']) {
                                $profile_photo = $child1['profile_photo'];
                            }

                            
                            $output['body']['children'][$child_count]['member_child_id'] = $member_child_id;
                            $output['body']['children'][$child_count]['member_child_initial'] = $member_child_initial;
                            $output['body']['children'][$child_count]['member_child_name'] = $member_child_name;
                            $output['body']['children'][$child_count]['member_child_gender'] = $member_child_gender;
                            $output['body']['children'][$child_count]['member_child_birth_date'] = $member_child_birth_date;
                            $output['body']['children'][$child_count]['member_child_rasi'] = $member_child_rasi;
                            $output['body']['children'][$child_count]['member_child_natchathiram'] = $member_child_natchathiram;
                            $output['body']['children'][$child_count]['member_child_aadhaar_number'] = $member_child_aadhaar_number;
                            $output['body']['children'][$child_count]['member_child_mobile_number'] = $member_child_mobile_number;
                            $output['body']['children'][$child_count]['member_child_education'] = $member_child_education;
                            $output['body']['children'][$child_count]['member_child_job'] = $member_child_job;
                            $output['body']['children'][$child_count]['member_child_marriage_status'] = $member_child_marriage_status;
                            $output['body']['children'][$child_count]['member_child_partner_name'] = $member_child_partner_name;
                            $output['body']['children'][$child_count]['member_child_partner_education'] = $member_child_partner_education;
                            $output['body']['children'][$child_count]['member_child_partner_birth_date'] = $member_child_partner_birth_date;
                            $output['body']['children'][$child_count]['member_child_marriage_date'] = $member_child_marriage_date;
                            $output['body']['children'][$child_count]['member_child_partner_rasi'] = $member_child_partner_rasi;
                            $output['body']['children'][$child_count]['member_child_partner_natchathiram'] = $member_child_partner_natchathiram;
                            
                            if(!empty($member_child_profile_photo) && file_exists($member_photo_location.$member_child_profile_photo)) {
                                $output["body"]['children'][$child_count]["member_child_profile_photo"] = 'http://sridemoapps.in/mahendran2022/temple/'.str_replace("../", "", $member_photo_location).$member_child_profile_photo;
                            }
                            else {
                                $output["body"]['children'][$child_count]["member_child_profile_photo"] = '';
                            }
                            $child_count++;
                        }
                    }
                }
                else{
                    $output['body']['children'] = [];
                }
            }

        }
        else{
            $output["head"]["code"] = 400;
            $output["head"]["msg"] = 'Invalid Member ID!';
        }
    }
    else{
        $output["head"]['code'] = 400;
        $output['head']['msg'] = "Invalid Member";
    }
}

if(isset($json_obj->profession_name)){
    $name = $json_obj->profession_name;
    $name = trim($name);
    $name_error = $valid->common_validation($name, "Name", 'text');
    if(!empty($name_error)) {
        $valid_profession = $valid->error_display($form_name, "name", $name_error, 'text');
    }
    
    $result = "";
    
    if(empty($valid_profession)) {
        $lowercase_name = "";
        if(!empty($name)) {
            $lowercase_name = strtolower($name);
            $lowercase_name = $obj->encode_decode('encrypt', $lowercase_name);
            $name = $obj->encode_decode('encrypt', $name);
        }

        $prev_profession_id = ""; $profession_error = "";
        if(!empty($lowercase_name)) {
            $prev_profession_id = $obj->getTableColumnValue($GLOBALS['profession_table'], 'lowercase_name', $lowercase_name, 'profession_id');
            if(!empty($prev_profession_id)) {
                $profession_error = "This profession name is already exist";
            }
        }

        $created_date_time = $GLOBALS['create_date_time_label']; $creator = $json_obj->creator;
        $creator_name = $obj->encode_decode('encrypt', $json_obj->creator_name);
        
        if(empty($prev_profession_id)) {
            $action = "";
            if(!empty($name)) {
                $action = "New Profession Created. Name - ".$obj->encode_decode('decrypt', $name);
            }

            $null_value = $GLOBALS['null_value'];
            $columns = array('created_date_time', 'creator', 'creator_name', 'profession_id', 'name', 'lowercase_name', 'deleted');
            $values = array("'".$created_date_time."'", "'".$creator."'", "'".$creator_name."'", "'".$null_value."'", "'".$name."'", "'".$lowercase_name."'", "'0'");
            $insert_id = $obj->InsertSQL($GLOBALS['profession_table'], $columns, $values, 'profession_id', '', $action);
            if(preg_match("/^\d+$/", $insert_id)) {
                $profession_id = $obj->getTableColumnValue($GLOBALS['profession_table'], 'lowercase_name', $lowercase_name, 'profession_id');
                $output["head"]["code"] = 200;
                $output["head"]["msg"] = 'Profession Successfully Created';
                $output["head"]["profession_id"] = $profession_id;
            }
            else {
                $output["head"]["code"] = 400;
                $output["head"]["msg"] = $insert_id;
            }
        }
        else {
            $output["head"]["code"] = 400;
                $output["head"]["msg"] = $profession_error;
        }
    }
    else {
        $output["head"]["code"] = 400;
        $output["head"]["msg"] = $valid_profession;
    }
}

$result = json_encode($output, JSON_NUMERIC_CHECK);

echo $result;
?>
