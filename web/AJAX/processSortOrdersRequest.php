<?php
	//set page var in order to adapt navbar and functions
	$GLOBALS['page'] = "bestellingen";

	//load config, typecast to object for easy access
	$GLOBALS['settings'] = (object) parse_ini_file('../../config/config.ini', true);

	//include classes BEFORE session_start because we might need them in session
	//include DAL (DAL & login always go on top since classes depend on them)
	require $GLOBALS['settings']->Folders['root'].'../lib/database/classes/DAL.php';

	//include login class
	require $GLOBALS['settings']->Folders['root'].'../lib/users/classes/Login.php';

	//include Product class
	require $GLOBALS['settings']->Folders['root'].'../lib/products/classes/Product.php';

	//include project class
	require $GLOBALS['settings']->Folders['root'].'../lib/database/functions/validateInputs.php';

	//include OrderProduct
	require $GLOBALS['settings']->Folders['root'].'../lib/orders/classes/OrderProduct.php';

	//include Order
	require $GLOBALS['settings']->Folders['root'].'../lib/orders/classes/Order.php';

	//include function to get user orders
	require $GLOBALS['settings']->Folders['root'].'../lib/orders/functions/getOrdersForUser.php';

	session_start();

	//redirect if user is not logged in
	if(!isset($_SESSION["user"]) OR $_SESSION["user"]->__get("loggedIn") != TRUE OR $_SESSION["user"]->__get("permissionLevel") == 0 OR $_SESSION["user"]->__get("permissionLevel") == 5)
	{
		header("location: index.php");
	}

	if(isset($_SESSION["user"]) && $_SESSION["user"]->__get("loggedIn") && isset($_POST["sort"]) && isset($_POST["sequence"]) && !empty($_POST["sort"]) && !empty($_POST["sequence"]))
	{
		//validate inputs
		$sort = validateInput($_POST["sort"]);
		$sequence = validateInput($_POST["sequence"]);

		//security to avoid random inputs
		if($sequence == "ASC" OR $sequence == "DESC")
		{
			switch($sort)
			{
				CASE "Bestelnummer":
					//array of user orders
					$orders = getOrdersForUser($_SESSION["user"]->__get("userId"), "bestelnummer", $sequence);
					break;

				CASE "Status":
					//array of user orders
					$orders = getOrdersForUser($_SESSION["user"]->__get("userId"), "status", $sequence);
					break;

				CASE "Persoonlijk/Project":
					//array of user orders
					$orders = getOrdersForUser($_SESSION["user"]->__get("userId"), "persoonlijk", $sequence);
					break;
			}

			//print every order
			foreach ($orders as $order)
			{
				echo '<div class="panel panel-default">';

				$order->printOrder();

				echo '</div>';
			}
		}
	}
?>