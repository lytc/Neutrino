<?php

namespace neutrino\route\match;

use neutrino\App,
    neutrino\Regex;

abstract class AbstractMatch
{
    /**
     * @var App
     */
    protected $_app;

    /**
     * @var string|Regex
     */
    protected $_condition;

    public function __construct(App $app, $condition)
    {
        $this->_app = $app;
        $this->_setCondition($condition);
    }

    /**
     * @param mixed $condition
     * @return AbstractMatch
     */
    protected function _setCondition($condition)
    {
        $this->_condition = $condition;
        return $this;
    }

    /**
     * @abstract
     * @return mixed
     */
    abstract protected function _getValue();

    /**
     * @return boolean
     */
    public function match()
    {
        $value = $this->_getValue();
        if ($this->_condition instanceof Regex) {
            return !!$this->_condition->match($value);
        }

        return $this->_condition === $value;
    }
}