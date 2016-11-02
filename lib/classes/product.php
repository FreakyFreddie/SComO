<?php
	//needs ProductPrice class
	class Product
	{
		public $productID;
		public $productName;
		public $productSupplier;
		public $productPrices = array();
		
		//__construct is called on each newly created object
		//if child class had __construct, parent __construct is not automatically called
		public function __construct($pID, $pName, $pSupplier, $pPrices)
		{
			$this->productID = $pID;
			$this->productName = $pName;
			$this->productSupplier = $pSupplier;
			$this->productPrices = $pPrices;
		}
	}
?>