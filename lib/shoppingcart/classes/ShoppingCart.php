<?php
	//Shopping Cart class, interaction between database & shopping cart
	class ShoppingCart
	{
		private $userId;
		private $shoppingCartArticles;
		private $dal;

		public function __construct($rnummer)
		{
			//set up connection
			$this->dal = new DAL();

			//prevent sql injection
			$this->userId = mysqli_real_escape_string($this->dal->getConn(), $rnummer);

			//get all products from user's shopping cart
			$this->getArticlesFromDB();

			//close the connection
			$this->dal->closeConn();
		}

		private function getArticlesFromDB()
		{
			//select all products in shopping cart
			$sql = "SELECT * FROM winkelwagen WHERE rnummer='".$this->userId."'";
			$articles = $this->dal->queryDB($sql);

			//create ShoppingCartArticle for each record & add to array
			foreach($articles as $article)
			{
				$this->shoppingCartArticles[] = new ShoppingCartArticle($this->userId, $article->idproduct, $article->leverancier, $article->aantal, $article->prijs);
			}
		}

		public function printShoppingCart()
		{
			//only print articles if there are articles in shopping cart
			if(isset($this->shoppingCartArticles) && ($this->shoppingCartArticles[0]->__get("productId") !=""))
			{
				echo '<div class ="row">
						<div class="col-sm-2">
							<strong>Product</strong>
						</div>
						<div class="col-sm-2">
							<strong>Leverancier</strong>
						</div>
						<div class="col-sm-1">
							<strong>ID</strong>
						</div>
						<div class="col-sm-1">
							<strong>Verkoper</strong>
						</div>
						<div class="col-sm-1">
							<strong>Datasheet</strong>
						</div>
						<div class="col-sm-2">
							<strong>Afbeelding</strong>
						</div>
						<div class="col-sm-1">
							<strong>Prijs</strong>
						</div>
						<div class="col-sm-1">
							<strong>Hoeveelheid</strong>
						</div>
					</div>';
				//print every article in the shopping cart
				foreach($this->shoppingCartArticles as $article)
				{
					$article->printShoppingCartArticle();
				}

				//print orderbutton
				echo '<div class = "row">
					<div class="col-sm-1">
						<button type="button" id="orderproducts" class="btn btn-primary" value="orderproducts" name="orderproducts">Bestelling plaatsen</button>
					</div>
				</div>';
			}
			//else print message
			else
			{
				echo "Er bevinden zich geen artikelen in uw winkelwagen";
			}
		}

		public function printFinalShoppingCart()
		{
			//only print articles if there are articles in shopping cart
			if(isset($this->shoppingCartArticles) && ($this->shoppingCartArticles[0]->__get("productId") !=""))
			{
				echo '<div class ="row">
						<div class="col-sm-2">
							<strong>Product</strong>
						</div>
						<div class="col-sm-2">
							<strong>Leverancier</strong>
						</div>
						<div class="col-sm-1">
							<strong>ID</strong>
						</div>
						<div class="col-sm-1">
							<strong>Verkoper</strong>
						</div>
						<div class="col-sm-1">
							<strong>Datasheet</strong>
						</div>
						<div class="col-sm-2">
							<strong>Afbeelding</strong>
						</div>
						<div class="col-sm-1">
							<strong>Prijs</strong>
						</div>
						<div class="col-sm-1">
							<strong>Hoeveelheid</strong>
						</div>
					</div>';
				//print every article in the shopping cart
				foreach($this->shoppingCartArticles as $article)
				{
					$article->printFinalShoppingCartArticle();
				}

				//print orderbutton
				echo '<div class = "row">
					<div class="col-sm-1">
						<a href="./winkelmandje.php" class="btn btn-primary">Terug</a>
					</div>
					<div class="col-sm-1">
						<button type="button" class="btn btn-primary" id="placeorder" value="placeorder" name="placeorder">Bestelling plaatsen</button>
					</div>
				</div>';
			}
			//else print message
			else
			{
				echo "Er bevinden zich geen artikelen in uw winkelwagen";
			}
		}

		public function addCartToOrders($orderpersonal)
		{
			//create a new order, indicate if it's personal or for a project
			$order = new Order($this->userId, $orderpersonal);
			$order->writeDB();

			foreach($this->shoppingCartArticles as $article)
			{
				$article->addArticleToOrder($order);
			}
		}

		//delete all products from the user's shopping cart
		public function emptyCart()
		{
			$dal = new DAL();

			$sql = "DELETE FROM winkelwagen WHERE rnummer='".$this->userId."'";
			$dal->writeDB($sql);

			$dal->closeConn();
		}
	}