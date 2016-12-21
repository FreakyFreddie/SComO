<?php
	//this script processes the AJAX request & shows the users shopping cart

	//load config, typecast to object for easy access
	$GLOBALS['settings'] = (object) parse_ini_file('../../config/config.ini', true);

	//include classes BEFORE session_start because we might need them in session
	//include DAL (DAL & login always go on top since classes depend on them)
	require $GLOBALS['settings']->Folders['root'].'../lib/database/classes/DAL.php';

	//include login class
	require $GLOBALS['settings']->Folders['root'].'../lib/users/classes/Login.php';

	//include Product class
	require $GLOBALS['settings']->Folders['root'].'../lib/products/classes/Product.php';

	//include Shopping Cart & ShoppingCartArticle
	require $GLOBALS['settings']->Folders['root'].'../lib/shoppingcart/classes/ShoppingCart.php';
	require $GLOBALS['settings']->Folders['root'].'../lib/shoppingcart/classes/ShoppingCartArticle.php';

	//include function to validate input
	require $GLOBALS['settings']->Folders['root'].'../lib/database/functions/validateInputs.php';

	//add PHPMailer mail functionality
	require $GLOBALS['settings']->Folders['root'].'../lib/PHPMailer/PHPMailerAutoload.php';

	session_start();

	//redirect if user is not logged in as admin
	if(!isset($_SESSION["user"]) OR $_SESSION["user"]->__get("loggedIn") != TRUE OR $_SESSION["user"]->__get("permissionLevel") != 2)
	{
		header("location: ../index.php");
	}

	//check login condition and if the request contains all info
	if(isset($_SESSION["user"]) && $_SESSION["user"]->__get("loggedIn") && isset($_POST["supplier"]) && isset($_POST["finalordernumber"]) && !empty($_POST["supplier"])  && !empty($_POST["finalordernumber"]))
	{
		//validate input
		$finalordernumber = validateInput($_POST["finalordernumber"]);
		$supplier = validateInput($_POST["supplier"]);

		//prepare timestamp
		date_default_timezone_set('Europe/Brussels');

		$dal = new DAL();

		//add final order number to database
		$finalordernumber = mysqli_real_escape_string($dal->getConn(), $_POST["finalordernumber"]);
		$supplier = mysqli_real_escape_string($dal->getConn(), $_POST["supplier"]);
		$sql = "INSERT INTO definitiefbesteld (defbestelnummer, leverancier, defbesteldatum) VALUES ('" . $finalordernumber . "', '" . $supplier . "', '" . date("Y-n-j H:i:s") . "')";
		$dal->writeDB($sql);

		//prevent updating defbestelnummer of orders with both mouser & farnell products
		$sql = "SELECT * FROM bestellingdefbestelling";
		$records = $dal->queryDB($sql);

		//step one: make a list of ordernumbers+supplier with orderstatus 2 that already a defbestelnummer
		//we prevent overwriting this defbestelnummer
		$alreadyorderedquery = "SELECT bestelnummer FROM bestellingdefbestelling
				WHERE leverancier='".$supplier."'";

		$alreadyorderedlist = array();
		$oncelist = array();
		$twicelist = array();

		if($dal->getNumResults() > 0)
		{
			$counter = 0;
			$tempordernumber=$records[0]->bestelnummer;
			foreach($records as $row)
			{
				//if a bestenummer is mentioned once, add it to the oncelist
				//if a bestenummer is mentioned twice, add it to the twicelist
				if($tempordernumber != $row->bestelnummer)
				{
					if($counter == 1)
					{
						$oncelist[] = $tempordernumber;
						$counter = 0;
					}
					elseif($counter == 2)
					{
						$twicelist[] = $tempordernumber;
						$counter = 0;
					}
				}
				else
				{
					$tempordernumber = $row->defbestelnummer;
					$counter++;
				}
				//if a row has a defbestelnummer and the order is for the requested supplier,
				//add the ordernumber to the query & the list of orders with a defbestelnummer
				if(empty($row->defbestelnummer) && $row->leverancier == $supplier)
				{
					$alreadyorderedquery = $alreadyorderedquery." AND bestelnummer <>'.$row->bestelnummer.'";
					$alreadyorderedlist = $row->bestelnummer;
				}
			}
		}

		//select all order numbers to update
		$records = $dal->queryDB($alreadyorderedquery);

		if($dal->getNumResults() > 0)
		{
			foreach($records as $row)
			{

			}
		}


		//orders where both farnell and mouser are definitief besteld --> status 3

		//update the definitief bestelnummer column,
		//but exclude orders from this supplier that already have a final order number
		$sql = "UPDATE bestellingproduct
				INNER JOIN bestelling
				ON bestellingproduct.bestelnummer = bestelling.bestelnummer
				SET bestellingproduct.defbestelnummer = '".$finalordernumber."'	
				WHERE bestellingproduct.leverancier = '".$supplier."' AND bestelling.status = '2';";
		$dal->writeDB($sql);

		//update status of approved orders to "ordered" = 3,
		//but only if an order with both farnell and mouser products has a mouser & farnell final order number
		//step one: check each order of "supplier"
		$sql = 'SELECT bestellingproduct.bestelnummer
				FROM bestellingproduct
				INNER JOIN bestelling
				ON bestellingproduct.bestelnummer';

		$sql = "UPDATE bestelling
				INNER JOIN bestellingproduct
				ON bestellingproduct.bestelnummer = bestelling.bestelnummer
				SET bestelling.status = '3'
				WHERE bestellingproduct.leverancier = '".$supplier."' AND bestelling.status = '2';";
		$dal->writeDB($sql);

		if($supplier == "Mouser")
		{

		}

		$dal->closeConn();
	}

?>