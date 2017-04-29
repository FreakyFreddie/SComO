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
				//json_encode & json_decode are used to prevent weird stuff from happaning. SimpleXMLObject creates attributes named 0, which are difficult to read
				$product = new FarnellProduct();
				
				//Product object attributes
				if(isset($xmlproduct->sku))
				{
					$product->__set("Id", json_decode(json_encode($xmlproduct->sku))->{'0'});
				}
				
				if(isset($xmlproduct->displayName))
				{
					$product->__set("Name", json_decode(json_encode($xmlproduct->displayName))->{'0'});
				}
				
				$product->__set("Prices", $prices);
				
				if(isset($xmlproduct->vendorName))
				{
					$product->__set("Vendor", json_decode(json_encode($xmlproduct->vendorName))->{'0'});
				}
				
				if(isset($xmlproduct->inv))
				{
					$product->__set("Inventory", json_decode(json_encode($xmlproduct->inv))->{'0'});
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
					$product->__set("DataSheet", json_decode(json_encode($xmlproduct->datasheets->url))->{'0'});
				}
				
				//not really needed, since we have default value set in class
				$product->__set("Supplier", "Farnell");
				
				//FarnellProduct object attributes
				if(isset($xmlproduct->productStatus))
				{
					$product->__set("Status", json_decode(json_encode($xmlproduct->productStatus))->{'0'});
				}
				
				if(isset($xmlproduct->rohsStatusCode))
				{
					$product->__set("ROHSStatusCode", json_decode(json_encode($xmlproduct->rohsStatusCode))->{'0'});
				}
				
				if(isset($xmlproduct->packSize))
				{
					$product->__set("PackSize", json_decode(json_encode($xmlproduct->packSize))->{'0'});
				}
				
				if(isset($xmlproduct->unitOfMeasure))
				{
					$product->__set("UnitOfMeasure", json_decode(json_encode($xmlproduct->unitOfMeasure))->{'0'});
				}
				
				if(isset($xmlproduct->id))
				{
					$product->__set("FullId", json_decode(json_encode($xmlproduct->id))->{'0'});
				}
				
				if(isset($xmlproduct->vendorId))
				{
					$product->__set("VendorId", json_decode(json_encode($xmlproduct->vendorId))->{'0'});
				}
				
				if(isset($xmlproduct->brandName))
				{
					$product->__set("BrandName", json_decode(json_encode($xmlproduct->brandName))->{'0'});
				}
				
				if(isset($xmlproduct->translatedManufacturerPartNumber))
				{
					$product->__set("TranslatedManufacturerPartNumber", json_decode(json_encode($xmlproduct->translatedManufacturerPartNumber))->{'0'});
				}
				
				if(isset($xmlproduct->translatedMinimumOrderQuality))
				{
					$product->__set("TranslatedMinimumOrderQuality", json_decode(json_encode($xmlproduct->translatedMinimumOrderQuality))->{'0'});
				}
				
				if(isset($xmlproduct->stock))
				{
					$product->__set("Stock", $xmlproduct->stock);
				}
				
				if(isset($xmlproduct->translatedPrimaryCatalogPage))
				{
					$product->__set("TranslatedPrimaryCatalogPage", json_decode(json_encode($xmlproduct->translatedPrimaryCatalogPage))->{'0'});
				}
					
				if(isset($xmlproduct->countryOfOrigin))
				{
					$product->__set("CountryOfOrigin", json_decode(json_encode($xmlproduct->countryOfOrigin))->{'0'});
				}
				
				if(isset($xmlproduct->comingSoon))
				{
					$product->__set("ComingSoon", json_decode(json_encode($xmlproduct->comingSoon))->{'0'});
				}
				
				if(isset($xmlproduct->publishingModule))
				{
					$product->__set("PublishingModule", json_decode(json_encode($xmlproduct->publishingModule))->{'0'});
				}
				
				if(isset($xmlproduct->vatHandlingCode))
				{
					$product->__set("VatHandlingCode", json_decode(json_encode($xmlproduct->vatHandlingCode))->{'0'});
				}
				
				if(isset($xmlproduct->releaseStatusCode))
				{
					$product->__set("ReleaseStatusCode", json_decode(json_encode($xmlproduct->releaseStatusCode))->{'0'});
				}
				
				if(isset($xmlproduct->isSpecialOrder))
				{
					$product->__set("IsSpecialOrder", json_decode(json_encode($xmlproduct->isSpecialOrder))->{'0'});
				}
				
				if(isset($xmlproduct->isAwaitingRelease))
				{
					$product->__set("IsAwaitingRelease", json_decode(json_encode($xmlproduct->isAwaitingRelease))->{'0'});
				}
				
				if(isset($xmlproduct->reeling))
				{
					$product->__set("Reeling", json_decode(json_encode($xmlproduct->reeling))->{'0'});
				}
				
				if(isset($xmlproduct->discountReason))
				{
					$product->__set("DiscountReason", json_decode(json_encode($xmlproduct->discountReason))->{'0'});
				}
				
				if(isset($xmlproduct->brandId))
				{
					$product->__set("BrandId", json_decode(json_encode($xmlproduct->brandId))->{'0'});
				}
				
				if(isset($xmlproduct->commodityClassCode))
				{
					$product->__set("CommodityClassCode", json_decode(json_encode($xmlproduct->commodityClassCode))->{'0'});
				}
				
				//push new product to array
				$farnellProducts[]= $product;
			}
		}
		
		//return array of farnell products
		return $farnellProducts;
	}

	/**
	//load config, typecast to object for easy access
	$GLOBALS['settings'] = (object) parse_ini_file('../../../config/config.ini', true);

	//include classes BEFORE session_start because we might need them in session
	//include DAL (DAL & login always go on top since classes depend on them)
	require $GLOBALS['settings']->Folders['root'].'../lib/database/classes/DAL.php';

	//include login class
	require $GLOBALS['settings']->Folders['root'].'../lib/users/classes/Login.php';

	//include ProductPrice class
	require $GLOBALS['settings']->Folders['root'].'../lib/products/classes/ProductPrice.php';

	//include Product class
	require $GLOBALS['settings']->Folders['root'].'../lib/products/classes/Product.php';

	//include MouserProduct
	require $GLOBALS['settings']->Folders['root'].'../lib/products/classes/MouserProduct.php';

	//include FarnellProduct
	require $GLOBALS['settings']->Folders['root'].'../lib/products/classes/FarnellProduct.php';

	//globally used functions go here
	//logfunction
	require $GLOBALS['settings']->Folders['root'].'../lib/users/functions/logActivity.php';

	//input checks
	require $GLOBALS['settings']->Folders['root'].'../lib/database/functions/validateInputs.php';
	getFarnellProducts("fuse", 0, 20);**/
?>