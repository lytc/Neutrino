<?php

namespace neutrino\route\match;

use neutrino\route\match\AbstractMatch;

class Agent extends AbstractMatch
{
    /**
     * @return string
     */
    protected function _getValue()
    {
        return $this->_app->getRequest()->getHeader('USER_AGENT');
    }

    /**
     * @return bool
     */
    public function match()
    {
        return $this->_app->getRequest()->isAgent($this->_condition);
    }
}