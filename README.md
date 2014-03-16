THIS PROJECT DOESN'T MAINTAIN ANYMORE
I'M RECOMMEND TO USE [LAZY FRAMEWORK](https://github.com/lytc/lazy) INSTEAD

Neutrino
========

Neutrino is a PHP 5.4 micro framework that helps you quickly write simple yet powerful web applications and APIs. Neutrino is easy to use for both beginners and professionals.

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
