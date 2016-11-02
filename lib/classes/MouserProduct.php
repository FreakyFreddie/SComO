<?php
	//needs Product class
	class MouserProduct extends Product
	{
		public $mproductAvailability;
		public $mproductDataSheetURL;
		public $mproductImagePath;
		public $mproductCategory;
		public $mproductLeadTime;
		public $mproductLifeCycleStatus;
		public $mproductManufacturer;
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
		public function __construct($mpMouserPartNumber, $mpDescription, $mpSupplier, $mpPrices, $mpAvailability, $mpDataSheetURL, $mpImagePath, $mpCategory, $mpLeadTime, $mpLifeCycleStatus, $mpManufacturer, $mpManufacturerPartNumber, $mpMin, $mpMult, $mpAttributes, $mpDetailURL, $mpReeling, $mpROHSStatus, $mpSuggestedReplacement, $mpMultiSimBlue, $mpUnitWeightKg)
		{
			//call parent construct (product)
			parent::__construct($mpMouserPartNumber, $mpDescription, $mpSupplier, $mpPrices);
			$this->mproductAvailability = $mpAvailability;
			$this->mproductDataSheetURL = $mpDataSheetURL;
			$this->fproductID = $fpID;
			$this->fproductBrandName = $fpBrandName;
			$this->fproductTranslatedManufacturerPartNumber = $fpTranslatedManufacturerPartNumber;
			$this->fproductTranslatedMinimumOrderQuality = $fpTranslatedMinimumOrderQuality;
			$this->fproductTranslatedPrimaryCatalogPage = $fpTranslatedPrimaryCatalogPage;
			$this->fproductPublishingModule = $fpPublishingModule;
			$this->fproductVatHandlingCode = $fpVatHandlingCode;
			$this->fproductDiscountReason = $fpDiscountReason;
		}
	}
?>