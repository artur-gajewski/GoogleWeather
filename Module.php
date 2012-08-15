<?php

namespace GoogleWeather;

use GoogleWeather\View\Helper\GoogleWeather;

class Module
{
    public function getAutoloaderConfig()
    {
        return array(
            'Zend\Loader\ClassMapAutoloader' => array(
                __DIR__ . '/autoload_classmap.php',
            ),
            'Zend\Loader\StandardAutoloader' => array(
                'namespaces' => array(
                    __NAMESPACE__ => __DIR__ . '/src/' . __NAMESPACE__,
                ),
            ),
        );
    }
    
    public function getServiceConfig()
    {
        return array(
            'factories' => array(
                'GoogleWeather' => 'GoogleWeather\Service\Factory',
            )
        ); 
    }
    
    public function getConfig()
    {
        return include __DIR__ . '/config/module.config.php';
    }
    
    public function getViewHelperConfig()
    {
        return array(
            'factories' => array(
                'getWeather' => function ($sm) {
                    $locator = $sm->getServiceLocator();
                    $config = $locator->get('Configuration');
                    $params = $config['GoogleWeather']['params'];
                    
                    $viewHelper = new View\Helper\GoogleWeather();
                    $viewHelper->setService($locator->get('GoogleWeather'));
                    $viewHelper->setParams($params);
                    
                    return $viewHelper;
                },
            ),
        );

    }


    
}