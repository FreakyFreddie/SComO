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
		<div class="row">
			<ol class="breadcrumb">
				<li>
					<a href="adminpanel.php">
						<svg class="glyph stroked home">
							<use xlink:href="#stroked-home"></use>
						</svg>
					</a>
				</li>
				<li>
					<a href="adminprojecten.php">
						Projecten
					</a>
				</li>
				<li class="active">Projecten toewijzen</li>
			</ol>
		</div><!--/.row-->
		<div class="row">
			<div class="col-lg-12">
				<div class="panel panel-default" id="getprojects">
					<div class="panel-heading">Selecteer één of meer lopende projecten</div>
					<div class="panel-body">
						<table data-toggle="table" data-url="AJAX/adminDisplayActiveProjectsRequest.php"  data-show-refresh="true" data-show-toggle="true" data-show-columns="true" data-search="true" data-select-item-name="toolbar1" data-pagination="true" data-sort-name="name" data-sort-order="desc">
							<thead>
								<tr>
									<th data-field="selector" data-checkbox="true" >selector</th>
									<th data-field="id" data-sortable="true">id</th>
									<th data-field="titel"  data-sortable="true">titel</th>
									<th data-field="budget" data-sortable="true">budget</th>
									<th data-field="rekening" data-sortable="true">rekening</th>
									<th data-field="einddatum" data-sortable="true">einddatum</th>
								</tr>
							</thead>
						</table>
					</div>
				</div>
			</div>
		</div><!--/.row-->
		<div class="row">
			<div class="col-lg-12">
				<div class="panel panel-default getelements" id="getusers">
					<div class="panel-heading">Selecteer één of meer gebruikers</div>
					<div class="panel-body">
						<table data-toggle="table" data-url="AJAX/adminDisplayUsersRequest.php"  data-show-refresh="true" data-show-toggle="true" data-show-columns="true" data-search="true" data-select-item-name="toolbar1" data-pagination="true" data-sort-name="name" data-sort-order="desc">
							<thead>
								<tr>
									<th data-field="selector" data-checkbox="true" >selector</th>
									<th data-field="email" data-sortable="true">email</th>
									<th data-field="naam"  data-sortable="true">naam</th>
									<th data-field="voornaam" data-sortable="true">voornaam</th>
									<th data-field="niveau" data-sortable="true">niveau</th>
									<th data-field="aanmaakdatum" data-sortable="true">aanmaakdatum</th>
								</tr>
							</thead>
						</table>
					</div>
				</div>
			</div>
		</div><!--/.row-->
		<div class="row">
			<div class="col-md-6">
				<div class="panel panel-default" id="listprojects">
					<div class="panel-heading">Toe te wijzen projecten</div>
					<div class="panel-body">
						<table id="projectlist" data-url="AJAX/adminGetSelectedProjectsRequest.php" data-toggle="table" data-show-refresh="true" data-show-toggle="true" data-show-columns="true" data-search="true" data-select-item-name="toolbar1" data-pagination="true" data-sort-name="name" data-sort-order="desc">
							<thead>
								<tr>
									<th data-field="selector" data-checkbox="true" >selector</th>
									<th data-field="id" data-sortable="true">id</th>
									<th data-field="titel"  data-sortable="true">titel</th>
								</tr>
							</thead>
						</table>
					</div>
				</div>
			</div>
			<div class="col-md-6">
				<div class="panel panel-default" id="listusers">
					<div class="panel-heading">Deelnemers aan de projecten</div>
					<div class="panel-body">
						<table id="participantlist" data-url="AJAX/adminGetSelectedUsersRequest.php" data-toggle="table" data-show-refresh="true" data-show-toggle="true" data-show-columns="true" data-search="true" data-select-item-name="toolbar1" data-pagination="true" data-sort-name="name" data-sort-order="desc">
							<thead>
								<tr>
									<th data-field="selector" data-checkbox="true" >selector</th>
									<th data-field="email" data-sortable="true">email</th>
									<th data-field="naam"  data-sortable="true">naam</th>
									<th data-field="voornaam"  data-sortable="true">voornaam</th>
								</tr>
							</thead>
						</table>
					</div>
				</div>
			</div>
		</div>
		<div class="row">
			<div class="col-md-6">
				<input class="btn btn-primary" type="button" id="adduserstoproject" value="Toewijzen" />
			</div>
		</div><!--/.row-->
	</div><!--/.main-->

	<script src="js/Lumino/bootstrap-datepicker.js"></script>
	<script src="js/Lumino/bootstrap-table.js"></script>
	<script src="js/adminAddUsersToProject-buttons.js"></script>
	<script src="js/adminAddUsersToProject.js"></script>
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