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
    $name = ""; $name_error = "";
    $valid_mandapam = "";

    if(isset($json_obj->edit_id)) {
        $edit_id = $json_obj->edit_id;
    }

    $name = $json_obj->name;
    $name = trim($name);
    $name_error = $valid->common_validation($name, "Name", 'text');
    if(!empty($name_error)) {
        $valid_mandapam = $name_error;		
    }
    
    $result = "";
    
    if(empty($valid_mandapam)) {
        $lowercase_name = "";
        if(!empty($name)) {
            $lowercase_name = strtolower($name);
            $lowercase_name = $obj->encode_decode('encrypt', $lowercase_name);
            $name = $obj->encode_decode('encrypt', $name);
        }

        $prev_mandapam_id = ""; $mandapam_error = "";		
        if(!empty($lowercase_name)) {
            $prev_mandapam_id = $obj->getTableColumnValue($GLOBALS['mandapam_table'], 'lowercase_name', $lowercase_name, 'mandapam_id');
            if(!empty($prev_mandapam_id)) {
                $mandapam_error = 'This mandapam name is already exist';
            }
        }

        $created_date_time = $GLOBALS['create_date_time_label']; $creator = $json_obj->creator;
        $creator_name = $obj->encode_decode('encrypt', $json_obj->creator_name);
        
        if(empty($edit_id)) {
            if(empty($prev_mandapam_id)) {						
                $action = "";
                if(!empty($name)) {
                    $action = "New Mandapam Created. Name - ".$obj->encode_decode('decrypt', $name);
                }

                $null_value = $GLOBALS['null_value'];
                $columns = array('created_date_time', 'creator', 'creator_name', 'mandapam_id', 'name', 'lowercase_name', 'deleted');
                $values = array("'".$created_date_time."'", "'".$creator."'", "'".$creator_name."'", "'".$null_value."'", "'".$name."'", "'".$lowercase_name."'", "'0'");
                $insert_id = $obj->InsertSQL($GLOBALS['mandapam_table'], $columns, $values, 'mandapam_id', '', $action);						
                if(preg_match("/^\d+$/", $insert_id)) {
                    $output["head"]["code"] = 200;
                    $output["head"]["msg"] = 'Mandapam Successfully created';
                }
                else {
                    $output["head"]["code"] = 400;
                    $output["head"]["msg"] = $insert_id;
                }
            }
            else {
                $output["head"]["code"] = 400;
                $output["head"]["msg"] = $mandapam_error;
            }
        }
        else {
            if(empty($prev_mandapam_id) || $prev_mandapam_id == $edit_id) {
                $getUniqueID = "";
                $getUniqueID = $obj->getTableColumnValue($GLOBALS['mandapam_table'], 'mandapam_id', $edit_id, 'id');
                if(preg_match("/^\d+$/", $getUniqueID)) {
                    $action = "";
                    if(!empty($name)) {
                        $action = "Mandapam Updated. Name - ".$obj->encode_decode('decrypt', $name);
                    }
                
                    $columns = array(); $values = array();						
                    $columns = array('creator_name', 'name', 'lowercase_name');
                    $values = array("'".$creator_name."'", "'".$name."'", "'".$lowercase_name."'");
                    $update_id = $obj->UpdateSQL($GLOBALS['mandapam_table'], $getUniqueID, $columns, $values, $action);
                    if(preg_match("/^\d+$/", $update_id)) {			
                        $output["head"]["code"] = 200;
                        $output["head"]["msg"] = 'Updated Successfully';						
                    }
                    else {
                        $output["head"]["code"] = 400;
                        $output["head"]["msg"] = $update_id;
                    }							
                }
            }
            else {
                $output["head"]["code"] = 400;
                $output["head"]["msg"] = $mandapam_error;
            }
        }
    }
    else {
        $output["head"]["code"] = 400;
        $output["head"]["msg"] = $valid_mandapam;
    }
}

if(isset($json_obj->search_text)){
    $search_text = "";

    $total_records_list = array();
    $total_records_list = $obj->getTableRecords($GLOBALS['mandapam_table'], '', '', '');

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

    if (!empty($total_records_list)) {
        foreach($total_records_list as $data)
        {
            if(!empty($data['name'])){
                $data['name'] = $obj -> encode_decode('decrypt', $data['name']);
            }
            if(!empty($data['creator_name'])){
                $data['creator_name'] = $obj -> encode_decode('decrypt', $data['creator_name']);
            }
            
            $output["body"][$count]["id"] = $data["id"];
            $output["body"][$count]["created_date_time"] = $data["created_date_time"];
            $output["body"][$count]["creator"] = $data["creator"];
            $output["body"][$count]["creator_name"] = $data["creator_name"];
            $output["body"][$count]["mandapam_id"] = $data["mandapam_id"];
            $output["body"][$count]["name"] = $data["name"];
            $count++;
        }
    }
}

if(isset($json_obj->delete_mandapam_id)) {
    $delete_mandapam_id = $json_obj->delete_mandapam_id;
    $msg = "";
    if(!empty($delete_mandapam_id)) {
        $booking_list = array(); $booking_rows = 0;
        $booking_list = $obj->getTableRecords($GLOBALS['mandapam_booking_table'], 'mandapam_id', $delete_mandapam_id, '');
        $booking_rows = count($booking_list);
        
        if(empty($booking_rows)) {
            $mandapam_unique_id = "";
            $mandapam_unique_id = $obj->getTableColumnValue($GLOBALS['mandapam_table'], 'mandapam_id', $delete_mandapam_id, 'id');
            if(preg_match("/^\d+$/", $mandapam_unique_id)) {
                $name = "";
                $name = $obj->getTableColumnValue($GLOBALS['mandapam_table'], 'mandapam_id', $delete_mandapam_id, 'name');
                if(!empty($name)) {
                    $name = $obj->encode_decode('decrypt', $name);
                    $name = trim($name);
                }
            
                $action = "";
                if(!empty($name)) {
                    $action = "Mandampam Deleted. Name - ".$name;
                }
            
                $columns = array(); $values = array();						
                $columns = array('deleted');
                $values = array("'1'");
                $msg = $obj->UpdateSQL($GLOBALS['mandapam_table'], $mandapam_unique_id, $columns, $values, $action);
                if(preg_match("/^\d+$/", $msg)) {
                    $output["head"]["code"] = 200;							
                    $output["head"]["msg"] = 'Mandapam Deleted Successfully';
                }
            }
        }
        else {
            $output["head"]["code"] = 400;
            $output["head"]["msg"] = "Unable to delete";
        }
    }
}

$result = json_encode($output, JSON_NUMERIC_CHECK);

echo $result;
?>