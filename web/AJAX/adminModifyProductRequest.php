<?php
	//this script processes the AJAX request

	//load config, typecast to object for easy access
	$GLOBALS['settings'] = (object) parse_ini_file('../../config/config.ini', true);

	//include classes BEFORE session_start because we might need them in session
	//include DAL (DAL & login always go on top since classes depend on them)
	require $GLOBALS['settings']->Folders['root'].'/lib/database/classes/DAL.php';

	//include login class
	require $GLOBALS['settings']->Folders['root'].'/lib/users/classes/Login.php';

	//include Product class
	require $GLOBALS['settings']->Folders['root'].'/lib/products/classes/Product.php';

	//include project class
	require $GLOBALS['settings']->Folders['root'].'/lib/project/classes/Project.php';

	//include function to validate input
	require $GLOBALS['settings']->Folders['root'].'/lib/database/functions/validateInputs.php';

	session_start();

	//redirect if user is not logged in as admin
	if(!isset($_SESSION["user"]) OR $_SESSION["user"]->__get("loggedIn") != TRUE OR $_SESSION["user"]->__get("permissionLevel") != 2)
	{
		header("location: ../index.php");
	}

	//check login condition and if the request contains all info
	if(isset($_SESSION["user"]) && $_SESSION["user"]->__get("loggedIn") && isset($_POST["id"]) && isset($_POST["leverancier"]) && isset($_POST["naam"]) && isset($_POST["verkoper"]) && isset($_POST["prijs"])
		&& !empty($_POST["id"]) && !empty($_POST["leverancier"]) && !empty($_POST["naam"]) && !empty($_POST["verkoper"]) && !empty($_POST["prijs"]))
	{
		$dal = new DAL();

		//validate input
		$id = mysqli_real_escape_string($dal->getConn(), validateInput($_POST["id"]));
		$supplier = mysqli_real_escape_string($dal->getConn(), validateInput($_POST["leverancier"]));
		$name = mysqli_real_escape_string($dal->getConn(), validateInput($_POST["naam"]));
		$vendor = mysqli_real_escape_string($dal->getConn(), validateInput($_POST["verkoper"]));
		$price = mysqli_real_escape_string($dal->getConn(), validateInput($_POST["prijs"]));

		//create array of parameters
		//first item = parameter types
		//i = integer
		//d = double
		//b = blob
		//s = string
		$parameters[0] = "ssdss";
		$parameters[3] = $name;
		$parameters[4] = $vendor;
		$parameters[5] = (float)$price;
		$parameters[1] = $id;
		$parameters[2] = $supplier;

		//prepare statement
		$dal->setStatement("UPDATE product
					SET productnaam=?, productverkoper=?, eigenprijs=?
					WHERE idproduct=? AND leverancier=?");
		$dal->writeDB($parameters);
		unset($parameters);

		//close the connection
		$dal->closeConn();
	}
?>