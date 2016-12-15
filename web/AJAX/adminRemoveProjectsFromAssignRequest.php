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

	session_start();

	//redirect if user is not logged in as admin
	if(!isset($_SESSION["user"]) OR $_SESSION["user"]->__get("loggedIn") != TRUE && $_SESSION["user"]->__get("permissionLevel") != 2)
	{
		header("location: ../index.php");
	}

	//check login condition and if the request contains all info
	if(isset($_SESSION["user"]) && $_SESSION["user"]->__get("loggedIn") && isset($_POST["array"]) && isset($_SESSION["adminAddProjectsToAssignRequest"]))
	{
		//remove array from session array
		foreach($_POST["array"] as $key => $assignedproject)
		{
			//remove matching elements from session array
			for($i = 0; $i < count($_SESSION["adminAddProjectsToAssignRequest"]); $i++)
			{
				if($_SESSION["adminAddProjectsToAssignRequest"][$i]["id"] == $_POST["array"][$key]["id"])
				{
					array_splice($_SESSION["adminAddProjectsToAssignRequest"], $i, 1);
				}
			}
		}
	}
?>