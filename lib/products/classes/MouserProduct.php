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
		public $mproductDetailURL;
		public $mproductReeling;
		public $mproductROHSStatus;
		public $mproductSuggestedReplacement;
		public $mproductMultisimBlue;
		//can be non-existent
		public $mproductUnitWeightKg;
		public $mproductAttributes;
		
		//__construct is called on each newly created object
		//if child class had __construct, parent __construct is not automatically called, so add it in child __construct
		public function __construct($mpMouserPartNumber, $mpDescription, $mpPrices, $mpAvailability, $mpDataSheetURL, $mpImagePath, $mpCategory, $mpLeadTime, $mpLifeCycleStatus, $mpManufacturer, $mpManufacturerPartNumber, $mpMin, $mpMult, $mpDetailURL, $mpReeling, $mpROHSStatus, $mpSuggestedReplacement, $mpMultiSimBlue, $mpSupplier)
		{
			//call parent construct (product)
			parent::__construct($mpMouserPartNumber, $mpDescription, $mpPrices, $mpManufacturer, $mpAvailability, $mpImagePath, $mpDataSheetURL, $mpSupplier);
			$this->mproductCategory = $mpCategory;
			$this->mproductLeadTime = $mpLeadTime;
			$this->mproductLifeCycleStatus = $mpLifeCycleStatus;
			$this->mproductManufacturerPartNumber = $mpManufacturerPartNumber;
			$this->mproductMin = $mpMin;
			$this->mproductMult = $mpMult;
			$this->mproductDetailURL = $mpDetailURL;
			$this->mproductReeling = $mpReeling;
			$this->mproductROHSStatus = $mpROHSStatus;
			$this->mproductSuggestedReplacement = $mpSuggestedReplacement;
			$this->mproductMultisimBlue = $mpMultiSimBlue;
		}
		
		public function printMouserProduct()
		{
			echo '<div class="col-sm-4">
					<div class="shoparticle mouserproduct">
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