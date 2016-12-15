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

		//array of products belonging to the order
		private $orderProducts;

		public function __construct($ouserId, $opersonal="0", $pId="")
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

		public function __set($property, $value)
		{
			switch ($property) {
				case "orderId":
					$this->orderId = $value;
					break;

				case "projectId":
					$this->projectId = $value;
					break;

				case "userId":
					$this->userId = $value;
					break;

				case "orderPersonal":
					$this->orderPersonal = $value;
					break;

				case "orderCreationDate":
					$this->orderCreationDate = $value;
					break;

				case "orderStatus":
					$this->orderStatus = $value;
					break;

				case "orderProducts":
					$this->orderproducts = $value;
					break;
			}
		}

		public function __get($property)
		{
			$result = FALSE;
			switch ($property) {
				case "Id":
					$result = $this->orderId;
					break;
			}

			return $result;
		}

		public function writeDB()
		{
			//catch empty orders
			if(isset($this->userId) && isset($this->orderCreationDate) && isset($this->orderStatus)
			&& !empty($this->userId) && !empty($this->orderCreationDate) && !empty($this->orderStatus))
			{
				$dal = new DAL();

				//prevent SQL injection
				$this->projectId = mysqli_real_escape_string($dal->getConn(), $this->projectId);
				$this->userId = mysqli_real_escape_string($dal->getConn(), $this->userId);
				$this->orderPersonal = mysqli_real_escape_string($dal->getConn(), $this->orderPersonal);
				$this->orderCreationDate = mysqli_real_escape_string($dal->getConn(), $this->orderCreationDate);
				$this->orderStatus = mysqli_real_escape_string($dal->getConn(), $this->orderStatus);

				//add order to DB
				$sql = "INSERT INTO bestelling (idproject, rnummer, persoonlijk, besteldatum, status) VALUES ('" . $this->projectId . "', '" . $this->userId . "', '" . $this->orderPersonal . "', '" . $this->orderCreationDate . "', '" . $this->orderStatus . "')";
				$dal->writeDB($sql);

				//get the automatically generated ID of the order
				$this->orderId = mysqli_insert_id($dal->getConn());
				echo $this->orderId;
			}
		}

		public function getProductsInOrder()
		{
			$dal = new DAL();

			//select all products of the order
			$sql = "SELECT * FROM bestellingproduct WHERE bestelnummer='".$this->orderId."'";
			$arrayproducts = $dal->queryDB($sql);

			//create OrderProduct for every product
			foreach($arrayproducts as $product)
			{
				$this->orderProducts[] = new OrderProduct($product->bestelnummer, $product->idproduct, $product->leverancier, $product->aantal, $product->prijs, $product->verzamelnaam, $product->defbestelnummer);
			}
		}

		public function printOrder()
		{
			//catch empty orders
			if(isset($this->orderProducts))
			{
				echo '<div class ="row">
							<div class="col-sm-2">
								<strong>Interne bestelnummer</strong>
								<p>'.$this->orderId.'</p>
							</div>
							<div class="col-sm-2">
								<strong>Status</strong>
								<p>'.$this->orderStatus.'</p>
							</div>
							<div class="col-sm-2">
								<strong>Persoonlijk gebruik</strong>
								<p>'. $this->orderPersonal .'</p>
							</div>
						</div>';

				foreach($this->orderProducts as $orderproduct)
				{
					$orderproduct->printOrderProduct();
				}
			}
		}
	}