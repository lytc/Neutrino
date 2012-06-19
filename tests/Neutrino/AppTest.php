<?php
use neutrino\App;

require_once dirname(__FILE__) . '/../bootstrap.php';

class Neutrino_App_Test extends PHPUnit_Framework_TestCase
{
    public function testGetRouter()
    {
        $app = new App();
        $router = $app->getRouter();
        $this->assertInstanceOf('neutrino\Router', $router);
    }

    public function testGetRequest()
    {
        $app = new App();
        $request = $app->getRequest();
        $this->assertInstanceOf('neutrino\http\Request', $request);
    }

    public function testGetResponse()
    {
        $app = new App();
        $response = $app->getResponse();
        $this->assertInstanceOf('neutrino\http\Response', $response);
    }
}