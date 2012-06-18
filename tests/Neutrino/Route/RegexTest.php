<?php

require_once dirname(__FILE__) . '/../../bootstrap.php';

class Neutrino_Route_Regex_Test extends PHPUnit_Framework_TestCase
{
    public function testMatchShouldBeReturnFalse()
    {
        $route = new Neutrino_Route_Regex('/([\w_\-]+)/(\d+)', function() {});
        $result = $route->match('/test');
        $this->assertFalse($result);
    }

    public function testMatch()
    {
        $route = new Neutrino_Route_Regex('/([\w_\-]+)/(\d+)', function() {});
        $result = $route->match('/posts/1');
        $this->assertEquals(['posts', 1], $result);
    }
}