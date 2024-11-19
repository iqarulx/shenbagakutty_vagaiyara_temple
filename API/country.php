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

if(isset($json_obj->country_listing)){
    $output["head"]["code"] = 200;
    $output["head"]["msg"] = "Success";

    $country_array = array ('Afghanistan','Albania','Algeria','American Samoa','Angola','Anguilla','Antartica','Antigua and Barbuda','Argentina','Armenia','Aruba','Ashmore and Cartier Island','Australia','Austria','Azerbaijan','Bahamas','Bahrain','Bangladesh','Barbados','Belarus','Belgium','Belize','Benin','Bermuda','Bhutan','Bolivia','Bosnia and Herzegovina','Botswana','Brazil','British Virgin Islands','Brunei','Bulgaria','Burkina Faso','Burma','Burundi','Cambodia','Cameroon','Canada','Cape Verde','Cayman Islands','Central African Republic','Chad','Chile','China','Christmas Island','Clipperton Island','Cocos (Keeling) Islands','Colombia','Comoros','Congo, Democratic Republic of the','Congo, Republic of the','Cook Islands','Costa Rica','Cote d\'Ivoire','Croatia','Cuba','Cyprus','Czeck Republic','Denmark','Djibouti','Dominica','Dominican Republic','Ecuador','Egypt','El Salvador','Equatorial Guinea','Eritrea','Estonia','Ethiopia','Europa Island','Falkland Islands (Islas Malvinas)','Faroe Islands','Fiji','Finland','France','French Guiana','French Polynesia','French Southern and Antarctic Lands','Gabon','Gambia, The','Gaza Strip','Georgia','Germany','Ghana','Gibraltar','Glorioso Islands','Greece','Greenland','Grenada','Guadeloupe','Guam','Guatemala','Guernsey','Guinea','Guinea-Bissau','Guyana','Haiti','Heard Island and McDonald Islands','Holy See (Vatican City)','Honduras','Hong Kong','Howland Island','Hungary','Iceland','India','Indonesia','Iran','Iraq','Ireland','Ireland, Northern','Israel','Italy','Jamaica','Jan Mayen','Japan','Jarvis Island','Jersey','Johnston Atoll','Jordan','Juan de Nova Island','Kazakhstan','Kenya','Kiribati','Korea, North','Korea, South','Kuwait','Kyrgyzstan','Laos','Latvia','Lebanon','Lesotho','Liberia','Libya','Liechtenstein','Lithuania','Luxembourg','Macau','Macedonia, Former Yugoslav Republic of','Madagascar','Malawi','Malaysia','Maldives','Mali','Malta','Man, Isle of','Marshall Islands','Martinique','Mauritania','Mauritius','Mayotte','Mexico','Micronesia, Federated States of','Midway Islands','Moldova','Monaco','Mongolia','Montserrat','Morocco','Mozambique','Namibia','Nauru','Nepal','Netherlands','Netherlands Antilles','New Caledonia','New Zealand','Nicaragua','Niger','Nigeria','Niue','Norfolk Island','Northern Mariana Islands','Norway','Oman','Pakistan','Palau','Panama','Papua New Guinea','Paraguay','Peru','Philippines','Pitcaim Islands','Poland','Portugal','Puerto Rico','Qatar','Reunion','Romainia','Russia','Rwanda','Saint Helena','Saint Kitts and Nevis','Saint Lucia','Saint Pierre and Miquelon','Saint Vincent and the Grenadines','Samoa','San Marino','Sao Tome and Principe','Saudi Arabia','Scotland','Senegal','Seychelles','Sierra Leone','Singapore','Slovakia','Slovenia','Solomon Islands','Somalia','South Africa','South Georgia and South Sandwich Islands','Spain','Spratly Islands','Sri Lanka','Sudan','Suriname','Svalbard','Swaziland','Sweden','Switzerland','Syria','Taiwan','Tajikistan','Tanzania','Thailand','Tobago','Toga','Tokelau','Tonga','Trinidad','Tunisia','Turkey','Turkmenistan','Tuvalu','Uganda','Ukraine','United Arab Emirates','United Kingdom','Uruguay','USA','Uzbekistan','Vanuatu','Venezuela','Vietnam','Virgin Islands','Wales','Wallis and Futuna','West Bank','Western Sahara','Yemen','Yugoslavia','Zambia','Zimbabwe');
    $count = 0;
    for($i = 0; $i < count($country_array); $i++){
        $output['body'][$count] = $country_array[$i];
        $count++;  
    }
}

