<?php
	//set page var in order to adapt navbar and functions
	$GLOBALS['page'] = "profiel";

	//include header
	require '../templates/header.php';

	//redirect if user is not logged in
	if(!isset($_SESSION["user"]) OR $_SESSION["user"]->__get("loggedIn") != TRUE)
	{
		header("location: index.php");
	}

	//include function to change password
	require $GLOBALS['settings']->Folders['root'].'../lib/users/functions/changePassWord.php';
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
			echo $_SESSION["user"]->__get("firstName")." ".$_SESSION["user"]->__get("lastName")
		?>
	</h1>
	<p>
		Gegevens.
	</p>
</div>
<div class="container workspace">
	<noscript>
		<div class="alert alert-warning alert-dismissible">
			<a href="#" class="close" data-dismiss="alert" aria-label="close">&times;</a>
			<strong>Opgelet!</strong> Zonder javascript werkt de webwinkel mogelijk niet.
		</div>
	</noscript>
	<?php
		if($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST["oudwachtwoord"]) && isset($_POST["wachtwoord"]) && isset($_POST["herhaalwachtwoord"])
			&& !empty($_POST["oudwachtwoord"]) && !empty($_POST["wachtwoord"]) && !empty($_POST["herhaalwachtwoord"]) && ($_POST["wachtwoord"]==$_POST["herhaalwachtwoord"]))
		{
			changePassWord($_SESSION["user"]->__get("userId"), $_POST["oudwachtwoord"], $_POST["wachtwoord"]);
		}
		else
		{
			echo '<form action="';
			echo htmlspecialchars($_SERVER['PHP_SELF']);
			echo '" method="post">
						<div class="form-group row">
							<label for="oudwachtwoord" class="col-sm-2">Oud wachtwoord</label>
							<div class="col-sm-10">
								<input type="password" class="form-control" id="oudwachtwoord" name="oudwachtwoord" placeholder="Oud wachtwoord" />
							</div>
						</div>
						<div class="form-group row">
							<label for="wachtwoord" class="col-sm-2">Niew wachtwoord</label>
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
						<button class="btn btn-primary" type="submit">Wijzig</button>
					</form>';
		}
	?>
</div>

<!-- footer -->
<?php require $GLOBALS['settings']->Folders['root'].'../templates/footer.php'; ?>
