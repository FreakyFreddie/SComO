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
	if(!isset($_SESSION["user"]) OR $_SESSION["user"]->__get("loggedIn") != TRUE && $_SESSION["user"]->__get("permissionLevel") != 2)
	{
		header("location: ../index.php");
	}

	//check login condition and if the request contains all info
	if(isset($_SESSION["user"]) && $_SESSION["user"]->__get("loggedIn") && isset($_POST["idtitle"]) && isset($_POST["title"]) && isset($_POST["funds"]) && isset($_POST["account"]) && isset($_POST["startdate"]) && isset($_POST["enddate"])
		&& !empty($_POST["idtitle"]) && !empty($_POST["title"]) && !empty($_POST["funds"]) && !empty($_POST["account"]) && !empty($_POST["startdate"]) && !empty($_POST["enddate"]))
	{
		//validate input
		$idtitle = validateInput($_POST["idtitle"]);
		$title = validateInput($_POST["title"]);
		$funds = validateInput($_POST["funds"]);
		$account = validateInput($_POST["account"]);
		$startdate = validateInput($_POST["startdate"]);
		$enddate = validateInput($_POST["enddate"]);

		//split in id & title
		$idtitle = explode(" ", $idtitle);
		$idproject = $idtitle[0];

		//create new project object
		$project = new Project($title, $funds, $startdate, $enddate, $account);

		//set project id
		$project->__set("Id", $idproject);

		//extract project info from DB
		$project->updateDB();;
	}
?>