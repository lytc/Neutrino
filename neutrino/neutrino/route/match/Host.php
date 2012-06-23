<?php

namespace neutrino\route\match;

use neutrino\route\match\AbstractMatch;

class Host extends AbstractMatch
{
    /**
     * @return string
     */
    protected function _getValue()
    {
        return $this->_app->getRequest()->getHeader('HOST');
    }
}