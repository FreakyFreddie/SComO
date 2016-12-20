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
	if(!isset($_SESSION["user"]) OR $_SESSION["user"]->__get("loggedIn") != TRUE)
	{
		header("location: index.php");
	}

	//include getProjectsFromDB
	require $GLOBALS['settings']->Folders['root'].'../lib/project/functions/getProjectsFromDB.php';
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
				<li class="active">Projecten wijzigen</li>
			</ol>
		</div><!--/.row-->
		<div class="row">
			<div class="col-lg-12">
				<div class="panel panel-default">
					<div class="panel-heading">Selecteer een project en wijzig de eigenschappen</div>
					<div class="panel-body">
						<form role="form">
							<div class="row">
								<div class="col-md-6">
									<div class="form-group">
										<label for="projectidentificatie">Id & Titel</label>
										<br />
										<select  class="form-control" id="selectproject" name="projectidentificatie">
											<option value="Maak een keuze">Maak een keuze...</option>
											<?php
												$projects = getProjectsFromDB();

												foreach($projects as $project)
												{
													echo '<option value="'.$project->idproject.' '.$project->titel.'">'.$project->idproject.' '.$project->titel.'</option>';
												}
											?>
										</select>
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
										<label for="titel">Titel</label>
										<input class="form-control" placeholder="Projectnaam" id="projecttitle" name="titel">
									</div>

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
									<input type="button" class="btn btn-primary" id="modifyproject" value="Opslaan" />
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
	<!-- AJAX to modify projects -->
	<script src="js/adminModifyProject.js"></script>
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