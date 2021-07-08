<?php

use PHPUnit\Framework\TestCase;

use Fastwf\Core\Router\Route;

class RouteTest extends TestCase {

    /**
     * @covers Fastwf\Core\Router\BaseRoute
     * @covers Fastwf\Core\Router\Route
     * @covers Fastwf\Core\Router\Segment
     * @covers Fastwf\Core\Router\Parser\RouteParser
     * @covers Fastwf\Core\Router\Parser\SpecificationRouteParser
     */
    public function testMethod() {
        $route = new Route('hello/world', ['get'], 'test');

        $this->assertNotNull($route->match('hello/world', 'GET'));
        $this->assertNull($route->match('hello/world', 'POST'));
    }

    /**
     * @covers Fastwf\Core\Router\BaseRoute
     * @covers Fastwf\Core\Router\Route
     * @covers Fastwf\Core\Router\Segment
     * @covers Fastwf\Core\Router\Parser\RouteParser
     * @covers Fastwf\Core\Router\Parser\SpecificationRouteParser
     */
    public function testNotMatch() {
        $route = new Route('hello/world', ['get'], 'test');

        $this->assertNull($route->match('hello/rejected', 'GET'));
    }

    /**
     * @covers Fastwf\Core\Router\BaseRoute
     * @covers Fastwf\Core\Router\Route
     * @covers Fastwf\Core\Router\Segment
     * @covers Fastwf\Core\Router\Parser\RouteParser
     * @covers Fastwf\Core\Router\Parser\SpecificationRouteParser
     */
    public function testMatchWildcard() {
        $route = new Route('**', ['get'], 'test');

        $this->assertNotNull($route->match('hello/accepted', 'GET'));
    }

    /**
     * @covers Fastwf\Core\Router\BaseRoute
     * @covers Fastwf\Core\Router\Route
     * @covers Fastwf\Core\Router\Segment
     * @covers Fastwf\Core\Router\Parser\RouteParser
     * @covers Fastwf\Core\Router\Parser\SpecificationRouteParser
     */
    public function testMatchParameter() {
        $route = new Route('user/{int:id}', ['get'], 'test');

        $this->assertEquals(['test/id' => 10], $route->match('user/10', 'GET'));
    }

}
