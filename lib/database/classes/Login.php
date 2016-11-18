<?php
	//Login class
	class Login
	{
		private $userId;
		private $passWord;
		private $permissionLevel;
		private $loggedIn;
		private $dal;
		
		public function __construct($rnummer, $wachtwoord)
		{
			//check user & passwd vs database
			$this->dal = new DAL();
			
			//prevent sql injection
			$rnummer = mysqli_real_escape_string($this->dal->getConn(), $rnummer);
			$wachtwoord = mysqli_real_escape_string($this->dal->getConn(), $wachtwoord);
			
			//validate credentials vs DB
			$this->validateCredentials($rnummer, $wachtwoord)
			OR die("Gebruikersnaam of wachtwoord ongeldig");
			
			$this->dal->CloseConn();
		}
		
		private function validateCredentials($rnummer, $wachtwoord)
		{
			//test if user exists with rnummer and wachtwoord
			$sql = "SELECT rnummer, wachtwoord, machtigingsniveau FROM gebruiker WHERE rnummer='".$rnummer."'";
			$records = $this->dal->QueryDB($sql);
			
			if($this->dal->getNumResults() == 1 && password_verify($wachtwoord, $records[0]->wachtwoord));
			{
				$this->userId = $records[0]->rnummer;
				$this->permissionlevel = $records[0]->machtigingsniveau;
				$this->loggedIn = True;
				
				return True;
			}
		}
	}
?>