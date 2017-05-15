<?php
	function logActivity()
	{
		//prepare timestamp
		date_default_timezone_set('Europe/Brussels');

		//getting information about user
		$ipaddress = $_SERVER['REMOTE_ADDR'];
		$page = "http://".$_SERVER['HTTP_HOST'].$_SERVER['PHP_SELF'];
		$datetime = date("Y-n-j H:i:s");
		$useragent = $_SERVER['HTTP_USER_AGENT'];
		$remotehost = @gethostbyaddr($ipaddress);
		$userid ="";

		//add userid if logged in
		if(isset($_SESSION["user"]) && $_SESSION["user"]->__get("loggedIn") == TRUE)
		{
			$userid = $_SESSION["user"]->__get("userId");
		}

		//create line to write in log
		$logline = $ipaddress . "|" . $datetime . "|" . $useragent . "|" . $remotehost . "|" . $page . "|" . $userid . "\n";

		//select file to write log to
		$logfile =  $GLOBALS['settings']->Folders['root'].'/logs/visitors.txt';

		//open log in append mode
		$file = fopen($logfile, 'a+')
		OR die("Fout bij openen logfile");

		//append the logline to the file
		fwrite($file, $logline)
		OR die("Fout bij schrijven naar logfile");

		//close file
		fclose($file);
	}

?>