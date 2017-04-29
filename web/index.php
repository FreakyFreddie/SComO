<?php
	//set page var in order to adapt navbar and functions
	$GLOBALS['page'] = "index";

	//include header
	require '../templates/header.php';
?>

	<script type="text/javascript" src="./js/searchProduct.js"></script>

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
			<form>
				<div class="form-group input-group searchbar">
					<input id="searchterm" type="text" class="form-control" placeholder="zoek een component" name="searchproduct">
					<span class="input-group-btn">
						<button type="button" id="searchproduct" class="btn btn-secondary">
							<span class="glyphicon glyphicon-search"></span>
						</button>
					</span>
				</div>
			</form>
		</div>
		<div id="overlay">
			<div class="loader">
				<i class="fa fa-cog fa-spin fa-5x fa-fw"></i>
				<span class="sr-only">Loading...</span>
			</div>
		</div>
		<div class="container shop workspace">
			<noscript>
				<div class="alert alert-success alert-dismissible">
					<a href="#" class="close" data-dismiss="alert" aria-label="close">&times;</a>
					<strong>Opgelet!</strong> Zonder javascript werkt de webwinkel mogelijk niet.
				</div>
			</noscript>

			<?php
				if(isset($_GET["searchproduct"]))
				{
					echo '<table id="displayproducts" data-toggle="table" data-url="AJAX/processSearchProductRequest.php?searchproduct='.validateInput($_GET["searchproduct"]).'" data-show-refresh="true" data-show-toggle="true" data-show-columns="true" data-search="true" data-select-item-name="toolbar1" data-pagination="true" data-sort-name="name" data-sort-order="desc">'
						.'<thead>'
							.'<tr>'
							.'<th data-field="image" data-sortable="true">afbeelding</th>'
							.'<th data-field="id" data-sortable="true">id</th>'
							.'<th data-field="name" data-sortable="true">naam</th>'
							.'<th data-field="supplier"  data-sortable="true">leverancier</th>'
							.'<th data-field="vendor" data-sortable="true">verkoper</th>'
							.'<th data-field="inventory" data-sortable="true">voorraad</th>'
							.'<th data-field="datasheet">datasheet</th>'
							.'<th data-field="prices">prijzen</th>'
							.'<th data-field="add">bestel</th>'
							.'</tr>'
						.'</thead>'
					.'</table>';
				}
			?>
		</div>
		<script src="js/Lumino/bootstrap-table.js"></script>
		<!-- footer -->
		<?php require $GLOBALS['settings']->Folders['root'].'../templates/footer.php'; ?>
