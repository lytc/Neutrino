<?php
ini_set('display_errors', true);

require_once dirname(__FILE__) . '/../../neutrino/Neutrino.php';

Neutrino::registerAutoLoad();
$app = new Neutrino_App();
//$app->run();