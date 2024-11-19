<?php
include 'include.php';
ini_set("extension", "php_gd2.dll");
// Header This Application is a JSON APPLICATION
header('Content-Type: application/json; charset=utf-8');

// get Post Form Data on API
$json = file_get_contents('php://input');

// Decode JSON Format
$json_obj = json_decode($json);

// Output Result Array Variable
$output = array();

// Check User Id and Password is isset on API Parameter
if (!empty($json_obj->update_member_id)) {
    $update_member_id = $json_obj->update_member_id;
    $image_url = "";
    $image_type = "";
    $image_upload_type = "";
    $prefix = "";

    if (isset($json_obj->image_url)) {
        $image_url = $json_obj->image_url;
        $image_url = trim($image_url);
    }
    if (isset($json_obj->image_type)) {
        $image_type = $json_obj->image_type;
        $image_type = trim($image_type);
    }

    if (isset($json_obj->image_upload_type)) {
        $image_upload_type = $json_obj->image_upload_type;
    }

    if (isset($json_obj->prefix)) {
        $prefix = $json_obj->prefix;
    }

    $target_dir = $obj->image_directory();
    $temp_dir = $obj->temp_image_directory();
    $target_dir = $target_dir . "member_photo/";
    $temp_dir = $temp_dir;

    $member_unique_id = "";
    $member_unique_id = $obj->getTableColumnValue($GLOBALS['member_table'], 'member_id', $update_member_id, 'id');

    $member_id = "";
    $member_id = $obj->getTableColumnValue($GLOBALS['member_table'], 'member_id', $update_member_id, 'status_base_member_id');

    $member_unique_number = "";
    $member_unique_number = $obj->getTableColumnValue($GLOBALS['member_table'], 'member_id', $update_member_id, 'member_unique_number');
    if (!empty($member_unique_number)) {
        $target_dir = $target_dir . $member_unique_number . "/";
        if (!file_exists($target_dir)) {
            mkdir($target_dir, 0777, true);
        }
    }

    if (preg_match("/^\d+$/", $member_unique_id)) {
        if ($image_upload_type == "profile") {
            $prev_profile_image = "";
            $prev_profile_image = $obj->getTableColumnValue($GLOBALS['member_table'], 'member_id', $update_member_id, 'profile_photo');
            if (!empty($image_url) && !empty($image_type)) {
                /*if(strpos($image_url, ',') !== false) {
                    $image_data = explode(',', $image_url);*/

                $image_value = "";
                $image_name = $member_unique_number . "_profile." . $image_type;
                $image_value = base64_decode($image_url);
                $destination = $temp_dir . $image_name;
                file_put_contents($destination, $image_value);

                $webp_image = "";
                if (!empty($image_name) && file_exists($temp_dir . $image_name)) {
                    $folder_image = $temp_dir . $image_name;
                    $extension_list = array('jpg', 'jpeg', 'png');
                    $resolution = 42;
                    if (in_array($image_type, $extension_list)) {
                        if (!empty($image_name) && !empty($image_type) && $image_type != "webp") {
                            $webp_image = str_replace("." . $image_type, "", $image_name);
                            $im = "";
                            $webp_image = $webp_image . '.webp';
                            if (!file_exists($temp_dir . $webp_image)) {
                                if ($image_type == "png" || $image_type == "jpg" || $image_type == "jpeg") {

                                    if ($image_type == "png") {
                                        $im = imagecreatefrompng($folder_image);
                                    } else if ($image_type == "jpg" || $image_type == "jpeg") {
                                        $im = imagecreatefromjpeg($folder_image);
                                    }
                                    if (!empty($im)) {
                                        imagepalettetotruecolor($im);
                                        imagewebp($im, $temp_dir . $webp_image, $resolution);
                                        imagedestroy($im);
                                    }
                                }
                            }
                        }
                    }
                }
                if (!empty($webp_image) && file_exists($temp_dir . $webp_image)) {
                    copy($temp_dir . $webp_image, $target_dir . $webp_image);
                    if (file_exists($target_dir . $webp_image)) {
                        // if(file_exists($target_dir.$prev_profile_image)) {
                        //     unlink($target_dir.$prev_profile_image);
                        // }
                        $columns = array();
                        $values = array();
                        $columns = array('profile_photo');
                        $values = array("'" . $webp_image . "'");
                        $company_update_id = $obj->UpdateSQL($GLOBALS['member_table'], $member_unique_id, $columns, $values, '');
                        $output["head"]["code"] = 200;
                        $output["head"]["msg"] = 'Image Uploaded Successfully';
                        if (preg_match("/^\d+$/", $company_update_id)) {
                            $obj->clear_temp_image_directory();
                        }
                    }
                } else {
                    $output["head"]["code"] = 400;
                    $output["head"]["msg"] = 'Error in Image Upload!';
                }
                //}
            } else {
                if (!empty($prev_profile_image)) {
                    if (file_exists($target_dir . $prev_profile_image)) {
                        // unlink($target_dir.$prev_profile_image);
                    }
                }
            }
        } else if ($image_upload_type == "wife") {
            $prev_wife_image = "";
            $prev_wife_image = $obj->getTableColumnValue($GLOBALS['member_table'], 'member_id', $update_member_id, 'wife_photo');
            if (!empty($image_url) && !empty($image_type)) {
                /*if(strpos($image_url, ',') !== false) {
                    $image_data = explode(',', $image_url);*/

                $image_value = "";
                $image_name = $member_unique_number . "_wife." . $image_type;
                $image_value = base64_decode($image_url);
                $destination = $temp_dir . $image_name;
                file_put_contents($destination, $image_value);

                $webp_image = "";
                if (!empty($image_name) && file_exists($temp_dir . $image_name)) {
                    $folder_image = $temp_dir . $image_name;
                    $extension_list = array('jpg', 'jpeg', 'png');
                    $resolution = 42;
                    if (in_array($image_type, $extension_list)) {
                        if (!empty($image_name) && !empty($image_type) && $image_type != "webp") {
                            $webp_image = str_replace("." . $image_type, "", $image_name);
                            $im = "";
                            $webp_image = $webp_image . '.webp';
                            if (!file_exists($temp_dir . $webp_image)) {
                                if ($image_type == "png" || $image_type == "jpg" || $image_type == "jpeg") {
                                    if ($image_type == "png") {
                                        $im = imagecreatefrompng($folder_image);
                                    } else if ($image_type == "jpg" || $image_type == "jpeg") {
                                        $im = imagecreatefromjpeg($folder_image);
                                    }
                                    if (!empty($im)) {
                                        imagepalettetotruecolor($im);
                                        imagewebp($im, $temp_dir . $webp_image, $resolution);
                                        imagedestroy($im);
                                    }
                                }
                            }
                        }
                    }
                }
                if (!empty($webp_image) && file_exists($temp_dir . $webp_image)) {
                    copy($temp_dir . $webp_image, $target_dir . $webp_image);
                    if (file_exists($target_dir . $webp_image)) {
                        if (file_exists($target_dir . $prev_wife_image)) {
                            // unlink($target_dir.$prev_wife_image);
                        }
                        $columns = array();
                        $values = array();
                        $columns = array('wife_photo');
                        $values = array("'" . $webp_image . "'");
                        $company_update_id = $obj->UpdateSQL($GLOBALS['member_table'], $member_unique_id, $columns, $values, '');
                        $output["head"]["code"] = 200;
                        $output["head"]["msg"] = 'Image Uploaded Successfully';
                        if (preg_match("/^\d+$/", $company_update_id)) {
                            $obj->clear_temp_image_directory();
                        }
                    }
                } else {
                    $output["head"]["code"] = 400;
                    $output["head"]["msg"] = 'Error in Image Upload!';
                }
                //}
            } else {
                if (!empty($prev_wife_image)) {
                    if (file_exists($target_dir . $prev_wife_image)) {
                        // unlink($target_dir.$prev_wife_image);
                    }
                }
            }
        } else if ($image_upload_type == "family") { //!empty($prefix) &&
            if (empty($prefix)) {
                $prefix = "1";
            }
            if (!empty($image_url) && !empty($image_type)) {
                if ($prefix < "4") {
                    $image_value = "";
                    $image_name = "family_" . $prefix . "." . $image_type;
                    $image_value = base64_decode($image_url);
                    $destination = $temp_dir . $image_name;
                    file_put_contents($destination, $image_value);

                    $webp_image = "";
                    if (!empty($image_name) && file_exists($temp_dir . $image_name)) {
                        $folder_image = $temp_dir . $image_name;
                        $extension_list = array('jpg', 'jpeg', 'png');
                        $resolution = 42;
                        if (in_array($image_type, $extension_list)) {
                            if (!empty($image_name) && !empty($image_type) && $image_type != "webp") {
                                $webp_image = str_replace("." . $image_type, "", $image_name);
                                $im = "";
                                $webp_image = $webp_image . '.webp';
                                if (!file_exists($temp_dir . $webp_image)) {
                                    if ($image_type == "png" || $image_type == "jpg" || $image_type == "jpeg") {
                                        if ($image_type == "png") {
                                            $im = imagecreatefrompng($folder_image);
                                        } else if ($image_type == "jpg" || $image_type == "jpeg") {
                                            $im = imagecreatefromjpeg($folder_image);
                                        }
                                        if (!empty($im)) {
                                            imagepalettetotruecolor($im);
                                            imagewebp($im, $temp_dir . $webp_image, $resolution);
                                            imagedestroy($im);
                                        }
                                    }
                                }
                            }
                        }
                    }
                    if (!empty($webp_image) && file_exists($temp_dir . $webp_image)) {
                        copy($temp_dir . $webp_image, $target_dir . $webp_image);
                        if (file_exists($target_dir . $webp_image)) {
                            $member_unique_id = "";
                            $member_unique_id = $obj->getTableColumnValue($GLOBALS['member_table'], 'member_id', $update_member_id, 'id');
                            if (preg_match("/^\d+$/", $member_unique_id)) {
                                $family_photo = "";
                                $family_photo = $obj->getTableColumnValue($GLOBALS['member_table'], 'member_id', $update_member_id, 'family_photo');
                                if (!empty($family_photo) && $family_photo != $GLOBALS['null_value']) {
                                    $family_photo = explode(",", $family_photo);
                                    $family_photo[count($family_photo)] = $webp_image;
                                    $family_photo = implode(",", $family_photo);
                                } else {
                                    $family_photo = $webp_image;
                                }

                                $columns = array();
                                $values = array();
                                $columns = array('family_photo');
                                $values = array("'" . $family_photo . "'");
                                $company_update_id = $obj->UpdateSQL($GLOBALS['member_table'], $member_unique_id, $columns, $values, '');
                            }

                            if (!empty($member_photo_location)) {
                                $member_photo_location = $member_photo_location . $member_unique_number . "/";
                            }

                            $output["head"]["code"] = 200;
                            $output["head"]["member_family_photos"] = 'http://sridemoapps.in/mahendran2022/temple/' . str_replace("../", "", $member_photo_location) . $webp_image;
                            $output["head"]["msg"] = 'Image Uploaded Successfully';
                            $obj->clear_temp_image_directory();
                        }
                    }
                } else {
                    $output["head"]["code"] = 400;
                    $output["head"]["msg"] = 'Maximum Photo upload : 3';
                }
            } else {
                /*if(empty($prefix)) {
                    $output["head"]["code"] = 400;
                    $output["head"]["msg"] = 'Enter Prefix';
                }
                else*/
                if (empty($image_url) || empty($image_type)) {
                    $output["head"]["code"] = 400;
                    $output["head"]["msg"] = 'Select the image to upload';
                }
            }
        } else if ($image_upload_type == "child") {
            if (!empty($prefix) && !empty($image_url) && !empty($image_type)) {
                $image_value = "";
                $image_name = $member_unique_number . "_child_" . $prefix . "." . $image_type;
                $image_value = base64_decode($image_url);
                $destination = $temp_dir . $image_name;
                file_put_contents($destination, $image_value);

                $webp_image = "";
                if (!empty($image_name) && file_exists($temp_dir . $image_name)) {
                    $folder_image = $temp_dir . $image_name;
                    $extension_list = array('jpg', 'jpeg', 'png');
                    $resolution = 42;
                    if (in_array($image_type, $extension_list)) {
                        if (!empty($image_name) && !empty($image_type) && $image_type != "webp") {
                            $webp_image = str_replace("." . $image_type, "", $image_name);
                            $im = "";
                            $webp_image = $webp_image . '.webp';
                            if (!file_exists($temp_dir . $webp_image)) {
                                if ($image_type == "png" || $image_type == "jpg" || $image_type == "jpeg") {
                                    if ($image_type == "png") {
                                        $im = imagecreatefrompng($folder_image);
                                    } else if ($image_type == "jpg" || $image_type == "jpeg") {
                                        $im = imagecreatefromjpeg($folder_image);
                                    }
                                    if (!empty($im)) {
                                        imagepalettetotruecolor($im);
                                        imagewebp($im, $temp_dir . $webp_image, $resolution);
                                        imagedestroy($im);
                                    }
                                }
                            }
                        }
                    }
                }
                if (!empty($webp_image) && file_exists($temp_dir . $webp_image)) {
                    copy($temp_dir . $webp_image, $target_dir . $webp_image);
                    if (file_exists($target_dir . $webp_image)) {
                        $child_prefix = "";
                        $child_prefix = "child_" . $prefix;
                        $member_child_unique_id = "";
                        $select_query = "";
                        $m = 1;
                        $select_query = "SELECT id from " . $GLOBALS['member_child_table'] . " where member_id='" . $update_member_id . "' AND deleted='0' order by id ASC ";
                        $member_child_list = array();
                        $member_child_list = $obj->getQueryRecords($GLOBALS['member_child_table'], $select_query);
                        if (!empty($member_child_list)) {
                            foreach ($member_child_list as $m_child) {
                                if (!empty($m_child['id'])) {
                                    if ($m == $prefix) {
                                        $member_child_unique_id = $m_child['id'];
                                    }
                                    $m++;
                                }
                            }
                        }
                        // $member_child_unique_id = $obj->getTableColumnValue($GLOBALS['member_child_table'], 'member_id', $update_member_id, 'id');
                        if (preg_match("/^\d+$/", $member_child_unique_id)) {
                            $columns = array();
                            $values = array();
                            $columns = array('profile_photo');
                            $values = array("'" . $webp_image . "'");
                            $company_update_id = $obj->UpdateSQL($GLOBALS['member_child_table'], $member_child_unique_id, $columns, $values, '');
                        }

                        $output["head"]["code"] = 200;
                        $output["head"]["msg"] = 'Image Uploaded Successfully';
                        $obj->clear_temp_image_directory();
                    }
                }
            } else {
                if (empty($prefix)) {
                    $output["head"]["code"] = 400;
                    $output["head"]["msg"] = 'Enter Prefix';
                } else if (empty($image_url) || empty($image_type)) {
                    $output["head"]["code"] = 400;
                    $output["head"]["msg"] = 'Select the image to upload';
                }
            }
        }
    }
}

$result = json_encode($output, JSON_NUMERIC_CHECK);

echo $result;
