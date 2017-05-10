<?php
	//load config, typecast to object for easy access
	$GLOBALS['settings'] = (object) parse_ini_file('../config/config.ini', true);
	
	//include classes BEFORE session_start because we might need them in session
	//globally used classes go here
	//include DAL (DAL & login always go on top since classes depend on them)
	require $GLOBALS['settings']->Folders['root'].'../lib/database/classes/DAL.php';

	//include login class
	require $GLOBALS['settings']->Folders['root'].'../lib/users/classes/Login.php';

	//include ProductPrice class
	require $GLOBALS['settings']->Folders['root'].'../lib/products/classes/ProductPrice.php';

	//include Product class
	require $GLOBALS['settings']->Folders['root'].'../lib/products/classes/Product.php';

	//include MouserProduct
	require $GLOBALS['settings']->Folders['root'].'../lib/products/classes/MouserProduct.php';

	//include FarnellProduct
	require $GLOBALS['settings']->Folders['root'].'../lib/products/classes/FarnellProduct.php';

	//add PHPMailer mail functionality
	require $GLOBALS['settings']->Folders['root'].'../lib/PHPMailer/PHPMailerAutoload.php';

	//start session once since header.php is included in all pages
	session_start();
?>

<!DOCTYPE html>
<html lang="en">
	<head>
		<meta charset="utf-8">
		<meta http-equiv="X-UA-Compatible" content="IE=edge">
		<meta name="viewport" content="width=device-width, initial-scale=1">
		<!-- The above 3 meta tags *must* come first in the head; any other head content must come *after* these tags -->
		<meta name="description" content="">
		<meta name="author" content="">
		<!--  <link rel="icon" href="../../favicon.ico"> -->

		<title>
			<?php
				echo $GLOBALS['settings']->Store["storeabbrev"];
			?>
		</title>

		<!-- Latest compiled and minified CSS -->
		<link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/css/bootstrap.min.css" integrity="sha384-BVYiiSIFeK1dGmJRAkycuHAHRg32OmUcww7on3RYdg4Va+PmSTsz/K68vbdEjh4u" crossorigin="anonymous">
		<link href="https://maxcdn.bootstrapcdn.com/font-awesome/4.7.0/css/font-awesome.min.css" rel="stylesheet" integrity="sha384-wvfXpqpZZVQGK6TAh5PVlGOfQNHSoD2xbE+QkPxCAFlNEevoEH3Sl0sibVcOQVnN" crossorigin="anonymous">

		<link href="css/Lumino/datepicker3.css" rel="stylesheet">
		<link href="css/Lumino/bootstrap-table.css" rel="stylesheet">
		<link href="css/Lumino/styles.css" rel="stylesheet">

		<!--Icons-->
		<script src="js/Lumino/lumino.glyphs.js"></script>

		<!-- Custom styles for this template -->
		<link href="./css/main.css" rel="stylesheet">

		<!-- HTML5 shim and Respond.js for IE8 support of HTML5 elements and media queries -->
		<!--[if lt IE 9]>
		  <script src="https://oss.maxcdn.com/html5shiv/3.7.3/html5shiv.min.js"></script>
		  <script src="https://oss.maxcdn.com/respond/1.4.2/respond.min.js"></script>
		<![endif]-->

		<!-- include jquery -->
		<script src="https://ajax.googleapis.com/ajax/libs/jquery/1.12.4/jquery.min.js"></script>
		
		<?php
			//globally used functions go here
			require $GLOBALS['settings']->Folders['root'].'../lib/products/functions/getfarnellproducts.php';
			require $GLOBALS['settings']->Folders['root'].'../lib/products/functions/getmouserproducts.php';

			//logfunction
			require $GLOBALS['settings']->Folders['root'].'../lib/users/functions/logActivity.php';

			//input checks
			require $GLOBALS['settings']->Folders['root'].'../lib/database/functions/validateInputs.php';
						
			//check login condition & log in
			if(isset($_POST["username"]) && isset($_POST["pwd"]))
			{
				//prevent HTML injection
				$rnr = validateRNummer($_POST["rnr"]);
				$pwd = validateWachtWoord($_POST["pwd"]);

				/**
				//Try logging in with Login object & add to session (can be implemented later
				$_SESSION["user"] = new Login($rnr, $pwd);

				//set cookie if the user wants to stay logged in
				if(isset($_POST["keeploggedin"]))
				{

				}

				function storeTokenForUser($username, $token)
				{
				$sql = "INSERT INTO session_remember (`username`, `token`, `expire`) VALUES (?, ?, ?)";
				return Dba::write($sql, array($username, $token, time()));
				}

				function onLogin($user) {
				$token = GenerateRandomToken(); // generate a token, should be 128 - 256 bit
				storeTokenForUser($user, $token);
				$cookie = $user . ':' . $token;
				$mac = hash_hmac('sha256', $cookie, SECRET_KEY);
				$cookie .= ':' . $mac;
				setcookie('rememberme', $cookie);
				}

				function rememberMe() {
				$cookie = isset($_COOKIE['rememberme']) ? $_COOKIE['rememberme'] : '';
				if ($cookie) {
				list ($user, $token, $mac) = explode(':', $cookie);
				if (!hash_equals(hash_hmac('sha256', $user . ':' . $token, SECRET_KEY), $mac)) {
				return false;
				}
				$usertoken = fetchTokenByUserName($user);
				if (hash_equals($usertoken, $token)) {
				logUserIn($user);
				}
				}
				User logs in with 'keep me logged in'
				Create session
				Create a cookie called SOMETHING containing: md5(salt+username+ip+salt) and a cookie called somethingElse containing id
				Store cookie in database
				User does stuff and leaves ----
				User returns, check for somethingElse cookie, if it exists, get the old hash from the database for that user, check of the contents of cookie SOMETHING match with the hash from the database, which should also match with a newly calculated hash (for the ip) thus: cookieHash==databaseHash==md5(salt+username+ip+salt), if they do, goto 2, if they don't goto 1
				 **/

				//Try logging in with Login object & add to session
				$_SESSION["user"] = new Login($rnr, $pwd);
			}

			//write activity to log
			logActivity();
		?>