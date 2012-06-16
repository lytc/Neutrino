<?php
require_once dirname(__FILE__) . '/../../neutrino/Neutrino.php';

Neutrino::registerAutoLoad();

$app = new Neutrino();

$app->run();