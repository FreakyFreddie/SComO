<?php
	function getMouserProducts($keyword='sword', $offset=0, $numberofresults=20, $mouserAPI)
	{
		/*
			MOUSER SOAP 1.2 REQUEST EXAMPLE
			
			POST /service/searchapi.asmx HTTP/1.1
			Host: www.mouser.be
			Content-Type: application/soap+xml; charset=utf-8
			Content-Length: length

			<?xml version="1.0" encoding="utf-8"?>
			<soap12:Envelope xmlns:xsi="http://www.w3.org/2001/XMLSchema-instance" xmlns:xsd="http://www.w3.org/2001/XMLSchema" xmlns:soap12="http://www.w3.org/2003/05/soap-envelope">
			  <soap12:Header>
				<MouserHeader xmlns="http://api.mouser.com/service">
				  <AccountInfo>
					<PartnerID>string</PartnerID>
				  </AccountInfo>
				</MouserHeader>
			  </soap12:Header>
			  <soap12:Body>
				<SearchByKeyword xmlns="http://api.mouser.com/service">
				  <keyword>string</keyword>
				  <records>int</records>
				  <startingRecord>int</startingRecord>
				  <searchOptions>ID</searchOptions>
				</SearchByKeyword>
			  </soap12:Body>
			</soap12:Envelope>
		*/

		//set up the SOAP client
		$soapclient = new SoapClient('http://api.mouser.com/service/searchapi.asmx?WSDL', array('soap_version' => SOAP_1_2, 'trace' => true));
		
		//PartnerID (API key) needed or mouser login, we use API key
		$headerparam = array('AccountInfo'=>array('PartnerID'=>$mouserAPI));
		
		//create the SOAP request header
		$header = new SoapHeader('http://api.mouser.com/service', 'MouserHeader', $headerparam);
		
		//set the header of the SOAP request
		$soapclient->__setSoapHeaders($header);
		
		//set the searchterm
		$search = array('keyword' => $keyword, 'records' => $numberofresults, 'startingRecord' => $offset, 'searchOptions' => '');
		
		/*
			MOUSER SOAP 1.2 RESPONSE EXAMPLE
			
			HTTP/1.1 200 OK
			Content-Type: application/soap+xml; charset=utf-8
			Content-Length: length

			<?xml version="1.0" encoding="utf-8"?>
			<soap12:Envelope xmlns:xsi="http://www.w3.org/2001/XMLSchema-instance" xmlns:xsd="http://www.w3.org/2001/XMLSchema" xmlns:soap12="http://www.w3.org/2003/05/soap-envelope">
			  <soap12:Body>
				<SearchByKeywordResponse xmlns="http://api.mouser.com/service">
				  <SearchByKeywordResult>
					<NumberOfResult>int</NumberOfResult>
					<Parts>
					  <MouserPart>
						<Availability>string</Availability>
						<DataSheetUrl>string</DataSheetUrl>
						<Description>string</Description>
						<ImagePath>string</ImagePath>
						<Category>string</Category>
						<LeadTime>string</LeadTime>
						<LifecycleStatus>string</LifecycleStatus>
						<Manufacturer>string</Manufacturer>
						<ManufacturerPartNumber>string</ManufacturerPartNumber>
						<Min>string</Min>
						<Mult>string</Mult>
						<MouserPartNumber>string</MouserPartNumber>
						<ProductAttributes xsi:nil="true" />
						<PriceBreaks xsi:nil="true" />
						<ProductDetailUrl>string</ProductDetailUrl>
						<Reeling>boolean</Reeling>
						<ROHSStatus>string</ROHSStatus>
						<SuggestedReplacement>string</SuggestedReplacement>
						<MultiSimBlue>int</MultiSimBlue>
						<UnitWeightKg xsi:nil="true" />
					  </MouserPart>
					  <MouserPart>
						<Availability>string</Availability>
						<DataSheetUrl>string</DataSheetUrl>
						<Description>string</Description>
						<ImagePath>string</ImagePath>
						<Category>string</Category>
						<LeadTime>string</LeadTime>
						<LifecycleStatus>string</LifecycleStatus>
						<Manufacturer>string</Manufacturer>
						<ManufacturerPartNumber>string</ManufacturerPartNumber>
						<Min>string</Min>
						<Mult>string</Mult>
						<MouserPartNumber>string</MouserPartNumber>
						<ProductAttributes xsi:nil="true" />
						<PriceBreaks xsi:nil="true" />
						<ProductDetailUrl>string</ProductDetailUrl>
						<Reeling>boolean</Reeling>
						<ROHSStatus>string</ROHSStatus>
						<SuggestedReplacement>string</SuggestedReplacement>
						<MultiSimBlue>int</MultiSimBlue>
						<UnitWeightKg xsi:nil="true" />
					  </MouserPart>
					</Parts>
				  </SearchByKeywordResult>
				</SearchByKeywordResponse>
			  </soap12:Body>
			</soap12:Envelope>
		*/
		
		//send SOAP request, results in stdClass object
		$soapresult = $soapclient->SearchByKeyword($search);
		
		//create mouser product array
		$mouserProducts = array();
		
		foreach($soapresult->SearchByKeywordResult->Parts as $key => $soapproduct)
		{
			
			//ignore numberofresults, only need products
			//create array of product prices & quantities
			
			foreach($soapproduct as $pattributes)
			{
				//print_r($pattributes);
				$prices = array();
				foreach($pattributes->PriceBreaks as $pPriceBreaks)
				{
					//print_r($pPriceBreaks);
					//if there more than one Price, $pPriceBreaks is an array
					if(is_array($pPriceBreaks))
					{
						foreach($pPriceBreaks as $pPrice)
						{
							//print_r($pPrice);
							//var_dump($pPrice);
							$price = new ProductPrice();
						
							if(isset($pPrice->Quantity) && isset($pPrice->Price))
							{
								$price->__set("Quantity", $pPrice->Quantity);
								$price->__set("Price", $pPrice->Price);
							}
							
							$prices[]= $price;

						}
					}
					//avoid looping through the attributes of a Price if there is only one
					else
					{
						//var_dump($pPrice);
						$price = new ProductPrice();
						
						if(isset($pPriceBreaks->Quantity) && isset($pPriceBreaks->Price))
						{
							$price->__set("Quantity", $pPriceBreaks->Quantity);
							$price->__set("Price", $pPriceBreaks->Price);
						}

						$prices[]= $price;
					}
				}

				//problem: what if an attribute is empty or not present? --> use setters instead of constructor
				$product = new MouserProduct();
				
				//Product object attributes
				if(isset($pattributes->MouserPartNumber))
				{
					$product->__set("ID", $pattributes->MouserPartNumber);
				}
				
				if(isset($pattributes->Description))
				{
					$product->__set("Name", $pattributes->Description);
				}
				
				$product->__set("Prices", $prices);
				
				if(isset($pattributes->Manufacturer))
				{
					$product->__set("Vendor", $pattributes->Manufacturer);
				}
				
				if(isset($pattributes->Availability))
				{
					$product->__set("Inventory", $pattributes->Availability);
				}
				
				//set image is not found
				if(isset($pattributes->ImagePath))
				{
					$product->__set("Image", $pattributes->ImagePath);
				}
				else
				{
					$product->__set("Image", "./img/not_found.jpg");
				}
				
				if(isset($pattributes->DataSheetUrl))
				{
					$product->__set("DataSheet", $pattributes->DataSheetUrl);
				}
				
				//not really needed, since we have default value set in class
				$product->__set("Supplier", "Mouser");
				
				//MouserProduct object attributes
				if(isset($pattributes->Category))
				{
					$product->__set("Category", $pattributes->Category);
				}
				
				if(isset($pattributes->LeadTime))
				{
					$product->__set("LeadTime", $pattributes->LeadTime);
				}
				
				if(isset($pattributes->LifecycleStatus))
				{
					$product->__set("LifeCycleStatus", $pattributes->LifecycleStatus);
				}
				
				if(isset($pattributes->ManufacturerPartNumber))
				{
					$product->__set("ManufacturerPartNumber", $pattributes->ManufacturerPartNumber);
				}
				
				if(isset($pattributes->Min))
				{
					$product->__set("Min", $pattributes->Min);
				}
				
				if(isset($pattributes->Mult))
				{
					$product->__set("Mult", $pattributes->Mult);
				}
				
				if(isset($pattributes->ProductDetailUrl))
				{
					$product->__set("DetailUrl", $pattributes->ProductDetailUrl);
				}
				
				if(isset($pattributes->Reeling))
				{
					$product->__set("Reeling", $pattributes->Reeling);
				}
				
				if(isset($pattributes->ROHSStatus))
				{
					$product->__set("ROHSStatus", $pattributes->ROHSStatus);
				}
				
				if(isset($pattributes->SuggestedReplacement))
				{
					$product->__set("SuggestedReplacement", $pattributes->SuggestedReplacement);
				}
				
				if(isset($pattributes->MultiSimBlue))
				{
					$product->__set("MultiSimBlue", $pattributes->MultiSimBlue);
				}
					
				if(isset($pattributes->UnitWeightKg))
				{
					$product->__set("UnitWeightKg", $pattributes->UnitWeightKg);
				}
				
				if(isset($pattributes->ProductAttributes))
				{
					$product->__set("Attributes", $pattributes->ProductAttributes);
				}
				
				//push new product to array
				$mouserProducts[]= $product;
			}
		}
		
		//return array of farnell products
		return $mouserProducts;
	}
?>	