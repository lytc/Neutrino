<?php
use neutrino\route\Regex,
    neutrino\App;

require_once dirname(__FILE__) . '/../../bootstrap.php';

class Neutrino_Route_Regex_Test extends PHPUnit_Framework_TestCase
{
    public function testMatchShouldBeReturnFalse()
    {
        $_SERVER['REQUEST_METHOD'] = 'GET';
        $_SERVER['REQUEST_URI'] = '/test';

        $app = new App();

        $route = new Regex('/([\w_\-]+)/(\d+)', function() {});
        $result = $route->match($app);
        $this->assertFalse($result);
    }

    public function testMatch()
    {
        $_SERVER['REQUEST_METHOD'] = 'GET';
        $_SERVER['REQUEST_URI'] = '/posts/1';

        $app = new App();

        $route = new Regex('/([\w_\-]+)/(\d+)', function() {});
        $result = $route->match($app);
        $this->assertEquals(['posts', 1], $result);
    }
}