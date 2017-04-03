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

		//create array of parameters
		//first item = parameter types
		//i = integer
		//d = double
		//b = blob
		//s = string
		$parameters[0] = "s";
		$parameters[1] = $userid;

		//prepare statement
		//test if user already exists with rnummer
		$dal->setStatement("SELECT wachtwoord FROM gebruiker WHERE rnummer=?");
		$records = $dal->queryDB($parameters);
		unset($parameters);

		//if user already exists, numrows >= 1, if not we can continue
		if($dal->getNumResults()==1 && password_verify($oldpassword, $records[0]->wachtwoord))
		{
			//hash password (use PASSWORD_DEFAULT since php might update algorithms if they become stronger)
			$password = password_hash($password,PASSWORD_DEFAULT);

			//create array of parameters
			//first item = parameter types
			//i = integer
			//d = double
			//b = blob
			//s = string
			$parameters[0] = "ss";
			$parameters[1] = $password;
			$parameters[2] = $userid;

			//prepare statement
			$dal->setStatement("UPDATE gebruiker SET wachtwoord=? WHERE rnummer=?");
			$dal->writeDB($parameters);
			unset($parameters);

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