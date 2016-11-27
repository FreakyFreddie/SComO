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

		public function __construct($ouserId, $opersonal, $pId="")
		{
			//prepare timestamp
			date_default_timezone_set('Europe/Brussels');

			$this->projectId = $pId;
			$this->userId = $ouserId;
			$this->orderPersonal = $opersonal;
			$this->orderCreationDate = date("Y-n-j H:i:s");

			//on creation, status is "pending"
			$this->orderStatus = "1";
		}

		public function writeDB()
		{
			$dal = new DAL();

			//prevent SQL injection
			$this->projectId = mysqli_real_escape_string($dal->getConn(), $this->projectId);
			$this->userId = mysqli_real_escape_string($dal->getConn(), $this->userId);
			$this->orderPersonal = mysqli_real_escape_string($dal->getConn(), $this->orderPersonal);
			$this->orderCreationDate = mysqli_real_escape_string($dal->getConn(), $this->orderCreationDate);
			$this->orderStatus = mysqli_real_escape_string($dal->getConn(), $this->orderStatus);

			//add order to DB (still to complete)
			$sql = "INSERT INTO bestelling (idproject, rnummer, persoonlijk, besteldatum, status) VALUES ('" . $this->projectId . "', '" . $this->userId . "', '" . $this->orderPersonal . "', '" . $this->orderCreationDate . "', '" . $this->orderStatus . "')";
			$dal->writeDB($sql);

			//get the automatically generated ID of the order
			$this->orderId = mysqli_insert_id($dal->getConn());
		}

		public function getOrderFromDB()
		{

		}
	}