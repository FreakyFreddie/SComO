<?php
	//returns the amount of new orders
	function getProjectsTotal()
	{
		$dal = new DAL();

		$sql = "SELECT COUNT(*) FROM project";
		$count = $dal->countDB($sql);

		$dal->closeConn();

		return $count;
	}
?>