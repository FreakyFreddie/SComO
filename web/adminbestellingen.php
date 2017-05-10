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
        <script src="js/adminSidePanelOrder.js"></script>

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
                                        Bestelnr
                                    </th>
                                    <td id="bestelnr" class="text-right">

                                    </td>
                                </tr>
                                <tr>
                                    <th>
                                        Besteldatum
                                    </th>
                                    <td id="besteldatum" class="text-right">

                                    </td>
                                </tr>
                                <tr>
                                    <th>
                                        Rnummer
                                    </th>
                                    <td id="rnummer" class="text-right">

                                    </td>
                                </tr>
                                </tbody>
                            </table>
                            <table class="table table-hover">
                                <tbody>
                                <tr>
                                    <th>
                                        ProjectId
                                    </th>
                                    <td id="projectid" class="text-right">

                                    </td>
                                </tr>
                                <tr>
                                    <th>
                                        ProjectTitel
                                    </th>
                                    <td id="projecttitel" class="text-right">

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
			<div class="alert alert-info alert-dismissible">
				<a href="#" class="close" data-dismiss="alert" aria-label="close">&times;</a>
				<strong>Opgelet!</strong> Gebruikte prijzen zijn de prijzen die van toepassing waren op de besteldatum!
			</div>
		</div>
		<div class="row">
			<div class="col-lg-12">
				<div class="panel panel-default">
					<div class="panel-heading">Alle bestellingen</div>
					<div class="panel-body">
						<table data-toggle="table" data-url="AJAX/adminDisplayOrdersRequest.php"  data-show-refresh="true" data-show-toggle="true" data-show-columns="true" data-search="true" data-select-item-name="toolbar1" data-pagination="true" data-sort-name="name" data-sort-order="desc">
							<thead>
								<tr>
									<th data-field="bestelnr" data-sortable="true">bestelnr</th>
									<th data-field="besteldatum"  data-sortable="true">besteldatum</th>
                                    <th data-field="rnummer" data-sortable="true">rnummer</th>
									<th data-field="projectid" data-sortable="true">project id</th>
									<th data-field="projecttitel" data-sortable="true">project titel</th>
                                    <th data-field="details">details</th>
								</tr>
							</thead>
						</table>
					</div>
				</div>
			</div>
		</div><!--/.row-->
	</div><!--/.main-->

	<script src="js/Lumino/bootstrap-datepicker.js"></script>
	<script src="js/Lumino/bootstrap-table.js"></script>
    <script src="js/Lumino/easypiechart.js"></script>
    <script src="js/Lumino/easypiechart-data.js"></script>
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

