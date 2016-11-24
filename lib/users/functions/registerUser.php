<?php
	function registerUser($firstname, $lastname, $rnummer, $email, $password)
	{
		//new Data Access Layer object
		$dal = new DAL();

		//validate input for html injection & check vs REGEX, counter mysql injection
		$firstname = mysqli_real_escape_string($dal->getConn(), validateNaam($firstname));
		$lastname = mysqli_real_escape_string($dal->getConn(), validateNaam($lastname));
		$rnummer = mysqli_real_escape_string($dal->getConn(), validateRNummer($rnummer));
		$mail = mysqli_real_escape_string($dal->getConn(), validateMail($email));
		$password = mysqli_real_escape_string($dal->getConn(), validateWachtWoord($password));
		$fullmail = $rnummer."@".$mail;

		//test if user already exists with rnummer
		$sql = "SELECT rnummer FROM gebruiker WHERE rnummer='".$rnummer."'";
		$dal->queryDB($sql);

		//if user already exists, numrows >= 1, if not we can continue
		if($dal->getNumResults()<1)
		{
			//hash password (use PASSWORD_DEFAULT since php might update algorithms if they become stronger)
			$password = password_hash($password,PASSWORD_DEFAULT);

			//prepare timestamp
			date_default_timezone_set('Europe/Brussels');

			do
			{
				//generate unique activation key
				$generatedkey = md5(uniqid(rand(), true));

				//test if activation link already exists with rnummer
				$sql = "SELECT rnummer FROM gebruiker WHERE activatiesleutel='".$generatedkey."'";
				$dal->queryDB($sql);
			}
			while($dal->getNumResults() != 0);

			//write to DB use date("j-n-Y H:i:s") for date
			$sql = "INSERT INTO gebruiker (rnummer, voornaam, achternaam, email, wachtwoord, machtigingsniveau, aanmaakdatum, activatiesleutel) VALUES ('".$rnummer."', '".$firstname."', '".$lastname."', '".$fullmail."', '".$password."', '0', '".date("Y-n-j H:i:s")."', '".$generatedkey."')";
			$dal->writeDB($sql);

			//"user created" message
			echo "Gebruiker aangemaakt";
			/*
			//generate mail headers & message
			$headers = "From: ".$GLOBALS["settings"]->Contact["webmaster"]."\r\nReply-To: ".$GLOBALS["settings"]->Contact["webmaster"];
			$mailmessage = "Klik op onderstaande link om je account te activeren\n".$GLOBALS["settings"]->Domain["domain"]."/activate.php?key=$generatedkey";
			
			//send mail with activation link ($to, $subject, $message), will not work on local server
			mail($fullmail, "Activeer uw ".$GLOBALS['settings']->Store['storeabbrev']." account", $mailmessage, $headers)
			OR die("Mailserver tijdelijk niet bereikbaar.");
	
			echo '<div class="row">
					<p>mail verzonden naar '.$fullmail.'</p>
				</div>';
			*/
		}
		else
		{
			echo "<p>Fout bij het aanmaken van de gebruiker. Probeer opnieuw.</p>";
		}

		$dal->closeConn();
	}
	
?>