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

    public function testRun()
    {
        $_SERVER['REQUEST_URI'] = '/test';
        $_SERVER['REQUEST_METHOD'] = 'GET';

        $app = new App();
        $app->get('/test', function() {});

        $response = $app->run();
        $this->assertInstanceOf('neutrino\http\Response', $response);
    }

    /**
     * @expectedException \neutrino\exception\NotFound
     * @expectedExceptionMessage Page not found
     */
    public function testRunNotMatchShouldThrowException()
    {
        $app = new App();
        $app->get('/test', function() {});
        $app->run();
    }

    public function testPass()
    {
        $_SERVER['REQUEST_URI'] = '/test';
        $_SERVER['REQUEST_METHOD'] = 'GET';

        $app = new App();
        $test = 0;

        $app->get('/test', function() use(&$test) {
            $this->pass();
            $test = 1;
        });

        $app->get('/:what', function() use(&$test) {
            $test = 2;
        });

        $app->run();

        $this->assertEquals(2, $test);
    }

    public function testHaltWithNoParam()
    {
        $_SERVER['REQUEST_URI'] = '/test';
        $_SERVER['REQUEST_METHOD'] = 'GET';

        $app = new App();
        $test = 0;

        $app->get('/test', function() use(&$test) {
            $this->halt();
            $test = 1;
        });

        $response = $app->run();
        $this->assertEquals(0, $test);
        $this->assertEquals(500, $response->getCode());
        $this->assertEquals([], $response->getHeaders());
        $this->assertEquals('', $response->getBody());
    }

    public function testHaltWith1Param()
    {
        $_SERVER['REQUEST_URI'] = '/test';
        $_SERVER['REQUEST_METHOD'] = 'GET';

        $app = new App();
        $test = 0;

        $app->get('/test', function() use(&$test) {
            $this->halt('message');
            $test = 1;
        });

        $response = $app->run();
        $this->assertEquals(0, $test);
        $this->assertEquals(500, $response->getCode());
        $this->assertEquals([], $response->getHeaders());
        $this->assertEquals('message', $response->getBody());
    }

    public function testHaltWith2Param()
    {
        $_SERVER['REQUEST_URI'] = '/test';
        $_SERVER['REQUEST_METHOD'] = 'GET';

        $app = new App();
        $test = 0;

        $app->get('/test', function() use(&$test) {
            $this->halt(401,  'message');
            $test = 1;
        });

        $response = $app->run();
        $this->assertEquals(0, $test);
        $this->assertEquals(401, $response->getCode());
        $this->assertEquals([], $response->getHeaders());
        $this->assertEquals('message', $response->getBody());
    }

    public function testBefore()
    {

    }

    public function testAfter()
    {

    }
}