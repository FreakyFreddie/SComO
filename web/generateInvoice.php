<?php
	//this page is created using the Lumino bootstrap dashboard template
	//The functionality is limited to the basics, but can be expanded if needed
	//template can be found here: http://medialoot.com/item/lumino-admin-bootstrap-template/
	//load config, typecast to object for easy access
	$GLOBALS['settings'] = (object) parse_ini_file('../config/config.ini', true);
	//set page var in order to adapt navbar and functions
	$GLOBALS['page'] = "adminpanel";
	$GLOBALS['adminpage'] = "adminpanel";

	//include DAL (DAL & login always go on top since classes depend on them)
	require $GLOBALS['settings']->Folders['root'].'../lib/database/classes/DAL.php';
	require $GLOBALS['settings']->Folders['root'].'../lib/barcodegenerator/src/BarcodeGenerator.php';
	require $GLOBALS['settings']->Folders['root'].'../lib/barcodegenerator/src/BarcodeGeneratorJPG.php';

	//add PHPMailer mail functionality
	require $GLOBALS['settings']->Folders['root'].'../lib/PHPMailer/PHPMailerAutoload.php';

	session_start();

		//add PHPMailer mail functionality
		require $GLOBALS['settings']->Folders['root'].'../lib/FPDF/fpdf.php';

		class Invoice extends FPDF
		{
			private $orderId;
			private $userInfo;
			private $products = array();

			public function __construct($orderid)
			{
				parent::__construct();

				$this->orderId = $orderid;

				$dal = new DAL();

				//prevent SQL injection
				$this->orderId = mysqli_real_escape_string($dal->getConn(), $this->orderId);

				//create array of parameters
				//first item = parameter types
				//i = integer
				//d = double
				//b = blob
				//s = string
				$parameters[0] = "i";
				$parameters[1] = (int)$this->orderId;

				//prepare statement
				//extract products in order
				$dal->setStatement("SELECT *
				FROM bestellingproduct
 				WHERE bestelnummer=?");

				$this->products = $dal->queryDB($parameters);
				unset($parameters);

				//create array of parameters
				//first item = parameter types
				//i = integer
				//d = double
				//b = blob
				//s = string
				$parameters[0] = "i";
				$parameters[1] = (int)$this->orderId;

				//prepare statement
				//extract products in order
				$dal->setStatement("SELECT gebruiker.rnummer, voornaam, achternaam, email
				FROM bestelling
				INNER JOIN gebruiker
				ON bestelling.rnummer = gebruiker.rnummer
 				WHERE bestelnummer=?");

				$this->userInfo = $dal->queryDB($parameters);
				unset($parameters);

				$dal->closeConn();
			}

			// Page header
			function Header()
			{
				// Logo
				$this->Image('img/Emsys_logo_tekst_onder_rgb.jpg',10,6,35);

				// Arial bold 15
				$this->SetFont('Arial','B',15);
				// Move to the right
				$this->Cell(60);
				// Title
				$this->Cell(70,10,'Factuur order '.$this->orderId,1,0,'C');

				// Move to the right
				$this->Cell(10);

				//generate barcode
				$generator = new \Picqer\Barcode\BarcodeGeneratorJPG();

				$barcode_file = "barcode".$this->orderId.".jpg";

				$codevalue = (string) $this->orderId;
				$barcode = '';

				for($i=0; $i < 12 - strlen($codevalue); $i++)
				{
					$barcode .= '0';
				}

				$barcode .= $codevalue;

				$myfile = fopen($barcode_file, "w");
				fwrite($myfile, $generator->getBarcode($barcode, $generator::TYPE_CODE_128));
				fclose($myfile);

				// Barcode
				$this->Image($barcode_file,160,6,40,20);

				unlink($barcode_file);

				// Line break
				$this->Ln(25);

				$this->Cell(50,10,'Rnummer: ');
				$this->Cell(50,10,$this->userInfo[0]->rnummer);

				// Line break
				$this->Ln(7);

				$this->Cell(50,10,'Naam: ');
				$this->Cell(50,10,$this->userInfo[0]->voornaam.' '.$this->userInfo[0]->achternaam);

				// Line break
				$this->Ln(15);
			}

			// Page footer
			function Footer()
			{
				// Position at 1.5 cm from bottom
				$this->SetY(-15);
				// Arial italic 8
				$this->SetFont('Arial','I',8);
				// Page number
				$this->Cell(0,10,'Page '.$this->PageNo().'/{nb}',0,0,'C');
			}

			// Colored table
			function ProductTable()
			{
				// Colors, line width and bold font
				$this->SetFillColor(255,0,0);
				$this->SetTextColor(255);
				$this->SetDrawColor(128,0,0);
				$this->SetLineWidth(.3);
				$this->SetFont('','B');
				// Header
				$this->Cell(80,7,'ID',1,0,'C',true);
				$this->Cell(30,7,'Aantal',1,0,'C',true);
				$this->Cell(30,7,'Prijs per stuk',1,0,'C',true);
				$this->Cell(30,7,'Totaalprijs',1,0,'C',true);
				$this->Ln();
				// Color and font restoration
				$this->SetFillColor(224,235,255);
				$this->SetTextColor(0);
				$this->SetFont('');
				// Data
				$fill = false;

				$total = 0;

				foreach($this->products as $product)
				{
					$this->Cell(80,6,$product->idproduct,'LR',0,'L',$fill);
					$this->Cell(30,6,$product->aantal,'LR',0,'L',$fill);
					$this->Cell(30,6,$product->prijs,'LR',0,'R',$fill);
					$this->Cell(30,6,round((float)$product->prijs * (int)$product->aantal, 2),'LR',0,'R',$fill);
					$this->Ln();
					$fill = !$fill;

					$total = $total + (round((float)$product->prijs * (int)$product->aantal, 2));
				}

				$this->SetFont('','B');

				$this->Cell(140,6,'Totaal','L',0,'R',$fill);
				$this->Cell(30,6,$total,'R',0,'R',$fill);
				$this->Ln();
				// Closing line
				$this->Cell(170,0,'','T');
			}
		}

		$orderid = 1;
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

		$fullmail = 'r0303063@student.thomasmore.be';

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
		$mail->AddAttachment($pdf_filename);

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

	?>