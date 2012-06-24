<?php
use neutrino\Neutrino,
neutrino\App;

require_once dirname(__FILE__) . '/../../neutrino/Neutrino.php';

Neutrino::registerAutoLoad();

$neutrino = Neutrino::getInstance();

$neutrino->map('/posts', function() {
    require_once 'routes/posts.php';
    $this->run('Posts');
});

$neutrino->map('/', function() {
    require_once 'routes/index.php';
    $this->run('Index');
});

$neutrino->run();