<?php
ini_set('display_errors', true);
require_once dirname(__FILE__) . '/../../neutrino/Neutrino.php';

Neutrino::registerAutoLoad();
$app = new Neutrino();

$app->get('/:name', function($name) {
    echo "Hello, $name!";
});

$app->run();