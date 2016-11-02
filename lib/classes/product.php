<?php
	class ProductPrice
	{
		public $productToQuantity;
		public $productFromQuantity;
		public $productPriceQuantity;
		
		//__construct is called on each newly created object
		//if child class had __construct, parent __construct is not automatically called
		public function __construct($pToQuantity, $pFromQuantity, $pPriceQuantity)
		{
			$this->productToQuantity = $pToQuantity;
			$this->productFromQuantity = $pFromQuantity;
			$this->productPriceQuantity = $pPriceQuantity;
		}
	}
?>

<?php
	class Product
	{
		public $productID;
		public $productName;
		public $productBrand;
		public $productSupplier;
		public $productPrices = array();
		
		//__construct is called on each newly created object
		//if child class had __construct, parent __construct is not automatically called
		public function __construct($pID, $pName, $pBrand, $pSupplier, $pPrices)
		{
			$this->productID = $pID;
			$this->productName = $pName;
			$this->productBrand = $pBrand;
			$this->productSupplier = $pSupplier;
			$this->productPrices = $pPrices;
		}
	}
?>