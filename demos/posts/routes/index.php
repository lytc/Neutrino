<?php

use neutrino\App;
class Index extends App
{
    public function init()
    {
        $this->get('/', function() {
            echo 'index';
        });
    }
}