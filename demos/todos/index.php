<?php
use neutrino\Neutrino,
neutrino\App;

require_once dirname(__FILE__) . '/../../neutrino/Neutrino.php';

Neutrino::registerAutoLoad();
$app = new App();
$app->setViewPath(dirname(__FILE__) . '/app/views');

$app->get('/posts/view', function() {
    $this->name = 'tran cong ly';
    $this->display('/posts/view.phtml');
});

$app->run();