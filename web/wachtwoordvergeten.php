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
	require $GLOBALS['settings']->Folders['root'].'/lib/users/functions/resetPassword.php';
?>

	</head>

	<body>
<?php
	//include navbar
	require '../templates/navbar.php';
?>

	<div class="jumbotron text-center">
		<h1>
			Wachtwoord vergeten?
		</h1>
		<p>
			Vraag een nieuw wachtwoord aan
		</p>
	</div>
	<div class="container register workspace">
		<?php
			if($_SERVER["REQUEST_METHOD"] == "POST"&& isset($_POST["rnummer"]) && isset($_POST["email"]) && validateDomain($_POST["email"]) && !empty($_POST["rnummer"]) && !empty($_POST["email"]))
			{
				resetPassword($_POST["rnummer"], $_POST["email"]);
			}
			else
			{
				echo '<form action="';
				echo htmlspecialchars($_SERVER['PHP_SELF']);
				echo '" method="post">
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
						<button class="btn btn-primary" type="submit">Reset wachtwoord</button>
					</form>';
			}
		?>
	</div>

	<!-- footer -->
<?php require $GLOBALS['settings']->Folders['root'].'/templates/footer.php'; ?>