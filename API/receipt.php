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

$receipt_category_options_list = $GLOBALS['receipt_type_options'];

if(isset($json_obj->search_text)){
    $from_date = "";
    if(!empty($json_obj->from_date)) {
        $from_date = $json_obj->from_date;
    }
    $to_date = "";
    if(!empty($json_obj->to_date)) {
        $to_date = $json_obj->to_date;
    }
    $receipt_category_id = "";
    if(!empty($json_obj->receipt_category_id)) {
        $receipt_category_id = $json_obj->receipt_category_id;
    }
    $receipt_type_id = "";
    if(!empty($json_obj->receipt_type_id)) {
        $receipt_type_id = $json_obj->receipt_type_id;
    }
    $member_id = "";
    if(!empty($json_obj->member_id)) {
        $member_id = $json_obj->member_id;
    }
    $non_member_name = "";
    if(!empty($json_obj->non_member_name)) {
        $non_member_name = $json_obj->non_member_name;
    }
    $login_member_id = "";
    if(!empty($json_obj->login_member_id)) {
        $login_member_id = $json_obj->login_member_id;
    }
    $receipt_creator = "";
    if(!empty($json_obj->receipt_creator)) {
        $receipt_creator = $json_obj->receipt_creator;
    }
    $search_text = "";
    if(!empty($json_obj->search_text)) {
        $search_text = $json_obj->search_text;
    }
    $page_number = "";
    if(!empty($json_obj->page_number)) {
        $page_number = $json_obj->page_number;
    }
    $page_limit = "";
    if(!empty($json_obj->page_limit)) {
        $page_limit = $json_obj->page_limit;
    }

    if(!empty($login_member_id)) {
        $member_list = array(); $creator_name = "";
        $member_list = $obj->getTableRecords($GLOBALS['member_table'], 'member_id', $login_member_id, '');
        if(!empty($member_list)) {
            foreach($member_list as $data) {
                if(!empty($data['status_base_member_id'])) {
                    $creator_name = $data['status_base_member_id'];
                    if(!empty($data['name'])) {
                        $creator_name = $creator_name." - ".$data['name'];
                    }
                }
            }
        }
        $receipt_creator = $obj->encode_decode('encrypt', $creator_name);
    }

    $listing_page_members = array(); $create_page_members = array();
    $listing_page_members = $obj->getReceiptMembersList($login_member_id);
    $create_page_members = $obj->getReceiptMembersList('');
    
    $non_members_list = array();
    $non_members_list = $obj->getReceiptNonMembersList($login_member_id);

    $receipt_count = 0;
    $receipt_count = $obj->getTotalAllReceiptCount($from_date, $to_date, $receipt_category_id, $receipt_type_id, $member_id, $non_member_name, $receipt_creator, $search_text);

    $page_start = 0; $page_end = 0;
    if(!empty($page_number) && !empty($page_limit) && !empty($receipt_count)) {
        if($receipt_count > $page_limit) {
            if($page_number) {
                $page_start = ($page_number - 1) * $page_limit;
                $page_end = $page_start + $page_limit;
            }
        }
        else {
            $page_start = 0;
            $page_end = $page_limit;
        }
    }

    $total_records_list = array();
    $total_records_list = $obj->getAllReceiptList($from_date, $to_date, $receipt_category_id, $receipt_type_id, $member_id, $non_member_name, $receipt_creator, $search_text, $page_start, $page_end, 'DESC', '');
    if(!empty($receipt_count)) {
        $output["head"]["code"] = 200;
        $output["head"]["msg"] = "Success";
    }
    else {
        $output["head"]["code"] = 400;
        $output["head"]["msg"] = "Sorry! No receipts found";
    }

    $output["head"]['receipt_count'] = $receipt_count;
    
    if(!empty($listing_page_members)) {
        $i = 0;
        foreach($listing_page_members as $data) {
            $output["head"]['listing_member'][$i]['name'] = $data['status_base_member_id']." - ".$data['name'];
            $output["head"]['listing_member'][$i]['id'] = $data['member_id'];
            $mobile_number = "";
            if(!empty($data['mobile_number']) && $data['mobile_number'] != "NULL") {
                $mobile_number = $data['mobile_number'];
            }
            $output["head"]['listing_member'][$i]['mobile_number'] = $mobile_number;
            $i++;
        }
    }
    if(!empty($create_page_members)) {
        $i = 0;
        foreach($create_page_members as $data) {
            $output["head"]['create_member'][$i]['name'] = $data['status_base_member_id']." - ".$data['name'];
            $output["head"]['create_member'][$i]['id'] = $data['member_id'];
            $mobile_number = "";
            if(!empty($data['mobile_number']) && $data['mobile_number'] != "NULL") {
                $mobile_number = $data['mobile_number'];
            }
            $output["head"]['create_member'][$i]['mobile_number'] = $mobile_number;
            $i++;
        }
    }

    if(!empty($non_members_list)) {
        $i = 0;
        foreach($non_members_list as $non_member_name) {
            if(!empty($non_member_name)) {
                $output["head"]['non_member'][$i]['name'] = $non_member_name;
                $output["head"]['non_member'][$i]['id'] = $obj->encode_decode('encrypt', $non_member_name);
                $i++;
            }
        }
    }
    else {
        $output["head"]['non_member'] = [];
    }

    if(!empty($receipt_category_options_list)) {
        $i = 0;
        foreach($receipt_category_options_list as $receipt_category) {
            if(!empty($receipt_category)) {
                $receipt_category_encrypted = $obj->encode_decode('encrypt', $receipt_category);
                $output["head"]['receipt_category'][$i]['name'] = $receipt_category;
                $output["head"]['receipt_category'][$i]['id'] = $receipt_category_encrypted;

                $category_receipt_type_list = array(); $receipt_types = array();
                $category_receipt_type_list = $obj->getTableRecords($GLOBALS['receipt_type_table'], 'type', $receipt_category_encrypted, 'ASC');
                if(!empty($category_receipt_type_list)) {
                    $j = 0;
                    foreach($category_receipt_type_list as $data) {
                        if(!empty($data['receipt_type_id']) && !empty($data['name'])) {
                            $output["head"]['receipt_category'][$i]['category_receipt_type'][$j]['name'] = $obj->encode_decode('decrypt', $data['name']);
                            $output["head"]['receipt_category'][$i]['category_receipt_type'][$j]['id'] = $data['receipt_type_id'];
                            $j++;
                        }
                    }
                }

                $i++;
            }
        }
    }

    $receipt_type_id = ""; $receipt_type_name = ""; $count = 0;
    $receipt_type_list = array();
    $receipt_type_list = $obj->getTableRecords($GLOBALS['receipt_type_table'], '', '', '');
    foreach($receipt_type_list as $rtype){
        if(!empty($rtype['receipt_type_id'])){
            $receipt_type_id = $rtype['receipt_type_id'];
        }
        if(!empty($rtype['receipt_type_name'])){
            $receipt_type_name = $rtype['receipt_type_name'];
        }
        $output["head"]['receipt_type'][$count]['automatic_id'] = $rtype['id'];
        $output["head"]['receipt_type'][$count]['receipt_type_id'] = $rtype['receipt_type_id'];
        if(!empty($rtype['name'])){
            $rtype['name'] = $obj->encode_decode('decrypt', $rtype['name']);
        }
        $output["head"]['receipt_type'][$count]['receipt_type_name'] = $rtype['name'];

        // new thalakattu 
        if($rtype['id'] == 1) {
            $output["head"]['receipt_type'][$count]['inputs']['0'] = 'Member';
            $output["head"]['receipt_type'][$count]['inputs']['1'] = 'Year Amount';
            $output["head"]['receipt_type'][$count]['inputs']['2'] = 'Poojai From Date';
            $output["head"]['receipt_type'][$count]['inputs']['3'] = 'Poojai To Date';
            $output["head"]['receipt_type'][$count]['inputs']['4'] = 'Poojai Amount';
        }

        // old thalakattu 
        if($rtype['id'] == 2) {
            $output["head"]['receipt_type'][$count]['inputs']['0'] = 'Member';
            $output["head"]['receipt_type'][$count]['inputs']['1'] = 'Amount';
            $output["head"]['receipt_type'][$count]['inputs']['2'] = 'Description';
        }

        // new member registration
        if($rtype['id'] == 3) {
            $output["head"]['receipt_type'][$count]['inputs']['0'] = 'Member';
            $output["head"]['receipt_type'][$count]['inputs']['1'] = 'Amount';
        }

        // gold silver dollar
        if($rtype['id'] == 4) {
            $output["head"]['receipt_type'][$count]['inputs']['0'] = 'Member';
            $output["head"]['receipt_type'][$count]['inputs']['1'] = 'Amount';
            $output["head"]['receipt_type'][$count]['inputs']['2'] = 'Description';
        }

        // pooja donation
        if($rtype['id'] == 5) {
            $output["head"]['receipt_type'][$count]['inputs']['0'] = 'Member';
            $output["head"]['receipt_type'][$count]['inputs']['1'] = 'Amount';
        }

        // mudi kaanikai
        if($rtype['id'] == 6) {
            $output["head"]['receipt_type'][$count]['inputs']['0'] = 'Member';
            $output["head"]['receipt_type'][$count]['inputs']['1'] = 'Amount';
            $output["head"]['receipt_type'][$count]['inputs']['2'] = 'Function Date';
            $output["head"]['receipt_type'][$count]['inputs']['3'] = 'Count For MudiKanikai';
            $output["head"]['receipt_type'][$count]['inputs']['4'] = 'Count For Kadhu Kuthu';
        }

        // nandavanam
        if($rtype['id'] == 7) {
            $output["head"]['receipt_type'][$count]['inputs']['0'] = 'Member';
            $output["head"]['receipt_type'][$count]['inputs']['1'] = 'Name';
            $output["head"]['receipt_type'][$count]['inputs']['2'] = 'Funeral To';
            $output["head"]['receipt_type'][$count]['inputs']['3'] = 'Amount';
        }

        // personal savings
        if($rtype['id'] == 8) {
            $output["head"]['receipt_type'][$count]['inputs']['0'] = 'Member';
            $output["head"]['receipt_type'][$count]['inputs']['1'] = 'Amount';
        }

        // general donation
        if($rtype['id'] == 9) {
            $output["head"]['receipt_type'][$count]['inputs']['0'] = 'Member';
            $output["head"]['receipt_type'][$count]['inputs']['1'] = 'Amount';
        }
        
        $count++;
    }

    $count = 0;
    if (!empty($total_records_list)) {
        foreach($total_records_list as $data)
        {
            if(!empty($data['creator_name'])){
                $data['creator_name'] = $obj -> encode_decode('decrypt', $data['creator_name']);
            }
            if(!empty($data['receipt_date']) && $data['receipt_date'] != "0000-00-00") { 
                $data['receipt_date'] = date("d-m-Y", strtotime($data['receipt_date'])); 
            }
            if(!empty($data['poojai_from_date']) && $data['poojai_from_date'] != "0000-00-00") { 
                $data['poojai_from_date'] = date("d-m-Y", strtotime($data['poojai_from_date'])); 
            }
            if(!empty($data['poojai_to_date']) && $data['poojai_to_date'] != "0000-00-00") { 
                $data['poojai_to_date'] = date("d-m-Y", strtotime($data['poojai_to_date'])); 
            }
            if(!empty($data['function_date']) && $data['function_date'] != "0000-00-00") { 
                $data['function_date'] = date("d-m-Y", strtotime($data['function_date'])); 
            }
            if(!empty($data['receipt_type_name'])){
                $data['receipt_type_name'] = $obj -> encode_decode('decrypt', $data['receipt_type_name']);
            }

            if(!empty($data['color_code'])) {
                $data['color_code'] = str_replace("#", "0xFF", $data['color_code']);
            }

            $output["body"][$count]["creator_name"] = $data["creator_name"];
            $output["body"][$count]["receipt_id"] = $data["receipt_id"];
            $output["body"][$count]["receipt_date"] = $data["receipt_date"];
            $output["body"][$count]["receipt_number"] = $data["receipt_number"];
            $output["body"][$count]["color_code"] = $data["color_code"];
            $output["body"][$count]["status_base_member_id"] = $data["status_base_member_id"];
            $output["body"][$count]["member_name"] = $data["member_name"];
            $output["body"][$count]["year_amount"] = $data["year_amount"];
            $output["body"][$count]["poojai_from_date"] = $data["poojai_from_date"];
            $output["body"][$count]["poojai_to_date"] = $data["poojai_to_date"];

            $output["body"][$count]["poojai_amount"] = $data["poojai_amount"];
            $output["body"][$count]["amount"] = $data["amount"];
            $output["body"][$count]["description"] = $data["description"];
            $output["body"][$count]["function_date"] = $data["function_date"];
            $output["body"][$count]["count_for_mudikanikai"] = $data["count_for_mudikanikai"];
            $output["body"][$count]["count_for_kadhu_kuthu"] = $data["count_for_kadhu_kuthu"];
            $output["body"][$count]["funeral_to"] = $data["funeral_to"];
            $output["body"][$count]["receipt_type_id"] = $data["receipt_type_id"];
            $output["body"][$count]["receipt_type_name"] = $data["receipt_type_name"];
            $output["body"][$count]["form_name"] = $data["form_name"];
            $output["body"][$count]["receipt_print_url"] = "http://sridemoapps.in/mahendran2022/temple/API/receipt_form/".$data['form_name']."/print.php?view_receipt_id=".$data['receipt_id'];

            $count++;
        }
    }
}

