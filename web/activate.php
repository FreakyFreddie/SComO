<?php
	//set page var in order to adapt navbar and functions
	$GLOBALS['page'] = "activate";

	//include header
	require '../templates/header.php';
?>
</head>

<body>
	<div class="container main workspace">
		<div class="row">
			<div class="col-lg-12">
				<div class="panel panel-default">
					<div class="panel-body">
<?php
	//include navbar
	require '../templates/navbar.php';

	if(isset($_GET["key"]))
	{
		$dal = new DAL();
		$conn = $dal->getConn();

		$key = mysqli_real_escape_string($conn, $_GET["key"]);

		//create array of parameters
		//first item = parameter types
		//i = integer
		//d = double
		//b = blob
		//s = string
		$parameters[0] = "s";
		$parameters[1] = $key;

		//prepare statement
		$dal->setStatement("SELECT machtigingsniveau FROM gebruiker WHERE activatiesleutel=?");

		$records = $dal->queryDB($parameters);
		
		if($dal->getNumResults()==1 && $records[0]->machtigingsniveau==0)
		{
			//create array of parameters
			//first item = parameter types
			//i = integer
			//d = double
			//b = blob
			//s = string
			$parameters[0] = "s";
			$parameters[1] = $key;

			//prepare statement
			$dal->setStatement("UPDATE gebruiker SET machtigingsniveau='1' WHERE activatiesleutel=?");

			$dal->writeDB($parameters);

			echo "Account geactiveerd.";
		}
		else
		{
			echo "U kan uw account geen tweede keer activeren.";
		}

		$dal->closeConn();
	}
?>
				</div>
			</div>
		</div>
	</div>
	<!-- footer -->
	<?php require $GLOBALS['settings']->Folders['root'].'/templates/footer.php'; ?>