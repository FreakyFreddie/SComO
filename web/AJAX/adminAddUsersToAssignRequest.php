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

	//function to add json info to session array
	require $GLOBALS['settings']->Folders['root'].'../lib/users/functions/jsonToSession.php';

	session_start();

	//redirect if user is not logged in as admin
	if(!isset($_SESSION["user"]) OR $_SESSION["user"]->__get("loggedIn") != TRUE && $_SESSION["user"]->__get("permissionLevel") != 2)
	{
		header("location: ../index.php");
	}

	//check login condition and if the request contains all info
	if(isset($_SESSION["user"]) && $_SESSION["user"]->__get("loggedIn") && isset($_POST["array"]))
	{
		//jsontToSession($arrayname, $postarray)
		//add posted json info to the session array
		jsonToSession("adminAddUsersToAssignRequest", $_POST["array"]);
	}
?>