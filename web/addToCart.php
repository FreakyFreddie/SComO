<?php
	//load config, typecast to object for easy access
	$GLOBALS['settings'] = (object) parse_ini_file('../config/config.ini', true);

	//include classes BEFORE session_start because we might need them in session
	//include ProductPrice class
	require $GLOBALS['settings']->Folders['root'].'../lib/products/classes/ProductPrice.php';

	//include Product class
	require $GLOBALS['settings']->Folders['root'].'../lib/products/classes/Product.php';

	//include MouserProduct
	require $GLOBALS['settings']->Folders['root'].'../lib/products/classes/MouserProduct.php';

	//include FarnellProduct
	require $GLOBALS['settings']->Folders['root'].'../lib/products/classes/FarnellProduct.php';

	//include DAL
	require $GLOBALS['settings']->Folders['root'].'../lib/database/classes/DAL.php';

	//include login class
	require $GLOBALS['settings']->Folders['root'].'../lib/database/classes/Login.php';

	session_start();

	//check login condition and if the request contains all info
	if(isset($_SESSION["user"]) && $_SESSION["user"]->__get("loggedIn") && isset($_REQUEST["productid"]) && isset($_REQUEST["supplier"]) && isset($_REQUEST["value"]))
	{
		//we search product to add to database (this causes overhead, but allows us to validate the data)
		//new Data Access Layer object
		$dal = new DAL();
		$conn = $dal->getConn();

		if($_REQUEST["supplier"]=="Farnell")
		{
			//send request to Farnell API
			//what if there are no results? --> send error to page & terminate script (still needs work)
			$product = getFarnellProducts($_REQUEST["productid"], 0, 1);

			//add product to cart
			addToCart($_SESSION["user"]->__get("userId"), $product, $_REQUEST["value"], $dal, $conn);
		}
		elseif($_REQUEST["supplier"]=="Mouser")
		{
			//send request to Mouser API
			$product = getMouserProducts($_REQUEST["productid"], 0, 1);

			//add product to cart
			addToCart($_SESSION["user"]->__get("userId"), $product, $_REQUEST["value"], $dal, $conn);
		}

		//close connection
		$dal->closeConn();
	}

	function addToCart($userId, $product, $productAmount, $dal, $conn)
	{
		//since there will be database interaction, we prevent SQL injection
		$productId = mysqli_real_escape_string($conn, $product->__get("Id"));
		$productName = mysqli_real_escape_string($conn, $productAmount);
		$productSupplier = "Farnell";
		$productAmount = mysqli_real_escape_string($conn, $_REQUEST["value"]);
		$productPricesForAmounts = $product->__get("Prices");

		//first check if the product already exists in the DB
		$sql = "SELECT idproduct FROM `product` WHERE idproduct='".$productId."' AND leverancier='".$productSupplier."'";
		$dal->queryDB($sql);

		//if product already exists in DB, we don't need to add it
		if($dal->getNumResults()==1)
		{
			//if product already existed in DB, we have to check if the user already has it in its shopping cart
			$sql = "SELECT aantal FROM `winkelwagen` WHERE idproduct='".$productId."' AND leverancier='".$productSupplier."' AND rnummer='".$_SESSION["user"]->__get("userId")."'";
			$records = $dal->queryDB($sql);

			//if user already has some of the product in his cart, we need to add the amount & update the record (also update price if necessary)
			if($dal->getNumResults()==1)
			{
				$productAmount = $productAmount + $records[0]->aantal;

				//calculate price for amount of the product in the shopping cart
				$productPriceForAmount = extractProductPrice($productAmount, $productPricesForAmounts);

				//update prijs en aantal of record
				$sql = "UPDATE winkelwagen SET aantal='".$productAmount."', prijs='".$productPriceForAmount."' WHERE rnummer='".$_SESSION["user"]->__get("userId")."' AND idproduct='".$productId."' AND leverancier='".$productSupplier."'";
				$dal->writeDB($sql);
			}
			//else we add the product to the cart
			else
			{
				//calculate price for amount of the product in the shopping cart
				$productPriceForAmount = extractProductPrice($productAmount, $productPricesForAmounts);

				//we add the data to the users shopping cart
				writeToCart($dal, $userId, $productId, $productSupplier, $productAmount, $productPriceForAmount);
			}
		}
		//else we add the product to the DB & add the product to the cart
		else
		{
			$sql = "INSERT INTO product (idproduct, leverancier, productnaam) VALUES ('".$productId."', '".$productSupplier."', '".$productName."')";
			$dal->writeDB($sql);

			//calculate price for amount of the product in the shopping cart
			$productPriceForAmount = extractProductPrice($productAmount, $productPricesForAmounts);

			//we add the data to the users shopping cart
			writeToCart($dal, $userId, $productId, $productSupplier, $productAmount, $productPriceForAmount);
		}
	}

	function extractProductPrice($productAmount, $productPricesForAmounts)
	{
		$productPriceForAmount = NULL;
		foreach($productPricesForAmounts as $productPriceForAmount)
		{
			if($productAmount >= $productPriceForAmount->productFromQuantity)
			{
				//break loop from the moment we have a match (if not, we end up using the price for the highest amount)
				$productPriceForAmount = $productPriceForAmount->productPriceQuantity;
				break;
			}
		}
		return $productPriceForAmount;
	}

	function writeToCart($dal, $userId, $productId, $productSupplier, $productAmount, $productPriceForAmount)
	{
		$sql = "INSERT INTO winkelwagen (rnummer, idproduct, leverancier, aantal, prijs) VALUES ('".$userId."', '".$productId."', '".$productSupplier."', '".$productAmount."', '".$productPriceForAmount."')";
		$dal->writeDB($sql);
	}
?>