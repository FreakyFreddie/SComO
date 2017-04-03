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
		public function __construct($pId = "", $pName = "", $pPrices = "", $pVendor = "", $pInventory = "", $pImage = "", $pDataSheet = "", $pSupplier = "")
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
			switch ($property) {
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
			switch ($property) {
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
					$result = $this->productVendor;
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

		public function fillFromDB($pId, $pSupplier)
		{
			$dal = new DAL();

			//since there will be database interaction, we prevent SQL injection
			$this->productId = mysqli_real_escape_string($dal->getConn(), $pId);
			$this->productSupplier = mysqli_real_escape_string($dal->getConn(), $pSupplier);

			//create array of parameters
			//first item = parameter types
			//i = integer
			//d = double
			//b = blob
			//s = string
			$parameters[0] = "ss";
			$parameters[1] = $this->productId;
			$parameters[2] = $this->productSupplier;

			//prepare statement
			//get product from DB, matching ID & Supplier
			$dal->setStatement("SELECT idproduct, leverancier, productnaam, productverkoper, productafbeelding, productdatasheet FROM `product` WHERE idproduct=? AND leverancier=?");
			$product = $dal->queryDB($parameters);

			$dal->closeConn();

			//set Product attributes
			$this->productId = $product[0]->idproduct;
			$this->productName = $product[0]->productnaam;
			$this->productSupplier = $product[0]->leverancier;
			$this->productVendor = $product[0]->productverkoper;
			$this->productImage = $product[0]->productafbeelding;
			$this->productDataSheet = $product[0]->productdatasheet;
		}

		//this function writes a product to the database if it does not exist yet
		public function writeDB()
		{
			$dal = new DAL();

			//since there will be database interaction, we prevent SQL injection
			$this->productId = mysqli_real_escape_string($dal->getConn(), $this->productId);
			$this->productName = mysqli_real_escape_string($dal->getConn(), $this->productName);
			$this->productSupplier = mysqli_real_escape_string($dal->getConn(), $this->productSupplier);
			$this->productVendor = mysqli_real_escape_string($dal->getConn(), $this->productVendor);
			$this->productImage = mysqli_real_escape_string($dal->getConn(), $this->productImage);
			$this->productDataSheet = mysqli_real_escape_string($dal->getConn(), $this->productDataSheet);

			//create array of parameters
			//first item = parameter types
			//i = integer
			//d = double
			//b = blob
			//s = string
			$parameters[0] = "ss";
			$parameters[1] = $this->productId;
			$parameters[2] = $this->productSupplier;

			//prepare statement
			//first check if the product already exists in the DB
			$dal->setStatement("SELECT idproduct FROM `product` WHERE idproduct=? AND leverancier=?");
			$dal->queryDB($parameters);

			//if product doesn't exist yes, we add it
			if ($dal->getNumResults() < 1)
			{
				//create array of parameters
				//first item = parameter types
				//i = integer
				//d = double
				//b = blob
				//s = string
				$parameters[0] = "ssssss";
				$parameters[1] = $this->productId;
				$parameters[2] = $this->productSupplier;
				$parameters[3] = $this->productName;
				$parameters[4] = $this->productVendor;
				$parameters[5] = $this->productImage;
				$parameters[6] = $this->productDataSheet;

				$dal->setStatement("INSERT INTO product (idproduct, leverancier, productnaam, productverkoper, productafbeelding, productdatasheet) VALUES (?, ?, ?, ?, ?, ?)");
				$dal->writeDB($parameters);
			}

			//close the connection
			$dal->closeConn();
		}

		public function extractProductPrice($productAmount)
		{
			$productPriceForAmount = NULL;

			//extract the product price for the given amount
			for ($i = 0; $i < count($this->productPrices); $i++)
			{
				//if user goes above the highest Quantity, the last price is valid
				if ($productAmount < $this->productPrices[count($this->productPrices) - 1]->__get("Quantity")) {
					//when productAmount is between two Quantities, the price for the lowest Quantity is valid
					if ($productAmount >= $this->productPrices[$i]->__get("Quantity") && $productAmount <= $this->productPrices[$i + 1]->__get("Quantity")) {
						//break loop from the moment we have a match (if not, we end up using the price for the highest amount)
						$productPriceForAmount = $this->productPrices[$i]->__get("Price");
						break;
					}
				} else {
					//we end up using the price for the highest amount
					$productPriceForAmount = $this->productPrices[$i]->__get("Price");
				}
			}

			//if $productPriceForAmount is not an float, it is an object, so exit with error
			if (!is_float($productPriceForAmount))
			{
				//throw error
				echo "Er ging iets mis, probeer opnieuw";
				exit();
			}

			return $productPriceForAmount;
		}
	}
?>