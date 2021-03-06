<?php
	//this page is created using the Lumino bootstrap dashboard template
	//The functionality is limited to the basics, but can be expanded if needed
	//template can be found here: http://medialoot.com/item/lumino-admin-bootstrap-template/

	//set page var in order to adapt navbar and functions
	$GLOBALS['page'] = "adminpanel";
	$GLOBALS['adminpage'] = "adminprojecten";

	//include header
	require '../templates/header.php';

	//redirect if user is not logged in
	if(!isset($_SESSION["user"]) OR $_SESSION["user"]->__get("loggedIn") != TRUE OR $_SESSION["user"]->__get("permissionLevel") != 2)
	{
		header("location: index.php");
	}

	//include getOrdersTotal
	require $GLOBALS['settings']->Folders['root'].'/lib/orders/functions/getOrdersTotal.php';
?>

	<!-- AJAX to add new projects -->
	<script src="js/adminAddNewProject.js"></script>
	<script src="js/adminSidePanelProject.js"></script>
	</head>

	<body>
	<?php
		//include navbar
		require $GLOBALS['settings']->Folders['root'].'/templates/navbar.php';
	?>

	<noscript>
		<div class="alert alert-warning alert-dismissible">
			<a href="#" class="close" data-dismiss="alert" aria-label="close">&times;</a>
			<strong>Opgelet!</strong> Zonder javascript werkt de webwinkel mogelijk niet.
		</div>
	</noscript>

	<?php
		//include admin dashboard navbar
		require $GLOBALS['settings']->Folders['root'].'/templates/adminnavbar.php';
	?>

	<div id="sidepanel" class="sidepanel">
		<div class="main">
			<a href="javascript:void(0)" class="closebtn" onclick="closeNav()">&times;</a>
			<div class="row">
				<div class="col-lg-9">
					<div class="panel panel-default removebutton1" id="infopanel">
						<div class="panel-heading">Info </div>
						<div class="panel-body">
							<table class="table table-hover">
								<tbody>
								<tr>
									<th>
										Id
									</th>
									<td id="id" class="text-right">

									</td>
								</tr>
								<tr>
									<th>
										Titel
									</th>
									<td id="titel" class="text-right">

									</td>
								</tr>
								<tr>
									<th>
										Budget
									</th>
									<td id="budget" class="text-right">

									</td>
								</tr>
								</tbody>
							</table>
							<table class="table table-hover">
								<tbody>
								<tr>
									<th>
										Startdatum
									</th>
									<td id="startdatum" class="text-right">

									</td>
								</tr>
								<tr>
									<th>
										Einddatum
									</th>
									<td id="einddatum" class="text-right">

									</td>
								</tr>
								<tr>
									<th>
										Rekening
									</th>
									<td id="rekening" class="text-right">

									</td>
								</tr>

								</tbody>
							</table>
						</div>
					</div>
				</div>
				<div class="col-lg-3">
					<div class="panel panel-default">
						<div class="panel-heading">Budget</div>
						<div class="panel-body easypiechart-panel">
							<div class="easypiechart" id="easypiechart-teal" data-percent="0">
								<span class="percent">0%</span>
							</div>
						</div>
					</div>
				</div>
			</div><!--/.row-->
			<div class="row">
				<div class="col-lg-6">
					<div class="panel panel-default removebutton2 addbutton1">
						<div class="panel-heading">Deelnemers</div>
						<div class="panel-body">
							<table id="displayprojectparticipants" data-show-refresh="true" data-show-toggle="true" data-show-columns="true" data-search="true" data-select-item-name="toolbar1" data-pagination="true" data-sort-name="name" data-sort-order="desc">
								<thead>
								<tr>
									<th data-field="selector" data-checkbox="true" >selector</th>
									<th data-field="rnummer" data-sortable="true">rnummer</th>
									<th data-field="naam"  data-sortable="true">naam</th>
									<th data-field="voornaam" data-sortable="true">voornaam</th>
									<th data-field="beheerder" data-sortable="true">beheerder</th>
								</tr>
								</thead>
							</table>
						</div>
					</div>
				</div>
				<div class="col-lg-6">
					<div class="panel panel-default">
						<div class="panel-heading">Bestellingen</div>
						<div class="panel-body">
							<table id="displayprojectorders" data-show-refresh="true" data-show-toggle="true" data-show-columns="true" data-search="true" data-select-item-name="toolbar1" data-pagination="true" data-sort-name="name" data-sort-order="desc">
								<thead>
								<tr>
									<th data-field="bestelnummer" data-sortable="true">order</th>
									<th data-field="productid" data-sortable="true">productid</th>
									<th data-field="aantal" data-sortable="true">aantal</th>
									<th data-field="prijs" data-sortable="true">prijs</th>
								</tr>
								</thead>
							</table>
						</div>
					</div>
				</div>
			</div><!--/.row-->
		</div>
	</div>

	<div class="col-sm-9 col-sm-offset-3 col-lg-10 col-lg-offset-2 main">
		<div class="row" id="row1">
			<div class="col-lg-12">
				<div class="panel panel-default removebutton1">
					<div class="panel-heading">Alle projecten</div>
					<div class="panel-body">
						<table data-toggle="table" data-url="AJAX/adminDisplayProjectsRequest.php"  data-show-refresh="true" data-show-toggle="true" data-show-columns="true" data-search="true" data-select-item-name="toolbar1" data-pagination="true" data-sort-name="name" data-sort-order="desc">
							<thead>
								<tr>
									<th data-field="selector" data-checkbox="true" >selector</th>
									<th data-field="id" data-sortable="true">id</th>
									<th data-field="titel"  data-sortable="true">titel</th>
									<th data-field="budget" data-sortable="true">budget</th>
									<th data-field="rekening" data-sortable="true">rekening</th>
									<th data-field="startdatum" data-sortable="true">startdatum</th>
									<th data-field="einddatum" data-sortable="true">einddatum</th>
									<th data-field="wijzig">wijzig</th>
									<th data-field="details">details</th>
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
					<div class="panel-heading">Maak een nieuw project aan</div>
					<div class="panel-body">
						<form role="form" action="#">
							<div class="row">
								<div class="col-md-6">
									<div class="form-group">
										<label for="titel">Titel project</label>
										<input class="form-control" placeholder="Titel" id="projecttitle" name="titel">
									</div>

									<div class="form-group">
										<label for="budget">Budget</label>
										<input class="form-control" placeholder="Budget" id="projectfunds" name="budget">
									</div>

									<div class="form-group">
										<label for="rekeningnummer">Rekeningnummer</label>
										<input class="form-control" placeholder="Rekeningnummer" id="projectaccount" name="rekeningnummer">
									</div>
								</div>
								<div class="col-md-6">
									<div class="form-group">
										<label for="startdatum">Startdatum</label>
										<input class="form-control" placeholder="2016-12-31" id="projectstartdate" name="startdatum">
									</div>

									<div class="form-group">
										<label for="vervaldatum">Vervaldatum</label>
										<input class="form-control" placeholder="2017-12-31" id="projectenddate" name="vervaldatum">
									</div>
								</div>
							</div>
							<div class="row">
								<div class="col-md-6">
									<input type="button" class="btn btn-primary" id="createnewproject" value="Aanmaken" />
								</div>
							</div>
						</form>
					</div>
				</div>
			</div><!-- /.col-->
		</div><!-- /.row -->
		<!-- Use any element to open the sidenav -->
	</div><!--/.main-->

	<script src="js/Lumino/bootstrap-datepicker.js"></script>
	<script src="js/Lumino/bootstrap-table.js"></script>
	<script src="js/Lumino/easypiechart.js"></script>
	<script src="js/Lumino/easypiechart-data.js"></script>
	<script src="js/adminAddTableButtons-projects.js"></script>
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
<?php require $GLOBALS['settings']->Folders['root'].'/templates/footer.php'; ?>