<?php

class Neutrino_Route_Named extends Neutrino_Route_Abstract
{
    public function match($uri) {
        $pattern = preg_quote($this->_pattern, '#');
        $pattern = preg_replace('/\\\:([\w_\-]+)/', '(?<$1>[\w_\-]+)', $pattern);
        $pattern = "#^$pattern$#";

        if (preg_match($pattern, $uri, $matches)) {
            array_shift($matches);
            $params = [];
            foreach ($matches as $key => $value) {
                if (is_numeric($key)) {
                    continue;
                }
                $params[$key] = $value;
            }
            return $params;
        }
        return false;
    }
}