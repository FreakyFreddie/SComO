<?php
	//Data Access Layer or Data Access Link
	class DAL
	{
		private $DBSettings;
		private $conn;
		private $numResults;
		private $statement;
		
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

		public function setStatement($statement)
		{
			$this->statement = $statement;
		}

		//request information, get records (without parameters)
		public function queryDBNoArgs($sql)
		{
			//echo $sql;
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
			
		//request information, get records (with parameters)
		public function queryDB($parameters)
		{
			if(!isset($this->statement))
			{
				//debug
				echo "statement not set";
			}
			else
			{
				//prepare and bind
				$stmt = $this->conn->prepare($this->statement) OR die("ERROR: Statement not valid.");

				//allows us to bind a variable amount of parameters
				//remember: first parameter is always a string of parameter types
				call_user_func_array(array($stmt, 'bind_param'), $this->refValues($parameters));

				//execute the statement with the passed parameters
				$result = $stmt->execute() or die("Er is een fout opgetreden bij het uitvoeren van de query");

				//fetch result
				$result = $stmt->get_result();

				//number of results
				$this->numResults = mysqli_num_rows($result);

				$records = array();

				while ($row = mysqli_fetch_object($result))
				{
					$records[] = $row;
				};

				$stmt->close();

				return $records;
			}
		}
		
		//Use to write something to DB
		public function writeDB($parameters)
		{
			if(!isset($this->statement))
			{
				//debug
				echo "statement not set";
			}
			else
			{
				//prepare and bind
				$stmt = $this->conn->prepare($this->statement) OR die("ERROR: Statement not valid.");

				//allows us to bind a variable amount of parameters
				//remember: first parameter is always a string of parameter types
				call_user_func_array(array($stmt, 'bind_param'), $this->refValues($parameters));

				//execute the statement with the passed parameters
				$stmt->execute() or die("Er is een fout opgetreden bij het uitvoeren van de query");

				$stmt->close();
			}
		}

		//returns one value: count, use for count queries
		public function countDB($sql)
		{
			//echo $sql;
			$result = mysqli_query($this->conn, $sql)
			or die("Er is een fout opgetreden bij het uitvoeren van de query");

			$data=mysqli_fetch_assoc($result);

			return $data["COUNT(*)"];
		}

		//bind_params needs references in php 5.3+
		private function refValues($arr){
			if (strnatcmp(phpversion(),'5.3') >= 0) //Reference is required for PHP 5.3+
			{
				$refs = array();
				foreach($arr as $key => $value)
				{
					$refs[$key] = &$arr[$key];
				}

				return $refs;
			}
			return $arr;
		}
	}
?>