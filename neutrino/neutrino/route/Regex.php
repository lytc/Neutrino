<?php
namespace neutrino\route;
use neutrino\route;

class Regex extends AbstractRoute
{
    protected function _matchUri($uri)
    {
        if (preg_match("#{$this->_pattern}#", $uri, $matches)) {
            array_shift($matches);
            return $matches;
        }

        return false;
    }
}