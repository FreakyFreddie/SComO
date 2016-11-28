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

		private $product;

		public function __construct($oid, $pid, $psupplier, $pamount, $pprice, $collection="", $finalonumber="")
		{
			$this->finalOrderNumber = $finalonumber;
			$this->orderId = $oid;
			$this->productId = $pid;
			$this->productSupplier = $psupplier;
			$this->productAmount = $pamount;
			$this->productPrice = $pprice;
			$this->collection = $collection;

			//get extra info on product
			$this->getProductInfo();
		}

		public function writeDB()
		{
			$dal = new DAL();

			//prevent SQL injection
			$this->finalOrderNumber = mysqli_real_escape_string($dal->getConn(), $this->finalOrderNumber);
			//is int
			//$this->orderId = mysqli_real_escape_string($dal->getConn(), $this->orderId);
			$this->productId = mysqli_real_escape_string($dal->getConn(), $this->productId);
			$this->productSupplier = mysqli_real_escape_string($dal->getConn(), $this->productSupplier);
			$this->productAmount = mysqli_real_escape_string($dal->getConn(), $this->productAmount);
			$this->productPrice = mysqli_real_escape_string($dal->getConn(), $this->productPrice);
			$this->collection = mysqli_real_escape_string($dal->getConn(), $this->collection);

			//add order to DB (still to complete)
			$sql = "INSERT INTO bestellingproduct (defbestelnummer, bestelnummer, idproduct, leverancier, aantal, prijs, verzamelnaam) VALUES ('" . $this->finalOrderNumber . "', '" . $this->orderId . "', '" . $this->productId . "', '" . $this->productSupplier . "', '" . $this->productAmount. "', '" . $this->productPrice. "', '" . $this->collection. "')";
			$dal->writeDB($sql);
		}

		public function getProductInfo()
		{
			$this->product = new Product();
			$this->product->fillFromDB($this->productId, $this->productSupplier);
		}

		public function printOrderProduct()
		{
			//print orders in "table row" style
			echo '<div class ="row">
				<div class="col-sm-2">'
				.$this->product->__get("Name").
				'</div>
				<div class="col-sm-2">'
				.$this->product->__get("Supplier").
				'</div>
				<div class="col-sm-1">'
				.$this->product->__get("Id").
				'</div>
				<div class="col-sm-1">'
				.$this->product->__get("Vendor").
				'</div>			
				<div class="col-sm-1">
					<a href="'.$this->product->__get("DataSheet").'" target="_blank">Link</a>
				</div>
				<div class="col-sm-2">
					<img class="img img-responsive" src="'.$this->product->__get("Image").'" />
				</div>
				<div class="col-sm-1">'
					.$this->productPrice.
				'</div>
				<div class="col-sm-1">'
					.$this->productAmount.
				'</div>
			</div>';
		}
	}