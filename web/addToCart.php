<?php
	//this script processes the AJAX request & updates the user's shopping cart

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
		$conn = $dal->getConn();

		if($_POST["supplier"]=="Farnell")
		{
			//send request to Farnell API
			//what if there are no results? --> send error to page & terminate script (still needs work)
			$product = getFarnellProducts($_POST["productid"], 0, 1);

			//add product to cart (typecast amount string to int)
			addToCart($_SESSION["user"]->__get("userId"), $product, "Farnell", (int) $_POST["amount"], $dal, $conn);
		}
		elseif($_POST["supplier"]=="Mouser")
		{
			//send request to Mouser API
			$product = getMouserProducts($_POST["productid"], 0, 1);

			//add product to cart (typecast amount string to int)
			addToCart($_SESSION["user"]->__get("userId"), $product, "Mouser", (int) $_POST["amount"], $dal, $conn);
		}

		//close connection
		$dal->closeConn();
	}

	function addToCart($userId, $product, $productSupplier, $productAmount, $dal, $conn)
	{
		//since there will be database interaction, we prevent SQL injection
		$productId = mysqli_real_escape_string($conn, $product[0]->__get("Id"));
		$productName = mysqli_real_escape_string($conn, $product[0]->__get("Name"));
		$productSupplier = mysqli_real_escape_string($conn, $productSupplier);
		$productAmount = mysqli_real_escape_string($conn, $productAmount);
		$productPricesForAmounts = $product[0]->__get("Prices");

		//first check if the product already exists in the DB
		$sql = "SELECT idproduct FROM `product` WHERE idproduct='".$productId."' AND leverancier='".$productSupplier."'";
		$dal->queryDB($sql);

		//if product already exists in DB, we don't need to add it
		if($dal->getNumResults()==1)
		{
			//if product already existed in DB, we have to check if the user already has it in its shopping cart
			$sql = "SELECT aantal FROM `winkelwagen` WHERE idproduct='".$productId."' AND leverancier='".$productSupplier."' AND rnummer='".$userId."'";
			$records = $dal->queryDB($sql);

			//if user already has some of the product in his cart, we need to add the amount & update the record (also update price if necessary)
			if($dal->getNumResults()==1)
			{
				$productAmount = $productAmount + $records[0]->aantal;

				//calculate price for amount of the product in the shopping cart
				$productPriceForAmount = extractProductPrice($productAmount, $productPricesForAmounts);

				//update prijs en aantal of record
				$sql = "UPDATE winkelwagen SET aantal='".$productAmount."', prijs='".$productPriceForAmount."' WHERE rnummer='".$userId."' AND idproduct='".$productId."' AND leverancier='".$productSupplier."'";
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

		//extract the product price for the given amount
		for($i = 0; $i < count($productPricesForAmounts); $i++)
		{
			//if user goes above the highest Quantity, the last price is valid
			if($productAmount < $productPricesForAmounts[count($productPricesForAmounts) - 1]->__get("Quantity"))
			{
				//when productAmount is between two Quantities, the price for the lowest Quantity is valid
				if((int) $productAmount >= $productPricesForAmounts[$i]->__get("Quantity") && (int) $productAmount <= $productPricesForAmounts[$i + 1]->__get("Quantity"))
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
			exit();
		}

		return $productPriceForAmount;
	}

	function writeToCart($dal, $userId, $productId, $productSupplier, $productAmount, $productPriceForAmount)
	{
		$sql = "INSERT INTO winkelwagen (rnummer, idproduct, leverancier, aantal, prijs) VALUES ('".$userId."', '".$productId."', '".$productSupplier."', '".$productAmount."', '".$productPriceForAmount."')";
		$dal->writeDB($sql);
	}
?>