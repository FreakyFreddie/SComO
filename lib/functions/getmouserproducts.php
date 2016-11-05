<?php
	function getMouserProducts($keyword="fuse", $offset=0, $numberofresults=20)
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
		
		foreach ($soapresult as $key => $soapproduct)
		{
			//ignore numberofresults, only need products
			if($key=="MouserPart")
			{
				//create array of product prices & quantities
				$prices = array();
				foreach($soapproduct->PriceBreaks as $productprice)
				{
					$price = new ProductPrice(
						$productprice->Quantity,
						$productprice->Price
					);
					$prices[]= $price;
				}
				
				//create new MouserProduct object with product specifications
				$product = new MouserProduct(
					$soapproduct->MouserPartNumber,
					$soapproduct->Description,
					$prices,
					$soapproduct->Availability,
					$soapproduct->DataSheetUrl,
					$soapproduct->ImagePath,
					$soapproduct->Category,
					$soapproduct->LeadTime,
					$soapproduct->LifeCycleStatus,
					$soapproduct->Manufacturer,
					$soapproduct->ManufacturerPartNumber,
					$soapproduct->Min,
					$soapproduct->Mult,
					$soapproduct->Attributes,
					$soapproduct->DetailUrl,
					$soapproduct->Reeling,
					$soapproduct->ROHSStatus,
					$soapproduct->SuggestedReplacement,
					$soapproduct->MultiSimBlue,
					$soapproduct->UnitWeightKg,
					"Mouser"
				);
				
				//push new product to array
				$mouserProducts[]= $product;
			}
		}
			
		//return array of farnell products
		return $mouserProducts;
	}
?>	