<?php
	//set page var in order to adapt navbar and functions
	$GLOBALS['page'] = "index";

	//include header
	require '../templates/header.php';
?>
		<script type="text/javascript" src="./js/addToCart.js"></script>
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
					echo $GLOBALS['settings']->Store['storename'];
				?>
			</h1>
			<p>
				<?php
					echo $GLOBALS['settings']->Store['quote'];
				?>
			</p>
			<form action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]);?>" method="get">
				<div class="form-group input-group searchbar">
					<input type="text" class="form-control" placeholder="zoek een component" name="searchproduct" 
						<?php
							if(isset($_GET['searchproduct']) && $_GET['searchproduct'] != "") 
							{
								//validate
								echo 'value="'.htmlspecialchars($_GET['searchproduct']).'"';
							}
						?>
					>
					<span class="input-group-btn">
						<button class="btn btn-secondary" type="submit">
							<span class="glyphicon glyphicon-search"></span>
						</button>
					</span>
				</div>
			</form>
		</div>
		<div class="container shop workspace">
			<noscript>
				<div class="alert alert-success alert-dismissible">
					<a href="#" class="close" data-dismiss="alert" aria-label="close">&times;</a>
					<strong>Opgelet!</strong> Zonder javascript werkt de webwinkel mogelijk niet.
				</div>
			</noscript>
			<?php
				if(isset($_GET['searchproduct']) && $_GET['searchproduct'] != "") 
				{
					//send request to Farnell API
					$farnellproducts = getFarnellProducts(htmlspecialchars($_GET['searchproduct']), 0, 20);

					//send request to Mouser API
					$mouserproducts = getMouserProducts(htmlspecialchars($_GET['searchproduct']), 0, 20);
					
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
		<?php require $GLOBALS['settings']->Folders['root'].'../templates/footer.php'; ?>
