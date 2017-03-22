<?php
	function registerUser($firstname, $lastname, $rnummer, $email, $password)
	{
		//new Data Access Layer object
		$dal = new DAL();

		//validate input for html injection & check vs REGEX, counter mysql injection
		$firstname = mysqli_real_escape_string($dal->getConn(), validateNaam($firstname));
		$lastname = mysqli_real_escape_string($dal->getConn(), validateNaam($lastname));
		$rnummer = mysqli_real_escape_string($dal->getConn(), validateRNummer($rnummer));

		if(validateDomain($email) == TRUE)
		{
			$mail = mysqli_real_escape_string($dal->getConn(), $email);
		}

		$password = mysqli_real_escape_string($dal->getConn(), validateWachtWoord($password));
		$fullmail = $rnummer."@".$mail;

		//test if user already exists with rnummer
		$sql = "SELECT rnummer FROM gebruiker WHERE rnummer='".$rnummer."'";
		$dal->queryDB($sql);

		//if user already exists, numrows >= 1, if not we can continue
		if($dal->getNumResults()<1) {
			//hash password (use PASSWORD_DEFAULT since php might update algorithms if they become stronger)
			$password = password_hash($password, PASSWORD_DEFAULT);

			//prepare timestamp
			date_default_timezone_set('Europe/Brussels');

			do {
				//generate unique activation key
				$generatedkey = md5(uniqid(rand(), TRUE));

				//test if activation link already exists with rnummer
				$sql = "SELECT rnummer FROM gebruiker WHERE activatiesleutel='" . $generatedkey . "'";
				$dal->queryDB($sql);
			} while ($dal->getNumResults() != 0);

			$mail = new PHPMailer;

			//$mail->SMTPDebug = 3;

			//PHPMailer settings
			$mail->isSMTP();
			$mail->Host = $GLOBALS['settings']->SMTP['host'];
			$mail->SMTPAuth = TRUE;
			$mail->Username = $GLOBALS['settings']->SMTP['username'];
			$mail->Password = $GLOBALS['settings']->SMTP['password'];
			$mail->SMTPSecure = $GLOBALS['settings']->SMTP['connection'];
			$mail->Port = $GLOBALS['settings']->SMTP['port'];

			$mail->From = $GLOBALS['settings']->SMTP['username'];
			$mail->FromName = $GLOBALS['settings']->SMTP['name'];
			$mail->addAddress($fullmail);

			$mail->isHTML(TRUE);

			$mail->Subject = "Activeer uw " . $GLOBALS['settings']->Store['storeabbrev'] . " account";
			$mail->Body = '<html><p>Klik op onderstaande link om je account te activeren</p><br /><a href="https://' . $GLOBALS["settings"]->Domain["domain"] . '/activate.php?key='.$generatedkey.'">https://'.$GLOBALS["settings"]->Domain["domain"] . '/activate.php?key='.$generatedkey.'</a></html>';

			if(!$mail->send())
			{
				echo '<p>Gebruiker kon niet worden aangemaakt</p>';

				//Debug
				//echo 'Mailer Error: ' . $mail->ErrorInfo;
			}
			else
			{
				//write to DB use date("j-n-Y H:i:s") for date
				$sql = "INSERT INTO gebruiker (rnummer, voornaam, achternaam, email, wachtwoord, machtigingsniveau, aanmaakdatum, activatiesleutel) VALUES ('" . $rnummer . "', '" . $firstname . "', '" . $lastname . "', '" . $fullmail . "', '" . $password . "', '0', '" . date("Y-n-j H:i:s") . "', '" . $generatedkey . "')";
				$dal->writeDB($sql);

				echo '<p>Gebruiker aangemaakt.</p>
				<p>Er is een activatiecode verzonden naar '.$fullmail.'.</p>';
			}
		}

		$dal->closeConn();
	}
?>