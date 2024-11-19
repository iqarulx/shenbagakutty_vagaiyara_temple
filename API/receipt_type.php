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

$count = 0;

// Check User Id and Password is isset on API Parameter
if(isset($json_obj->name)) {
    $name = ""; $name_error = ""; $type = ""; $type_error = ""; $prefix = ""; $prefix_error = ""; $starting_number = ""; $starting_number_error = "";
    
    $valid_receipt_type = ""; $form_name = "receipt_type_form";

    if(isset($json_obj->edit_id)) {
        $edit_id = $json_obj->edit_id;
    }

    $name = $json_obj->name;
    $name = trim($name);
    $name_error = $valid->common_validation($name, "Name", 'text');
    if(!empty($name_error)) {
        $valid_receipt_type = $name_error;	
    }

    $type = $json_obj->type;
    $type = trim($type);
    if(!empty($type)) {
        $check_type = $obj->encode_decode('decrypt', $type);
        if($check_type != $GLOBALS['receipt_type_corpus'] && $check_type != $GLOBALS['receipt_type_ordinary']) {
            $type_error = "Invalid type";
        }
    }
    else {
        $type_error = "Select the type";
    }
    if(!empty($type_error)) {
        $valid_receipt_type = $valid->error_display($form_name, "type", $type_error, 'select');
    }

    $prefix = $json_obj->prefix;
    $prefix = trim($prefix);
    $prefix_error = $valid->common_validation($prefix, "prefix", 'text');
    if(!empty($prefix_error)) {
        $valid_receipt_type = $prefix_error;
    }

    $starting_number = $json_obj->starting_number;
    $starting_number = trim($starting_number);
    $starting_number_error = $valid->valid_number($starting_number, "starting number", '1');
    if(!empty($starting_number_error)) {
        $valid_receipt_type = $starting_number_error;
    }
    
    $result = "";
    
    if(empty($valid_receipt_type)) {
        $lowercase_name = ""; $form_name = ""; $prev_form_name = "";
        if(!empty($name)) {
            $lowercase_name = strtolower($name);
            $form_name = strtolower($name);
            if(!empty($form_name)) {
                $form_name = preg_replace('/[^a-zA-Z0-9 ]/s','_',$form_name);                        
                $form_name = str_replace(" ", "_", $form_name);
            }
            $lowercase_name = $obj->encode_decode('encrypt', $lowercase_name);
            $name = $obj->encode_decode('encrypt', $name);
        }

        if(!empty($prefix)) {
            $prefix = $obj->encode_decode('encrypt', $prefix);
        }

        if(!empty($edit_id)) {
            $prev_form_name = $obj->getTableColumnValue($GLOBALS['receipt_type_table'], 'receipt_type_id', $edit_id, 'form_name');
        }

        $prev_receipt_type_id = ""; $receipt_type_error = "";		
        if(!empty($lowercase_name)) {
            $prev_receipt_type_id = $obj->getTableColumnValue($GLOBALS['receipt_type_table'], 'lowercase_name', $lowercase_name, 'receipt_type_id');
            if(!empty($prev_receipt_type_id)) {
                $receipt_type_error = "This receipt type name is already exist";
            }
        }

        $created_date_time = $GLOBALS['create_date_time_label']; $creator = $json_obj->creator;
        $creator_name = $obj->encode_decode('encrypt', $json_obj->creator_name);
        
        if(empty($edit_id)) {
            if(empty($prev_receipt_type_id)) {
                $action = "";
                if(!empty($name)) {
                    $action = "New Receipt Type Created. Name - ".$obj->encode_decode('decrypt', $name);
                }

                $null_value = $GLOBALS['null_value'];
                $columns = array('created_date_time', 'creator', 'creator_name', 'receipt_type_id', 'name', 'lowercase_name', 'form_name', 'type', 'prefix', 'starting_number', 'deleted');
                $values = array("'".$created_date_time."'", "'".$creator."'", "'".$creator_name."'", "'".$null_value."'", "'".$name."'", "'".$lowercase_name."'", "'".$form_name."'", "'".$type."'", "'".$prefix."'", "'".$starting_number."'", "'0'");
                $insert_id = $obj->InsertSQL($GLOBALS['receipt_type_table'], $columns, $values, 'receipt_type_id', '', $action);
                if(preg_match("/^\d+$/", $insert_id)) {
                    $output["head"]["code"] = 200;
                    $output["head"]["msg"] = "Receipt Type Successfully created";
                }
                else {
                    $output["head"]["code"] = 400;
                    $output["head"]["msg"] = $insert_id;
                }
            }
            else {
                $output["head"]["code"] = 400;
                $output["head"]["msg"] = $receipt_type_error;
            }
        }
        else {
            if(empty($prev_receipt_type_id) || $prev_receipt_type_id == $edit_id) {
                $getUniqueID = "";
                $getUniqueID = $obj->getTableColumnValue($GLOBALS['receipt_type_table'], 'receipt_type_id', $edit_id, 'id');
                if(preg_match("/^\d+$/", $getUniqueID)) {
                    $action = "";
                    if(!empty($name)) {
                        $action = "Receipt Type Updated. Name - ".$obj->encode_decode('decrypt', $name);
                    }
                
                    $columns = array(); $values = array();						
                    $columns = array('creator_name', 'prefix', 'starting_number');
                    $values = array("'".$creator_name."'", "'".$prefix."'", "'".$starting_number."'");
                    $update_id = $obj->UpdateSQL($GLOBALS['receipt_type_table'], $getUniqueID, $columns, $values, $action);
                    if(preg_match("/^\d+$/", $update_id)) {	
                        $output["head"]["code"] = 200;
                        $output["head"]["msg"] = 'Updated Successfully';					
                    }
                    else {
                        $result = array('number' => '2', 'msg' => $update_id);
                        $output["head"]["code"] = 400;
                        $output["head"]["msg"] = $update_id;
                    }							
                }
            }
            else {
                $output["head"]["code"] = 400;
                $output["head"]["msg"] = $receipt_type_error;
            }
        }
    }
    else {
        $output["head"]["code"] = 400;
        $output["head"]["msg"] = $valid_receipt_type;
    }
}

