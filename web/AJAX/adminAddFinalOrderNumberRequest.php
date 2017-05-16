<?php
	//this script processes the AJAX request & shows the users shopping cart
	//load config, typecast to object for easy access
	$GLOBALS['settings'] = (object) parse_ini_file('../../config/config.ini', true);
	//include classes BEFORE session_start because we might need them in session
	//include DAL (DAL & login always go on top since classes depend on them)
	require $GLOBALS['settings']->Folders['root'].'../lib/database/classes/DAL.php';
	//include login class
	require $GLOBALS['settings']->Folders['root'].'../lib/users/classes/Login.php';
	//include Product class
	require $GLOBALS['settings']->Folders['root'].'../lib/products/classes/Product.php';
	//include Shopping Cart & ShoppingCartArticle
	require $GLOBALS['settings']->Folders['root'].'../lib/shoppingcart/classes/ShoppingCart.php';
	require $GLOBALS['settings']->Folders['root'].'../lib/shoppingcart/classes/ShoppingCartArticle.php';
	//include function to validate input
	require $GLOBALS['settings']->Folders['root'].'../lib/database/functions/validateInputs.php';
	session_start();
	//redirect if user is not logged in as admin
	if(!isset($_SESSION["user"]) OR $_SESSION["user"]->__get("loggedIn") != TRUE && $_SESSION["user"]->__get("permissionLevel") != 2)
	{
		header("location: ../index.php");
	}
	//check login condition and if the request contains all info
	if(isset($_SESSION["user"]) && $_SESSION["user"]->__get("loggedIn") && isset($_POST["supplier"]) && isset($_POST["finalordernumber"]) && !empty($_POST["supplier"])  && !empty($_POST["finalordernumber"]))
	{
		//validate input
		$finalordernumber = validateInput($_POST["finalordernumber"]);
		$supplier = validateInput($_POST["supplier"]);

		//prepare timestamp
		date_default_timezone_set('Europe/Brussels');

		$dal = new DAL();

		$finalordernumber = mysqli_real_escape_string($dal->getConn(), $_POST["finalordernumber"]);
		$supplier = mysqli_real_escape_string($dal->getConn(), $_POST["supplier"]);

		//create array of parameters
		//first item = parameter types
		//i = integer
		//d = double
		//b = blob
		//s = string
		$parameters[0] = "ssi";
		$parameters[1] = $finalordernumber;
		$parameters[2] = date("Y-n-j H:i:s");
		$parameters[3] = 0;

		//prepare statement
		//add final order number to database
		$dal->setStatement("INSERT INTO definitiefbesteld (defbestelnummer, defbesteldatum, status) VALUES (?, ?, ?)");
		$dal->writeDB($parameters);
		unset($parameters);

		//create array of parameters
		//first item = parameter types
		//i = integer
		//d = double
		//b = blob
		//s = string
		$parameters[0] = "ss";
		$parameters[1] = $finalordernumber;
		$parameters[2] = $supplier;

		//prepare statement
		//update the definitief bestelnummer column
		$dal->setStatement("UPDATE bestellingproduct
				INNER JOIN bestelling
				ON bestellingproduct.bestelnummer = bestelling.bestelnummer
				SET bestellingproduct.defbestelnummer = ?	
				WHERE bestelling.status = '2' AND bestellingproduct.leverancier = ?");
		$dal->writeDB($parameters);
		unset($parameters);

		//create array of parameters
		//first item = parameter types
		//i = integer
		//d = double
		//b = blob
		//s = string
		$parameters[0] = "s";
		$parameters[1] = $supplier;

		//prepare statement
		//update status of approved orders to "ordered" = 3
		$dal->setStatement("UPDATE bestelling
				INNER JOIN bestellingproduct
				ON bestellingproduct.bestelnummer = bestelling.bestelnummer
				SET bestelling.status = '3'
				WHERE bestelling.status = '2' AND bestellingproduct.leverancier = ?");
		$dal->writeDB($parameters);
		unset($parameters);

		$dal->closeConn();
	}
?>