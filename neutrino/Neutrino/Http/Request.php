<?php

class Neutrino_Http_Request
{
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
    public function __construct(Neutrino $app)
    {
        $this->_app = $app;
    }

    /**
     * @return string
     */
    public function getUri()
    {
        if (null === $this->_uri) {
            $pattern = '/^' . preg_quote($this->_app->baseUri, '/') . '/';
            $this->_uri = preg_replace($pattern, '', $this->getServer('REQUEST_URI'));
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
                if ('HTTP' == ($name = substr($key, 0, 4))) {
                    $this->_headers[$name] = $value;
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
        return strtoupper($this->getServer('HTTP_REQUEST_METHOD'));
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
     * @return mixed
     */
    public function isOption()
    {
        return 'OPTION' == $this->getMethod();
    }

    /**
     * @return bool
     */
    public function isXhr()
    {
        return $this->getServer('X_REQUESTED_WITH') == XMLHttpRequest;
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
}