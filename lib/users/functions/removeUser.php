<?php
	function removeUser($rnummer)
	{
		//validate input
		$rnummer = validateInput($rnummer);

		$dal = new DAL();

		//prevent sql injection
		$rnummer = mysqli_real_escape_string($dal->getConn(), $rnummer);

		//delete project from DB
		$sql = "DELETE FROM gebruiker WHERE rnummer = '".$rnummer."'";
		$dal->writeDB($sql);

		//close the connection
		$dal->closeConn();
	}
?>