<?php
	//set page var in order to adapt navbar and functions
	$GLOBALS['page'] = "registreren";

	//include header
	require '../templates/header.php';
	
	//if user logs in on the registration page, he is redirected to index
	if(isset($_SESSION["user"]) && $_SESSION["user"]->__get("loggedIn"))
	{
		header("Location:index.php");
	}

	require $GLOBALS['settings']->Folders['root'].'../lib/users/functions/registerUser.php';
?>

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
				if($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST["voornaam"]) && isset($_POST["naam"]) && isset($_POST["rnummer"]) && isset($_POST["email"]) && validateDomain($_POST["email"]) && isset($_POST["wachtwoord"]) && isset($_POST["herhaalwachtwoord"])
					&& !empty($_POST["voornaam"]) && !empty($_POST["naam"]) && !empty($_POST["rnummer"]) && !empty($_POST["email"]) && !empty($_POST["wachtwoord"]) && !empty($_POST["herhaalwachtwoord"]) && ($_POST["wachtwoord"]==$_POST["herhaalwachtwoord"]))
				{
					registerUser($_POST["voornaam"], $_POST["naam"], $_POST["rnummer"], $_POST["email"], $_POST["wachtwoord"]);
				}
				else
				{
					echo '<form action="';
					echo htmlspecialchars($_SERVER['PHP_SELF']);
					echo '" method="post">
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
							<label for="rnummer" class="col-sm-2">rnummer en email</label>
							<div class="col-sm-5">
								<input type="text" class="form-control" id="rnummer" name="rnummer" placeholder="r0123456" />
							</div>
							<div class="col-sm-5">
								<select class="form-control"  id="email" name="email">
									<option value="Selecteer keuze">Selecteer keuze</option>';
									//integrate mail whitelist
									foreach ($GLOBALS['settings']->Whitelist["mail"] as $mailaddress)
									{
										echo '<option value="'.$mailaddress.'">@'.$mailaddress.'</option>';
									}
					echo'		</select> 
							</div>
						</div>
						<div class="form-group row">
							<label for="wachtwoord" class="col-sm-2">Wachtwoord</label>
							<div class="col-sm-10">
								<input type="password" class="form-control" id="wachtwoord" name="wachtwoord" placeholder="wachtwoord" />
							</div>
						</div>
						<div class="form-group row">
							<label for="herhaalwachtwoord" class="col-sm-2">Herhaal wachtwoord</label>
							<div class="col-sm-10">
								<input type="password" class="form-control" id="herhaalwachtwoord" name="herhaalwachtwoord" placeholder="herhaal wachtwoord" />
							</div>
						</div>
						<button class="btn btn-primary" type="submit">Registreer</button>
					</form>';
				}
			?>
		</div>
		
		<!-- footer -->
		<?php require $GLOBALS['settings']->Folders['root'].'../templates/footer.php'; ?>