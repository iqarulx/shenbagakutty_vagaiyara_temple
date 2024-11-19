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
if(isset($json_obj->login_member_id)) {
    $member_id = $json_obj->login_member_id;
    $member_id = trim($member_id);

    $fcm_id = $json_obj->fcm_id;
    $fcm_id = trim($fcm_id);

    if(empty($fcm_id)) { $fcm_id = $GLOBALS['null_value']; }

    $show_receipt_page = false; $member_volunteer_data_list = array();
    if(!empty($member_id)) {

        $member_unique_id = "";
        $member_unique_id = $obj->getTableColumnValue($GLOBALS['member_table'], 'member_id', $member_id, 'id');

        if(preg_match("/^\d+$/", $member_unique_id)) {
            $status_base_member_id = "";
            $status_base_member_id = $obj->getTableColumnValue($GLOBALS['member_table'], 'member_id', $member_id, 'status_base_member_id');

            $action = "";
            if(!empty($status_base_member_id)) {
                $action = "Member FCM ID Updated. Member ID - ".$status_base_member_id.", FCM ID - ".$fcm_id;
            }

            $columns = array(); $values = array();						
            $columns = array('fcm_id');
            $values = array("'".$fcm_id."'");
            $member_update_id = $obj->UpdateSQL($GLOBALS['member_table'], $member_unique_id, $columns, $values, $action);
        }

        $member_volunteer_data_list = $obj->CheckIsMemberVolunteer($member_id);
        if(!empty($member_volunteer_data_list)) {
            foreach($member_volunteer_data_list as $data) {
                if(!empty($data['access_pages'])) {
                    $selected_access_pages = "";
                    $selected_access_pages = explode(",", $data['access_pages']);
                    if(!empty($selected_access_pages)) {
                        foreach($selected_access_pages as $access_page) {
                            if(!empty($access_page)) {
                                $check_receipt_type_unique_id = "";
                                $check_receipt_type_unique_id = $obj->getTableColumnValue($GLOBALS['receipt_type_table'], 'name', $access_page, 'id');
                                if(preg_match("/^\d+$/", $check_receipt_type_unique_id)) {
                                    $show_receipt_page = true;
                                }
                            }
                        }
                    }
                }
            }
        }
        $output["head"]["code"] = 200;
        $output["head"]["receipt_volunteer"] = $show_receipt_page;
    }
}


$result = json_encode($output, JSON_NUMERIC_CHECK);

echo $result;
?>