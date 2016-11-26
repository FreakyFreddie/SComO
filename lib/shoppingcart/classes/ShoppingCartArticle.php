<?php
	//article in shopping cart, used to fill in a product that is extracted from the DB shopping cart
	class ShoppingCartArticle
	{
		private $userId;
		private $productId;
		private $productSupplier;
		private $productAmount;
		private $productPrice;

		//product is object of Product class
		private $product;

		//if a product is in the shopping cart, it is also in the database
		public function __construct($uId, $pId, $pSupplier, $pAmount, $pPrice)
		{
			//set userid, id, supplier & amount (typecast amount string to int)
			$this->userId = $uId;
			$this->productId = $pId;
			$this->productSupplier = $pSupplier;
			$this->productAmount = $pAmount;
			$this->productPrice = $pPrice;

			//get attributes from product (from DB, not external)
			$this->product = new Product();
			$this->product->fillFromDB($pId, $pSupplier);
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
			//print shopping article in "table row" style
			//if amount changes, AJAX script will keep track of update
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
				<div class="col-sm-1">
					<form class="input-group" action="#">
						<label for="amountproduct" class="sr-only">Producthoeveelheid</label>
						<input type="number" class="form-control amountproduct" value="'.$this->productAmount.'" name="amountproduct" productid="'.$this->product->__get("Id").'" supplier="'.$this->product->__get("Supplier").'" />
					</form>
				</div>
				<div class="col-sm-1">
					<form class="input-group" action="#">
						<button type="button" class="btn btn-danger deleteproduct" value="delete" name="deleteproduct" productid="'.$this->product->__get("Id").'" supplier="'.$this->product->__get("Supplier").'">Delete</button>
					</form>
				</div>
			</div>';
		}

		public function printFinalShoppingCartArticle()
		{
			//print shopping article in "table row" style
			//if amount changes, AJAX script will keep track of update
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

		public function addArticleToOrder()
		{

		}
	}
?>