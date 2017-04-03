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
	//$_GET["supplier"]="Farnell";

	//redirect if user is not logged in as admin
	if(!isset($_SESSION["user"]) OR $_SESSION["user"]->__get("loggedIn") != TRUE OR $_SESSION["user"]->__get("permissionLevel") != 2)
	{
		header("location: ../index.php");
	}

	//check login condition and if the request contains all info
	if(isset($_SESSION["user"]) && $_SESSION["user"]->__get("loggedIn") && isset($_GET["supplier"]) && !empty($_GET["supplier"]))
	{
		//validate input
		$supplier = validateInput($_GET["supplier"]);

		//get orders for projects to be approved (what is important?)
		$dal = new DAL();
		$supplier = mysqli_real_escape_string($dal->getConn(), $supplier);

		//create array of parameters
		//first item = parameter types
		//i = integer
		//d = double
		//b = blob
		//s = string
		$parameters[0] = "s";
		$parameters[1] = $supplier;

		//prepare statement
		//get user email
		$dal->setStatement("SELECT bestellingproduct.idproduct as productid, SUM(bestellingproduct.aantal) as aantal
			FROM bestelling INNER JOIN bestellingproduct
			ON bestelling.bestelnummer=bestellingproduct.bestelnummer
			WHERE bestelling.status=2 AND bestellingproduct.leverancier=?
			GROUP BY bestellingproduct.idproduct");
		$records = $dal->queryDB($parameters);
		unset($parameters);

		//prepare timestamp
		date_default_timezone_set('Europe/Brussels');

		//farnell BOM
		if($supplier == "Farnell")
		{
			//prepare headers
			header("Content-type: text/csv");
			header("Content-Disposition: attachment; filename=FarnellBOM_".date("Y_n_j").".csv");
			header("Pragma: no-cache");
			header("Expires: 0");

			//content
			foreach($records as $product)
			{
				echo $product->productid.",".$product->aantal."\n";
			}
		}
		//Mouser BOM
		elseif ($supplier == "Mouser")
		{
			//prepare headers
			header("Content-type: text/csv");
			header("Content-Disposition: attachment; filename=MouserBOM_".date("Y_n_j").".csv");
			header("Pragma: no-cache");
			header("Expires: 0");

			//content
			foreach($records as $product)
			{
				echo $product->productid.",".$product->aantal."\n";
			}
		}

		$dal->closeConn();
	}

?>