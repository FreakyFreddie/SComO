<?php
	//this script processes the AJAX request & updates the user's shopping cart

	//load config, typecast to object for easy access
	$GLOBALS['settings'] = (object) parse_ini_file('../../config/config.ini', true);

	//include classes BEFORE session_start because we might need them in session
	//include DAL (DAL & login always go on top since classes depend on them)
	require $GLOBALS['settings']->Folders['root'].'../lib/database/classes/DAL.php';

	//include login class
	require $GLOBALS['settings']->Folders['root'].'../lib/users/classes/Login.php';

	session_start();

	//check login condition and if the request contains all info
	//price should update as well, but this is only needed in the final order confirmation (avoiding the overhead)
	if(isset($_SESSION["user"]) && $_SESSION["user"]->__get("loggedIn") && isset($_POST["productid"]) && isset($_POST["supplier"]) && isset($_POST["amount"]))
	{
		//new Data Access Layer object
		$dal = new DAL();

		//prevent SQL injection
		$userId = mysqli_real_escape_string($dal->getConn(), $_SESSION["user"]->__get("userId"));
		$productId = mysqli_real_escape_string($dal->getConn(), $_POST["productid"]);
		$Supplier = mysqli_real_escape_string($dal->getConn(), $_POST["supplier"]);
		$productAmount = mysqli_real_escape_string($dal->getConn(), (int)$_POST["amount"]);

		//check if the user has the product in his shopping cart
		$sql = "SELECT idproduct FROM winkelwagen WHERE idproduct='".$productId."' AND leverancier='".$Supplier."' AND rnummer='".$userId."'";
		$dal->queryDB($sql);

		if($dal->getNumResults() == 1)
		{
			//if product exists in cart, update aantal of record
			$sql = "UPDATE winkelwagen SET aantal='".$productAmount."' WHERE rnummer='".$userId."' AND idproduct='".$productId."' AND leverancier='".$Supplier."'";
			$dal->writeDB($sql);
		}

		//close connection
		$dal->closeConn();
	}
?>