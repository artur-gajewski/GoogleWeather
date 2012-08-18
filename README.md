# GoogleWeather module for Zend Framework 2

This module provides a way to obtain weather forecast for a given city and use view helper to display taht information on your view scripts.


Requirements:

- PHP 5.3
- Zend Framework 2
- An active internet connection (module fetches forecast from Google)

See [https://github.com/artur-gajewski/GoogleWeather](https://github.com/artur-gajewski/GoogleWeather)

@Author: Artur Gajewski


## Installation

Go to your vendor directory and clone the module from Github:

```php
git clone https://github.com/artur-gajewski/GoogleWeather
```

To install all needed dependancies, run the Composer:

```php
php composer.phar install
```

Then add 'GoogleWeather' into the Module array in APPLICATION_ROOT/config/application.config.php

```php
<?php
return array(
    'modules' => array(
        ...
        'GoogleWeather',
        ...
    ),
);
```


## Accessing GoogleWeather from a controller

GoogleWeather module is accessible via Service Locator:

```php
$weather = $this->getServiceLocator()->get('GoogleWeather');
```

When you obtain the service and create the object, you can then use it to do the magic:

```php
$forecast = $weather->getWeather('Helsinki', 'en');
```

The return value is an object containing current and future weather information.

You can access forecast information with following functions:

#### getLocation()        

Get the name of the forecast's location.

#### getCurrentCondition()
        
Get the current weather condition in selected language.
    
#### getCurrentIcon()
        
Get the URL to the image representing the current weather.
    
#### getCurrentTemperature()
        
Get the current temperature in local degrees and type (celcius or fahrenheit).
    
#### getForecastCondition($dayNumber)

Get the forecasted weather condition in selected language for the given day in array, 0 being today.

#### getForecastIcon($dayNumber)

Get the forecasted weather image for the given day in array, 0 being today.

#### getForecastDayOfWeek($dayNumber)

Get the string representation of day of the week for the given day in array, 0 being today.
    
#### getForecastLow($dayNumber)
        
Get the lowest forecasted temperature in local degrees and type for the given day in array, 0 being today.
    
#### getForecastHigh($dayNumber)
        
Get the highest forecasted temperature in local degrees and type for the given day in array, 0 being today.


## Usage of GoogleWeather view helper

You can use GoogleWeather in your view scripts by using a view helper class provided.

```php
<h2>
    <?php echo $this->getWeather('Helsinki')->getLocation(); ?>
</h2>
<img src="<?php echo $this->getWeather('Helsinki')->getForecastIcon(0); ?>"/>
<ul>
    <li>
        Week of day: <?php echo $this->getWeather('Helsinki')->getForecastDayOfWeek(0); ?>
    </li>
    <li>
        Condition: <?php echo $this->getWeather('Helsinki')->getForecastCondition(0); ?>
    </li>
    <li>
        Lowest temperature: <?php echo $this->getWeather('Helsinki')->getForecastLow(0); ?>
    </li>
    <li>
        Highest temperature: <?php echo $this->getWeather('Helsinki')->getForecastHigh(0); ?>
    </li>
</ul>

```


## Questions or comments?

Feel free to email me with any questions or comments about this module

[info@arturgajewski.com](mailto:info@arturgajewski.com)