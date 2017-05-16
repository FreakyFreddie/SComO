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

	//include function to remove projects
	require $GLOBALS['settings']->Folders['root'].'../lib/project/functions/removeProject.php';

	//include project class
	require $GLOBALS['settings']->Folders['root'].'../lib/database/functions/validateInputs.php';

	session_start();

	//redirect if user is not logged in as admin
	if(!isset($_SESSION["user"]) OR $_SESSION["user"]->__get("loggedIn") != TRUE OR $_SESSION["user"]->__get("permissionLevel") != 2)
	{
		header("location: ../index.php");
	}

	//check login condition and if the request contains all info
	if(isset($_SESSION["user"]) && $_SESSION["user"]->__get("loggedIn") && isset($_POST["array"]) && isset($_POST["rnummer"]) && !empty($_POST["array"]) && !empty($_POST["rnummer"]))
	{
		$dal = new DAL();

		//prevent SQL injection
		$rnummer = mysqli_real_escape_string($dal->getConn(), $_POST["rnummer"]);

		//create array of parameters
		//first item = parameter types
		//i = integer
		//d = double
		//b = blob
		//s = string
		$parameters[0] = "si";
		$parameters[1] = $rnummer;

		//prepare statement
		$dal->setStatement("DELETE FROM gebruikerproject WHERE rnummer=? AND idproject=?");

		foreach($_POST["array"] as $user)
		{
			$id = mysqli_real_escape_string($dal->getConn(), $user["id"]);
			$parameters[2] = (int) $id;

			$dal->writeDB($parameters);
		}

		unset($parameters);

		$dal->closeConn();
	}
?>