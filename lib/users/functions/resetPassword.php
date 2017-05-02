<?php
	function resetPassword($rnummer, $email)
	{
		//new Data Access Layer object
		$dal = new DAL();

		if(validateDomain($email) == TRUE && validateRNummer($rnummer) != FALSE)
		{
			//validate input for html injection & check vs REGEX, counter mysql injection
			$rnummer = mysqli_real_escape_string($dal->getConn(), validateRNummer($rnummer));
			$mail = mysqli_real_escape_string($dal->getConn(), $email);

			$fullmail = $rnummer."@".$mail;

			//create array of parameters
			//first item = parameter types
			//i = integer
			//d = double
			//b = blob
			//s = string
			$parameters[0] = "s";
			$parameters[1] = $rnummer;

			//prepare statement
			//test if user exists with rnummer
			$dal->setStatement("SELECT rnummer FROM gebruiker WHERE rnummer=?");
			$dal->queryDB($parameters);
			unset($parameters);

			//if user already exists, numrows >= 1, if not we can continue
			if(!($dal->getNumResults()<1))
			{
				$generatedpassword = randomPassword();

				//hash password (use PASSWORD_DEFAULT since php might update algorithms if they become stronger)
				$password = password_hash($generatedpassword, PASSWORD_DEFAULT);

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

				$mail->Subject = "Nieuw " . $GLOBALS['settings']->Store['storeabbrev'] . " wachtwoord";
				$mail->Body = '<html><p>Uw nieuwe wachtwoord is: </p><p>'.$generatedpassword.'</p></html>';

				if(!$mail->send())
				{
					//Debug
					//echo 'Mailer Error: ' . $mail->ErrorInfo;
				}
				else
				{
					//create array of parameters
					//first item = parameter types
					//i = integer
					//d = double
					//b = blob
					//s = string
					$parameters[0] = "ss";
					$parameters[1] = $password;
					$parameters[2] = $rnummer;

					//prepare statement
					//write to DB using date("j-n-Y H:i:s") for date
					$dal->setStatement("UPDATE gebruiker SET wachtwoord = ? WHERE rnummer = ?");
					$dal->writeDB($parameters);
					unset($parameters);

				}
				echo '<p>Nieuw wachtwoord verzonden naar '.$fullmail.'.</p>';
			}
		}
		$dal->closeConn();
	}

	function randomPassword()
	{
		$alphabet = 'abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ1234567890';
		$pass = array(); //remember to declare $pass as an array
		$alphaLength = strlen($alphabet) - 1; //put the length -1 in cache
		for ($i = 0; $i < 12; $i++) {
			$n = rand(0, $alphaLength);
			$passwd[] = $alphabet[$n];
		}
		$pass = implode($passwd);

		return $pass; //turn the array into a string
	}
?>