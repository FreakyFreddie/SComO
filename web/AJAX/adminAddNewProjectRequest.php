<?php
	//this script processes the AJAX request & updates the user's shopping cart

	//load config, typecast to object for easy access
	$GLOBALS['settings'] = (object) parse_ini_file('../../config/config.ini', true);

	//include classes BEFORE session_start because we might need them in session
	//include DAL (DAL & login always go on top since classes depend on them)
	require $GLOBALS['settings']->Folders['root'].'/lib/database/classes/DAL.php';

	//include login class
	require $GLOBALS['settings']->Folders['root'].'/lib/users/classes/Login.php';

	//include ProductPrice class
	require $GLOBALS['settings']->Folders['root'].'/lib/project/classes/Project.php';

	//include project class
	require $GLOBALS['settings']->Folders['root'].'/lib/database/functions/validateInputs.php';

	session_start();

	//redirect if user is not logged in as admin
	if(!isset($_SESSION["user"]) OR $_SESSION["user"]->__get("loggedIn") != TRUE OR $_SESSION["user"]->__get("permissionLevel") != 2)
	{
		header("location: ../index.php");
	}

	//check login condition and if the request contains all info
	if(isset($_POST["projecttitle"]) && isset($_POST["projectfunds"]) && isset($_POST["projectaccount"]) && isset($_POST["projectstartdate"]) && isset($_POST["projectenddate"])
		&& !empty($_POST["projecttitle"]) && !empty($_POST["projectfunds"]) && !empty($_POST["projectaccount"]) && !empty($_POST["projectstartdate"]) && !empty($_POST["projectenddate"]))
	{
		//validate inputs
		$projecttitle = validateInput($_POST["projecttitle"]);
		$projectfunds = validateInput($_POST["projectfunds"]);
		$projectstartdate = validateInput($_POST["projectstartdate"]);
		$projectenddate = validateInput($_POST["projectenddate"]);
		$projectaccount = validateInput($_POST["projectaccount"]);

		//create new project
		$project = new Project($projecttitle, $projectfunds, $projectstartdate, $projectenddate, $projectaccount);

		//write project to DB
		$project->writeDB();
	}
?>