if(isset($json_obj->delete_receipt_id)) {
    $delete_receipt_id = $json_obj->delete_receipt_id;
    $delete_receipt_id = trim($delete_receipt_id);
    if(!empty($delete_receipt_id)) {
        $receipt_unique_id = ""; $receipt_table = "";
        $receipt_unique_id = $obj->getTableColumnValue($GLOBALS['new_thalakattu_table'], 'receipt_id', $delete_receipt_id, 'id');
        if(preg_match("/^\d+$/", $receipt_unique_id)) {
            $receipt_table = $GLOBALS['new_thalakattu_table'];
        }
        else {
            $receipt_unique_id = $obj->getTableColumnValue($GLOBALS['old_thalakattu_table'], 'receipt_id', $delete_receipt_id, 'id');
            if(preg_match("/^\d+$/", $receipt_unique_id)) {
                $receipt_table = $GLOBALS['old_thalakattu_table'];
            }
            else {
                $receipt_unique_id = $obj->getTableColumnValue($GLOBALS['new_member_registration_table'], 'receipt_id', $delete_receipt_id, 'id');
                if(preg_match("/^\d+$/", $receipt_unique_id)) {
                    $receipt_table = $GLOBALS['new_member_registration_table'];
                }
                else {
                    $receipt_unique_id = $obj->getTableColumnValue($GLOBALS['gold_silver_dollar_table'], 'receipt_id', $delete_receipt_id, 'id');
                    if(preg_match("/^\d+$/", $receipt_unique_id)) {
                        $receipt_table = $GLOBALS['gold_silver_dollar_table'];
                    }
                    else {
                        $receipt_unique_id = $obj->getTableColumnValue($GLOBALS['pooja_donation_table'], 'receipt_id', $delete_receipt_id, 'id');
                        if(preg_match("/^\d+$/", $receipt_unique_id)) {
                            $receipt_table = $GLOBALS['pooja_donation_table'];
                        }
                        else {
                            $receipt_unique_id = $obj->getTableColumnValue($GLOBALS['mudi_kaanikai_table'], 'receipt_id', $delete_receipt_id, 'id');
                            if(preg_match("/^\d+$/", $receipt_unique_id)) {
                                $receipt_table = $GLOBALS['mudi_kaanikai_table'];
                            }
                            else {
                                $receipt_unique_id = $obj->getTableColumnValue($GLOBALS['nandavanam_table'], 'receipt_id', $delete_receipt_id, 'id');
                                if(preg_match("/^\d+$/", $receipt_unique_id)) {
                                    $receipt_table = $GLOBALS['nandavanam_table'];
                                }
                                else {
                                    $receipt_unique_id = $obj->getTableColumnValue($GLOBALS['personal_savings_table'], 'receipt_id', $delete_receipt_id, 'id');
                                    if(preg_match("/^\d+$/", $receipt_unique_id)) {
                                        $receipt_table = $GLOBALS['personal_savings_table'];
                                    }
                                    else {
                                        $receipt_unique_id = $obj->getTableColumnValue($GLOBALS['general_donation_table'], 'receipt_id', $delete_receipt_id, 'id');
                                        if(preg_match("/^\d+$/", $receipt_unique_id)) {
                                            $receipt_table = $GLOBALS['general_donation_table'];
                                        }
                                    }
                                }
                            }
                        }
                    }
                }
            }
        }
        if(preg_match("/^\d+$/", $receipt_unique_id)) {
            $receipt_number = "";
            $receipt_number = $obj->getTableColumnValue($receipt_table, 'receipt_id', $delete_receipt_id, 'receipt_number');
        
            $action = "";
            if(!empty($receipt_number)) {
                $action = "Receipt Deleted. Receipt Number - ".$receipt_number;
            }
        
            $columns = array(); $values = array(); $msg = "";				
            $columns = array('deleted');
            $values = array("'1'");
            $msg = $obj->UpdateSQL($receipt_table, $receipt_unique_id, $columns, $values, $action);
            if(preg_match("/^\d+$/", $msg)) {
                $output["head"]["code"] = 200;
                $output["head"]["msg"] = "Successfully Receipt Deleted";
            }
            else {
                $output["head"]["code"] = 400;
                $output["head"]["msg"] = $msg;
            }
        }
        else {
            $output["head"]["code"] = 400;
            $output["head"]["msg"] = "Invalid Receipt";
        }
    }
    else {
        $output["head"]["code"] = 400;
        $output["head"]["msg"] = "Empty Receipt";
    }
}

$result = json_encode($output, JSON_NUMERIC_CHECK);

echo $result;
?>