<?php
	session_start();

	//log out
	session_unset();
	session_destroy();

	//redirect to index.php
	header("Location:index.php");
?>