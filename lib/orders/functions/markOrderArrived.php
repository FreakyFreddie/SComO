<?php
	function markOrderArrived($finalorderid)
	{
		$dal = new DAL();

		//prevent sql injection
		$finalorderid = mysqli_real_escape_string($dal->getConn(), $finalorderid);

		//create array of parameters
		//first item = parameter types
		//i = integer
		//d = double
		//b = blob
		//s = string
		$parameters[0] = "s";
		$parameters[1] = $finalorderid;

		//prepare statement
		//get array of order numbers belonging to the final order number
		$dal->setStatement("SELECT definitiefbesteldebestellingen.bestelnummer, bestelling.rnummer
				FROM definitiefbesteldebestellingen
				INNER JOIN bestelling
				ON definitiefbesteldebestellingen.bestelnummer=bestelling.bestelnummer
				WHERE definitiefbesteldebestellingen.defbestelnummer=?");
		$records = $dal->queryDB($parameters);
		unset($parameters);

		foreach($records as $order)
		{
			//prevent sql injection
			$orderid = mysqli_real_escape_string($dal->getConn(), $order->bestelnummer);
			$userid = mysqli_real_escape_string($dal->getConn(), $order->rnummer);

			//create array of parameters
			//first item = parameter types
			//i = integer
			//d = double
			//b = blob
			//s = string
			$parameters[0] = "i";
			$parameters[1] = $orderid;

			$dal->setStatement("UPDATE bestelling
					SET status='4'
					WHERE bestelnummer=?");
			$dal->writeDB($parameters);
			unset($parameters);

			//create array of parameters
			//first item = parameter types
			//i = integer
			//d = double
			//b = blob
			//s = string
			$parameters[0] = "s";
			$parameters[1] = $userid;

			//get user email
			$dal->setStatement("SELECT email FROM gebruiker
 					WHERE rnummer=?");
			$records = $dal->queryDB($parameters);
			unset($parameters);

			$fullmail = $records[0]->email;

			//send mail to inform person
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

			$mail->Subject = "bestelling #" . $orderid . " is goedgekeurd.";
			$mail->Body = '<html><p>Uw bestelling is gedeeltelijk of volledig aangekomen.</p></html>';

			if (!$mail->send())
			{
				echo "<p>Fout bij het verzenden van de mail.</p>";
			}
			else
			{
				echo '<div class="row">
					<p>mail verzonden naar ' . $fullmail . '</p>
					</div>';
			}

			//close the connection
			$dal->closeConn();
		}

	}
?>