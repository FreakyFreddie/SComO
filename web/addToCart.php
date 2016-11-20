<?php
	session_start();

	//check login condition and if the request contains all info
	if(isset($_SESSION["user"]) && $_SESSION["user"]->__get("loggedIn") && isset($_REQUEST["productid"]) && isset($_REQUEST["supplier"]) && isset($_REQUEST["value"]))
	{
		$_SESSION["cart"]["productid"]=$_REQUEST["productid"];
		$_SESSION["cart"]["supplier"]=$_REQUEST["supplier"];
		$_SESSION["cart"]["value"]=$_REQUEST["value"];

		//search product to add to database (this causes overhead, but allows us to validate the data)
		if($_REQUEST["supplier"]=="Farnell")
		{
			//send request to Farnell API
			$product = getFarnellProducts($_REQUEST["productid"], 0, 1);
		}
		elseif($_REQUEST["supplier"]=="Mouser")
		{
			//send request to Mouser API
			$product = getMouserProducts($_REQUEST["productid"], 0, 1);
		}



		//add the product to the users shopping cart in DB

	}

	function

?>