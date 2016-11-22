<?php
	//article in shopping cart
	class ShoppingCartArticle
	{
		private $userId;
		private $productId;
		private $productSupplier;
		private $productAmount;
		private $productPrice;

		//product is object of Product class
		private $product;

		//don't use construct, if article was already in shopping cart database, we don't have to check with Farnell or Mouser DB
		public function __construct($uId, $pId, $pSupplier, $pAmount, $pPrice)
		{
			//set userid, id, supplier & amount (typecast amount string to int)
			$this->userId = $uId;
			$this->productId = $pId;
			$this->productSupplier = $pSupplier;
			$this->productAmount = $pAmount;
			$this->productPrice = $pPrice;
		}

		//returns property value
		public function __set($property, $value)
		{
			switch($property)
			{
				case "userId":
					$this->userId = $value;
					break;

				case "productId":
					$this->productId = $value;
					break;

				case "Supplier":
					$this->productSupplier = $value;
					break;

				case "Amount":
					$this->productAmount = $value;
					break;

				case "Price":
					$this->productPrice = $value;
					break;
			}
		}

		//returns property value
		public function __get($property)
		{
			$result = FALSE;
			switch($property)
			{
				case "userId":
					$result = $this->userId;
					break;

				case "productId":
					$result = $this->productId;
					break;

				case "Supplier":
					$result = $this->productSupplier;
					break;

				case "Amount":
					$result = $this->productAmount;
					break;

				case "Price":
					$result = $this->productPrice;
					break;
			}

			return $result;
		}

		public function printShoppingCartArticle()
		{
			//print shopping article in "table row" style 2x3 & 3x2
			echo '<div class="col-sm-2">'
					.$this->product->productName.
				'</div>
				<div class="col-sm-1">'
					.$this->product->productSupplier.
				'</div>
				<div class="col-sm-2">'
					.$this->product->productId.
				'</div>
				<div class="col-sm-2">'
					.$this->product->productVendor.
				'</div>			
				<div class="col-sm-1">
					<a href="'.$this->product->productDataSheet.'" target="_blank">Link</a>
				</div>
				<div class="col-sm-1">
					<img src="'.$this->product->productImage.'" />
				</div>
				<div class="col-sm-1">
					<form class="input-group" action="#">
						<input type="number" class="form-control" value="'.$this->productAmount.'" name="amountproduct" productid="'.$this->productId.'" supplier="'.$this->productSupplier.'">
					</form>
				</div>
				<div class="col-sm-1">
					<form class="input-group" action="#">
						<input type="number" class="form-control" value="'.$this->productAmount.'" name="amountproduct" productid="'.$this->productId.'" supplier="'.$this->productSupplier.'">
					</form>
				</div>';

		}
	}