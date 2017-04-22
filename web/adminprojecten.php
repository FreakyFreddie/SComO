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

	<!-- AJAX to add new projects -->
	<script src="js/adminAddNewProject.js"></script>
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
	</div><!--/.main-->

	<script src="js/Lumino/bootstrap-datepicker.js"></script>
	<script src="js/Lumino/bootstrap-table.js"></script>
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
<?php require $GLOBALS['settings']->Folders['root'].'../templates/footer.php'; ?>