<?php
use neutrino\Neutrino,
    neutrino\App,
    neutrino\route\AbstractRoute;

require_once dirname(__FILE__) . '/../../bootstrap.php';

class MyRoute extends AbstractRoute
{
    protected function _matchUri($uri) {}
}

class Neutrino_Route_Abstract_Test extends PHPUnit_Framework_TestCase
{
    public function testCreateInstance()
    {
        $route = MyRoute::createInstance('/test', function() {});
        $this->assertInstanceOf('MyRoute', $route);
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

    public function testGetCallback()
    {
        $callback = function() {};
        $route = new MyRoute('/test', $callback);
        $this->assertEquals($callback, $route->getCallback());
    }

    public function testGet()
    {
        $pattern = '/test';
        $callback = function() {};

        $route = MyRoute::get($pattern, $callback);

        $this->assertEquals(Neutrino::METHOD_GET, $route->getMethod());
    }

    public function testPost()
    {
        $pattern = '/test';
        $callback = function() {};

        $route = MyRoute::post($pattern, $callback);

        $this->assertEquals(Neutrino::METHOD_POST, $route->getMethod());
    }

    public function testPut()
    {
        $pattern = '/test';
        $callback = function() {};

        $route = MyRoute::put($pattern, $callback);

        $this->assertEquals(Neutrino::METHOD_PUT, $route->getMethod());
    }

    public function testDelete()
    {
        $pattern = '/test';
        $callback = function() {};

        $route = MyRoute::delete($pattern, $callback);

        $this->assertEquals(Neutrino::METHOD_DELETE, $route->getMethod());
    }

    /**
     * @expectedException \neutrino\Exception\NotFound
     */
    public function testMathWithConditionsShouldThrowException()
    {
        $_SERVER['REQUEST_METHOD'] = 'GET';
        $_SERVER['REQUEST_URI'] = '/foo/bar';
        $_SERVER['HTTP_USER_AGENT'] = 'Mozilla/5.0 (Macintosh; Intel Mac OS X 10_7_4) AppleWebKit/536.5 (KHTML, like Gecko) Chrome/19.0.1084.56 Safari/536.5';

        $app = new App();
        $app->get('/foo/bar', ['agent' => 'firefox'], function() {});
        $app->run();
    }

    public function testMathWithConditions()
    {
        $_SERVER['REQUEST_METHOD'] = 'GET';
        $_SERVER['REQUEST_URI'] = '/foo/bar';
        $_SERVER['HTTP_USER_AGENT'] = 'Mozilla/5.0 (Macintosh; Intel Mac OS X 10_7_4) AppleWebKit/536.5 (KHTML, like Gecko) Chrome/19.0.1084.56 Safari/536.5';

        $app = new App();
        $result = 0;
        $app->get('/foo/bar', ['agent' => 'chrome'], function() use (&$result) {
            $result = 1;
        });
        $app->run();

        $this->assertEquals(1, $result);
    }
}