<?php
	//set page var in order to adapt navbar and functions
	$_GLOBALS['page'] = "profiel";
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
					echo 'Profiel';
				?>
			</h1>
			<p>
				<?php
					echo 'Jouw Naam';
				?>
			</p>
		</div>
		
		<!-- footer -->
		<?php require '../templates/footer.php'; ?>