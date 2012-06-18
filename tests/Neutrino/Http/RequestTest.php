<?php
require_once dirname(__FILE__) . '/../../bootstrap.php';

class Neutrino_Http_Request_Test extends PHPUnit_Framework_TestCase
{
    protected function _createRequest()
    {
        $app = new Neutrino_App();
        return $app->getRequest();
    }

    public function testGetUri()
    {
        $request = new Neutrino_Http_Request();

        $uri = '/posts/view/1';
        $_SERVER['REQUEST_URI'] = $uri;

        $this->assertEquals($uri, $request->getUri());
    }

    public function testGetServer()
    {
        $request = new Neutrino_Http_Request();
        $_SERVER['TEST_ENV'] = 'test';

        $this->assertEquals('test', $request->getServer('TEST_ENV'));
    }

    public function testGetHeaders()
    {
        $request = new Neutrino_Http_Request();

        $this->assertEquals('array', gettype($request->getHeaders()));
    }

    public function testGetHeader()
    {
        $request = new Neutrino_Http_Request();

        $_SERVER['HTTP_TEST_HEADER'] = 'test';

        $this->assertNull($request->getHeader('NON_EXISTING_HEADER'));
        $this->assertEquals('test', $request->getHeader('TEST_HEADER'));
    }

    public function testGetMethod()
    {
        $request = new Neutrino_Http_Request();

        $_SERVER['REQUEST_METHOD'] = 'GET';

        $this->assertEquals('GET', $request->getMethod());
    }

    public function testIsGet()
    {
        $request = new Neutrino_Http_Request();

        $_SERVER['REQUEST_METHOD'] = 'GET';

        $this->assertTrue($request->isGet());
    }

    public function testIsPost()
    {
        $request = new Neutrino_Http_Request();

        $_SERVER['REQUEST_METHOD'] = 'POST';

        $this->assertTrue($request->isPost());
    }

    public function testIsPut()
    {
        $request = new Neutrino_Http_Request();

        $_SERVER['REQUEST_METHOD'] = 'PUT';

        $this->assertTrue($request->isPut());
    }

    public function testIsDelete()
    {
        $request = new Neutrino_Http_Request();

        $_SERVER['REQUEST_METHOD'] = 'DELETE';

        $this->assertTrue($request->isDelete());
    }

    public function testIsOptions()
    {
        $request = new Neutrino_Http_Request();

        $_SERVER['REQUEST_METHOD'] = 'OPTIONS';

        $this->assertTrue($request->isOptions());
    }

    public function testIsHead()
    {
        $request = new Neutrino_Http_Request();

        $_SERVER['REQUEST_METHOD'] = 'HEAD';

        $this->assertTrue($request->isHead());
    }

    public function testIsXhr()
    {
        $request = new Neutrino_Http_Request();

        $_SERVER['X_REQUESTED_WITH'] = 'XMLHttpRequest';

        $this->assertTrue($request->isXhr());
    }

    public function testGetGetParam()
    {
        $request = new Neutrino_Http_Request();
        $_GET['test'] = 'test';

        $this->assertEquals('test', $request->getGetParam('test'));
    }

    public function testGetPostParam()
    {
        $request = new Neutrino_Http_Request();
        $_POST['test'] = 'test';

        $this->assertEquals('test', $request->getPostParam('test'));
    }

    public function testGetCookieParam()
    {
        $request = new Neutrino_Http_Request();
        $_COOKIE['test'] = 'test';

        $this->assertEquals('test', $request->getCookieParam('test'));
    }

    public function testGetParam()
    {
        $request = new Neutrino_Http_Request();

        $_REQUEST['test'] = 'test';
        $_REQUEST['test2'] = 'test2';


        $this->assertEquals('test', $request->getParam('test'));
        $this->assertEquals('test2', $request->getParam('test2'));
    }

    public function testGetAllParams()
    {
        $request = new Neutrino_Http_Request();

        $this->assertEquals([], $request->getAllParams());

        $_REQUEST['test'] = 'test';
        $_REQUEST['test2'] = 'test2';

        $allParams = $request->getAllParams();
        $this->assertEquals(2, count($allParams));
        $this->assertContains('test', $allParams);
        $this->assertContains('test2', $allParams);
    }

    public function testGetSomeParams()
    {
        $request = new Neutrino_Http_Request();

        $_REQUEST['test'] = 'test';
        $_REQUEST['test2'] = 'test2';
        $_REQUEST['test3'] = 'test3';
        $_REQUEST['test4'] = 'test4';
        $_REQUEST['test5'] = 'test5';

        $params = $request->getSomeParams('test2', 'test4');
        $this->assertEquals(['test2' => 'test2', 'test4' => 'test4'], $params);

        $params = $request->getSomeParams(['test', 'test3']);
        $this->assertEquals(['test' => 'test', 'test3' => 'test3'], $params);
    }
}