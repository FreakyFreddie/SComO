<?php
	//needs Product class
	class FarnellProduct extends Product
	{
		private $fproductStatus;
		private $fproductRohsStatusCode;
		private $fproductPackSize;
		private $fproductUnitOfMeasure;
		private $fproductFullId;
		private $fproductVendorId;
		private $fproductBrandName;
		private $fproductTranslatedManufacturerPartNumber;
		private $fproductTranslatedMinimumOrderQuality;
		private $fproductStock;
		private $fproductTranslatedPrimaryCatalogPage;
		private $fproductCountryOfOrigin;
		private $fproductComingSoon;
		private $fproductPublishingModule;
		private $fproductVatHandlingCode;
		private $fproductReleaseStatusCode;
		private $fproductIsSpecialOrder;
		private $fproductIsAwaitingRelease;
		private $fproductReeling;
		private $fproductDiscountReason;
		private $fproductBrandId;
		private $fproductCommodityClassCode;
		
		//__construct is called on each newly created object
		//if child class had __construct, parent __construct is not automatically called, so add it in child __construct
		public function __construct($fpSKU="", $fpDisplayName="", $fpPrices="", $fpStatus="", $fpRohsStatusCode="", $fpPackSize="", $fpUnitOfMeasure="", $fpId="", $fpImage="./img/not_found.jpg", $fpDataSheets="", $fpInv="", $fpVendorId="", $fpVendorName="", $fpBrandName="", $fpTranslatedManufacturerPartNumber="", $fpTranslatedMinimumOrderQuality="", $fpStock="", $fpCountryOfOrigin="", $fpComingSoon="", $fpTranslatedPrimaryCatalogPage="", $fpPublishingModule="", $fpVatHandlingCode="", $fpReleaseStatusCode="", $fpIsSpecialOrder="", $fpIsAwaitingRelease="", $fpReeling="", $fpDiscountReason="", $fpBrandId="", $fpCommodityClassCode="", $fpSupplier="Farnell")
		{
			//call parent construct (product)
			parent::__construct($fpSKU, $fpDisplayName, $fpPrices, $fpVendorName, $fpInv, $fpImage, $fpDataSheets, $fpSupplier);
			$this->fproductStatus = $fpStatus;
			$this->fproductRohsStatusCode = $fpRohsStatusCode;
			$this->fproductPackSize = $fpPackSize;
			$this->fproductUnitOfMeasure = $fpUnitOfMeasure;
			$this->fproductId = $fpId;
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
		
		public function __set($property, $value)
		{
			parent::__set($property, $value);
			switch($property)
			{
				case "Status":
				$this->fproductStatus = $value;
				break;
				
				case "ROHSStatusCode":
				$this->fproductRohsStatusCode = $value;
				break;
				
				case "PackSize":
				$this->fproductPackSize = $value;
				break;
				
				case "UnitOfMeasure":
				$this->fproductUnitOfMeasure = $value;
				break;
				
				case "FullId":
				$this->fproductFullId = $value;
				break;
				
				case "VendorId":
				$this->fproductVendorId = $value;
				break;
				
				case "BrandName":
				$this->fproductBrandName = $value;
				break;
				
				case "TranslatedManufacturerPartNumber":
				$this->fproductTranslatedManufacturerPartNumber = $value;
				break;
				
				case "TranslatedMinimumOrderQuality":
				$this->fproductTranslatedMinimumOrderQuality = $value;
				break;
				
				case "Stock":
				$this->fproductStock = $value;
				break;
				
				case "TranslatedPrimaryCatalogPage":
				$this->fproductTranslatedPrimaryCatalogPage = $value;
				break;
				
				case "CountryOfOrigin":
				$this->fproductCountryOfOrigin = $value;
				break;
				
				case "ComingSoon":
				$this->fproductComingSoon = $value;
				break;
				
				case "PublishingModule":
				$this->fproductPublishingModule = $value;
				break;
				
				case "VatHandlingCode":
				$this->fproductVatHandlingCode = $value;
				break;
				
				case "ReleaseStatusCode":
				$this->fproductReleaseStatusCode = $value;
				break;
				
				case "IsSpecialOrder":
				$this->fproductIsSpecialOrder = $value;
				break;
				
				case "IsAwaitingRelease":
				$this->fproductIsAwaitingRelease = $value;
				break;
				
				case "Reeling":
				$this->fproductReeling = $value;
				break;
				
				case "DiscountReason":
				$this->fproductDiscountReason = $value;
				break;
				
				case "BrandId":
				$this->fproductBrandId = $value;
				break;
				
				case "CommodityClassCode":
				$this->fproductCommodityClassCode = $value;
				break;
			}
		}
		
		public function __get($property)
		{
			$result = parent::__get($property);
			switch($property)
			{
				case "Status":
				$result = $this->fproductStatus;
				break;
				
				case "RohsStatusCode":
				$result = $this->fproductRohsStatusCode;
				break;
				
				case "PackSize":
				$result = $this->fproductPackSize;
				break;
				
				case "UnitOfMeasure":
				$result = $this->fproductUnitOfMeasure;
				break;
				
				case "Id":
				$result = $this->fproductId;
				break;
				
				case "VendorId":
				$result = $this->fproductVendorId;
				break;
				
				case "BrandName":
				$result = $this->fproductBrandName;
				break;
				
				case "TranslatedManufacturerPartNumber":
				$result = $this->fproductTranslatedManufacturerPartNumber;
				break;
				
				case "TranslatedMinimumOrderQuality":
				$result = $this->fproductTranslatedMinimumOrderQuality;
				break;
				
				case "Stock":
				$result = $this->fproductStock;
				break;
				
				case "TranslatedPrimaryCatalogPage":
				$result = $this->fproductTranslatedPrimaryCatalogPage;
				break;
				
				case "CountryOfOrigin":
				$result = $this->fproductCountryOfOrigin;
				break;
				
				case "ComingSoon":
				$result = $this->fproductComingSoon;
				break;
				
				case "PublishingModule":
				$result = $this->fproductPublishingModule;
				break;
				
				case "VatHandlingCode":
				$result = $this->fproductVatHandlingCode;
				break;
				
				case "ReleaseStatusCode":
				$result = $this->fproductReleaseStatusCode;
				break;
				
				case "IsSpecialOrder":
				$result = $this->fproductIsSpecialOrder;
				break;
				
				case "IsAwaitingRelease":
				$result = $this->fproductIsAwaitingRelease;
				break;
				
				case "Reeling":
				$result = $this->fproductReeling;
				break;
				
				case "DiscountReason":
				$result = $this->fproductDiscountReason;
				break;
				
				case "BrandId":
				$result = $this->fproductBrandId;
				break;
				
				case "CommodityClassCode":
				$result = $this->fproductCommodityClassCode;
				break;
			}
			return $result;
		}
		
		public function printFarnellProduct()
		{
			echo '<div class="col-sm-4">
					<div class="shoparticle farnellproduct">
						<h3>'.$this->productName.'</h3>
						<img class="img-responsive" src='.$this->productImage.' alt="'.$this->productName.'" />
						<table class="table-striped table-hover">
							<tr>
								<th>
									Supplier
								</th>
								<th class="text-right">'.$this->productSupplier.'</th>
							</tr>
							<tr>
								<td>
									Id
								</td>
								<td class="text-right">'.$this->productId.'</td>
							</tr>
							<tr>
								<td>
									Vendor
								</td>
								<td class="text-right">'.$this->productVendor.'</td>
							</tr>
							<tr>
								<td>
									Inventory
								</td>
								<td class="text-right">'.$this->productInventory.'</td>
							</tr>
							<tr>
								<td>
									DataSheet
								</td>
								<td class="text-right"><a href="'.$this->productDataSheet.'" target="_blank">Link</a></td>
							</tr>
						</table>
						<br />
						<table class="table-striped table-hover">
							<tr>
								<th>
									Quantity
								</th>
								<th class="text-right">
									Price
								</th>
							</tr>';
			foreach($this->productPrices as $productPrice)
			{
				echo '<tr>
						<td>> '.$productPrice->__get("Quantity").'</td>
						<td class="text-right">'.$productPrice->__get("Price").'</td>
					</tr>';
			}			
			echo '</table>
					<form class="input-group" action="#">
						<input type="number" class="form-control" value="1" name="amountproduct" productid="'.$this->productId.'" supplier="'.$this->productSupplier.'">
						<span class="input-group-btn">
							<button class="btn btn-secondary productbutton" type="button">
								<span class="glyphicon glyphicon-plus"></span>
							</button>
						</span>
					</form>
				</div>
			</div>';
		}
	}
?>