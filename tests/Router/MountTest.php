<?php

namespace Fastwf\Tests\Router;

use PHPUnit\Framework\TestCase;

use Fastwf\Core\Router\Mount;
use Fastwf\Core\Router\Route;
use Fastwf\Core\Router\Exception\MountException;


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
        $mount = new Mount([
            "path" => "mount",
            "routes" => [
                new Route([
                    'path' => '**',
                    'methods' => ['GET'],
                    'handler' => null,
                ]),
            ]
        ]);

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
        $mount = new Mount([
            'path' => 'mount',
            'routes' => function () {
                return [
                    new Route([
                        'path' => '**',
                        'methods' => ['GET'],
                        'handler' => null,
                    ]),
                ];
            },
        ]);

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
        $mount = new Mount([
            'path' => 'mount',
            'routes' => function () {
                return [
                    new Route([
                        'path' => 'user',
                        'methods' => ['GET'],
                        'handler' => null,
                        'name' => 'getUsers',
                    ]),
                ];
            },
        ]);

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
        $mount = new Mount([
            'path' => 'mount',
            'routes' => [
                new Route([
                    'path' => 'user',
                    'methods' => ['GET'],
                    'handler' => null,
                    'name' => 'getUsers',
                ]),
            ],
        ]);

        $this->assertNull($mount->match('path/to/resource', 'GET'));
    }

    /**
     * @covers Fastwf\Core\Router\BaseRoute
     * @covers Fastwf\Core\Router\Route
     * @covers Fastwf\Core\Router\Mount
     * @covers Fastwf\Core\Router\Segment
     * @covers Fastwf\Core\Router\Parser\RouteParser
     * @covers Fastwf\Core\Router\Parser\SpecificationRouteParser
     * @covers Fastwf\Core\Router\Exception\MountException
     */
    public function testMountWildcardFailed() {
        $this->expectException(MountException::class);

        $mount = new Mount([
            'path' => 'mount/**/',
            'routes' => [
                new Route([
                    'path' => 'user',
                    'methods' => ['GET'],
                    'handler' => null,
                    'name' => 'getUsers',
                ]),
            ],
        ]);

        $this->assertNotNull($mount->match('mount/path/user', 'GET'));
    }

    /**
     * @covers Fastwf\Core\Router\BaseRoute
     * @covers Fastwf\Core\Router\Route
     * @covers Fastwf\Core\Router\Mount
     * @covers Fastwf\Core\Router\Segment
     * @covers Fastwf\Core\Router\Parser\RouteParser
     * @covers Fastwf\Core\Router\Parser\SpecificationRouteParser
     * @covers Fastwf\Core\Router\Exception\MountException
     */
    public function testMountPathFailed() {
        $this->expectException(MountException::class);

        $mount = new Mount([
            'path' => 'mount/{path:fileName}/',
            'routes' => [
                new Route([
                    'path' => 'user',
                    'methods' => ['GET'],
                    'handler' => null,
                    'name' => 'getUsers',
                ]),
            ],
        ]);

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
        $mount = new Mount([
            'path' => 'group/{id}/',
            'routes' => [
                new Route([
                    'path' => 'user/{int:id}',
                    'methods' => ['GET'],
                    'handler' => null,
                    'name' => 'getUsers',
                ]),
            ],
            'name' => 'group'
        ]);

        $this->assertEquals(
            ["group/id" => 'fastwf-team', 'getUsers/id' => 10],
            $mount->match('group/fastwf-team/user/10', 'GET')['parameters']
        );
    }

    /**
     * @covers Fastwf\Core\Router\BaseRoute
     * @covers Fastwf\Core\Router\Route
     * @covers Fastwf\Core\Router\Mount
     * @covers Fastwf\Core\Router\Segment
     * @covers Fastwf\Core\Router\Parser\RouteParser
     * @covers Fastwf\Core\Router\Parser\SpecificationRouteParser
     */
    public function testMountMatchParametersNoMountName() {
        $mount = new Mount([
            'path' => 'group/{id}/',
            'routes' => [
                new Route([
                    'path' => 'user/{int:id}',
                    'methods' => ['GET'],
                    'handler' => null,
                    'name' => 'getUsers',
                ]),
            ],
        ]);

        $this->assertEquals(
            ["id" => 'fastwf-team', 'getUsers/id' => 10],
            $mount->match('group/fastwf-team/user/10', 'GET')['parameters']
        );
    }

    /**
     * @covers Fastwf\Core\Router\BaseRoute
     * @covers Fastwf\Core\Router\Route
     * @covers Fastwf\Core\Router\Mount
     * @covers Fastwf\Core\Router\Segment
     * @covers Fastwf\Core\Router\Parser\RouteParser
     * @covers Fastwf\Core\Router\Parser\SpecificationRouteParser
     */
    public function testMountOnEmpty() {
        $mount = new Mount([
            'path' => '',
            'routes' => [
                new Route([
                    'path' => '',
                    'methods' => ['GET'],
                    'handler' => null,
                ]),
            ],
        ]);

        $this->assertNull(
            $mount->match('users/', 'GET')
        );
    }

    /**
     * @covers Fastwf\Core\Router\BaseRoute
     * @covers Fastwf\Core\Router\Route
     * @covers Fastwf\Core\Router\Mount
     * @covers Fastwf\Core\Router\Segment
     * @covers Fastwf\Core\Router\Parser\RouteParser
     * @covers Fastwf\Core\Router\Parser\SpecificationRouteParser
     */
    public function testMatchWithMountPath() {
        $mount = new Mount([
            'path' => 'prefix',
            'routes' => [
                new Route([
                    'path' => '',
                    'methods' => ['GET'],
                    'handler' => null,
                ]),
            ],
        ]);

        $this->assertNull(
            $mount->match('', 'GET')
        );
    }

    /**
     * @covers Fastwf\Core\Router\BaseRoute
     * @covers Fastwf\Core\Router\Route
     * @covers Fastwf\Core\Router\Mount
     * @covers Fastwf\Core\Router\Segment
     * @covers Fastwf\Core\Router\Parser\RouteParser
     * @covers Fastwf\Core\Router\Parser\SpecificationRouteParser
     */
    public function testGetRouteIterator() {
        $mount = new Mount([
            "path" => "mount",
            "routes" => [
                new Route([
                    'path' => 'route',
                    'methods' => ['GET'],
                    'handler' => null,
                ]),
            ]
        ]);

        $this->assertEquals(
            ['route'],
            \array_map(function ($route) { return $route->getPath(); }, \iterator_to_array($mount->getRouteIterator())),
        );
    }

}
