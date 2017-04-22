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

	//redirect if user is not logged in as admin
	if(!isset($_SESSION["user"]) OR $_SESSION["user"]->__get("loggedIn") != TRUE OR $_SESSION["user"]->__get("permissionLevel") != 2)
	{
		header("location: ../index.php");
	}

	//check login condition and if the request contains all info
	if(isset($_SESSION["user"]) && $_SESSION["user"]->__get("loggedIn"))
	{
		//list all projects
		$dal = new DAL();
		$sql = "SELECT project.idproject as id, project.titel as titel, project.budget as budget, project.rekeningnr as rekening, project.startdatum as startdatum, project.vervaldatum as einddatum
			FROM project;";

		$records = $dal->queryDBNoArgs($sql);

		$dal->closeConn();

		//add buttons to change row or view details
		for($i = 0; $i < count($records); $i++)
		{
			$records[$i]->wijzig = '<button class="btn btn-default" type="button" name="wijzig" onclick="changeProject('.$records[$i]->id.')"><i class="fa fa-exchange fa-lg"></i></button>';
			$records[$i]->details = '<button class="btn btn-default" type="button" name="details" onclick="openNav()"><i class="fa fa-angle-double-right fa-lg"></i></button>';
		}

		//Lumino admin panel requires a JSON to process
		echo json_encode($records);
	}
?>