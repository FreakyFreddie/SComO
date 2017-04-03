<?php
	function removeUser($rnummer)
	{
		//validate input
		$rnummer = validateInput($rnummer);

		$dal = new DAL();

		//prevent sql injection
		$rnummer = mysqli_real_escape_string($dal->getConn(), $rnummer);

		//create array of parameters
		//first item = parameter types
		//i = integer
		//d = double
		//b = blob
		//s = string
		$parameters[0] = "s";
		$parameters[1] = $rnummer;

		//prepare statement
		//delete user from DB
		$dal->setStatement("DELETE FROM gebruiker WHERE rnummer = ?");
		$dal->writeDB($parameters);

		//prepare statement
		//delete user from projects -- also possible to delete from bestellingen, but probably not for the best
		$dal->setStatement("DELETE FROM gebruikerproject WHERE rnummer = ?");
		$dal->writeDB($parameters);

		//close the connection
		$dal->closeConn();
	}
?>