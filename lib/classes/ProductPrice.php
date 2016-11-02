<?php
	class ProductPrice
	{
		public $productFromQuantity;
		public $productPriceQuantity;
		
		//__construct is called on each newly created object
		//if child class had __construct, parent __construct is not automatically called
		public function __construct($pFromQuantity, $pPriceQuantity)
		{
			$this->productFromQuantity = $pFromQuantity;
			$this->productPriceQuantity = $pPriceQuantity;
		}
	}
?>
