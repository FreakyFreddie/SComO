<?php
	//this page is created using the Lumino bootstrap dashboard template
	//The functionality is limited to the basics, but can be expanded if needed
	//template can be found here: http://medialoot.com/item/lumino-admin-bootstrap-template/

	//set page var in order to adapt navbar and functions
	$GLOBALS['page'] = "adminpanel";
	$GLOBALS['adminpage'] = "adminusers";

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
	<script src="js/adminAddNewUser.js"></script>
	<script src="js/adminSidePanelUser.js"></script>

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
										Rnummer
									</th>
									<td id="rnummer" class="text-right">

									</td>
								</tr>
								<tr>
									<th>
										Voornaam
									</th>
									<td id="voornaam" class="text-right">

									</td>
								</tr>
								<tr>
									<th>
										Naam
									</th>
									<td id="naam" class="text-right">

									</td>
								</tr>
								</tbody>
							</table>
							<table class="table table-hover">
								<tbody>
								<tr>
									<th>
										Email
									</th>
									<td id="email" class="text-right">

									</td>
								</tr>
								<tr>
									<th>
										Niveau
									</th>
									<td id="niveau" class="text-right">

									</td>
								</tr>
								<tr>
									<th>
										Aanmaakdatum
									</th>
									<td id="aanmaakdatum" class="text-right">

									</td>
								</tr>

								</tbody>
							</table>
						</div>
					</div>
				</div>
				<div class="col-lg-3">
					<div class="panel panel-default">
						<div class="panel-heading">Bestelde componenten</div>
						<div class="panel-body easypiechart-panel">
							<div class="easypiechart" id="easypiechart-teal" data-percent="0">
								<span class="percent">0</span>
							</div>
						</div>
					</div>
				</div>
			</div><!--/.row-->
			<div class="row">
				<div class="col-lg-6">
					<div class="panel panel-default removebutton2 addbutton1">
						<div class="panel-heading">Deelname in projecten</div>
						<div class="panel-body">
							<table id="displayprojectsparticipating" data-show-refresh="true" data-show-toggle="true" data-show-columns="true" data-search="true" data-select-item-name="toolbar1" data-pagination="true" data-sort-name="name" data-sort-order="desc">
								<thead>
								<tr>
									<th data-field="selector" data-checkbox="true" >selector</th>
									<th data-field="idproject" data-sortable="true">id</th>
									<th data-field="titel"  data-sortable="true">titel</th>
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
							<table id="displayuserorders" data-show-refresh="true" data-show-toggle="true" data-show-columns="true" data-search="true" data-select-item-name="toolbar1" data-pagination="true" data-sort-name="name" data-sort-order="desc">
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
		<div class="row"  id="row1">
			<div class="col-lg-12">
				<div class="panel panel-default removebutton1">
					<div class="panel-heading">Alle gebruikers</div>
					<div class="panel-body">
						<table data-toggle="table" data-url="AJAX/adminDisplayUsersRequest.php"  data-show-refresh="true" data-show-toggle="true" data-show-columns="true" data-search="true" data-select-item-name="toolbar1" data-pagination="true" data-sort-name="name" data-sort-order="desc">
							<thead>
								<tr>
									<th data-field="selector" data-checkbox="true" >selector</th>
									<th data-field="rnummer" data-sortable="true">rnummer</th>
									<th data-field="email" data-sortable="true">email</th>
									<th data-field="naam" data-sortable="true">naam</th>
									<th data-field="voornaam"  data-sortable="true">voornaam</th>
									<th data-field="niveau" data-sortable="true">niveau</th>
									<th data-field="aanmaakdatum" data-sortable="true">aanmaakdatum</th>
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
					<div class="panel-heading">Maak een nieuwe gebruiker aan</div>
					<div class="panel-body">
						<form role="form" action="#">
							<div class="row">
								<div class="col-md-6">
									<div class="form-group">
										<label for="rnummer">Rnummer</label>
										<input class="form-control" placeholder="rnummer" id="rnummer" name="rnummer" />
									</div>

									<div class="form-group">
										<label for="voornaam">Voornaam</label>
										<input class="form-control" placeholder="voornaam" id="voornaam" name="voornaam" />
									</div>

									<div class="form-group">
										<label for="wachtwoord">Wachtwoord</label>
										<input type="password" class="form-control" placeholder="wachtwoord" id="wachtwoord" name="wachtwoord" />
									</div>

									<div class="form-group">
										<label for="machtigingsniveau">Machtigingsniveau</label>
										<select class="form-control" id="machtigingsniveau" name="machtigingsniveau">
											<option value="non-actief">non-actief</option>
											<option value="user">user</option>
											<option value="admin">admin</option>
											<option value="banned">banned</option>
										</select>
									</div>
								</div>

								<div class="col-md-6">
									<div class="form-group">
										<label for="email">Email</label>
										<input class="form-control" placeholder="r0123456@student.thomasmore.be" id="email" name="email" />
									</div>

									<div class="form-group">
										<label for="achternaam">Achternaam</label>
										<input class="form-control" placeholder="achternaam" id="achternaam" name="achternaam" />
									</div>

									<div class="form-group">
										<label for="bevestigwachtwoord">Bevestig wachtwoord</label>
										<input type="password" class="form-control" placeholder="wachtwoord" id="wachtwoordconfirm" name="bevestigwachtwoord" />
									</div>
								</div>
							</div>
							<div class="row">
								<div class="col-md-6">
									<input type="button" class="btn btn-primary" id="createnewuser" value="Aanmaken" />
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
	<script src="js/adminAddTableButtons-users.js"></script>
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