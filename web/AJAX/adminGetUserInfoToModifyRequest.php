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

	//include project class
	require $GLOBALS['settings']->Folders['root'].'../lib/database/functions/validateInputs.php';

	session_start();

	//redirect if user is not logged in as admin
	if(!isset($_SESSION["user"]) OR $_SESSION["user"]->__get("loggedIn") != TRUE OR $_SESSION["user"]->__get("permissionLevel") != 2)
	{
		header("location: ../index.php");
	}

	//check login condition and if the request contains all info
	if(isset($_SESSION["user"]) && $_SESSION["user"]->__get("loggedIn") && isset($_POST["rnummervoornaamachternaam"]) && !empty($_POST["rnummervoornaamachternaam"]))
	{
		//validate input
		$rnummervoornaamachternaam = validateInput($_POST["rnummervoornaamachternaam"]);

		//split in id & title
		$rnummervoornaamachternaam = explode(" - ", $rnummervoornaamachternaam);
		$userid = $rnummervoornaamachternaam[0];

		$dal = new DAL();

		//extract user info from DB
		//database input, counter injection
		$userid = mysqli_real_escape_string($dal->getConn(), $userid);

		//create array of parameters
		//first item = parameter types
		//i = integer
		//d = double
		//b = blob
		//s = string
		$parameters[0] = "s";
		$parameters[1] = $userid;

		//prepare statement
		$dal->setStatement("SELECT * FROM gebruiker WHERE rnummer =?");
		$records = $dal->queryDB($parameters);
		unset($parameters);

		$dal->closeConn();

		//pass JSON object if record is found
		if($dal->getNumResults() == 1)
		{
			switch($records[0]->machtigingsniveau)
			{
				case "0":
					$permissionlevel = "non-actief";
					break;

				case "1":
					$permissionlevel = "user";
					break;

				case "2":
					$permissionlevel = "admin";
					break;

				case "5":
					$permissionlevel = "banned";
					break;
			}

			echo json_encode(
				array(
					'voornaam' => $records[0]->voornaam,
					'achternaam' => $records[0]->achternaam,
					'email' => $records[0]->email,
					'machtigingsniveau' => $permissionlevel
				)
			);
		}
	}
?>