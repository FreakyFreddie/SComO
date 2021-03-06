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
	function validateDomain($mail)
	{
		$pass=FALSE;
		
		foreach($GLOBALS["settings"]->Whitelist["mail"] as $domain)
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
        $data=filter_var($data, FILTER_SANITIZE_STRING, FILTER_FLAG_STRIP_LOW);
		
		//Naam needs to be one or more words, sometimes separated by a - (ex. An-sophie)
		if(preg_match("/^[A-z ,.'-]+$/", $data))
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
			return FALSE;
		}
	}
	
	function validateRNummer($data)
	{
		$data = validateInput($data);
		
		//rnummer needs to be one letter (r, s, u, ...) , followed bij 7 digits
		if(preg_match("/^[".$GLOBALS["settings"]->Whitelist["idletters"]."][0-9][0-9][0-9][0-9][0-9][0-9][0-9]$/", $data))
		{
			//make all letters lowercase
			$data = strtolower($data);
			
			return $data;
		}
		else
		{
			echo "<p>Een rnummer moet steeds beginnen met één van volgende letters: ".$GLOBALS["settings"]->Whitelist["idletters"]." gevolgd door 7 cijfers</p>";
			return FALSE;
		}
	}
	
	function validateMail($data)
	{
		$data = validateInput($data);

		//split data & test rnummer & domain
		$splitdata= explode("@", $data);

		//check if rnummer & maildomain are valid
		if($splitdata[0]==validateRNummer($splitdata[0]) && validateDomain($splitdata[1]))
		{
			return $data;
		}
		else
		{
			echo "<p>Niet ondersteund emailadres.</p>";
			return FALSE;
		}
	}
	
	function validateWachtWoord($data)
	{
		$data = validateInput($data);
        $data = filter_var($data, FILTER_SANITIZE_STRING, FILTER_FLAG_STRIP_LOW);

		//password must contain at least 8 characters or numbers(a-z, A-Z, 0-9)
		if(strlen($data) > 7)
		{
			return $data;
		}
		else
		{
			echo "<p>Een wachtwoord moet bestaan uit ten minste 8 karakters.</p>";
			return FALSE;
		}
	}
	
?>