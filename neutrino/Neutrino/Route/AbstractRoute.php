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
    protected $_options = [
        'method'    => Neutrino::METHOD_GET
    ];

    /**
     * @abstract
     * @param String $uri
     * @return array
     */
    abstract protected function _matchUri($uri);

    /**
     * @param string $pattern
     * @param array|Closure $options
     * @param Closure $callback
     * @param string $methods
     */
    public function __construct($pattern, $options, $callback = null)
    {
        $this->_pattern = $pattern;
        if (null === $callback) {
            $this->_callback = $options;
        } else {
            $this->_options = $options;
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
    public static function createInstance($pattern, $options, $callback = null)
    {
        $className = get_called_class();
        return new $className($pattern, $options, $callback);
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
        return $this->_options['method'];
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
     * @param array|Closure $options
     * @param Closure $callback
     * @return AbstractRoute
     */
    public static function get($pattern, $options, $callback = null) {
        if (null === $callback) {
            $callback = $options;
            $options = [];
        }
        $options['method'] = Neutrino::METHOD_GET;

        return self::createInstance($pattern, $options, $callback);
    }

    /**
     * @static
     * @param string $pattern
     * @param array|Closure $options
     * @param Closure $callback
     * @return AbstractRoute
     */
    public static function post($pattern, $options, $callback = null)
    {
        if (null === $callback) {
            $callback = $options;
            $options = [];
        }
        $options['method'] = Neutrino::METHOD_POST;

        return self::createInstance($pattern, $options, $callback);
    }

    /**
     * @static
     * @param string $pattern
     * @param array|Closure $options
     * @param Closure $callback
     * @return AbstractRoute
     */
    public static function put($pattern, $options, $callback = null)
    {
        if (null === $callback) {
            $callback = $options;
            $options = [];
        }
        $options['method'] = Neutrino::METHOD_PUT;

        return self::createInstance($pattern, $options, $callback);
    }

    /**
     * @static
     * @param string $pattern
     * @param array|Closure $options
     * @param Closure $callback
     * @return AbstractRoute
     */
    public static function delete($pattern, $options, $callback = null)
    {
        if (null === $callback) {
            $callback = $options;
            $options = [];
        }
        $options['method'] = Neutrino::METHOD_DELETE;

        return self::createInstance($pattern, $options, $callback);
    }

    /**
     * @param App $app
     * @return array
     */
    public function match(App $app)
    {
        $request = $app->getRequest();
        $uri = substr($request->getUri(), strlen($app->getBaseUri()));

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
        if (!count($this->_options)) {
            return true;
        }
        foreach ($this->_options as $matchMethod => $option) {
            $matchMethod = '_match' . ucfirst($matchMethod);

            if (!$this->{$matchMethod}($app, $option)) {
                return false;
            }
        }

        return true;
    }

    protected function _matchMethod(App $app, $method)
    {
        $request = $app->getRequest();

        if ($request->getMethod() == strtoupper($method)) {
            return true;
        }
    }
}