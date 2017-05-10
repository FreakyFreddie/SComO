<?php
	//Shopping Cart class, interaction between database & shopping cart
	class ShoppingCart
	{
		private $userId;
		private $shoppingCartArticles;

		public function __construct($rnummer)
		{
			//set up connection
			$dal = new DAL();

			//prevent sql injection
			$this->userId = mysqli_real_escape_string($dal->getConn(), $rnummer);

			//get all products from user's shopping cart
			$this->getArticlesFromDB();

			//close the connection
			$dal->closeConn();
		}

		private function getArticlesFromDB()
		{
			//set up connection
			$dal = new DAL();

			$this->userId = mysqli_real_escape_string($dal->getConn(), $this->userId);

			//create array of parameters
			//first item = parameter types
			//i = integer
			//d = double
			//b = blob
			//s = string
			$parameters[0] = "s";
			$parameters[1] = $this->userId;

			//prepare statement
			//fetch user shopping cart articles
			$dal->setStatement("SELECT * FROM winkelwagen WHERE rnummer=?");
			$articles = $dal->queryDB($parameters);
			unset($parameters);

			//create ShoppingCartArticle for each record & add to array
			foreach($articles as $article)
			{
				$this->shoppingCartArticles[] = new ShoppingCartArticle($this->userId, $article->idproduct, $article->leverancier, $article->aantal, $article->prijs);
			}

			$dal->closeConn();
		}

		public function printShoppingCart()
		{
			//only print articles if there are articles in shopping cart
			if(isset($this->shoppingCartArticles) && ($this->shoppingCartArticles[0]->__get("productId") !=""))
			{
				$products = array();

				//print every article in the shopping cart
				foreach($this->shoppingCartArticles as $article)
				{
					$products[] = $article->printShoppingCartArticle();
				}

				return $products;
			}
		}

		public function printFinalShoppingCart()
		{
			//only print articles if there are articles in shopping cart
			if(isset($this->shoppingCartArticles) && ($this->shoppingCartArticles[0]->__get("productId") !=""))
			{
				//print every article in the shopping cart
				$products = array();

				foreach($this->shoppingCartArticles as $article)
				{
					$products[] = $article->printFinalShoppingCartArticle();
				}

				//print orderbutton
				echo '				</tbody>
								</table>
							</div>
						<div class = "row">
						<div class="col-sm-1">
							<a href="./winkelmandje.php" class="btn btn-primary">Terug</a>
						</div>
						<div class="col-sm-1">
							<button type="button" class="btn btn-primary" id="placeorder" value="placeorder" name="placeorder">Bestelling plaatsen</button>
						</div>
					</div>
				</div>';
			}
			//else print message
			else
			{
				echo '<div class="panel-heading">
						Er bevinden zich geen artikelen in uw winkelwagen
					</div>';
			}
		}

		public function addCartToOrders($orderpersonal, $project)
		{
			//if farnell product in shopping cart --> create farnell order

			$mousercount = 0;
			$farnellcount = 0;

			foreach($this->shoppingCartArticles as $article)
			{
				if($article->__get("Supplier") == "Mouser")
				{
					$mousercount ++;
				}
				if($article->__get("Supplier") == "Farnell")
				{
					$farnellcount++;
				}
			}

			//if mouser product in shopping cart -->  create mouser order
			if($mousercount > 0)
			{
				$mouserorder = $this->createOrder($orderpersonal, $project);


                foreach($this->shoppingCartArticles as $article)
				{
					if($article->__get("Supplier") == "Mouser")
					{

						$article->addArticleToOrder($mouserorder);
					}
				}
			}
			if($farnellcount > 0)
			{
				$farnellorder = $this->createOrder($orderpersonal, $project);

				foreach($this->shoppingCartArticles as $article)
				{
					if($article->__get("Supplier") == "Farnell")
					{

						$article->addArticleToOrder($farnellorder);
					}
				}
			}
		}

		//delete all products from the user's shopping cart
		public function emptyCart()
		{
			//set up connection
			$dal = new DAL();

			$this->userId = mysqli_real_escape_string($dal->getConn(), $this->userId);

			//create array of parameters
			//first item = parameter types
			//i = integer
			//d = double
			//b = blob
			//s = string
			$parameters[0] = "s";
			$parameters[1] = $this->userId;

			//prepare statement
			//delete articles from user shopping cart
			$dal->setStatement("DELETE FROM `winkelwagen` WHERE `rnummer`=?");
			$dal->writeDB($parameters);
			unset($parameters);

			$dal->closeConn();
		}

		//returns projectid & projectnaam of each project in DB
		public function getProjectsForUser()
		{
			$dal = new DAL();

			$this->userId = mysqli_real_escape_string($dal->getConn(), $this->userId);

			//create array of parameters
			//first item = parameter types
			//i = integer
			//d = double
			//b = blob
			//s = string
			$parameters[0] = "s";
			$parameters[1] = $this->userId;

			//prepare statement
			//delete articles from user shopping cart
			$dal->setStatement("SELECT gebruikerproject.idproject as idproject, project.titel as titel FROM gebruikerproject
				INNER JOIN project
				ON gebruikerproject.idproject = project.idproject
				WHERE gebruikerproject.rnummer=?");
			$records = $dal->queryDB($parameters);
			unset($parameters);

			$dal->closeConn();

			return $records;
		}

		private function createOrder($orderpersonal, $project)
		{
			if($orderpersonal == "persoonlijk")
			{
				$orderpersonal = "1";

				//create a new personal order
				$order = new Order($this->userId, $orderpersonal);
				$order->writeDB();
			}
			elseif($orderpersonal == "project")
			{
				$orderpersonal = "0";

				//extract projectid
				$project = explode(" - ", $project);
				$projectid = $project[0];

				$records = $this->getProjectsForUser();

				foreach($records as $record)
				{
					if($record->idproject == $projectid)
					{
						//create a new project order
						$order = new Order($this->userId, $orderpersonal, $projectid);
						$order->writeDB();
					}
				}
			}

			return $order;
		}
	}
?>