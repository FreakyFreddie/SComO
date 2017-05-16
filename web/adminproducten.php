<?php
	//this page is created using the Lumino bootstrap dashboard template
	//The functionality is limited to the basics, but can be expanded if needed
	//template can be found here: http://medialoot.com/item/lumino-admin-bootstrap-template/

	//set page var in order to adapt navbar and functions
	$GLOBALS['page'] = "adminpanel";
	$GLOBALS['adminpage'] = "adminproducten";

	//include header
	require '../templates/header.php';

	//redirect if user is not logged in
	if(!isset($_SESSION["user"]) OR $_SESSION["user"]->__get("loggedIn") != TRUE OR $_SESSION["user"]->__get("permissionLevel") != 2)
	{
		header("location: index.php");
	}

	//include getOrdersTotal
	require $GLOBALS['settings']->Folders['root'].'../lib/orders/functions/getOrdersTotal.php';
?>
	<!-- AJAX to add new projects -->
	<script src="js/adminAddNewProduct.js"></script>

	</head>

	<body>
	<?php
		//include navbar
		require $GLOBALS['settings']->Folders['root'].'../templates/navbar.php';
	?>

	<noscript>
		<div class="alert alert-warning alert-dismissible">
			<a href="#" class="close" data-dismiss="alert" aria-label="close">&times;</a>
			<strong>Opgelet!</strong> Zonder javascript werkt de webwinkel mogelijk niet.
		</div>
	</noscript>

	<?php
		//include admin dashboard navbar
		require $GLOBALS['settings']->Folders['root'].'../templates/adminnavbar.php';
	?>

	<div class="col-sm-9 col-sm-offset-3 col-lg-10 col-lg-offset-2 main">
		<div class="row"  id="row1">
			<div class="col-lg-12">
				<div class="panel panel-default">
					<div class="panel-heading">Alle Producten</div>
					<div class="panel-body">
						<table data-toggle="table" data-url="AJAX/adminDisplayProductsRequest.php"  data-show-refresh="true" data-show-toggle="true" data-show-columns="true" data-search="true" data-select-item-name="toolbar1" data-pagination="true" data-sort-name="name" data-sort-order="desc">
							<thead>
								<tr>
									<th data-field="idproduct" data-sortable="true">id</th>
									<th data-field="leverancier" data-sortable="true">leverancier</th>
									<th data-field="productnaam" data-sortable="true">naam</th>
									<th data-field="productverkoper"  data-sortable="true">verkoper</th>
									<th data-field="eigenprijs"  data-sortable="true">prijs</th>
									<th data-field="wijzig">wijzig</th>
								</tr>
							</thead>
						</table>
					</div>
				</div>
			</div>
		</div><!--/.row-->
		<div class="row" id="row2">
			<div class="col-lg-12">
				<div class="panel panel-default">
					<div class="panel-heading">Maak een nieuw Product aan</div>
					<div class="panel-body">
						<form role="form" action="#">
							<div class="row">
								<div class="col-md-6">
									<div class="form-group">
										<label for="id">Id</label>
										<input class="form-control" placeholder="id" id="id" name="id" />
									</div>
								</div>
								<div class="col-md-6">
									<div class="form-group">
										<label for="naam">Naam</label>
										<input class="form-control" placeholder="naam" id="naam" name="naam" />
									</div>
								</div>
							</div>
							<div class="row">
								<div class="col-md-6">
									<div class="form-group">
										<label for="prijs">Prijs</label>
										<input class="form-control" placeholder="prijs" id="prijs" name="prijs" />
									</div>
								</div>
							</div>
							<div class="row">
								<div class="col-md-6">
									<input type="button" class="btn btn-primary" id="createnewproduct" value="Aanmaken" />
								</div>
							</div>
						</form>
					</div>
				</div>
			</div><!-- /.col-->
		</div><!-- /.row -->
	</div><!--/.main-->

	<script src="js/Lumino/bootstrap-datepicker.js"></script>
	<script src="js/Lumino/bootstrap-table.js"></script>
	<script src="js/Lumino/easypiechart.js"></script>
	<script src="js/Lumino/easypiechart-data.js"></script>
	<script src="js/adminAddTableButtons-products.js"></script>
	<script>
		!function ($) {
			$(document).on("click","ul.nav li.parent > a > span.icon", function(){
				$(this).find('em:first').toggleClass("glyphicon-minus");
			});
			$(".sidebar span.icon").find('em:first').addClass("glyphicon-plus");
		}(window.jQuery);

		$(window).on('resize', function () {
			if ($(window).width() > 768) $('#sidebar-collapse').collapse('show')
		})
		$(window).on('resize', function () {
			if ($(window).width() <= 767) $('#sidebar-collapse').collapse('hide')
		})
	</script>
	<!-- footer -->
<?php require $GLOBALS['settings']->Folders['root'].'../templates/footer.php'; ?>