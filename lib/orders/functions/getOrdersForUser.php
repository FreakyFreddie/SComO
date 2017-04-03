<?php
	//returns all orders of a user with the products the order contains
	function getOrdersForUser($userid, $sort, $sequence)
	{
		$dal = new DAL();

		//prevent SQL injection
		$userid= mysqli_real_escape_string($dal->getConn(), $userid);
		$sort= mysqli_real_escape_string($dal->getConn(), $sort);
		$sequence= mysqli_real_escape_string($dal->getConn(), $sequence);

		//create array of parameters
		//first item = parameter types
		//i = integer
		//d = double
		//b = blob
		//s = string
		$parameters[0] = "sss";
		$parameters[1] = $userid;
		$parameters[2] = $sort;
		$parameters[3] = $sequence;

		//prepare statement
		$dal->setStatement("SELECT * FROM bestelling WHERE rnummer=? ORDER BY ? ?");
		$arrayorders = $dal->queryDB($parameters);

		$dal->closeConn();

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