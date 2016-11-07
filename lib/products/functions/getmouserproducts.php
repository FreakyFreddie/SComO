<?php
	function getMouserProducts($keyword='sword', $offset=0, $numberofresults=20)
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
		$headerparam = array('AccountInfo'=>array('PartnerID'=>"ebb711aa-9c13-459d-9721-8a145b4ffac6"));
		
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
							$price = new ProductPrice(
								$pPrice->Quantity,
								$pPrice->Price
								);
							$prices[]= $price;

						}
					}
					//avoid looping through the attributes of a Price if there is only one
					else
					{
					//var_dump($pPrice);
						$price = new ProductPrice(
							$pPriceBreaks->Quantity,
							$pPriceBreaks->Price
							);
						$prices[]= $price;
					}
				}

				//problem: what if an attribute is empty or not present?
				//problem [PriceBreaks] => stdClass Object ( [Pricebreaks] => stdClass Object
				// [PriceBreaks] => stdClass Object ( [Pricebreaks] => Array ( [0] => stdClass Object ( [Quantity] => 1 [Price] => € 4,60 [Currency] => EUR ) [1] => stdClass Object ( [Quantity] => 10 [Price] => € 4,37 [Currency] => EUR ) [2] => stdClass Object ( [Quantity] => 25 [Price] => € 3,65 [Currency] => EUR ) [3] => stdClass Object ( [Quantity] => 50 [Price] => € 3,47 [Currency] => EUR ) ) )
				//problem: sometimes imagePath not present --> can't work with constructor
				//create new MouserProduct object with product specifications				
				//set image if not found
				if(!isset($pattributes->ImagePath))
				{
					$pattributes->ImagePath="./img/not_found.jpg";
				}
				
				$product = new MouserProduct(
					$pattributes->MouserPartNumber,
					$pattributes->Description,
					$prices,
					$pattributes->Availability,
					$pattributes->DataSheetUrl,
					$pattributes->ImagePath,
					$pattributes->Category,
					$pattributes->LeadTime,
					$pattributes->LifecycleStatus,
					$pattributes->Manufacturer,
					$pattributes->ManufacturerPartNumber,
					$pattributes->Min,
					$pattributes->Mult,
					$pattributes->ProductDetailUrl,
					$pattributes->Reeling,
					$pattributes->ROHSStatus,
					$pattributes->SuggestedReplacement,
					$pattributes->MultiSimBlue,
					"Mouser"
				);
				
				if(isset($pattributes->ProductAttributes))
				{
					$product->mproductAttributes = $pattributes->ProductAttributes;
				}
				
				if(isset($pattributes->UnitWeightKg))
				{
					$product->mproductUnitWeightKg = $pattributes->UnitWeightKg;
				}
				
				//push new product to array
				$mouserProducts[]= $product;
			}
		}
		
		//return array of farnell products
		return $mouserProducts;
	}
?>	