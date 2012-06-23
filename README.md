Neutrino
========

A small PHP framework for PHP ~> 5.4

## Release infomation
Neutrino framework release 1.0-dev3
THIS RELEASE IS A DEVELOPMENT RELEASE AND NOT INTENDED FOR PRODUCTION USE. PLEASE USE AT YOUR OWN RISK

##"Hello World" application
```php
<?php
use neutrino\Neutrino,
    neutrino\App;

require_once 'neutrino/Neutrino.php';

Neutrino::registerAutoLoad();
$app = new App();

$app->get('/', function() {
    echo "Hello World!";
});

$app->run();
```