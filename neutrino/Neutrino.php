<?php
namespace neutrino;

use \Closure,
    neutrino\App,
    neutrino\http\Request,
    neutrino\DynamicMethod,
    neutrino\exception\NotFound;


require_once dirname(__FILE__) . '/Neutrino/Exception.php';

class Neutrino
{
    const METHOD_GET        = 'GET';
    const METHOD_POST       = 'POST';
    const METHOD_PUT        = 'PUT';
    const METHOD_DELETE     = 'DELETE';
    const METHOD_OPTIONS    = 'OPTIONS';
    const METHOD_HEAD       = 'HEAD';

    /**
     * @var Neutrino
     */
    protected static $_instance;

    /**
     * @var array
     */
    protected $_map = [];

    /**
     * @static
     * @return Neutrino
     */
    public static function getInstance()
    {
        if (!self::$_instance) {
            self::$_instance = new self();
        }

        return self::$_instance;
    }

    /**
     * @static
     * @throws \neutrino\Exception
     */
    public static function registerAutoLoad()
    {
        spl_autoload_register(function($className) {
            if (class_exists($className, false) || interface_exists($className, false)) {
                return true;
            }

            $filePath = dirname(__FILE__) . '/' . str_replace('\\', '/', $className) . '.php';
            $resolvedName = stream_resolve_include_path($filePath);

            if (false !== $resolvedName) {
                return include $resolvedName;
            }

            return false;
        });
    }

    /**
     * @param string $pattern
     * @param Closure $closure
     * @return Neutrino
     */
    public function map($pattern, Closure $closure)
    {
        $this->_map[$pattern] = $closure;
        return $this;
    }

    /**
     * @return App
     * @throws NotFound
     */
    public function run()
    {
        $request = Request::getInstance();
        $uri = $request->getUri();
        foreach ($this->_map as $pattern => $closure) {
            if (substr($uri, 0, strlen($pattern)) == $pattern) {
                $class = new DynamicMethod();
                $class->run = function($appName) use ($pattern) {
                    $app = new $appName($pattern);

                    try {
                        $app->run();
                    } catch (NotFound $exception) {

                    }
                };
                return call_user_func(Closure::bind($closure, $class));
            }
        }

        throw new NotFound('Page not found', 404);
    }
}