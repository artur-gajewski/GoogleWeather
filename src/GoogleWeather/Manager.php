<?php

namespace GoogleWeather;

use GoogleWeather\Google\Weather;

class Manager
{
    /**
     * @var Array 
     */
    protected $params;
    
    /**
     * @var array
     */
    protected $cache;
    
    /**
     * Set the Module specific configuration parameters
     * 
     * @param Array $params
     */
    public function __construct($params) {
        $this->params = $params;
        $this->cache = array();
    }

    /**
     * Get the forecast for given location
     * 
     * @param string $cityName
     * @param string $language
     * @return array 
     * @throws \Exception 
     */
    public function getWeather($cityName, $language)
    {
        if (isset($this->cache[$cityName])) {
            $weather = $this->cache[$cityName];
        } else {
            $weather = new Weather($cityName, $language);
            
            // Cache the weather object so we don't have to do HTTP request on each call
            // Enables to get multiple object's properties at different times
            $this->cache[$cityName] = $weather;
        }
        
        if (!$weather) {
            throw new \Exception('Location does not exist.');
        }
        
        return $weather->fetch();
    }
    
}
