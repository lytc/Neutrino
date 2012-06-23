<?php

namespace neutrino;

use neutrino\view\Exception as ViewException,
    \stdClass,
    \Closure;

class View
{
    /**
     * @var int
     */
    protected $_errorReporting = E_ALL;

    /**
     * @var mixed
     */
    protected $_scope = null;

    /**
     *
     */
    public function __construct()
    {

    }

    /**
     * @param int $level
     * @return View
     */
    public function setErrorReporting($level)
    {
        $this->_errorReporting = $level;
        return $this;
    }

    /**
     * @param object $scope
     * @return View
     */
    public function setScope($scope)
    {
        $this->_scope = $scope;
        return $this;
    }

    /**
     * @return object
     */
    public function getScope()
    {
        if (!$this->_scope) {
            $this->_scope = $this;
        }
        return $this->_scope;
    }

    /**
     * @param string $script
     * @return string
     * @throws ViewException
     */
    public function render($script)
    {
        $resolvedName = stream_resolve_include_path($script);

        if (false === $resolvedName) {
            throw new ViewException("View script '$script' not found");
        }

        $errorLevel = $this->_errorReporting;

        $callback = function() use ($script, $errorLevel) {
            $currentErrorLevel = error_reporting();
            error_reporting($errorLevel);

            ob_start();
            include $script;

            $content = ob_get_clean();

            error_reporting($currentErrorLevel);
            return $content;
        };

        $callback = Closure::bind($callback, $this->getScope());
        return $callback();

    }

    /**
     * @param $script
     * @return string
     */
    public function display($script)
    {
        $content = $this->render($script);
        echo $content;
        return $content;
    }
}