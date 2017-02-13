<?php
	//returns projectid & projectnaam of each project in DB
	function getProjectsFromDB()
	{
		$dal = new DAL();

		$sql = "SELECT idproject, titel FROM project";
		$records = $dal->queryDB($sql);

		$dal->closeConn();

		return $records;
	}
?>