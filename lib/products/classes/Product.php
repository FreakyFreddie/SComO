<?php
	//needs ProductPrice class
	class Product
	{
		protected $productId;
		protected $productName;
		protected $productPrices = array();
		protected $productVendor;
		protected $productInventory;
		protected $productImage;
		protected $productDataSheet;
		protected $productSupplier;
		
		//__construct is called on each newly created object
		//use getters & setters to check values and handle errors better
		//if child class had __construct, parent __construct is not automatically called
		public function __construct($pId="", $pName="", $pPrices="", $pVendor="", $pInventory="", $pImage="", $pDataSheet="", $pSupplier="")
		{
			$this->productId = $pId;
			$this->productName = $pName;
			$this->productPrices = $pPrices;
			$this->productVendor = $pVendor;
			$this->productInventory = $pInventory;
			$this->productImage = $pImage;
			$this->productDataSheet = $pDataSheet;
			$this->productSupplier = $pSupplier;
		}
		
		public function __set($property, $value)
		{
			switch($property)
			{
				case "Id":
				$this->productId = $value;
				break;
				
				case "Name":
				$this->productName = $value;
				break;
				
				case "Prices":
				$this->productPrices = $value;
				break;
				
				case "Vendor":
				$this->productVendor = $value;
				break;
				
				case "Inventory":
				$this->productInventory = $value;
				break;
				
				case "Image":
				$this->productImage = $value;
				break;
				
				case "DataSheet":
				$this->productDataSheet = $value;
				break;
				
				case "Supplier":
				$this->productSupplier = $value;
				break;
			}
		}
		
		public function __get($property)
		{
			$result = FALSE;
			switch($property)
			{
				case "Id":
				$result = $this->productId;
				break;
				
				case "Name":
				$result = $this->productName;
				break;
				
				case "Prices":
				$result = $this->productPrices;
				break;
				
				case "Vendor":
				$this->productVendor;
				break;
				
				case "Inventory":
				$result = $this->productInventory;
				break;
				
				case "Image":
				$result = $this->productImage;
				break;
				
				case "DataSheet":
				$result = $this->productDataSheet;
				break;
				
				case "Supplier":
				$result = $this->productSupplier;
				break;
			}
			return $result;
		}
	}
?>