<?php
	//this page is created using the Lumino bootstrap dashboard template
	//The functionality is limited to the basics, but can be expanded if needed
	//template can be found here: http://medialoot.com/item/lumino-admin-bootstrap-template/

	//set page var in order to adapt navbar and functions
	$GLOBALS['page'] = "adminpanel";
	$GLOBALS['adminpage'] = "adminpanel";

	//include header
	require '../templates/header.php';

	//redirect if user is not logged in
	if(!isset($_SESSION["user"]) OR $_SESSION["user"]->__get("loggedIn") != TRUE OR $_SESSION["user"]->__get("permissionLevel") != 2)
	{
		header("location: index.php");
	}

	//include getOrdersTotal
	require $GLOBALS['settings']->Folders['root'].'../lib/orders/functions/getOrdersTotal.php';

	//include getProjectsTotal
	require $GLOBALS['settings']->Folders['root'].'../lib/project/functions/getProjectsTotal.php';

	//include getUsersTotal
	require $GLOBALS['settings']->Folders['root'].'../lib/users/functions/getUsersTotal.php';

?>
		<link href="css/Lumino/datepicker3.css" rel="stylesheet">
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
				<div class="col-xs-12 col-md-6 col-lg-3">
					<div class="panel panel-blue panel-widget ">
						<div class="row no-padding">
							<a href="adminbestellingenkeuren.php">
								<div class="col-sm-3 col-lg-5 widget-left">
									<svg class="glyph stroked bag">
										<use xlink:href="#stroked-bag"></use>
									</svg>
								</div>
							</a>
							<div class="col-sm-9 col-lg-7 widget-right">
								<div class="large">
									<?php
										$countNewOrders = getOrdersTotal("Pending");
										echo $countNewOrders;
									?>
								</div>
								<div class="text-muted">Nieuwe orders</div>
							</div>
						</div>
					</div>
				</div>
				<div class="col-xs-12 col-md-6 col-lg-3">
					<div class="panel panel-orange panel-widget">
						<div class="row no-padding">
							<a href="adminbestellingenBOMdownloaden.php">
								<div class="col-sm-3 col-lg-5 widget-left">
									<svg class="glyph stroked table">
										<use xlink:href="#stroked-table"></use>
									</svg>
								</div>
							</a>
							<div class="col-sm-9 col-lg-7 widget-right">
								<div class="large">
									<?php
										$countProcessedOrders = getOrdersTotal("Besteld") + getOrdersTotal("Aangekomen") + getOrdersTotal("Afgehaald");
										echo $countProcessedOrders;
									?>
								</div>
								<div class="text-muted">Orders verwerkt</div>
							</div>
						</div>
					</div>
				</div>
				<div class="col-xs-12 col-md-6 col-lg-3">
					<div class="panel panel-teal panel-widget">
						<div class="row no-padding">
							<a href="adminprojecten.php">
								<div class="col-sm-3 col-lg-5 widget-left">
									<svg class="glyph stroked calendar">
										<use xlink:href="#stroked-calendar"></use>
									</svg>
								</div>
							</a>
							<div class="col-sm-9 col-lg-7 widget-right">
								<div class="large">
									<?php
										$countProjects = getProjectsTotal();
										echo $countProjects;
									?>
								</div>
								<div class="text-muted">Projecten</div>
							</div>
						</div>
					</div>
				</div>
				<div class="col-xs-12 col-md-6 col-lg-3">
					<div class="panel panel-red panel-widget">
						<div class="row no-padding">
							<a href="adminusers.php">
								<div class="col-sm-3 col-lg-5 widget-left">
									<svg class="glyph stroked male-user">
										<use xlink:href="#stroked-male-user"></use>
									</svg>
								</div>
							</a>
							<div class="col-sm-9 col-lg-7 widget-right">
								<div class="large">
									<?php
										$countUsers = getUsersTotal();
										echo $countUsers;
									?>
								</div>
								<div class="text-muted">Gebruikers</div>
							</div>
						</div>
					</div>
				</div>
			</div><!--/.row-->

			<div class="row">
				<div class="col-lg-12">
					<div class="panel panel-default">
						<div class="panel-heading">Hoeveelheid door studenten bestelde producten per week</div>
						<div class="panel-body">
							<div class="canvas-wrapper">
								<canvas class="main-chart" id="line-chart" height="200" width="600"></canvas>
							</div>
						</div>
					</div>
				</div>
			</div><!--/.row-->
		</div>	<!--/.main-->

		<script src="js/Lumino/chart.min.js"></script>
		<script src="js/Lumino/chart-data.js"></script>
		<script src="js/Lumino/easypiechart.js"></script>
		<script src="js/Lumino/easypiechart-data.js"></script>
		<script src="js/Lumino/bootstrap-datepicker.js"></script>
		<script>
			$('#calendar').datepicker({
			});

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

	<?php require $GLOBALS['settings']->Folders['root'].'../templates/footer.php'; ?>