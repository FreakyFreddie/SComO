<?php
	//set page var in order to adapt navbar and functions
	$_GLOBALS['page'] = "winkelmandje";
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
					echo 'Winkelmandje';
				?>
			</h1>
			<p>
				<?php
					echo 'Jouw artikelen';
				?>
			</p>
		</div>
		
		<!-- footer -->
		<?php require '../templates/footer.php'; ?>