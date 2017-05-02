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

	//include functions
	require $GLOBALS['settings']->Folders['root'].'../lib/products/functions/getfarnellproducts.php';
	require $GLOBALS['settings']->Folders['root'].'../lib/products/functions/getmouserproducts.php';
	require $GLOBALS['settings']->Folders['root'].'../lib/shoppingcart/functions/addToCart.php';

	//input checks
	require $GLOBALS['settings']->Folders['root'].'../lib/database/functions/validateInputs.php';

	session_start();

	//redirect if user is not logged in
	if(!isset($_SESSION["user"]) OR $_SESSION["user"]->__get("loggedIn") != TRUE OR $_SESSION["user"]->__get("permissionLevel") == 0 OR $_SESSION["user"]->__get("permissionLevel") == 5)
	{
		header("location: index.php");
	}

	//check login condition and if the request contains all info
	if(isset($_SESSION["user"]) && $_SESSION["user"]->__get("loggedIn") && isset($_POST["productid"]) && isset($_POST["supplier"]) && isset($_POST["amount"]))
	{
		$amount = (int) $_POST["amount"];

		if($_POST["supplier"]=="Farnell")
		{
			//send request to Farnell API, returns array of one FarnellProduct
			//what if there are no results? --> send error to page & terminate script (still needs work)
			$product = getFarnellProducts($_POST["productid"], 0, 1);

			//only process if quantity is higher than or equal to minimum quantity
			if($amount >= $product[0]->__get("Prices")[0]->__get("Quantity"))
			{
				//add product to cart (typecast amount string to int)
				addToCart($_SESSION["user"]->__get("userId"), $product, $amount);
			}
		}
		elseif($_POST["supplier"]=="Mouser")
		{
			//send request to Mouser API, returns array of one MouserProduct
			$product = getMouserProducts($_POST["productid"], 0, 1);

			//only process if quantity is higher than or equal to minimum quantity
			if($amount >= $product[0]->__get("Prices")[0]->__get("Quantity"))
			{
				//add product to cart (typecast amount string to int)
				addToCart($_SESSION["user"]->__get("userId"), $product, (int)$_POST["amount"]);
			}
		}
		else
		{
			//check if product exists in database

			$dal = new DAL();

			//prevent sql injection
			$id = mysqli_real_escape_string($dal->getConn(), validateInput($_POST["productid"]));
			$supplier = mysqli_real_escape_string($dal->getConn(), validateInput($_POST["supplier"]));

			//check if user already exists
			$dal = new DAL();

			//create array of parameters
			//first item = parameter types
			//i = integer
			//d = double
			//b = blob
			//s = string
			$parameters[0] = "ss";
			$parameters[1] = $id;
			$parameters[2] = $supplier;

			//prepare statement
			$dal->setStatement("SELECT eigenprijs
					FROM product
					WHERE idproduct = ? AND leverancier = ?;");

			$records = $dal->queryDB($parameters);
			unset($parameters);

			if($dal->getNumResults() > 0)
			{
				$productprices = array();
				$productprices[0] = new ProductPrice("1", $records[0]->eigenprijs);

				$product = array();
				$product[0] = new Product();

				$product[0]->__set("Id", $id);
				$product[0]->__set("Supplier", $supplier);
				$product[0]->__set("Prices", $productprices);

				//only process if quantity is higher than or equal to minimum quantity
				if($amount >= 1)
				{
					//add product to cart (typecast amount string to int)
					addToCart($_SESSION["user"]->__get("userId"), $product, (int)$_POST["amount"]);
				}
			}

		}
	}
?>