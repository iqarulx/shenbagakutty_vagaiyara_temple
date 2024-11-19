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
if(isset($json_obj->from_date)) {
    $from_date = ""; $from_date_error = ""; $to_date = ""; $to_date_error = ""; $mandapam_id = ""; $mandapam_id_error = "";
    $valid_mandapam_booking = ""; $form_name = "mandapam_booking_form";

    $from_date = $json_obj->from_date;
    $from_date = trim($from_date);
    if(!empty($from_date)) {
        $from_date = date("d-m-Y", strtotime($from_date));
    }
    $from_date_error = $valid->valid_date($from_date, "from date", '1');
    if(!empty($from_date_error)) {
        $valid_mandapam_booking = $from_date_error;
    }

    $to_date = $json_obj->to_date;
    $to_date = trim($to_date);
    if(!empty($to_date)) {
        $to_date = date("d-m-Y", strtotime($to_date));
    }
    $to_date_error = $valid->valid_date($to_date, "to date", '1');
    if(!empty($to_date_error)) {
        $valid_mandapam_booking = $to_date_error;
    }

    $mandapam_id = $json_obj->mandapam_id;
    $mandapam_id = trim($mandapam_id);
    if(!empty($mandapam_id)) {
        $mandapam_unique_id = "";
        $mandapam_unique_id = $obj->getTableColumnValue($GLOBALS['mandapam_table'], 'mandapam_id', $mandapam_id, 'id');
        if(!preg_match("/^\d+$/", $mandapam_unique_id)) {
            $mandapam_id_error = "Invalid mandapam";
        }
    }
    else {
        $mandapam_id_error = "Select the mandapam";
    }
    if(!empty($mandapam_id_error)) {
        $valid_mandapam_booking = $mandapam_id_error;
    }
    
    $result = "";
    
    if(empty($valid_mandapam_booking)) {
        if(!empty($from_date)) {
            $from_date = date("Y-m-d", strtotime($from_date));
        }
        if(!empty($to_date)) {
            $to_date = date("Y-m-d", strtotime($to_date));
        }

        $booking_count = 0; $mandapam_booking_error = "";		
        if(!empty($mandapam_id)) {
            $booking_count = $obj->CheckMandapamAlreadyBooking($mandapam_id, $from_date, $to_date);
            if(!empty($booking_count)) {
                $mandapam_booking_error = "This mandapam is already exist in booking";
            }
        }

        $created_date_time = $GLOBALS['create_date_time_label']; $creator = $json_obj->creator;
        $creator_name = $obj->encode_decode('encrypt', $json_obj->creator_name);
        
        if(empty($booking_count)) {						
            $action = "";
            if(!empty($from_date) && !empty($to_date)) {
                $action = "New Mandapam Booking Created. From Date - ".date("d-m-Y", strtotime($from_date)).", To Date - ".date("d-m-Y", strtotime($to_date));
            }

            $null_value = $GLOBALS['null_value'];
            $columns = array('created_date_time', 'creator', 'creator_name', 'mandapam_booking_id', 'from_date', 'to_date', 'mandapam_id', 'deleted');
            $values = array("'".$created_date_time."'", "'".$creator."'", "'".$creator_name."'", "'".$null_value."'", "'".$from_date."'", "'".$to_date."'", "'".$mandapam_id."'", "'0'");
            $insert_id = $obj->InsertSQL($GLOBALS['mandapam_booking_table'], $columns, $values, 'mandapam_booking_id', '', $action);						
            if(preg_match("/^\d+$/", $insert_id)) {
                $output["head"]["code"] = 200;
                $output["head"]["msg"] = 'Mandapam Booked Successfully';
            }
            else {
                $output["head"]["code"] = 400;
                $output["head"]["msg"] = $insert_id;
            }
        }
        else {
            $output["head"]["code"] = 400;
            $output["head"]["msg"] = $mandapam_booking_error;
        }
    }
    else {
        $output["head"]["code"] = 400;
        $output["head"]["msg"] = $valid_mandapam_booking;
    }
}

