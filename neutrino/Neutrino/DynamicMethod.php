<?php

class Neutrino_DynamicMethod
{
    public function __call($method, $args)
    {
        if ($this->{$method} instanceof Closure) {
            return call_user_func_array($this->{$method}, $args);
        }
        return parent::__call($method, $args);
    }
}