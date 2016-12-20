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

	//include function to validate input
	require $GLOBALS['settings']->Folders['root'].'../lib/database/functions/validateInputs.php';

	session_start();

	//redirect if user is not logged in as admin
	if(!isset($_SESSION["user"]) OR $_SESSION["user"]->__get("loggedIn") != TRUE && $_SESSION["user"]->__get("permissionLevel") != 2)
	{
		header("location: ../index.php");
	}

	//check login condition and if the request contains all info
	if(isset($_SESSION["user"]) && $_SESSION["user"]->__get("loggedIn") && isset($_POST["rnummervoornaamachternaam"]) && isset($_POST["voornaam"]) && isset($_POST["achternaam"]) && isset($_POST["email"]) && isset($_POST["machtigingsniveau"])
		&& !empty($_POST["rnummervoornaamachternaam"]) && !empty($_POST["voornaam"]) && !empty($_POST["achternaam"]) && !empty($_POST["email"]) && !empty($_POST["machtigingsniveau"]))
	{
		//validate input
		$rnummervoornaamachternaam = validateInput($_POST["rnummervoornaamachternaam"]);
		$firstname = validateNaam($_POST["voornaam"]);
		$lastname = validateNaam($_POST["achternaam"]);
		$email = validateMail($_POST["email"]);
		$permissionlevel = validateInput($_POST["machtigingsniveau"]);

		//split in rnummer & trash
		$rnummervoornaamachternaam = explode(" - ", $rnummervoornaamachternaam);
		$userid = validateRNummer($rnummervoornaamachternaam[0]);

		//prevent adding non-allowed permission levels
		if($permissionlevel == "non-actief" OR $permissionlevel == "user" OR $permissionlevel == "admin" OR $permissionlevel == "banned")
		{
			//ready permissionlevel for database
			switch ($permissionlevel)
			{
				case "non-actief":
					$permissionlevel = "0";
					break;

				case "user":
					$permissionlevel = "1";
					break;

				case "admin":
					$permissionlevel = "2";
					break;

				case "banned":
					$permissionlevel = "5";
					break;
			}

			$dal = new DAL();

			//prevent sql injection
			$userid = mysqli_real_escape_string($dal->getConn(), $userid);
			$firstname = mysqli_real_escape_string($dal->getConn(), $firstname);
			$lastname = mysqli_real_escape_string($dal->getConn(), $lastname);
			$email = mysqli_real_escape_string($dal->getConn(), $email);
			$permissionlevel = mysqli_real_escape_string($dal->getConn(), $permissionlevel);

			$sql = "UPDATE gebruiker
					SET voornaam='" . $firstname . "', achternaam='" . $lastname . "', email='" . $email . "', machtigingsniveau='" . $permissionlevel . "'
					WHERE rnummer='" . $userid . "'";
			$dal->writeDB($sql);

			//close the connection
			$dal->closeConn();
		}
	}
?>