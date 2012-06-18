<?php

require_once dirname(__FILE__) . '/../bootstrap.php';

class Neutrino_Router_Test extends PHPUnit_Framework_TestCase
{
    protected function _createRouter($allowDuplicate = false)
    {
        $app = new Neutrino_App();
        $router = new Neutrino_Router($app, $allowDuplicate);
        return $router;
    }

    public function testConstROUTER_NAMED_CLASS()
    {
        $this->assertEquals(Neutrino_Router::ROUTER_NAMED_CLASS, 'Neutrino_Route_Named');
    }

    public function testConstROUTER_REGEX_CLASS()
    {
        $this->assertEquals(Neutrino_Router::ROUTER_REGEX_CLASS, 'Neutrino_Route_Regex');
    }

    public function testGetDefaultRouteClass()
    {
        $this->assertEquals($this->_createRouter()->getDefaultRouteClass(), 'Neutrino_Route_Named');
    }

    /**
     * @expectedException Neutrino_Router_Exception
     * @expectedExceptionMessage Default route class must be an instance of Neutrino_Route_Abstract
     */
    public function testSetDefaultRouteClassShouldThrowException()
    {
        $this->_createRouter()->setDefaultRouteClass('Neutrino');
    }

    public function testSetDefaultRouteClass()
    {
        $router = $this->_createRouter();
        $router->setDefaultRouteClass('Neutrino_Route_Regex');

        $this->assertEquals($router->getDefaultRouteClass(), 'Neutrino_Route_Regex');
    }

    public function testAdd()
    {
        $router = $this->_createRouter();
        $route = new Neutrino_Route_Named('/test', function() {});
        $router->add($route);

        $this->assertEquals($router->getRoutes(), [$route]);
    }

    /**
     * @expectedException Neutrino_Router_Exception
     * @expectedExceptionMessage Duplicate route with pattern '/test'
     */
    public function testNotAllowedDuplicateRouteShouldThrowException()
    {
        $router = $this->_createRouter();

        $route1 = new Neutrino_Route_Named('/test', function() {});
        $route2 = new Neutrino_Route_Named('/test', function() {});

        $router->add($route1);
        $router->add($route2);
    }

    public function testMap()
    {
        $router = $this->_createRouter();

        $router->map('/test', function() {});
        $router->map('#/test2', function() {});

        $routes = $router->getRoutes();
        $this->assertEquals(count($routes), 2);
        $this->assertInstanceOf('Neutrino_Route_Named', $routes[0]);
        $this->assertInstanceOf('Neutrino_Route_Regex', $routes[1]);
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