<?php

require_once dirname(__FILE__) . '/Neutrino/Exception.php';

class Neutrino
{
    const METHOD_GET = 'GET';
    const METHOD_POST = 'POST';
    const METHOD_PUT = 'PUT';
    const METHOD_DELETE = 'DELETE';

    /**
     * @var string
     */
    public $baseUri = '';

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
    public function __construct()
    {

    }

    /**
     * @static
     * @throws Neutrino_Exception
     */
    public static function registerAutoLoad()
    {
        spl_autoload_register(function($className) {
            if (class_exists($className, false) || interface_exists($className, false)) {
                return;
            }

            $filePath = dirname(__FILE__) . '/' . str_replace('_', '/', $className) . '.php';

            if (!is_file($filePath)) {
                throw new Neutrino_Exception("File not found for class '$className'");
            }

            require_once $filePath;

            if (!class_exists($className) && !interface_exists($className)) {
                throw new Neutrino_Exception("Class '$className'' not found");
            }
        });
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
            $this->_request = new Neutrino_Http_Request($this);
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