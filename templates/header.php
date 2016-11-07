<?php
	//start session once, header.php is included in all pages
	session_start();
	
	//load config, typecast to object for easy access
	$_GLOBALS['settings'] = (object) parse_ini_file('../config/config.ini', true);
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
		<link rel="icon" href="../../favicon.ico">

		<title>
			<?php
				echo $_GLOBALS['settings']->Store["storeabbrev"];
			?>
		</title>

		<!-- Bootstrap core CSS -->
		<link href="./css/bootstrap.min.css" rel="stylesheet">

		<!-- IE10 viewport hack for Surface/desktop Windows 8 bug -->
		<link href="./css/ie10-viewport-bug-workaround.css" rel="stylesheet">

		<!-- Custom styles for this template -->
		<link href="./css/main.css" rel="stylesheet">

		<!-- Just for debugging purposes. Don't actually copy these 2 lines! -->
		<!--[if lt IE 9]><script src="../../assets/js/ie8-responsive-file-warning.js"></script><![endif]-->
		<script src="./js/ie-emulation-modes-warning.js"></script>

		<!-- HTML5 shim and Respond.js for IE8 support of HTML5 elements and media queries -->
		<!--[if lt IE 9]>
		  <script src="https://oss.maxcdn.com/html5shiv/3.7.3/html5shiv.min.js"></script>
		  <script src="https://oss.maxcdn.com/respond/1.4.2/respond.min.js"></script>
		<![endif]-->
		
		<?php
			//globally used classes go here
			//include ProductPrice class
			require $_GLOBALS['settings']->Folders['root'].'../lib/products/classes/ProductPrice.php';

			//include Product class
			require $_GLOBALS['settings']->Folders['root'].'../lib/products/classes/Product.php';

			//include MouserProduct
			require $_GLOBALS['settings']->Folders['root'].'../lib/products/classes/MouserProduct.php';

			//include FarnellProduct
			require $_GLOBALS['settings']->Folders['root'].'../lib/products/classes/FarnellProduct.php';

			//globally used functions go here
			require $_GLOBALS['settings']->Folders['root'].'../lib/products/functions/getfarnellproducts.php';
			require $_GLOBALS['settings']->Folders['root'].'../lib/products/functions/getmouserproducts.php';
		?>