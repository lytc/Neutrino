<?php

require_once dirname(__FILE__) . '/../bootstrap.php';

class Neutrino_App_Test extends PHPUnit_Framework_TestCase
{
    public function testGetRouter()
    {
        $app = new Neutrino_App();
        $router = $app->getRouter();
        $this->assertInstanceOf('Neutrino_Router', $router);
    }

    public function testGetRequest()
    {
        $app = new Neutrino_App();
        $request = $app->getRequest();
        $this->assertInstanceOf('Neutrino_Http_Request', $request);
    }

    public function testGetResponse()
    {
        $app = new Neutrino_App();
        $response = $app->getResponse();
        $this->assertInstanceOf('Neutrino_Http_Response', $response);
    }
}