<?php
	//returns the amount of new orders
	function getProductsTotal($supplier)
	{
		$dal = new DAL();

		//create array of parameters
		//first item = parameter types
		//i = integer
		//d = double
		//b = blob
		//s = string
		$parameters[0] = "s";
		$parameters[1] = $supplier;

		//prepare statement
		$dal->setStatement("SELECT COUNT(*) AS number FROM product WHERE leverancier=?");
		$count = $dal->queryDB($parameters);
		unset($parameters);

		$dal->closeConn();

		return $count[0]->number;
	}
?>