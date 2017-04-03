<?php
	//Login class
	class Login
	{
		private $userId;
		private $permissionLevel;
		private $firstName;
		private $lastName;
		private $loggedIn = False;
		
		public function __construct($rnummer, $wachtwoord)
		{
			//check user & passwd vs database
			$dal = new DAL();

			//prevent sql injection
			$rnummer = mysqli_real_escape_string($dal->getConn(), $rnummer);
			$wachtwoord = mysqli_real_escape_string($dal->getConn(), $wachtwoord);
			
			//validate credentials vs DB
			$this->validateCredentials($rnummer, $wachtwoord);

			//close the connection
			$dal->closeConn();
		}
		
		//returns property value
		public function __get($property)
		{
			switch($property)
			{
				case "userId":
				$result = $this->userId;
				break;
				
				case "firstName":
				$result = $this->firstName;
				break;
				
				case "lastName":
				$result = $this->lastName;
				break;
				
				case "permissionLevel":
				$result = $this->permissionLevel;
				break;
				
				case "loggedIn":
				$result = $this->loggedIn;
				break;
			}
			
			return $result;
		}
		
		private function validateCredentials($rnummer, $wachtwoord)
		{
			$dal = new DAL();

			//create array of parameters
			//first item = parameter types
			//i = integer
			//d = double
			//b = blob
			//s = string
			$parameters[0] = "s";
			$parameters[1] = $rnummer;

			//prepare statement
			$dal->setStatement("SELECT rnummer, voornaam, achternaam, wachtwoord, machtigingsniveau FROM gebruiker WHERE rnummer=?");
			$records = $dal->queryDB($parameters);

			//if only 1 result AND hashed password matches password in db, fill in object attributes
			if($dal->getNumResults() == 1 && (password_verify($wachtwoord, $records[0]->wachtwoord) == TRUE))
			{
				$this->userId = $records[0]->rnummer;
				$this->firstName = $records[0]->voornaam;
				$this->lastName = $records[0]->achternaam;
				$this->permissionLevel = (int) $records[0]->machtigingsniveau;
				$this->loggedIn = True;
			}

			$dal->closeConn();
		}
	}
?>