<?php

use neutrino\Neutrino,
    neutrino\App;

require_once dirname(__FILE__) . '/../../neutrino/Neutrino.php';

Neutrino::registerAutoLoad();
$app = new App();

$app->get('/:name', function($name) {
    if ($name != 'ly') {
        $this->pass();
    }
    echo "Hello, $name!";
});

$app->get('/:who', function($who) {
    echo "Who: $who";
});

$app->run();