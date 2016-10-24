<?php
	//set page var in order to adapt navbar and functions
	$_GLOBALS['page'] = "index";
?>

<?php
	//include configuration file
	require '../config/config.php';
	
	//include header
	require '../templates/header.php';
?>
	
		<script src="./js/livesearch.js"></script>
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
				echo $storename;
			?>
		</h1>
		<p>
			<?php
				echo $quote;
			?>
		</p>

		<form class="input-group searchbar">
			<input type="text" class="form-control" onkeyup="showResult(this.value)"  placeholder="zoek een component" >
			<span class="input-group-addon">
				<button type="submit">
					<span class="glyphicon glyphicon-search"></span>
				</button> 
			</span>
			<div id="livesearch"></div>
		</form>
		</div>
		<div class="container shop">
			<div class="row">
				<div class="col-sm-4">
					<div class="shoparticle">
						.col-sm-4
					</div>
				</div>
				<div class="col-sm-4">
					<div class="shoparticle">
						.col-sm-4
					</div>
				</div>
				<div class="col-sm-4">
					<div class="shoparticle">
						.col-sm-4
					</div>
				</div>
			</div>
			<div class="row">
				<div class="col-sm-4">
					<div class="shoparticle">
						.col-sm-4
					</div>
				</div>
				<div class="col-sm-4">
					<div class="shoparticle">
						<img src="./img/bg1.jpg" class="img-responsive" />
						<h3>
							Artikelnaam
						</h3>
						<ul>
							<li>
								
							</li>
							<li>
								
							</li>
							<li>
								
							</li>
						</ul>
					</div>
				</div>
				<div class="col-sm-4">
					<div class="shoparticle">
						.col-sm-4
					</div>
				</div>
			</div>
		</div>


		<!-- footer -->
		<?php require '../templates/footer.php'; ?>
