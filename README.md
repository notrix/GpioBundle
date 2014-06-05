Gpio Bundle
==========

A Symfony2 bundle to operate with raspberry pi gpio pins

Features include:

- Easy IO pin configuration (add humanized slugs instead numbers)
- Command to setup pins from configuration to raspberry file system
- Watch command to listen input status changes
- Command to read input pin status and set output pin status
- Configure Symfony events to be triggered on input pin status change

Installation
------------

GpioBundle is available on Packagist ([notrix/gpio-bundle](http://packagist.org/packages/notrix/gpio-bundle))
and is installable via [Composer](http://getcomposer.org/).

```bash
php composer.phar require notrix/gpio-bundle 'dev-master'
```

Configuration
------------

Possible configuration options are listed below

``` yaml
# app/config/config.yml
notrix_gpio:
    sudo: true # Run commands with sudo permissions for raspberry
    development: false # true will use fake raspberry lib to imitate getting and setting pin statuses
    watcher_interval: 0.35 # how often poll input files for status changes
    in: # here you can configure input pins
        18: # this is rasperry's internal pin number 
            slug: pir_sensor1 # your custom slug to identify pin
            event: { on: pir_sensor_on, off: pir_sensor_off } # symfony event names. You can attach an event listeners to them
        23:
            slug: pir_sensor2
            event: [ pir_sensor_triggered ] # one event on both statuses on and off
    out: # here you can configure output pins
        17:
            slug: blue_led # slug to identify current pin
        22:
            slug: red_led
```

**Note:** As this bundle has a dependancy on ronanguilloux/php-gpio development lib version, your project minimum stability has to be 'dev' or include this vendor as a dependancy to your project with @dev stability flag

Usage
--

You should allways use manager service 'notrix_gpio.pin_manager' to controll your pins.

Write [Event listeners](http://symfony.com/doc/current/cookbook/service_container/event_listener.html) for input pins.

Run 'php app/console notrix:gpio:setup' to initialize pins

Run 'php app/console notrix:gpio:watch -vv' to see how symfony reacts to pin status changes and with no '-vv' to run it quietly and do the listeners job.

About
-----

GpioBundle is a [NoTriX](https://notrix.lt) initiative.
If you wanna be in the list of [contributors](https://github.com/notrix/GpioBundle/contributors) feel free to fork, update, extend and PR your changes. Thanks.
