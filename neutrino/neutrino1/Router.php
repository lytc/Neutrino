<?php
namespace neutrino;

use \Closure,
    neutrino\Neutrino,
    neutrino\App,
    neutrino\route\Named,
    neutrino\route\Regex,
    neutrino\router\Exception as RouterException;

class Router implements \Iterator
{
    const ROUTER_NAMED_CLASS = 'neutrino\route\Named';
    const ROUTER_REGEX_CLASS = 'neutrino\route\Regex';

    /**
     * @var int
     */
    protected $_index = 0;

    /**
     * @var bool
     */
    protected  $_allowDuplicate = false;

    protected $_defaultRouteClass = 'neutrino\route\Named';

    /**
     * @var Neutrino
     */
    protected $_app;

    /**
     * @var array
     */
    protected $_routes = [];

    /**
     * @param Neutrino $app
     */
    public function __construct(App $app, $allowDuplicate = false)
    {
        $this->_app = $app;
        $this->_allowDuplicate = $allowDuplicate;
    }

    /**
     * @return Neutrino
     */
    public function getApp()
    {
        return $this->_app;
    }

    /**
     * @param $className
     * @return Router
     * @throws neutrino\router\Exception
     */
    public function setDefaultRouteClass($className)
    {
        if (!is_subclass_of($className, 'neutrino\route\AbstractRoute')) {
            throw new RouterException('Default route class must be an instance of neutrino\route\AbstractRoute');
        }

        $this->_defaultRouteClass = $className;
        return $this;
    }

    /**
     * @return string
     */
    public function getDefaultRouteClass()
    {
        return $this->_defaultRouteClass;
    }

    /**
     * @return array
     */
    public function getRoutes()
    {
        return $this->_routes;
    }

    /**
     * @param neutrino\route\AbstractRoute $route
     * @return Router
     * @throws neutrino\router\Exception
     */
    public function add(\neutrino\route\AbstractRoute $route)
    {
        if (!$this->_allowDuplicate) {
            $pattern = $route->getPattern();
            $method = $route->getMethod();

            foreach ($this->_routes as $item) {
                if ($pattern == $item->getPattern() && $method = $item->getMethod()) {
                    throw new RouterException("Duplicate route with pattern '{$pattern}'");
                }
            }
        }

        $this->_routes[] = $route;
        return $this;
    }

    /**
     * @param string $pattern
     * @param array|Closure $options
     * @param Closure $closure
     * @return Router
     */
    public function map($pattern, $options, $callback = null)
    {
        if ('#' == $pattern[0]) {
            $route = new Regex(substr($pattern, 1), $options, $callback);
        } else {
            $route = new Named($pattern, $options, $callback);
        }

        return $this->add($route);
        return $this;
    }

    /**
     * @param string $pattern
     * @param array|Closure
     * @param $callback
     * @return Router
     */
    public function get($pattern, $options, $callback = null)
    {
        return $this->map($pattern, $options, $callback);
    }

    /**
     * @param string $pattern
     * @param array|Closure
     * @param $callback
     * @return Router
     */
    public function post($pattern, $options, $callback = null)
    {
        if (null === $callback) {
            $callback = $options;
            $options = [];
        }
        $options['method'] = Neutrino::METHOD_POST;

        return $this->map($pattern, $options, $callback);
    }

    /**
     * @param string $pattern
     * @param array|Closure $options
     * @param Closure $callback
     * @return Router
     */
    public function put($pattern, $options, $callback = null)
    {
        if (null === $callback) {
            $callback = $options;
            $options = [];
        }
        $options['method'] = Neutrino::METHOD_PUT;

        return $this->map($pattern, $options, Neutrino::METHOD_PUT);
    }

    /**
     * @param string $pattern
     * @param array|Closure $options
     * @param Closure $callback
     * @return Router
     */
    public function delete($pattern, $options, $callback = null)
    {
        if (null === $callback) {
            $callback = $options;
            $options = [];
        }
        $options['method'] = Neutrino::METHOD_DELETE;

        return $this->map($pattern, $options, $callback);
    }

    public function current()
    {
        return $this->_routes[$this->_index];
    }

    public function key()
    {
        return $this->_index;
    }

    public function next()
    {
        $this->_index++;
    }

    public function rewind()
    {
        $this->_index = 0;
    }

    public function valid()
    {
        return isset($this->_routes[$this->_index]);
    }
}