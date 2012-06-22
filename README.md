Neutrino
========

A small PHP framework for PHP ~> 5.4

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