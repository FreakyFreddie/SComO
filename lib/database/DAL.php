<?php
	//Data Access Layer or Data Access Link (translate query results into objects and vice-versa)
	class DAL
	{
		public function __construct()
		{
			
		}
		
		//private function to connect to db, extra security layer
		private function dbconnect()
		{
			$DBSettings = (object) parse_ini_file('./config/dbconfig.ini', true);
			$conn = new mysqli($DBSettings->Server['servername'], $DBSettings->User['dbusername'], $DBSettings->User['dbpassword'], $DBSettings->Database['dbname']);
			
			// Check connection
			if ($conn->connect_error)
			{
				die("Connection failed: " . $conn->connect_error);
			}
			
			return $conn;
		}
	}
	
	
	//request info from DB
	function QueryDB($sql)
	{
		$DBSettings = (object) parse_ini_file('./config/dbconfig.ini', true);
		
		var_dump($DBSettings);
		// Create connection
		$conn = new mysqli($DBSettings->Server['servername'], $DBSettings->User['dbusername'], $DBSettings->User['dbpassword'], $DBSettings->Database['dbname']);
		
		// Check connection
		if ($conn->connect_error) {
			die("Connection failed: " . $conn->connect_error);
		}
		
		// Create database
		if ($conn->query($sql) === TRUE) {
			echo "Query succesfully executed";
		} else {
			echo "Error executing query: " . $conn->error;
		}
		
		$conn->close();
	}
	
	//send info to db
	
	
	$sql = "SELECT * FROM *";
	QueryDB($sql);
?>