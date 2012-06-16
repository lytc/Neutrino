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
     * @var array|string
     */
    protected $_methods;

    /**
     * @abstract
     * @param string $url
     * @return boolean
     */
    abstract public function match($url);

    /**
     * @param string $pattern
     * @param callable $callable
     * @param string $methods
     */
    public function __construct($pattern, $callable, $methods = Neutrino::METHOD_GET)
    {
        $this->_pattern = $pattern;
        $this->_callable = $callable;

        if (is_string($methods)) {
            $methods = [$methods];
        }

        $this->_methods = $methods;
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
     * @param string|array $methods
     * @return Neutrino_Route_Abstract
     */
    public function setMethods($methods)
    {
        if (!is_array($methods)) {
            $methods = [$methods];
        }
        $this->_methods = $methods;
        return $this;
    }

    /**
     * @return array|string
     */
    public function getMethods()
    {
        return $this->_methods;
    }

    /**
     * @return callable
     */
    public function getCallable()
    {
        return $this->_callable;
    }

    /**
     * @param string|Neutrino_Route_Abstract $pattern
     * @param string|array $methods optional
     * @return bool
     */
    public function isIntersectWith($pattern, $methods = Neutrino::METHOD_GET)
    {
        if ($pattern instanceof Neutrino_Route_Abstract) {
            $methods = $pattern->getMethods();
            $pattern = $pattern->getPattern();
        }

        if (is_string($methods)) {
            $methods = [$methods];
        }

        if ($pattern === $this->_pattern && array_intersect($this->_methods, $methods)) {
            return true;
        }

        return false;
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