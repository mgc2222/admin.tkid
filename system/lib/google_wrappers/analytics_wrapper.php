<?php
// required libraries: 	google/Google_Client.php;
//						google/contrib/Google_AnalyticsService.php
// 

// config at: https://code.google.com/apis/console
// docs: https://developers.google.com/analytics/devguides/reporting/core/dimsmets/visitor
// 		https://developers.google.com/analytics/devguides/reporting/core/dimsmets/pagetracking
// test with:  http://ga-dev-tools.appspot.com/explorer/
// see : http://stackoverflow.com/questions/9863509/service-applications-and-google-analytics-api-v3-server-to-server-oauth2-authen
//		https://gist.github.com/4017147

class AnalyticsWrapper
{
	var $developerEmail = '696806600810@developer.gserviceaccount.com';
	var $keyFilePath = 'google/36d407faed8deb4ca2baf2c421f2d3dc04580503-privatekey.p12';
	var $clientId = '696806600810.apps.googleusercontent.com';
	var $arrServices = array('https://www.googleapis.com/auth/analytics.readonly');
	
	function __construct()
	{
		$relativePath = dirname(__FILE__).'/../';
		$this->keyFilePath = $relativePath.$this->keyFilePath;
	}
	
	function SetCredentials($clientId, $developerEmail, $keyFilePath)
	{
		$this->clientId = $clientId;
		$this->developerEmail = $developerEmail; // email you added to GA
		$this->keyFilePath = $keyFilePath;  // keyfile you downloaded
	}
	
	// $ids = 'ga:4561379'; -> get it from analytics property id
	// $metrics = 'ga:visitors,ga:pageviews,ga:uniquePageviews';
	// $optProps = array('filters' => 'ga:pagePath==/hotel-phoenicia-holiday-resort_mamaia.html','dimensions'=>'ga:day');
	function GetData($ids, $startDate, $endDate, $metrics, $optProps)
	{
		$client = new Google_Client();	
		$client->setApplicationName("Travelro Api Service");
		$client->setAssertionCredentials(
			new Google_AssertionCredentials(
				$this->developerEmail, 
				$this->arrServices,
				file_get_contents($this->keyFilePath) 
		));

		$client->setClientId($this->clientId);
		$client->setAccessType('offline_access');  // this may be unnecessary?		
		
		$service = new Google_AnalyticsService($client);
		// $siteUrl = 'www.travelro.ro'; // not needed for now

		$data = $service->data_ga->get($ids, $startDate, $endDate, $metrics, $optProps);
		return $data;
	}
}
?>