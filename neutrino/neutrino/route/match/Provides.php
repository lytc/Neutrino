<?php

namespace neutrino\route\match;

use neutrino\route\match\AbstractMatch;

class Provides extends AbstractMatch
{
    /**
     * @return string
     */
    protected function _getValue()
    {
        $uri = $this->_app->getRequest()->getUri();
        return pathinfo($uri, PATHINFO_EXTENSION);
    }

    /**
     * @return bool
     */
    public function match()
    {
        $value = $this->_getValue();

        if (is_array($this->_condition)) {
            return in_array($value, $this->_condition);
        }

        return strtolower($value) === strtolower($this->_condition);
    }
}