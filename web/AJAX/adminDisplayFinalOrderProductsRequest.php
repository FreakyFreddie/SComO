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

	//input checks
	require $GLOBALS['settings']->Folders['root'].'/lib/database/functions/validateInputs.php';

	session_start();

	//redirect if user is not logged in as admin
	if(!isset($_SESSION["user"]) OR $_SESSION["user"]->__get("loggedIn") != TRUE OR $_SESSION["user"]->__get("permissionLevel") != 2)
	{
		header("location: ../index.php");
	}

	//check login condition and if the request contains all info
	if(isset($_SESSION["user"]) && $_SESSION["user"]->__get("loggedIn") && isset($_GET["defbestelnummer"]))
	{
		//Select orders from view "bestellingen"
		$dal = new DAL();

		$defbestelnummer = validateInput($_GET["defbestelnummer"]);
		$defbestelnummer = mysqli_real_escape_string($dal->getConn(), $defbestelnummer);

		//create array of parameters
		//first item = parameter types
		//i = integer
		//d = double
		//b = blob
		//s = string
		$parameters[0] = "s";
		$parameters[1] = $defbestelnummer;

		//prepare statement
		$dal->setStatement("SELECT product.idproduct, product.leverancier, product.productnaam, product.productverkoper, product.productafbeelding, product.productdatasheet, bestellingproduct.prijs, bestellingproduct.aantal
			FROM bestellingproduct
			INNER JOIN product
			ON product.idproduct=bestellingproduct.idproduct
			WHERE bestellingproduct.defbestelnummer=?");

		$records = $dal->queryDB($parameters);
		unset($parameters);

		for($i = 0; $i<count($records); $i++)
		{
			$records[$i]->prijs = round($records[$i]->prijs, 2);
			$records[$i]->productdatasheet = '<a href="'.$records[$i]->productdatasheet.'">Link</a>';
			$records[$i]->productafbeelding = '<img class="img img-responsive" src="'.$records[$i]->productafbeelding.'" />';
		}

		$dal->closeConn();

		//Lumino admin panel requires a JSON to process
		echo json_encode($records);
	}
?>