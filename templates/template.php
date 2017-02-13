<?php
	//set page var in order to adapt navbar and functions
	$GLOBALS['page'] = "index";

	//include header
	require '../templates/header.php';

	//redirect if user is not logged in
	if(!isset($_SESSION["user"]) OR $_SESSION["user"]->__get("loggedIn") != TRUE)
	{
		header("location: index.php");
	}
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
						echo 'value="'.$_GET['searchproduct'].'"';
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
<div class="container">
	<noscript>
		<div class="alert alert-warning alert-dismissible">
			<a href="#" class="close" data-dismiss="alert" aria-label="close">&times;</a>
			<strong>Opgelet!</strong> Zonder javascript werkt de webwinkel mogelijk niet.
		</div>
	</noscript>
</div>

<!-- footer -->
<?php require $GLOBALS['settings']->Folders['root'].'../templates/footer.php'; ?>

