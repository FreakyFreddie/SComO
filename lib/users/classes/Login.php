<?php
	//Login class
	class Login
	{
		private $userId;
		private $permissionLevel;
		private $firstName;
		private $lastName;
		private $loggedIn = False;

		private $dal;
		
		public function __construct($rnummer, $wachtwoord)
		{
			//check user & passwd vs database
			$this->dal = new DAL();

			//prevent sql injection
			$rnummer = mysqli_real_escape_string($this->dal->getConn(), $rnummer);
			$wachtwoord = mysqli_real_escape_string($this->dal->getConn(), $wachtwoord);
			
			//validate credentials vs DB
			$this->validateCredentials($rnummer, $wachtwoord);

			//close the connection
			$this->dal->closeConn();
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
			//test if user exists with rnummer and wachtwoord
			$sql = "SELECT rnummer, voornaam, achternaam, wachtwoord, machtigingsniveau FROM gebruiker WHERE rnummer='".$rnummer."'";
			$records = $this->dal->queryDB($sql);

			//if only 1 result AND hashed password matches password in db, fill in object attributes
			if($this->dal->getNumResults() == 1 && (password_verify($wachtwoord, $records[0]->wachtwoord) == TRUE))
			{
				$this->userId = $records[0]->rnummer;
				$this->firstName = $records[0]->voornaam;
				$this->lastName = $records[0]->achternaam;
				$this->permissionLevel = (int) $records[0]->machtigingsniveau;
				$this->loggedIn = True;
			}
		}
	}
?>