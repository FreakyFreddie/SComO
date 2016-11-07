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
		public function __construct($fpSKU, $fpDisplayName, $fpPrices, $fpStatus, $fpRohsStatusCode, $fpPackSize, $fpUnitOfMeasure, $fpID, $fpImage, $fpDataSheets, $fpInv, $fpVendorId, $fpVendorName, $fpBrandName, $fpTranslatedManufacturerPartNumber, $fpTranslatedMinimumOrderQuality, $fpStock, $fpCountryOfOrigin, $fpComingSoon, $fpTranslatedPrimaryCatalogPage, $fpPublishingModule, $fpVatHandlingCode, $fpReleaseStatusCode, $fpIsSpecialOrder, $fpIsAwaitingRelease, $fpReeling, $fpDiscountReason, $fpBrandId, $fpCommodityClassCode, $fpSupplier)
		{
			//call parent construct (product)
			parent::__construct($fpSKU, $fpDisplayName, $fpPrices, $fpVendorName, $fpInv, $fpImage, $fpDataSheets, $fpSupplier);
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
		
		public function printFarnellProduct()
		{
			echo '<div class="col-sm-4">
					<div class="shoparticle farnellproduct">
						<h3>'.$this->productName.'</h3>
						<img class="img-responsive" src='.$this->productImage.' alt="'.$this->productName.'" />
						<table class="table-striped table-hover">
							<tr>
								<td>
									Supplier
								</td>
								<td class="text-right">'.$this->productSupplier.'</td>
							</tr>
							<tr>
								<td>
									ID
								</td>
								<td class="text-right">'.$this->productID.'</td>
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
								<td class="text-right"><a href="'.$this->productDataSheet.'">Link</a></td>
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
						<td>< '.$productPrice->productFromQuantity.'</td>
						<td class="text-right">'.$productPrice->productPriceQuantity.'</td>
					</tr>';
			}			
			echo '</table>
					<form class="input-group fieldwithaddon" action="" method="post">
						<input type="number" class="form-control" value="1" name="amountproduct">
						<span class="input-group-addon">
							<button type="submit">
								<span class="glyphicon glyphicon-plus"></span>
							</button> 
						</span>
					</form>
				</div>
			</div>';
		}
	}
?>