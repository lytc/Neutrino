<?php

require_once dirname(__FILE__) . '/../../bootstrap.php';

class Neutrino_Http_Response_Test extends PHPUnit_Framework_TestCase
{
    public function testSetHeaderAndGetHeaders()
    {
        $app = new Neutrino_App();
        $response = $app->getResponse();

        $response->setHeader('Content-Type', 'application/json');
        $expected = [['name' => 'Content-Type', 'value' => 'application/json', 'replace' => true, 'code' => null]];
        $this->assertEquals($expected, $response->getHeaders());

        $response->setHeader('Content-Type', 'text/html', false, 200);
        $expected[] = ['name' => 'Content-Type', 'value' => 'text/html', 'replace' => false, 'code' => 200];
        $this->assertEquals($expected, $response->getHeaders());
    }

    public function testSetCodeAndGetCode()
    {
        $app = new Neutrino_App();
        $response = $app->getResponse();

        $this->assertEquals(200, $response->getCode());

        $response->setCode(404);
        $this->assertEquals(404, $response->getCode());
    }

    public function testSetMessageAndGetMessage()
    {
        $app = new Neutrino_App();
        $response = $app->getResponse();

        $this->assertEquals('OK', $response->getMessage());

        $response->setMessage('Message');
        $this->assertEquals('Message', $response->getMessage());
    }

    public function testSetBodyAndGetBody()
    {
        $app = new Neutrino_App();
        $response = $app->getResponse();

        $this->assertEquals('', $response->getBody());

        $response->setBody('body');
        $this->assertEquals('body', $response->getBody());
    }

    public function testRedirect()
    {
        $app = new Neutrino_App();
        $response = $app->getResponse();

        $response->redirect('/test');
        $expected = [['name' => 'Location', 'value' => '/test', 'replace' => true, 'code' => 302]];
        $this->assertEquals($expected, $response->getHeaders());
    }

    public function testRedirectWithCode()
    {
        $app = new Neutrino_App();
        $response = $app->getResponse();

        $response->redirect('/test', 304);
        $expected = [['name' => 'Location', 'value' => '/test', 'replace' => true, 'code' => 304]];
        $this->assertEquals($expected, $response->getHeaders());
    }

    /**
     * @expectedException Neutrino_Http_Response_Exception
     * @expectedExceptionMessage Redirect code must be >= 300 and <= 307
     */
    public function testRedirectShouldThrowExceptionWithWrongCode()
    {
        $app = new Neutrino_App();
        $response = $app->getResponse();

        $response->redirect('/test', 500);
    }
}