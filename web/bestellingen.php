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
	require $GLOBALS['settings']->Folders['root'].'/lib/orders/classes/OrderProduct.php';

	//include Order
	require $GLOBALS['settings']->Folders['root'].'/lib/orders/classes/Order.php';

	//include function to get user orders
	require $GLOBALS['settings']->Folders['root'].'/lib/orders/functions/getOrdersForUser.php';

?>
	<!-- AJAX to sort orders -->
	<script src="js/showSidePanelOrder.js"></script>

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

	<div id="sidepanel" class="sidepanel">
		<div class="main">
			<a href="javascript:void(0)" class="closebtn" onclick="closeNav()">&times;</a>
			<div class="row">
				<div class="col-lg-9">
					<div class="panel panel-default removebutton1" id="infopanel">
						<div class="panel-heading">Info </div>
						<div class="panel-body">
							<div class="row">
								<table class="table table-hover">
									<tbody>
									<tr>
										<th>
											bestelnummer
										</th>
										<td id="bestelnummer" class="text-right">

										</td>
									</tr>
									<tr>
										<th>
											Status
										</th>
										<td id="status" class="text-right">

										</td>
									</tr>
									</tbody>
								</table>
								<table class="table table-hover">
									<tbody>
									<tr>
										<th>
											Project
										</th>
										<td id="project" class="text-right">

										</td>
									</tr>
									<tr>
										<th>
											Besteldatum
										</th>
										<td id="besteldatum" class="text-right">

										</td>
									</tr>
									</tbody>
								</table>
							</div>
							<div class="row">
								<div class="col-lg-12">
									<p id="message">
									</p>
								</div>
							</div>
						</div>
					</div>
				</div>
				<div class="col-lg-3">
					<div class="panel panel-default">
						<div class="panel-heading">Totale kost</div>
						<div class="panel-body easypiechart-panel">
							<div class="easypiechart" id="easypiechart-orange" data-percent="0">
								<span class="percent">€0</span>
							</div>
						</div>
					</div>
				</div>
			</div><!--/.row-->
			<div class="row">
				<div class="col-lg-12">
					<div class="panel panel-default">
						<div class="panel-heading">Producten in bestelling</div>
						<div class="panel-body">
							<table id="displayuserorderproducts" data-show-refresh="true" data-show-toggle="true" data-show-columns="true" data-search="true" data-select-item-name="toolbar1" data-pagination="true" data-sort-name="name" data-sort-order="desc">
								<thead>
								<tr>
									<th data-field="productafbeelding" data-sortable="true">afbeelding</th>
									<th data-field="idproduct" data-sortable="true">id</th>
									<th data-field="productnaam" data-sortable="true">naam</th>
									<th data-field="leverancier" data-sortable="true">leverancier</th>
									<th data-field="productverkoper"  data-sortable="true">verkoper</th>
									<th data-field="productdatasheet" data-sortable="true">datasheet</th>
									<th data-field="prijs" data-sortable="true">prijs</th>
									<th data-field="aantal" data-sortable="true">aantal</th>
								</tr>
								</thead>
							</table>
						</div>
					</div>
				</div>
			</div><!--/.row-->
		</div>
	</div>

	<div class="container main workspace">
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
						<table id="displayuserorders"  data-toggle="table" data-url="AJAX/processDisplayUserOrdersRequest.php"  data-show-refresh="true" data-show-toggle="true" data-show-columns="true" data-search="true" data-select-item-name="toolbar1" data-pagination="true" data-sort-name="name" data-sort-order="desc">
							<thead>
							<tr>
								<th data-field="bestelnummer" data-sortable="true">bestelnummer</th>
								<th data-field="besteldatum" data-sortable="true">datum</th>
								<th data-field="project" data-sortable="true">project</th>
								<th data-field="status"  data-sortable="true">status</th>
								<th data-field="details">details</th>
							</tr>
							</thead>
						</table>
					</div>
				</div>
			</div>
		</div><!--/.row-->
	</div>
	<script src="js/Lumino/bootstrap-datepicker.js"></script>
	<script src="js/Lumino/bootstrap-table.js"></script>
	<script src="js/Lumino/easypiechart.js"></script>
	<script src="js/Lumino/easypiechart-data.js"></script>
	<!-- footer -->
	<?php require $GLOBALS['settings']->Folders['root'].'/templates/footer.php'; ?>