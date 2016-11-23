<?php
	//this script processes the AJAX request & updates the user's shopping cart

	//load config, typecast to object for easy access
	$GLOBALS['settings'] = (object) parse_ini_file('../config/config.ini', true);

	//include classes BEFORE session_start because we might need them in session
	//include DAL (DAL & login always go on top since classes depend on them)
	require $GLOBALS['settings']->Folders['root'].'../lib/database/classes/DAL.php';

	//include login class
	require $GLOBALS['settings']->Folders['root'].'../lib/database/classes/Login.php';

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

	session_start();

	//check login condition and if the request contains all info
	if(isset($_SESSION["user"]) && $_SESSION["user"]->__get("loggedIn") && isset($_POST["productid"]) && isset($_POST["supplier"]) && isset($_POST["amount"]))
	{
		//we search product to add to database (this causes overhead, but allows us to validate the data)
		//new Data Access Layer object
		$dal = new DAL();

		if($_POST["supplier"]=="Farnell")
		{
			//send request to Farnell API, returns array of one FarnellProduct
			//what if there are no results? --> send error to page & terminate script (still needs work)
			$product = getFarnellProducts($_POST["productid"], 0, 1);

			//add product to cart (typecast amount string to int)
			addToCart($_SESSION["user"]->__get("userId"), $product, (int) $_POST["amount"], $dal);
		}
		elseif($_POST["supplier"]=="Mouser")
		{
			//send request to Mouser API, returns array of one MouserProduct
			$product = getMouserProducts($_POST["productid"], 0, 1);

			//add product to cart (typecast amount string to int)
			addToCart($_SESSION["user"]->__get("userId"), $product, (int) $_POST["amount"], $dal);
		}

		//close connection
		$dal->closeConn();
	}


	function addToCart($userId, $product, $productAmount, $dal)
	{
		//if product is not yet in DB, add it
		$product[0]->writeDB();

		//check if the user already has the product in its shopping cart
		//it is more efficient to do it like this, The other solution is to load everything in a ShoppingCart Object (longer DB access = more overhead)
		$sql = "SELECT aantal FROM winkelwagen WHERE idproduct='".$product[0]->__get("Id")."' AND leverancier='".$product[0]->__get("Supplier")."' AND rnummer='".$userId."'";
		$records = $dal->queryDB($sql);

		//if user already has some of the product in his cart, we need to add the amount & update the record (also update price if necessary)
		if($dal->getNumResults()==1)
		{
			$productAmount = $productAmount + $records[0]->aantal;

			//calculate price for amount of the product in the shopping cart
			$productPriceForAmount = extractProductPrice($productAmount, $product[0]->__get("Prices"));

			//update prijs en aantal of record
			$sql = "UPDATE winkelwagen SET aantal='".$productAmount."', prijs='".$productPriceForAmount."' WHERE rnummer='".$userId."' AND idproduct='".$product[0]->__get("Id")."' AND leverancier='".$product[0]->__get("Supplier")."'";
			$dal->writeDB($sql);
		}
		//else we just add the product to the cart
		else
		{
			//calculate price for amount of the product in the shopping cart
			$productPriceForAmount = extractProductPrice($productAmount, $product[0]->__get("Prices"));

			//we add the data to the users shopping cart
			writeToCart($userId, $product, $productAmount, $productPriceForAmount, $dal);
		}
	}

	function extractProductPrice($productAmount, $productPricesForAmounts)
	{
		$productPriceForAmount = NULL;

		//extract the product price for the given amount
		for($i = 0; $i < count($productPricesForAmounts); $i++)
		{
			//if user goes above the highest Quantity, the last price is valid
			if($productAmount < $productPricesForAmounts[count($productPricesForAmounts) - 1]->__get("Quantity"))
			{
				//when productAmount is between two Quantities, the price for the lowest Quantity is valid
				if($productAmount >= $productPricesForAmounts[$i]->__get("Quantity") && (int) $productAmount <= $productPricesForAmounts[$i + 1]->__get("Quantity"))
				{
					//break loop from the moment we have a match (if not, we end up using the price for the highest amount)
					$productPriceForAmount =  $productPricesForAmounts[$i]->__get("Price");
					break;
				}
			}
			else
			{
				//we end up using the price for the highest amount
				$productPriceForAmount =  $productPricesForAmounts[$i]->__get("Price");
			}
		}

		//if $productPriceForAmount is not an float, it is an object, so exit with error
		if(!is_float($productPriceForAmount))
		{
			//throw error
			echo "Er ging iets mis, probeer opnieuw";

			exit();
		}

		return $productPriceForAmount;
	}

	function writeToCart($userId, $product, $productAmount, $productPriceForAmount, $dal)
	{
		$sql = "INSERT INTO winkelwagen (rnummer, idproduct, leverancier, aantal, prijs) VALUES ('".$userId."', '".$product[0]->__get("Id")."', '".$product[0]->__get("Supplier")."', '".$productAmount."', '".$productPriceForAmount."')";
		$dal->writeDB($sql);
	}
?>