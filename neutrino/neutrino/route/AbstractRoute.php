<?php

namespace neutrino\route;
use neutrino\Neutrino,
    neutrino\App,
    neutrino\route\Exception,
    neutrino\http\Request,
    \Closure;

abstract class AbstractRoute
{
    /**
     * @var string
     */
    protected $_pattern;

    /**
     * @var Closure
     */
    protected $_callback;

    /**
     * @var array
     */
    protected $_conditions = [];

    /**
     * @abstract
     * @param String $uri
     * @return array
     */
    abstract protected function _matchUri($uri);

    /**
     * @param string $pattern
     * @param array|Closure $conditions
     * @param Closure $callback
     * @param string $methods
     */
    public function __construct($pattern, $conditions, $callback = null)
    {
        $this->_pattern = $pattern;
        if (null === $callback) {
            $this->_callback = $conditions;
        } else {
            $this->_conditions = $conditions;
            $this->_callback = $callback;
        }
    }

    /**
     * @static
     * @param string $pattern
     * @param Closure $callback
     * @param string $method
     * @return AbstractRoute
     */
    public static function createInstance($pattern, $conditions, $callback = null)
    {
        $className = get_called_class();
        return new $className($pattern, $conditions, $callback);
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
        return isset($this->_conditions['method'])?
                $this->_conditions['method'] : Neutrino::METHOD_GET;
    }

    /**
     * @return Closuere
     */
    public function getCallback()
    {
        return $this->_callback;
    }

    /**
     * @static
     * @param $pattern
     * @param array|Closure $conditions
     * @param Closure $callback
     * @return AbstractRoute
     */
    public static function get($pattern, $conditions, $callback = null) {
        if (null === $callback) {
            $callback = $conditions;
            $conditions = [];
        }
        $conditions['method'] = Neutrino::METHOD_GET;

        return self::createInstance($pattern, $conditions, $callback);
    }

    /**
     * @static
     * @param string $pattern
     * @param array|Closure $conditions
     * @param Closure $callback
     * @return AbstractRoute
     */
    public static function post($pattern, $conditions, $callback = null)
    {
        if (null === $callback) {
            $callback = $conditions;
            $conditions = [];
        }
        $conditions['method'] = Neutrino::METHOD_POST;

        return self::createInstance($pattern, $conditions, $callback);
    }

    /**
     * @static
     * @param string $pattern
     * @param array|Closure $conditions
     * @param Closure $callback
     * @return AbstractRoute
     */
    public static function put($pattern, $conditions, $callback = null)
    {
        if (null === $callback) {
            $callback = $conditions;
            $conditions = [];
        }
        $conditions['method'] = Neutrino::METHOD_PUT;

        return self::createInstance($pattern, $conditions, $callback);
    }

    /**
     * @static
     * @param string $pattern
     * @param array|Closure $conditions
     * @param Closure $callback
     * @return AbstractRoute
     */
    public static function delete($pattern, $conditions, $callback = null)
    {
        if (null === $callback) {
            $callback = $conditions;
            $conditions = [];
        }
        $conditions['method'] = Neutrino::METHOD_DELETE;

        return self::createInstance($pattern, $conditions, $callback);
    }

    /**
     * @param App $app
     * @return array
     */
    public function match(App $app)
    {
        $request = $app->getRequest();
        $uri = substr($request->getUri(), strlen($app->getBaseUri()));

        if (!$uri) {
            $uri = '/';
        }

        $params = $this->_matchUri($uri);
        if (is_array($params)) {
            if ($this->_matchOptions($app)) {
                return $params;
            }
        }

        return false;
    }

    /**
     * @param App $app
     * @return boolean
     */
    protected function _matchOptions(App $app)
    {
        foreach ($this->_conditions as $type => $condition) {
            $matchClassName = 'neutrino\\route\\match\\' . ucfirst($type);

            if (!(new $matchClassName($app, $condition))->match()) {
                return false;
            }
        }

        return true;
    }
}