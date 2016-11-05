<?php
	//globally used classes go here
	//include ProductPrice class
	require '../classes/ProductPrice.php';

	//include Product class
	require '../classes/Product.php';

	//include MouserProduct
	require '../classes/MouserProduct.php';

	//include FarnellProduct
	require '../classes/FarnellProduct.php';
	
	//include configuration file
	require '../../config/config.php';

?>

<?php
	function getFarnellProducts($keyword="fuse", $offset=0, $numberofresults=20, $farnellAPI)
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
			."&callInfo.apiKey=$farnellAPI"
			."&resultsSettings.offset=$offset"
			."&resultsSettings.numberOfResults=$numberofresults"
			."&resultsSettings.refinements.filters=rohsCompliant,inStock"
			."&resultsSettings.responseGroup=large"
		);

		//result contains the output string (which is an XML file)
		$result = curl_exec($curl);
		if ($result === false)	// Error check
		{
			echo "Error, kan niet verbinden met API";
		//	throw new Exception(curl_error($curl), curl_errno($curl));
		}

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
		
		foreach ($xml as $key => $xmlproduct)
		{
			//ignore numberofresults, only need products
			if($key=="products")
			{
				//create array of product prices & quantities
				$prices = array();
				foreach($xmlproduct->prices as $productprice)
				{
					$price = new ProductPrice(
						$productprice->from,
						$productprice->cost
					);
					$prices[]= $price;
				}
				
				//create new FarnellProduct object with product specifications
				$product = new FarnellProduct(
					$xmlproduct->sku,
					$xmlproduct->displayName,
					$prices,
					$xmlproduct->productStatus,
					$xmlproduct->rohsStatusCode,
					$xmlproduct->packSize,
					$xmlproduct->unitOfMeasure,
					$xmlproduct->id,
					"http://be.farnell.com/productimages/standard/en_GB".$xmlproduct->image["baseName"],
					$xmlproduct->datasheets[url],
					$xmlproduct->inv,
					$xmlproduct->vendorId,
					$xmlproduct->vendorName,
					$xmlproduct->brandName,
					$xmlproduct->translatedManufacturerPartNumber,
					$xmlproduct->translatedMinimumOrderQuality,
					$xmlproduct->stock,
					$xmlproduct->translatedPrimaryCatalogPage,
					$xmlproduct->countryOfOrigin,
					$xmlproduct->comingSoon,
					$xmlproduct->publishingModule,
					$xmlproduct->vatHandlingCode,
					$xmlproduct->releaseStatusCode,
					$xmlproduct->isSpecialOrder,
					$xmlproduct->isAwaitingRelease,
					$xmlproduct->reeling,
					$xmlproduct->discountReason,
					$xmlproduct->brandId,
					$xmlproduct->commodityClassCode,
					"Farnell"
				);
				
				//push new product to array
				$farnellProducts[]= $product;
			}
		}
		
		//return array of farnell products
		return $farnellProducts;
	}
?>