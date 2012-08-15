<?php

namespace GoogleWeather\Google;

use Zend\Http\Client;

class Weather 
{
    /**
     * Google's API URL
     */
    protected $serviceApiUrl;

    /**
     * Google service URL
     */
    protected $googleBaseUrl;
    
    /**
     * The HTTP Client object
     *
     * @var Zend\Http\Client
     */
    protected $httpClient;
 
    /**
     * Container for the obtained forecast
     * 
     * @var array $forecast 
     */
    protected $forecast;
    
    /**
     * @var array $lows 
     */
    private $lows;
    
    /**
     * @var array $highs 
     */
    private $highs;
    
    /**
     * @var array $icons 
     */
    private $icons;
    
    /**
     * @var array $daysOfWeek 
     */
    private $daysOfWeek;
    
    /**
     * @var array $conditions 
     */
    private $conditions;
    
    /**
     * @var string $currentIcon; 
     */
    private $currentIcon;
    
    /**
     * @var string $currentTemperature; 
     */
    private $currentTemperature;
    
    /**
     * @var string $currentCondition; 
     */
    private $currentCondition;
    
    /**
     * The city of weather forecast
     *
     * @var string $cityName
     */
    private $cityName;
    
    /**
     * The ISO code for language
     *
     * @var string $language
     */
    private $language;

    /**
     * Setter for the Google's base URL
     * 
     * @param string $url 
     */
    public function setGoogleBaseUrl($url)
    {
        $this->googleBaseUrl = $url;
    }
    
    /**
     * Getter for the Google's base URL
     * 
     * @return string 
     */
    public function getGoogleBaseUrl()
    {
        return $this->googleBaseUrl;
    }
    
    /**
     * Setter for the Google's service API URL
     * 
     * @param string $url 
     */
    public function setServiceApiUrl($url)
    {
        $this->serviceApiUrl = $url;
    }
    
    /**
     * Getter for the Google's service API URL
     * 
     * @return string 
     */
    public function getServiceApiUrl()
    {
        return $this->serviceApiUrl;
    }
    
    /**
     * Sets the Zend_Http_Client object to use in requests. If not provided a default will
     * be used.
     *
     * @param Zend_Http_Client $client The HTTP client instance to use
     * @return Ztools_Service_Google_Weather
     */
    public function setHttpClient(Zend\Http\Client $client)
    {
        $this->httpClient = $client;
        return $this;
    }

    /**
     * Returns the instance of the Zend_Http_Client which will be used. Creates an instance
     * of Zend_Http_Client if no previous client was set.
     *
     * @return Zend_Http_Client The HTTP client which will be used
     */
    public function getHttpClient()
    {
        if(!($this->httpClient instanceof \Zend\Http\Client)) {
            $client = new \Zend\Http\Client();
            
            $client->setConfig(array('maxredirects' => 2,
                                     'timeout' => 5));

            $this->setHttpClient($client);
        }

        $this->httpClient->resetParameters();
        return $this->httpClient;
    }
    
    /**
     * Setter for the forecast
     * 
     * @param string $forecast 
     */
    public function setForecast($forecast)
    {
        $this->forecast = $forecast;
    }
    
    /**
     * Getter for the forecast
     * 
     * @return string 
     */
    public function getForecast()
    {
        return $this->forecast;
    }
    
    /**
     * Sets the city name to be used in requests.
     *
     * @param string $ip The IP address used for query
     * @return Ztools_Service_Google_Weather
     */
    public function setCityName($cityName)
    {
        $this->cityName = $cityName;
        return $this;
    }
    
    /**
     * Gets the city name to be used in requests.
     *
     * @return string $_ip
     */
    public function getCityName()
    {
        return $this->cityName;
    }
    
    /**
     * Sets the language code used in requests.
     *
     * @param string $language The language code
     * @return Ztools_Service_Weather
     */
    public function setLanguage($language)
    {
        $this->language = $language;
        return $this;
    }
    
    /**
     * Gets the language code to be used in requests.
     *
     * @return string $_language
     */
    public function getLanguage()
    {
        return $this->language;
    }
    
