<?php
	//Data Access Layer or Data Access Link
	class DAL
	{
		private $DBSettings;
		private $conn;
		private $numResults;
		
		//pass root
		public function __construct()
		{
			//problem
			$this->DBSettings = (object) parse_ini_file($GLOBALS['settings']->Folders['root'].'../config/dbconfig.ini', true);
			$this->dbConnect();
		}
		
		//we need connection to use msqli escape (counter sql injection)
		public function getConn()
		{
			return $this->conn;
		}
		
		public function closeConn()
		{
			$this->conn->close();
		}
		
		//access the number of results
		public function getNumResults()
		{
			return $this->numResults;
		}
		
		//private function to connect to db, extra security layer
		private function dbConnect()
		{
			$this->conn = new mysqli($this->DBSettings->Server['servername'], $this->DBSettings->User['dbusername'], $this->DBSettings->User['dbpassword'])
			or die("Er kan geen connectie gemaakt worden met de databank");
			
			mysqli_select_db($this->conn, $this->DBSettings->Database['dbname'])
			or die("databank ".$this->DBSettings->Database['dbname']." niet beschikbaar");
		}
			
		//request information, get records
		public function QueryDB($sql)
		{
			$result = mysqli_query($this->conn, $sql)
			or die("Er is een fout opgetreden bij het uitvoeren van de query");
			
			//number of results
			$this->numResults = mysqli_num_rows($result);
			
			$records = array();
			
			while ($row = mysqli_fetch_object($result))
			{
				$records[] = $row;
			};
			
			return $records;
		}
		
		//Use to write something to DB
		public function WriteDB($sql)
		{
			mysqli_query($this->conn, $sql)
			or die("Er is een fout opgetreden bij het uitvoeren van de query");
		}
	}
?>