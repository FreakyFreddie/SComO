<?php
	//this page is created using the Lumino bootstrap dashboard template
	//The functionality is limited to the basics, but can be expanded if needed
	//template can be found here: http://medialoot.com/item/lumino-admin-bootstrap-template/
	//load config, typecast to object for easy access
	$GLOBALS['settings'] = (object) parse_ini_file('../config/config.ini', true);
	//set page var in order to adapt navbar and functions
	$GLOBALS['page'] = "adminpanel";
	$GLOBALS['adminpage'] = "adminpanel";


		//add PHPMailer mail functionality
		require $GLOBALS['settings']->Folders['root'].'../lib/FPDF/fpdf.php';

		class PDF extends FPDF
		{
			// Page header
			function Header()
			{
				// Logo
				$this->Image('logo.png',10,6,30);
				// Arial bold 15
				$this->SetFont('Arial','B',15);
				// Move to the right
				$this->Cell(80);
				// Title
				$this->Cell(30,10,'Title',1,0,'C');
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
		}

		// Instanciation of inherited class
		$pdf = new PDF();
		$pdf->AliasNbPages();
		$pdf->AddPage();
		$pdf->SetFont('Times','',12);
		for($i=1;$i<=40;$i++)
			$pdf->Cell(0,10,'Printing line number '.$i,0,1);
		$pdf->Output();
	?>