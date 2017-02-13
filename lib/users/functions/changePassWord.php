<?php
	//changes the old password for the new
	function changePassWord($userid, $oldpassword, $password)
	{
		//new Data Access Layer object
		$dal = new DAL();

		//validate input for html injection & check vs REGEX, counter mysql injection
		$userid = mysqli_real_escape_string($dal->getConn(), validateRNummer($userid));
		$oldpassword = mysqli_real_escape_string($dal->getConn(), validateWachtWoord($oldpassword));
		$password = mysqli_real_escape_string($dal->getConn(), validateWachtWoord($password));

		//test if user already exists with rnummer
		$sql = "SELECT wachtwoord FROM gebruiker WHERE rnummer='".$userid."'";
		$records = $dal->queryDB($sql);

		//if user already exists, numrows >= 1, if not we can continue
		if($dal->getNumResults()==1 && password_verify($oldpassword, $records[0]->wachtwoord))
		{
			//hash password (use PASSWORD_DEFAULT since php might update algorithms if they become stronger)
			$password = password_hash($password,PASSWORD_DEFAULT);

			//write to DB use date("j-n-Y H:i:s") for date
			$sql = "UPDATE gebruiker SET wachtwoord='".$password."' WHERE rnummer='".$userid."'";
			$dal->writeDB($sql);

			//"user created" message
			echo "Wachtwoord gewijzigd.";
		}
		else
		{
			echo "<p>Fout bij het wijzigen van uw wachtwoord. Probeer opnieuw.</p>";
		}

		$dal->closeConn();
	}
?>