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

	<!-- AJAX to update users -->
	<script src="js/adminModifyUser.js"></script>

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
					<a href="adminusers.php">
						Gebruikers
					</a>
				</li>
				<li class="active">Gebruikers wijzigen</li>
			</ol>
		</div><!--/.row-->
		<div class="row">
			<div class="col-lg-12">
				<div class="panel panel-default">
					<div class="panel-heading">Alle gebruikers</div>
					<div class="panel-body">
						<form role="form">
							<div class="row">
								<div class="col-md-6">
									<div class="form-group">
										<label for="useridentificatie">Gebruiker</label>
										<br />
										<select  class="form-control" id="selectuser" name="useridentificatie">
											<option value="Maak een keuze">Maak een keuze...</option>
											<?php
												$dal = new DAL();

												$sql = "SELECT rnummer, voornaam, achternaam FROM gebruiker";
												$records = $dal->queryDB($sql);

												foreach($records as $user)
												{
													echo '<option value="'.$user->rnummer.' - '.$user->voornaam.' '.$user->achternaam.'">'.$user->rnummer.' - '.$user->voornaam.' '.$user->achternaam.'</option>';
												}

												$dal->closeConn();
											?>
										</select>
									</div>
									<div class="form-group">
										<label for="voornaam">Voornaam</label>
										<input class="form-control" placeholder="voornaam" id="voornaam" name="voornaam" />
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
								</div>
							</div>
							<div class="row">
								<div class="col-md-6">
									<input type="button" class="btn btn-primary" id="modifyuser" value="Opslaan" />
								</div>
							</div>
						</form>
					</div>
				</div>
			</div>
		</div><!--/.row-->
	</div><!--/.main-->

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