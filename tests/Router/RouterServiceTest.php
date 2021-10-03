<?php

namespace Fastwf\Tests\Router;

use Fastwf\Core\Router\Route;
use PHPUnit\Framework\TestCase;
use Fastwf\Core\Router\RouterService;

class RouterServiceTest extends TestCase
{

    private $service;

    protected function setup(): void
    {
        $this->service = new RouterService(
            null,
            [
                new Route([
                    'path' => 'hello-world/{name}',
                    'methods' => ['GET'],
                    'handler' => null,
                    'name' => 'hello',
                ])
            ],
            '',
        );
    } 

    /**
     * @covers Fastwf\Core\Router\BaseRoute
     * @covers Fastwf\Core\Router\Route
     * @covers Fastwf\Core\Router\Mount
     * @covers Fastwf\Core\Router\Segment
     * @covers Fastwf\Core\Router\Parser\RouteParser
     * @covers Fastwf\Core\Router\Parser\SpecificationRouteParser
     * @covers Fastwf\Core\Router\Formatter\PartialPathFormatter
     * @covers Fastwf\Core\Router\Formatter\PathFormatter
     * @covers Fastwf\Core\Utils\ArrayUtil
     * @covers Fastwf\Core\Utils\AsyncProperty
     * @covers Fastwf\Core\Utils\StringUtil
     * @covers Fastwf\Core\Router\Formatter\RouteGenerator
     * @covers Fastwf\Core\Router\RouterService
     */
    public function testUrlFor()
    {
        $this->assertEquals(
            '/hello-world/foo',
            $this->service->urlFor('hello', ['hello/name' => 'foo']),
        );
    }

}