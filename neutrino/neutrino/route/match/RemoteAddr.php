<?php

namespace neutrino\route\match;

use neutrino\route\match\AbstractMatch;

class RemoteAddr extends AbstractMatch
{
    /**
     * @return string
     */
    protected function _getValue()
    {
        return $this->_app->getRequest()->getServer('REMOTE_ADDR');
    }
}