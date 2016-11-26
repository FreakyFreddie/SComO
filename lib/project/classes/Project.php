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

			$sql = "INSERT INTO project (titel, budget, startdatum, vervaldatum, rekeningnummer) VALUES ('" . $this->projectTitle . "', '" . $this->productSupplier . "', '" . $this->projectFunding . "', '" .$this->projectStartDate . "', '" . $this->projectEndDate . "', '" . $this->projectAccountNumber . "')";
			$dal->writeDB($sql);

			//close the connection
			$dal->closeConn();
		}

		//assign the project to a user
		public function assignProject($userId)
		{

		}

		public function getProjectInfo()
		{

		}
	}

