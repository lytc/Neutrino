<?php

namespace neutrino\route\match;

use neutrino\route\match\AbstractMatch;

class Method extends AbstractMatch
{
    /**
     * @param mixed $condition
     * @return AbstractMatch
     */
    protected function _setCondition($condition)
    {
        $condition = strtoupper($condition);
        return parent::_setCondition($condition);
    }

    /**
     * @return string
     */
    protected function _getValue()
    {
        return $this->_app->getRequest()->getMethod();
    }
}