<?php
	//this script processes the AJAX request & updates the user's shopping cart

	//load config, typecast to object for easy access
	$GLOBALS['settings'] = (object) parse_ini_file('../../config/config.ini', true);

	//include classes BEFORE session_start because we might need them in session
	//include DAL (DAL & login always go on top since classes depend on them)
	require $GLOBALS['settings']->Folders['root'].'../lib/database/classes/DAL.php';

	//include login class
	require $GLOBALS['settings']->Folders['root'].'../lib/users/classes/Login.php';

	//include ProductPrice class
	require $GLOBALS['settings']->Folders['root'].'../lib/products/classes/ProductPrice.php';

	//include Product class
	require $GLOBALS['settings']->Folders['root'].'../lib/products/classes/Product.php';

	//include MouserProduct
	require $GLOBALS['settings']->Folders['root'].'../lib/products/classes/MouserProduct.php';

	//include FarnellProduct
	require $GLOBALS['settings']->Folders['root'].'../lib/products/classes/FarnellProduct.php';

	//globally used functions go here
	require $GLOBALS['settings']->Folders['root'].'../lib/products/functions/getfarnellproducts.php';
	require $GLOBALS['settings']->Folders['root'].'../lib/products/functions/getmouserproducts.php';

	//logfunction
	require $GLOBALS['settings']->Folders['root'].'../lib/users/functions/logActivity.php';

	//input checks
	require $GLOBALS['settings']->Folders['root'].'../lib/database/functions/validateInputs.php';

	session_start();

	//redirect if user is not logged in
	if(!isset($_SESSION["user"]) OR $_SESSION["user"]->__get("loggedIn") != TRUE OR $_SESSION["user"]->__get("permissionLevel") == 0 OR $_SESSION["user"]->__get("permissionLevel") == 5)
	{
		header("location: ../index.php");
	}

	if(isset($_SESSION["user"]) && $_SESSION["user"]->__get("loggedIn") && isset($_GET["searchproduct"]))
	{
		//send request to Farnell API
		$farnellproducts = getFarnellProducts(validateInput($_GET['searchproduct']), 0, 20);

		//send request to Mouser API
		$mouserproducts = getMouserProducts(validateInput($_GET['searchproduct']), 0, 20);

		$products = array();

		$i=0;

		foreach($farnellproducts as $farnellproduct)
		{
			$prices = '<table class="table-striped table-hover">
							<tr>
								<th>
									Quantity
								</th>
								<th class="text-right">
									Price
								</th>
							</tr>';
			foreach($farnellproduct->__get("Prices") as $productPrice)
			{
				$prices .= '<tr>
					<td>> '.$productPrice->__get("Quantity").'</td>
					<td class="text-right">'.$productPrice->__get("Price").'</td>
				</tr>';
			}

			$prices .= '</table>';

			$add = '<form class="input-group" action="#">
						<label for="amountproduct" class="sr-only">Producthoeveelheid</label>
						<input type="number" class="form-control" value="1" id="amountproduct" name="amountproduct" data-productid="'.$farnellproduct->__get("Id").'" data-supplier="'.$farnellproduct->__get("Supplier").'" />
						<span class="input-group-btn">
							<button class="btn btn-secondary productbutton" type="button">
								<span class="glyphicon glyphicon-plus"></span>
							</button>
						</span>
					</form>';

			$products[$i]["name"] = $farnellproduct->__get("Name");
			$products[$i]["image"] = '<img class="img-responsive" src='.$farnellproduct->__get("Image").' alt="'.$farnellproduct->__get("Name").'" />';
			$products[$i]["id"] = $farnellproduct->__get("Id");
			$products[$i]["vendor"] = $farnellproduct->__get("Vendor");
			$products[$i]["supplier"] = $farnellproduct->__get("Supplier");
			$products[$i]["inventory"] = $farnellproduct->__get("Inventory");
			$products[$i]["datasheet"] = '<a href="'.$farnellproduct->__get("DataSheet").'" target="_blank">Link</a>';
			$products[$i]["prices"] = $prices;
			$products[$i]["add"] = $add;
			$i++;
		}

		foreach($mouserproducts as $mouserproduct)
		{
			$prices = '<table class="table-striped table-hover">
							<tr>
								<th>
									Quantity
								</th>
								<th class="text-right">
									Price
								</th>
							</tr>';
			foreach($mouserproduct->__get("Prices") as $productPrice)
			{
				$prices .= '<tr>
					<td>> '.$productPrice->__get("Quantity").'</td>
					<td class="text-right">'.$productPrice->__get("Price").'</td>
				</tr>';
			}

			$prices .= '</table>';

			$add = '<form class="input-group" action="#">
						<label for="amountproduct" class="sr-only">Producthoeveelheid</label>
						<input type="number" class="form-control" value="1" id="amountproduct" name="amountproduct" data-productid="'.$mouserproduct->__get("Id").'" data-supplier="'.$mouserproduct->__get("Supplier").'" />
						<span class="input-group-btn">
							<button class="btn btn-secondary productbutton" type="button">
								<span class="glyphicon glyphicon-plus"></span>
							</button>
						</span>
					</form>';

			$products[$i]["name"] = $mouserproduct->__get("Name");
			$products[$i]["image"] = '<img class="img-responsive" src='.$mouserproduct->__get("Image").' alt="'.$mouserproduct->__get("Name").'" />';
			$products[$i]["id"] = $mouserproduct->__get("Id");
			$products[$i]["vendor"] = $mouserproduct->__get("Vendor");
			$products[$i]["supplier"] = $mouserproduct->__get("Supplier");
			$products[$i]["inventory"] = $mouserproduct->__get("Inventory");
			$products[$i]["datasheet"] = '<a href="'.$mouserproduct->__get("DataSheet").'" target="_blank">Link</a>';
			$products[$i]["prices"] = $prices;
			$products[$i]["add"] = $add;
			$i++;
		}

		var_dump($mouserproducts);
		var_dump($farnellproducts);

		//Lumino admin panel requires a JSON to process
		echo json_encode($products);
	}
?>