<?php
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
			INNER JOIN product
			ON bestellingproduct.idproduct=product.idproduct
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
			$this->Image('../../web/img/Emsys_logo_tekst_onder_rgb.jpg',10,6,35);

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
			$this->Cell(50,10,'ID',1,0,'C',true);
			$this->Cell(60,10,'Naam',1,0,'C',true);
			$this->Cell(20,10,'Aantal',1,0,'C',true);
			$this->Cell(30,10,'Prijs per stuk',1,0,'C',true);
			$this->Cell(30,10,'Totaalprijs',1,0,'C',true);
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
				$this->Cell(50,10,$product->idproduct,'LR',0,'L',$fill);
				$this->Cell(60,10,substr($product->productnaam, 0, 25),'LR',0,'L',$fill);
				$this->Cell(20,10,$product->aantal,'LR',0,'R',$fill);
				$this->Cell(30,10,$product->prijs,'LR',0,'R',$fill);
				$this->Cell(30,10,round((float)$product->prijs * (int)$product->aantal, 2),'LR',0,'R',$fill);
				$this->Ln();
				$fill = !$fill;

				$total = $total + (round((float)$product->prijs * (int)$product->aantal, 2));
			}

			$this->SetFont('','B');

			$this->Cell(160,6,'Totaal','L',0,'R',$fill);
			$this->Cell(30,6,$total,'R',0,'R',$fill);
			$this->Ln();
			// Closing line
			$this->Cell(190,0,'','T');
		}
	}
?>