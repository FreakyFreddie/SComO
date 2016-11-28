<?php
	//returns all orders of a user with the products the order contains
	function getOrdersForUser($userid)
	{
		$dal = new DAL();

		//select all orders of user
		$sql = "SELECT * FROM bestelling WHERE rnummer='".$userid."'";
		$arrayorders = $dal->queryDB($sql);

		//create new array for user's orders
		$orders = array();

		//get the products for each order
		foreach($arrayorders as $arrayorder)
		{
			//create Order object & set attributes
			$order = new Order($userid);
			$order->__set("orderId", $arrayorder->bestelnummer);
			$order->__set("projectId", $arrayorder->idproject);
			$order->__set("userId", $arrayorder->rnummer);
			$order->__set("orderPersonal", $arrayorder->persoonlijk);
			$order->__set("orderDate", $arrayorder->besteldatum);
			$order->__set("orderStatus", $arrayorder->status);

			//get the products in the order
			$order->getProductsInOrder();

			//add order to array
			$orders[] = $order;
		}

		return $orders;
	}
?>