<?php
require_once dirname(__FILE__) . '/../../neutrino/Neutrino.php';

Neutrino::registerAutoLoad();
$app = new Neutrino_App();

$app->get('/:name', function($name) {
    echo "Hello, $name!";
});

$app->run();