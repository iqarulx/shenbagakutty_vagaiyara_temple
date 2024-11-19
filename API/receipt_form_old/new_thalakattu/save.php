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
    $status_base_member_id = ""; $year_amount = 0; $poojai_from_date = ""; $poojai_to_date = ""; $poojai_amount = 0; $amount = 0;

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

        $year_amount = $json_obj->year_amount;
        $year_amount = trim($year_amount);
        $year_amount_error = $valid->valid_price($year_amount, "year amount", "1");
        if(!empty($year_amount_error)) {
            if(empty($valid_receipt)) {
                $valid_receipt = $year_amount_error;
            }
        }

        $poojai_from_date = $json_obj->poojai_from_date;
        $poojai_from_date = trim($poojai_from_date);	
        if(!empty($poojai_from_date) && $poojai_from_date != "0000-00-00") {
            $poojai_from_date = date("d-m-Y", strtotime($poojai_from_date));
        }		
        if(!empty($poojai_from_date)) {
            $poojai_from_date_error = $valid->valid_date($poojai_from_date, "poojai from date", "1");
        }
        else {
            $poojai_from_date_error = "Select the poojai from date";
        }
        if(!empty($poojai_from_date_error)) {
            if(empty($valid_receipt)) {
                $valid_receipt = $poojai_from_date_error;
            }
        }

        $poojai_to_date = $json_obj->poojai_to_date;
        $poojai_to_date = trim($poojai_to_date);	
        if(!empty($poojai_to_date) && $poojai_to_date != "0000-00-00") {
            $poojai_to_date = date("d-m-Y", strtotime($poojai_to_date));
        }		
        if(!empty($poojai_to_date)) {
            $poojai_to_date_error = $valid->valid_date($poojai_to_date, "poojai to date", "1");
        }
        else {
            $poojai_to_date_error = "Select the poojai to date";
        }
        if(!empty($poojai_to_date_error)) {
            if(empty($valid_receipt)) {
                $valid_receipt = $poojai_to_date_error;
            }
        }

        $poojai_amount = $json_obj->poojai_amount;
        $poojai_amount = trim($poojai_amount);
        $poojai_amount_error = $valid->valid_price($poojai_amount, "poojai amount", "1");
        if(!empty($poojai_amount_error)) {
            if(empty($valid_receipt)) {
                $valid_receipt = $poojai_amount_error;
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

            if(!empty($year_amount)) {
                $amount = $amount + $year_amount; 
            }
            if(!empty($poojai_amount)) {
                $amount = $amount + $poojai_amount; 
            }
        
            if(!empty($poojai_from_date) && $poojai_from_date != "0000-00-00") {
                $poojai_from_date = date("Y-m-d", strtotime($poojai_from_date));
            }
            if(!empty($poojai_to_date) && $poojai_to_date != "0000-00-00") {
                $poojai_to_date = date("Y-m-d", strtotime($poojai_to_date));
            }
            
            if(empty($edit_id)) {
                $null_value = $GLOBALS['null_value'];
                $columns = array('created_date_time', 'creator', 'creator_name', 'receipt_id', 'receipt_type_id', 'receipt_number', 'receipt_date', 'member_id', 'member_name', 'status_base_member_id', 'year_amount', 'poojai_from_date', 'poojai_to_date', 'poojai_amount', 'amount', 'deleted');
                $values = array("'".$created_date_time."'", "'".$creator."'", "'".$creator_name."'", "'".$null_value."'", "'".$receipt_type_id."'", "'".$null_value."'", "'".$receipt_date."'", "'".$member_id."'", "'".$member_name."'", "'".$status_base_member_id."'", "'".$year_amount."'", "'".$poojai_from_date."'", "'".$poojai_to_date."'", "'".$poojai_amount."'", "'".$amount."'", "'0'");
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