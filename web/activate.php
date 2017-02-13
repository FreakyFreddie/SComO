<?php
	//set page var in order to adapt navbar and functions
	$GLOBALS['page'] = "activate";

	//include header
	require '../templates/header.php';
?>
</head>

<body>
<?php
	//include navbar
	require '../templates/navbar.php';

	if(isset($_GET["key"]))
	{
		$dal = new DAL();
		$conn = $dal->getConn();
		
		$key = mysqli_real_escape_string($conn, $_GET["key"]);
		
		$sql = "SELECT machtigingsniveau FROM gebruiker WHERE activatiesleutel='".$key."'";
		$records = $dal->queryDB($sql);
		
		if($dal->getNumResults()==1 && $records[0]->machtigingsniveau==0)
		{
			//update DB (still needs work)
			$sql = "UPDATE gebruiker SET machtigingsniveau='1' WHERE activatiesleutel='".$key."'";
			$dal->writeDB($sql);

			echo "Account geactiveerd.";
		}
		else
		{
			echo "U kan uw account geen tweede keer activeren.";
		}

		$dal->closeConn();
	}
?>
	<!-- footer -->
	<?php require $GLOBALS['settings']->Folders['root'].'../templates/footer.php'; ?>