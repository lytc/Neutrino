<?php
use neutrino\App,
    neutrino\http\Request;

require_once dirname(__FILE__) . '/../../bootstrap.php';

class Request_Test extends PHPUnit_Framework_TestCase
{
    protected function _createRequest()
    {
        $app = new App();
        return $app->getRequest();
    }

    public function testGetUri()
    {
        $request = new Request();

        $uri = '/posts/view/1';
        $_SERVER['REQUEST_URI'] = $uri;

        $this->assertEquals($uri, $request->getUri());
    }

    public function testGetServer()
    {
        $request = new Request();
        $_SERVER['TEST_ENV'] = 'test';

        $this->assertEquals('test', $request->getServer('TEST_ENV'));
    }

    public function testGetHeaders()
    {
        $request = new Request();

        $this->assertEquals('array', gettype($request->getHeaders()));
    }

    public function testGetHeader()
    {
        $request = new Request();

        $_SERVER['HTTP_TEST_HEADER'] = 'test';

        $this->assertNull($request->getHeader('NON_EXISTING_HEADER'));
        $this->assertEquals('test', $request->getHeader('TEST_HEADER'));
    }

    public function testGetMethod()
    {
        $request = new Request();

        $_SERVER['REQUEST_METHOD'] = 'POST';

        $this->assertEquals('POST', $request->getMethod());

        $request->setParam('__METHOD__', 'DELETE');
        $this->assertEquals('DELETE', $request->getMethod());
    }

    public function testIsGet()
    {
        $request = new Request();

        $_SERVER['REQUEST_METHOD'] = 'GET';

        $this->assertTrue($request->isGet());
    }

    public function testIsPost()
    {
        $request = new Request();

        $_SERVER['REQUEST_METHOD'] = 'POST';

        $this->assertTrue($request->isPost());
    }

    public function testIsPut()
    {
        $request = new Request();

        $_SERVER['REQUEST_METHOD'] = 'PUT';

        $this->assertTrue($request->isPut());
    }

    public function testIsDelete()
    {
        $request = new Request();

        $_SERVER['REQUEST_METHOD'] = 'DELETE';

        $this->assertTrue($request->isDelete());
    }

    public function testIsOptions()
    {
        $request = new Request();

        $_SERVER['REQUEST_METHOD'] = 'OPTIONS';

        $this->assertTrue($request->isOptions());
    }

    public function testIsHead()
    {
        $request = new Request();

        $_SERVER['REQUEST_METHOD'] = 'HEAD';

        $this->assertTrue($request->isHead());
    }

    public function testIsXhr()
    {
        $request = new Request();

        $_SERVER['X_REQUESTED_WITH'] = 'XMLHttpRequest';

        $this->assertTrue($request->isXhr());
    }

    public function testGetGetParam()
    {
        $request = new Request();
        $_GET['test'] = 'test';

        $this->assertEquals('test', $request->getGetParam('test'));
    }

    public function testSetParam()
    {
        $request = new Request();
        $request->setParam('param1', 'param1');

        $this->assertEquals('param1', $request->getParam('param1'));
    }

    public function testSetParams()
    {
        $request = new Request();
        $request->setParams(['param1' => 'param1', 'param2' => 2]);

        $this->assertEquals('param1', $request->getParam('param1'));
        $this->assertEquals(2, $request->getParam('param2'));
    }

    public function testGetPostParam()
    {
        $request = new Request();
        $_POST['test'] = 'test';

        $this->assertEquals('test', $request->getPostParam('test'));
    }

    public function testGetCookieParam()
    {
        $request = new Request();
        $_COOKIE['test'] = 'test';

        $this->assertEquals('test', $request->getCookieParam('test'));
    }

    public function testGetParam()
    {
        $request = new Request();

        $_REQUEST['test'] = 'test';
        $_REQUEST['test2'] = 'test2';


        $this->assertEquals('test', $request->getParam('test'));
        $this->assertEquals('test2', $request->getParam('test2'));
    }

    public function testGetAllParams()
    {
        $request = new Request();

        $this->assertEquals([], $request->getAllParams());

        $request->setParam('param1', 'param1');
        $request->setParam('param2', 'param2');
        $allParams = $request->getAllParams();
        $this->assertEquals(2, count($allParams));

        $_REQUEST['param3'] = 'param3';
        $_REQUEST['param4'] = 'param4';

        $allParams = $request->getAllParams(true);
        $this->assertEquals(4, count($allParams));

        $this->assertContains('param1', $allParams);
        $this->assertContains('param2', $allParams);
        $this->assertContains('param3', $allParams);
        $this->assertContains('param4', $allParams);
    }

    public function testGetSomeParams()
    {
        $request = new Request();

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