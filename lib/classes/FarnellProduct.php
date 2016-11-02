<?php
	//needs Product class
	class FarnellProduct extends Product
	{
		public $fproductPackSize;
		public $fproductUnitOfMeasure;
		public $fproductID;
		public $fproductBrandName;
		public $fproductTranslatedManufacturerPartNumber;
		public $fproductTranslatedMinimumOrderQuality;
		public $fproductTranslatedPrimaryCatalogPage;
		public $fproductPublishingModule;
		public $fproductVatHandlingCode;
		public $fproductDiscountReason;
		
		//__construct is called on each newly created object
		//if child class had __construct, parent __construct is not automatically called, so add it in child __construct
		public function __construct($fpSKU, $fpDisplayName, $fpSupplier, $fpPrices, $fpPackSize, $fpUnitOfMeasure, $fpID, $fpBrandName, $fpTranslatedManufacturerPartNumber, $fpTranslatedMinimumOrderQuality, $fpTranslatedPrimaryCatalogPage, $fpPublishingModule, $fpVatHandlingCode, $fpDiscountReason)
		{
			//call parent construct (product)
			parent::__construct($fpSKU, $fpDisplayName, $fpSupplier, $fpPrices);
			$this->fproductPackSize = $fpPackSize;
			$this->fproductUnitOfMeasure = $fpUnitOfMeasure;
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