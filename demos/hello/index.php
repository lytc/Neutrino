<?php
use neutrino\Neutrino,
    neutrino\App;

require_once dirname(__FILE__) . '/../../neutrino/Neutrino.php';

Neutrino::registerAutoLoad();
$app = new App();

$app->get('/:name', function($name) {
    echo "Hello, $name!";
});

$app->run();