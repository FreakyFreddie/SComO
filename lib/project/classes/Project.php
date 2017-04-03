<?php

	class Project
	{
		//id is decided by database (auto increment)
		private $projectId;
		private $projectTitle;
		private $projectFunding;
		private $projectStartDate;
		private $projectEndDate;
		private $projectAccountNumber;

		public function __construct($ptitle="", $pfunding="", $pstartdate="", $penddate="", $paccountnumber="")
		{
			$this->projectTitle = $ptitle;
			$this->projectFunding = $pfunding;
			$this->projectStartDate = $pstartdate;
			$this->projectEndDate =$penddate;
			$this->projectAccountNumber = $paccountnumber;
		}

		public function writeDB()
		{
			$dal = new DAL();

			$this->projectTitle = mysqli_real_escape_string($dal->getConn(), $this->projectTitle);
			$this->projectFunding = mysqli_real_escape_string($dal->getConn(), $this->projectFunding);
			$this->projectStartDate = mysqli_real_escape_string($dal->getConn(), $this->projectStartDate);
			$this->projectEndDate = mysqli_real_escape_string($dal->getConn(), $this->projectEndDate);
			$this->projectAccountNumber = mysqli_real_escape_string($dal->getConn(), $this->projectAccountNumber);

			//create array of parameters
			//first item = parameter types
			//i = integer
			//d = double
			//b = blob
			//s = string
			$parameters[0] = "sdsss";
			$parameters[1] = $this->projectTitle;
			$parameters[2] = $this->projectFunding;
			$parameters[3] = $this->projectStartDate;
			$parameters[4] = $this->projectEndDate;
			$parameters[5] = $this->projectAccountNumber;

			//prepare statement
			//create project in db
			$dal->setStatement("INSERT INTO project (titel, budget, startdatum, vervaldatum, rekeningnr) VALUES (?, ?, ?, ?, ?)");
			$dal->writeDB($parameters);
			unset($parameters);

			//close the connection
			$dal->closeConn();
		}

		public function __set($property, $value)
		{
			switch ($property)
			{
				case "Id":
					$this->projectId = $value;
					break;

				case "Title":
					$this->projectTitle = $value;
					break;

				case "Funding":
					$this->projectFunding = $value;
					break;

				case "StartDate":
					$this->projectStartDate = $value;
					break;

				case "EndDate":
					$this->projectEndDatet = $value;
					break;

				case "AccountNumber":
					$this->projectAccountNumber = $value;
					break;
			}
		}

		public function __get($property)
		{
			$result = false;
			switch ($property)
			{
				case "Id":
					$result = $this->projectId;
					break;

				case "Title":
					$result = $this->projectTitle;
					break;

				case "Funding":
					$result = $this->projectFunding;
					break;

				case "StartDate":
					$result = $this->projectStartDate;
					break;

				case "EndDate":
					$result = $this->projectEndDate;
					break;

				case "AccountNumber":
					$result = $this->projectAccountNumber;
					break;
			}

			return $result;
		}

		public function getFromDB()
		{
			$dal = new DAL();

			//database input, counter injection
			$this->projectId = mysqli_real_escape_string($dal->getConn(), $this->projectId);

			//create array of parameters
			//first item = parameter types
			//i = integer
			//d = double
			//b = blob
			//s = string
			$parameters[0] = "i";
			$parameters[1] = $this->projectId;

			//prepare statement
			//check if project already exists in db
			$dal->setStatement("SELECT * FROM project WHERE idproject =?");
			$records = $dal->queryDB($parameters);
			unset($parameters);

			//fill attributes if record is found
			if($dal->getNumResults() == 1)
			{
				$this->projectTitle = $records[0]->titel;
				$this->projectFunding = $records[0]->budget;
				$this->projectStartDate = $records[0]->startdatum;
				$this->projectEndDate = $records[0]->vervaldatum;
				$this->projectAccountNumber = $records[0]->rekeningnr;
			}

			//close the connection
			$dal->closeConn();
		}

		public function updateDB()
		{
			$dal = new DAL();

			$this->projectId = mysqli_real_escape_string($dal->getConn(), $this->projectId);
			$this->projectTitle = mysqli_real_escape_string($dal->getConn(), $this->projectTitle);
			$this->projectFunding = mysqli_real_escape_string($dal->getConn(), $this->projectFunding);
			$this->projectStartDate = mysqli_real_escape_string($dal->getConn(), $this->projectStartDate);
			$this->projectEndDate = mysqli_real_escape_string($dal->getConn(), $this->projectEndDate);
			$this->projectAccountNumber = mysqli_real_escape_string($dal->getConn(), $this->projectAccountNumber);

			//create array of parameters
			//first item = parameter types
			//i = integer
			//d = double
			//b = blob
			//s = string
			$parameters[0] = "sdsssi";
			$parameters[1] = $this->projectTitle;
			$parameters[2] = $this->projectFunding;
			$parameters[3] = $this->projectStartDate;
			$parameters[4] = $this->projectEndDate;
			$parameters[5] = $this->projectAccountNumber;
			$parameters[6] = $this->projectId;

			//prepare statement
			//write project update to db
			$dal->setStatement("UPDATE project SET titel=?, budget=?, startdatum=?, vervaldatum=?, rekeningnr=? WHERE idproject=?");
			$dal->writeDB($parameters);
			unset($parameters);

			//close the connection
			$dal->closeConn();
		}

		public function deleteFromDB()
		{
			$dal = new DAL();

			$this->projectId = mysqli_real_escape_string($dal->getConn(), $this->projectId);

			//create array of parameters
			//first item = parameter types
			//i = integer
			//d = double
			//b = blob
			//s = string
			$parameters[0] = "i";
			$parameters[1] = $this->projectId;

			//prepare statement
			//delete project from DB
			$dal->setStatement("DELETE FROM project WHERE idproject = ?");
			$dal->writeDB($parameters);
			unset($parameters);

			//close the connection
			$dal->closeConn();
		}

		public function removeParticipants()
		{
			$dal = new DAL();

			$this->projectId = mysqli_real_escape_string($dal->getConn(), $this->projectId);

			//create array of parameters
			//first item = parameter types
			//i = integer
			//d = double
			//b = blob
			//s = string
			$parameters[0] = "i";
			$parameters[1] = $this->projectId;

			//prepare statement
			//delete link between project & users from DB
			$dal->setStatement("DELETE FROM gebruikerproject WHERE idproject = ?");
			$dal->writeDB($parameters);
			unset($parameters);

			//close the connection
			$dal->closeConn();
		}

		public function toJSON()
		{
			return json_encode(
				array(
					'projectTitle' => $this->projectTitle,
					'projectFunding' => $this->projectFunding,
					'projectStartDate' => $this->projectStartDate,
					'projectEndDate' => $this->projectEndDate,
					'projectAccountNumber' => $this->projectAccountNumber
				)
			);
		}
	}

