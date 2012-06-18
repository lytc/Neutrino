<?php

class Neutrino_Router
{
    const ROUTER_NAMED_CLASS = 'Neutrino_Route_Named';
    const ROUTER_REGEX_CLASS = 'Neutrino_Route_Regex';

    /**
     * @var bool
     */
    protected  $_allowDuplicate = false;

    protected $_defaultRouteClass = 'Neutrino_Route_Named';

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
    public function __construct(Neutrino_App $app, $allowDuplicate = false)
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
     * @return Neutrino_Router
     * @throws Neutrino_Router_Exception
     */
    public function setDefaultRouteClass($className)
    {
        if (!is_subclass_of($className, 'Neutrino_Route_Abstract')) {
            throw new Neutrino_Router_Exception('Default route class must be an instance of Neutrino_Route_Abstract');
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
     * @param Neutrino_Route_Abstract $route
     * @return Neutrino_Router
     * @throws Neutrino_Router_Exception
     */
    public function add(Neutrino_Route_Abstract $route)
    {
        if (!$this->_allowDuplicate) {
            $pattern = $route->getPattern();
            $method = $route->getMethod();

            foreach ($this->_routes as $item) {
                if ($pattern == $item->getPattern() && $method = $item->getMethod()) {
                    throw new Neutrino_Router_Exception("Duplicate route with pattern '{$pattern}'");
                }
            }
        }

        $this->_routes[] = $route;
        return $this;
    }

    /**
     * @param string $pattern
     * @param callable $callable
     * @param string $methods
     * @return Neutrino_Router
     */
    public function map($pattern, $callable, $methods = Neutrino::METHOD_GET)
    {
        if ('#' == $pattern[0]) {
            $route = new Neutrino_Route_Regex(substr($pattern, 1), $callable, $methods);
        } else {
            $route = new Neutrino_Route_Named($pattern, $callable, $methods);
        }

        return $this->add($route);
        return $this;
    }

    /**
     * @param $pattern
     * @param $callable
     * @return Neutrino_Router
     */
    public function get($pattern, $callable)
    {
        return $this->map($pattern, $callable);
    }

    /**
     * @param $pattern
     * @param $callable
     * @return Neutrino_Router
     */
    public function post($pattern, $callable)
    {
        return $this->map($pattern, $callable, Neutrino::METHOD_POST);
    }

    /**
     * @param $pattern
     * @param $callable
     * @return Neutrino_Router
     */
    public function put($pattern, $callable)
    {
        return $this->map($pattern, $callable, Neutrino::METHOD_PUT);
    }

    /**
     * @param $pattern
     * @param $callable
     * @return Neutrino_Router
     */
    public function delete($pattern, $callable)
    {
        return $this->map($pattern, $callable, Neutrino::METHOD_DELETE);
    }

    /**
     * @return bool
     */
    public function dispatch()
    {
        $request = $this->_app->getRequest();
        $uri = substr($request->getUri(), strlen($this->_app->getBaseUri()));
        $hasMatch = false;

        foreach ($this->_routes as $route) {
            if ($request->getMethod() == $route->getMethod() && is_array($params = $route->match($uri))) {
                call_user_func_array(Closure::bind($route->getCallable(), $this->_app), $params);
                $hasMatch = true;
                break;
            }
        }

        return $hasMatch;
    }
}