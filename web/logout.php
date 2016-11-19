<?php
	session_start();
	
	//log out
	session_unset();
	session_destroy();
	
	//set loggedout global to TRUE
	$GLOBALS["loggedout"]=TRUE;
	
	//redirect to index.php
	header("Location:index.php");
?>