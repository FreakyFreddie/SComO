<?php
	//set page var in order to adapt navbar and functions
	$_GLOBALS['page'] = 'index';

	//include header
	require '../templates/header.php';
?>
	
		<script src="./js/livesearch.js"></script>
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
					echo $_GLOBALS['settings']->Store['storename'];
				?>
			</h1>
			<p>
				<?php
					echo $_GLOBALS['settings']->Store['quote'];
				?>
			</p>
			<form action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]);?>" method="post">
				<div class="form-group input-group searchbar fieldwithaddon">
					<input type="text" class="form-control" placeholder="zoek een component" name="searchproduct" 
						<?php
							if(isset($_POST['searchproduct']) && $_POST['searchproduct'] != "") 
							{
								echo 'value="'.$_POST['searchproduct'].'"';
							}
						?>
					>
					<span class="input-group-addon">
						<button type="submit">
							<span class="glyphicon glyphicon-search"></span>
						</button> 
					</span>
				</div>
			</form>
		</div>
		<div class="container shop">
			<?php
				if(isset($_POST['searchproduct']) && $_POST['searchproduct'] != "") 
				{
					//getFarnellProducts needs
					$farnellproducts = getFarnellProducts($_POST['searchproduct'], 0, 20, $_GLOBALS['settings']->Suppliers['farnellAPI']);
					
					//getMouserProducts needs
					$mouserproducts = getMouserProducts($_POST['searchproduct'], 0 ,20, $_GLOBALS['settings']->Suppliers['mouserAPI']);
					
					//print message if no products found
					if(empty($farnellproducts) && empty($mouserproducts))
					{
						echo '<div class="row">
								<div class="col-sm-12 text-center">
									<h3>
										Geen weer te geven resultaten voor dit product.
									</h3>
								</div>
							</div>';
					}
					
					//merge arrays into one array for easy sorting
					$products = array_merge($farnellproducts, $mouserproducts);
					
					$counter = 1;
					
					//3 products per line on bigger screens
					foreach($products as $Product)
					{
						if($counter == 1)
						{
							echo '<div class="row">';
						}
						
						if (get_class($Product) == "FarnellProduct")
						{
							$Product->printFarnellProduct();
						}
						elseif  (get_class($Product) == "MouserProduct")
						{
							$Product->printMouserProduct();
						}
						
						if($counter == 3)
						{
							echo '</div>';
							$counter = 0;
						}
						
						$counter++;
					}
				}
			?>
		</div>

		<!-- footer -->
		<?php require $_GLOBALS['settings']->Folders['root'].'../templates/footer.php'; ?>