if(isset($json_obj->search_text)){
    $search_text = "";

    $total_records_list = array();
    $total_records_list = $obj->getTableRecords($GLOBALS['mandapam_booking_table'], '', '', '');

    if(empty($total_records_list)){
        $output["head"]["code"] = 400;
        $output["head"]["msg"] = "No Recent Records";
    }
    else{
        $output["head"]["code"] = 200;
        $output["head"]["msg"] = "Success";
    }

    $mandapam_list = array();
    $mandapam_list = $obj->getTableRecords($GLOBALS['mandapam_table'], '', '', '');

    if(!empty($mandapam_list)){
        $count = 0;
        foreach($mandapam_list as $list){
            $mandapam_id = ""; $mandapam_name = "";
            if(!empty($list['mandapam_id'])){
                $mandapam_id = $list['mandapam_id'];
            }
            if(!empty($list['name'])){
                $mandapam_name = $list['name'];
                $mandapam_name = $obj->encode_decode('decrypt', $mandapam_name);
            }
            $output["head"]['mandapam'][$count]['mandapam_id'] = $mandapam_id;
            $output["head"]['mandapam'][$count]['mandapam_name'] = $mandapam_name;
            $count++;
        }
    }

    if (!empty($total_records_list)) {
        $count = 0;
        foreach($total_records_list as $data)
        {
            if(!empty($data['name'])){
                $data['name'] = $obj -> encode_decode('decrypt', $data['name']);
            }
            if(!empty($data['creator_name'])){
                $data['creator_name'] = $obj -> encode_decode('decrypt', $data['creator_name']);
            }

            $mandapam_name = "";
            $mandapam_name = $obj->getTableColumnValue($GLOBALS['mandapam_table'], 'mandapam_id', $data['mandapam_id'], 'name');

            if(!empty($mandapam_name)){
                $mandapam_name = $obj->encode_decode('decrypt', $mandapam_name);
            }
            
            $output["body"][$count]["id"] = $data["id"];
            $output["body"][$count]["created_date_time"] = $data["created_date_time"];
            $output["body"][$count]["creator"] = $data["creator"];
            $output["body"][$count]["creator_name"] = $data["creator_name"];
            $output["body"][$count]["mandapam_booking_id"] = $data["mandapam_booking_id"];
            $output["body"][$count]["from_date"] = date("d-m-Y", strtotime($data['from_date']));
            $output["body"][$count]["to_date"] = date("d-m-Y", strtotime($data['to_date']));
            $output["body"][$count]["mandapam_id"] = $data["mandapam_id"];
            $output["body"][$count]["mandapam_name"] = $mandapam_name;
            $count++;
        }
    }
}

if(isset($json_obj->delete_mandapam_booking_id)) {
    $delete_mandapam_booking_id = $json_obj -> delete_mandapam_booking_id;
    $msg = "";
    if(!empty($delete_mandapam_booking_id)) {	
        $mandapam_booking_unique_id = "";
        $mandapam_booking_unique_id = $obj->getTableColumnValue($GLOBALS['mandapam_booking_table'], 'mandapam_booking_id', $delete_mandapam_booking_id, 'id');
        if(preg_match("/^\d+$/", $mandapam_booking_unique_id)) {
            $from_date = ""; $to_date = "";
            $from_date = $obj->getTableColumnValue($GLOBALS['mandapam_booking_table'], 'mandapam_booking_id', $delete_mandapam_booking_id, 'from_date');
            $to_date = $obj->getTableColumnValue($GLOBALS['mandapam_booking_table'], 'mandapam_booking_id', $delete_mandapam_booking_id, 'to_date');
        
            $action = "";
            if(!empty($from_date) && !empty($to_date)) {
                $action = "Mandapam Booking Deleted. From Date - ".date("d-m-Y", strtotime($from_date)).", To Date - ".date("d-m-Y", strtotime($to_date));
            }
        
            $columns = array(); $values = array();						
            $columns = array('deleted');
            $values = array("'1'");
            $msg = $obj->UpdateSQL($GLOBALS['mandapam_booking_table'], $mandapam_booking_unique_id, $columns, $values, $action);
            if(preg_match("/^\d+$/", $msg)) {
                $output["head"]["code"] = 200;							
                $output["head"]["msg"] = 'Mandapam Booking Deleted Successfully';
            }
            else {
                $output["head"]["code"] = 400;							
                $output["head"]["msg"] = $msg;
            }
        }
    }
}

