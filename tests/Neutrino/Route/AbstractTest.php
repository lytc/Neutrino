<?php

require_once dirname(__FILE__) . '/../../bootstrap.php';

class MyRoute extends Neutrino_Route_Abstract
{
    public function match($uri) {}
}

class Neutrino_Route_Abstract_Test extends PHPUnit_Framework_TestCase
{
    public function testCreateInstance()
    {
        $route = MyRoute::createInstance('/test', function() {});
        $this->assertInstanceOf('MyRoute', $route);
    }

    /**
     * @expectedException Neutrino_Route_Exception
     * @expectedExceptionMessage The request method must be GET, POST, PUT, DELETE, OPTIONS or HEAD. 'TRACE' given.
     */
    public function testWithUnExpectedRequestMethodShouldThrowException()
    {
        MyRoute::createInstance('/test', function() {}, 'TRACE');
    }

    public function testGetPattern()
    {
        $pattern = '/test';
        $route = new MyRoute($pattern, function() {});
        $this->assertEquals($pattern, $route->getPattern());
    }

    public function testGetMethod()
    {
        $route = new MyRoute('/test', function() {});
        $this->assertEquals(Neutrino::METHOD_GET, $route->getMethod());
    }

    public function testGetCallable()
    {
        $callable = function() {};
        $route = new MyRoute('/test', $callable);
        $this->assertEquals($callable, $route->getCallable());
    }

    public function testGet()
    {
        $pattern = '/test';
        $callable = function() {};

        $route = MyRoute::get($pattern, $callable);

        $this->assertEquals(Neutrino::METHOD_GET, $route->getMethod());
    }

    public function testPost()
    {
        $pattern = '/test';
        $callable = function() {};

        $route = MyRoute::post($pattern, $callable);

        $this->assertEquals(Neutrino::METHOD_POST, $route->getMethod());
    }

    public function testPut()
    {
        $pattern = '/test';
        $callable = function() {};

        $route = MyRoute::put($pattern, $callable);

        $this->assertEquals(Neutrino::METHOD_PUT, $route->getMethod());
    }

    public function testDelete()
    {
        $pattern = '/test';
        $callable = function() {};

        $route = MyRoute::delete($pattern, $callable);

        $this->assertEquals(Neutrino::METHOD_DELETE, $route->getMethod());
    }
}