<?php
	//needs Product class
	class MouserProduct extends Product
	{
		private $mproductCategory;
		private $mproductLeadTime;
		private $mproductLifeCycleStatus;
		private $mproductManufacturerPartNumber;
		private $mproductMin;
		private $mproductMult;
		private $mproductDetailURL;
		private $mproductReeling;
		private $mproductROHSStatus;
		private $mproductSuggestedReplacement;
		private $mproductMultisimBlue;
		private $mproductUnitWeightKg;
		private $mproductAttributes;
		
		//__construct is called on each newly created object
		//if child class had __construct, parent __construct is not automatically called, so add it in child __construct
		public function __construct($mpMouserPartNumber="", $mpDescription="", $mpPrices="", $mpAvailability="", $mpDataSheetURL="", $mpImagePath="./img/not_found.jpg", $mpCategory="", $mpLeadTime="", $mpLifeCycleStatus="", $mpManufacturer="", $mpManufacturerPartNumber="", $mpMin="", $mpMult="", $mpDetailURL="", $mpReeling="", $mpROHSStatus="", $mpSuggestedReplacement="", $mpMultiSimBlue="", $mpSupplier="Mouser")
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
		
		public function __set($property, $value)
		{
			parent::__set($property, $value);
			switch($property)
			{
				case "Category":
				$this->mproductCategory = $value;
				break;
				
				case "LeadTime":
				$this->mproductLeadTime = $value;
				break;
				
				case "LifeCycleStatus":
				$this->mproductLifeCycleStatus = $value;
				break;
				
				case "ManufacturerPartNumber":
				$this->mproductManufacturerPartNumber = $value;
				break;
				
				case "Min":
				$this->mproductMin = $value;
				break;
				
				case "Mult":
				$this->mproductMult = $value;
				break;
				
				case "DetailURL":
				$this->mproductDetailURL = $value;
				break;
				
				case "Reeling":
				$this->mproductReeling = $value;
				break;
				
				case "ROHSStatus":
				$this->mproductROHSStatus = $value;
				break;
				
				case "SuggestedReplacement":
				$this->mproductSuggestedReplacement = $value;
				break;
				
				case "MultiSimBlue":
				$this->mproductMultisimBlue = $value;
				break;
				
				case "UnitWeightKg":
				$this->mproductUnitWeightKg = $value;
				break;
				
				case "Attributes":
				$this->mproductAttributes = $value;
				break;
			}
		}
		
		public function __get($property)
		{
			$result = parent::__get($property);
			switch($property)
			{
				case "Category":
				$result = $this->mproductCategory;
				break;
				
				case "LeadTime":
				$result = $this->mproductLeadTime;
				break;
				
				case "LifeCycleStatus":
				$result = $this->mproductLifeCycleStatus;
				break;
				
				case "ManufacturerPartNumberr":
				$result = $this->mproductManufacturerPartNumber;
				break;
				
				case "Min":
				$result = $this->mproductMin;
				break;
				
				case "Mult":
				$result = $this->mproductMult;
				break;
				
				case "DetailURL":
				$result = $this->mproductDetailURL;
				break;
				
				case "Reeling":
				$result = $this->mproductReeling;
				break;
				
				case "ROHSStatus":
				$result = $this->mproductROHSStatus;
				break;
				
				case "SuggestedReplacement":
				$result = $this->mproductSuggestedReplacement;
				break;
				
				case "MultisimBlue":
				$result = $this->mproductMultisimBlue;
				break;
				
				case "UnitWeightKg":
				$result = $this->mproductUnitWeightKg;
				break;
				
				case "Attributes":
				$result = $this->mproductAttributes;
				break;
			}
			return $result;
		}
		
		public function printMouserProduct()
		{
			echo '<div class="col-sm-4">
					<div class="shoparticle mouserproduct">
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
						<td>< '.$productPrice->__get("Quantity").'</td>
						<td class="text-right">'.$productPrice->__get("Price").'</td>
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