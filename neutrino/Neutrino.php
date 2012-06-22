<?php
namespace neutrino;

use \Closure,
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
     * @param string $baseUri
     * @param Closure $closure
     * @return App
     */
    public static function map($baseUri, $closure)
    {
        $request = Request::getInstance();
        $uri = $request->getUri();

        if (substr($uri, 0, strlen($baseUri)) == $baseUri) {
            $class = new DynamicMethod();

            $class->run = function($appName) use ($baseUri) {
                $app = new $appName($baseUri);
                try {
                    $app->run();
                } catch (NotFound $exception) {

                }
                return $app;
            };

            $app = call_user_func(\Closure::bind($closure, $class));
            return $app;
        }
    }
}