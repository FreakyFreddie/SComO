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

	session_start();

	//redirect if user is not logged in as admin
	if(!isset($_SESSION["user"]) OR $_SESSION["user"]->__get("loggedIn") != TRUE OR $_SESSION["user"]->__get("permissionLevel") != 2)
	{
		header("location: ../index.php");
	}

	//check login condition and if the request contains all info
	if(isset($_SESSION["user"]) && $_SESSION["user"]->__get("loggedIn"))
	{
		//Select orders from view "bestellingen"
		$dal = new DAL();
		$sql = "SELECT definitiefbesteldebestellingen.defbestelnummer, definitiefbesteldebestellingen.defbesteldatum, definitiefbesteldebestellingen.leverancier, SUM(totaalkost) AS totaalkost
				FROM definitiefbesteldebestellingen
				INNER JOIN bestelling
				ON definitiefbesteldebestellingen.bestelnummer = bestelling.bestelnummer
				WHERE bestelling.status = '3'
				GROUP BY definitiefbesteldebestellingen.defbestelnummer";
		$records = $dal->queryDB($sql);

		//Lumino admin panel requires a JSON to process
		echo json_encode($records);
	}
?>