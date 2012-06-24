<?php
use neutrino\Neutrino,
    neutrino\App,
    neutrino\Router,
    neutrino\route\Named;

require_once dirname(__FILE__) . '/../bootstrap.php';

class Router_Test extends PHPUnit_Framework_TestCase
{
    protected function _createRouter($allowDuplicate = false)
    {
        $app = new App();
        $router = new Router($app, $allowDuplicate);
        return $router;
    }

    public function testConstROUTER_NAMED_CLASS()
    {
        $this->assertEquals('neutrino\route\Named', Router::ROUTER_NAMED_CLASS);
    }

    public function testConstROUTER_REGEX_CLASS()
    {
        $this->assertEquals('neutrino\route\Regex', Router::ROUTER_REGEX_CLASS);
    }

    public function testGetDefaultRouteClass()
    {
        $this->assertEquals('neutrino\route\Named', $this->_createRouter()->getDefaultRouteClass());
    }

    /**
     * @expectedException neutrino\router\Exception
     * @expectedExceptionMessage Default route class must be an instance of neutrino\route\AbstractRoute
     */
    public function testSetDefaultRouteClassShouldThrowException()
    {
        $this->_createRouter()->setDefaultRouteClass('stdClass');
    }

    public function testSetDefaultRouteClass()
    {
        $router = $this->_createRouter();
        $router->setDefaultRouteClass('neutrino\route\Regex');

        $this->assertEquals($router->getDefaultRouteClass(), 'neutrino\route\Regex');
    }

    public function testAdd()
    {
        $router = $this->_createRouter();
        $route = new Named('/test', function() {});
        $router->add($route);

        $this->assertEquals($router->getRoutes(), [$route]);
    }

    public function testMap()
    {
        $router = $this->_createRouter();

        $router->map('/test', function() {});
        $router->map('#/test2', function() {});

        $routes = $router->getRoutes();
        $this->assertEquals(count($routes), 2);
        $this->assertInstanceOf('neutrino\route\Named', $routes[0]);
        $this->assertInstanceOf('neutrino\route\Regex', $routes[1]);
    }

    public function testGet()
    {
        $router = $this->_createRouter();
        $router->get('/test', function() {});
        $this->assertEquals(Neutrino::METHOD_GET, $router->getRoutes()[0]->getMethod());
    }

    public function testPost()
    {
        $router = $this->_createRouter();
        $router->post('/test', function() {});
        $this->assertEquals(Neutrino::METHOD_POST, $router->getRoutes()[0]->getMethod());
    }

    public function testPut()
    {
        $router = $this->_createRouter();
        $router->put('/test', function() {});
        $this->assertEquals(Neutrino::METHOD_PUT, $router->getRoutes()[0]->getMethod());
    }

    public function testDelete()
    {
        $router = $this->_createRouter();
        $router->delete('/test', function() {});
        $this->assertEquals(Neutrino::METHOD_DELETE, $router->getRoutes()[0]->getMethod());
    }

    public function testDispatch()
    {
        $this->markTestIncomplete('This test has not been implemented yet');
    }
}