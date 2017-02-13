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
				//make orderstatus understandable
				switch($this->orderStatus)
				{
					case "0":
						$orderstatus = "geweigerd";
						break;

					case "1":
						$orderstatus = "pending";
						break;

					case "2":
						$orderstatus = "goedgekeurd";
						break;

					case "3":
						$orderstatus = "besteld";
						break;

					case "4":
						$orderstatus = "aangekomen";
						break;

					case "5":
						$orderstatus = "afgehaald";
						break;
				}

				if($this->orderPersonal == 1)
				{
					$personal = " / persoonlijk";
				}
				elseif($this->orderPersonal == 0)
				{
					$dal = new DAL();

					//prevent SQL injection
					$this->projectId = mysqli_real_escape_string($dal->getConn(), $this->projectId);

					//select title of the project
					$sql = "SELECT titel FROM project WHERE idproject='".$this->projectId."'";
					$records = $dal->queryDB($sql);

					$personal = " / ".$records[0]->titel;
				}
				echo '<div class="panel-heading" data-bestelnummer="'.$this->orderId.'">
							<strong>
								Order #'.$this->orderId.' / '.$orderstatus.$personal.' / '.$this->orderCreationDate.'
							</strong>
						</div>
						<div class="panel-body">
						<div class="table-responsive">
							<table class="table">
								<thead>
									<tr>
										<th data-field="naam">Naam</th>
										<th data-field="leverancier">Leverancier</th>
										<th data-field="id">ID</th>
										<th data-field="fabrikant">fabrikant</th>
										<th data-field="datasheet">datasheet</th>
										<th data-field="afbeelding">afbeelding</th>
										<th data-field="prijs">prijs</th>
										<th data-field="hoeveelheid">hoeveelheid</th>
									</tr>
								</thead>
								<tbody>
						';

				foreach($this->orderProducts as $orderproduct)
				{
					echo '<tr>';

					$orderproduct->printOrderProduct();

					echo '</tr>';
				}

				echo '			</tbody>
							</table>
						</div>
					</div>';
			}
		}
	}