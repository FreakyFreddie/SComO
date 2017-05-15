<?php
	//this script processes the AJAX request & updates the user's shopping cart

	//load config, typecast to object for easy access
	$GLOBALS['settings'] = (object) parse_ini_file('../../config/config.ini', true);

	//include classes BEFORE session_start because we might need them in session
	//include DAL (DAL & login always go on top since classes depend on them)
	require $GLOBALS['settings']->Folders['root'].'/lib/database/classes/DAL.php';

	//include login class
	require $GLOBALS['settings']->Folders['root'].'/lib/users/classes/Login.php';

	session_start();

	//redirect if user is not logged in
	if(!isset($_SESSION["user"]) OR $_SESSION["user"]->__get("loggedIn") != TRUE OR $_SESSION["user"]->__get("permissionLevel") == 0 OR $_SESSION["user"]->__get("permissionLevel") == 5)
	{
		header("location: index.php");
	}

	//check login condition and if the request contains all info
	if(isset($_SESSION["user"]) && $_SESSION["user"]->__get("loggedIn") && isset($_POST["productid"]) && isset($_POST["supplier"]))
	{
		//new Data Access Layer object
		$dal = new DAL();

		//prevent SQL injection
		$userId = mysqli_real_escape_string($dal->getConn(), $_SESSION["user"]->__get("userId"));
		$productId = mysqli_real_escape_string($dal->getConn(), $_POST["productid"]);
		$Supplier = mysqli_real_escape_string($dal->getConn(), $_POST["supplier"]);
		$productAmount = mysqli_real_escape_string($dal->getConn(), (int)$_POST["amount"]);

		//create array of parameters
		//first item = parameter types
		//i = integer
		//d = double
		//b = blob
		//s = string
		$parameters[0] = "sss";
		$parameters[1] = $productId;
		$parameters[2] = $Supplier;
		$parameters[3] = $userId;

		//prepare statement
		//check if the user has the product in his shopping cart
		$dal->setStatement("SELECT idproduct FROM winkelwagen WHERE idproduct=? AND leverancier=? AND rnummer=?");
		$dal->queryDB($parameters);
		unset($parameters);

		if($dal->getNumResults() == 1)
		{
			//create array of parameters
			//first item = parameter types
			//i = integer
			//d = double
			//b = blob
			//s = string
			$parameters[0] = "sss";
			$parameters[1] = $userId;
			$parameters[2] = $productId;
			$parameters[3] = $Supplier;


			//prepare statement
			//if product exists in cart, delete it
			$dal->setStatement("DELETE FROM winkelwagen WHERE rnummer=? AND idproduct=? AND leverancier=?");
			$dal->writeDB($parameters);
			unset($parameters);
		}

		//close connection
		$dal->closeConn();
	}
?>