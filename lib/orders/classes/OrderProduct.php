<?php

	class OrderProduct
	{
		private $finalOrderNumber;
		private $orderId;
		private $productId;
		private $productSupplier;
		private $productAmount;
		private $productPrice;
		private $collection;

		public function __construct($oid, $pid, $psupplier, $pamount, $pprice, $collection="", $finalonumber="")
		{
			$this->finalOrderNumber = $finalonumber;
			$this->orderId = $oid;
			$this->productId = $pid;
			$this->productSupplier = $psupplier;
			$this->productAmount = $pamount;
			$this->productPrice = $pprice;
			$this->collection = $collection;
		}

		public function writeDB()
		{
			$dal = new DAL();

			//prevent SQL injection
			$this->finalOrderNumber = mysqli_real_escape_string($dal->getConn(), $this->finalOrderNumber);
			$this->orderId = mysqli_real_escape_string($dal->getConn(), $this->orderId);
			$this->productId = mysqli_real_escape_string($dal->getConn(), $this->productId);
			$this->productSupplier = mysqli_real_escape_string($dal->getConn(), $this->productSupplier);
			$this->productAmount = mysqli_real_escape_string($dal->getConn(), $this->productAmount);
			$this->productPrice = mysqli_real_escape_string($dal->getConn(), $this->productPrice);
			$this->collection = mysqli_real_escape_string($dal->getConn(), $this->collection);

			//add order to DB (still to complete)
			$sql = "INSERT INTO bestellingproduct (defbestelnummer, bestelnummer, idproduct, leverancier, aantal, prijs, verzamelnaam) VALUES ('" . $this->finalOrderNumber . "', '" . $this->orderId . "', '" . $this->productId . "', '" . $this->productSupplier . "', '" . $this->productAmount. "', '" . $this->productPrice. "', '" . $this->collection. "')";
			$dal->writeDB($sql);
		}
	}