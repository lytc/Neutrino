<?php

class Neutrino_Route_Regex extends Neutrino_Route_Abstract
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