if(isset($json_obj->search_text)){
    $search_text = ""; $receipt_type_options_list = array();

    $total_records_list = array();
    $total_records_list = $obj->getTableRecords($GLOBALS['receipt_type_table'], '', '', '');

    if(!empty($json_obj->search_text)) {
        $search_text = strtolower($json_obj->search_text);
        $list = array();
        if(!empty($total_records_list)) {
            foreach($total_records_list as $val) {
                if( (strpos(strtolower($obj->encode_decode('decrypt', $val['name'])), $search_text) !== false) ) {
                    $list[] = $val;
                }
            }
        }
        $total_records_list = $list;
    }

    if(empty($total_records_list)){
        $output["head"]["code"] = 400;
        $output["head"]["msg"] = "No Recent Records";
    }
    else{
        $output["head"]["code"] = 200;
        $output["head"]["msg"] = "Success";
    }

    $receipt_type_options_list = $GLOBALS['receipt_type_options'];
    if(!empty($receipt_type_options_list)) {
        foreach($receipt_type_options_list as $option) {
            if(!empty($option)) {
                $option_encrypted = "";
                $option_encrypted = $obj->encode_decode('encrypt', $option);
                $output["head"]['receipt_type'][$count]['receipt_type_options'] = $option;
                $output["head"]['receipt_type'][$count]['receipt_type_options_encrypted'] = $option_encrypted;
                $count++;
            }
        }
    }

    if (!empty($total_records_list)) {
        $count = 0;
        foreach($total_records_list as $data)
        {
            $type = "";
            if(!empty($data['name'])){
                $data['name'] = $obj -> encode_decode('decrypt', $data['name']);
            }
            if(!empty($data['prefix'])){
                $data['prefix'] = $obj -> encode_decode('decrypt', $data['prefix']);
            }
            if(!empty($data['type'])){
                $type = $data['type'];
                $data['type'] = $obj -> encode_decode('decrypt', $data['type']);
            }
            if(!empty($data['creator_name'])){
                $data['creator_name'] = $obj -> encode_decode('decrypt', $data['creator_name']);
            }
            
            $output["body"][$count]["id"] = $data["id"];
            $output["body"][$count]["created_date_time"] = $data["created_date_time"];
            $output["body"][$count]["creator"] = $data["creator"];
            $output["body"][$count]["creator_name"] = $data["creator_name"];
            $output["body"][$count]["receipt_type_id"] = $data["receipt_type_id"];
            $output["body"][$count]["name"] = $data["name"];
            $output["body"][$count]["prefix"] = $data["prefix"];
            $output["body"][$count]["starting_number"] = $data["starting_number"];
            $output["body"][$count]["type"] = $data["type"];
            $output["body"][$count]["type_enc"] = $type;
            $count++;
        }
    }
}

if(isset($json_obj->delete_receipt_type_id)) {
    $delete_receipt_type_id = $json_obj->delete_receipt_type_id;
    $msg = "";
    if(!empty($delete_receipt_type_id)) {
        $receipt_type_unique_id = "";
        $receipt_type_unique_id = $obj->getTableColumnValue($GLOBALS['receipt_type_table'], 'receipt_type_id', $delete_receipt_type_id, 'id');
        if(preg_match("/^\d+$/", $receipt_type_unique_id)) {
            $name = "";
            $name = $obj->getTableColumnValue($GLOBALS['receipt_type_table'], 'receipt_type_id', $delete_receipt_type_id, 'name');
            if(!empty($name)) {
                $name = $obj->encode_decode('decrypt', $name);
                $name = trim($name);
            }
        
            $action = "";
            if(!empty($name)) {
                $action = "Receipt Type Deleted. Name - ".$name;
            }
        
            $columns = array(); $values = array();						
            $columns = array('deleted');
            $values = array("'1'");
            $msg = $obj->UpdateSQL($GLOBALS['receipt_type_table'], $receipt_type_unique_id, $columns, $values, $action);
            if(preg_match("/^\d+$/", $msg)) {
                $output["head"]["code"] = 200;							
                $output["head"]["msg"] = 'Receipt Type Deleted Successfully';
            }
        }
        else{
            $output["head"]["code"] = 400;							
            $output["head"]["msg"] = 'No Records Found';
        }
    }
}

$result = json_encode($output, JSON_NUMERIC_CHECK);

echo $result;
?>