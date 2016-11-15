<?php
	//Strip unnecessary characters (extra space, tab, newline) from the user input data (with the PHP trim() function)
    //Remove backslashes (\) from the user input data (with the PHP stripslashes() function)
	//change special chars like < to &lt etc.
	function validateInput($data)
	{
		$data = trim($data);
		$data = stripslashes($data);
		$data = htmlspecialchars($data);
		
		return $data;
	}
	
	//function validates if maildomain is whitelisted, returns boolean
	function validateDomain($mail, $whitelist)
	{
		$pass=FALSE;
		
		foreach($whitelist as $domain)
		{
			if($mail == $domain)
			{
				$pass = TRUE;
			}
		}

		return $pass;
	}

	//process data, different function in to use different REGEX
	function validateNaam($data)
	{
		$data = validateInput($data);
		
		//Naam needs to be one or more words, sometimes separated by a - (ex. An-sophie)
		if(preg_match("/^[a-zA-Z -]+$/", $data))
		{
			//make all letters lowercase
			$data = strtolower($data);
			
			//make first letter of every word capital
			$data = ucwords($data);

			return $data;
		}
		else
		{
			echo "<p>Een naam kan enkel letters, spaties en - bevatten.</p>";
		}
	}
	
	function validateRNummer($data, $idletters)
	{
		$data = validateInput($data);
		
		//rnummer needs to be one letter (r, s, u, ...) , followed bij 7 digits
		if(preg_match("/^[$idletters][0-9][0-9][0-9][0-9][0-9][0-9][0-9]$/", $data))
		{
			//make all letters lowercase
			$data = strtolower($data);
			
			return $data;
		}
		else
		{
			echo "<p>Een rnummer moet steeds beginnen met één van volgende letters: $idletters gevolgd door 7 cijfers</p>";
		}
	}
	
	function validateMail($data)
	{
		$data = validateInput($data);
		
		return $data;
	}
	
	function validateWachtWoord($data)
	{
		$data = validateInput($data);
		
		return $data;
	}
	
?>