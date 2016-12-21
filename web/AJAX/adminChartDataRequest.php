<?php
	//this script processes the AJAX request and returns data for the chart

	//load config, typecast to object for easy access
	$GLOBALS['settings'] = (object) parse_ini_file('../../config/config.ini', true);

	//include classes BEFORE session_start because we might need them in session
	//include DAL (DAL & login always go on top since classes depend on them)
	require $GLOBALS['settings']->Folders['root'].'../lib/database/classes/DAL.php';

	//include login class
	require $GLOBALS['settings']->Folders['root'].'../lib/users/classes/Login.php';

	//include Product class
	require $GLOBALS['settings']->Folders['root'].'../lib/products/classes/Product.php';

	//include Shopping Cart & ShoppingCartArticle
	require $GLOBALS['settings']->Folders['root'].'../lib/shoppingcart/classes/ShoppingCart.php';
	require $GLOBALS['settings']->Folders['root'].'../lib/shoppingcart/classes/ShoppingCartArticle.php';

	session_start();

	//redirect if user is not logged in as admin
	if(!isset($_SESSION["user"]) OR $_SESSION["user"]->__get("loggedIn") != TRUE OR $_SESSION["user"]->__get("permissionLevel") != 2)
	{
		header("location: ../index.php");
	}

	//check login condition and if the request contains all info
	if(isset($_SESSION["user"]) && $_SESSION["user"]->__get("loggedIn") && $_SESSION["user"]->__get("permissionLevel") == 2)
	{
		$dal = new DAL();

		//get today's date
		date_default_timezone_set('Europe/Brussels');

		//set the graph scale (higher scale = more weeks in between milestones)
		$scale= 1 ;

		//set milestone dates, note that they are DateTime objects
		//first date +23h & 59mins since we want the whole day included
		$dates[0] = date_create(date("Y-n-j"));

		//fill in all dates
		for($counter = 1; $counter <= 11; $counter++)
		{
			$dates[$counter] = date_create(date("Y-n-j", strtotime("-".($counter*$scale)." week")));
		}

		//create output array: milestone date + total amount for that week
		$output = array();

		//prepare the milestone dates for output
		foreach($dates as $key => $date)
		{
			$output[$key]["date"] = $date;
		}

		//returns the amount of products ordered per day, most recent first
		//I prefer this method over querying the data for every week individually (more overhead)
		$sql = "SELECT CAST(bestelling.besteldatum AS DATE) AS datum, SUM(bestellingproduct.aantal) AS aantal
			FROM bestellingproduct
			INNER JOIN bestelling
			ON bestelling.bestelnummer=bestellingproduct.bestelnummer
			GROUP BY datum
			ORDER BY datum DESC";
		$records = $dal->queryDB($sql);
		$dal->closeConn();

		//start a counter for the array
		$i = 0;

		//start counting at 0 items ordered this week
		$totalweek = 0;

		//we need to print the amount of products ordered per week
		//every week (e.g. count 7 days back from today, count 7 days back from that date) is a milestone date
		foreach($records as $key => $record)
		{
			//exit the loop if we go over the max date
			if(date_diff($dates[$i],date_create($record->datum))->days > ($scale*$counter*7))
			{
				break;
			}

			//if the date is within one scale unit from a milestone date add the amount corresponding the date to the total for this week
			//if the there is more than one scale unit between the the milestone date & amount date, keep checking
			if(date_diff($dates[$i],date_create($record->datum))->days > $scale*7)
			{
				//security loop in case there was a week with 0 orders
				do
				{
					//write the date as a string
					$output[$i]["date"] = $output[$i]["date"]->format('Y-m-d');

					//write amount for this week to array
					$output[$i]["amount"] = $totalweek;

					//reset totalweek back to 0
					$totalweek = 0;

					//counter goes up
					$i = $i+1;
				}
				while((date_diff($dates[$i],date_create($record->datum))->days > $scale*7));

				$totalweek = $totalweek + $record->aantal;
			}
			else
			{
				$totalweek = $totalweek + $record->aantal;
			}
		}

		//process the data for the last week
		//write the date as a string
		$output[$i]["date"] = $output[$i]["date"]->format('Y-m-d');

		//write amount for this week to array
		$output[$i]["amount"] = $totalweek;

		//if counter has not reached the max yet, set all previous dates with amount 0
		if($i < 11)
		{
			$i++;
			for($i; $i <=11; $i++)
			{
				$output[$i]["date"] = $output[$i]["date"]->format('Y-m-d');

				//write amount for this week to array
				$output[$i]["amount"] = 0;
			}
		}

		//reverse the output
		$output = array_reverse($output);

		//print the info to create a JSON object with javascript
		echo '{"labels" : ["'.$output[0]["date"].'","'.$output[1]["date"].'","'.$output[2]["date"].'","'.$output[3]["date"].'","'.$output[4]["date"].'","'.$output[5]["date"].'","'.$output[6]["date"].'","'.$output[7]["date"].'","'.$output[8]["date"].'","'.$output[9]["date"].'","'.$output[10]["date"].'","'.$output[11]["date"].'"],
				"datasets" : [
					{
						"label": "My First dataset",
						"fillColor" : "rgba(48, 164, 255, 0.2)",
						"strokeColor" : "rgba(48, 164, 255, 1)",
						"pointColor" : "rgba(48, 164, 255, 1)",
						"pointStrokeColor" : "#fff",
						"pointHighlightFill" : "#fff",
						"pointHighlightStroke" : "rgba(48, 164, 255, 1)",
						"data" : ['.$output[0]["amount"].','.$output[1]["amount"].','.$output[2]["amount"].','.$output[3]["amount"].','.$output[4]["amount"].','.$output[5]["amount"].','.$output[6]["amount"].','.$output[7]["amount"].','.$output[8]["amount"].','.$output[9]["amount"].','.$output[10]["amount"].','.$output[11]["amount"].']
					}
				]}';
	}
?>