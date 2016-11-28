<?php
	//set page var in order to adapt navbar and functions
	$GLOBALS['page'] = "bestellingen";

	//include header
	require '../templates/header.php';

	//redirect if user is not logged in
	if(!isset($_SESSION["user"]) OR $_SESSION["user"]->__get("loggedIn") != TRUE)
	{
		header("location: index.php");
	}

	//include OrderProduct
	require $GLOBALS['settings']->Folders['root'].'../lib/orders/classes/OrderProduct.php';

	//include Order
	require $GLOBALS['settings']->Folders['root'].'../lib/orders/classes/Order.php';

	//include ShoppingCartArticle
	require $GLOBALS['settings']->Folders['root'].'../lib/orders/functions/getOrdersForUser.php';
?>
	</head>

	<body>
	<?php
		//include navbar
		require '../templates/navbar.php';
	?>

	<!-- PROJECT TITLE and QUOTE -->
	<div class="jumbotron text-center">
		<h1>
			Bestellingen
		</h1>
		<p>
			Jouw bestelgeschiedenis
		</p>
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
			$orders = getOrdersForUser($_SESSION["user"]->__get("userId"));
			foreach($orders as $order)
			{
				$order->printOrder();
			}
		?>
	</div>

	<!-- footer -->
	<?php require $GLOBALS['settings']->Folders['root'].'../templates/footer.php'; ?>