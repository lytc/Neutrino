<?php
namespace neutrino\route;
use neutrino\route;

class Named extends AbstractRoute
{
    protected function _matchUri($uri) {
        $pattern = preg_quote($this->_pattern, '#');

        $pattern = preg_replace('/\\\:([^\/]+)/', '(?<$1>[^\/]+)', $pattern);
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