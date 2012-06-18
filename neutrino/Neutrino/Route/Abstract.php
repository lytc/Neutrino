<?php

abstract class Neutrino_Route_Abstract
{
    /**
     * @var string
     */
    protected $_pattern;

    /**
     * @var callable
     */
    protected $_callable;

    /**
     * @var string
     */
    protected $_method;

    /**
     * @abstract
     * @param string $uri
     * @return boolean
     */
    abstract public function match($uri);

    /**
     * @param string $pattern
     * @param callable $callable
     * @param string $methods
     */
    public function __construct($pattern, $callable, $method = Neutrino::METHOD_GET)
    {
        $this->_pattern = $pattern;
        $this->_callable = $callable;

        $method = strtoupper($method);
        $supportMethods = array(
            Neutrino::METHOD_GET,
            Neutrino::METHOD_POST,
            Neutrino::METHOD_PUT,
            Neutrino::METHOD_DELETE,
            Neutrino::METHOD_OPTIONS,
            Neutrino::METHOD_HEAD
        );
        if (!in_array($method, $supportMethods)) {
            throw new Neutrino_Route_Exception(
                "The request method must be GET, POST, PUT, DELETE, OPTIONS or HEAD. '$method' given.");
        }
        $this->_method = $method;
    }

    /**
     * @static
     * @param string $pattern
     * @param callable $callable
     * @param string $method
     * @return Neutrino_Route_Abstract
     */
    public static function createInstance($pattern, $callable, $method = Neutrino::METHOD_GET)
    {
        $className = get_called_class();
        return new $className($pattern, $callable, $method);
    }

    /**
     * @return string
     */
    public function getPattern()
    {
        return $this->_pattern;
    }

    /**
     * @return array|string
     */
    public function getMethod()
    {
        return $this->_method;
    }

    /**
     * @return callable
     */
    public function getCallable()
    {
        return $this->_callable;
    }

    /**
     * @static
     * @param $pattern
     * @param $callable
     * @return Neutrino_Route_Abstract
     */
    public static function get($pattern, $callable) {
        return self::createInstance($pattern, $callable);
    }

    /**
     * @static
     * @param string $pattern
     * @param callable $callable
     * @return Neutrino_Route_Abstract
     */
    public static function post($pattern, $callable)
    {
        return self::createInstance($pattern, $callable, Neutrino::METHOD_POST);
    }

    /**
     * @static
     * @param string $pattern
     * @param callable $callable
     * @return Neutrino_Route_Abstract
     */
    public static function put($pattern, $callable)
    {
        return self::createInstance($pattern, $callable, Neutrino::METHOD_PUT);
    }

    /**
     * @static
     * @param string $pattern
     * @param callable $callable
     * @return Neutrino_Route_Abstract
     */
    public static function delete($pattern, $callable)
    {
        return self::createInstance($pattern, $callable, Neutrino::METHOD_DELETE);
    }
}