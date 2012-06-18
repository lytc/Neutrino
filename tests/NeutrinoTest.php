<?php

require_once dirname(__FILE__) . '/bootstrap.php';

class Neutrino_Test extends PHPUnit_Framework_TestCase
{
    public function testMap()
    {
        $_SERVER['REQUEST_METHOD'] = 'GET';
        $_SERVER['REQUEST_URI'] = '/posts/1';

        $app1 = Neutrino::map('/test', function() {
            return $this->run('Posts');
        });
        $this->assertNull($app1);

        $app2 = Neutrino::map('/posts', function() {
            return $this->run('Posts');
        });

        $this->assertInstanceOf('Posts', $app2);
        $this->assertEquals("Id: 1", $app2->getResponse()->getBody());
    }
}

class Posts extends Neutrino_App
{
    public function init()
    {
        $this->get('/:id', function($id) {
            $this->getResponse()->setBody("Id: $id");
        });
    }
}