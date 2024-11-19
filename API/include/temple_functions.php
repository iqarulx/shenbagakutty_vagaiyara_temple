<?php
    class Temple_Functions extends User_Functions {
        // update profile photo
		public function UpdateMemberProfilePhoto() {
            $target_dir = "";
            $target_dir = $this->image_directory();

            $upload_dir = "";
            $upload_dir = $this->image_directory();

            //echo $target_dir."<br>";
            if(!empty($target_dir)) {
                $target_dir = str_replace("upload", "photos", $target_dir);
                $target_dir = $target_dir."part4/webp/part4/";
                //$webp_dir = $target_dir."webp/";
                $upload_dir = $upload_dir."member_photo/";
                $files = "";
                $files = array_diff(scandir($target_dir), array('.', '..'));
                //print_r($files);
                if(!empty($files)) {
                    $member_id_position_list = array(); $count = 0;
                    foreach($files as $file) { //echo $target_dir.$file."<br>";
                        if(!empty($file) && file_exists($target_dir.$file)) {
                            $image_name_extension = "";
                            $image_name_extension = explode(".", $file);
                            if(!empty($image_name_extension)) {
                                $image_name = ""; $extension = "";
                                if(!empty($image_name_extension['0'])) {
                                    $image_name = $image_name_extension['0'];
                                }
                                if(!empty($image_name_extension['1'])) {
                                    $extension = $image_name_extension['1'];
                                }
                                if(!empty($image_name) && !empty($extension)) {
                                    /*$im = ""; $webp_image = $image_name.'.webp';
									if(!file_exists($webp_dir.$webp_image)) {
										if($extension == "png" || $extension == "jpg" || $extension == "jpeg") {
											if($extension == "png") {
												$im = imagecreatefrompng($target_dir.$file);
											}
											else if($extension == "jpg" || $extension == "jpeg") {
												$im = imagecreatefromjpeg($target_dir.$file);
											}
											if(!empty($im)) {
												imagepalettetotruecolor($im);
												imagewebp($im, $webp_dir.$webp_image, 42);
												imagedestroy($im);

												if(file_exists($webp_dir.$webp_image)) {
													$count++;
												}
											}
										}	
									}*/
                                    $member_id_position = "";
                                    $member_id_position = explode("-", $image_name);
                                    if(!empty($member_id_position)) {
                                        $member_id = ""; $position = "";
                                        if(!empty($member_id_position['0'])) {
                                            $member_id = $member_id_position['0'];
                                        }
                                        if(!empty($member_id_position['1'])) {
                                            $position = $member_id_position['1'];
                                        }
                                        if(!empty($member_id) && !empty($position)) {
                                            $member_id_position_list[] = array('member_id' => $member_id, 'position' => $position, 'extension' => $extension);
                                        }
                                    }
                                }
                            }
                        }
                    }
                }
                //print_r($member_id_position_list);
                if(!empty($member_id_position_list)) {
                    $member_id_by_group = array();
                    foreach($member_id_position_list as $key => $item) {
                        if(array_key_exists('member_id', $item)) {
                            $member_id_by_group[$item['member_id']][$key] = $item;
                        }
                    }
                    ksort($member_id_by_group, SORT_NUMERIC);
                    //print_r($member_id_by_group);
                    if(!empty($member_id_by_group)) {
                        foreach($member_id_by_group as $value) {
                            if(!empty($value)) {
                                $new_member_id = ""; $old_images = array(); $new_images = array();
                                foreach($value as $data) {
                                    //echo $data['member_id'].", ".$data['position'].", ".$data['extension']."<br>";
                                    if(!empty($data['member_id']) && $data['position'] && $data['extension']) {      
                                        $new_member_id = $data['member_id'];                                  
                                        /*if($new_member_id < 10) {
                                            $new_member_id = "000".$new_member_id;
                                        }
                                        else if($new_member_id < 100) {
                                            $new_member_id = "00".$new_member_id;
                                        }
                                        else if($new_member_id < 1000) {
                                            $new_member_id = "0".$new_member_id;
                                        }*/
                                        if(!empty($new_member_id)) {
                                            $old_images[] = $data['member_id']."-".$data['position'].".".$data['extension'];
                                            $new_images[] = $new_member_id."-".$data['position'].".".$data['extension'];
                                        }
                                    }
                                }
                                //echo "new_member_id - ".$new_member_id; print_r($old_images); print_r($new_images);
                                //exit;
                                if(!empty($new_member_id) && !empty($old_images) && !empty($new_images)) {
                                    $new_images = implode(",", $new_images);
                                    //$new_member_id = $this->encode_decode('encrypt', $new_member_id);
                                    $member_unique_id = "";
                                    $member_unique_id = $this->getTableColumnValue($GLOBALS['member_table'], 'member_unique_number', $new_member_id, 'id');
                                    //echo "member_unique_id - ".$member_unique_id; exit;
                                    if(preg_match("/^\d+$/", $member_unique_id)) {
                                        $columns = array(); $values = array(); $member_update_id = "";
                                        $columns = array('profile_photo');
                                        $values = array("'".$new_images."'");
                                        $member_update_id = $this->UpdateSQL($GLOBALS['member_table'], $member_unique_id, $columns, $values, '');
                                        if(preg_match("/^\d+$/", $member_update_id)) {	
                                            $new_images = explode(",", $new_images);
                                            for($i = 0; $i < count($old_images); $i++) {
                                                if(!empty($old_images[$i]) && !empty($new_images) && file_exists($target_dir.$old_images[$i])) {
                                                    rename($target_dir.$old_images[$i], $target_dir.$new_images[$i]);
                                                    copy($target_dir.$new_images[$i], $upload_dir.$new_images[$i]);
                                                }
                                            }			
                                        }
                                    }
                                }
                            }                            
                        }
                    }
                }
            }
        }

        // get country list
		public function getMemberCountryList() {
            $select_query = ""; $list = array(); $country_list = array();
            $where = "country != '".$GLOBALS['null_value']."'";
            if(!empty($where)) {
                $select_query = "SELECT DISTINCT country FROM ".$GLOBALS['member_table']." 
                                    WHERE ".$where." AND country != '".$GLOBALS['null_value']."' AND deleted = '0'";
            }
            if(!empty($select_query)) {
			    $list = $this->getQueryRecords($GLOBALS['member_table'], $select_query);
            }
            if(!empty($list)) {
                foreach($list as $row) {
                    if(!empty($row['country'])) {
                        $country_list[] = $row['country'];
                    }
                }
            }
			return $country_list;
        }
        // get state list
		public function getMemberStateList($country) {
            $select_query = ""; $list = array(); $state_list = array(); 
            $where = "state != '".$GLOBALS['null_value']."'";
            if(!empty($country)) {
                if(!empty($where)) {
                    $where = $where." AND country = '".$country."'";
                }
            }
            if(!empty($where)) {
                $select_query = "SELECT state FROM ".$GLOBALS['member_table']." WHERE ".$where." AND deleted = '0' GROUP BY state";
            }
			if(!empty($select_query)) {
			    $list = $this->getQueryRecords($GLOBALS['member_table'], $select_query);
            }
            if(!empty($list)) {
                foreach($list as $row) {
                    if(!empty($row['state'])) {
                        $state_list[] = $row['state'];
                    }
                }
            }
			return $state_list;
        }
        // get city list
		public function getMemberCityList($country, $state) {
            $select_query = ""; $list = array(); $city_list = array(); 
            $where = "city != '".$GLOBALS['null_value']."'";
            if(!empty($country)) {
                if(!empty($where)) {
                    $where = $where." AND country = '".$country."'";
                }
            }
            if(!empty($state)) {
                if(!empty($where)) {
                    $where = $where." AND state = '".$state."'";
                }
            }
            if(!empty($where)) {
                $select_query = "SELECT city FROM ".$GLOBALS['member_table']." WHERE ".$where." AND deleted = '0' GROUP BY city";
            }
            else {
                $select_query = "SELECT city FROM ".$GLOBALS['member_table']." WHERE deleted = '0' GROUP BY city";  
            }
			if(!empty($select_query)) {
			    $list = $this->getQueryRecords($GLOBALS['member_table'], $select_query);
            }
            if(!empty($list)) {
                foreach($list as $row) {
                    if(!empty($row['city'])) {
                        $city_list[] = $row['city'];
                    }
                }
            }
			return $city_list;
        }

        // get initial list
		public function getMemberInitialList() {
            $select_query = ""; $list = array(); $initial_list = array();
            $select_query = "SELECT initial FROM ".$GLOBALS['member_table']." WHERE initial != '".$GLOBALS['null_value']."' AND deleted = '0' GROUP BY initial";
			$list = $this->getQueryRecords($GLOBALS['member_table'], $select_query);
            if(!empty($list)) {
                foreach($list as $row) {
                    if(!empty($row['initial'])) {
                        $initial_list[] = $row['initial'];
                    }
                }
            }
			return $initial_list;
        }
        // get father list
		public function getMemberFatherList($initial) {
            $select_query = ""; $list = array(); $father_ids = array(); $father_list = array(); 
            $where = "father_id != '".$GLOBALS['null_value']."'";
            if(!empty($initial)) {
                if(!empty($where)) {
                    $where = $where." AND initial = '".$initial."'";
                }
            }
            if(!empty($where)) {
                $select_query = "SELECT father_id FROM ".$GLOBALS['member_table']." WHERE ".$where." AND deleted = '0' GROUP BY father_id";
            }            
			if(!empty($select_query)) {
			    $list = $this->getQueryRecords($GLOBALS['member_table'], $select_query);
            }
            if(!empty($list)) {
                foreach($list as $row) {
                    if(!empty($row['father_id'])) {
                        $father_ids[] = $row['father_id'];
                    }
                }
                if(!empty($father_ids)) {
                    $father_ids = implode(",", $father_ids);
                    $select_query = "SELECT member_id, name, city, member_unique_number FROM ".$GLOBALS['member_table']." 
                                        WHERE FIND_IN_SET(member_unique_number, '".$father_ids."') AND deleted = '0' ORDER BY id ASC";
			        $father_list = $this->getQueryRecords($GLOBALS['member_table'], $select_query);
                }
            }
			return $father_list;
        }
        // get introducer list
		public function getMemberIntroducerList($initial) {
            $select_query = ""; $list = array(); $introducer_ids = array(); $introducer_list = array();
            $where = "introducer_id != '".$GLOBALS['null_value']."'";
            if(!empty($initial)) {
                if(!empty($where)) {
                    $where = $where." AND initial = '".$initial."'";
                }
            }
            if(!empty($where)) {
                $select_query = "SELECT introducer_id FROM ".$GLOBALS['member_table']." WHERE ".$where." AND deleted = '0' GROUP BY introducer_id";
            }
            if(!empty($select_query)) {
                $list = $this->getQueryRecords($GLOBALS['member_table'], $select_query);
            }
            if(!empty($list)) {
                foreach($list as $row) {
                    if(!empty($row['introducer_id'])) {
                        $introducer_ids[] = $row['introducer_id'];
                    }
                }
                if(!empty($introducer_ids)) {
                    $introducer_ids = implode(",", $introducer_ids);
                    $select_query = "SELECT member_id, name, city, member_unique_number FROM ".$GLOBALS['member_table']." 
                                        WHERE FIND_IN_SET(member_unique_number, '".$introducer_ids."') AND deleted = '0' ORDER BY id ASC";
			        $introducer_list = $this->getQueryRecords($GLOBALS['member_table'], $select_query);
                }
            }
			return $introducer_list;
        }
        // get mobile number list
		public function getMemberMobileNumberList() {
            $select_query = ""; $list = array(); $mobile_number_list = array();
            $select_query = "SELECT mobile_number FROM ".$GLOBALS['member_table']." 
                            WHERE mobile_number != '".$GLOBALS['null_value']."' AND deleted = '0' GROUP BY mobile_number";
			$list = $this->getQueryRecords($GLOBALS['member_table'], $select_query);
            if(!empty($list)) {
                foreach($list as $row) {
                    if(!empty($row['mobile_number'])) {
                        $mobile_number_list[] = $row['mobile_number'];
                    }
                }
            }
			return $mobile_number_list;
        }

        // get member list
        public function getMemberList($country, $state, $city, $status_id, $mobile_no_option, $initial, $father_id, $introducer_id, $mobile_number, $profession, $search_text, $page_start, $page_limit) {
            $select_query = ""; $list = array(); $where = "";

            if(!empty($country)) {
                $where = "m.country = '".$country."'";
            }
            if(!empty($state)) {
                if(!empty($where)) {
                    $where = $where." AND m.state = '".$state."'";
                }
                else {
                    $where = "m.state = '".$state."'";
                }
            }
            if(!empty($city)) {
                if(!empty($where)) {
                    $where = $where." AND m.city = '".$city."'";
                }
                else {
                    $where = "m.city = '".$city."'";
                }
            }
            if(!empty($status_id)) {
                if(!empty($where)) {
                    $where = $where." AND m.status_id = '".$status_id."'";
                }
                else {
                    $where = "m.status_id = '".$status_id."'";
                }
            }
            if(!empty($mobile_no_option)) {
                if($mobile_no_option == 1) {
                    if(!empty($where)) {
                        $where = $where." AND m.mobile_number != '".$GLOBALS['null_value']."'";
                    }
                    else {
                        $where = "m.mobile_number != '".$GLOBALS['null_value']."'";
                    }
                }
                else if($mobile_no_option == 2) {
                    if(!empty($where)) {
                        $where = $where." AND m.mobile_number = '".$GLOBALS['null_value']."'";
                    }
                    else {
                        $where = "m.mobile_number = '".$GLOBALS['null_value']."'";
                    }
                }
            }
            if(!empty($initial)) {
                if(!empty($where)) {
                    $where = $where." AND m.initial = '".$initial."'";
                }
                else {
                    $where = "m.initial = '".$initial."'";
                }
            }
            if(!empty($father_id)) {
                if(!empty($where)) {
                    $where = $where." AND m.father_id = '".$father_id."'";
                }
                else {
                    $where = "m.father_id = '".$father_id."'";
                }
            }
            if(!empty($introducer_id)) {
                if(!empty($where)) {
                    $where = $where." AND m.introducer_id = '".$introducer_id."'";
                }
                else {
                    $where = "m.introducer_id = '".$introducer_id."'";
                }
            }
            if(!empty($mobile_number)) {
                if(!empty($where)) {
                    $where = $where." AND mobile_number = '".$mobile_number."'";
                }
                else {
                    $where = "mobile_number = '".$mobile_number."'";
                }
            }
            if(!empty($profession)) {
                if(!empty($where)) {
                    $where = $where." AND m.profession = '".$profession."'";
                }
                else {
                    $where = "m.profession = '".$profession."'";
                }
            }
            if(!empty($search_text)) {
                if(!empty($where)) {
                    $where = $where." AND (m.status_base_member_id = '".$search_text."' OR m.member_unique_number = '".$search_text."' OR m.name LIKE '%".$search_text."%')";
                }
                else {
                    $where = "(m.status_base_member_id = '".$search_text."' OR m.member_unique_number = '".$search_text."' OR m.name LIKE '%".$search_text."%')";
                }
            }
            if(!empty($where)) {
                $select_query = "SELECT m.*, s.name as status_name, p.name as profession_name
                                    FROM ".$GLOBALS['member_table']." as m 
                                    LEFT JOIN ".$GLOBALS['status_table']." as s ON s.status_id = m.status_id
                                    LEFT JOIN ".$GLOBALS['profession_table']." as p ON p.profession_id = m.profession
                                    WHERE ".$where." AND m.deleted = '0' ORDER BY m.id DESC LIMIT ".$page_start.", ".$page_limit;
            }
            else {
                $select_query = "SELECT m.*, s.name as status_name, p.name as profession_name
                                    FROM ".$GLOBALS['member_table']." as m 
                                    LEFT JOIN ".$GLOBALS['status_table']." as s ON s.status_id = m.status_id
                                    LEFT JOIN ".$GLOBALS['profession_table']." as p ON p.profession_id = m.profession
                                    WHERE m.deleted = '0' ORDER BY m.id DESC LIMIT ".$page_start.", ".$page_limit;
            }
            //echo $select_query;
            if(!empty($select_query)) {
                $list = $this->getQueryRecords($GLOBALS['member_table'], $select_query);
            }
            return $list;
        }

        // get member list
        public function getTotalMebersCount($country, $state, $city, $status_id, $mobile_no_option, $initial, $father_id, $introducer_id, $mobile_number, $profession, $search_text) {
            $select_query = ""; $list = array(); $where = ""; $total_members_count = 0;

            if(!empty($country)) {
                $where = "country = '".$country."'";
            }
            if(!empty($state)) {
                if(!empty($where)) {
                    $where = $where." AND state = '".$state."'";
                }
                else {
                    $where = "state = '".$state."'";
                }
            }
            if(!empty($city)) {
                if(!empty($where)) {
                    $where = $where." AND city = '".$city."'";
                }
                else {
                    $where = "city = '".$city."'";
                }
            }
            if(!empty($status_id)) {
                if(!empty($where)) {
                    $where = $where." AND status_id = '".$status_id."'";
                }
                else {
                    $where = "status_id = '".$status_id."'";
                }
            }
            if(!empty($mobile_no_option)) {
                if($mobile_no_option == 1) {
                    if(!empty($where)) {
                        $where = $where." AND mobile_number != '".$GLOBALS['null_value']."'";
                    }
                    else {
                        $where = "mobile_number != '".$GLOBALS['null_value']."'";
                    }
                }
                else if($mobile_no_option == 2) {
                    if(!empty($where)) {
                        $where = $where." AND mobile_number = '".$GLOBALS['null_value']."'";
                    }
                    else {
                        $where = "mobile_number = '".$GLOBALS['null_value']."'";
                    }
                }
            }
            if(!empty($initial)) {
                if(!empty($where)) {
                    $where = $where." AND initial = '".$initial."'";
                }
                else {
                    $where = "initial = '".$initial."'";
                }
            }
            if(!empty($father_id)) {
                if(!empty($where)) {
                    $where = $where." AND father_id = '".$father_id."'";
                }
                else {
                    $where = "father_id = '".$father_id."'";
                }
            }
            if(!empty($introducer_id)) {
                if(!empty($where)) {
                    $where = $where." AND introducer_id = '".$introducer_id."'";
                }
                else {
                    $where = "introducer_id = '".$introducer_id."'";
                }
            }
            if(!empty($mobile_number)) {
                if(!empty($where)) {
                    $where = $where." AND mobile_number = '".$mobile_number."'";
                }
                else {
                    $where = "mobile_number = '".$mobile_number."'";
                }
            }
            if(!empty($profession)) {
                if(!empty($where)) {
                    $where = $where." AND profession = '".$profession."'";
                }
                else {
                    $where = "profession = '".$profession."'";
                }
            }
            if(!empty($search_text)) {
                if(!empty($where)) {
                    $where = $where." AND (status_base_member_id = '".$search_text."' OR name LIKE '%".$search_text."%')";
                }
                else {
                    $where = "(status_base_member_id = '".$search_text."' OR name LIKE '%".$search_text."%')";
                }
            }
            if(!empty($where)) {
                $select_query = "SELECT COUNT(id) as total_members_count FROM ".$GLOBALS['member_table']." WHERE ".$where." AND deleted = '0'";
            }
            else {
                $select_query = "SELECT COUNT(id) as total_members_count FROM ".$GLOBALS['member_table']." WHERE deleted = '0'";
            }
            //echo $select_query;
            if(!empty($select_query)) {
                $list = $this->getQueryRecords($GLOBALS['member_table'], $select_query);
                if(!empty($list)) {
                    foreach($list as $data) {
                        if(!empty($data['total_members_count'])) {
                            $total_members_count = $data['total_members_count'];
                        }
                    }
                }
            }
            return $total_members_count;
        }

        // check mandapam is already booking
		public function CheckMandapamAlreadyBooking($mandapam_id, $from_date, $to_date) {
            $select_query = ""; $list = array(); $where = ""; $date_where = ""; $booking_count = 0;

            if(!empty($mandapam_id)) {
                $where = "mandapam_id = '".$mandapam_id."'";
            }
            if(!empty($from_date)) {
                $from_date = date("Y-m-d", strtotime($from_date));
                $date_where = "('".$from_date."' >= DATE(from_date) AND '".$from_date."' <= DATE(to_date))";
            }
            if(!empty($to_date)) {
                $to_date = date("Y-m-d", strtotime($to_date));
                if(!empty($date_where)) {
                    $date_where = $date_where." OR ('".$to_date."' >= DATE(from_date) AND '".$to_date."' <= DATE(to_date))";
                }
                else {
                    $date_where = "('".$to_date."' >= DATE(from_date) AND '".$to_date."' <= DATE(to_date))";
                }                    
            }
            if(!empty($where)) {
                if(!empty($date_where)) {
                    $where = $where." AND (".$date_where.")";
                }
                $select_query = "SELECT COUNT(id) as booking_count FROM ".$GLOBALS['mandapam_booking_table']." WHERE ".$where." AND deleted = '0'";
            }
            //echo $select_query;
            if(!empty($select_query)) {
                $list = $this->getQueryRecords($GLOBALS['mandapam_booking_table'], $select_query);
                if(!empty($list)) {
                    foreach($list as $data) {
                        if(!empty($data['booking_count'])) {
                            $booking_count = $data['booking_count'];
                        }
                    }
                }
            }
            return $booking_count;
        }

        // get mandapam booking list
		public function getMandapamBookingList($mandapam_id, $from_date, $to_date) {
            $select_query = ""; $list = array(); $where = "";
            if(!empty($from_date)) {
                $from_date = date("Y-m-d", strtotime($from_date));
                $where = "DATE(mb.from_date) >= '".$from_date."'";
            }
            if(!empty($to_date)) {
                $to_date = date("Y-m-d", strtotime($to_date));
                if(!empty($where)) {
                    $where = $where." AND DATE(mb.to_date) <= '".$to_date."'";
                }
                else {
                    $where = "DATE(mb.to_date) <= '".$to_date."'";
                }
            }
            if(!empty($mandapam_id)) {
                if(!empty($where)) {
                    $where = $where." AND mb.mandapam_id = '".$mandapam_id."'";
                }
                else {
                    $where = "mb.mandapam_id = '".$mandapam_id."'";
                }
            }
            if(!empty($where)) {
                $select_query = "SELECT mb.*, m.name as mandapam_name FROM ".$GLOBALS['mandapam_booking_table']." as mb
                                    INNER JOIN ".$GLOBALS['mandapam_table']." as m ON m.mandapam_id = mb.mandapam_id
                                    WHERE ".$where." AND mb.deleted = '0' ORDER BY mb.id DESC";
            }
            else {
                $select_query = "SELECT mb.*, m.name as mandapam_name FROM ".$GLOBALS['mandapam_booking_table']." as mb
                                    INNER JOIN ".$GLOBALS['mandapam_table']." as m ON m.mandapam_id = mb.mandapam_id
                                    WHERE mb.deleted = '0' ORDER BY mb.id DESC";
            }
            //echo $select_query;
            if(!empty($select_query)) {
                $list = $this->getQueryRecords($GLOBALS['mandapam_booking_table'], $select_query);
            }
            return $list;
        }

        // get dashboard booking date list
		public function getDashboardBookingDatelist($booking_month, $booking_year, $booking_month_last_date, $mandapam_id) {
            $booking_date_list = array(); $booking_dates = array();
            if(!empty($booking_month) && preg_match("/^\d+$/", $booking_month)) {
                if(!empty($booking_year) && preg_match("/^\d+$/", $booking_year)) {
                    if(!empty($booking_month_last_date) && preg_match("/^\d+$/", $booking_month_last_date)) {
    
                        if($booking_month < 10) {
                            $booking_month = "0".$booking_month;
                        }
    
                        $from_date = "01-".$booking_month."-".$booking_year;
                        $to_date = $booking_month_last_date."-".$booking_month."-".$booking_year;
                        //echo "from_date - ".$from_date.", to_date - ".$to_date.", mandapam_id - ".$mandapam_id;
    
                        $mandapam_booking_list = array();
                        $mandapam_booking_list = $this->getMandapamBookingList($mandapam_id, $from_date, $to_date);
                        //print_r($mandapam_booking_list);
    
                        if(!empty($mandapam_booking_list)) {
                            foreach($mandapam_booking_list as $data) {
                                $booking_from_date = ""; $booking_to_date = "";
                                if(!empty($data['from_date']) && $data['from_date'] != "0000-00-00") {
                                    $booking_from_date = date("d-m-Y", strtotime($data['from_date']));
                                }
                                if(!empty($data['to_date']) && $data['to_date'] != "0000-00-00") {
                                    $booking_to_date = date("d-m-Y", strtotime($data['to_date']));
                                }
                                if(!empty($booking_from_date) && !empty($booking_to_date)) {
                                    $days = 0;
                                    $days = round( ( strtotime($booking_to_date) - strtotime($booking_from_date) ) / (60 * 60 * 24));
                                    $days = $days + 1;
                                    //echo "booking_from_date - ".$booking_from_date.", booking_to_date - ".$booking_to_date.", days - ".$days."<br>";
                                    if(!empty($days)) {
                                        if(!in_array($booking_from_date, $booking_date_list)) {
                                            $booking_date_list[] = $booking_from_date;
                                        }
                                        for($i = 1; $i < $days; $i++) {
                                            $next_date = "";
                                            $next_date = date('d-m-Y', strtotime('+'.$i.'days', strtotime($booking_from_date)));
                                            if(!in_array($next_date, $booking_date_list)) {
                                                $booking_date_list[] = $next_date;
                                            }
                                        }
                                    }
                                }
                            }
                        }
                        //print_r($booking_date_list);
                        if(!empty($booking_date_list)) {
                            usort($booking_date_list, function ($a, $b) {
                                return strtotime($a) - strtotime($b);
                            });
                            if(!empty($booking_date_list)) {
                                foreach($booking_date_list as $date_value) {
                                    if(!empty($date_value)) {
                                        $date_value = explode("-", $date_value);
                                        if(!empty($date_value['0'])) {
                                            $value = $date_value['0'];
                                            if($value['0'] == '0') {
                                                $value = substr($value, 1);
                                            }
                                            $booking_dates[] = $value;
                                        }
                                    }
                                }
                            }
                        }
                        //print_r($booking_date_list);
    
                    }
                }
            }
            if(!empty($booking_dates)) {
                $booking_dates = implode("$$$", $booking_dates);
            }
            else {
                $booking_dates = "";
            }
            return $booking_dates;
        }

        // get receipt members list
		public function getReceiptMembersList($member_id) {
            $select_query = ""; $list = array(); $member_ids = array();
            if(!empty($member_id)) {
                $new_thalakattu_query = "SELECT member_id FROM ".$GLOBALS['new_thalakattu_table']." WHERE creator = '".$member_id."' AND deleted = '0' 
                                            GROUP BY member_id";
                $old_thalakattu_query = "SELECT member_id FROM ".$GLOBALS['old_thalakattu_table']." WHERE creator = '".$member_id."' AND deleted = '0'
                                            GROUP BY member_id";
                $new_member_registration_query = "SELECT member_id FROM ".$GLOBALS['new_member_registration_table']." WHERE creator = '".$member_id."' 
                                            AND deleted = '0' GROUP BY member_id";
                $gold_silver_dollar_query = "SELECT member_id FROM ".$GLOBALS['gold_silver_dollar_table']." WHERE creator = '".$member_id."' 
                                            AND deleted = '0' GROUP BY member_id";
                $pooja_donation_query = "SELECT member_id FROM ".$GLOBALS['pooja_donation_table']." WHERE creator = '".$member_id."' AND deleted = '0'
                                            GROUP BY member_id";
                $mudi_kaanikai_query = "SELECT member_id FROM ".$GLOBALS['mudi_kaanikai_table']." WHERE creator = '".$member_id."' AND deleted = '0' 
                                            GROUP BY member_id";
                $nandavanam_query = "SELECT member_id FROM ".$GLOBALS['nandavanam_table']." WHERE creator = '".$member_id."' AND deleted = '0'
                                        GROUP BY member_id";
                $personal_savings_query = "SELECT member_id FROM ".$GLOBALS['personal_savings_table']." WHERE creator = '".$member_id."' AND deleted = '0'
                                                GROUP BY member_id";
                $general_donation_query = "SELECT member_id FROM ".$GLOBALS['general_donation_table']." WHERE creator = '".$member_id."' AND deleted = '0'
                                            GROUP BY member_id";
                $member_select_query = "SELECT member_id FROM ( (".$new_thalakattu_query.") UNION ALL (".$old_thalakattu_query.") UNION ALL 
                                    (".$new_member_registration_query.") UNION ALL (".$gold_silver_dollar_query.") UNION ALL (".$pooja_donation_query.") 
                                    UNION ALL (".$mudi_kaanikai_query.") UNION ALL (".$nandavanam_query.") UNION ALL (".$personal_savings_query.") UNION ALL 
                                    (".$general_donation_query.") ) as g GROUP BY member_id";
                if(!empty($member_select_query)) {
                    $list = $this->getQueryRecords($GLOBALS['member_table'], $member_select_query);
                    if(!empty($list)) {
                        foreach($list as $data) {
                            if(!empty($data['member_id'])) {
                                $member_ids[] = $data['member_id'];
                            }
                        }
                    }
                }
                if(!empty($member_ids)) {
                    $member_ids = implode(",", $member_ids);
                    $select_query = "SELECT member_id, status_base_member_id, name, city, mobile_number FROM ".$GLOBALS['member_table']." 
                                        WHERE status_prefix != '8' AND status_prefix != '9' AND FIND_IN_SET(member_id, '".$member_ids."') AND deleted = '0'
                                        ORDER BY id DESC";
                }
            }
            else {
                $select_query = "SELECT member_id, status_base_member_id, name, city, mobile_number FROM ".$GLOBALS['member_table']." 
                                    WHERE status_prefix != '8' AND status_prefix != '9' AND deleted = '0' ORDER BY id DESC";
            }
            if(!empty($select_query)) {
                $list = $this->getQueryRecords($GLOBALS['member_table'], $select_query);
            }
            return $list;
        }

        // get receipt non members list
		public function getReceiptNonMembersList($member_id) {
            $select_query = ""; $list = array(); $non_members_list = array();
            if(!empty($member_id)) {
                $select_query = "SELECT member_name FROM ".$GLOBALS['nandavanam_table']." WHERE member_id = '".$GLOBALS['null_value']."' 
                                AND status_base_member_id = '".$GLOBALS['null_value']."' AND creator = '".$member_id."' AND deleted = '0' 
                                GROUP BY member_name ORDER BY id DESC";
            }
            else {
                $select_query = "SELECT member_name FROM ".$GLOBALS['nandavanam_table']." WHERE member_id = '".$GLOBALS['null_value']."' 
                                AND status_base_member_id = '".$GLOBALS['null_value']."' AND deleted = '0' 
                                GROUP BY member_name ORDER BY id DESC";
            }                    
            if(!empty($select_query)) {
                $list = $this->getQueryRecords($GLOBALS['nandavanam_table'], $select_query);
                if(!empty($list)) {
                    foreach($list as $data) {
                        if(!empty($data['member_name'])) {
                            $non_members_list[] = $data['member_name'];
                        }
                    }
                }
            }
            return $non_members_list;
        }
        
        // get receipt creator members list
		public function getReceiptCreatorMembersList() {
            $select_query = ""; $list = array();
            $new_thalakattu_query = "SELECT creator_name FROM ".$GLOBALS['new_thalakattu_table']." WHERE deleted = '0' GROUP BY creator_name";
            $old_thalakattu_query = "SELECT creator_name FROM ".$GLOBALS['old_thalakattu_table']." WHERE deleted = '0' GROUP BY creator_name";
            $new_member_registration_query = "SELECT creator_name FROM ".$GLOBALS['new_member_registration_table']." WHERE deleted = '0' 
                                                GROUP BY creator_name";
            $gold_silver_dollar_query = "SELECT creator_name FROM ".$GLOBALS['gold_silver_dollar_table']." WHERE deleted = '0' GROUP BY creator_name";
            $pooja_donation_query = "SELECT creator_name FROM ".$GLOBALS['pooja_donation_table']." WHERE deleted = '0' GROUP BY creator_name";
            $mudi_kaanikai_query = "SELECT creator_name FROM ".$GLOBALS['mudi_kaanikai_table']." WHERE deleted = '0' GROUP BY creator_name";
            $nandavanam_query = "SELECT creator_name FROM ".$GLOBALS['nandavanam_table']." WHERE deleted = '0' GROUP BY creator_name";
            $personal_savings_query = "SELECT creator_name FROM ".$GLOBALS['personal_savings_table']." WHERE deleted = '0' GROUP BY creator_name";
            $general_donation_query = "SELECT creator_name FROM ".$GLOBALS['general_donation_table']." WHERE deleted = '0' GROUP BY creator_name";
            $select_query = "SELECT creator_name FROM ( (".$new_thalakattu_query.") UNION ALL (".$old_thalakattu_query.") UNION ALL 
                                (".$new_member_registration_query.") UNION ALL (".$gold_silver_dollar_query.") UNION ALL (".$pooja_donation_query.") 
                                UNION ALL (".$mudi_kaanikai_query.") UNION ALL (".$nandavanam_query.") UNION ALL (".$personal_savings_query.") UNION ALL 
                                (".$general_donation_query.") ) as g GROUP BY creator_name";
            if(!empty($select_query)) {
                $list = $this->getQueryRecords($GLOBALS['member_table'], $select_query);
            }
            return $list;
        }

        // get all receipt list
		public function getAllReceiptList($from_date, $to_date, $receipt_category_id, $receipt_type_id, $member_id, $non_member_name, $creator, $search_text, $page_start, $page_end, $ordering, $receipt_id) {
            $select_query = ""; $list = array(); $where = ""; $nandavanam_where = "";

            if(!empty($receipt_id)) {
                $where = "r.receipt_id = '".$receipt_id."'";
                $nandavanam_where = "r.receipt_id = '".$receipt_id."'";
            }
            else {
                if(!empty($from_date)) {
                    $from_date = date("Y-m-d", strtotime($from_date));
                    $where = "DATE(r.receipt_date) >= '".$from_date."'";
                    $nandavanam_where = "DATE(r.receipt_date) >= '".$from_date."'";
                }
                if(!empty($to_date)) {
                    $to_date = date("Y-m-d", strtotime($to_date));
                    if(!empty($where)) {
                        $where = $where." AND DATE(r.receipt_date) <= '".$to_date."'";
                    }
                    else {
                        $where = "DATE(r.receipt_date) <= '".$to_date."'";
                    }
                    if(!empty($nandavanam_where)) {
                        $nandavanam_where = $nandavanam_where." AND DATE(r.receipt_date) <= '".$to_date."'";
                    }
                    else {
                        $nandavanam_where = "DATE(r.receipt_date) <= '".$to_date."'";
                    }
                }
                if(!empty($receipt_category_id)) {
                    if(!empty($where)) {
                        $where = $where." AND t.type = '".$receipt_category_id."'";
                    }
                    else {
                        $where = "t.type = '".$receipt_category_id."'";
                    }
                    if(!empty($nandavanam_where)) {
                        $nandavanam_where = $nandavanam_where." AND t.type = '".$receipt_category_id."'";
                    }
                    else {
                        $nandavanam_where = "t.type = '".$receipt_category_id."'";
                    }
                }
                if(!empty($receipt_type_id)) {
                    if(!empty($where)) {
                        $where = $where." AND r.receipt_type_id = '".$receipt_type_id."'";
                    }
                    else {
                        $where = "r.receipt_type_id = '".$receipt_type_id."'";
                    }
                    if(!empty($nandavanam_where)) {
                        $nandavanam_where = $nandavanam_where." AND r.receipt_type_id = '".$receipt_type_id."'";
                    }
                    else {
                        $nandavanam_where = "r.receipt_type_id = '".$receipt_type_id."'";
                    }
                }
                if(!empty($member_id)) {
                    if(!empty($where)) {
                        $where = $where." AND r.member_id = '".$member_id."'";
                    }
                    else {
                        $where = "r.member_id = '".$member_id."'";
                    }
                    if(!empty($nandavanam_where)) {
                        $nandavanam_where = $nandavanam_where." AND r.member_id = '".$member_id."'";
                    }
                    else {
                        $nandavanam_where = "r.member_id = '".$member_id."'";
                    }
                }

                if(!empty($non_member_name)) {
                    if(!empty($nandavanam_where)) {
                        if(!empty($member_id)) {
                            $nandavanam_where = $nandavanam_where." OR (r.member_name LIKE '%".$non_member_name."%')";
                        }
                        else {
                            $nandavanam_where = $nandavanam_where." AND (r.member_name LIKE '%".$non_member_name."%')";
                        }
                    }
                    else {
                        $nandavanam_where = "(r.member_name LIKE '%".$non_member_name."%')";
                    }
                }
                if(!empty($creator)) {
                    if(!empty($where)) {
                        $where = $where." AND r.creator_name = '".$creator."'";
                    }
                    else {
                        $where = "r.creator_name = '".$creator."'";
                    }
                    if(!empty($nandavanam_where)) {
                        $nandavanam_where = $nandavanam_where." AND r.creator_name = '".$creator."'";
                    }
                    else {
                        $nandavanam_where = "r.creator_name = '".$creator."'";
                    }
                }
                if(!empty($search_text)) {
                    if(!empty($where)) {
                        $where = $where." AND (r.receipt_number LIKE '%".$search_text."%')";
                    }
                    else {
                        $where = "(r.receipt_number LIKE '%".$search_text."%')";
                    }
                    if(!empty($nandavanam_where)) {
                        $nandavanam_where = $nandavanam_where." AND (r.receipt_number LIKE '%".$search_text."%')";
                    }
                    else {
                        $nandavanam_where = "(r.receipt_number LIKE '%".$search_text."%')";
                    }
                }
            }    

            if(!empty($non_member_name) && empty($member_id)) {
                $select_query = "SELECT r.creator_name, r.receipt_id, r.receipt_date, r.receipt_number, r.member_id, r.member_name,
                                            r.status_base_member_id, '' as year_amount, '' as poojai_from_date, '' as poojai_to_date, '' as poojai_amount,
                                            r.amount, '' as description, '' as function_date, '' as count_for_mudikanikai, '' as count_for_kadhu_kuthu,
                                            r.funeral_to, r.receipt_type_id, t.color_code, t.name as receipt_type_name, t.form_name
                                            FROM ".$GLOBALS['nandavanam_table']." as r
                                            INNER JOIN ".$GLOBALS['receipt_type_table']." as t ON t.receipt_type_id = r.receipt_type_id
                                            WHERE ".$nandavanam_where." AND r.deleted = '0'";
            }
            else {
                if(!empty($where)) {
                    $new_thalakattu_query = "SELECT r.creator_name, r.receipt_id, r.receipt_date, r.receipt_number, r.member_id, r.member_name,
                                                r.status_base_member_id, r.year_amount, r.poojai_from_date, r.poojai_to_date, r.poojai_amount, r.amount,
                                                '' as description, '' as function_date, '' as count_for_mudikanikai, '' as count_for_kadhu_kuthu,
                                                '' as funeral_to, r.receipt_type_id, t.color_code, t.name as receipt_type_name, t.form_name
                                                FROM ".$GLOBALS['new_thalakattu_table']." as r
                                                INNER JOIN ".$GLOBALS['receipt_type_table']." as t ON t.receipt_type_id = r.receipt_type_id
                                                WHERE ".$where." AND r.deleted = '0'";
                }
                else {
                    $new_thalakattu_query = "SELECT r.creator_name, r.receipt_id, r.receipt_date, r.receipt_number, r.member_id, r.member_name,
                                                r.status_base_member_id, r.year_amount, r.poojai_from_date, r.poojai_to_date, r.poojai_amount, r.amount,
                                                '' as description, '' as function_date, '' as count_for_mudikanikai, '' as count_for_kadhu_kuthu,
                                                '' as funeral_to, r.receipt_type_id, t.color_code, t.name as receipt_type_name, t.form_name
                                                FROM ".$GLOBALS['new_thalakattu_table']." as r
                                                INNER JOIN ".$GLOBALS['receipt_type_table']." as t ON t.receipt_type_id = r.receipt_type_id
                                                WHERE r.deleted = '0'";
                }

                if(!empty($where)) {
                    $old_thalakattu_query = "SELECT r.creator_name, r.receipt_id, r.receipt_date, r.receipt_number, r.member_id, r.member_name,
                                                r.status_base_member_id, '' as year_amount, '' as poojai_from_date, '' as poojai_to_date, '' as poojai_amount,
                                                r.amount, r.description, '' as function_date, '' as count_for_mudikanikai, '' as count_for_kadhu_kuthu,
                                                '' as funeral_to, r.receipt_type_id, t.color_code, t.name as receipt_type_name, t.form_name
                                                FROM ".$GLOBALS['old_thalakattu_table']." as r
                                                INNER JOIN ".$GLOBALS['receipt_type_table']." as t ON t.receipt_type_id = r.receipt_type_id
                                                WHERE ".$where." AND r.deleted = '0'";
                }
                else {
                    $old_thalakattu_query = "SELECT r.creator_name, r.receipt_id, r.receipt_date, r.receipt_number, r.member_id, r.member_name,
                                                r.status_base_member_id, '' as year_amount, '' as poojai_from_date, '' as poojai_to_date, '' as poojai_amount,
                                                r.amount, r.description, '' as function_date, '' as count_for_mudikanikai, '' as count_for_kadhu_kuthu,
                                                '' as funeral_to, r.receipt_type_id, t.color_code, t.name as receipt_type_name, t.form_name
                                                FROM ".$GLOBALS['old_thalakattu_table']." as r
                                                INNER JOIN ".$GLOBALS['receipt_type_table']." as t ON t.receipt_type_id = r.receipt_type_id
                                                WHERE r.deleted = '0'";
                }

                if(!empty($where)) {
                    $new_member_registration_query = "SELECT r.creator_name, r.receipt_id, r.receipt_date, r.receipt_number, r.member_id, r.member_name,
                                                        r.status_base_member_id, '' as year_amount, '' as poojai_from_date, '' as poojai_to_date,
                                                        '' as poojai_amount, r.amount, '' as description, '' as function_date, '' as count_for_mudikanikai,
                                                        '' as count_for_kadhu_kuthu, '' as funeral_to, r.receipt_type_id, t.color_code,
                                                        t.name as receipt_type_name, t.form_name
                                                        FROM ".$GLOBALS['new_member_registration_table']." as r
                                                        INNER JOIN ".$GLOBALS['receipt_type_table']." as t ON t.receipt_type_id = r.receipt_type_id
                                                        WHERE ".$where." AND r.deleted = '0'";
                }
                else {
                    $new_member_registration_query = "SELECT r.creator_name, r.receipt_id, r.receipt_date, r.receipt_number, r.member_id, r.member_name,
                                                        r.status_base_member_id, '' as year_amount, '' as poojai_from_date, '' as poojai_to_date,
                                                        '' as poojai_amount, r.amount, '' as description, '' as function_date, '' as count_for_mudikanikai,
                                                        '' as count_for_kadhu_kuthu, '' as funeral_to, r.receipt_type_id, t.color_code,
                                                        t.name as receipt_type_name, t.form_name
                                                        FROM ".$GLOBALS['new_member_registration_table']." as r
                                                        INNER JOIN ".$GLOBALS['receipt_type_table']." as t ON t.receipt_type_id = r.receipt_type_id
                                                        WHERE r.deleted = '0'";
                }

                if(!empty($where)) {
                    $gold_silver_dollar_query = "SELECT r.creator_name, r.receipt_id, r.receipt_date, r.receipt_number, r.member_id, r.member_name,
                                                    r.status_base_member_id, '' as year_amount, '' as poojai_from_date, '' as poojai_to_date,
                                                    '' as poojai_amount, r.amount, r.description, '' as function_date, '' as count_for_mudikanikai,
                                                    '' as count_for_kadhu_kuthu, '' as funeral_to, r.receipt_type_id, t.color_code,
                                                    t.name as receipt_type_name, t.form_name
                                                    FROM ".$GLOBALS['gold_silver_dollar_table']." as r
                                                    INNER JOIN ".$GLOBALS['receipt_type_table']." as t ON t.receipt_type_id = r.receipt_type_id
                                                    WHERE ".$where." AND r.deleted = '0'";
                }
                else {
                    $gold_silver_dollar_query = "SELECT r.creator_name, r.receipt_id, r.receipt_date, r.receipt_number, r.member_id, r.member_name,
                                                    r.status_base_member_id, '' as year_amount, '' as poojai_from_date, '' as poojai_to_date,
                                                    '' as poojai_amount, r.amount, r.description, '' as function_date, '' as count_for_mudikanikai,
                                                    '' as count_for_kadhu_kuthu, '' as funeral_to, r.receipt_type_id, t.color_code,
                                                    t.name as receipt_type_name, t.form_name
                                                    FROM ".$GLOBALS['gold_silver_dollar_table']." as r
                                                    INNER JOIN ".$GLOBALS['receipt_type_table']." as t ON t.receipt_type_id = r.receipt_type_id
                                                    WHERE r.deleted = '0'";
                }

                if(!empty($where)) {
                    $pooja_donation_query = "SELECT r.creator_name, r.receipt_id, r.receipt_date, r.receipt_number, r.member_id, r.member_name,
                                                r.status_base_member_id, '' as year_amount, '' as poojai_from_date, '' as poojai_to_date, '' as poojai_amount,
                                                r.amount, '' as description, '' as function_date, '' as count_for_mudikanikai, '' as count_for_kadhu_kuthu,
                                                '' as funeral_to, r.receipt_type_id, t.color_code, t.name as receipt_type_name, t.form_name
                                                FROM ".$GLOBALS['pooja_donation_table']." as r
                                                INNER JOIN ".$GLOBALS['receipt_type_table']." as t ON t.receipt_type_id = r.receipt_type_id
                                                WHERE ".$where." AND r.deleted = '0'";
                }
                else {
                    $pooja_donation_query = "SELECT r.creator_name, r.receipt_id, r.receipt_date, r.receipt_number, r.member_id, r.member_name,
                                                r.status_base_member_id, '' as year_amount, '' as poojai_from_date, '' as poojai_to_date, '' as poojai_amount,
                                                r.amount, '' as description, '' as function_date, '' as count_for_mudikanikai, '' as count_for_kadhu_kuthu,
                                                '' as funeral_to, r.receipt_type_id, t.color_code, t.name as receipt_type_name, t.form_name
                                                FROM ".$GLOBALS['pooja_donation_table']." as r
                                                INNER JOIN ".$GLOBALS['receipt_type_table']." as t ON t.receipt_type_id = r.receipt_type_id
                                                WHERE r.deleted = '0'";
                }

                if(!empty($where)) {
                    $mudi_kaanikai_query = "SELECT r.creator_name, r.receipt_id, r.receipt_date, r.receipt_number, r.member_id, r.member_name,
                                                r.status_base_member_id, '' as year_amount, '' as poojai_from_date, '' as poojai_to_date, '' as poojai_amount,
                                                r.amount, '' as description, r.function_date, r.count_for_mudikanikai, r.count_for_kadhu_kuthu,
                                                '' as funeral_to, r.receipt_type_id, t.color_code, t.name as receipt_type_name, t.form_name
                                                FROM ".$GLOBALS['mudi_kaanikai_table']." as r
                                                INNER JOIN ".$GLOBALS['receipt_type_table']." as t ON t.receipt_type_id = r.receipt_type_id
                                                WHERE ".$where." AND r.deleted = '0'";
                }
                else {
                    $mudi_kaanikai_query = "SELECT r.creator_name, r.receipt_id, r.receipt_date, r.receipt_number, r.member_id, r.member_name,
                                                r.status_base_member_id, '' as year_amount, '' as poojai_from_date, '' as poojai_to_date, '' as poojai_amount,
                                                r.amount, '' as description, r.function_date, r.count_for_mudikanikai, r.count_for_kadhu_kuthu,
                                                '' as funeral_to, r.receipt_type_id, t.color_code, t.name as receipt_type_name, t.form_name
                                                FROM ".$GLOBALS['mudi_kaanikai_table']." as r
                                                INNER JOIN ".$GLOBALS['receipt_type_table']." as t ON t.receipt_type_id = r.receipt_type_id
                                                WHERE r.deleted = '0'";
                }

                if(!empty($nandavanam_where)) {
                    $nandavanam_query = "SELECT r.creator_name, r.receipt_id, r.receipt_date, r.receipt_number, r.member_id, r.member_name,
                                                r.status_base_member_id, '' as year_amount, '' as poojai_from_date, '' as poojai_to_date, '' as poojai_amount,
                                                r.amount, '' as description, '' as function_date, '' as count_for_mudikanikai, '' as count_for_kadhu_kuthu,
                                                r.funeral_to, r.receipt_type_id, t.color_code, t.name as receipt_type_name, t.form_name
                                                FROM ".$GLOBALS['nandavanam_table']." as r
                                                INNER JOIN ".$GLOBALS['receipt_type_table']." as t ON t.receipt_type_id = r.receipt_type_id
                                                WHERE ".$nandavanam_where." AND r.deleted = '0'";
                }
                else {
                    $nandavanam_query = "SELECT r.creator_name, r.receipt_id, r.receipt_date, r.receipt_number, r.member_id, r.member_name,
                                                r.status_base_member_id, '' as year_amount, '' as poojai_from_date, '' as poojai_to_date, '' as poojai_amount,
                                                r.amount, '' as description, '' as function_date, '' as count_for_mudikanikai, '' as count_for_kadhu_kuthu,
                                                r.funeral_to, r.receipt_type_id, t.color_code, t.name as receipt_type_name, t.form_name
                                                FROM ".$GLOBALS['nandavanam_table']." as r
                                                INNER JOIN ".$GLOBALS['receipt_type_table']." as t ON t.receipt_type_id = r.receipt_type_id
                                                WHERE r.deleted = '0'";
                }

                if(!empty($where)) {
                    $personal_savings_query = "SELECT r.creator_name, r.receipt_id, r.receipt_date, r.receipt_number, r.member_id, r.member_name,
                                                r.status_base_member_id, '' as year_amount, '' as poojai_from_date, '' as poojai_to_date, '' as poojai_amount,
                                                r.amount, '' as description, '' as function_date, '' as count_for_mudikanikai, '' as count_for_kadhu_kuthu,
                                                '' as funeral_to, r.receipt_type_id, t.color_code, t.name as receipt_type_name, t.form_name
                                                FROM ".$GLOBALS['personal_savings_table']." as r
                                                INNER JOIN ".$GLOBALS['receipt_type_table']." as t ON t.receipt_type_id = r.receipt_type_id
                                                WHERE ".$where." AND r.deleted = '0'";
                }
                else {
                    $personal_savings_query = "SELECT r.creator_name, r.receipt_id, r.receipt_date, r.receipt_number, r.member_id, r.member_name,
                                                r.status_base_member_id, '' as year_amount, '' as poojai_from_date, '' as poojai_to_date, '' as poojai_amount,
                                                r.amount, '' as description, '' as function_date, '' as count_for_mudikanikai, '' as count_for_kadhu_kuthu,
                                                '' as funeral_to, r.receipt_type_id, t.color_code, t.name as receipt_type_name, t.form_name
                                                FROM ".$GLOBALS['personal_savings_table']." as r
                                                INNER JOIN ".$GLOBALS['receipt_type_table']." as t ON t.receipt_type_id = r.receipt_type_id
                                                WHERE r.deleted = '0'";
                }

                if(!empty($where)) {
                    $general_donation_query = "SELECT r.creator_name, r.receipt_id, r.receipt_date, r.receipt_number, r.member_id, r.member_name,
                                                r.status_base_member_id, '' as year_amount, '' as poojai_from_date, '' as poojai_to_date, '' as poojai_amount,
                                                r.amount, '' as description, '' as function_date, '' as count_for_mudikanikai, '' as count_for_kadhu_kuthu,
                                                '' as funeral_to, r.receipt_type_id, t.color_code, t.name as receipt_type_name, t.form_name
                                                FROM ".$GLOBALS['general_donation_table']." as r
                                                INNER JOIN ".$GLOBALS['receipt_type_table']." as t ON t.receipt_type_id = r.receipt_type_id
                                                WHERE ".$where." AND r.deleted = '0'";
                }
                else {
                    $general_donation_query = "SELECT r.creator_name, r.receipt_id, r.receipt_date, r.receipt_number, r.member_id, r.member_name,
                                                r.status_base_member_id, '' as year_amount, '' as poojai_from_date, '' as poojai_to_date, '' as poojai_amount,
                                                r.amount, '' as description, '' as function_date, '' as count_for_mudikanikai, '' as count_for_kadhu_kuthu,
                                                '' as funeral_to, r.receipt_type_id, t.color_code, t.name as receipt_type_name, t.form_name
                                                FROM ".$GLOBALS['general_donation_table']." as r
                                                INNER JOIN ".$GLOBALS['receipt_type_table']." as t ON t.receipt_type_id = r.receipt_type_id
                                                WHERE r.deleted = '0'";
                }  

                if(!empty($page_start) && !empty($page_end)) {
                    $select_query = "SELECT creator_name, receipt_id, receipt_date, receipt_number, member_id, member_name, status_base_member_id, year_amount,
                                    poojai_from_date, poojai_to_date, poojai_amount, amount, description, function_date, count_for_mudikanikai,
                                    count_for_kadhu_kuthu, funeral_to, receipt_type_id, color_code, receipt_type_name, form_name
                                    FROM ( (".$new_thalakattu_query.") UNION ALL (".$old_thalakattu_query.") UNION ALL (".$new_member_registration_query.") 
                                            UNION ALL (".$gold_silver_dollar_query.") UNION ALL (".$pooja_donation_query.") UNION ALL (".$mudi_kaanikai_query.")
                                            UNION ALL (".$nandavanam_query.") UNION ALL (".$personal_savings_query.") UNION ALL (".$general_donation_query.")
                                        ) as g ORDER BY receipt_date ".$ordering." LIMIT ".$page_start.", ".$page_end;
                }
                else {
                    $select_query = "SELECT creator_name, receipt_id, receipt_date, receipt_number, member_id, member_name, status_base_member_id, year_amount,
                                    poojai_from_date, poojai_to_date, poojai_amount, amount, description, function_date, count_for_mudikanikai,
                                    count_for_kadhu_kuthu, funeral_to, receipt_type_id, color_code, receipt_type_name, form_name
                                    FROM ( (".$new_thalakattu_query.") UNION ALL (".$old_thalakattu_query.") UNION ALL (".$new_member_registration_query.") 
                                            UNION ALL (".$gold_silver_dollar_query.") UNION ALL (".$pooja_donation_query.") UNION ALL (".$mudi_kaanikai_query.")
                                            UNION ALL (".$nandavanam_query.") UNION ALL (".$personal_savings_query.") UNION ALL (".$general_donation_query.")
                                        ) as g ORDER BY receipt_date ".$ordering;
                }
            }    
            //echo $select_query;
            if(!empty($select_query)) {
                $list = $this->getQueryRecords($GLOBALS['new_thalakattu_table'], $select_query);
            }
            return $list;
        }

        // get total all receipt count
		public function getTotalAllReceiptCount($from_date, $to_date, $receipt_category_id, $receipt_type_id, $member_id, $non_member_name, $creator, $search_text) {
            $select_query = ""; $list = array(); $where = ""; $nandavanam_where = ""; $receipt_count = 0;

            if(!empty($from_date)) {
                $from_date = date("Y-m-d", strtotime($from_date));
                $where = "DATE(r.receipt_date) >= '".$from_date."'";
                $nandavanam_where = "DATE(r.receipt_date) >= '".$from_date."'";
            }
            if(!empty($to_date)) {
                $to_date = date("Y-m-d", strtotime($to_date));
                if(!empty($where)) {
                    $where = $where." AND DATE(r.receipt_date) <= '".$to_date."'";
                }
                else {
                    $where = "DATE(r.receipt_date) <= '".$to_date."'";
                }
                if(!empty($nandavanam_where)) {
                    $nandavanam_where = $nandavanam_where." AND DATE(r.receipt_date) <= '".$to_date."'";
                }
                else {
                    $nandavanam_where = "DATE(r.receipt_date) <= '".$to_date."'";
                }
            }
            if(!empty($receipt_category_id)) {
                if(!empty($where)) {
                    $where = $where." AND t.type = '".$receipt_category_id."'";
                }
                else {
                    $where = "t.type = '".$receipt_category_id."'";
                }
                if(!empty($nandavanam_where)) {
                    $nandavanam_where = $nandavanam_where." AND t.type = '".$receipt_category_id."'";
                }
                else {
                    $nandavanam_where = "t.type = '".$receipt_category_id."'";
                }
            }
            if(!empty($receipt_type_id)) {
                if(!empty($where)) {
                    $where = $where." AND r.receipt_type_id = '".$receipt_type_id."'";
                }
                else {
                    $where = "r.receipt_type_id = '".$receipt_type_id."'";
                }
                if(!empty($nandavanam_where)) {
                    $nandavanam_where = $nandavanam_where." AND r.receipt_type_id = '".$receipt_type_id."'";
                }
                else {
                    $nandavanam_where = "r.receipt_type_id = '".$receipt_type_id."'";
                }
            }
            if(!empty($member_id)) {
                if(!empty($where)) {
                    $where = $where." AND r.member_id = '".$member_id."'";
                }
                else {
                    $where = "r.member_id = '".$member_id."'";
                }
                if(!empty($nandavanam_where)) {
                    $nandavanam_where = $nandavanam_where." AND r.member_id = '".$member_id."'";
                }
                else {
                    $nandavanam_where = "r.member_id = '".$member_id."'";
                }
            }
            if(!empty($non_member_name)) {
                if(!empty($nandavanam_where)) {
                    if(!empty($member_id)) {
                        $nandavanam_where = $nandavanam_where." OR (r.member_name LIKE '%".$non_member_name."%')";
                    }
                    else {
                        $nandavanam_where = $nandavanam_where." AND (r.member_name LIKE '%".$non_member_name."%')";
                    }
                }
                else {
                    $nandavanam_where = "(r.member_name LIKE '%".$non_member_name."%')";
                }
            }
            if(!empty($creator)) {
                if(!empty($where)) {
                    $where = $where." AND r.creator_name = '".$creator."'";
                }
                else {
                    $where = "r.creator_name = '".$creator."'";
                }
                if(!empty($nandavanam_where)) {
                    $nandavanam_where = $nandavanam_where." AND r.creator_name = '".$member_id."'";
                }
                else {
                    $nandavanam_where = "r.creator_name = '".$member_id."'";
                }
            }
            if(!empty($search_text)) {
                if(!empty($where)) {
                    $where = $where." AND (r.receipt_number LIKE '%".$search_text."%')";
                }
                else {
                    $where = "(r.receipt_number LIKE '%".$search_text."%')";
                }
                if(!empty($nandavanam_where)) {
                    $nandavanam_where = $nandavanam_where." AND (r.receipt_number LIKE '%".$search_text."%')";
                }
                else {
                    $nandavanam_where = "(r.receipt_number LIKE '%".$search_text."%')";
                }
            }

            if(!empty($non_member_name) && empty($member_id)) {
                $select_query = "SELECT COUNT(r.id) as receipt_count
                                                FROM ".$GLOBALS['nandavanam_table']." as r
                                                INNER JOIN ".$GLOBALS['receipt_type_table']." as t ON t.receipt_type_id = r.receipt_type_id
                                                WHERE ".$nandavanam_where." AND r.deleted = '0'";
            }
            else {
                if(!empty($where)) {
                    $new_thalakattu_query = "SELECT COUNT(r.id) as receipt_count
                                                FROM ".$GLOBALS['new_thalakattu_table']." as r
                                                INNER JOIN ".$GLOBALS['receipt_type_table']." as t ON t.receipt_type_id = r.receipt_type_id
                                                WHERE ".$where." AND r.deleted = '0'";
                }
                else {
                    $new_thalakattu_query = "SELECT COUNT(r.id) as receipt_count
                                                FROM ".$GLOBALS['new_thalakattu_table']." as r
                                                INNER JOIN ".$GLOBALS['receipt_type_table']." as t ON t.receipt_type_id = r.receipt_type_id
                                                WHERE r.deleted = '0'";
                }

                if(!empty($where)) {
                    $old_thalakattu_query = "SELECT COUNT(r.id) as receipt_count
                                                FROM ".$GLOBALS['old_thalakattu_table']." as r
                                                INNER JOIN ".$GLOBALS['receipt_type_table']." as t ON t.receipt_type_id = r.receipt_type_id
                                                WHERE ".$where." AND r.deleted = '0'";
                }
                else {
                    $old_thalakattu_query = "SELECT COUNT(r.id) as receipt_count
                                                FROM ".$GLOBALS['old_thalakattu_table']." as r
                                                INNER JOIN ".$GLOBALS['receipt_type_table']." as t ON t.receipt_type_id = r.receipt_type_id
                                                WHERE r.deleted = '0'";
                }

                if(!empty($where)) {
                    $new_member_registration_query = "SELECT COUNT(r.id) as receipt_count
                                                        FROM ".$GLOBALS['new_member_registration_table']." as r
                                                        INNER JOIN ".$GLOBALS['receipt_type_table']." as t ON t.receipt_type_id = r.receipt_type_id
                                                        WHERE ".$where." AND r.deleted = '0'";
                }
                else {
                    $new_member_registration_query = "SELECT COUNT(r.id) as receipt_count
                                                        FROM ".$GLOBALS['new_member_registration_table']." as r
                                                        INNER JOIN ".$GLOBALS['receipt_type_table']." as t ON t.receipt_type_id = r.receipt_type_id
                                                        WHERE r.deleted = '0'";
                }

                if(!empty($where)) {
                    $gold_silver_dollar_query = "SELECT COUNT(r.id) as receipt_count
                                                FROM ".$GLOBALS['gold_silver_dollar_table']." as r
                                                INNER JOIN ".$GLOBALS['receipt_type_table']." as t ON t.receipt_type_id = r.receipt_type_id
                                                WHERE ".$where." AND r.deleted = '0'";
                }
                else {
                    $gold_silver_dollar_query = "SELECT COUNT(r.id) as receipt_count
                                                FROM ".$GLOBALS['gold_silver_dollar_table']." as r
                                                INNER JOIN ".$GLOBALS['receipt_type_table']." as t ON t.receipt_type_id = r.receipt_type_id
                                                WHERE r.deleted = '0'";
                }

                if(!empty($where)) {
                    $pooja_donation_query = "SELECT COUNT(r.id) as receipt_count
                                                FROM ".$GLOBALS['pooja_donation_table']." as r
                                                INNER JOIN ".$GLOBALS['receipt_type_table']." as t ON t.receipt_type_id = r.receipt_type_id
                                                WHERE ".$where." AND r.deleted = '0'";
                }
                else {
                    $pooja_donation_query = "SELECT COUNT(r.id) as receipt_count
                                                FROM ".$GLOBALS['pooja_donation_table']." as r
                                                INNER JOIN ".$GLOBALS['receipt_type_table']." as t ON t.receipt_type_id = r.receipt_type_id
                                                WHERE r.deleted = '0'";
                }

                if(!empty($where)) {
                    $mudi_kaanikai_query = "SELECT COUNT(r.id) as receipt_count
                                                FROM ".$GLOBALS['mudi_kaanikai_table']." as r
                                                INNER JOIN ".$GLOBALS['receipt_type_table']." as t ON t.receipt_type_id = r.receipt_type_id
                                                WHERE ".$where." AND r.deleted = '0'";
                }
                else {
                    $mudi_kaanikai_query = "SELECT COUNT(r.id) as receipt_count
                                                FROM ".$GLOBALS['mudi_kaanikai_table']." as r
                                                INNER JOIN ".$GLOBALS['receipt_type_table']." as t ON t.receipt_type_id = r.receipt_type_id
                                                WHERE r.deleted = '0'";
                }

                if(!empty($nandavanam_where)) {
                    $nandavanam_query = "SELECT COUNT(r.id) as receipt_count
                                                FROM ".$GLOBALS['nandavanam_table']." as r
                                                INNER JOIN ".$GLOBALS['receipt_type_table']." as t ON t.receipt_type_id = r.receipt_type_id
                                                WHERE ".$nandavanam_where." AND r.deleted = '0'";
                }
                else {
                    $nandavanam_query = "SELECT COUNT(r.id) as receipt_count
                                                FROM ".$GLOBALS['nandavanam_table']." as r
                                                INNER JOIN ".$GLOBALS['receipt_type_table']." as t ON t.receipt_type_id = r.receipt_type_id
                                                WHERE r.deleted = '0'";
                }

                if(!empty($where)) {
                    $personal_savings_query = "SELECT COUNT(r.id) as receipt_count
                                                FROM ".$GLOBALS['personal_savings_table']." as r
                                                INNER JOIN ".$GLOBALS['receipt_type_table']." as t ON t.receipt_type_id = r.receipt_type_id
                                                WHERE ".$where." AND r.deleted = '0'";
                }
                else {
                    $personal_savings_query = "SELECT COUNT(r.id) as receipt_count
                                                FROM ".$GLOBALS['personal_savings_table']." as r
                                                INNER JOIN ".$GLOBALS['receipt_type_table']." as t ON t.receipt_type_id = r.receipt_type_id
                                                WHERE r.deleted = '0'";
                }

                if(!empty($where)) {
                    $general_donation_query = "SELECT COUNT(r.id) as receipt_count
                                                FROM ".$GLOBALS['general_donation_table']." as r
                                                INNER JOIN ".$GLOBALS['receipt_type_table']." as t ON t.receipt_type_id = r.receipt_type_id
                                                WHERE ".$where." AND r.deleted = '0'";
                }
                else {
                    $general_donation_query = "SELECT COUNT(r.id) as receipt_count
                                                FROM ".$GLOBALS['general_donation_table']." as r
                                                INNER JOIN ".$GLOBALS['receipt_type_table']." as t ON t.receipt_type_id = r.receipt_type_id
                                                WHERE r.deleted = '0'";
                }                            

                $select_query = "SELECT receipt_count
                                    FROM ( (".$new_thalakattu_query.") UNION ALL (".$old_thalakattu_query.") UNION ALL (".$new_member_registration_query.") 
                                            UNION ALL (".$gold_silver_dollar_query.") UNION ALL (".$pooja_donation_query.") UNION ALL (".$mudi_kaanikai_query.")
                                            UNION ALL (".$nandavanam_query.") UNION ALL (".$personal_savings_query.") UNION ALL (".$general_donation_query.")
                                        ) as g";
            }                            
            if(!empty($select_query)) {
                $list = $this->getQueryRecords($GLOBALS['new_thalakattu_table'], $select_query);
                //print_r($list);
                if(!empty($list)) {
                    foreach($list as $data) {
                        if(!empty($data['receipt_count'])) {
                            $receipt_count = $receipt_count + $data['receipt_count'];
                        }
                    }
                }
            }
            return $receipt_count;
        }

        // get volunteer members list
		public function getVolunteerMembersList() {
            $select_query = ""; $list = array();
            $select_query = "SELECT member_id, status_base_member_id, name, city, mobile_number FROM ".$GLOBALS['member_table']." 
                                WHERE status_prefix != '8' AND status_prefix != '9' AND deleted = '0' ORDER BY id DESC";
            if(!empty($select_query)) {
                $list = $this->getQueryRecords($GLOBALS['member_table'], $select_query);
            }
            return $list;
        }

        // check member is volunteer
		public function CheckIsMemberVolunteer($member_id) {
            $select_query = ""; $list = array(); $current_date = date("Y-m-d");
            if(!empty($member_id)) {
                $select_query = "SELECT v.* FROM ".$GLOBALS['volunteer_table']." as v
                                INNER JOIN ".$GLOBALS['member_table']." as m ON FIND_IN_SET(m.member_id, v.member_id)
                                WHERE m.member_id = '".$member_id."' AND '".$current_date."' >= DATE(v.from_date) 
                                AND '".$current_date."' <= DATE(v.to_date) AND m.status_prefix != '8' AND m.status_prefix != '9' AND v.deleted = '0'";
            }
            if(!empty($select_query)) {
                $list = $this->getQueryRecords($GLOBALS['member_table'], $select_query);
            }
            return $list;
        }

        // member create receipt type list
		public function MemberCreateReceiptTypes($receipt_type_ids) {
            $select_query = ""; $list = array();
            if(!empty($receipt_type_ids)) {
                $select_query = "SELECT * FROM ".$GLOBALS['receipt_type_table']." WHERE FIND_IN_SET(receipt_type_id, '".$receipt_type_ids."') 
                                    AND deleted = '0' ORDER BY id DESC";
            }                    
            if(!empty($select_query)) {
                $list = $this->getQueryRecords($GLOBALS['receipt_type_table'], $select_query);
            }
            return $list;
        }

        // check notification is already exist
		public function CheckNotificationAlreadyExist($lowercase_title, $notification_date_time) {
            $prev_notification_id = ""; $select_query = ""; $list = array();
            if(!empty($lowercase_title) && !empty($notification_date_time)) {
                $select_query = "SELECT notification_id FROM ".$GLOBALS['notification_table']." WHERE lowercase_title = '".$lowercase_title."' AND
                                    notification_date_time = '".$notification_date_time."' AND deleted = '0' ORDER BY id DESC";
            }
            if(!empty($select_query)) {
                $list = $this->getQueryRecords($GLOBALS['notification_table'], $select_query);
                if(!empty($list)) {
                    foreach($list as $data) {
                        if(!empty($data['notification_id'])) {
                            $prev_notification_id = $data['notification_id'];
                        }
                    }
                }
            }
            return $prev_notification_id;
        }

        // check notification is available to send by date
		public function CheckNotifcationByDate() {
            $list = array(); $today = date("Y-m-d"); $where = ""; $notification_list = array();
            if(!empty($today)) {
                $where = "DATE(notification_date_time) = '".$today."' AND notification_send = '0'";
            }
            if(!empty($where)) {
                $select_query = "SELECT * FROM ".$GLOBALS['notification_table']." WHERE ".$where." AND deleted = '0' ORDER BY id DESC";
                $list = $this->getQueryRecords($GLOBALS['notification_table'], $select_query);
                if(!empty($list)) {
                    foreach($list as $data) {
                        if(!empty($data['notification_id'])) {
                            $notification_id = $data['notification_id']; 
                            $notification_type = ""; $notification_title = ""; $notification_natchathiram = "";
                            if(!empty($data['title'])) {
                                $notification_title = $this->encode_decode('decrypt', $data['title']);
                            }
                            if(!empty($data['type'])) {
                                $notification_type = $this->encode_decode('decrypt', $data['type']);

                                if(!empty($data['natchathiram']) && $data['natchathiram'] != $GLOBALS['null_value']) {
                                    $notification_natchathiram = $data['natchathiram'];
                                }

                                $select_member_query = ""; $member_list = ""; $notification_member_ids = "";
                                if($notification_type == $GLOBALS['notification_type_general']) {
                                    $select_member_query = "SELECT GROUP_CONCAT(member_id) notification_member_ids FROM ".$GLOBALS['member_table']." 
                                                                WHERE (status_prefix != '8' AND status_prefix != '9') AND fcm_id != ".$GLOBALS['null_value']." 
                                                                AND deleted = '0'";
                                }
                                else if($notification_type == $GLOBALS['notification_type_speical_date'] || $notification_type == $GLOBALS['notification_type_natchathiram']) {
                                    if(!empty($notification_natchathiram)) {
                                        $select_member_query = "SELECT GROUP_CONCAT(member_id) notification_member_ids FROM ".$GLOBALS['member_table']." 
                                                                WHERE (status_prefix != '8' AND status_prefix != '9') AND fcm_id != ".$GLOBALS['null_value']." 
                                                                AND natchathiram = '".$notification_natchathiram."' AND deleted = '0'";
                                    }
                                }
                                if(!empty($select_member_query)) {
                                    $member_list = $this->getQueryRecords($GLOBALS['member_table'], $select_member_query);
                                    if(!empty($member_list)) {
                                        foreach($member_list as $row) {
                                            if(!empty($row['notification_member_ids'])) {
                                                $notification_member_ids = $row['notification_member_ids'];
                                            }
                                        }
                                    }
                                }

                                if(!empty($notification_member_ids)) {
                                    $notification_member_ids = explode(",", $notification_member_ids);
                                    $notification_list[] = array('notification_id' => $notification_id, 'title' => $notification_title, 'member_ids' => $notification_member_ids);
                                }

                            }
                        }
                    }
                }
            }
            return $notification_list;
        }
        
        public function getMemberNotificationList($member_id) {
            $list = array();
            if(!empty($member_id)) {
                echo $select_query = "SELECT m.fcm_id, ns.notification_id, n.title, n.image, n.audio, n.description FROM ".$GLOBALS['member_table']." as m
                                INNER JOIN ".$GLOBALS['notification_send_table']." as ns ON FIND_IN_SET(m.fcm_id, ns.member_fcm_ids)
                                INNER JOIN ".$GLOBALS['notification_table']." as n ON n.notification_id = ns.notification_id
                                WHERE m.member_id = '".$member_id."' AND m.fcm_id != '".$GLOBALS['null_value']."' AND NOT FIND_IN_SET(m.fcm_id, ns.remove_member_fcm_ids) AND m.deleted = '0'";
                $list = $this->getQueryRecords($GLOBALS['member_table'], $select_query);
            }
            return $list;
        }


    }
?>
