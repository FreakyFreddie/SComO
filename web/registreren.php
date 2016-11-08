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
		
		<div class="jumbotron text-center">
			<h1>
				Registreren
			</h1>
			<p>
				Maak een account aan.
			</p>
		</div>
		<div class="container register">
			<?php
				if(isset($_POST["voornaam"]) && isset($_POST["naam"]) && isset($_POST["email"]) && isset($_POST["wachtwoord"]))
				{
					echo '<div class="row">
							<p>Registratie succesvol</p>
							<p>mail verzonden naar '.$_POST["email"].'</p>
						</div>';
				}
				else
				{
					echo '<form action="registreren.php" method="post">
						<div class="form-group row">
							<label for="Voornaam" class="col-sm-2">Voornaam</label>
							<div class="col-sm-10">
								<input type="text" class="form-control" id="voornaam" name="voornaam" placeholder="voornaam" />
							</div>
						</div>
						<div class="form-group row">
							<label for="naam" class="col-sm-2">Naam</label>
							<div class="col-sm-10">
								<input type="text" class="form-control" id="naam" name="naam" placeholder="naam" />
							</div>
						</div>
						<div class="form-group row">
							<label for="email" class="col-sm-2">Email</label>
							<div class="col-sm-10">
								<input type="email" class="form-control" id="email" name="email" placeholder="r0123456@student.instelling.be" />
							</div>
						</div>
						<div class="form-group row">
							<label for="wachtwoord" class="col-sm-2">Wachtwoord</label>
							<div class="col-sm-10">
								<input type="password" class="form-control"  id="wachtwoord" name="wachtwoord" placeholder="password" />
							</div>
						</div>
						<button class="btn btn-primary" type="submit">Registreer</button>
					</form>';
				}
			?>
		</div>
		<!-- footer -->
		<?php require '../templates/footer.php'; ?>