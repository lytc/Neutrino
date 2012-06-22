<?php
use neutrino\Neutrino,
    neutrino\App;

require_once dirname(__FILE__) . '/../../neutrino/Neutrino.php';

Neutrino::registerAutoLoad();
$app = new App();

$app->get('/', function() {
    echo "Hello World!";
});

$app->run();