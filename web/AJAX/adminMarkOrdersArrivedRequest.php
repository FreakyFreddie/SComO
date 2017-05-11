<?php
	//this script processes the AJAX request

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
	require $GLOBALS['settings']->Folders['root'].'../lib/project/classes/Project.php';

	//include order class
	require $GLOBALS['settings']->Folders['root'].'../lib/orders/classes/Order.php';

	//add PHPMailer mail functionality
	require $GLOBALS['settings']->Folders['root'].'../lib/PHPMailer/PHPMailerAutoload.php';

	//include function to remove projects
	require $GLOBALS['settings']->Folders['root'].'../lib/orders/functions/markOrderArrived.php';

	//include project class
	require $GLOBALS['settings']->Folders['root'].'../lib/database/functions/validateInputs.php';

	//add PHPMailer mail functionality
	require $GLOBALS['settings']->Folders['root'].'../lib/FPDF/fpdf.php';

	//include DAL (DAL & login always go on top since classes depend on them)
	require $GLOBALS['settings']->Folders['root'].'../lib/barcodegenerator/src/BarcodeGenerator.php';
	require $GLOBALS['settings']->Folders['root'].'../lib/barcodegenerator/src/BarcodeGeneratorJPG.php';

	require $GLOBALS['settings']->Folders['root'].'../lib/orders/classes/generateInvoice.php';

	session_start();

	//redirect if user is not logged in as admin
	if(!isset($_SESSION["user"]) OR $_SESSION["user"]->__get("loggedIn") != TRUE OR $_SESSION["user"]->__get("permissionLevel") != 2)
	{
		header("location: ../index.php");
	}

	//check login condition and if the request contains all info
	if(isset($_SESSION["user"]) && $_SESSION["user"]->__get("loggedIn") && isset($_POST["array"]) && !empty($_POST["array"]))
	{
		foreach($_POST["array"] as $order)
		{
			//remove the project
			markOrderArrived($order["id"]);
		}
	}
?>