<?php
	//returns the amount of users
	function getUsersTotal()
	{
		$dal = new DAL();

		$sql = "SELECT COUNT(*) FROM gebruiker";
		$count = $dal->countDB($sql);

		$dal->closeConn();

		return $count;
	}
?>