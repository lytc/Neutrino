<?php
use neutrino\Neutrino,
neutrino\App;

require_once dirname(__FILE__) . '/../../neutrino/Neutrino.php';

Neutrino::registerAutoLoad();

require_once dirname(__FILE__) . '/app/routes/Todos.php';

$app = new Todos();
$app->setViewPath(dirname(__FILE__) . '/app/views');
$app->run();