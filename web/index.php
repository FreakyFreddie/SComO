<?php
	//set page var in order to adapt navbar and functions
	$GLOBALS['page'] = "index";

	//include header
	require '../templates/header.php';
?>
	</head>

	<body>	
		<?php
			//include navbar
			require '../templates/navbar.php';
		?>
		
		<!-- PROJECT TITLE and QUOTE -->
		<div class="jumbotron text-center">
			<h1>
				<?php
					echo $GLOBALS['settings']->Store['storename'];
				?>
			</h1>
			<p>
				<?php
					echo $GLOBALS['settings']->Store['quote'];
				?>
			</p>
			<form id="searchform">
				<div class="form-group input-group searchbar">
					<input id="searchterm" type="text" class="form-control" placeholder="zoek een component" name="searchproduct">
					<span class="input-group-btn">
						<button type="button" id="searchproduct" class="btn btn-secondary">
							<span class="glyphicon glyphicon-search"></span>
						</button>
					</span>
				</div>
			</form>
		</div>
		<!--<div id="overlay">
			<div class="loader">
				<i class="fa fa-cog fa-spin fa-5x fa-fw"></i>
				<span class="sr-only">Loading...</span>
			</div>
		</div>-->
		<div class="container shop workspace">
			<noscript>
				<div class="alert alert-success alert-dismissible">
					<a href="#" class="close" data-dismiss="alert" aria-label="close">&times;</a>
					<strong>Opgelet!</strong> Zonder javascript werkt de webwinkel mogelijk niet.
				</div>
			</noscript>
			<?php
				if(!isset($_SESSION["user"]) OR $_SESSION["user"]->__get("loggedIn") != TRUE)
				{
					echo'<div class="alert alert-warning alert-dismissible">
						<a href="#" class="close" data-dismiss="alert" aria-label="close">&times;</a>
						<strong>Opgelet!</strong> U dient ingelogd te zijn om de webwinkel te kunnen gebruiken.
					</div>';
				}
				elseif($_SESSION["user"]->__get("permissionLevel") == 5)
				{
					echo'<div class="alert alert-warning alert-dismissible">
						<a href="#" class="close" data-dismiss="alert" aria-label="close">&times;</a>
						<strong>Opgelet!</strong> U bent verbannen. Neem contact op met uw docent.
					</div>';
				}
				elseif($_SESSION["user"]->__get("permissionLevel") == 0)
				{
					echo'<div class="alert alert-warning alert-dismissible">
						<a href="#" class="close" data-dismiss="alert" aria-label="close">&times;</a>
						<strong>Opgelet!</strong> Uw account is nog niet geactiveerd. Activeer deze via de ontvangen link in uw schoolmailbox.
					</div>';
				}
			?>
		</div>
		<script src="js/Lumino/bootstrap-table.js"></script>
		<script type="text/javascript" src="./js/searchProduct.js"></script>
		<!-- footer -->
		<?php require $GLOBALS['settings']->Folders['root'].'/templates/footer.php'; ?>
