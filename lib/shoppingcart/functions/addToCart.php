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

		//create array of parameters
		//first item = parameter types
		//i = integer
		//d = double
		//b = blob
		//s = string
		$parameters[0] = "sss";
		$parameters[1] = $product[0]->__get("Id");
		$parameters[2] = $product[0]->__get("Supplier");
		$parameters[3] = $userId;

		//prepare statement
		//check if the user already has the product in its shopping cart
		//it is more efficient to do it like this, The other solution is to load everything in a ShoppingCart Object (longer DB access = more overhead)
		$dal->setStatement("SELECT idproduct, leverancier, aantal FROM winkelwagen WHERE idproduct='?' AND leverancier=? AND rnummer=?");
		$records = $dal->queryDB($parameters);
		unset($parameters);

		//if user already has some of the product in his cart, we need to add the amount & update the record (also update price if necessary)
		if($dal->getNumResults()==1)
		{
			$productAmount = $productAmount + $records[0]->aantal;

			//calculate price for amount of the product in the shopping cart
			$productPriceForAmount = $product[0]->extractProductPrice($productAmount);

			//create array of parameters
			//first item = parameter types
			//i = integer
			//d = double
			//b = blob
			//s = string
			$parameters[0] = "idsss";
			$parameters[1] = $productAmount;
			$parameters[2] = $productPriceForAmount;
			$parameters[3] = $userId;
			$parameters[4] = $product[0]->__get("Id");
			$parameters[5] = $product[0]->__get("Supplier");

			//prepare statement
			//update prijs and aantal of record
			$dal->setStatement("UPDATE winkelwagen SET aantal=?, prijs=? WHERE rnummer=? AND idproduct=? AND leverancier=?");
			$dal->writeDB($parameters);
			unset($parameters);
		}
		//else we just add the product to the cart
		elseif($dal->getNumResults()==0)
		{
			//calculate price for amount of the product in the shopping cart
			$productPriceForAmount = $product[0]->extractProductPrice($productAmount);

			//create array of parameters
			//first item = parameter types
			//i = integer
			//d = double
			//b = blob
			//s = string
			$parameters[0] = "sssid";
			$parameters[1] = $userId;
			$parameters[2] = $product[0]->__get("Id");
			$parameters[3] = $product[0]->__get("Supplier");
			$parameters[4] = $productAmount;
			$parameters[5] = $productPriceForAmount;

			//prepare statement
			//we add the data to the users shopping cart
			$dal->setStatement("INSERT INTO winkelwagen (rnummer, idproduct, leverancier, aantal, prijs) VALUES (?, ?, ?, ?, ?)");
			$dal->writeDB($parameters);
			unset($parameters);
		}

		//close connection
		$dal->closeConn();
	}
?>