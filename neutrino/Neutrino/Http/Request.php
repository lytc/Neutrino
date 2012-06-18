<?php

class Neutrino_Http_Request
{
    /**
     * @var Neutrino_Http_Request
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
     * @param Neutrino $app
     */
    public function __construct($uri = null)
    {
        $this->_uri = $uri;
    }

    /**
     * @static
     * @return Neutrino_Http_Request
     */
    public static function getInstance()
    {
        if (!self::$_instance) {
            self::$_instance = new self();
        }
        return self::$_instance;
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
     * @param $name
     * @return mixed
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
        return strtoupper($this->getServer('REQUEST_METHOD'));
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
     * @param mixed $default
     * @return mixed
     */
    public function getParam($name, $default = null)
    {
        return isset($_REQUEST[$name])? $_REQUEST[$name] : $default;
    }

    /**
     * @return array
     */
    public function getAllParams()
    {
        return array_merge($_GET, $_POST, $_COOKIE, $_REQUEST);
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