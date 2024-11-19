<?php
	include("temple_13122022.php");
	class Basic_Functions extends db {
		public $con;
		
		public function connect() {
			$con = parent::connect();
			return $con;
		}
		
		// encryption or decryption
		public function encode_decode($action, $string) {
			$output = "";
			//encode
			if(!empty($string) && $string != $GLOBALS['null_value']) {
				if($action == 'encrypt') {
					$string = htmlentities($string, ENT_QUOTES);
					$output = base64_encode($string);
					$output =  bin2hex($output);
					//$output = gzcompress($output, 9);
				}			
				//decode
				if($action == 'decrypt') {
					//$output = gzuncompress($string);
					$output = hex2bin($string);
					$output = base64_decode($output);
					$output = html_entity_decode($output);
				}
			}
			return $output;
		}

		// get last record id before insert
		public function getLastRecordIDFromTable($table) {
			$max_unique_id = ""; $list = array();				
			$select_query = "SELECT id FROM ".$table." ORDER BY id DESC LIMIT 1";
			$list = $this->getQueryRecords($table, $select_query);
			if(!empty($list)) {
				foreach($list as $data) {
					if(!empty($data['id'])) {
						$max_unique_id = $data['id'];
					}
				}
			}
			return $max_unique_id;
		}
		// auto generate next number
		public function automate_number($table, $column, $last_record_id, $current_record_id) {
			$last_number = 0; $next_number = 0; $matches = array();
			if(!empty($current_record_id)) {

				$prefix = ""; $starting_number = ""; $receipt_table = 0;
				if (strpos($table, 'rpt_') !== false) {
					$receipt_table = 1;
					$folder_name = "";
					$folder_name = $table;
					$folder_name = str_replace($GLOBALS['table_prefix']."_rpt_", "", $table);
					$folder_name = str_replace("_table", "", $folder_name);
					//echo "folder_name - ".$folder_name."<br>";
					if(!empty($folder_name)) {
						$receipt_type_list = array();
						$receipt_type_list = $this->getTableRecords($GLOBALS['receipt_type_table'], 'form_name', $folder_name, '');
						if(!empty($receipt_type_list)) {
							foreach($receipt_type_list as $data) {
								if(!empty($data['prefix'])) {
									$prefix = $data['prefix'];	
									if(!empty($prefix)) {
										$prefix = $this->encode_decode('decrypt', $prefix);
									}
								}
								if(!empty($data['starting_number'])) {
									$starting_number = $data['starting_number'];	
								}
							}
						}
					}
				}
				//echo "prefix - ".$prefix.", starting_number - ".$starting_number."<br>";

				if(!empty($last_record_id)) {
					//echo "last_record_id - ".$last_record_id.", current_record_id - ".$current_record_id."<br>";
					$interval = 0;
					$interval = $current_record_id - $last_record_id;
					//echo "interval - ".$interval."<br>";
					if(!empty($interval) && $interval > 1) {
						$current_record_id = $current_record_id + 1;
					}
					else {
						$current_record_id = $current_record_id - 1;
					}
					//echo "current_record_id - ".$current_record_id."<br>";
					//echo "last_record_id - ".$last_record_id.", current_record_id - ".$current_record_id.", last_number - ".$last_number;
					//$last_number = $this->getTableColumnValue($table, 'id', $last_record_id, $column);

					if(!empty($receipt_table) && !empty($prefix) && !empty($starting_number)) {
						$select_query = "SELECT ".$column." FROM ".$table." WHERE id = '".$current_record_id."' AND ".$column." LIKE '".$prefix."%'";
					}
					else {	
						$select_query = "SELECT ".$column." FROM ".$table." WHERE id = '".$current_record_id."'";
					}
					$list = $this->getQueryRecords($table, $select_query);
					if(!empty($list)) {
						foreach($list as $data) {
							if(!empty($data[$column])) {
								$last_number = $data[$column];
							}
						}
					}
					if(empty($receipt_table) && empty($last_number)) {
						$select_query = "SELECT * FROM ".$table." ORDER BY id DESC LIMIT 1";
						if(!empty($select_query)) {
							$list = array();
							$list = $this->getQueryRecords($table, $select_query);
							if(!empty($list)) {
								foreach($list as $data) {
									if($data[$column]) {
										$last_number = $data[$column];
									}
								}
							}
						}
					}
					if(!empty($last_number) && $last_number != $GLOBALS['null_value']) {	
						//$last_number = $this->encode_decode('decrypt', $last_number);
						//echo "last_number - ".$last_number."<br>";
						preg_match_all('/([0-9]+|[a-zA-Z]+)/',$last_number,$matches);				
						if(!empty($matches)) {
							$match = "";
							$match = $matches[count($matches) - 1];
							if(!empty($match)) {
								$last_number = $match[count($match) - 1];
								if(preg_match("/^\d+$/", $last_number)) {
									$next_number = $last_number + 1;
								}
							}
						}
					}
				}

				//echo "next_number - ".$next_number."<br>";

				if(empty($next_number)) {
					//echo "receipt_table - ".$receipt_table.", prefix - ".$prefix.", starting_number - ".$starting_number."<br>";
					if(!empty($receipt_table) && !empty($prefix) && !empty($starting_number)) {
						$next_number = $prefix.$starting_number;
					}
					else {
						$next_number = 1;
					}
					//echo "next_number - ".$next_number."<br>";
				}
				else {
					if(!empty($receipt_table) && !empty($prefix) && !empty($starting_number)) {
						$next_number = $prefix.$next_number;
					}
				}
			}	
			return $next_number;
		}

		// insert records to table
		public function InsertSQL($table, $columns, $values, $custom_id, $unique_number, $action) {
			$con = $this->connect(); $last_insert_id = "";
			
			if(!empty($columns) && !empty($values)) {
				if(count($columns) == count($values)) {					
					$columns = implode(",", $columns);
					$values = implode(",", $values);

					$last_record_id = 0;
                	$last_record_id = $this->getLastRecordIDFromTable($table);
					
					$result = "";
					$insert_query = "INSERT INTO ".$table." (".$columns.") VALUES (".$values.")";
					$result = $con->prepare($insert_query);
					if($result->execute() === TRUE) {
						$last_insert_id = $con->lastInsertId();
						$last_query_insert_id = "";
						if(preg_match("/^\d+$/", $last_insert_id)) {
							if(!empty($custom_id)) {
								$unique_number_value = "";
								if(!empty($unique_number)) {
									$unique_number_value = $this->automate_number($table, $unique_number, $last_record_id, $last_insert_id);
									/*if(!empty($unique_number_value)) {                    
										$unique_number_value = $this->encode_decode('encrypt', strtoupper($unique_number_value));
									}*/									
								}

								$custom_id_value = "";
								if($last_insert_id < 10) {
									$custom_id_value = date("dmYhis")."_0".$last_insert_id;
								}
								else {
									$custom_id_value = date("dmYhis")."_".$last_insert_id;
								}
								if(!empty($custom_id_value)) {
									$custom_id_value = $this->encode_decode('encrypt', $custom_id_value);
								}
								$columns = array(); $values = array(); $update_id = "";	
								if(!empty($unique_number) && !empty($unique_number_value)) {
									$columns = array($custom_id, $unique_number);
									$values = array("'".$custom_id_value."'", "'".$unique_number_value."'");
								}	
								else {			
									$columns = array($custom_id);
									$values = array("'".$custom_id_value."'");
								}
								$update_id = $this->UpdateSQL($table, $last_insert_id, $columns, $values, '');
								if(preg_match("/^\d+$/", $update_id)) {
									$last_log_id = $this->add_log($table, $last_insert_id, $insert_query, $action);			
								}
							}
							else {
								$last_log_id = $this->add_log($table, $last_insert_id, $insert_query, $action);
							}
						}
					}
					else {
						$last_insert_id = "Unable to insert the data";
					}
				}
				else {
					$last_insert_id = "Columns are not match";
				}
			}			
					
			return $last_insert_id;
		}
		// add log to csv file
		public function add_log($table, $table_unique_id, $query, $action) {
			$con = $this->connect(); $last_query_insert_id = "";
			if(!empty($query) && !empty($action)) {
				$query = $this->encode_decode('encrypt', $query);
				$action = $this->encode_decode('encrypt', $action);
				$table = $this->encode_decode('encrypt', $table);
			
				$create_date_time = $GLOBALS['create_date_time_label'];
				$creator = "";
				if(!empty($_SESSION[$GLOBALS['site_name_user_prefix'].'_user_id']) && isset($_SESSION[$GLOBALS['site_name_user_prefix'].'_user_id'])) {
					$creator = $_SESSION[$GLOBALS['site_name_user_prefix'].'_user_id'];
				}
				$creator_type = "";
				if(!empty($_SESSION[$GLOBALS['site_name_user_prefix'].'_user_type']) && isset($_SESSION[$GLOBALS['site_name_user_prefix'].'_user_type'])) {
					$creator_type = $this->encode_decode('encrypt', $_SESSION[$GLOBALS['site_name_user_prefix'].'_user_type']);
				}
				$creator_name = "";
				if(!empty($_SESSION[$GLOBALS['site_name_user_prefix'].'_user_name']) && isset($_SESSION[$GLOBALS['site_name_user_prefix'].'_user_name'])) {
					$creator_name = $this->encode_decode('encrypt', $_SESSION[$GLOBALS['site_name_user_prefix'].'_user_name']);
				}

				$actual_link = "http://".$_SERVER['HTTP_HOST'].$_SERVER['REQUEST_URI'];			
				$log_backup_file = $GLOBALS['log_backup_file'];
				if (strpos($actual_link, 'receipt_form') !== false) {
					$log_backup_file = "../../".$GLOBALS['log_backup_file'];
				}
				
				$columns = array('type', 'created_date_time', 'creator', 'creator_name', 'log_table', 'log_table_unique_id', 'action', 'query');	
				$values = array("'".$creator_type."'", "'".$create_date_time."'", "'".$creator."'", "'".$creator_name."'", "'".$table."'", "'".$table_unique_id."'", "'".$action."'", "'".$query."'");			
				if(count($columns) == count($values)) {	
					$log_data = array();
					$log_data = array('type' => $creator_type, 'created_date_time' => $create_date_time, 'creator' => $creator, 'creator_name' => $creator_name, 'table' => $table, 'table_unique_id' => $table_unique_id, 'action' => $action, 'query' => $query);	
					if(!empty($log_data)) {
						$log_data = json_encode($log_data);
						
						if(file_exists($log_backup_file)) {
							file_put_contents($log_backup_file, $log_data, FILE_APPEND | LOCK_EX);
							file_put_contents($log_backup_file, "\n", FILE_APPEND | LOCK_EX);
						}
						else {
							$myfile = fopen($log_backup_file, "a+");
							fwrite($myfile, $log_data."\n");
							fclose($myfile);
						}
					}
				}
			}			
					
			return $last_query_insert_id;
		}
		// update records to table
		public function UpdateSQL($table, $update_id, $columns, $values, $action) {
			$con = $this->connect(); $updated_data = ''; $msg = "";
			
			if(!empty($columns) && !empty($values)) {
			
				if(count($columns) == count($values)) {					
					for($r = 0; $r < count($columns); $r++) {
						$updated_data = $updated_data.$columns[$r]." = ".$values[$r]."";
						if(!empty($columns[$r+1])) {
							$updated_data = $updated_data.', ';
						}	
					}
					if(!empty($updated_data)) {
						$updated_data = trim($updated_data);
						$update_query = "UPDATE ".$table." SET ".$updated_data." WHERE id='".$update_id."'";
						$result = $con->prepare($update_query);
						if($result->execute() === TRUE) {
							$msg = 1;							
							$last_log_id = $this->add_log($table, $update_id, $update_query, $action);
						}
						else {
							$msg = "Unable to update the data";
						}
					}
					else {
						$msg = "Unable to update the data";
					}
				}
				else {
					$msg = "Columns are not match";
				}
			}
					
			return $msg;	
		}
		// get particular column value of table
		public function getTableColumnValue($table, $column, $value, $return_value) {
			$table_column_value = ""; $select_query = ""; $list = array();
			if(!empty($column) && !empty($value) && !empty($return_value)) {
				 $select_query = "SELECT ".$return_value." FROM ".$table." WHERE ".$column." = '".$value."' AND deleted = '0'";	
				//echo $select_query."<br>";
				if(!empty($select_query)) {
					$list = $this->getQueryRecords($table, $select_query);
					if(!empty($list)) {
						foreach($list as $row) {
							$table_column_value = $row[$return_value];
						}
					}
				}
			}
			return $table_column_value;
		}
		// get all values of table
		public function getTableRecords($table, $column, $value, $order) {
			$select_query = ""; $list = array();

			if(empty($order)) {
				$order = "DESC";
			}

			if(!empty($table)) {
				if(!empty($column) && !empty($value)) {		
					$select_query = "SELECT * FROM ".$table." WHERE ".$column." = '".$value."' AND deleted = '0' ORDER BY id ".$order;	
				}
				else if(empty($column) && empty($value)) {		
					$select_query = "SELECT * FROM ".$table." WHERE deleted = '0' ORDER BY id ".$order;	
				}
			}			
			//echo $select_query;
			if(!empty($select_query)) {
				$list = $this->getQueryRecords($table, $select_query);
			}
			return $list;
		}

		// get all values of table use query
		public function getQueryRecords($table, $select_query) {
			$con = $this->connect(); $list = array();
			if(!empty($select_query)) {
				$result = 0; $pdo = "";			
				$pdo = $con->prepare($select_query);
				$pdo->execute();	
				$result = $pdo->setFetchMode(PDO::FETCH_ASSOC);
				if(!empty($result)) {
					foreach($pdo->fetchAll() as $row) {
						$list[] = $row;
					}
				}
			}
			return $list;
		}
		// get backup of database tables
		public function daily_db_backup() {
			$path = "";
			$actual_link = "http://".$_SERVER['HTTP_HOST'].$_SERVER['REQUEST_URI'];
			$path = $GLOBALS['backup_folder_name']."/";
			
			$con = $this->connect();
			$backupAlert = 0; $backup_file = ""; $file_name = ""; $dbname = $this->db_name;
			$tables = array();
			//$result = mysqli_query($con, "SHOW TABLES");
			$select_query = "SHOW TABLES";
			$result = 0; $pdo = "";			
			$pdo = $con->prepare($select_query);
			$pdo->execute();	
			$result = $pdo->fetchAll(PDO::FETCH_COLUMN); 
			if (!empty($result)) {
				$tables = array();
				foreach($result as $table_name) {
					if(!empty($table_name)) {
						$tables[] = $table_name;
					}
				}
				$output = '';
				if(!empty($tables)) {
					foreach($tables as $table) {
						$show_table_query = "SHOW CREATE TABLE " . $table . "";
						$statement = $con->prepare($show_table_query);
						$statement->execute();
						$show_table_result = $statement->fetchAll();

						foreach($show_table_result as $show_table_row) {
							$output .= "\n\n" . $show_table_row["Create Table"] . ";\n\n";
						}
						$select_query = "SELECT * FROM " . $table . "";
						$statement = $con->prepare($select_query);
						$statement->execute();
						$total_row = $statement->rowCount();
						for($count=0; $count<$total_row; $count++) {
							$single_result = $statement->fetch(\PDO::FETCH_ASSOC);
							$table_column_array = array_keys($single_result);
							$table_value_array = array_values($single_result);
							$output .= "\nINSERT INTO $table (";
							$output .= "" . implode(", ", $table_column_array) . ") VALUES (";
							$output .= "'" . implode("','", $table_value_array) . "');\n";
						}
					}
				}

				if(!empty($output)) {
					$file_name = $dbname.'.sql';
					$backup_file = $path.$file_name;
					file_put_contents($backup_file, $output);
					if(file_exists($backup_file)) {
						$backupAlert = 1;
					}
				}
			}

			$msg = "";
			if(!empty($backupAlert) && $backupAlert == 1) {
				$msg = $backup_file;
			}
			else {
				$msg = $backupAlert;
			}
			return $msg;
		}

		public function numberFormat($number, $decimals = 0) {
			if (strpos($number,'.') != null) {
				$decimalNumbers = substr($number, strpos($number,'.'));
				$decimalNumbers = substr($decimalNumbers, 1, $decimals);
			}
			else {
				$decimalNumbers = 0;
				for ($i = 2; $i <=$decimals ; $i++) {
					$decimalNumbers = $decimalNumbers.'0';
				}
			}	
			$number = (int) $number;
			// reverse
			$number = strrev($number);	
			$n = '';
			$stringlength = strlen($number);
	
			for ($i = 0; $i < $stringlength; $i++) {
				if ($i%2==0 && $i!=$stringlength-1 && $i>1) {
					$n = $n.$number[$i].',';
				}
				else {
					$n = $n.$number[$i];
				}
			}
	
			$number = $n;
			// reverse
			$number = strrev($number);
				
			($decimals!=0)? $number=$number.'.'.$decimalNumbers : $number ;
	
			return $number;
		}
		
		public function truncate_number( $number, $precision = 2) {
			// Zero causes issues, and no need to truncate
			if ( 0 == (int)$number ) {
				return $number;
			}
			// Are we negative?
			$negative = $number / abs($number);
			// Cast the number to a positive to solve rounding
			$number = abs($number);
			// Calculate precision number for dividing / multiplying
			$precision = pow(10, $precision);
			// Run the math, re-applying the negative value to ensure returns correctly negative / positive
			return floor( $number * $precision ) / $precision * $negative;
		}

		public function SortingImages($images, $positions) {
			$sorted_images_list = array(); $image_position_list = array();
			for($i = 0; $i < count($images); $i++) {
				if(!empty($images[$i]) && !empty($positions[$i])) {
					$image_position_list[$i] = array('image' => $images[$i], 'position' => $positions[$i]);
				}
			}
			if(!empty($image_position_list)) {
				$values = array();
				foreach ($image_position_list as $key => $row) {
					$values[$key] = $row['position'];
				}
				array_multisort($values, SORT_ASC, $image_position_list);
				if(!empty($image_position_list)) {
					foreach($image_position_list as $key => $val) {
						if(!empty($val['image'])) {
							$sorted_images_list[] = $val['image'];
						}
					}
				}
			}
			return $sorted_images_list;
		}

	}	
?>