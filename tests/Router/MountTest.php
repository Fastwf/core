<?php

use PHPUnit\Framework\TestCase;

use Fastwf\Core\Router\Mount;
use Fastwf\Core\Router\Route;

class MountTest extends TestCase {

    /**
     * @covers Fastwf\Core\Router\BaseRoute
     * @covers Fastwf\Core\Router\Route
     * @covers Fastwf\Core\Router\Mount
     * @covers Fastwf\Core\Router\Segment
     * @covers Fastwf\Core\Router\Parser\RouteParser
     * @covers Fastwf\Core\Router\Parser\SpecificationRouteParser
     */
    public function testSynchronousRoutes() {
        $mount = new Mount(
            'mount',
            [
                new Route('**', ['GET'], 'wildcard')
            ],
            'mountPoint'
        );

        $this->assertNotNull($mount->match('mount/path/to/resource', 'GET'));
    }

    /**
     * @covers Fastwf\Core\Router\BaseRoute
     * @covers Fastwf\Core\Router\Route
     * @covers Fastwf\Core\Router\Mount
     * @covers Fastwf\Core\Router\Segment
     * @covers Fastwf\Core\Router\Parser\RouteParser
     * @covers Fastwf\Core\Router\Parser\SpecificationRouteParser
     */
    public function testAsynchronousRoutes() {
        $mount = new Mount(
            'mount',
            fn() => [
                new Route('**', ['GET'], 'wildcard')
            ],
            'mountPoint'
        );

        $this->assertNotNull($mount->match('mount/path/to/resource', 'GET'));
    }

    /**
     * @covers Fastwf\Core\Router\BaseRoute
     * @covers Fastwf\Core\Router\Route
     * @covers Fastwf\Core\Router\Mount
     * @covers Fastwf\Core\Router\Segment
     * @covers Fastwf\Core\Router\Parser\RouteParser
     * @covers Fastwf\Core\Router\Parser\SpecificationRouteParser
     */
    public function testNoRouteMatch() {
        $mount = new Mount(
            'mount',
            fn() => [
                new Route('user', ['GET'], 'getUsers')
            ],
            'mountPoint'
        );

        $this->assertNull($mount->match('mount/path/to/resource', 'GET'));
    }

    /**
     * @covers Fastwf\Core\Router\BaseRoute
     * @covers Fastwf\Core\Router\Route
     * @covers Fastwf\Core\Router\Mount
     * @covers Fastwf\Core\Router\Segment
     * @covers Fastwf\Core\Router\Parser\RouteParser
     * @covers Fastwf\Core\Router\Parser\SpecificationRouteParser
     */
    public function testMountNotMatch() {
        $mount = new Mount(
            'mount',
            [
                new Route('user', ['GET'], 'getUsers')
            ],
            'mountPoint'
        );

        $this->assertNull($mount->match('path/to/resource', 'GET'));
    }

    /**
     * @covers Fastwf\Core\Router\BaseRoute
     * @covers Fastwf\Core\Router\Route
     * @covers Fastwf\Core\Router\Mount
     * @covers Fastwf\Core\Router\Segment
     * @covers Fastwf\Core\Router\Parser\RouteParser
     * @covers Fastwf\Core\Router\Parser\SpecificationRouteParser
     */
    public function testMountMatchWildcard() {
        $mount = new Mount(
            'mount/**/',
            [
                new Route('user', ['GET'], 'getUsers')
            ],
            'mountPoint'
        );

        $this->assertNotNull($mount->match('mount/path/user', 'GET'));
    }

    /**
     * @covers Fastwf\Core\Router\BaseRoute
     * @covers Fastwf\Core\Router\Route
     * @covers Fastwf\Core\Router\Mount
     * @covers Fastwf\Core\Router\Segment
     * @covers Fastwf\Core\Router\Parser\RouteParser
     * @covers Fastwf\Core\Router\Parser\SpecificationRouteParser
     */
    public function testMountMatchParameters() {
        $mount = new Mount(
            'group/{id}/',
            [
                new Route('user/{int:id}', ['GET'], 'getUsers')
            ],
            'group'
        );

        $this->assertEquals(
            ["group/id" => 'fastwf-team', 'getUsers/id' => 10],
            $mount->match('group/fastwf-team/user/10', 'GET')
        );
    }

}
