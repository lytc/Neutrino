<?php
namespace neutrino\route;
use neutrino\route;

class Regex extends AbstractRoute
{
    public function match($uri)
    {
        if (preg_match("#{$this->_pattern}#", $uri, $matches)) {
            array_shift($matches);
            return $matches;
        }

        return false;
    }
}