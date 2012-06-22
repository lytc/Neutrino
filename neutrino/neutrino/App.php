<?php
namespace neutrino;

use neutrino\Router,
    neutrino\http\Request,
    neutrino\http\Response,
    neutrino\exception\Pass,
    neutrino\exception\NotFound,
    neutrino\exception\Halt,
    Closure;

class App
{
    /**
     * @var string
     */
    protected $_baseUri = '';

    /**
     * @var Router
     */
    protected $_router;

    /**
     * @var Request
     */
    protected $_request;

    /**
     * @var Response
     */
    protected $_response;

    /**
     * @var Closure
     */
    protected $_noRouteMatchCallback;

    /**
     *
     */
    public function __construct($baseUri = '')
    {
        $this->_baseUri = $baseUri;
        $this->init();
    }

    public function init()
    {

    }

    /**
     * @param string $baseUri
     * @return Neutrino
     */
    public function setBaseUri($baseUri)
    {
        $this->_baseUri = $baseUri;
        return $this;
    }

    /**
     * @return string
     */
    public function getBaseUri()
    {
        return $this->_baseUri;
    }

    /**
     * @return Router
     */
    public function getRouter()
    {
        if (!$this->_router) {
            $this->_router = new Router($this);
        }
        return $this->_router;
    }

    /**
     * @return Request
     */
    public function getRequest()
    {
        if (!$this->_request) {
            $this->_request = new Request();
        }
        return $this->_request;
    }

    /**
     * @return Response
     */
    public function getResponse()
    {
        if (!$this->_response) {
            $this->_response = new Response($this);
        }
        return $this->_response;
    }

    /**
     * @param string $pattern
     * @param Closure $callback
     * @return Router
     */
    public function map()
    {
        return call_user_func_array([$this->getRouter(), 'map'], func_get_args());
    }

    /**
     * @param string $pattern
     * @param Closure $callback
     * @return Router
     */
    public function get()
    {
        return call_user_func_array([$this->getRouter(), 'get'], func_get_args());
    }

    /**
     * @param string $pattern
     * @param Closure $callback
     * @return Router
     */
    public function post()
    {
        return call_user_func_array([$this->getRouter(), 'post'], func_get_args());
    }

    /**
     * @param string $pattern
     * @param Closure $callback
     * @return Router
     */
    public function put()
    {
        return call_user_func_array([$this->getRouter(), 'put'], func_get_args());
    }

    /**
     * @param string $pattern
     * @param Closure $callback
     * @return Router
     */
    public function delete()
    {
        return call_user_func_array([$this->getRouter(), 'delete'], func_get_args());
    }

    /**
     * @return Closure
     */
    public function getNoRouteMatchCallback()
    {
        if (!$this->_noRouteMatchCallback) {
            $this->_noRouteMatchCallback = Closure::bind(function() {
                throw new NotFound('Page not found', 404);
            }, $this);
        }
        return $this->_noRouteMatchCallback;
    }

    /**
     * @throws exception\Pass
     */
    public function pass()
    {
        throw new Pass('Punt processing to the next matching route!');
    }

    public function halt($code = 500, $headers = [], $message = '')
    {
        $map = [
            // 1
            'array array string'   => function() use (&$code, &$headers) {
                                    $headers = $code; $code = 500;
            },
            'string array string'  => function() use (&$code, &$message) {
                                    $message = $code; $code = 500;
                                },
            // 2
            'integer string string' => function() use (&$headers, &$message) {
                                    $message = $headers; $headers = [];
                                },
            'array string string' => function() use (&$code, &$headers, &$message) {
                                    $message = $headers; $headers = $code; $code = 500;
                                },
        ];

        $args = func_get_args();
        $type = [gettype($code), gettype($headers), gettype($message)];
        $type = implode(' ', $type);

        if (isset($map[$type])) {
            $map[$type]();
        }

        $this->getResponse()->setCode($code)
            ->setHeaders($headers)
            ->setBody($message);

        throw new Halt();
    }

    /**
     * @return Response
     */
    public function run()
    {
        $request = $this->getRequest();
        $hasMatch = false;

        try {
            foreach ($this->getRouter() as $route) {
                try {
                    if (is_array($params = $route->match($this))) {
                        call_user_func_array(Closure::bind($route->getCallback(), $this), $params);
                        $hasMatch = true;
                        break;
                    }
                } catch (Pass $exception) {
                    continue;
                }
            }

            if (!$hasMatch) {
                $callback = $this->getNoRouteMatchCallback();
                $callback();
            }
        } catch (Halt $exception) {

        }

        return $this->getResponse()->send();
    }
}