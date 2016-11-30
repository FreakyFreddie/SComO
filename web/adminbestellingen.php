<?php
	//this page is created using the Lumino bootstrap dashboard template
	//The functionality is limited to the basics, but can be expanded if needed
	//template can be found here: http://medialoot.com/item/lumino-admin-bootstrap-template/

	//set page var in order to adapt navbar and functions
	$GLOBALS['page'] = "adminpanel";
	$GLOBALS['adminpage'] = "adminbestellingen";

	//include header
	require '../templates/header.php';

	//redirect if user is not logged in
	if(!isset($_SESSION["user"]) OR $_SESSION["user"]->__get("loggedIn") != TRUE)
	{
		header("location: index.php");
	}

	//include getOrdersTotal
	require $GLOBALS['settings']->Folders['root'].'../lib/orders/functions/getOrdersTotal.php';
?>

		<link href="css/Lumino/datepicker3.css" rel="stylesheet">
		<link href="css/Lumino/bootstrap-table.css" rel="stylesheet">
		<link href="css/Lumino/styles.css" rel="stylesheet">

		<!--Icons-->
		<script src="js/Lumino/lumino.glyphs.js"></script>

		<!--[if lt IE 9]>
		<script src="js/Lumino/html5shiv.min.js"></script>
		<script src="js/Lumino/respond.min.js"></script>
		<![endif]-->

	</head>

	<body>
	<?php
		//include navbar
		require '../templates/navbar.php';
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
		<div class="row">
			<ol class="breadcrumb">
				<li>
					<a href="adminpanel.php">
						<svg class="glyph stroked home">
							<use xlink:href="#stroked-home"></use>
						</svg>
					</a>
				</li>
				<li class="active">Bestellingen</li>
			</ol>
		</div><!--/.row-->

		<div class="row">
			<div class="col-lg-12">
				<div class="panel panel-default">
					<div class="panel-heading">Alle bestellingen</div>
					<div class="panel-body">
						<table data-toggle="table" data-url="AJAX/adminNewProjectOrdersRequest.php"  data-show-refresh="true" data-show-toggle="true" data-show-columns="true" data-search="true" data-select-item-name="toolbar1" data-pagination="true" data-sort-name="name" data-sort-order="desc">
							<thead>
							<tr>
								<!--<th data-field="state" data-checkbox="true" >Item ID</th>-->
								<th data-field="bestelnr" data-sortable="true">bestelnr</th>
								<th data-field="datum"  data-sortable="true">datum</th>
								<th data-field="projectid" data-sortable="true">project</th>
								<th data-field="rnummer" data-sortable="true">rnummer</th>
								<th data-field="productid" data-sortable="true">productid</th>
								<th data-field="leverancier" data-sortable="true">leverancier</th>
								<th data-field="aantal" data-sortable="true">aantal</th>
								<th data-field="prijs" data-sortable="true">prijs</th>
							</tr>
							</thead>
						</table>
					</div>
				</div>
			</div>
		</div><!--/.row-->
		<div class="row">
			<div class="col-md-6">
				<div class="panel panel-default">
					<div class="panel-heading">Basic Table</div>
					<div class="panel-body">
						<table data-toggle="table" data-url="tables/data2.json" >
							<thead>
							<tr>
								<th data-field="id" data-align="right">Item ID</th>
								<th data-field="name">Item Name</th>
								<th data-field="price">Item Price</th>
							</tr>
							</thead>
						</table>
					</div>
				</div>
			</div>
			<div class="col-md-6">
				<div class="panel panel-default">
					<div class="panel-heading">Styled Table</div>
					<div class="panel-body">
						<table data-toggle="table" id="table-style" data-url="tables/data2.json" data-row-style="rowStyle">
							<thead>
							<tr>
								<th data-field="id" data-align="right" >Item ID</th>
								<th data-field="name" >Item Name</th>
								<th data-field="price" >Item Price</th>
							</tr>
							</thead>
						</table>
						<script>
							$(function () {
								$('#hover, #striped, #condensed').click(function () {
									var classes = 'table';

									if ($('#hover').prop('checked')) {
										classes += ' table-hover';
									}
									if ($('#condensed').prop('checked')) {
										classes += ' table-condensed';
									}
									$('#table-style').bootstrapTable('destroy')
										.bootstrapTable({
											classes: classes,
											striped: $('#striped').prop('checked')
										});
								});
							});

							function rowStyle(row, index) {
								var classes = ['active', 'success', 'info', 'warning', 'danger'];

								if (index % 2 === 0 && index / 2 < classes.length) {
									return {
										classes: classes[index / 2]
									};
								}
								return {};
							}
						</script>
					</div>
				</div>
			</div>
		</div><!--/.row-->


	</div><!--/.main-->

	<script src="js/Lumino/chart.min.js"></script>
	<script src="js/Lumino/chart-data.js"></script>
	<script src="js/Lumino/easypiechart.js"></script>
	<script src="js/Lumino/easypiechart-data.js"></script>
	<script src="js/Lumino/bootstrap-datepicker.js"></script>
	<script src="js/Lumino/bootstrap-table.js"></script>
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

