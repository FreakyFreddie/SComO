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
        $output2 = array();

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
			WHERE bestelling.status > 0
			GROUP BY datum
			ORDER BY datum DESC";
		$records = $dal->queryDBNoArgs($sql);

        $sql2 = "SELECT CAST(bestelling.besteldatum AS DATE) AS datum2, SUM((bestellingproduct.aantal * bestellingproduct.prijs)) AS totaal
			FROM bestelling
			INNER JOIN bestellingproduct
			ON bestelling.bestelnummer=bestellingproduct.bestelnummer
			WHERE bestelling.status > 0
			GROUP BY datum2
			ORDER BY datum2 DESC";
        $records2 = $dal->queryDBNoArgs($sql2);
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

            //restart a counter for the array
        $i = 0;

        //start counting at 0
        $totalweekprice = 0;

        //we need to print the amount spent this week

        //every week (e.g. count 7 days back from today, count 7 days back from that date) is a milestone date

        foreach($records2 as $key => $record2)
        {
            //exit the loop if we go over the max date
            if(date_diff($dates[$i],date_create($record2->datum2))->days > ($scale*$counter*7))
            {
                break;
            }

            //if the date is within one scale unit from a milestone date add the amount corresponding the date to the total for this week
            //if there is more than one scale unit between the milestone date & amount date, keep checking
            if(date_diff($dates[$i],date_create($record2->datum2))->days > $scale*7)
            {
                //security loop in case there was a week with 0 orders
                do
                {
                    //write amount for this week to array
                    $output2[$i]["amount"] = $totalweekprice;

                    //reset totalweekprice back to 0
                    $totalweekprice = 0;

                    //counter goes up
                    $i++;
                }
                while((date_diff($dates[$i],date_create($record2->datum2))->days > $scale*7));

                $totalweekprice = $totalweekprice + $record2->totaal;
            }
            else
            {
                $totalweekprice = $totalweekprice + $record2->totaal;
            }
        }

        //write amount for this week to array
        $output2[$i]["amount"] = $totalweekprice;

        //if counter has not reached the max yet, set all previous dates with amount 0
        if($i < 11)
        {
            $i++;
            for($i; $i <=11; $i++)
            {
                //write amount for this week to array
                $output2[$i]["amount"] = 0;
            }
        }

        //reverse the output
        $output2 = array_reverse($output2);

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
					},
					{
						"label": "My Second dataset",
						"fillColor" : "rgba(114, 114, 140, 0.2)",
						"strokeColor" : "rgba(114, 114, 140, 1)",
						"pointColor" : "rgba(114, 114, 140, 1)",
						"pointStrokeColor" : "#fff",
						"pointHighlightFill" : "#fff",
						"pointHighlightStroke" : "rgba(114, 114, 140, 1)",
						"data" : ['.$output2[0]["amount"].','.$output2[1]["amount"].','.$output2[2]["amount"].','.$output2[3]["amount"].','.$output2[4]["amount"].','.$output2[5]["amount"].','.$output2[6]["amount"].','.$output2[7]["amount"].','.$output2[8]["amount"].','.$output2[9]["amount"].','.$output2[10]["amount"].','.$output2[11]["amount"].']
					}
				]}';

	}
?>