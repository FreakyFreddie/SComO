<?php
	function getFarnellProducts($keyword, $offset, $numberofresults)
	{
		//Curl is used to transfer data over a wide variety of protocols
		//To use curl, you may have to install php libcurl package (usually included in php5+)
		//initialize curl
		$curl = curl_init();

		// Error check
		if ($curl === false)
		{
			throw new Exception('failed to initialize');
		}

		//return transfer as a string
		curl_setopt($curl, CURLOPT_RETURNTRANSFER, '1');
		
		//set content type xml
		curl_setopt($curl, CURLOPT_HTTPHEADER,array('Content-Type: text/xml')); 
		
		//NO SSL (didn't work) -- try to fix in future
		curl_setopt($curl, CURLOPT_SSL_VERIFYPEER, 'false');
		
		//set the URL, breakdown
		//keyword for searching
		//omitXMLSchema strips namespaces
		//term
			//any: use for keyword search
			//id: use for element14 part number search
			//manuPartNum: use for manufacturer part number search.
		//offset is where to start (1 means first product)
		//numberofresults for number of products
		//responsegroup op large want we hebben de image ook nodig
		curl_setopt($curl, CURLOPT_URL, "https://api.element14.com/catalog/products"
			."?term=any:$keyword"
			."&storeInfo.id=be.farnell.com"
			."&callInfo.responseDataFormat=XML"
			."&callInfo.omitXmlSchema=True"
			."&callInfo.apiKey=".$GLOBALS['settings']->Suppliers['farnellAPI']
			."&resultsSettings.offset=$offset"
			."&resultsSettings.numberOfResults=$numberofresults"
			."&resultsSettings.refinements.filters=rohsCompliant,inStock"
			."&resultsSettings.responseGroup=large"
		);

		//result contains the output string (which is an XML file)
		$result = curl_exec($curl) or die("Kan niet verbinden met API");
		
		//stop curl
		curl_close($curl);
		
		/*
			XML FILE EXAMPLE RETURNED FROM FARNELL
			<?xml version="1.0" encoding="UTF-8"?>
			<keywordSearchReturn>
				<numberOfResults>3934</numberOfResults>
				<products>
					<sku>1123073</sku>
					<displayName>BUSSMANN BY EATON - S500-1.6-R - FUSE, QUICK BLOW, 1.6A</displayName>
					<productStatus>defaultStatus</productStatus>
					<rohsStatusCode>YES</rohsStatusCode>
					<packSize>1</packSize>
					<unitOfMeasure>EACH</unitOfMeasure>
					<id>pf_BEE_1123073_0</id>
					<image>
						<baseName>/42420642.jpg</baseName>
						<vrntPath>farnell/</vrntPath>
					</image>
					<datasheets>
						<type>T</type>
						<description>Technical Data Sheet</description>
						<url>http://www.farnell.com/datasheets/10887.pdf</url>
					</datasheets>
					<prices>
						<to>90</to>
						<from>10</from>
						<cost>0.633</cost>
					<prices>
					<prices>
						<to>490</to>
						<from>100</from>
						<cost>0.541</cost>
					<prices>
					<prices>
						<to>990</to>
						<from>500</from>
						<cost>0.48</cost>
					</prices>
					<prices>
						<to>1000000000</to>
						<from>1000</from>
						<cost>0.416</cost>
					</prices>
					<inv>339545</inv>
					<vendorId>76657</vendorId>
					<vendorName>BUSSMANN BY EATON</vendorName>
					<brandName>BUSSMANN BY EATON</brandName>
					<translatedManufacturerPartNumber>S500-1.6-R</translatedManufacturerPartNumber>
					<translatedMinimumOrderQuality>10</translatedMinimumOrderQuality>
					<related>
						<containAlternatives>true</containAlternatives>
						<containcontainRoHSAlternatives>true</containcontainRoHSAlternatives>
						<containAccessories>true</containAccessories>
						<containcontainRoHSAccessories>true</containcontainRoHSAccessories>
					</related>
					<stock>
						<level>339545</level>
						<leastLeadTime>91</leastLeadTime>
						<status>1</status>
						<shipsFromMultipleWarehouses>true</shipsFromMultipleWarehouses>
						<breakdown>
							<inv>783</inv>
							<region>Liege</region>
							<lead>0</lead>
							<warehouse>LG1</warehouse>
						</breakdown>
						<breakdown>
							<inv>338762</inv>
							<region>UK</region>
							<lead>91</lead>
							<warehouse>GB1</warehouse>
						</breakdown>
						<regionalBreakdown>
							<level>783</level>
							<leastLeadTime>0</leastLeadTime>
							<status>1</status>
							<warehouse>Liege</warehouse>
							<shipsFromMultipleWarehouses>true</shipsFromMultipleWarehouses>
						</regionalBreakdown>
						<regionalBreakdown>
							<level>338762</level>
							<leastLeadTime>91</leastLeadTime>
							<status>1</status>
							<warehouse>UK</warehouse>
							<shipsFromMultipleWarehouses>true</shipsFromMultipleWarehouses>
						</regionalBreakdown>
						<nominatedWarehouseDetails></nominatedWarehouseDetails>
					</stock>
					<translatedPrimaryCatalogPage>2082</translatedPrimaryCatalogPage>
					<countryOfOrigin>CN</countryOfOrigin>
					<comingSoon>false</comingSoon>
					<publishingModule>en/PIM_204592.xml</publishingModule>
					<vatHandlingCode>SLSR</vatHandlingCode>
					<releaseStatusCode>-1</releaseStatusCode>
					<isSpecialOrder>false</isSpecialOrder>
					<isAwaitingRelease>false</isAwaitingRelease>
					<reeling>false</reeling>
					<discountReason>0</discountReason>
					<brandId>1000158</brandId>
					<commodityClassCode>037004000</commodityClassCode>
				</products>
		*/
		
		$xml = new SimpleXMLElement($result);
		
		//extract namespace from string (no longer needed, since we added omitXMLSchema=True to the URL)
		/*
		$ns=$sxe->getNamespaces(true);
		
		
		//xml string to SimpleXMLElement
		foreach($ns as $nskey => $namespace)
		{
			//parse XML to array with SimpleXML parser
			//true indicates prefix
			$xml=simplexml_load_string($result, "SimpleXMLElement", 0, $nskey, true);
		}
		*/
		
		//create farnell product array
		$farnellProducts = array();
		
		foreach($xml as $key => $xmlproduct)
		{
			//ignore numberofresults, only need products
			if($key=="products")
			{
				//create array of product prices & quantities
				$prices = array();
				foreach($xmlproduct->prices as $productprice)
				{
					
					$price = new ProductPrice();
					
					if(isset($productprice->from) && isset($productprice->cost))
					{
						$price->__set("Quantity", $productprice->from);
						$price->__set("Price", $productprice->cost);
					}

					$prices[]= $price;
				}
				
				//problem: what if an attribute is empty or not present? --> use setters instead of constructor
				$product = new FarnellProduct();
				
				//Product object attributes
				if(isset($xmlproduct->sku))
				{
					$product->__set("ID", $xmlproduct->sku);
				}
				
				if(isset($xmlproduct->displayName))
				{
					$product->__set("Name", $xmlproduct->displayName);
				}
				
				$product->__set("Prices", $prices);
				
				if(isset($xmlproduct->vendorName))
				{
					$product->__set("Vendor", $xmlproduct->vendorName);
				}
				
				if(isset($xmlproduct->inv))
				{
					$product->__set("Inventory", $xmlproduct->inv);
				}
				
				//set image is not found
				if(isset($xmlproduct->image->baseName))
				{
					$product->__set("Image", 'http://be.farnell.com/productimages/standard/en_GB'.$xmlproduct->image->baseName);
				}
				else
				{
					$product->__set("Image", "./img/not_found.jpg");
				}
				
				if(isset($xmlproduct->datasheets->url))
				{
					$product->__set("DataSheet", $xmlproduct->datasheets->url);
				}
				
				//not really needed, since we have default value set in class
				$product->__set("Supplier", "Farnell");
				
				//FarnellProduct object attributes
				if(isset($xmlproduct->productStatus))
				{
					$product->__set("Status", $xmlproduct->productStatus);
				}
				
				if(isset($xmlproduct->rohsStatusCode))
				{
					$product->__set("ROHSStatusCode", $xmlproduct->rohsStatusCode);
				}
				
				if(isset($xmlproduct->packSize))
				{
					$product->__set("PackSize", $xmlproduct->packSize);
				}
				
				if(isset($xmlproduct->unitOfMeasure))
				{
					$product->__set("UnitOfMeasure", $xmlproduct->unitOfMeasure);
				}
				
				if(isset($xmlproduct->id))
				{
					$product->__set("FullID", $xmlproduct->id);
				}
				
				if(isset($xmlproduct->vendorId))
				{
					$product->__set("VendorID", $xmlproduct->vendorId);
				}
				
				if(isset($xmlproduct->brandName))
				{
					$product->__set("BrandName", $xmlproduct->brandName);
				}
				
				if(isset($xmlproduct->translatedManufacturerPartNumber))
				{
					$product->__set("TranslatedManufacturerPartNumber", $xmlproduct->translatedManufacturerPartNumber);
				}
				
				if(isset($xmlproduct->translatedMinimumOrderQuality))
				{
					$product->__set("TranslatedMinimumOrderQuality", $xmlproduct->translatedMinimumOrderQuality);
				}
				
				if(isset($xmlproduct->stock))
				{
					$product->__set("Stock", $xmlproduct->stock);
				}
				
				if(isset($xmlproduct->translatedPrimaryCatalogPage))
				{
					$product->__set("TranslatedPrimaryCatalogPage", $xmlproduct->translatedPrimaryCatalogPage);
				}
					
				if(isset($xmlproduct->countryOfOrigin))
				{
					$product->__set("CountryOfOrigin", $xmlproduct->countryOfOrigin);
				}
				
				if(isset($xmlproduct->comingSoon))
				{
					$product->__set("ComingSoon", $xmlproduct->comingSoon);
				}
				
				if(isset($xmlproduct->publishingModule))
				{
					$product->__set("PublishingModule", $xmlproduct->publishingModule);
				}
				
				if(isset($xmlproduct->vatHandlingCode))
				{
					$product->__set("VatHandlingCode", $xmlproduct->vatHandlingCode);
				}
				
				if(isset($xmlproduct->releaseStatusCode))
				{
					$product->__set("ReleaseStatusCode", $xmlproduct->releaseStatusCode);
				}
				
				if(isset($xmlproduct->isSpecialOrder))
				{
					$product->__set("IsSpecialOrder", $xmlproduct->isSpecialOrder);
				}
				
				if(isset($xmlproduct->isAwaitingRelease))
				{
					$product->__set("IsAwaitingRelease", $xmlproduct->isAwaitingRelease);
				}
				
				if(isset($xmlproduct->reeling))
				{
					$product->__set("Reeling", $xmlproduct->reeling);
				}
				
				if(isset($xmlproduct->discountReason))
				{
					$product->__set("DiscountReason", $xmlproduct->discountReason);
				}
				
				if(isset($xmlproduct->brandId))
				{
					$product->__set("BrandID", $xmlproduct->brandId);
				}
				
				if(isset($xmlproduct->commodityClassCode))
				{
					$product->__set("CommodityClassCode", $xmlproduct->commodityClassCode);
				}
				
				//push new product to array
				$farnellProducts[]= $product;
			}
		}
		
		//return array of farnell products
		return $farnellProducts;
	}
	//$test=getFarnellProducts("fuse", 0, 20, "gd8n8b2kxqw6jq5mutsbrvur");
	//print_r($test);
?>