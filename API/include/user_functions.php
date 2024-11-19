<?php
	class User_Functions extends Basic_Functions {
		// check current user login ip address
		public function check_user_id_ip_address() {
			$select_query = ""; $list = array(); $check_login_id = "";			
			if(!empty($_SESSION[$GLOBALS['site_name_user_prefix'].'_user_id']) && isset($_SESSION[$GLOBALS['site_name_user_prefix'].'_user_id'])) {
				$select_query = "SELECT id FROM ".$GLOBALS['login_table']." WHERE user_id = '".$_SESSION[$GLOBALS['site_name_user_prefix'].'_user_id']."' AND ip_address = '".$_SESSION[$GLOBALS['site_name_user_prefix'].'_user_ip_address']."' AND logout_date_time = '0000-00-00 00:00:00' ORDER BY id DESC LIMIT 1";
				$list = $this->getQueryRecords($GLOBALS['login_table'], $select_query);
				if(!empty($list)) {
					foreach($list as $row) {
						$check_login_id = $row['id'];
					}
				}
			}
			return $check_login_id;
		}
		// check current user is login or not
		public function checkUser() {			
			$user_id = ""; $select_query = ""; $list = array(); $login_user_id = "";
			if(isset($_SESSION[$GLOBALS['site_name_user_prefix'].'_user_id'])) {
				$user_id = $_SESSION[$GLOBALS['site_name_user_prefix'].'_user_id'];				
				$today = date('Y-m-d');	
				
				$select_query = "SELECT * FROM ".$GLOBALS['login_table']." WHERE user_id = '".$user_id."' AND DATE(login_date_time) = '".$today."' ORDER BY id DESC LIMIT 1";
				$list = $this->getQueryRecords($GLOBALS['login_table'], $select_query);
				if(!empty($list)) {
					foreach($list as $row) {
						$login_user_id = $row['user_id'];
					}
				}
			}
			return $login_user_id;
		}
		public function getDailyReport($from_date, $to_date) {
            $log_list = array(); $select_query = ""; $where = "";
			$log_backup_file = $GLOBALS['log_backup_file'];
			if(file_exists($log_backup_file)) {
				$myfile = fopen($log_backup_file, "r");
				while(!feof($myfile)) {
					$log = "";
					$log = fgets($myfile);
					$log = trim($log);
					if(!empty($log)) {
						$log = json_decode($log);
						$log_list[] = $log;
					}
				}
				fclose($myfile);
				if(!empty($log_list)) {
					$list = array();
					foreach($log_list as $row) {							
						if(!empty($from_date) && !empty($to_date)) {
							$success = 0; $action = "";
							foreach($row as $key => $value) {								
								if( (!empty($key) && $key == "action")) {
									$action = $value;
								}
							}
							if(!empty($action)) {
								foreach($row as $key => $value) {
									if( (!empty($key) && $key == "created_date_time")) {
										if( ( date("d-m-Y", strtotime($value)) >= date("d-m-Y", strtotime($from_date)) ) && ( date("d-m-Y", strtotime($value)) <= date("d-m-Y", strtotime($to_date)) ) ) {
											$success = 1;										
										}
									}
								}
							}
							if(!empty($success) && $success == 1) {
								$list[] = $row;
							}
						}
					}
					$log_list = $list;
				}
			}
			return $log_list;
        }
		// send email
		/*public function send_email_details($from, $to, $detail, $title) {	
			$msg = "";
			$headers  = 'MIME-Version: 1.0' . "\r\n";
			$headers .= 'Content-type: text/html; charset=iso-8859-1' . "\r\n";
			$headers .= "From: ".$from;
			if(!mail($to, $title, $detail, $headers)) {
				$msg = "unable to send your mail";
			}  
			return $msg;
		}*/
		public function send_email_details($to_emails, $detail, $title) {
			require_once "PHPMailer/phpmailer.php";

			if ( isset($_SERVER["OS"]) && $_SERVER["OS"] == "Windows_NT" ) {
				$hostname = strtolower($_SERVER["COMPUTERNAME"]);
			} else {
				$hostname = `hostname`;
				$hostnamearray = explode('.', $hostname);
				$hostname = $hostnamearray[0];
			}

			$mail = new PHPMailer();
    
            $mail->SMTPDebug = 0;  // Enable verbose debug output
            //SMTP settings start
            $mail->isSMTP(); // Set mailer to use SMTP
            if ( strpos($hostname, 'cpnl') === FALSE ) //if not cPanel
				$mail->Host = 'relay-hosting.secureserver.net';
			else
				$mail->Host = 'localhost';
				
            $mail->SMTPAuth = false; // Enable SMTP authentication
            $mail->From = 'admin@spsagro.com';
			$mail -> FromName = 'Order Form';
			if(!empty($to_emails)) {
				foreach($to_emails as $key => $to) {
					if(!empty($key)) {
						$mail->AddBCC($to);
					}
					if(empty($key)) {
						$mail->addAddress($to);
					}
				}
			}            
			$mail->Subject = $title;
			$mail->Body = $detail;
	
			$mailresult = $mail->Send();
			$mailconversation = nl2br(htmlspecialchars(ob_get_clean())); //captures the output of PHPMailer and htmlizes it
            if ( $mailresult ) {
				$msg = "Successfully Mail send";
            }
            else {
                $msg = 'FAIL: ' . $mail->ErrorInfo . '<br />' . $mailconversation;
            }
			return $msg;
		}
		// send sms
		public function send_mobile_details($company_mobile_number, $number, $sms) {
			$res = true; $sms_link = "";
			if(!empty($company_mobile_number) && !empty($number) && !empty($sms)) {
				//$sms_link = "https://www.fast2sms.com/dev/bulkV2?authorization=VFxWy81QjDk3S2b6qo0JRNHaYCcZs4nmA5Xl7KMuGtwpTIPiUBV6LM5aSg7x84mfP2XyJtshdoFGEBrK&route=dlt&sender_id=SRISOF&message=126873&variables_values=".$msg."|&flash=0&numbers=".$mobile_number;
				$fields = array(
					"sender_id" => "SRISOF",
					"message" => $number,
					"variables_values" => $sms,
					"route" => "dlt",
					"numbers" => $company_mobile_number,
				);
				
				$curl = curl_init();
				
				curl_setopt_array($curl, array(
				  CURLOPT_URL => "https://www.fast2sms.com/dev/bulkV2",
				  CURLOPT_RETURNTRANSFER => true,
				  CURLOPT_ENCODING => "",
				  CURLOPT_MAXREDIRS => 10,
				  CURLOPT_TIMEOUT => 30,
				  CURLOPT_SSL_VERIFYHOST => 0,
				  CURLOPT_SSL_VERIFYPEER => 0,
				  CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
				  CURLOPT_CUSTOMREQUEST => "POST",
				  CURLOPT_POSTFIELDS => json_encode($fields),
				  CURLOPT_HTTPHEADER => array(
					"authorization: VFxWy81QjDk3S2b6qo0JRNHaYCcZs4nmA5Xl7KMuGtwpTIPiUBV6LM5aSg7x84mfP2XyJtshdoFGEBrK",
					"accept: */*",
					"cache-control: no-cache",
					"content-type: application/json"
				  ),
				));
				
				$response = curl_exec($curl);
				$err = curl_error($curl);
				
				curl_close($curl);
				
				/*if ($err) {
				  echo "cURL Error #:" . $err;
				} else {
				  echo $response;
				}*/
			}
			/*$phone_number = '91'.$phone_number;
			$mailin = new MailinSms($GLOBALS['mailin_sms_api_key']);
			$mailin->addTo($phone_number);
			$mailin->setFrom('ram');
			$mailin->setText($msg);
			$mailin->setTag('');
			$mailin->setType('');
			$mailin->setCallback('');
			$res = $mailin->send();*/
			return $res;
		}
		
		public function getOTPNumber() {
			$select_query = ""; $list = array(); $new_otp_number = ""; $otp_unique_id = ""; $otp_number = mt_rand(1000, 9999);
			if(!empty($otp_number)) {
				$otp_unique_id = $this->getTableColumnValue($GLOBALS['otp_send_phone_numbers_table'], 'otp_number', $otp_number, 'id');
				if(!empty($otp_unique_id)) {
					$this->getOTPNumber();
				}
				else {
					$new_otp_number = $otp_number;
				}
			}
			return $new_otp_number;
		}

		public function getOTPSendCount($created_date_time, $otp_receive_mobile_number) {
            $select_query = ""; $list = array(); $otp_send_count = 0;
			if(!empty($created_date_time) && !empty($otp_receive_mobile_number)) {
				$select_query = "SELECT SUM(otp_send_count) as otp_send_count FROM ".$GLOBALS['otp_send_phone_numbers_table']." 
									WHERE DATE(send_date_time) = '".date("Y-m-d", strtotime($created_date_time))."' 
									AND phone_number = '".$otp_receive_mobile_number."' AND deleted = '0' ORDER BY id DESC";
				$list = $this->getQueryRecords($GLOBALS['otp_send_phone_numbers_table'], $select_query);
				if(!empty($list)) {
					foreach($list as $data) {
						if(!empty($data['otp_send_count'])) {
							$otp_send_count = $data['otp_send_count'];
						}
					}
				}
			}
			return $otp_send_count;
        }

		public function getOTPSendUniqueID($otp_send_date, $otp_receive_mobile_number) {
            $select_query = ""; $list = array(); $unique_id = "";
			if(!empty($otp_send_date) && !empty($otp_receive_mobile_number)) {
				$select_query = "SELECT id FROM ".$GLOBALS['otp_send_phone_numbers_table']." 
									WHERE DATE(send_date_time) = '".date("Y-m-d", strtotime($otp_send_date))."' AND phone_number = '".$otp_receive_mobile_number."' 
									AND deleted = '0' ORDER BY id DESC LIMIT 1";
				$list = $this->getQueryRecords($GLOBALS['otp_send_phone_numbers_table'], $select_query);
				if(!empty($list)) {
					foreach($list as $data) {
						if(!empty($data['id'])) {
							$unique_id = $data['id'];
						}
					}
				}
			}
			return $unique_id;
        }	

		public function image_directory() {
			$target_dir = "../images/upload/";
			return $target_dir;
		}
		public function temp_image_directory() {
			$temp_dir = "../images/temp/";
			return $temp_dir;
		}
		public function clear_temp_image_directory() {
			$temp_dir = "../images/temp/";
			
			$files = glob($temp_dir.'*'); // get all file names
			foreach($files as $file){ // iterate files
			  if(is_file($file))
				unlink($file); // delete file
			}
			
			return true;
		}
		
		 // check access page permission
		public function CheckStaffAccessPage($designation, $permission_page) {
			$list = array(); $select_query = ""; $acccess_permission = 0;
			if(!empty($designation)) {
				$select_query = "SELECT * FROM ".$GLOBALS['designation_table']." WHERE designation_id = '".$designation."' AND deleted = '0'";
			}
			if(!empty($select_query)) {
				$list = $this->getQueryRecords($GLOBALS['designation_table'], $select_query);
				if(!empty($list)) {
					$access_pages = "";
					foreach($list as $data) {
						if(!empty($data['access_pages'])) {
							$access_pages = $data['access_pages'];
						}
					}

					if(!empty($access_pages)) {
						$access_pages = explode(",", $access_pages);
						if(!empty($permission_page)) {
							$permission_page = $this->encode_decode('encrypt', $permission_page);
							if(in_array($permission_page, $access_pages)) {
								$acccess_permission = 1;
							}
						}
					}
				}
			}
			return $acccess_permission;
		}
	}	
?>
