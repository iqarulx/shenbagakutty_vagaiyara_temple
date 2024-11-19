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

if(isset($json_obj->notification_delete_member_id)) {
    $notification_delete_member_id = "";
    $notification_delete_member_id = $json_obj->notification_delete_member_id;
    $notification_delete_member_id = trim($notification_delete_member_id);

    $delete_notification_id = "";
    $delete_notification_id = $json_obj->delete_notification_id;
    $delete_notification_id = trim($delete_notification_id);


    if(!empty($notification_delete_member_id)) {
        $member_unique_id = "";
        $member_unique_id = $obj->getTableColumnValue($GLOBALS['member_table'], 'member_id', $notification_delete_member_id, 'id');
        if(preg_match("/^\d+$/", $member_unique_id)) {
            $fcm_id = "";
            $fcm_id = $obj->getTableColumnValue($GLOBALS['member_table'], 'member_id', $notification_delete_member_id, 'fcm_id');
            if(!empty($fcm_id) && $fcm_id != $GLOBALS['null_value']) {

                if(!empty($delete_notification_id)) {
                    $notification_unique_id = "";
                    $notification_unique_id = $obj->getTableColumnValue($GLOBALS['notification_send_table'], 'notification_id', $delete_notification_id, 'id');
                    if(preg_match("/^\d+$/", $notification_unique_id)) {
                        $remove_member_fcm_ids = "";
                        $remove_member_fcm_ids = $obj->getTableColumnValue($GLOBALS['notification_send_table'], 'notification_id', $delete_notification_id, 'remove_member_fcm_ids');
                        if(!empty($remove_member_fcm_ids) && $remove_member_fcm_ids != $GLOBALS['null_value']) {
                            $remove_member_fcm_ids = explode(",", $remove_member_fcm_ids);
                            $remove_member_fcm_ids[count($remove_member_fcm_ids)] = $fcm_id;
                            $remove_member_fcm_ids = implode(",", $remove_member_fcm_ids);

                            $columns = array(); $values = array(); $update_id = "";			
                            $columns = array('remove_member_fcm_ids');
                            $values = array("'".$remove_member_fcm_ids."'");
                            $update_id = $obj->UpdateSQL($GLOBALS['notification_send_table'], $notification_unique_id, $columns, $values, '');
                            if(preg_match("/^\d+$/", $update_id)) {
                                $output["head"]["code"] = 200;
                                $output["head"]["msg"] = "Successfully Removed";
                            }
                            else {
                                $output["head"]["code"] = 400;
                                $output["head"]["msg"] = $update_id;
                            }
                        }
                        else {
                            $columns = array(); $values = array(); $update_id = "";			
                            $columns = array('remove_member_fcm_ids');
                            $values = array("'".$fcm_id."'");
                            $update_id = $obj->UpdateSQL($GLOBALS['notification_send_table'], $notification_unique_id, $columns, $values, '');
                            if(preg_match("/^\d+$/", $update_id)) {
                                $output["head"]["code"] = 200;
                                $output["head"]["msg"] = "Successfully Removed";
                            }
                            else {
                                $output["head"]["code"] = 400;
                                $output["head"]["msg"] = $update_id;
                            }
                        }
                    }
                    else {
                        $output["head"]["code"] = 400;
                        $output["head"]["msg"] = 'Invalid Notification';
                    }
                }
                else {
                    $output["head"]["code"] = 400;
                    $output["head"]["msg"] = 'Empty Notification';
                }

            }
        }
        else {
            $output["head"]["code"] = 400;
            $output["head"]["msg"] = 'Invalid member';
        }
    }
    else {
        $output["head"]["code"] = 400;
        $output["head"]["msg"] = 'Empty Member';
    }
}

if(isset($json_obj->notification_view_member_id)){
    $notification_view_member_id = "";
    $notification_view_member_id = $json_obj->notification_view_member_id;
    $notification_view_member_id = trim($notification_view_member_id);

    $total_records_list = array();
    if(!empty($notification_view_member_id)) {
        $total_records_list = $obj->getMemberNotificationList($notification_view_member_id);
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
            if(!empty($data['title'])){
                $data['title'] = $obj -> encode_decode('decrypt', $data['title']);
            }
            $notification_image = "";
            if(!empty($data['image']) && $data['image'] != $GLOBALS['null_value']) {
                $notification_image = $data['image'];
                if(!empty($notification_image)) {
                    $notification_image = 'http://sridemoapps.in/mahendran2022/temple/images/upload/notification/'.$notification_image;
                }
            }
            $notification_audio = "";
            if(!empty($data['audio']) && $data['audio'] != $GLOBALS['null_value']) {
                $notification_audio = $data['audio'];
                if(!empty($notification_audio)) {
                    $notification_audio = 'http://sridemoapps.in/mahendran2022/temple/images/upload/notification/'.$notification_audio;
                }
            }
            $description = "";
            if(!empty($data['description'])) {
                $description = $obj->encode_decode('decrypt', $data['description']);
                $description = html_entity_decode($description);
            }
            $output["body"][$count]["notification_id"] = $data["notification_id"];
            $output["body"][$count]["title"] = $data["title"];
            $output["body"][$count]["notification_image"] = $notification_image;
            $output["body"][$count]["notification_audio"] = $notification_audio;
            $output["body"][$count]["description"] = $description;
            $count++;
        }
    }
}

$result = json_encode($output, JSON_NUMERIC_CHECK);

echo $result;
?>
