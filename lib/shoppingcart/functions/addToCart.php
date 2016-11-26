<?php
	function addToCart($userId, $product, $productAmount)
	{
		//we search product to add to database (this causes overhead, but allows us to validate the data)
		//new Data Access Layer object
		$dal = new DAL();

		//prevent sql injection
		$userId = mysqli_real_escape_string($dal->getConn(), $userId);
		$productAmount = mysqli_real_escape_string($dal->getConn(), $productAmount);

		//if product is not yet in DB, add it
		$product[0]->writeDB();

		//check if the user already has the product in its shopping cart
		//it is more efficient to do it like this, The other solution is to load everything in a ShoppingCart Object (longer DB access = more overhead)
		$sql = "SELECT idproduct, leverancier, aantal FROM winkelwagen WHERE idproduct='".$product[0]->__get("Id")."' AND leverancier='".$product[0]->__get("Supplier")."' AND rnummer='".$userId."'";
		$records = $dal->queryDB($sql);

		//if user already has some of the product in his cart, we need to add the amount & update the record (also update price if necessary)
		if($dal->getNumResults()==1)
		{
			$productAmount = $productAmount + $records[0]->aantal;

			//calculate price for amount of the product in the shopping cart
			$productPriceForAmount = $product[0]->extractProductPrice($productAmount);

			//update prijs en aantal of record
			$sql = "UPDATE winkelwagen SET aantal='".$productAmount."', prijs='".$productPriceForAmount."' WHERE rnummer='".$userId."' AND idproduct='".$product[0]->__get("Id")."' AND leverancier='".$product[0]->__get("Supplier")."'";
			$dal->writeDB($sql);
		}
		//else we just add the product to the cart
		elseif($dal->getNumResults()==0)
		{
			//calculate price for amount of the product in the shopping cart
			$productPriceForAmount = $product[0]->extractProductPrice($productAmount);

			//we add the data to the users shopping cart
			$sql = "INSERT INTO winkelwagen (rnummer, idproduct, leverancier, aantal, prijs) VALUES ('".$userId."', '".$product[0]->__get("Id")."', '".$product[0]->__get("Supplier")."', '".$productAmount."', '".$productPriceForAmount."')";
			$dal->writeDB($sql);
		}

		//close connection
		$dal->closeConn();
	}
?>