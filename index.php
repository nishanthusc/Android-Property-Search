<?php
header("Access-Control-Allow-Origin: *");
header('Access-Control-Allow-Methods: GET, POST'); // 155 N 10th Ave, Pocatello, ID
//echo $_POST['streetInput'];
//echo $addr;
$StreetAddress = urlencode($_GET['streetInput']);//'155 N 10th Ave';////'2636 Menlo Ave';//urlencode($_GET['streetInput']);//'2636 Menlo Ave';
$city = 'Pocatello';//'Los Angeles';
$state = 'ID';
$locationStr = urlencode($_GET['cityInput'].", ".$_GET['stateInput']);//urlencode($city.", ".$state);//urlencode($city.", ".$state); //urlencode($_GET['cityInput'].", ".$_GET['stateInput']);//urlencode($city.", ".$state); 
$url = "http://www.zillow.com/webservice/GetDeepSearchResults.htm?zws-id=X1-ZWz1b3b89i9dzf_85kuc&address=".$StreetAddress."&citystatezip=".$locationStr."&rentzestimate=true";
//echo $url;                                   
//echo $url;
$xmlResponse = simplexml_load_file($url);
//print_r($xmlResponse);

//print_r($xmlResponse);
//echo $xmlResponse->response->results->result->links->homedetails;
//echo $xmlResponse->request->citystatezip;
//$propertyType=$xmlResponse->response->results->result->useCode;
if($xmlResponse->message->code == 0){

	

					$zpid = $xmlResponse->response->results->result->zpid;
					$chartUrl1 = "http://www.zillow.com/webservice/GetChart.htm?zws-id=X1-ZWz1b3b89i9dzf_85kuc&address&unit-type=percent&zpid=".$zpid."&width=600&height=300&chartDuration=1year";
					//echo $chartUrl1;
					$chartResponse1 = simplexml_load_file($chartUrl1);
					//echo $chartResponse;
					if(($chartResponse1->message->code)==0){

						$imageUrl1 = $chartResponse1->response->url;
						//echo $imageUrl1;

					}

					else{

						$imageUrl1 = "";
						
					}
					//echo $imageUrl1;
					$chartUrl2 = "http://www.zillow.com/webservice/GetChart.htm?zws-id=X1-ZWz1b3b89i9dzf_85kuc&address&unit-type=percent&zpid=".$zpid."&width=600&height=300&chartDuration=5years";
					//echo $chartUrl2;
					$chartResponse2 = simplexml_load_file($chartUrl2);
					//echo $chartResponse2;
					if(($chartResponse2->message->code)==0){

						$imageUrl2 = $chartResponse2->response->url;
						//echo $imageUrl2;

					}

					else{

						$imageUrl2 = "";
						
					}

					$chartUrl3 = "http://www.zillow.com/webservice/GetChart.htm?zws-id=X1-ZWz1b3b89i9dzf_85kuc&address&unit-type=percent&zpid=".$zpid."&width=600&height=300&chartDuration=10years";
					//echo $chartUrl3;
					$chartResponse3 = simplexml_load_file($chartUrl3);
					//echo $chartResponse2;
					if(($chartResponse3->message->code)==0){

						$imageUrl3 = $chartResponse3->response->url;
						//echo $imageUrl3;

					}

					else{
						//echo "In NA";
						$imageUrl3 = "";
						
					}


				if((($xmlResponse->response->results->result->lastSoldPrice)!="") or(($xmlResponse->response->results->lastSoldPrice)!=0)){
					$x = (float)$xmlResponse->response->results->result->lastSoldPrice;
					$lastSoldPrice= "$".number_format($x,2);
				}
				else{ 
					$lastSoldPrice= "N/A";
				}

				if(($xmlResponse->response->results->result->yearBuilt)!=""){
				 	$yearBuilt= $xmlResponse->response->results->result->yearBuilt;
				} 
				else{ 
					$yearBuilt= "N/A";
				}

				if (($xmlResponse->response->results->result->lastSoldDate)!=""){
					$date = date_create($xmlResponse->response->results->result->lastSoldDate); 
					$lastSoldDate= date_format($date,"d-M-Y");
				} 
				else{
					$lastSoldDate= "N/A";
				}


				if(($xmlResponse->response->results->result->lotSizeSqFt)!=""){
					$y = (float)$xmlResponse->response->results->result->lotSizeSqFt; 
					$lotSize= number_format($y)." sq.ft";
				} 
				else{ 
					$lotSize= "N/A";
				}

				if(($xmlResponse->response->results->result->zestimate->{'last-updated'})!=""){ 
					$date1 = date_create($xmlResponse->response->results->result->zestimate->{'last-updated'});
					$zestimatePropEstimateDate= date_format($date1,"d-M-Y");
				} 
				else{
					$zestimatePropEstimateDate= "N/A";
				}

				if(($xmlResponse->response->results->result->zestimate->amount)!=""){ 
					$castedNum = (float)$xmlResponse->response->results->result->zestimate->amount;
					 $zestimatePropEstimateVal= "$".number_format($castedNum,2);
				} 
				else{ 
					$zestimatePropEstimateVal= "N/A";
				}

				if(($xmlResponse->response->results->result->finishedSqFt)!=""){ 
					$z=(float)$xmlResponse->response->results->result->finishedSqFt;
					 $finishedArea= number_format($z)." sq.ft";
				} 
				else{ 
					$finishedArea= "N/A";
				}


				if(($xmlResponse->response->results->result->zestimate->valueChange)!=""){
					$changeVal = $xmlResponse->response->results->result->zestimate->valueChange;
					if(strpos($changeVal,'-')!==false){
						//$imageUrl="http://cs-server.usc.edu:45678/hw/hw6/down_r.gif";
						$symbol = "down";
					}
					else{
						//$imageUrl="http://cs-server.usc.edu:45678/hw/hw6/up_g.gif";
						$symbol="up";
					}
					$aa = (float)abs($xmlResponse->response->results->result->zestimate->valueChange); 
					$daysChange= "$".number_format($aa,2);
					$facebookValue="$".number_format((float)$xmlResponse->response->results->result->zestimate->valueChange,2);
				} 
				else{
					$daysChange= "N/A";
					$symbol="N/A";
					$facebookValue="N/A";
				}


				if (($xmlResponse->response->results->result->bathrooms)!=""){
					$bathrooms= $xmlResponse->response->results->result->bathrooms;
				} else{
					$bathrooms= "N/A";
				}


				if((($xmlResponse->response->results->result->zestimate->valuationRange->low)!="")&&(($xmlResponse->response->results->result->zestimate->valuationRange->high)!="")){
					$mm = (float)$xmlResponse->response->results->result->zestimate->valuationRange->low;
					$yy = (float)$xmlResponse->response->results->result->zestimate->valuationRange->high; 
					$allTimePropRangeLeft= "$".number_format($mm,2);
					$allTimePropRangeRight="$".number_format($yy,2);
				} 
				else{
					$allTimePropRangeLeft="N/A";
					$allTimePropRangeRight="N/A";
				}


				if (($xmlResponse->response->results->result->bedrooms)!=""){
					$bedrooms= $xmlResponse->response->results->result->bedrooms;
				} 
				else{ 
					$bedrooms= "N/A";
				}

				if(($xmlResponse->response->results->result->rentzestimate->{'last-updated'})!=""){
					$date = date_create($xmlResponse->response->results->result->rentzestimate->{'last-updated'});
					$rentZestimateDate = date_format($date,"d-M-Y");
				}
				else{
					$rentZestimateDate="N/A";
				}

				if (($xmlResponse->response->results->result->rentzestimate->amount)!=""){
					$tmp=(float)$xmlResponse->response->results->result->rentzestimate->amount;
					$rentZestimateVal= "$".number_format($tmp,2);
				} 
				else{
					$rentZestimateVal= "N/A";
				}


				if(($xmlResponse->response->results->result->taxAssessmentYear)!=""){
					$taxAssessmentYear= $xmlResponse->response->results->result->taxAssessmentYear;
				} 
				else{
					$taxAssessmentYear= "N/A";
				}

				//echo "rent zestimate".$xmlResponse->response->results->result->rentzestimate->valueChange;
				if(($xmlResponse->response->results->result->rentzestimate->valueChange)!=""){
					//echo "inside renst ";
					$dd = abs($xmlResponse->response->results->result->rentzestimate->valueChange);
					$changeVal1 = $xmlResponse->response->results->result->rentzestimate->valueChange;
					if(strpos($changeVal1,'-')!==false){
						$Url1="http://cs-server.usc.edu:45678/hw/hw6/down_r.gif";
						$symbol1="down";
					}
					else{
						$symbol1="up";
						//$imageUrl1="http://cs-server.usc.edu:45678/hw/hw6/up_g.gif";
					}
					$ee = (float)$dd; 
					$rentChange= "$".number_format($ee,2);
				} 
				else {
					$rentChange= "N/A";
					$symbol1="N/A";
				}


				if(($xmlResponse->response->results->result->taxAssessment)!=""){ 
					$nn = (float)$xmlResponse->response->results->result->taxAssessment;
					$taxAssessment= "$".number_format($nn,2);
				} 
				else{
					$taxAssessment= "N/A";
				}


				if((($xmlResponse->response->results->result->rentzestimate->valuationRange->low)!="")&&(($xmlResponse->response->results->result->rentzestimate->valuationRange->high)!="")) {
					$aa = (float)$xmlResponse->response->results->result->rentzestimate->valuationRange->low;
					$bb=(float)$xmlResponse->response->results->result->rentzestimate->valuationRange->high;
					$allTimeRentRangeLeft= "$".number_format($aa,2);
					$allTimeRentRangeRight="$".number_format($bb,2);
				} 
				else{ 
					$allTimeRentRangeLeft= "N/A";
					$allTimeRentRangeRight="N/A";
				}


				if(($xmlResponse->response->results->result->zpid)!=''){

						$zpid = (string)$xmlResponse->response->results->result->zpid;
				}
				else{
					$zpid="N/A";
				}

				$address = $xmlResponse->request->address.",".$xmlResponse->request->citystatezip;
				$housedetails = $xmlResponse->response->results->result->links->homedetails;
				//echo $address; 
				//if(($xmlResponse->response->results->result->lastSoldPrice)!=""){$x = (float)$xmlResponse->response->results->result->lastSoldPrice;$y="$".number_format($x,2);} else{ echo "-";}
				$arr = array(
							 'homedetails'=>(string)$xmlResponse->response->results->result->links->homedetails,
							 'street'=>(string)$xmlResponse->response->results->result->address->street,
							 'city'=>(string)$xmlResponse->response->results->result->address->city,
							 'state'=>(string)$xmlResponse->response->results->result->address->state,
							 'zipcode'=>(string)$xmlResponse->response->results->result->address->zipcode,
							 'propertyType' =>(string)$xmlResponse->response->results->result->useCode, 
							 'lastSoldPrice' => $lastSoldPrice,
							 'yearBuilt' => (string)$yearBuilt,
							 'lastSoldDate' => $lastSoldDate,
							 'lotSize' => $lotSize,
							 'zestimatePropEstimateDate'=>$zestimatePropEstimateDate,
							 'zestimatePropEstimateVal'=>$zestimatePropEstimateVal,
							 'finishedArea'=>$finishedArea,
							 'thirtyDaysOverallChangeSymbol'=>$symbol,
							 'thirtyDaysOverallChange'=>$daysChange,
							 'bathrooms'=>(string)$bathrooms,
							 'allTimePropRangeLow'=>$allTimePropRangeLeft,
							 'allTimePropRangeHigh'=>$allTimePropRangeRight,
							 'bedrooms'=>(string)$bedrooms,
							 'rentZestimateDate'=>$rentZestimateDate,
							 'rentZestimateVal'=>$rentZestimateVal,
							 'taxAssessmentYear'=>(string)$taxAssessmentYear,
							 'thirtyDaysRentChangeSymbol'=>$symbol1,
							 'thirtyDaysRentChange'=>$rentChange,
							 'taxAssessment'=>$taxAssessment,
							 'allTimeRentRangeLow'=>$allTimeRentRangeLeft,
							 'allTimeRentRangeHigh'=>$allTimeRentRangeRight,
							 'oneYearImage'=>(string)$imageUrl1,
							 'fiveYearImage'=>(string)$imageUrl2,
							 'tenYearImage'=>(string)$imageUrl3,
							 'facebookValue'=>(string)$facebookValue);

				//echo json_encode($arr);

				$json = json_encode($arr);
				echo $json;
}
else{

	echo "";

}

?>