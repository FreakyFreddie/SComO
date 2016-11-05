<?php
	//needs ProductPrice class
	class Product
	{
		public $productID;
		public $productName;
		public $productPrices = array();
		public $productVendor;
		public $productInventory;
		public $productImage;
		public $productDataSheet;
		public $productSupplier;
		
		//__construct is called on each newly created object
		//if child class had __construct, parent __construct is not automatically called
		public function __construct($pID, $pName, $pPrices, $pVendor, $pInventory, $pImage, $pDataSheet, $pSupplier)
		{
			$this->productID = $pID;
			$this->productName = $pName;
			$this->productPrices = $pPrices;
			$this->productVendor = $pVendor;
			$this->productInventory = $pInventory;
			$this->productImage = $pImage;
			$this->productDataSheet = $pDataSheet;
			$this->productSupplier = $pSupplier;
		}
	}
?>