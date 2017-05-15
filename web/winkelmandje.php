<?php
	//set page var in order to adapt navbar and functions
	$GLOBALS['page'] = "winkelmandje";

	//include header
	require '../templates/header.php';

	//redirect if user is not logged in or account is not activated yet
	if(!isset($_SESSION["user"]) OR $_SESSION["user"]->__get("loggedIn") != TRUE OR $_SESSION["user"]->__get("permissionLevel") == 0 OR $_SESSION["user"]->__get("permissionLevel") == 5)
	{
		header("location: index.php");
	}

	//include Shopping Cart & ShoppingCartArticle
	require $GLOBALS['settings']->Folders['root'].'/lib/shoppingcart/classes/ShoppingCart.php';
	require $GLOBALS['settings']->Folders['root'].'/lib/shoppingcart/classes/ShoppingCartArticle.php';
?>

	<script src="js/placeOrder.js"></script>
	<script src="js/showShoppingCart.js"></script>
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
				echo $_SESSION["user"]->__get("firstName")." ".$_SESSION["user"]->__get("lastName")
			?>
		</h1>
		<p>
			Winkelmandje.
		</p>
	</div>
	<div id="shoppingcart" class="container main workspace">
		<noscript>
			<div class="alert alert-warning alert-dismissible">
				<a href="#" class="close" data-dismiss="alert" aria-label="close">&times;</a>
				<strong>Opgelet!</strong> Zonder javascript werkt de webwinkel mogelijk niet.
			</div>
		</noscript>
		<div class="row">
			<div class="col-lg-12">
				<div class="panel panel-default">
					<div class="panel-body">
						<?php
							echo '<div class="row">
									<div class="col-sm-4">
									<p>Deze bestelling is: </p>
									<input checked="true" class="orderpersoonlijk" type="radio" name="orderpersoonlijk" value="persoonlijk" /> Persoonlijk
									<input class="orderpersoonlijk" type="radio" name="orderpersoonlijk" value="project" /> Voor project
								</div>
								<div class="col-sm-4" id="projectlist">
									<p>Indien voor project, selecteer welk: </p>
									<select  class="form-control" id="selectproject" name="selectproject">';
										$shoppingcart = new ShoppingCart($_SESSION["user"]->__get("userId"));

										$projects = $shoppingcart->getProjectsForUser();

										foreach($projects as $project)
										{
											echo '<option value="' . $project->idproject . ' - ' . $project->titel . '">' . $project->idproject . ' - ' . $project->titel . '</option>';
										}
							echo '</select>
								</div>
								<div class="col-sm-4 text-right">
									<button type="button" id="orderproducts" class="btn btn-primary" value="orderproducts" name="orderproducts">Bestelling plaatsen</button>
								</div>
							</div>';
						?>
						<table id="displayshoppingcart" data-show-refresh="true" data-show-toggle="true" data-show-columns="true" data-search="true" data-select-item-name="toolbar1" data-pagination="true" data-sort-name="name" data-sort-order="desc">
							<thead>
							<tr>
								<th data-field="idproduct" data-sortable="true">id</th>
								<th data-field="productnaam" data-sortable="true">naam</th>
								<th data-field="leverancier" data-sortable="true">leverancier</th>
								<th data-field="productverkoper"  data-sortable="true">verkoper</th>
								<th data-field="datasheet"  data-sortable="true">datasheet</th>
								<th data-field="afbeelding"  data-sortable="true">afbeelding</th>
								<th data-field="prijs"  data-sortable="true">prijs</th>
								<th data-field="aantal"  data-sortable="true">aantal</th>
								<th data-field="delete">delete</th>
							</tr>
							</thead>
						</table>
					</div>
				</div>
			</div>
		</div><!--/.row-->
	</div>
	<script src="js/Lumino/bootstrap-table.js"></script>
	<!-- footer -->
	<?php require $GLOBALS['settings']->Folders['root'].'/templates/footer.php'; ?>

