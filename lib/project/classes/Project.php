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

			$sql = "INSERT INTO project (titel, budget, startdatum, vervaldatum, rekeningnr) VALUES ('" . $this->projectTitle . "', '" . $this->projectFunding . "', '" .$this->projectStartDate . "', '" . $this->projectEndDate . "', '" . $this->projectAccountNumber . "')";
			$dal->writeDB($sql);

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

			$sql = "SELECT * FROM project WHERE idproject = '" .$this->projectId . "'";
			$records = $dal->queryDB($sql);

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

			$sql = "UPDATE project SET titel='".$this->projectTitle."', budget='".$this->projectFunding."', rekeningnr='".$this->projectAccountNumber."', startdatum='".$this->projectStartDate."', vervaldatum='".$this->projectEndDate."' WHERE idproject='".$this->projectId."'";
			$dal->writeDB($sql);

			//close the connection
			$dal->closeConn();
		}

		public function deleteFromDB()
		{
			$dal = new DAL();

			$this->projectId = mysqli_real_escape_string($dal->getConn(), $this->projectId);

			//delete project from DB
			$sql = "DELETE FROM project WHERE idproject = '".$this->projectId."'";
			$dal->writeDB($sql);

			//close the connection
			$dal->closeConn();
		}

		public function removeParticipants()
		{
			$dal = new DAL();

			$this->projectId = mysqli_real_escape_string($dal->getConn(), $this->projectId);

			//delete link to participants
			$sql = "DELETE FROM gebruikerproject WHERE idproject = '".$this->projectId."'";
			$dal->writeDB($sql);


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

