<?php
use neutrino\route\Named;

require_once dirname(__FILE__) . '/../../bootstrap.php';

class Neutrino_Route_Named_Test extends PHPUnit_Framework_TestCase
{
    public function testMatchShouldBeReturnFalse()
    {
        $route = new Named('/:resources/:id', function() {});
        $result = $route->match('/test');
        $this->assertFalse($result);
    }

    public function testMatch()
    {
        $route = new Named('/:resources/:id', function() {});
        $result = $route->match('/posts/1');
        $this->assertEquals(['resources' => 'posts', 'id' => 1], $result);
    }
}