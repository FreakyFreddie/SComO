<?php
	//creation of a new order->write to database
	//add OrderProduct (product from shopping cart) to DB
	class Order
	{
		private $orderId;
		private $projectId;
		private $userId;
		private $orderPersonal;
		private $orderCreationDate;
		private $orderStatus;

		public function __construct($pId="", $ouserId, $opersonal, $ocreationdate)
		{
			$this->projectId = $pId;
			$this->userId = $ouserId;
			$this->orderPersonal = $opersonal;
			//ocreationdate can be replaced by date function
			$this->orderCreationDate = $ocreationdate;
			$this->orderStatus = "0";
		}

		public function writeOrderDB()
		{
			$dal = new DAL();

			$this->projectId = mysqli_real_escape_string($dal->getConn(), $this->projectId);
			$this->userId = mysqli_real_escape_string($dal->getConn(), $this->userId);
			$this->orderPersonal = mysqli_real_escape_string($dal->getConn(), $this->orderPersonal);
			$this->orderCreationDate = mysqli_real_escape_string($dal->getConn(), $this->orderCreationDate);
			$this->orderStatus = mysqli_real_escape_string($dal->getConn(), $this->orderStatus);

			//add order to DB (still to complete)
			$sql = "INSERT INTO bestelling (idproject, leverancier, productnaam, productverkoper, productafbeelding, productdatasheet) VALUES ('" . $this->productId . "', '" . $this->productSupplier . "', '" . $this->productName . "', '" . $this->productVendor . "', '" . $this->productImage . "', '" . $this->productDataSheet . "')";
			$dal->writeDB($sql);
		}
	}