<?php
	//set page var in order to adapt navbar and functions
	$_GLOBALS['page'] = 'index';
?>

<?php
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

		<form class="input-group searchbar" action="index.php" method="post">
			<input type="text" class="form-control" placeholder="zoek een component" name="searchproduct">
			<span class="input-group-addon">
				<button type="submit">
					<span class="glyphicon glyphicon-search"></span>
				</button> 
			</span>
		</form>
		</div>
		<div class="container shop">
		<?php
			if(isset($_POST['searchproduct'])) 
			{
				//getFarnellProducts needs
				$farnellproducts = getFarnellProducts($_POST['searchproduct'], 0, 20, $_GLOBALS['settings']->Suppliers['farnellAPI']);
				
				//getMouserProducts needs
				$mouserproducts = getMouserProducts($_POST['searchproduct'], 0 ,20, $_GLOBALS['settings']->Suppliers['mouserAPI']);
				
				//merge arrays
				$products = array_merge($farnellproducts, $mouserproducts);
				
				print_r($mouserproducts);
				
				foreach($products as $Product)
				{
					break;
				}
			echo'
			<div class="row">
				<div class="col-sm-4">
					<div class="shoparticle">
						.col-sm-4
					</div>
				</div>
				<div class="col-sm-4">
					<div class="shoparticle">
						.col-sm-4
					</div>
				</div>
				<div class="col-sm-4">
					<div class="shoparticle">
						.col-sm-4
					</div>
				</div>
			</div>
			<div class="row">
				<div class="col-sm-4">
					<div class="shoparticle">
						.col-sm-4
					</div>
				</div>
				<div class="col-sm-4">
					<div class="shoparticle">
						<img src="./img/bg1.jpg" class="img-responsive" />
						<h3>
							Artikelnaam
						</h3>
						<ul>
							<li>
								
							</li>
							<li>
								
							</li>
							<li>
								
							</li>
						</ul>
					</div>
				</div>
				<div class="col-sm-4">
					<div class="shoparticle">
						.col-sm-4
					</div>
				</div>
			</div>';
			}
		?>
			
			
		</div>


		<!-- footer -->
		<?php require '../templates/footer.php'; ?>