    /**
     * @param string $cityName
     * @param string $language 
     */
    public function __construct($cityName, $language = 'en')
    {
        $this->googleBaseUrl = "http://www.google.com";
        $this->serviceApiUrl = $this->googleBaseUrl . '/ig/api';
        
        $this->httpClient = new \Zend\Http\Client($this->getServiceApiUrl());
        
        if (!empty($cityName)) {
            $this->setCityName($cityName);
        }
        
        if (!empty($language)) {
            $this->setLanguage($language);
        }
    }
    
    /**
     * Fetches the weather forecast for the given city.
     */
    public function fetch() 
    {
        $client = $this->getHttpClient();
        $params = array('weather' => $this->getCityName());
        
        if ($this->getLanguage() != null) {
            $params['hl'] = $this->getLanguage();
        }
        
        $client->setParameterGet($params);
        $response = null;
        
        try {
            $response = $client->send();
        } catch(\Exception $e) {
             throw new \Exception("No weather information available.");
        }
        
        if ($response->getStatusCode() != 200) {
            throw new \Exception("No weather information available.");
        }
        
        $xml = new \SimpleXMLElement(utf8_encode($response->getBody()));
        $information = $xml->xpath("/xml_api_reply/weather/forecast_information");
        $current = $xml->xpath("/xml_api_reply/weather/current_conditions");
        $forecastList = $xml->xpath("/xml_api_reply/weather/forecast_conditions");

        foreach ($forecastList as $forecast) {
            $this->lows[] = $forecast->low['data'];
            $this->highs[] = $forecast->high['data'];
            
            $this->icons[] = $this->googleBaseUrl . $forecast->icon['data'];
            
            $this->daysOfWeek[] = $forecast->day_of_week['data'];
            $this->conditions[] = $forecast->condition['data'];
        }

        foreach ($forecastList as $forecast) {
            $this->currentIcon = $this->getGoogleBaseUrl() . $current[0]->icon['data'];
            $this->currentTemperature = intval($current[0]->tempF['data']);
            $this->currentCondition = $current[0]->condition['data'];
        }
        
        $forecastArray = array();
        $count = 0;
        foreach($this->conditions as $data) {
            $forecastArray[] = array(
                "low"         => $this->lows[$count],
                "high"        => $this->highs[$count],
                "icon"        => $this->icons[$count],
                "day_of_week" => $this->daysOfWeek[$count],
                "condition"   => $this->conditions[$count]
            );
            $count++;
        }
        
        $forecast = array(
            "location" => $this->getCityName(),
            "current" => array(
                "icon" => $this->currentIcon,
                "temperature" => $this->currentTemperature,
                "condition" => $this->currentCondition),
            "forecast" => $forecastArray
            );
        
        $this->setForecast($forecast);
        
        return $this;
    }
    
    public function getLocation() {
        $forecast = $this->getForecast();
        return $forecast['location'];
    }
    
    public function getCurrentCondition() {
        $forecast = $this->getForecast();
        return $forecast['current']['condition'];
    }
    
    public function getCurrentIcon() {
        $forecast = $this->getForecast();
        return $forecast['current']['icon'];
    }
    
    public function getCurrentTemperature() {
        $forecast = $this->getForecast();
        return $forecast['current']['temperature'];
    }
    
    public function getForecastCondition($dayNumber) {
        $forecast = $this->getForecast();
        return $forecast['forecast'][$dayNumber]['condition'];
    }
    
    public function getForecastIcon($dayNumber) {
        $forecast = $this->getForecast();
        return $forecast['forecast'][$dayNumber]['icon'];
    }
    
    public function getForecastDayOfWeek($dayNumber) {
        $forecast = $this->getForecast();
        return $forecast['forecast'][$dayNumber]['day_of_week'];
    }
    
    public function getForecastLow($dayNumber) {
        $forecast = $this->getForecast();
        return $forecast['forecast'][$dayNumber]['low'];
    }
    
    public function getForecastHigh($dayNumber) {
        $forecast = $this->getForecast();
        return $forecast['forecast'][$dayNumber]['high'];
    }
}