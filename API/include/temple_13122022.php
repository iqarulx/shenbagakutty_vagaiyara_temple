<?php
	class db
	{
		public $con;
		
		public function connect() {			
			$servername = "localhost";
			$username = "mahendran";
			$password = "@TlgKG;({Uhg";
			$this->db_name = "mahendran2022";
			
			try {
			  $con = new PDO("mysql:host=$servername;dbname=".$this->db_name.";charset=utf8", $username, $password);
			  // set the PDO error mode to exception
			  $con->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
			  return $con;
			} 
			catch(PDOException $e) {
			  echo "Connection failed: " . $e->getMessage();
			}
			
		}	
	}
?>