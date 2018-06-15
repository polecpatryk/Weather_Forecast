<?php

	class Forecast
	{
		private $forecastAPI = 'http://api.openweathermap.org/data/2.5/forecast?mode=xml&APPID=9c398cd4cf22ab63cebf65a655f9d64d&lang=pl&units=metric';
		private $curl;
		private $responseXML;
		
		public function __construct() 
		{
			$this->curl = curl_init();
		}
		
		public function __destruct()
		{
			curl_close($this->curl);
		}
		 
		public function Search($city)
		{
			curl_setopt($this->curl, CURLOPT_URL           , $this->forecastAPI.'&q='.$city);
			curl_setopt($this->curl, CURLOPT_RETURNTRANSFER, true);

			$this->responseXML = simplexml_load_string(curl_exec($this->curl));
			
			if(isset($this->responseXML->cod))
				return intval($this->responseXML->cod);
			else
				return $this->responseXML;
		}
		
		public function Get()
		{
			return $this->responseXML;
		}
	}
?>