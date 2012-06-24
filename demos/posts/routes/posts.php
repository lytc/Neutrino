<?php

use neutrino\App;

class Posts extends App
{
    public function init()
    {
        $this->get('#^/$', function() {
            echo 'posts index';
        });

        $this->get('#^/(\d+)$', function($id) {
            echo $id;
        });
    }
}