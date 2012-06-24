<?php
use neutrino\Neutrino,
    neutrino\App;

require_once dirname(__FILE__) . '/bootstrap.php';

class Neutrino_Test extends PHPUnit_Framework_TestCase
{
    /**
     * @expectedException \neutrino\exception\NotFound
     */
    public function testMapShouldThrowNotFoundException()
    {
        $neutrino = Neutrino::getInstance();
        $neutrino->map('/foo', function() {});
        $neutrino->run();
    }

    public function testMap()
    {
        $_SERVER['REQUEST_METHOD'] = 'GET';
        $_SERVER['REQUEST_URI'] = '/posts/1';

        $neutrino = Neutrino::getInstance();
        $neutrino->map('/posts', function() {
            $this->run('Posts');
        });
        $neutrino->run();
    }
}

class Posts extends App
{
    public function init()
    {
        $this->get('/:id', function($id) {
            $this->getResponse()->setBody("Id: $id");
        });
    }
}