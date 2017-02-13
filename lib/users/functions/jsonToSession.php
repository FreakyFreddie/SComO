<?php
	//add posted json info to the session array
	function jsonToSession($sessionarray, $postarray)
	{
		//count is the position where we add new objects
		$count = 0;

		if(isset($_SESSION[$sessionarray]))
		{
			$count = count($_SESSION[$sessionarray]);
		}

		//First sanitize the input
		foreach($postarray as $secondarray)
		{
			foreach($secondarray as $attname => $attribute)
			{
				//sanitize input
				$sanitizedattribute = validateInput($attribute);

				//add project to array in session
				$_SESSION[$sessionarray][$count][$attname] = $sanitizedattribute;
			}

			//increment count
			$count = $count + 1;
		}

		//list all element keys
		$keys = array();

		foreach($_SESSION[$sessionarray][0] as $key => $value)
		{
			$keys[] = $key;
		}

		//check array for duplicates (sample is array of element attributes)
		foreach($_SESSION[$sessionarray] as $index => $sample)
		{
			$match = 0;

			//search for matches
			foreach($_SESSION[$sessionarray] as $matchindex => $secondarray)
			{
				$truekeys = array();

				//check if this row matches all values of sample
				foreach($keys as $keyvalue)
				{
					if(($secondarray[$keyvalue] == $sample[$keyvalue]))
					{
						$truekeys[] = true;
					}
					else
					{
						$truekeys[] = false;
					}
				}

				//if row matches all sample values, we have a match
				for($i = 0; $i < count($keys); $i++)
				{
					//break if a false is encountered
					if($truekeys[$i] == false)
					{
						break;
					}

					//if the end of the array has been reached, we have a match
					if(($i == count($keys)-1) && ($truekeys[$i] == true))
					{
						$match++;
					}

					//if there is more then one match, remove the duplicates
					if($match > 1)
					{
						//remove element from array
						array_splice($_SESSION[$sessionarray], $matchindex, 1);

						//reset match to 1
						$match = 1;
					}
				}
			}
		}
	}
?>

