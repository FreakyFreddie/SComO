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

	//include registerfunction
	require $GLOBALS['settings']->Folders['root'].'/lib/users/functions/registerUser.php';
?>

	<script type="text/javascript" src="js/Validator/validator.min.js"></script>

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
		<div class="container main workspace">
			<div class="row">
				<div class="col-lg-12">
					<div class="panel panel-default">
						<div class="panel-body">
						<?php
							if($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST["voornaam"]) && isset($_POST["naam"]) && isset($_POST["rnummer"]) && isset($_POST["email"]) && validateDomain($_POST["email"]) && isset($_POST["wachtwoord"]) && isset($_POST["herhaalwachtwoord"])
								&& !empty($_POST["voornaam"]) && !empty($_POST["naam"]) && !empty($_POST["rnummer"]) && !empty($_POST["email"]) && !empty($_POST["wachtwoord"]) && !empty($_POST["herhaalwachtwoord"]) && ($_POST["wachtwoord"]==$_POST["herhaalwachtwoord"]))
							{
								registerUser($_POST["voornaam"], $_POST["naam"], $_POST["rnummer"], $_POST["email"], $_POST["wachtwoord"]);
							}
							else
							{
								echo '<form data-toggle="validator" role="form" action="';
								echo htmlspecialchars($_SERVER['PHP_SELF']);
								echo '" method="post">
							<div class="form-group">
								<div class="form-inline row">
									<div class="form-group col-sm-3 has-feedback">
										<label for="voornaam" class="control-label">Voornaam</label>
										<input type="text" pattern="^[A-z ,.\'-]{1,}$" class="form-control" id="voornaam" name="voornaam" placeholder="John" required>
										<span class="glyphicon form-control-feedback" aria-hidden="true"></span>
										<div class="help-block with-errors"></div>
									</div>
									<div class="form-group col-sm-4 has-feedback">
										<label for="naam" class="control-label">Naam</label>
										<input type="text" pattern="^[A-z ,.\'-]{1,}$" class="form-control" id="naam" name="naam" placeholder="Doe" required>
										<span class="glyphicon form-control-feedback" aria-hidden="true"></span>
										<div class="help-block with-errors"></div>
									</div>
								</div>
							</div>
							<div class="form-group">
								<div class="form-inline row">
									<div class="form-group col-sm-3 has-feedback">
										<label for="rnummer" class="control-label">rnummer</label>
										<input type="text" data-minlength="8" pattern="^[rsu][0-9][0-9][0-9][0-9][0-9][0-9][0-9]$" data-maxlength="8"  class="form-control" id="rnummer" name="rnummer" placeholder="r01234567" required>
										<span class="glyphicon form-control-feedback" aria-hidden="true"></span>
										<div class="help-block">r,s of u gevolgd door 7 cijfers.</div>
									</div>
									<div class="form-group col-sm-4 has-feedback">
										<label for="email" class="control-label">email</label>
										<select class="form-control"  id="email" name="email">';
								//integrate mail whitelist
								foreach ($GLOBALS['settings']->Whitelist["mail"] as $mailaddress)
								{
									echo '<option value="'.$mailaddress.'">@'.$mailaddress.'</option>';
								}
								echo'</select>
									</div>
								</div>
							</div>
							<div class="form-group">
								<div class="form-inline row">
									<div class="form-group col-sm-3 has-feedback">
										<label for="wachtwoord" class="control-label">Wachtwoord</label>
										<input type="password" data-minlength="8" class="form-control" id="wachtwoord" name="wachtwoord" placeholder="Wachtwoord" required>
										<span class="glyphicon form-control-feedback" aria-hidden="true"></span>
										<div class="help-block">Minimaal 8 karakters.</div>
									</div>
									<div class="form-group col-sm-4 has-feedback">
										<label for="herhaalwachtwoord" class="control-label">Herhaal wachtwoord</label>
										<input type="password" class="form-control" id="herhaalwachtwoord" name="herhaalwachtwoord" data-match="#wachtwoord" data-match-error="Wachtwoorden komen niet overeen." placeholder="herhaal wachtwoord" required>
										<span class="glyphicon form-control-feedback" aria-hidden="true"></span>
										<div class="help-block with-errors"></div>
									</div>
								</div>
							</div>
							<div class="form-group">
								<button type="submit" class="btn btn-primary">Submit</button>
							</div>
						</form>';
							}
						?>
						</div>
					</div>
				</div>
			</div>
		</div>

		<!-- footer -->
		<?php require $GLOBALS['settings']->Folders['root'].'/templates/footer.php'; ?>