if(isset($json_obj->filter_country_id)){
    $filter_country_id = $json_obj->filter_country_id;
    $filter_country_id = trim($filter_country_id);
    if(!empty($filter_country_id)) {
        $filter_country_id = $filter_country_id + 1;
    }
    $state_array = array();
    if(isset($s_a[$filter_country_id])){
        $state_array = $s_a[$filter_country_id];
    }
    
    $count = 0;

    $output["head"]["code"] = 200;
    $output["head"]["msg"] = "Success";

    if(empty($state_array)){
        $output['body'] = [];
    }
    else{
        for($i = 0; $i < count($state_array); $i++){
            $output['body'][$count] = $state_array[$i];
            $count++;
        }
    }
}

if(isset($json_obj->city_listing)){
    $city_list = array();
    $city_list = $obj->getTableRecords($GLOBALS['city_table'], '', '', '');

    $output["head"]["code"] = 200;
    $output["head"]["msg"] = "Success";

    $count = 0;
    if(!empty($city_list)) {
        foreach($city_list as $data) {
            if(!empty($data['city_name'])) {
                $output["body"][$count] = $data['city_name'];
                $count++;
            }
        }
    }
    else{
        $output['body'] = [];
    }
}

if(isset($json_obj->add_city_name)) {
    $city_name = $json_obj->add_city_name;
    $city_name = trim($city_name);

    $state_name = "";
    $state_name = $json_obj->state_name;
    $state_name = trim($state_name);

    $country_name = "";
    $country_name = $json_obj->country_name;
    $country_name = trim($country_name);

    $msg = "";
    if(!empty($city_name)) {
        $name_array = "";
        $name_array = explode(" ", $city_name);
        if(is_array($name_array)) {
            for($n = 0; $n < count($name_array); $n++) {
                if(!empty($name_array[$n])) {
                    $name_array[$n] = trim($name_array[$n]);
                    $name_array[$n] = strtolower($name_array[$n]);
                    $name_array[$n] = ucfirst($name_array[$n]);
                }
                else {
                    unset($name_array[$n]);
                }
            }
            $city_name = implode(" ", $name_array);
        }

        $created_date_time = $GLOBALS['create_date_time_label']; $creator = $json_obj->creator;
        $creator_name = $obj->encode_decode('encrypt', $json_obj->creator_name);

        $action = "";
        if(!empty($country_name) && !empty($state_name) && !empty($city_name)) {
            $action = "New City Created. City Name - ".$city_name.", State Name - ".$state_name.", Country Name - ".$country_name;
        }

        $city_unique_id = "";
        $city_unique_id = $obj->getTableColumnValue($GLOBALS['city_table'], 'city_name', $city_name, 'id');
        if(preg_match("/^\d+$/", $city_unique_id)) {
            $columns = array(); $values = array();						
            $columns = array('country_name', 'state_name', 'city_name', 'lower_case_name');
            $values = array("'".$country_name."'", "'".$state_name."'", "'".$city_name."'", "'".strtolower($city_name)."'");
            $msg = $obj->UpdateSQL($GLOBALS['city_table'], $city_unique_id, $columns, $values, '');
        }
        else {        
            $columns = array('created_date_time', 'creator', 'creator_name', 'country_name', 'state_name', 'city_name', 'lower_case_name', 'deleted');
            $values = array("'".$created_date_time."'", "'".$creator."'", "'".$creator_name."'", "'".$country_name."'", "'".$state_name."'", "'".$city_name."'", "'".strtolower($city_name)."'", "'0'");
            $msg = $obj->InsertSQL($GLOBALS['city_table'], $columns, $values, '', '', $action);	
        }

        $output["head"]["code"] = 200;
        $output["head"]["msg"] = "City Inserted Successfully";
    }
    else {
        $output["head"]["code"] = 400;
        $output["head"]["msg"] = "City name is empty";
    }
}

$result = json_encode($output, JSON_NUMERIC_CHECK);

echo $result;
?>