<?php
	//include product class
	require '../lib/classes/product.php';
?>

<?php
	function getFarnellProducts($keyword="fuse", $offset=0, $numberofresults=20)
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
		//offset is where to start (1 means first product)
		//numberofresults for number of products
		curl_setopt($curl, CURLOPT_URL, "https://api.element14.com/catalog/products"
			."?term=any:$keyword"
			."&storeInfo.id=be.farnell.com"
			."&callInfo.responseDataFormat=XML"
			."&callInfo.callback="
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
			<ns1:keywordSearchReturn>
				<ns1:numberOfResults>8530</ns1:numberOfResults>
				<ns1:products>
					<ns1:sku>1123079</ns1:sku>
					<ns1:displayName>BUSSMANN BY EATON - S500-1-R. - FUSE, QUICK BLOW, 1A, 5X20MM</ns1:displayName>
					<ns1:packSize>1</ns1:packSize>
					<ns1:unitOfMeasure>EACH</ns1:unitOfMeasure>
					<ns1:id>pf_UK1_1123079_0</ns1:id>
					<ns1:prices>
						<ns1:to>90</ns1:to>
						<ns1:from>10</ns1:from>
						<ns1:cost>0.633</ns1:cost>
					</ns1:prices>
					<ns1:prices>
						<ns1:to>490</ns1:to>
						<ns1:from>100</ns1:from>
						<ns1:cost>0.541</ns1:cost>
					</ns1:prices>
					<ns1:prices>
						<ns1:to>990</ns1:to>
						<ns1:from>500</ns1:from>
						<ns1:cost>0.48</ns1:cost>
					</ns1:prices>
					<ns1:prices>
						<ns1:to>1000000000</ns1:to>
						<ns1:from>1000</ns1:from>
						<ns1:cost>0.416</ns1:cost>
					</ns1:prices>
					<ns1:brandName>BUSSMANN BY EATON</ns1:brandName>
					<ns1:translatedManufacturerPartNumber>S500-1-R.</ns1:translatedManufacturerPartNumber>
					<ns1:translatedMinimumOrderQuality>10</ns1:translatedMinimumOrderQuality>
					<ns1:translatedPrimaryCatalogPage>2417</ns1:translatedPrimaryCatalogPage>
					<ns1:publishingModule>en/PIM_204592.xml</ns1:publishingModule>
					<ns1:vatHandlingCode>SLST</ns1:vatHandlingCode>
					<ns1:discountReason>0</ns1:discountReason>
				</ns1:products>
			</ns1:keywordSearchReturn>
		*/
		
		//extract namespace from string
		$sxe = new SimpleXMLElement($result);
		$ns=$sxe->getNamespaces(true);
		
		//xml string to SimpleXMLElement
		foreach($ns as $nskey => $namespace)
		{
			//parse XML to array with SimpleXML parser
			//true indicates prefix
			$xml=simplexml_load_string($result, "SimpleXMLElement", 0, $nskey, true);
		}
		
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
				
				//create new Product object with product specifications
				$product = new FarnellProduct(
					$xmlproduct->sku,
					$xmlproduct->displayName,
					"Farnell",
					$prices,
					$xmlproduct->packSize,
					$xmlproduct->unitOfMeasure,
					$xmlproduct->id,
					$xmlproduct->brandName,
					$xmlproduct->translatedManufacturerPartNumber,
					$xmlproduct->translatedMinimumOrderQuality,
					$xmlproduct->translatedPrimaryCatalogPage,
					$xmlproduct->publishingModule,
					$xmlproduct->vatHandlingCode,
					$xmlproduct->discountReason
				);
				
				//push new product to array
				$farnellProducts[]= $product;
			}
		}
		
		//return array of farnell products
		return $farnellProducts;
	}
?>