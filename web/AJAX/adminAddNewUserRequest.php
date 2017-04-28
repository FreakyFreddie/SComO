<?php
	//this script processes the AJAX request & updates the user's shopping cart

	//load config, typecast to object for easy access
	$GLOBALS['settings'] = (object) parse_ini_file('../../config/config.ini', true);

	//include classes BEFORE session_start because we might need them in session
	//include DAL (DAL & login always go on top since classes depend on them)
	require $GLOBALS['settings']->Folders['root'].'../lib/database/classes/DAL.php';

	//include login class
	require $GLOBALS['settings']->Folders['root'].'../lib/users/classes/Login.php';

	//include ProductPrice class
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
	if(isset($_POST["rnummer"]) && isset($_POST["voornaam"]) && isset($_POST["achternaam"]) && isset($_POST["wachtwoord"]) && isset($_POST["wachtwoordconfirm"]) && isset($_POST["email"]) && isset($_POST["machtigingsniveau"])
		&& !empty($_POST["rnummer"]) && !empty($_POST["voornaam"]) && !empty($_POST["achternaam"]) && !empty($_POST["wachtwoord"]) && !empty($_POST["wachtwoordconfirm"]) && !empty($_POST["email"]) && !empty($_POST["machtigingsniveau"]))
	{
		if($_POST["wachtwoord"] == $_POST["wachtwoordconfirm"])
		{
			//validate inputs
			$userid = validateRNummer($_POST["rnummer"]);
			$firstname = validateNaam($_POST["voornaam"]);
			$lastname = validateNaam($_POST["achternaam"]);
			$password = validateWachtWoord($_POST["wachtwoord"]);
			$email = validateMail($_POST["email"]);
			$permissionlevel = validateInput($_POST["machtigingsniveau"]);

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
				$password = mysqli_real_escape_string($dal->getConn(), $password);
				$email = mysqli_real_escape_string($dal->getConn(), $email);
				$permissionlevel = mysqli_real_escape_string($dal->getConn(), $permissionlevel);

				//hash password (use PASSWORD_DEFAULT since php might update algorithms if they become stronger)
				$password = password_hash($password, PASSWORD_DEFAULT);

				//check if user already exists
				$dal = new DAL();

				//create array of parameters
				//first item = parameter types
				//i = integer
				//d = double
				//b = blob
				//s = string
				$parameters[0] = "s";
				$parameters[1] = $userid;

				//prepare statement
				$dal->setStatement("SELECT rnummer
					FROM gebruiker
					WHERE rnummer = ?");
				$records = $dal->queryDB($parameters);
				unset($parameters);

				//add user if not already present in DB
				if($dal->getNumResults() == 0)
				{
					//prepare timestamp
					date_default_timezone_set('Europe/Brussels');

					//create array of parameters
					//first item = parameter types
					//i = integer
					//d = double
					//b = blob
					//s = string
					$parameters[0] = "sssssis";
					$parameters[1] = $userid;
					$parameters[2] = $firstname;
					$parameters[3] = $lastname;
					$parameters[4] = $email;
					$parameters[5] = $password;
					$parameters[6] = $permissionlevel;
					$parameters[7] = date("Y-n-j");

					//prepare statement
					$dal->setStatement("INSERT INTO gebruiker (rnummer, voornaam, achternaam, email, wachtwoord, machtigingsniveau, aanmaakdatum) VALUES (?, ?, ?, ?, ?, ?, ?)");
					$dal->writeDB($parameters);
					unset($parameters);
				}

				//close the connection
				$dal->closeConn();
			}
		}
	}
?>