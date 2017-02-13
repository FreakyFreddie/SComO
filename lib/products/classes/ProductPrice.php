<?php
	class ProductPrice
	{
		private $productFromQuantity;
		private $productPriceQuantity;
		
		//__construct is called on each newly created object
		//if child class had __construct, parent __construct is not automatically called
		public function __construct($pFromQuantity="", $pPriceQuantity="")
		{
			$this->productFromQuantity = (int) $pFromQuantity;
			$this->productPriceQuantity = (float) $pPriceQuantity;
		}
		
		public function __set($property, $value)
		{
			switch($property)
			{
				case "Quantity":
				$this->productFromQuantity = (int) $value;
				break;
				
				case "Price":
				$this->productPriceQuantity = (float) $value;
				break;
			}
		}
		
		public function __get($property)
		{
			switch($property)
			{
				case "Quantity":
				$result = $this->productFromQuantity;
				break;
				
				case "Price":
				$result = $this->productPriceQuantity;
				break;
			}
			return $result;
		}
	}
?>
