<?php
	//this script processes the AJAX request & shows the users shopping cart

	//load config, typecast to object for easy access
	$GLOBALS['settings'] = (object) parse_ini_file('../../config/config.ini', true);

	//include classes BEFORE session_start because we might need them in session
	//include DAL (DAL & login always go on top since classes depend on them)
	require $GLOBALS['settings']->Folders['root'].'/lib/database/classes/DAL.php';

	//include login class
	require $GLOBALS['settings']->Folders['root'].'/lib/users/classes/Login.php';

	//include Product class
	require $GLOBALS['settings']->Folders['root'].'/lib/products/classes/Product.php';

	//include Shopping Cart & ShoppingCartArticle
	require $GLOBALS['settings']->Folders['root'].'/lib/shoppingcart/classes/ShoppingCart.php';
	require $GLOBALS['settings']->Folders['root'].'/lib/shoppingcart/classes/ShoppingCartArticle.php';

	session_start();

	//redirect if user is not logged in as admin
	if(!isset($_SESSION["user"]) OR $_SESSION["user"]->__get("loggedIn") != TRUE OR $_SESSION["user"]->__get("permissionLevel") != 2)
	{
		header("location: ../index.php");
	}

	//check login condition and if the request contains all info
	if(isset($_SESSION["user"]) && $_SESSION["user"]->__get("loggedIn"))
	{
		//orders for projects to be approved (what is important?)
		$dal = new DAL();
		$sql = "SELECT bestelling.bestelnummer as bestelnr, bestelling.besteldatum as datum,
 			bestelling.rnummer as rnummer, SUM(bestellingproduct.aantal * bestellingproduct.prijs) as totaalkost
			FROM bestelling INNER JOIN bestellingproduct
			ON bestelling.bestelnummer=bestellingproduct.bestelnummer
			WHERE bestelling.status=1 AND bestelling.persoonlijk=1
			GROUP BY bestelling.bestelnummer;";
		$records = $dal->queryDBNoArgs($sql);

		//add buttons to change row or view details
		for($i = 0; $i < count($records); $i++)
		{
			$records[$i]->totaalkost = round($records[$i]->totaalkost, 2);
			$records[$i]->details = '<button class="btn btn-default" type="button" name="details" onclick="openNav('.$records[$i]->bestelnr.",'".$records[$i]->datum."','".$records[$i]->rnummer.'\')"><i class="fa fa-angle-double-right fa-lg"></i></button>';
		}

		//Lumino admin panel requires a JSON to process
		echo json_encode($records);
	}
?>