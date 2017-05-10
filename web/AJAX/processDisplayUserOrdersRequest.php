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

	session_start();

	//redirect if user is not logged in
	if(!isset($_SESSION["user"]) OR $_SESSION["user"]->__get("loggedIn") != TRUE OR $_SESSION["user"]->__get("permissionLevel") == 0 OR $_SESSION["user"]->__get("permissionLevel") == 5)
	{
		header("location: index.php");
	}

	//check login condition and if the request contains all info
	if(isset($_SESSION["user"]) && $_SESSION["user"]->__get("loggedIn"))
	{
		$dal = new DAL();

		//prevent SQL injection
		$userid= mysqli_real_escape_string($dal->getConn(), $_SESSION["user"]->__get("userId"));

		//create array of parameters
		//first item = parameter types
		//i = integer
		//d = double
		//b = blob
		//s = string
		$parameters[0] = "s";
		$parameters[1] = $userid;

		//prepare statement
		$dal->setStatement("SELECT bestelling.bestelnummer, bestelling.status, bestelling.besteldatum, bestelling.persoonlijk
			FROM bestelling
			WHERE bestelling.rnummer=?");

		$records = $dal->queryDB($parameters);
		unset($parameters);



		//add buttons to change row or view details
		for($i = 0; $i < count($records); $i++)
		{
			if((int) $records[$i]->persoonlijk == 1)
			{
				$records[$i]->project = "-";
			}
			elseif((int) $records[$i]->persoonlijk == 0)
			{
				//create array of parameters
				//first item = parameter types
				//i = integer
				//d = double
				//b = blob
				//s = string
				$parameters[0] = "i";
				$parameters[1] = (int) $records[$i]->bestelnummer;

				//prepare statement
				$dal->setStatement("SELECT project.titel
				FROM bestelling
				INNER JOIN project
				ON bestelling.idproject=project.idproject
				WHERE bestelling.bestelnummer=?");

				$projecttitel = $dal->queryDB($parameters);
				unset($parameters);

				if(isset($projecttitel[0]->titel) && !empty($projecttitel[0]->titel))
				{
					$records[$i]->project = $projecttitel[0]->titel;
				}
				else
				{
					$records[$i]->project = "-";
				}
			}
			$records[$i]->details = '<button class="btn btn-default" type="button" name="details" onclick="openNav('.$records[$i]->bestelnummer.",'".$records[$i]->status."','".$records[$i]->besteldatum."','".$records[$i]->project.'\')"><i class="fa fa-angle-double-right fa-lg"></i></button>';
		}

		$dal->closeConn();

		//Lumino admin panel requires a JSON to process
		echo json_encode($records);
	}
?>