if(isset($json_obj->mandapam_booking_month)) {
    $mandapam_booking_month = $json_obj->mandapam_booking_month;
    $mandapam_booking_month = trim($mandapam_booking_month);
    $mandapam_booking_year = $json_obj->mandapam_booking_year;
    $mandapam_booking_year = trim($mandapam_booking_year);
    $month_last_date = $json_obj->month_last_date;
    $month_last_date = trim($month_last_date);
    $booking_mandapam_id = $json_obj->booking_mandapam_id;
    $booking_mandapam_id = trim($booking_mandapam_id);

    if(empty($mandapam_booking_month) || empty($mandapam_booking_year) || empty($month_last_date)) {
        $current_date = date("d-m-Y");
        $mandapam_booking_month = date("m", strtotime($current_date));
        $mandapam_booking_year = date("Y", strtotime($current_date));
        $month_last_date = date("t", strtotime($current_date));
    }
    if(empty($mandapam_booking_month['0'])) {
        $mandapam_booking_month = substr($mandapam_booking_month, 1, strlen($mandapam_booking_month));
    }

    if(!empty($mandapam_booking_month) && preg_match("/^\d+$/", $mandapam_booking_month)) {
        if(!empty($mandapam_booking_year) && strlen($mandapam_booking_year) == 4 && preg_match("/^\d+$/", $mandapam_booking_year)) {
            if(!empty($month_last_date) && preg_match("/^\d+$/", $month_last_date)) {

                $mandapam_error = "";
                if(!empty($booking_mandapam_id)) {
                    $mandapam_unique_id = "";
                    $mandapam_unique_id = $obj->getTableColumnValue($GLOBALS['mandapam_table'], 'mandapam_id', $booking_mandapam_id, 'id');
                    if(!preg_match("/^\d+$/", $mandapam_unique_id)) {
                        $mandapam_error = "Invalid mandapam";
                    }
                }
                if(empty($mandapam_error)) {
                    $from_date = "01-".$mandapam_booking_month."-".$mandapam_booking_year;
                    $to_date = $month_last_date."-".$mandapam_booking_month."-".$mandapam_booking_year;

                    $mandapam_list = array();
		            $mandapam_list = $obj->getTableRecords($GLOBALS['mandapam_table'], '', '', '');
                    if(!empty($mandapam_list)) {
                        $list = array();
                        foreach($mandapam_list as $data) {
                            if(!empty($data['name'])) {
                                $data['name'] = $obj->encode_decode('decrypt', $data['name']);
                                $list[] = $data;
                            }
                        }
                        $mandapam_list = $list;
                    }

                    $booking_list = array();
		            $booking_list = $obj->getMandapamBookingList($booking_mandapam_id, $from_date, $to_date);
                    if(!empty($booking_list)) {
                        $list = array();
                        foreach($booking_list as $data) {
                            if(!empty($data['mandapam_name'])) {
                                $data['mandapam_name'] = $obj->encode_decode('decrypt', $data['mandapam_name']);
                                $list[] = $data;
                            }
                        }
                        $booking_list = $list;
                    }

                    $booking_dates = array();
		            $booking_dates = $obj->getDashboardBookingDatelist($mandapam_booking_month, $mandapam_booking_year, $month_last_date, $booking_mandapam_id);
                    if(!empty($booking_dates)) {
                        $booking_dates = explode("$$$", $booking_dates);
                        $output["head"]["code"] = 200;							
                        $output["head"]["msg"] = 'Success';
                        $output["head"]["booking_dates"] = $booking_dates;
                        $output["head"]["booking_list"] = $booking_list;
                        $output["head"]["mandapam_list"] = $mandapam_list;
                    }
                    else {
                        $output["head"]["code"] = 400;							
                        $output["head"]["msg"] = "Sorry! No bookings found";
                        $output["head"]["booking_dates"] = [];
                        $output["head"]["booking_list"] = [];
                        if(!empty($mandapam_list)) {
                            $output["head"]["mandapam_list"] = $mandapam_list;
                        }
                        else {
                            $output["head"]["mandapam_list"] = [];
                        }
                    }
                }
                else {
                    $output["head"]["code"] = 400;							
                    $output["head"]["msg"] = $mandapam_error;
                }

            }
            else {
                $output["head"]["code"] = 400;							
                $output["head"]["msg"] = "Invalid month last date";
            }
        }
        else {
            $output["head"]["code"] = 400;							
            $output["head"]["msg"] = "Invalid year";
        }
    }
    else {
        $output["head"]["code"] = 400;							
        $output["head"]["msg"] = "Invalid month";
    }
}

$result = json_encode($output, JSON_NUMERIC_CHECK);

echo $result;
?>