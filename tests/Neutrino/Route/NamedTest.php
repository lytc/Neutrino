<?php
use neutrino\App,
    neutrino\route\Named;

require_once dirname(__FILE__) . '/../../bootstrap.php';

class Neutrino_Route_Named_Test extends PHPUnit_Framework_TestCase
{
    public function testMatchShouldBeReturnFalse()
    {
        $_SERVER['REQUEST_METHOD'] = 'GET';
        $_SERVER['REQUEST_URI'] = '/test';

        $app = new App();
        $route = new Named('/:resources/:id', function() {});
        $result = $route->match($app);
        $this->assertFalse($result);
    }

    public function testMatch()
    {
        $_SERVER['REQUEST_METHOD'] = 'GET';
        $_SERVER['REQUEST_URI'] = '/posts/1';

        $app = new App();
        $route = new Named('/:resources/:id', function() {});
        $result = $route->match($app);
        $this->assertEquals(['resources' => 'posts', 'id' => 1], $result);
    }
}