<?php
	//set page var in order to adapt navbar and functions
	$GLOBALS['page'] = "bestellingen";

	//include header
	require '../templates/header.php';

	//redirect if user is not logged in
	if(!isset($_SESSION["user"]) OR $_SESSION["user"]->__get("loggedIn") != TRUE OR $_SESSION["user"]->__get("permissionLevel") == 0 OR $_SESSION["user"]->__get("permissionLevel") == 5)
	{
		header("location: index.php");
	}

	//include OrderProduct
	require $GLOBALS['settings']->Folders['root'].'../lib/orders/classes/OrderProduct.php';

	//include Order
	require $GLOBALS['settings']->Folders['root'].'../lib/orders/classes/Order.php';

	//include function to get user orders
	require $GLOBALS['settings']->Folders['root'].'../lib/orders/functions/getOrdersForUser.php';

?>
	<!-- AJAX to sort orders -->
	<script src="js/sortOrders.js"></script>

	</head>

	<body>
	<?php
		//include navbar
		require '../templates/navbar.php';
	?>

	<!-- PROJECT TITLE and QUOTE -->
	<div class="jumbotron text-center">
		<h1>
			<?php
				echo $_SESSION["user"]->__get("firstName")." ".$_SESSION["user"]->__get("lastName");
			?>
		</h1>
		<p>
			Bestelgeschiedenis.
		</p>
	</div>
	<div class="container">
		<div class="panel panel-default">
			<div class="panel-heading">
				<strong>
					Sorteer bestellingen op:
				</strong>
			</div>
			<div class="panel-body">
				<form>
					<div class="form-group">
						<div class="col-lg-12">
							<label for="sorteeropbestelnummer" class="sr-only"></label>
							<input type="button" class="btn btn-default order-by" value="Bestelnummer" name="sorteeropbestelnummer" data-sequence="DESC" />
							/
							<label for="sorteeropstatus" class="sr-only"></label>
							<input type="button" class="btn btn-default order-by" value="Status" name="sorteeropstatus" data-sequence="ASC" />
							/
							<label for="sorteeroppersoonlijk" class="sr-only"></label>
							<input type="button" class="btn btn-default order-by" value="Persoonlijk/Project" name="sorteeroppersoonlijk" data-sequence="ASC" />

						</div>
					</div>
				</form>
			</div>
		</div>
	</div>

	<div class="container workspace">
		<noscript>
			<div class="alert alert-warning alert-dismissible">
				<a href="#" class="close" data-dismiss="alert" aria-label="close">&times;</a>
				<strong>Opgelet!</strong> Zonder javascript werkt de webwinkel mogelijk niet.
			</div>
		</noscript>

		<?php
			//array of user orders
			$orders = getOrdersForUser($_SESSION["user"]->__get("userId"), "bestelnummer", "ASC");
			foreach($orders as $order)
			{
				echo '<div class="panel panel-default">';

				$order->printOrder();

				echo '</div>';
			}
		?>
	</div>

	<!-- footer -->
	<?php require $GLOBALS['settings']->Folders['root'].'../templates/footer.php'; ?>