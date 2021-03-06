<?php

namespace Fastwf\Tests\Router;

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
        $route = new Route(['path' => 'hello/world', 'methods' => ['get'], 'handler' => null]);

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
        $route = new Route(['path' => 'hello/world', 'methods' => ['get'], 'handler' => null]);

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
        $route = new Route(['path' => '**', 'methods' => ['get'], 'handler' => null]);

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
        $route = new Route(['path' => 'user/{int:id}', 'methods' => ['get'], 'name' => 'test', 'handler' => null]);

        $this->assertEquals(['test/id' => 10], $route->match('user/10', 'GET')['parameters']);
    }

    /**
     * @covers Fastwf\Core\Router\BaseRoute
     * @covers Fastwf\Core\Router\Route
     * @covers Fastwf\Core\Router\Segment
     * @covers Fastwf\Core\Router\Parser\RouteParser
     * @covers Fastwf\Core\Router\Parser\SpecificationRouteParser
     */
    public function testMatchParameterNoRouteName() {
        $route = new Route(['path' => 'user/{int:id}', 'methods' => ['get'], 'handler' => null]);

        $this->assertEquals(['id' => 10], $route->match('user/10', 'GET')['parameters']);
    }

    /**
     * @covers Fastwf\Core\Router\BaseRoute
     * @covers Fastwf\Core\Router\Route
     * @covers Fastwf\Core\Router\Segment
     * @covers Fastwf\Core\Router\Parser\RouteParser
     * @covers Fastwf\Core\Router\Parser\SpecificationRouteParser
     */
    public function testMatchParameterRouteNameEmpty() {
        $route = new Route(['path' => 'user/{int:id}', 'methods' => ['get'], 'name' => '  ', 'handler' => null]);

        $this->assertEquals(['id' => 10], $route->match('user/10', 'GET')['parameters']);
    }

    /**
     * @covers Fastwf\Core\Router\BaseRoute
     * @covers Fastwf\Core\Router\Route
     * @covers Fastwf\Core\Router\Segment
     * @covers Fastwf\Core\Router\Parser\RouteParser
     * @covers Fastwf\Core\Router\Parser\SpecificationRouteParser
     */
    public function testMatchPath() {
        $route = new Route(['path' => '{path:fileName}', 'methods' => ['get'], 'handler' => null]);

        $this->assertNotNull($route->match('hello/accepted', 'GET'));
    }

}
