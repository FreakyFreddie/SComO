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
			$articles = $this->dal()->queryDB($sql);

			//create ShoppingCartArticle for each record & add to array
			foreach($articles as $article)
			{
				$this->shoppingCartArticles[] = new ShoppingCartArticle($this->userId, $article["idproduct"], $article["leverancier"], $article["aantal"], $article["prijs"]);
			}
		}

		public function printShoppingCart()
		{
			//print every article in the shopping cart
			foreach($this->shoppingCartArticles as $article)
			{
				echo '<div class ="row">
						<div class="col-sm-3">
							<h3></h3>
						</div>
						<div class="col-sm-3">
							<h3></h3>
						</div>
					</div>
					<div class ="row">'
				.$article->printShoppingCartArticle().
				'</div>';
			}
		}
	}