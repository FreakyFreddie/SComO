<?php
	//needs Product class
	class FarnellProduct extends Product
	{
		public $fproductStatus;
		public $fproductRohsStatusCode;
		public $fproductPackSize;
		public $fproductUnitOfMeasure;
		public $fproductId;
		public $fproductVendorId;
		public $fproductBrandName;
		public $fproductTranslatedManufacturerPartNumber;
		public $fproductTranslatedMinimumOrderQuality;
		public $fproductStock;
		public $fproductTranslatedPrimaryCatalogPage;
		public $fproductCountryOfOrigin;
		public $fproductComingSoon;
		public $fproductPublishingModule;
		public $fproductVatHandlingCode;
		public $fproductReleaseStatusCode;
		public $fproductIsSpecialOrder;
		public $fproductIsAwaitingRelease;
		public $fproductReeling;
		public $fproductDiscountReason;
		public $fproductBrandId;
		public $fproductCommodityClassCode;
		
		//__construct is called on each newly created object
		//if child class had __construct, parent __construct is not automatically called, so add it in child __construct
		public function __construct($fpSKU, $fpDisplayName, $fpPrices, $fpStatus, $fpRohsStatusCode, $fpPackSize, $fpUnitOfMeasure, $fpID, $fpVendorId, $fpBrandName, $fpTranslatedManufacturerPartNumber, $fpTranslatedMinimumOrderQuality, $fpStock, $fpCountryOfOrigin, $fpComingSoon, $fpTranslatedPrimaryCatalogPage, $fpPublishingModule, $fpVatHandlingCode, $fpReleaseStatusCode, $fpIsSpecialOrder, $fpIsAwaitingRelease, $fpReeling, $fpDiscountReason, $fpBrandId, $fpCommodityClassCode, $fpSupplier)
		{
			//call parent construct (product)
			parent::__construct($fpSKU, $fpDisplayName, $fpSupplier, $fpPrices);
			$this->fproductStatus = $fpStatus;
			$this->fproductRohsStatusCode = $fpRohsStatusCode;
			$this->fproductPackSize = $fpPackSize;
			$this->fproductUnitOfMeasure = $fpUnitOfMeasure;
			$this->fproductId = $fpID;
			$this->fproductVendorId = $fpVendorId;
			$this->fproductBrandName = $fpBrandName;
			$this->fproductTranslatedManufacturerPartNumber = $fpTranslatedManufacturerPartNumber;
			$this->fproductTranslatedMinimumOrderQuality = $fpTranslatedMinimumOrderQuality;
			$this->fproductStock = $fpStock;
			$this->fproductTranslatedPrimaryCatalogPage = $fpTranslatedPrimaryCatalogPage;
			$this->fproductCountryOfOrigin = $fpCountryOfOrigin;
			$this->fproductComingSoon = $fpComingSoon;
			$this->fproductPublishingModule = $fpPublishingModule;
			$this->fproductVatHandlingCode = $fpVatHandlingCode;
			$this->fproductReleaseStatusCode = $fpReleaseStatusCode;
			$this->fproductIsSpecialOrder = $fpIsSpecialOrder;
			$this->fproductIsAwaitingRelease = $fpIsAwaitingRelease;
			$this->fproductReeling = $fpReeling;
			$this->fproductDiscountReason = $fpDiscountReason;
			$this->fproductBrandId = $fpBrandId;
			$this->fproductCommodityClassCode = $fpCommodityClassCode;
		}
	}
?>