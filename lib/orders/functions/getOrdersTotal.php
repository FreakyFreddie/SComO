<?php
	//returns the amount of new orders
	function getOrdersTotal($status)
	{
		$dal = new DAL();

		switch ($status) {
			case "Geweigerd":
				$sql = "SELECT COUNT(*) FROM bestelling WHERE status='0'";
				$count = $dal->countDB($sql);
				break;

			case "Pending":
				$sql = "SELECT COUNT(*) FROM bestelling WHERE status='1'";
				$count = $dal->countDB($sql);
				break;

			case "Goedgekeurd":
				$sql = "SELECT COUNT(*) FROM bestelling WHERE status='2'";
				$count = $dal->countDB($sql);
				break;

			case "Besteld":
				$sql = "SELECT COUNT(*) FROM bestelling WHERE status='3'";
				$count = $dal->countDB($sql);
				break;

			case "Aangekomen":
				$sql = "SELECT COUNT(*) FROM bestelling WHERE status='4'";
				$count = $dal->countDB($sql);
				break;

			case "Afgehaald":
				$sql = "SELECT COUNT(*) FROM bestelling WHERE status='5'";
				$count = $dal->countDB($sql);
				break;
		}

		$dal->closeConn();

		return $count;
	}
?>