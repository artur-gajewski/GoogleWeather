<?php

namespace GoogleWeather\Service;

use Zend\ServiceManager\FactoryInterface,
    Zend\ServiceManager\ServiceLocatorInterface,
    GoogleWeather\Manager;

/**
* GoogleWeather service manager factory
*/
class Factory implements FactoryInterface
{
    /**
     * Factory method for GoogleWeather Manager service
     *
     * @param ServiceLocatorInterface $serviceLocator
     * @return GoogleWeather\Manager
     */
    public function createService(ServiceLocatorInterface $serviceLocator)
    {
        $config = $serviceLocator->get('Configuration');
        $params = $config['GoogleWeather']['params'];
        
        $manager = new Manager($params);
        return $manager;
    }
}