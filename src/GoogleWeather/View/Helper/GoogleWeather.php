<?php

namespace GoogleWeather\View\Helper;

use Zend\View\Helper\AbstractHelper;
use Zend\Http\Request;

class GoogleWeather extends AbstractHelper
{
    /**
     * @var GoogleWeather Service
     */
    protected $service;
    
    /**
     * @var array $params
     */
    protected $params;
    
    /**
     * Called upon invoke
     * 
     * @param integer $id
     * @return GoogleWeather\Google\Weather
     */
    public function __invoke($cityName, $language = 'en')
    {
        $forecast = $this->service->getWeather($cityName, $language);
        return $forecast;
    }

    /**
     * Get GoogleWeather service.
     *
     * @return $this->service
     */
    public function getService()
    {
        return $this->service;
    }

    /**
     * Set GoogleWeather service.
     *
     * @param $service
     */
    public function setService($service)
    {
        $this->service = $service;
        return $this;
    }
    
    /**
     * Get GoogleWeather params.
     *
     * @return $this->params
     */
    public function getParams()
    {
        return $this->params;
    }

    /**
     * Set GoogleWeather params.
     *
     * @param array $params
     */
    public function setParams(Array $params)
    {
        $this->params = $params;
        return $this;
    }
    
}