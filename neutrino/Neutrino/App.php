<?php

class Neutrino_App
{
    /**
     * @var string
     */
    protected $_baseUri = '';

    /**
     * @var Neutrino_Router
     */
    protected $_router;

    /**
     * @var Neutrino_Http_Request
     */
    protected $_request;

    /**
     * @var Neutrino_Http_Response
     */
    protected $_response;

    /**
     * @var callable
     */
    protected $_noRouteMatchCallable;

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
     * @return Neutrino_Router
     */
    public function getRouter()
    {
        if (!$this->_router) {
            $this->_router = new Neutrino_Router($this);
        }
        return $this->_router;
    }

    /**
     * @return Neutrino_Http_Request
     */
    public function getRequest()
    {
        if (!$this->_request) {
            $this->_request = new Neutrino_Http_Request();
        }
        return $this->_request;
    }

    public function getResponse()
    {
        if (!$this->_response) {
            $this->_response = new Neutrino_Http_Response($this);
        }
        return $this->_response;
    }

    /**
     * @param string $pattern
     * @param callable $callable
     * @return Neutrino_Router
     */
    public function map()
    {
        return call_user_func_array([$this->getRouter(), 'map'], func_get_args());
    }

    /**
     * @param string $pattern
     * @param callable $callable
     * @return Neutrino_Router
     */
    public function get()
    {
        return call_user_func_array([$this->getRouter(), 'get'], func_get_args());
    }

    /**
     * @param string $pattern
     * @param callable $callable
     * @return Neutrino_Router
     */
    public function post()
    {
        return call_user_func_array([$this->getRouter(), 'post'], func_get_args());
    }

    /**
     * @param string $pattern
     * @param callable $callable
     * @return Neutrino_Router
     */
    public function put()
    {
        return call_user_func_array([$this->getRouter(), 'put'], func_get_args());
    }

    /**
     * @param string $pattern
     * @param callable $callable
     * @return Neutrino_Router
     */
    public function delete()
    {
        return call_user_func_array([$this->getRouter(), 'delete'], func_get_args());
    }

    /**
     * @return callable
     */
    public function getNoRouteMatchCallable()
    {
        if (!$this->_noRouteMatchCallable) {
            $this->_noRouteMatchCallable = function() {
                $this->getResponse()->setCode(404)->setMessage('Page not found');
            };
        }
        return $this->_noRouteMatchCallable;
    }

    /**
     * @return Neutrino
     */
    public function run()
    {
        $hasMatch = $this->getRouter()->dispatch();

        if (!$hasMatch) {
            $callable = Closure::bind($this->getNoRouteMatchCallable(), $this);
            $callable();
        }

        $this->getResponse()->send();
        return $this;
    }
}