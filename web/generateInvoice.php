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


		//add PHPMailer mail functionality
		require $GLOBALS['settings']->Folders['root'].'../lib/FPDF/fpdf.php';

		class Invoice extends FPDF
		{
			private $orderId;
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

				$dal->closeConn();


			}

			// Page header
			function Header()
			{
				// Logo
				$this->Image('img/Emsys_logo_tekst_onder_rgb.jpg',10,6,30);
				// Arial bold 15
				$this->SetFont('Arial','B',15);
				// Move to the right
				$this->Cell(60);
				// Title
				$this->Cell(70,10,'Invoice for order #'.$this->orderId,1,0,'C');
				// Line break
				$this->Ln(20);
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
			function FancyTable($header, $data)
			{
				//extract data for product
				foreach($this->products as $product)
				{
					$product->idproduct;
					$product->leverancier;
					$product->aantal;
					$product->prijs;
					$product->verzamelnaam;
				}

				// Colors, line width and bold font
				$this->SetFillColor(255,0,0);
				$this->SetTextColor(255);
				$this->SetDrawColor(128,0,0);
				$this->SetLineWidth(.3);
				$this->SetFont('','B');
				// Header
				$w = array(40, 35, 40, 45);
				for($i=0;$i<count($header);$i++)
					$this->Cell($w[$i],7,$header[$i],1,0,'C',true);
				$this->Ln();
				// Color and font restoration
				$this->SetFillColor(224,235,255);
				$this->SetTextColor(0);
				$this->SetFont('');
				// Data
				$fill = false;
				foreach($data as $row)
				{
					$this->Cell($w[0],6,$row[0],'LR',0,'L',$fill);
					$this->Cell($w[1],6,$row[1],'LR',0,'L',$fill);
					$this->Cell($w[2],6,number_format($row[2]),'LR',0,'R',$fill);
					$this->Cell($w[3],6,number_format($row[3]),'LR',0,'R',$fill);
					$this->Ln();
					$fill = !$fill;
				}
				// Closing line
				$this->Cell(array_sum($w),0,'','T');
			}
		}

		$orderid = 1;
		// Instanciation of inherited class
		$pdf = new Invoice($orderid);
		$pdf->AliasNbPages();
		$pdf->AddPage();
		$pdf->SetFont('Times','',12);
		for($i=1;$i<=40;$i++)
			$pdf->Cell(0,10,'Printing line number '.$i,0,1);
		//$pdf->FancyTable($header,$data);
		$pdf->Output();
	?>