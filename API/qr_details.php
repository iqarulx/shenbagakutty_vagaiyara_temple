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
if(!empty($json_obj->qr_member_id)) {
    $qr_member_id = "";
    $qr_member_id = $json_obj->qr_member_id;
    $member_details = array();
    $member_details = $obj->getTableRecords($GLOBALS['member_table'], 'member_id', $qr_member_id,'');

    if(!empty($member_details)){
        foreach($member_details as $member){
            $member_name = ""; $status_base_member_id = ""; $father_name = ""; $mobile_number = ""; $father_id = "";
            if(!empty($member['name'])){
                $member_name = $member['name'];
            }
            if(!empty($member['father_id']) && $member['father_id'] != $GLOBALS['null_value']){
                $father_id = $member['father_id'];
            }
            if(!empty($member['status_base_member_id'])){
                $status_base_member_id = $member['status_base_member_id'];
            }
            if(!empty($member['mobile_number'])){
                $mobile_number = $member['mobile_number'];
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
                            $father_name = $father_initial.$father_name;					
                        }             
                    }
                }
            }
        }
        $output["head"]['code'] = 200;
        $output['head']['msg'] = "Success";

        $output['body']['member_id'] = $status_base_member_id;
        $output['body']['member_name'] = $member_name;
        $output['body']['mobile_number'] = $mobile_number;
        $output['body']['father_name'] = $father_name;
    }
    else{
        $output["head"]['code'] = 400;
        $output['head']['msg'] = "Invalid Member";
    }
}

$result = json_encode($output, JSON_NUMERIC_CHECK);

echo $result;
?>