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
			$this->userId = mysqli_real_escape_string($this->dal->getConn(), $this->userId);

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
			$this->dal->setStatement("SELECT * FROM winkelwagen WHERE rnummer=?");
			$articles = $this->dal->queryDB($parameters);

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
				echo '<div class="panel-heading">
							<strong>
								Producten
							</strong>
						</div>
						<div class="panel-body">
						<div class="table-responsive">
							<table class="table">
								<thead>
									<tr>
										<th data-field="naam">Naam</th>
										<th data-field="leverancier">Leverancier</th>
										<th data-field="id">ID</th>
										<th data-field="fabrikant">fabrikant</th>
										<th data-field="datasheet">datasheet</th>
										<th data-field="afbeelding">afbeelding</th>
										<th data-field="prijs">prijs</th>
										<th data-field="hoeveelheid">hoeveelheid</th>
										<th data-field="delete">delete</th>
									</tr>
								</thead>
								<tbody>';
				//print every article in the shopping cart
				foreach($this->shoppingCartArticles as $article)
				{
					echo '<tr>';

					$article->printShoppingCartArticle();

					echo '</tr>';
				}

				//print orderbutton
				echo '			</tbody>
							</table>
						</div>
						<div class = "row">
							<div class="col-sm-4">
								<p>Deze bestelling is: </p>
								<input checked="true" class="orderpersoonlijk" type="radio" name="orderpersoonlijk" value="persoonlijk" /> Persoonlijk
								<input class="orderpersoonlijk" type="radio" name="orderpersoonlijk" value="project" /> Voor project
							</div>
							<div class="col-sm-4" id="projectlist">
								<p>Indien voor project, selecteer welk: </p>
								<select  class="form-control" id="selectproject" name="selectproject">';

										$projects = $this->getProjectsForUser($_SESSION["user"]->__get("userId"));
	
										foreach($projects as $project)
										{
											echo '<option value="'.$project->idproject.' - '.$project->titel.'">'.$project->idproject.' - '.$project->titel.'</option>';
										}

								echo '</select>
							</div>
							<div class="col-sm-4">
								<button type="button" id="orderproducts" class="btn btn-primary" value="orderproducts" name="orderproducts">Bestelling plaatsen</button>
							</div>
						</div>
					</div>
				';


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
				echo '<div class="panel-heading">
							<strong>
								Producten
							</strong>
						</div>
						<div class="panel-body">
						<div class="table-responsive">
							<table class="table">
								<thead>
									<tr>
										<th data-field="naam">Naam</th>
										<th data-field="leverancier">Leverancier</th>
										<th data-field="id">ID</th>
										<th data-field="fabrikant">fabrikant</th>
										<th data-field="datasheet">datasheet</th>
										<th data-field="afbeelding">afbeelding</th>
										<th data-field="prijs">prijs</th>
										<th data-field="hoeveelheid">hoeveelheid</th>
									</tr>
								</thead>
								<tbody>';
				//print every article in the shopping cart
				foreach($this->shoppingCartArticles as $article)
				{
					echo '<tr>';

					$article->printFinalShoppingCartArticle();

					echo '</tr>';
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
			$this->userId = mysqli_real_escape_string($this->dal->getConn(), $this->userId);

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
			$this->dal->setStatement("SELECT * FROM winkelwagen WHERE rnummer=?");
			$articles = $this->dal->queryDB($parameters);

			$sql = "DELETE FROM winkelwagen WHERE rnummer='".$this->userId."'";
			$dal->writeDB($sql);

			$dal->closeConn();
		}

		//returns projectid & projectnaam of each project in DB
		public function getProjectsForUser($userid)
		{
			$dal = new DAL();

			//still to exclude old projects
			$sql = "SELECT gebruikerproject.idproject as idproject, project.titel as titel FROM gebruikerproject
				INNER JOIN project
				ON gebruikerproject.idproject = project.idproject
				WHERE gebruikerproject.rnummer='".$userid."'";
			$records = $dal->queryDB($sql);

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

				//create a new project order
				$order = new Order($this->userId, $orderpersonal, $projectid);
				$order->writeDB();
			}

			return $order;
		}
	}
?>