<?php
	//returns the amount of new orders
	function getOrdersTotal($status)
	{
		$dal = new DAL();

		//create array of parameters
		//first item = parameter types
		//i = integer
		//d = double
		//b = blob
		//s = string
		$parameters[0] = "i";

		//prepare statement
		$dal->setStatement("SELECT COUNT(*) FROM bestelling WHERE status=?");


		switch ($status) {
			case "Geweigerd":
				$parameters[1] = 0;
				$count = $dal->queryDB($parameters);
				break;

			case "Pending":
				$parameters[1] = 1;
				$count = $dal->queryDB($parameters);
				break;

			case "Goedgekeurd":
				$parameters[1] = 2;
				$count = $dal->queryDB($parameters);
				break;

			case "Besteld":
				$parameters[1] = 3;
				$count = $dal->queryDB($parameters);
				break;

			case "Aangekomen":
				$parameters[1] =4;
				$count = $dal->queryDB($parameters);
				break;

			case "Afgehaald":
				$parameters[1] = 5;
				$count = $dal->queryDB($parameters);
				break;
		}
		unset($parameters);

		$dal->closeConn();

		return $count;
	}
?>