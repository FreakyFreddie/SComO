<?php
	function removeProject($projectid)
	{
		//new project object
		$project = new Project();

		//validate input
		$projectid = validateInput($projectid);

		//set the project id
		$project->__set("Id", $projectid);

		//remove participants from project & remove project from DB
		$project->removeParticipants();
		$project->deleteFromDB();
	}
?>