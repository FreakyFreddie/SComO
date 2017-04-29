<?php
	//set page var in order to adapt navbar and functions
	$GLOBALS['page'] = "index";

	//include header
	require '../templates/header.php';
?>
		<script type="text/javascript" src="./js/searchProduct.js"></script>
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
			<form>
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
		<div class="container shop workspace">
			<noscript>
				<div class="alert alert-success alert-dismissible">
					<a href="#" class="close" data-dismiss="alert" aria-label="close">&times;</a>
					<strong>Opgelet!</strong> Zonder javascript werkt de webwinkel mogelijk niet.
				</div>
			</noscript>
		</div>

		<!-- footer -->
		<?php require $GLOBALS['settings']->Folders['root'].'../templates/footer.php'; ?>
