<?php
	//set page var in order to adapt navbar and functions
	$_GLOBALS['page'] = "registreren";
?>

<?php
	//include configuration file
	require '../config/config.php';
	
	//include header
	require '../templates/header.php';
?>

	<body>		
		<?php
			//include navbar
			require '../templates/navbar.php';
		?>
		
		<div class="jumbotron text-center">
			<h1>
				<?php
					echo 'Registreren';
				?>
			</h1>
			<p>
				<?php
					echo 'Maak een account aan.';
				?>
			</p>
		</div>
		
		<!-- footer -->
		<?php require '../templates/footer.php'; ?>