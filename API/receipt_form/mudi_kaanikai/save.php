<?php
    $print_page = 1;
    include '../../include.php';

    // Header This Application is a JSON APPLICATION
    header('Content-Type: application/json; charset=utf-8');

    // get Post Form Data on API
    $json = file_get_contents('php://input');

    // Decode JSON Format
    $json_obj = json_decode($json);

    // Output Result Array Variable
    $output = array(); $result = "";

    $valid_receipt = ""; $edit_id = ""; $receipt_date = ""; $receipt_type_id = ""; $table_name = ""; $member_id = ""; $member_name = "";
    $status_base_member_id = ""; $amount = ""; $function_date = ""; $count_for_mudikanikai = ""; $count_for_kadhu_kuthu = "";

    if(isset($json_obj->edit_id)) {
        $edit_id = $json_obj->edit_id;
        $edit_id = trim($edit_id);

        $receipt_date = $json_obj->receipt_date;
		$receipt_date = trim($receipt_date);	
		if(!empty($receipt_date) && $receipt_date != "0000-00-00") {
			$receipt_date = date("d-m-Y", strtotime($receipt_date));
		}		
		if(!empty($receipt_date)) {
			$receipt_date_error = $valid->valid_date($receipt_date, "receipt date", "1");
		}
		else {
			$receipt_date_error = "Select the receipt date";
		}
		if(!empty($receipt_date_error)) {
			$valid_receipt = $receipt_date_error;			
		}

        $receipt_type_id = $json_obj->receipt_type_id;
		$receipt_type_id = trim($receipt_type_id);
        if(!empty($receipt_type_id)) {
			$receipt_type_unique_id = "";
			$receipt_type_unique_id = $obj->getTableColumnValue($GLOBALS['receipt_type_table'], 'receipt_type_id', $receipt_type_id, 'id');
			if(preg_match("/^\d+$/", $receipt_type_unique_id)) {
				$receipt_type_list = array();
				$receipt_type_list = $obj->getTableRecords($GLOBALS['receipt_type_table'], 'receipt_type_id', $receipt_type_id, '');
				if(!empty($receipt_type_list)) {
					foreach($receipt_type_list as $data) {
						if(!empty($data['form_name'])) {
							$receipt_folder_name = $data['form_name'];
							if(!empty($receipt_folder_name)) {    
								$table_name = $GLOBALS[$receipt_folder_name.'_table'];
							}
						}
					}
				}
			}
            else {
                $receipt_type_id_error = "Invalid receipt type";
            }
		}
		else {
			$receipt_type_id_error = "Select the receipt type";
		}
		if(!empty($receipt_type_id_error)) {
			if(empty($valid_receipt)) {
                $valid_receipt = $receipt_type_id_error;
            }
		}

        $member_id = $json_obj->member_id;
        $member_id = trim($member_id);
        if(!empty($member_id)) {
            $member_unique_id = "";
            $member_unique_id = $obj->getTableColumnValue($GLOBALS['member_table'], 'member_id', $member_id, 'id');
            if(preg_match("/^\d+$/", $member_unique_id)) {
                $member_list = array();
                $member_list = $obj->getTableRecords($GLOBALS['member_table'], 'member_id', $member_id, '');
                if(!empty($member_list)) {
                    foreach($member_list as $data) {
                        if(!empty($data['name'])) {
                            $member_name = $data['name'];
                        }
                        if(!empty($data['status_base_member_id'])) {
                            $status_base_member_id = $data['status_base_member_id'];	
                        }
                    }
                }
            }
            else {
                $receipt_type_id_error = "Invalid member";
            }
        }
        else {
            $member_id_error = "Select the member";
        }
        if(!empty($member_id_error)) {
            if(empty($valid_receipt)) {
                $valid_receipt = $member_id_error;
            }
        }

        $amount = $json_obj->amount;
        $amount = trim($amount);
        $amount_error = $valid->valid_price($amount, "amount", "1");
        if(!empty($amount_error)) {
            if(empty($valid_receipt)) {
                $valid_receipt = $amount_error;
            }
        }

        $function_date = $json_obj->function_date;
        $function_date = trim($function_date);	
        if(!empty($function_date) && $function_date != "0000-00-00") {
            $function_date = date("d-m-Y", strtotime($function_date));
        }		
        if(!empty($function_date)) {
            $function_date_error = $valid->valid_date($function_date, "function date", "1");
        }
        else {
            $function_date_error = "Select the function date";
        }
        if(!empty($function_date_error)) {
            if(empty($valid_receipt)) {
                $valid_receipt = $function_date_error;
            }
        }

        $count_for_kadhu_kuthu = $json_obj->count_for_kadhu_kuthu;
        $count_for_kadhu_kuthu = trim($count_for_kadhu_kuthu);
        if(!empty($count_for_kadhu_kuthu)) {
            $count_for_kadhu_kuthu_error = $valid->valid_number($count_for_kadhu_kuthu, "count for kadhu kuthu", "1");
        }
        if(!empty($count_for_kadhu_kuthu_error)) {
            if(!empty($valid_receipt)) {
                $valid_receipt = $valid_receipt." ".$valid->error_display($form_name, "count_for_kadhu_kuthu", $count_for_kadhu_kuthu_error, 'text');
            }
            else {
                $valid_receipt = $valid->error_display($form_name, "count_for_kadhu_kuthu", $count_for_kadhu_kuthu_error, 'text');
            }
        }

        $count_for_mudikanikai = $json_obj->count_for_mudikanikai;
        $count_for_mudikanikai = trim($count_for_mudikanikai);
        if(!empty($count_for_mudikanikai)) {
            $count_for_mudikanikai_error = $valid->valid_number($count_for_mudikanikai, "count for mudikanikai", "1");
        }

        if(empty($count_for_mudikanikai_error) && empty($count_for_mudikanikai) && empty($count_for_kadhu_kuthu)) {
            $count_for_mudikanikai_error = "Please enter the count for mudikanikai / kadhu kuthu";
        }

        if(!empty($count_for_mudikanikai_error)) {
            if(!empty($valid_receipt)) {
                $valid_receipt = $valid_receipt." ".$valid->error_display($form_name, "count_for_mudikanikai", $count_for_mudikanikai_error, 'text');
            }
            else {
                $valid_receipt = $valid->error_display($form_name, "count_for_mudikanikai", $count_for_mudikanikai_error, 'text');
            }
        }

        if(empty($valid_receipt)) {

            $created_date_time = $GLOBALS['create_date_time_label']; $creator = $json_obj->creator;
            //$creator_name = $obj->encode_decode('encrypt', $json_obj->creator_name);
            $creator_name = "";
            if(!empty($creator)) {
                $member_list = array();
                $member_list = $obj->getTableRecords($GLOBALS['member_table'], 'member_id', $creator, '');
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
                $creator_name = $obj->encode_decode('encrypt', $creator_name);
            }

            if(!empty($receipt_date) && $receipt_date != "0000-00-00") {
                $receipt_date = date("Y-m-d", strtotime($receipt_date));
                $receipt_time = date("H:i:s", strtotime($created_date_time));
                $receipt_date = $receipt_date." ".$receipt_time;
            }
            if(!empty($function_date) && $function_date != "0000-00-00") {
                $function_date = date("Y-m-d", strtotime($function_date));
            }

            if(empty($edit_id)) {
                $null_value = $GLOBALS['null_value'];
                $columns = array('created_date_time', 'creator', 'creator_name', 'receipt_id', 'receipt_type_id', 'receipt_number', 'receipt_date', 'member_id', 'member_name', 'status_base_member_id', 'function_date', 'count_for_mudikanikai', 'count_for_kadhu_kuthu', 'amount', 'deleted');
                $values = array("'".$created_date_time."'", "'".$creator."'", "'".$creator_name."'", "'".$null_value."'", "'".$receipt_type_id."'", "'".$null_value."'", "'".$receipt_date."'", "'".$member_id."'", "'".$member_name."'", "'".$status_base_member_id."'", "'".$function_date."'", "'".$count_for_mudikanikai."'", "'".$count_for_kadhu_kuthu."'", "'".$amount."'", "'0'");
                $insert_id = $obj->InsertSQL($table_name, $columns, $values, 'receipt_id', 'receipt_number', '');						
                if(preg_match("/^\d+$/", $insert_id)) {
                    $receipt_number = "";
                    $receipt_number = $obj->getTableColumnValue($table_name, 'id', $insert_id, 'receipt_number');
                    $action = "";
                    if(!empty($receipt_number)) {
                        $action = "New Receipt Created. Receipt Number - ".$receipt_number;
                    }
                
                    $columns = array(); $values = array();						
                    $columns = array('receipt_number');
                    $values = array("'".$receipt_number."'");
                    $update_id = $obj->UpdateSQL($table_name, $insert_id, $columns, $values, $action);

                    $print_url = ""; $receipt_id = "";
                    $receipt_id = $obj->getTableColumnValue($table_name, 'id', $insert_id, 'receipt_id');
                    if(!empty($receipt_folder_name) && !empty($receipt_id)) { 
                        $print_url = "http://sridemoapps.in/mahendran2022/temple/API/receipt_form/".$receipt_folder_name."/print.php?view_receipt_id=".$receipt_id;
                    }

                    $output["head"]["code"] = 200;							
                    $output["head"]["msg"] = 'Receipt Successfully created';
                    $output["head"]["print_url"] = $print_url;
                }
                else {
                    $output["head"]["code"] = 400;							
                    $output["head"]["msg"] = $insert_id;
                }
            }
        } 
        else{
            $output["head"]["code"] = 400;							
            $output["head"]["msg"] = $valid_receipt;
        } 
    } 

    $result = json_encode($output, JSON_NUMERIC_CHECK);

    echo $result;
?>