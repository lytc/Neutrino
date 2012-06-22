<?php
namespace neutrino\http;

use neutrino\Neutrino;

class Request
{
    /**
     * @var Request
     */
    protected static $_instance;
    /**
     * @var Neutrino
     */
    protected $_app;

    /**
     * @var string
     */
    protected $_uri = null;

    /**
     * @var array
     */
    protected $_headers = null;

    /**
     * @var array
     */
    protected $_cachedParams = null;

    /**
     * @var array
     */
    protected $_customParams = array();

    /**
     * @var Closure
     */
    protected $_callbackMethodOverride;

    /**
     * @param Neutrino $app
     */
    public function __construct($uri = null)
    {
        $this->_uri = $uri;

        $this->_callbackMethodOverride = \Closure::bind(function() {
            if ($method = $this->getParam('__METHOD__')) {
                return $method;
            }

            if ($method = $this->getHeader('X-HTTP-Method-Override')) {
                return $method;
            }

            return $this->getServer('REQUEST_METHOD');

        }, $this);
    }

    /**
     * @static
     * @return Request
     */
    public static function getInstance()
    {
        if (!self::$_instance) {
            self::$_instance = new self();
        }
        return self::$_instance;
    }

    /**
     * @param Closure $callback
     * @return Request
     */
    public function setCallbackMethodOverride($callback)
    {
        $this->_callbackMethodOverride = \Closure::bind($callback, $this);
        return $this;
    }

    /**
     * @return string
     */
    public function getUri()
    {
        if (null === $this->_uri) {
            $this->_uri = $this->getServer('REQUEST_URI');
        }

        return $this->_uri;
    }

    /**
     * @param string $name
     * @return string
     */
    public function getServer($name)
    {
        if (isset($_SERVER[$name])) {
            return $_SERVER[$name];
        }
    }

    /**
     * @return array
     */
    function getHeaders()
    {
        if (null === $this->_headers) {
            $this->_headers = [];
            foreach ($_SERVER as $key => $value) {
                if ('HTTP' == substr($key, 0, 4)) {
                    $this->_headers[substr($key, 5)] = $value;
                }
            }

            # try get from apache
            if (function_exists('apache_request_headers')) {
                $headers = apache_request_headers();
                foreach ($headers as $key => $value) {
                    $this->_headers[strtoupper($key)] = $value;
                }
            }
        }

        return $this->_headers;
    }

    /**
     * @param string $name
     * @param mixed $default
     * @return string
     */
    public function getHeader($name, $default = null)
    {
        $name = strtoupper($name);
        $headers = $this->getHeaders();
        return isset($headers[$name])? $headers[$name] : $default;
    }

    /**
     * @return string
     */
    public function getMethod()
    {
        return call_user_func($this->_callbackMethodOverride);
    }

    /**
     * @return bool
     */
    public function isGet()
    {
        return 'GET' == $this->getMethod();
    }

    /**
     * @return bool
     */
    public function isPost()
    {
        return 'POST' == $this->getMethod();
    }

    /**
     * @return bool
     */
    public function isPut()
    {
        return 'PUT' == $this->getMethod();
    }

    /**
     * @return bool
     */
    public function isDelete()
    {
        return 'DELETE' == $this->getMethod();
    }

    /**
     * @return bool
     */
    public function isOptions()
    {
        return 'OPTIONS' == $this->getMethod();
    }

    /**
     * @return bool
     */
    public function isHead()
    {
        return 'HEAD' == $this->getMethod();
    }

    /**
     * @return bool
     */
    public function isXhr()
    {
        return $this->getServer('X_REQUESTED_WITH') == 'XMLHttpRequest';
    }

    /**
     * @param string $name
     * @param mixed $default
     * @return mixed
     */
    public function getGetParam($name, $default = null)
    {
        return isset($_GET[$name])? $_GET[$name] : $default;
    }

    /**
     * @param string $name
     * @param mixed $default
     * @return mixed
     */
    public function getPostParam($name, $default = null)
    {
        return isset($_POST[$name])? $_POST[$name] : $default;
    }

    /**
     * @param string $name
     * @param mixed $default
     * @return mixed
     */
    public function getCookieParam($name, $default = null)
    {
        return isset($_COOKIE[$name])? $_COOKIE[$name] : $default;
    }

    /**
     * @param string $name
     * @param mixed $value
     * @return Request
     */
    public function setParam($name, $value)
    {
        $this->_customParams[$name] = $value;
        return $this;
    }

    /**
     * @param array $params
     * @return Request
     */
    public function setParams(array $params)
    {
        $this->_customParams = array_merge($this->_customParams, $params);
        return $this;
    }

    /**
     * @param string $name
     * @param mixed $default
     * @return mixed
     */
    public function getParam($name, $default = null)
    {
        $allParams = $this->getAllParams();

        if (isset($allParams[$name])) {
            return $allParams[$name];
        }

        return $default;
    }

    /**
     * @return array
     */
    public function getAllParams($forceNew = false)
    {
        if ($forceNew || null === $this->_cachedParams) {
            $this->_cachedParams = array_merge($_GET, $_POST, $_COOKIE, $_REQUEST);
        }

        $this->_cachedParams = array_merge($this->_cachedParams, $this->_customParams);

        return $this->_cachedParams;
    }

    public function getSomeParams($names)
    {
        $result = [];

        if (func_num_args() > 1) {
            $names = func_get_args();
        }

        foreach ($names as $name) {
            $result[$name] = $this->getParam($name);
        }

        return $result;
    }
}