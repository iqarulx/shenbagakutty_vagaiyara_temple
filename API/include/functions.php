<?php
	include("basic_functions.php");
	include("user_functions.php");
	include("temple_functions.php");

	class Core_Functions extends Basic_Functions {		
		// Start Basic Functions
		public function basic_functions_object() {
			$basic_obj = "";		
			$basic_obj = new Basic_Functions();
			return $basic_obj;
		}
		// encryption or decryption
		public function encode_decode($action, $string) {
			$basic_obj = "";
			$basic_obj = $this->basic_functions_object();
			$string = $basic_obj->encode_decode($action, $string);
			return $string;
		}	
		// insert records to table		
		public function InsertSQL($table, $columns, $values, $custom_id, $unique_number, $action) {
			$basic_obj = "";
			$basic_obj = $this->basic_functions_object();

			$last_insert_id = "";		
			$last_insert_id = $basic_obj->InsertSQL($table, $columns, $values, $custom_id, $unique_number, $action);
			return $last_insert_id;
		}	
		// update records to table
		public function UpdateSQL($table, $update_id, $columns, $values, $action) {
			$basic_obj = "";
			$basic_obj = $this->basic_functions_object();

			$msg = "";		
			$msg = $basic_obj->UpdateSQL($table, $update_id, $columns, $values, $action);
			return $msg;
		}
		// get particular column value of table
		public function getTableColumnValue($table, $column, $value, $return_value) {
			$basic_obj = "";
			$basic_obj = $this->basic_functions_object();

			$result = "";
			$result = $basic_obj->getTableColumnValue($table, $column, $value, $return_value);
			return $result;
		}
		// get all values of table
		public function getTableRecords($table, $column, $value, $order) {
			$basic_obj = "";
			$basic_obj = $this->basic_functions_object();

			$result = ""; $list = array();		
			$result = $basic_obj->getTableRecords($table, $column, $value, $order);
			return $result;
		}
		// get all values of table use query
		public function getQueryRecords($table, $query) {
			$basic_obj = "";
			$basic_obj = $this->basic_functions_object();
			
			$result = "";		
			$result = $basic_obj->getQueryRecords($table, $query);
			return $result;
		}
		// get backup of database tables
		public function daily_db_backup() {
			$basic_obj = "";
			$basic_obj = $this->basic_functions_object();

			$result = "";		
			$result = $basic_obj->daily_db_backup();
			return $result;
		}
		// get number formated
		public function numberFormat($number, $decimals = 0) {
			$basic_obj = "";
			$basic_obj = $this->basic_functions_object();

			$result = "";
			$result = $basic_obj->numberFormat($number, $decimals = 0);
			return $result;
		}
		// get number formated
		public function truncate_number( $number, $precision = 2) {
			$basic_obj = "";
			$basic_obj = $this->basic_functions_object();

			$result = "";
			$result = $basic_obj->truncate_number( $number, $precision = 2);
			return $result;
		}
		// sort images with position
		public function SortingImages($images, $positions) {
			$basic_obj = "";
			$basic_obj = $this->basic_functions_object();
			
			$list = array();
			$list = $basic_obj->SortingImages($images, $positions);
			return $list;
		}
		// End Basic Functions
		
		// Start User Functions
		public function user_functions_object() {
			$user_obj = "";		
			$user_obj = new User_Functions();
			return $user_obj;
		}
		// check current user login ip address
		public function check_user_id_ip_address() {
			$user_obj = "";
			$user_obj = $this->user_functions_object();

			$check_login_id = "";			
			$check_login_id = $user_obj->check_user_id_ip_address();			
			return $check_login_id;	
		}
		// check current user is login or not
		public function checkUser() {
			$user_obj = "";
			$user_obj = $this->user_functions_object();

			$login_user_id = "";			
			$login_user_id = $user_obj->checkUser();			
			return $login_user_id;	
		}
		// get daily report
		public function getDailyReport($from_date, $to_date) {
			$user_obj = "";
			$user_obj = $this->user_functions_object();

			$list = array();
			$list = $user_obj->getDailyReport($from_date, $to_date);
			return $list;
		}
		// send email
		/*public function send_email_details($from, $to, $detail, $title) {
			$user_obj = "";
			$user_obj = $this->user_functions_object();

			$res = "";
			$res = $user_obj->send_email_details($from, $to, $detail, $title);
			return $res;
		}*/
		public function send_email_details($to_emails, $detail, $title) {
			$user_obj = "";
			$user_obj = $this->user_functions_object();

			$res = "";
			$res = $user_obj->send_email_details($to_emails, $detail, $title);
			return $res;
		}
		// send sms
		public function send_mobile_details($mobile_number, $sms_number, $sms) {
			$user_obj = "";
			$user_obj = $this->user_functions_object();

			$res = "";
			$res = $user_obj->send_mobile_details($mobile_number, $sms_number, $sms);
			return true;
		}	
		public function getOTPNumber() {
			$user_obj = "";
			$user_obj = $this->user_functions_object();

			$otp_number = "";
			$otp_number = $user_obj->getOTPNumber();
			return $otp_number;
		}
		public function getOTPSendCount($created_date_time, $otp_receive_mobile_number) {
			$user_obj = "";
			$user_obj = $this->user_functions_object();

			$otp_send_count = 0;
			$otp_send_count = $user_obj->getOTPSendCount($created_date_time, $otp_receive_mobile_number);
			return $otp_send_count;
		}
		public function getOTPSendUniqueID($otp_send_date, $otp_receive_mobile_number) {
			$user_obj = "";
			$user_obj = $this->user_functions_object();

			$unique_id = "";
			$unique_id = $user_obj->getOTPSendUniqueID($otp_send_date, $otp_receive_mobile_number);
			return $unique_id;
		}
		public function image_directory() {
			$user_obj = "";
			$user_obj = $this->user_functions_object();

			$target_dir = "";		
			$target_dir = $user_obj->image_directory();
			return $target_dir;
		}
		public function temp_image_directory() {
			$user_obj = "";
			$user_obj = $this->user_functions_object();

			$temp_dir = "";		
			$temp_dir = $user_obj->temp_image_directory();
			return $temp_dir;
		}
		public function clear_temp_image_directory() {
			$user_obj = "";
			$user_obj = $this->user_functions_object();

			$res = "";		
			$res = $user_obj->clear_temp_image_directory();
			return $res;
		}
		public function CheckStaffAccessPage($designation, $permission_page) {
			$user_obj = "";
			$user_obj = $this->user_functions_object();

			$acccess_permission = 0;		
			$acccess_permission = $user_obj->CheckStaffAccessPage($designation, $permission_page);
			return $acccess_permission;
		}
		// End User Functions

		public function getProjectTitle() {
			$project_title = "Temple";

			$company_name = "";
			$company_list = array();
			$company_list = $this->getTableRecords($GLOBALS['company_table'], '', '', '');
            if(!empty($company_list)) {
                foreach($company_list as $data) {
                    if(!empty($data['name'])) {
                        $company_name = $this->encode_decode('decrypt', $data['name']);
					}
				}
				if(!empty($company_name)) {
					$project_title = $company_name;
				}
			}		

			return $project_title;
		}

		// Start temple Functions
		public function temple_functions_object() {
			$temple_obj = "";		
			$temple_obj = new Temple_Functions();
			return $temple_obj;
		}
		// update profile photo
		public function UpdateMemberProfilePhoto() {
			$temple_obj = "";
			$temple_obj = $this->temple_functions_object();

			$updated = 0;			
			$updated = $temple_obj->UpdateMemberProfilePhoto();			
			return $updated;	
		}

		// get country list
		public function getMemberCountryList() {
			$temple_obj = "";
			$temple_obj = $this->temple_functions_object();

			$list = array();			
			$list = $temple_obj->getMemberCountryList();			
			return $list;	
		}
		// get state list
		public function getMemberStateList($country) {
			$temple_obj = "";
			$temple_obj = $this->temple_functions_object();

			$list = array();			
			$list = $temple_obj->getMemberStateList($country);			
			return $list;	
		}
		// get city list
		public function getMemberCityList($country, $state) {
			$temple_obj = "";
			$temple_obj = $this->temple_functions_object();

			$list = array();			
			$list = $temple_obj->getMemberCityList($country, $state);			
			return $list;	
		}		
		// get initial list
		public function getMemberInitialList() {
			$temple_obj = "";
			$temple_obj = $this->temple_functions_object();

			$list = array();			
			$list = $temple_obj->getMemberInitialList();			
			return $list;	
		}
		// get father list
		public function getMemberFatherList($initial) {
			$temple_obj = "";
			$temple_obj = $this->temple_functions_object();

			$list = array();			
			$list = $temple_obj->getMemberFatherList($initial);			
			return $list;	
		}
		// get introducer list
		public function getMemberIntroducerList($initial) {
			$temple_obj = "";
			$temple_obj = $this->temple_functions_object();

			$list = array();			
			$list = $temple_obj->getMemberIntroducerList($initial);			
			return $list;	
		}
		// get mobile number list
		public function getMemberMobileNumberList() {
			$temple_obj = "";
			$temple_obj = $this->temple_functions_object();

			$list = array();			
			$list = $temple_obj->getMemberMobileNumberList();			
			return $list;	
		}
		// get member list
		public function getMemberList($country, $state, $city, $status_id, $mobile_no_option, $initial, $father_id, $introducer_id, $mobile_number, $profession, $search_text, $page_start, $page_limit) {
			$temple_obj = "";
			$temple_obj = $this->temple_functions_object();

			$list = array();			
			$list = $temple_obj->getMemberList($country, $state, $city, $status_id, $mobile_no_option, $initial, $father_id, $introducer_id, $mobile_number, $profession, $search_text, $page_start, $page_limit);			
			return $list;	
		}
		// get total members count
		public function getTotalMebersCount($country, $state, $city, $status_id, $mobile_no_option, $initial, $father_id, $introducer_id, $mobile_number, $profession, $search_text) {
			$temple_obj = "";
			$temple_obj = $this->temple_functions_object();

			$total_members_count = 0;			
			$total_members_count = $temple_obj->getTotalMebersCount($country, $state, $city, $status_id, $mobile_no_option, $initial, $father_id, $introducer_id, $mobile_number, $profession, $search_text);			
			return $total_members_count;	
		}
		// check mandapam is already booking
		public function CheckMandapamAlreadyBooking($mandapam_id, $from_date, $to_date) {
			$temple_obj = "";
			$temple_obj = $this->temple_functions_object();

			$booking_count = 0;			
			$booking_count = $temple_obj->CheckMandapamAlreadyBooking($mandapam_id, $from_date, $to_date);			
			return $booking_count;	
		}
		// get mandapam booking list
		public function getMandapamBookingList($mandapam_id, $from_date, $to_date) {
			$temple_obj = "";
			$temple_obj = $this->temple_functions_object();

			$list = array();			
			$list = $temple_obj->getMandapamBookingList($mandapam_id, $from_date, $to_date);			
			return $list;	
		}
		// get dashboard booking date list
		public function getDashboardBookingDatelist($booking_month, $booking_year, $booking_month_last_date, $mandapam_id) {
			$temple_obj = "";
			$temple_obj = $this->temple_functions_object();

			$booking_dates = "";		
			$booking_dates = $temple_obj->getDashboardBookingDatelist($booking_month, $booking_year, $booking_month_last_date, $mandapam_id);			
			return $booking_dates;	
		}
		// get receipt members list
		public function getReceiptMembersList($member_id) {
			$temple_obj = "";
			$temple_obj = $this->temple_functions_object();

			$list = array();			
			$list = $temple_obj->getReceiptMembersList($member_id);			
			return $list;	
		}
		// get receipt non members list
		public function getReceiptNonMembersList($member_id) {
			$temple_obj = "";
			$temple_obj = $this->temple_functions_object();

			$list = array();			
			$list = $temple_obj->getReceiptNonMembersList($member_id);			
			return $list;	
		}
		// get receipt creator members list
		public function getReceiptCreatorMembersList() {
			$temple_obj = "";
			$temple_obj = $this->temple_functions_object();

			$list = array();			
			$list = $temple_obj->getReceiptCreatorMembersList();			
			return $list;	
		}
		// get all receipt list
		public function getAllReceiptList($from_date, $to_date, $receipt_category_id, $receipt_type_id, $member_id, $non_member_name, $creator, $search_text, $page_start, $page_end, $ordering, $receipt_id) {
			$temple_obj = ""; 
			$temple_obj = $this->temple_functions_object();

			$list = array();			
			$list = $temple_obj->getAllReceiptList($from_date, $to_date, $receipt_category_id, $receipt_type_id, $member_id, $non_member_name, $creator, $search_text, $page_start, $page_end, $ordering, $receipt_id);			
			return $list;	
		}
		// get total all receipt count
		public function getTotalAllReceiptCount($from_date, $to_date, $receipt_category_id, $receipt_type_id, $member_id, $non_member_name, $creator, $search_text) {
			$temple_obj = ""; 
			$temple_obj = $this->temple_functions_object();

			$count = 0;			
			$count = $temple_obj->getTotalAllReceiptCount($from_date, $to_date, $receipt_category_id, $receipt_type_id, $member_id, $non_member_name, $creator, $search_text);			
			return $count;	
		}
		// get volunteer members list
		public function getVolunteerMembersList() {
			$temple_obj = "";
			$temple_obj = $this->temple_functions_object();

			$list = array();			
			$list = $temple_obj->getVolunteerMembersList();			
			return $list;	
		}
		// check member is volunteer
		public function CheckIsMemberVolunteer($member_id) {
			$temple_obj = "";
			$temple_obj = $this->temple_functions_object();

			$list = array();			
			$list = $temple_obj->CheckIsMemberVolunteer($member_id);			
			return $list;	
		}
		// member create receipt type list
		public function MemberCreateReceiptTypes($receipt_type_ids) {
			$temple_obj = "";
			$temple_obj = $this->temple_functions_object();

			$list = array();			
			$list = $temple_obj->MemberCreateReceiptTypes($receipt_type_ids);			
			return $list;	
		}

		// check notification is already exist
		public function CheckNotificationAlreadyExist($lowercase_title, $notification_date_time) {
			$temple_obj = "";
			$temple_obj = $this->temple_functions_object();

			$prev_notification_id = "";
			$prev_notification_id = $temple_obj->CheckNotificationAlreadyExist($lowercase_title, $notification_date_time);			
			return $prev_notification_id;	
		}
		// check notification is available to send by date
		public function CheckNotifcationByDate() {
			$temple_obj = "";
			$temple_obj = $this->temple_functions_object();

			$list = array();			
			$list = $temple_obj->CheckNotifcationByDate();			
			return $list;	
		}
        
        // check notification is available to send by date
        public function getMemberNotificationList($member_id) {
            $temple_obj = "";
            $temple_obj = $this->temple_functions_object();

            $list = array();
            $list = $temple_obj->getMemberNotificationList($member_id);
            return $list;
        }
        
		// End temple Functions
	}
?>
