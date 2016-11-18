<?php
	if(isset $_GET["key"])
	{
		$dal = new DAL();
		$conn = $dal->getConn();
		
		$key = mysqli_real_escape_string($conn, $_GET["key"]);
		
		$sql = "SELECT machtigingsniveau FROM gebruiker WHERE activatiesleutel='".$key."'";
		$records = $dal->QueryDB($sql);
		
		if($dal->getNumResults()==1 && $records[0]->machtigingsniveau==0)
		{
			//update DB (still needs work)
			$sql = "UPDATE gebruikers SET machtigingsniveau='1' WHERE activatiesleutel='".$key."'";
			$dal->WriteDB($sql);
		}
		else
		{
			echo "U kunt uw account geen tweede keer activeren."
		}

		
		$dal->CloseConn();
	}
?>