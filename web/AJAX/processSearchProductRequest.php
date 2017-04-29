<?php
	//this script processes the AJAX request & updates the user's shopping cart

	//load config, typecast to object for easy access
	$GLOBALS['settings'] = (object) parse_ini_file('../../config/config.ini', true);

	//include classes BEFORE session_start because we might need them in session
	//include DAL (DAL & login always go on top since classes depend on them)
	require $GLOBALS['settings']->Folders['root'].'../lib/database/classes/DAL.php';

	//include login class
	require $GLOBALS['settings']->Folders['root'].'../lib/users/classes/Login.php';

	//include ProductPrice class
	require $GLOBALS['settings']->Folders['root'].'../lib/products/classes/ProductPrice.php';

	//include Product class
	require $GLOBALS['settings']->Folders['root'].'../lib/products/classes/Product.php';

	//include MouserProduct
	require $GLOBALS['settings']->Folders['root'].'../lib/products/classes/MouserProduct.php';

	//include FarnellProduct
	require $GLOBALS['settings']->Folders['root'].'../lib/products/classes/FarnellProduct.php';

	//globally used functions go here
	require $GLOBALS['settings']->Folders['root'].'../lib/products/functions/getfarnellproducts.php';
	require $GLOBALS['settings']->Folders['root'].'../lib/products/functions/getmouserproducts.php';

	//logfunction
	require $GLOBALS['settings']->Folders['root'].'../lib/users/functions/logActivity.php';

	//input checks
	require $GLOBALS['settings']->Folders['root'].'../lib/database/functions/validateInputs.php';

	session_start();

	//redirect if user is not logged in
	if(!isset($_SESSION["user"]) OR $_SESSION["user"]->__get("loggedIn") != TRUE OR $_SESSION["user"]->__get("permissionLevel") == 0 OR $_SESSION["user"]->__get("permissionLevel") == 5)
	{
		header("location: ../index.php");
	}

	if(isset($_SESSION["user"]) && $_SESSION["user"]->__get("loggedIn") && isset($_POST["searchterm"]))
	{
		//send request to Farnell API
		$farnellproducts = getFarnellProducts(validateInput($_POST['searchterm']), 0, 20);

		//send request to Mouser API
		$mouserproducts = getMouserProducts(validateInput($_POST['searchterm']), 0, 20);

		$products = array();

		$i=0;

		foreach($farnellproducts as $farnellproduct)
		{
			$products[$i]["name"] = $farnellproduct->__get("Name");
			$products[$i]["image"] = $farnellproduct->__get("Image");
			$products[$i]["id"] = $farnellproduct->__get("Id");
			$products[$i]["Vendor"] = $farnellproduct->__get("Vendor");
			$products[$i]["supplier"] = $farnellproduct->__get("Supplier");
			$products[$i]["inventory"] = $farnellproduct->__get("Inventory");
			$products[$i]["datasheet"] = $farnellproduct->__get("DataSheet");
			$products[$i]["prices"] = $farnellproduct->__get("Prices");
			$i++;
		}

		foreach($mouserproducts as $mouserproduct)
		{
			$products[$i]["name"] = $mouserproduct->__get("Name");
			$products[$i]["image"] = $mouserproduct->__get("Image");
			$products[$i]["id"] = $mouserproduct->__get("Id");
			$products[$i]["Vendor"] = $mouserproduct->__get("Vendor");
			$products[$i]["supplier"] = $mouserproduct->__get("Supplier");
			$products[$i]["inventory"] = $mouserproduct->__get("Inventory");
			$products[$i]["datasheet"] = $mouserproduct->__get("DataSheet");
			$products[$i]["prices"] = $mouserproduct->__get("Prices");
			$i++;
		}

		//Lumino admin panel requires a JSON to process
		echo json_encode($products);
	}
?>