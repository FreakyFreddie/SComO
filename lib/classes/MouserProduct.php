<?php
	//needs Product class
	class MouserProduct extends Product
	{
		public $mproductCategory;
		public $mproductLeadTime;
		public $mproductLifeCycleStatus;
		public $mproductManufacturerPartNumber;
		public $mproductMin;
		public $mproductMult;
		public $mproductAttributes;
		public $mproductDetailURL;
		public $mproductReeling;
		public $mproductROHSStatus;
		public $mproductSuggestedReplacement;
		public $mproductMultisimBlue;
		public $mproductUnitWeightKg;
		
		//__construct is called on each newly created object
		//if child class had __construct, parent __construct is not automatically called, so add it in child __construct
		public function __construct($mpMouserPartNumber, $mpDescription, $mpPrices, $mpAvailability, $mpDataSheetURL, $mpImagePath, $mpCategory, $mpLeadTime, $mpLifeCycleStatus, $mpManufacturer, $mpManufacturerPartNumber, $mpMin, $mpMult, $mpAttributes, $mpDetailURL, $mpReeling, $mpROHSStatus, $mpSuggestedReplacement, $mpMultiSimBlue, $mpUnitWeightKg, $mpSupplier)
		{
			//call parent construct (product)
			parent::__construct($mpMouserPartNumber, $mpDescription, $mpPrices, $mpManufacturer, $mpAvailability, $mpImagePath, $mpDataSheetURL, $mpSupplier);
			$this->mproductCategory = $mpCategory;
			$this->mproductLeadTime = $mpLeadTime;
			$this->mproductLifeCycleStatus = $mpLifeCycleStatus;
			$this->mproductManufacturerPartNumber = $mpManufacturerPartNumber;
			$this->mproductMin = $mpMin;
			$this->mproductMult = $mpMult;
			$this->mproductAttributes = $mpAttributes;
			$this->mproductDetailURL = $mpDetailURL;
			$this->mproductReeling = $mpReeling;
			$this->mproductROHSStatus = $mpROHSStatus;
			$this->mproductSuggestedReplacement = $mpSuggestedReplacement;
			$this->mproductMultisimBlue = $mpMultiSimBlue;
			$this->mproductUnitWeightKg = $mpUnitWeightKg;
		}
	}
?>