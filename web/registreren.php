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
				if($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST["voornaam"]) && isset($_POST["naam"]) && isset($_POST["rnummer"]) && isset($_POST["email"]) && validateDomain($_POST["email"]) && isset($_POST["wachtwoord"])
					&& !empty($_POST["voornaam"]) && !empty($_POST["naam"]) && !empty($_POST["rnummer"]) && !empty($_POST["email"]) && !empty($_POST["wachtwoord"]))
				{
					//new Data Access Layer object
					$dal = new DAL();
					$conn = $dal->getConn();
					
					//validate input for html injection & check vs REGEX, counter mysql injection
					$voornaam = mysqli_real_escape_string($conn, validateNaam($_POST["voornaam"]));
					$naam = mysqli_real_escape_string($conn, validateNaam($_POST["naam"]));
					$rnummer = mysqli_real_escape_string($conn, validateRNummer($_POST["rnummer"]));
					$mail = mysqli_real_escape_string($conn, validateMail($_POST["email"]));
					$wachtwoord = mysqli_real_escape_string($conn, validateWachtWoord($_POST["wachtwoord"]));
					$fullmail = $rnummer."@".$mail;
					
					//test if user already exists with rnummer
					$sql = "SELECT rnummer FROM gebruiker WHERE rnummer='".$rnummer."'";
					$dal->queryDB($sql);
					
					//if user already exists, numrows >= 1, if not we can continue
					if($dal->getNumResults()<1)
					{
						//hash password (use PASSWORD_DEFAULT since php might update algorithms if they become stronger)
						$wachtwoord = password_hash($wachtwoord,PASSWORD_DEFAULT);
						
						//prepare timestamp
						date_default_timezone_set('Europe/Brussels');
						
						do
						{
							//generate unique activation key
							$generatedkey = md5(uniqid(rand(), true));
							
							//test if activation link already exists with rnummer
							$sql = "SELECT rnummer FROM gebruiker WHERE activatiesleutel='".$generatedkey."'";
							$dal->queryDB($sql);
						}
						while($dal->getNumResults() != 0);
						
						//write to DB use date("j-n-Y H:i:s") for date
						$sql = "INSERT INTO gebruiker (rnummer, voornaam, achternaam, email, wachtwoord, machtigingsniveau, aanmaakdatum, activatiesleutel) VALUES ('".$rnummer."', '".$voornaam."', '".$naam."', '".$fullmail."', '".$wachtwoord."', '0', '".date("Y-n-j H:i:s")."', '".$generatedkey."')";
						$dal->writeDB($sql);
						
						//"user created" message
						echo "Gebruiker aangemaakt";
						
						//generate mail headers & message
						$headers = "From: ".$GLOBALS["settings"]->Contact["webmaster"]."\r\nReply-To: ".$GLOBALS["settings"]->Contact["webmaster"];
						$mailmessage = "Klik op onderstaande link om je account te activeren\n".$GLOBALS["settings"]->Domain["domain"]."/activate.php?key=$generatedkey";
						
						//send mail with activation link ($to, $subject, $message), will not work on local server
						/*
						mail($fullmail, "Activeer uw ".$GLOBALS['settings']->Store['storeabbrev']." account", $mailmessage, $headers)
						OR die("Mailserver tijdelijk niet bereikbaar.");

						echo '<div class="row">
								<p>mail verzonden naar '.$fullmail.'</p>
							</div>';
						*/
					}
					else
					{
						echo "<p>Fout bij het aanmaken van de gebruiker. Probeer opnieuw.</p>";
					}
					
					$dal->closeConn();
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