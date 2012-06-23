<?php

namespace neutrino\route\match;

use neutrino\route\match\AbstractMatch;

class Xhr extends AbstractMatch
{
    /**
     * @param mixed $condition
     * @return AbstractMatch
     */
    protected function _setCondition($condition)
    {
        $condition = !!$condition;
        return parent::_setCondition($condition);
    }

    /**
     * @return bool
     */
    protected function _getValue()
    {
        return $this->_app->getRequest()->isXhr();
    }
}