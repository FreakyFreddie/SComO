<?php
	//Login class
	class Login
	{
		private UserId;
		private PassWord;
		private PermissionLevel;
		
		public function __construct($rnr, $pwd)
		{
			//check user & passwd vs database
			$dal = new DAL();
			
			$wachtwoord = mysqli_real_escape_string($conn, validateWachtWoord($_POST["wachtwoord"]));
			
			//test if user exists with rnummer and wachtwoord
			$sql = "SELECT rnummer FROM gebruiker WHERE rnummer='".$rnr."' AND '".$pwd."'";
			$records = $dal->QueryDB($sql);
			
			$dal->CloseConn();
			
			//
		}
		
		private function()
	}
?>