<?php
	//this script processes the AJAX request

	//load config, typecast to object for easy access
	$GLOBALS['settings'] = (object) parse_ini_file('../../config/config.ini', true);

	//include classes BEFORE session_start because we might need them in session
	//include DAL (DAL & login always go on top since classes depend on them)
	require $GLOBALS['settings']->Folders['root'].'../lib/database/classes/DAL.php';

	//include login class
	require $GLOBALS['settings']->Folders['root'].'../lib/users/classes/Login.php';

	//include Product class
	require $GLOBALS['settings']->Folders['root'].'../lib/products/classes/Product.php';

	//include project class
	require $GLOBALS['settings']->Folders['root'].'../lib/project/classes/Project.php';

	//include function to validate input
	require $GLOBALS['settings']->Folders['root'].'../lib/database/functions/validateInputs.php';

	session_start();

	//redirect if user is not logged in as admin
	if(!isset($_SESSION["user"]) OR $_SESSION["user"]->__get("loggedIn") != TRUE OR $_SESSION["user"]->__get("permissionLevel") != 2)
	{
		header("location: ../index.php");
	}

	//check login condition and if the request contains all info
	if(isset($_SESSION["user"]) && $_SESSION["user"]->__get("loggedIn") && isset($_POST["id"]) && isset($_POST["titel"]) && isset($_POST["budget"]) && isset($_POST["rekening"]) && isset($_POST["startdatum"]) && isset($_POST["einddatum"])
		&& !empty($_POST["id"]) && !empty($_POST["titel"]) && !empty($_POST["budget"]) && !empty($_POST["rekening"]) && !empty($_POST["startdatum"]) && !empty($_POST["einddatum"]))
	{
		//validate input
		$idproject = validateInput($_POST["id"]);
		$title = validateInput($_POST["titel"]);
		$funds = validateInput($_POST["budget"]);
		$account = validateInput($_POST["rekening"]);
		$startdate = validateInput($_POST["startdatum"]);
		$enddate = validateInput($_POST["einddatum"]);

		//create new project object
		$project = new Project($title, $funds, $startdate, $enddate, $account);

		//set project id
		$project->__set("Id", $idproject);

		//extract project info from DB
		$project->updateDB();
	}
?>