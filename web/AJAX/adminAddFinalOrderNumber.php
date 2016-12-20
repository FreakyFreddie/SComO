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

		//add final order number to database
		$finalordernumber = mysqli_real_escape_string($dal->getConn(), $_POST["finalordernumber"]);
		$supplier = mysqli_real_escape_string($dal->getConn(), $_POST["supplier"]);
		$sql = "INSERT INTO definitiefbesteld (defbestelnummer, leverancier, defbesteldatum) VALUES ('" . $finalordernumber . "', '" . $supplier . "', '" . date("Y-n-j H:i:s") . "')";
		$dal->writeDB($sql);

		//update the definitief bestelnummer column
		$sql = "UPDATE bestellingproduct
				INNER JOIN bestelling
				ON bestellingproduct.bestelnummer = bestelling.bestelnummer
				SET bestellingproduct.defbestelnummer = '".$finalordernumber."'	
				WHERE bestelling.status = '2' AND bestellingproduct.leverancier = '".$supplier."';";
		$dal->writeDB($sql);

		//update status of approved orders to "ordered" = 3
		$sql = "UPDATE bestelling
				INNER JOIN bestellingproduct
				ON bestellingproduct.bestelnummer = bestelling.bestelnummer
				SET bestelling.status = '3'
				WHERE bestelling.status = '2' AND bestellingproduct.leverancier = '".$supplier."';";
		$dal->writeDB($sql);

		$dal->closeConn();
	}

?>