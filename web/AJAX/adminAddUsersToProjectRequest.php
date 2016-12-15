<?php
	//this script processes the AJAX request & updates the user's shopping cart

	//load config, typecast to object for easy access
	$GLOBALS['settings'] = (object) parse_ini_file('../../config/config.ini', true);

	//include classes BEFORE session_start because we might need them in session
	//include DAL (DAL & login always go on top since classes depend on them)
	require $GLOBALS['settings']->Folders['root'].'../lib/database/classes/DAL.php';

	//include login class
	require $GLOBALS['settings']->Folders['root'].'../lib/users/classes/Login.php';

	//include ProductPrice class
	require $GLOBALS['settings']->Folders['root'].'../lib/project/classes/Project.php';

	//inputchecks
	require $GLOBALS['settings']->Folders['root'].'../lib/database/functions/validateInputs.php';

	session_start();

	//redirect if user is not logged in as admin
	if(!isset($_SESSION["user"]) OR $_SESSION["user"]->__get("loggedIn") != TRUE && $_SESSION["user"]->__get("permissionLevel") != 2)
	{
		header("location: ../index.php");
	}

	//var_dump($_SESSION["adminAddProjectsToAssignRequest"]);
	//var_dump($_SESSION["adminAddUsersToAssignRequest"]);

	//check login condition and if the request contains all info
	if(isset($_SESSION["user"]) && $_SESSION["user"]->__get("loggedIn") && isset($_SESSION["adminAddProjectsToAssignRequest"]) && isset($_SESSION["adminAddUsersToAssignRequest"]))
	{
		$dal = new DAL();

		foreach($_SESSION["adminAddProjectsToAssignRequest"] as $project)
		{
			//sanitize DB input
			$projectid = mysqli_real_escape_string($dal->getConn(), $project["id"]);

			foreach($_SESSION["adminAddUsersToAssignRequest"] as $user)
			{
				$userid = explode("@", $user["email"]);
				$userid = mysqli_real_escape_string($dal->getConn(), $userid[0]);

				$sql = "SELECT * FROM gebruikerproject WHERE rnummer='" . $userid . "' AND idproject= '" . $projectid . "'";
				$records = $dal->queryDB($sql);

				//if not exists, add row
				if($dal->getNumResults() == 0)
				{
					$sql = "INSERT INTO gebruikerproject (rnummer, idproject) VALUES ('" . $userid . "', '" . $projectid . "')";
					$dal->writeDB($sql);
				}
			}
		}

		$dal->closeConn();

		//unset the session arrays
		unset($_SESSION["adminAddUsersToAssignRequest"]);
		unset($_SESSION["adminAddProjectsToAssignRequest"]);
	}
?>