<?php

use neutrino\App;
class Todos extends App
{
    public function init()
    {
        session_start();
        $_SESSION['items'] = isset($_SESSION['items'])? $_SESSION['items'] : [];

        $this->get('/add', function() {
            $this->display('/add.phtml');
        });

        $this->get('/:id/update', function($id) {
            $this->id = $id;
            $this->name = $_SESSION['items'][$id];

            $this->display('/update.phtml');
        });

        $this->post('/', function() {
            $name = $this->getRequest()->getParam('name');
            $_SESSION['items'][uniqid()] = $name;
            $this->redirect('/');
        });

        $this->put('/:id', function($id) {
            $name = $this->getRequest()->getParam('name');
            $_SESSION['items'][$id] = $name;
            $this->redirect('/');
        });

        $this->delete('/:id', function($id) {
            unset($_SESSION['items'][$id]);
            $this->redirect('/');
        });

        $this->get('/', function() {
            $this->items = $_SESSION['items'];
            $this->display('/list.phtml');
        });


    }
}