<?php
	//this script processes the AJAX request & updates the user's shopping cart

	//load config, typecast to object for easy access
	$GLOBALS['settings'] = (object) parse_ini_file('../../config/config.ini', true);

	//include classes BEFORE session_start because we might need them in session
	//include DAL (DAL & login always go on top since classes depend on them)
	require $GLOBALS['settings']->Folders['root'].'/lib/database/classes/DAL.php';

	//include login class
	require $GLOBALS['settings']->Folders['root'].'/lib/users/classes/Login.php';

	//include ProductPrice class
	require $GLOBALS['settings']->Folders['root'].'/lib/products/classes/ProductPrice.php';

	//include Product class
	require $GLOBALS['settings']->Folders['root'].'/lib/products/classes/Product.php';

	//logfunction
	require $GLOBALS['settings']->Folders['root'].'/lib/users/functions/logActivity.php';

	//input checks
	require $GLOBALS['settings']->Folders['root'].'/lib/database/functions/validateInputs.php';

	session_start();

	//redirect if user is not logged in
	if(!isset($_SESSION["user"]) OR $_SESSION["user"]->__get("loggedIn") != TRUE OR $_SESSION["user"]->__get("permissionLevel") == 0 OR $_SESSION["user"]->__get("permissionLevel") == 5)
	{
		header("location: ../index.php");
	}

	if(isset($_SESSION["user"]) && $_SESSION["user"]->__get("loggedIn") && isset($_GET["searchproduct"]))
	{
		//validate inputs
		$q = validateNaam($_GET["searchproduct"]);

		$dal = new DAL();

		//extract user info from DB
		//database input, counter injection
		$q = mysqli_real_escape_string($dal->getConn(), $q);

		//create array of parameters
		//first item = parameter types
		//i = integer
		//d = double
		//b = blob
		//s = string
		$parameters[0] = "ssss";
		$parameters[1] = "%".$q."%";
		$parameters[2] = "EMSYS";
		$parameters[3] = "%".$q."%";
		$parameters[4] = "EMSYS";

		//prepare statement
		$dal->setStatement("SELECT idproduct AS id, productnaam AS name, leverancier AS supplier, productverkoper AS vendor, eigenprijs AS price FROM product WHERE idproduct LIKE ? AND leverancier = ?
			UNION
			SELECT idproduct AS id, productnaam AS name, leverancier AS supplier, productverkoper AS vendor, eigenprijs AS price FROM product WHERE productnaam LIKE ? AND leverancier = ?
		");

		$records = $dal->queryDB($parameters);
		unset($parameters);

		//close the connection
		$dal->closeConn();

		//add product if not already present in DB
		if($dal->getNumResults() > 0)
		{
			$i = 0;

			foreach($records as $record)
			{
				$add = '<form class="input-group" action="#">
						<label for="amountproduct" class="sr-only">Producthoeveelheid</label>
						<input type="number" class="form-control" value="1" id="amountproduct" name="amountproduct" data-productid="'.$records[$i]->id.'" data-supplier="'.$records[$i]->supplier.'" />
						<span class="input-group-btn">
							<button class="btn btn-secondary productbutton" type="button">
								<span class="glyphicon glyphicon-plus"></span>
							</button>
						</span>
					</form>';

				$products[$i]["id"] = $record->id;
				$products[$i]["name"] = $record->name;
				$products[$i]["supplier"] = $record->supplier;
				$products[$i]["vendor"] = $record->vendor;
				$products[$i]["price"] = round($record->price, 2);
				$products[$i]["add"] = $add;
			}

			echo json_encode($products);
		}
	}
?>