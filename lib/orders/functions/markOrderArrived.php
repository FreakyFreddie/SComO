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
		$dal->setStatement("SELECT bestellingproduct.bestelnummer
				FROM definitiefbesteld
				INNER JOIN bestellingproduct
				ON definitiefbesteld.defbestelnummer=bestellingproduct.defbestelnummer
				WHERE bestellingproduct.defbestelnummer=? AND definitiefbesteld.status='0'
				GROUP BY bestellingproduct.bestelnummer");
		$records = $dal->queryDB($parameters);
		unset($parameters);

		//update definitiefbesteld status & date of arrival
		//create array of parameters
		//first item = parameter types
		//i = integer
		//d = double
		//b = blob
		//s = string
		$parameters[0] = "ss";
		$parameters[1] = date("Y-n-j H:i:s");
		$parameters[2] = $finalorderid;

		$dal->setStatement("UPDATE definitiefbesteld
					SET defaankomstdatum=?, status='1'
					WHERE defbestelnummer=?");
		$dal->writeDB($parameters);
		unset($parameters);

		foreach($records as $order)
		{
			//prevent sql injection
			$orderid = mysqli_real_escape_string($dal->getConn(), $order->bestelnummer);

			//get email to sent invoice to
			//create array of parameters
			//first item = parameter types
			//i = integer
			//d = double
			//b = blob
			//s = string
			$parameters[0] = "i";
			$parameters[1] = $orderid;

			$dal->setStatement("SELECT bestelling.bestelnummer, bestelling.rnummer, gebruiker.email
				FROM bestelling
				INNER JOIN gebruiker
				ON bestelling.rnummer = gebruiker.rnummer
				WHERE bestelling.bestelnummer=?");
			$userorders = $dal->queryDB($parameters);
			unset($parameters);

			//update each order to arrived
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

			$fullmail = $userorders[0]->email;

			// Instanciation of inherited class
			$pdf = new Invoice($orderid);
			$pdf->AliasNbPages();
			$pdf->AddPage();
			$pdf->SetFont('Times','',12);
			//for($i=1;$i<=20;$i++)
			//$pdf->Cell(0,10,'Printing line number '.$i,0,1);
			//$pdf->FancyTable($header,$data);
			$pdf->ProductTable();

			$pdf_filename = "factuur".$orderid.".pdf";

			if(!file_exists($pdf_filename) || is_writable($pdf_filename)){
				$pdf->Output($pdf_filename, "F");
			} else {
				exit("Path Not Writable");
			}

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

			$mail->Subject = "bestelling #" . $orderid . " is toegekomen.";
			$mail->Body = '<html><p>Uw bestelling is aangekomen. Deze kan afgehaald worden aan het secretariaat.</p><p>Gelieve een afgedrukte versie van de factuur en uw studentenkaart mee te brengen als afhaalbewijs.</p></html>';
			$mail->addAttachment($pdf_filename);

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

			unlink($pdf_filename);

			//close the connection
			$dal->closeConn();
		}
	}
?>