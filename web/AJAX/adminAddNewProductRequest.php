<?php
	//this script processes the AJAX request & updates the user's shopping cart

	//load config, typecast to object for easy access
	$GLOBALS['settings'] = (object) parse_ini_file('../../config/config.ini', true);

	//include classes BEFORE session_start because we might need them in session
	//include DAL (DAL & login always go on top since classes depend on them)
	require $GLOBALS['settings']->Folders['root'].'/lib/database/classes/DAL.php';

	//include login class
	require $GLOBALS['settings']->Folders['root'].'/lib/users/classes/Login.php';

	//include ProductPrice class
	require $GLOBALS['settings']->Folders['root'].'/lib/project/classes/Project.php';

	//include project class
	require $GLOBALS['settings']->Folders['root'].'/lib/database/functions/validateInputs.php';

	session_start();

	//redirect if user is not logged in as admin
	if(!isset($_SESSION["user"]) OR $_SESSION["user"]->__get("loggedIn") != TRUE OR $_SESSION["user"]->__get("permissionLevel") != 2)
	{
		header("location: ../index.php");
	}

	//check login condition and if the request contains all info
	if(isset($_POST["id"]) && isset($_POST["leverancier"]) && isset($_POST["naam"]) && isset($_POST["prijs"]) && !empty($_POST["id"]) && !empty($_POST["leverancier"]) && !empty($_POST["naam"]) && !empty($_POST["prijs"]))
	{
		//validate inputs
		$supplier= validateInput($_POST["leverancier"]);
		$name = validateInput($_POST["naam"]);

		$dal = new DAL();

		//prevent sql injection
		$id = mysqli_real_escape_string($dal->getConn(), $_POST["id"]);
		$supplier = mysqli_real_escape_string($dal->getConn(), $supplier);
		$name = mysqli_real_escape_string($dal->getConn(), $name);
		$price = (float) mysqli_real_escape_string($dal->getConn(), $_POST["prijs"]);

		$parameters[0] = "ss";
		$parameters[1] = $id;
		$parameters[2] = $supplier;

		//create array of parameters
		//first item = parameter types
		//i = integer
		//d = double
		//b = blob
		//s = string
		//prepare statement
		$dal->setStatement("SELECT idproduct, leverancier
			FROM product
			WHERE idproduct = ? AND leverancier = ?");
		$records = $dal->queryDB($parameters);
		unset($parameters);

		//add product if not already present in DB
		if($dal->getNumResults() == 0)
		{
			//create array of parameters
			//first item = parameter types
			//i = integer
			//d = double
			//b = blob
			//s = string
			$parameters[0] = "ssssd";
			$parameters[1] = $id;
			$parameters[2] = $supplier;
			$parameters[3] = $name;
			$parameters[4] = $supplier;
			$parameters[5] = $price;

			//prepare statement
			$dal->setStatement("INSERT INTO product (idproduct, leverancier, productnaam, productverkoper, eigenprijs) VALUES (?, ?, ?, ?, ?)");
			$dal->writeDB($parameters);
			unset($parameters);
		}

		//close the connection
		$dal->closeConn();
	}
?>