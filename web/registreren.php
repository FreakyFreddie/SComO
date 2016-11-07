<?php
	//set page var in order to adapt navbar and functions
	$_GLOBALS['page'] = "registreren";

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

	<body>		
		<?php
			//include navbar
			require '../templates/navbar.php';
		?>
		
		<div class="jumbotron text-center">
			<h1>
				Registreren
			</h1>
			<p>
				Maak een account aan.
			</p>
		</div>
		
		<div class="container register">	
			<form action="" method="post">
				<div class="form-group row">
					<label for="Voornaam" class="col-sm-2">Voornaam</label>
					<div class="col-sm-10">
						<input type="text" class="form-control" id="voornaam" placeholder="voornaam" />
					</div>
				</div>
				<div class="form-group row">
					<label for="naam" class="col-sm-2">Naam</label>
					<div class="col-sm-10">
						<input type="text" class="form-control" id="naam" placeholder="naam" />
					</div>
				</div>
				<div class="form-group row">
					<label for="email" class="col-sm-2">Email</label>
					<div class="col-sm-10">
						<input type="email" class="form-control" id="email" placeholder="rnummer@student.instelling.be" />
					</div>
				</div>
				<div class="form-group row">
					<label for="wachtwoord" class="col-sm-2">Wachtwoord</label>
					<div class="col-sm-10">
						<input type="password" class="form-control"  id="wachtwoord" placeholder="password" />
					</div>
				</div>
			</form>
		</div>
		
		<!-- footer -->
		<?php require '../templates/footer.php'; ?>