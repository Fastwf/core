<?php

namespace Fastwf\Tests\Router;

use PHPUnit\Framework\TestCase;

use Fastwf\Core\Router\Route;
use Fastwf\Core\Router\RouterService;
use Fastwf\Tests\Engine\SimpleEngine;

class RouterServiceTest extends TestCase
{

    private $router;
    private $context;
    private $service;

    protected function setup(): void
    {
        $this->context = $this->getMockBuilder(SimpleEngine::class)
            ->setConstructorArgs([__DIR__ . '/../configuration.ini'])
            ->onlyMethods(['sendResponse', 'handleRequest'])
            ->getMock();

        $this->router = [
            new Route([
                'path' => 'hello-world/{name}',
                'methods' => ['GET'],
                'handler' => null,
                'name' => 'hello',
            ])
        ];
        $this->service = new RouterService($this->context, $this->router, '');
    } 

    /**
     * @covers Fastwf\Core\Components\RequestHandler
     * @covers Fastwf\Core\Configuration
     * @covers Fastwf\Core\Engine\Engine
     * @covers Fastwf\Core\Engine\Run\Runner
     * @covers Fastwf\Core\Http\Frame\Headers
     * @covers Fastwf\Core\Http\Frame\HttpRequest
     * @covers Fastwf\Core\Http\Frame\HttpResponse
     * @covers Fastwf\Core\Http\Frame\HttpStreamResponse
     * @covers Fastwf\Core\Http\HttpException
     * @covers Fastwf\Core\Http\NotFoundException
     * @covers Fastwf\Core\Router\BaseRoute
     * @covers Fastwf\Core\Router\Components\RouterShutdown
     * @covers Fastwf\Core\Router\Formatter\PartialPathFormatter
     * @covers Fastwf\Core\Router\Formatter\PathFormatter
     * @covers Fastwf\Core\Router\Formatter\RouteGenerator
     * @covers Fastwf\Core\Router\Mount
     * @covers Fastwf\Core\Router\Parser\RouteParser
     * @covers Fastwf\Core\Router\Parser\SpecificationRouteParser
     * @covers Fastwf\Core\Router\Route
     * @covers Fastwf\Core\Router\RouterService
     * @covers Fastwf\Core\Router\Segment
     * @covers Fastwf\Core\Utils\ArrayProxy
     * @covers Fastwf\Core\Utils\ArrayUtil
     * @covers Fastwf\Core\Utils\AsyncProperty
     * @covers Fastwf\Core\Utils\Logging\DefaultLogger
     * @covers Fastwf\Core\Utils\StringUtil
     */
    public function testUrlFor()
    {
        $this->context->run();

        $this->assertEquals(
            '/hello-world/foo',
            $this->service->urlFor('hello', ['hello/name' => 'foo']),
        );
    }

    /**
     * @covers Fastwf\Core\Components\RequestHandler
     * @covers Fastwf\Core\Configuration
     * @covers Fastwf\Core\Engine\Engine
     * @covers Fastwf\Core\Engine\Run\Runner
     * @covers Fastwf\Core\Http\Frame\Headers
     * @covers Fastwf\Core\Http\Frame\HttpRequest
     * @covers Fastwf\Core\Http\Frame\HttpResponse
     * @covers Fastwf\Core\Http\Frame\HttpStreamResponse
     * @covers Fastwf\Core\Http\HttpException
     * @covers Fastwf\Core\Http\NotFoundException
     * @covers Fastwf\Core\Router\BaseRoute
     * @covers Fastwf\Core\Router\Components\RouterShutdown
     * @covers Fastwf\Core\Router\Formatter\PartialPathFormatter
     * @covers Fastwf\Core\Router\Formatter\PathFormatter
     * @covers Fastwf\Core\Router\Formatter\RouteGenerator
     * @covers Fastwf\Core\Router\Mount
     * @covers Fastwf\Core\Router\Parser\RouteParser
     * @covers Fastwf\Core\Router\Parser\SpecificationRouteParser
     * @covers Fastwf\Core\Router\Route
     * @covers Fastwf\Core\Router\RouterService
     * @covers Fastwf\Core\Router\Segment
     * @covers Fastwf\Core\Utils\ArrayProxy
     * @covers Fastwf\Core\Utils\ArrayUtil
     * @covers Fastwf\Core\Utils\AsyncProperty
     * @covers Fastwf\Core\Utils\Logging\DefaultLogger
     * @covers Fastwf\Core\Utils\StringUtil
     */
    public function testDumpRouteGeneratorNoFile()
    {
        $_ENV['SERVER_MODEPRODUCTION'] = 'no';
        $this->context->run();

        $this->service->dumpRouteGenerator();

        $this->assertFalse(\file_exists($this->getCachedPath()));
    }

    /**
     * @covers Fastwf\Core\Components\RequestHandler
     * @covers Fastwf\Core\Configuration
     * @covers Fastwf\Core\Engine\Engine
     * @covers Fastwf\Core\Engine\Run\Runner
     * @covers Fastwf\Core\Http\Frame\Headers
     * @covers Fastwf\Core\Http\Frame\HttpRequest
     * @covers Fastwf\Core\Http\Frame\HttpResponse
     * @covers Fastwf\Core\Http\Frame\HttpStreamResponse
     * @covers Fastwf\Core\Http\HttpException
     * @covers Fastwf\Core\Http\NotFoundException
     * @covers Fastwf\Core\Router\BaseRoute
     * @covers Fastwf\Core\Router\Components\RouterShutdown
     * @covers Fastwf\Core\Router\Formatter\PartialPathFormatter
     * @covers Fastwf\Core\Router\Formatter\PathFormatter
     * @covers Fastwf\Core\Router\Formatter\RouteGenerator
     * @covers Fastwf\Core\Router\Mount
     * @covers Fastwf\Core\Router\Parser\RouteParser
     * @covers Fastwf\Core\Router\Parser\SpecificationRouteParser
     * @covers Fastwf\Core\Router\Route
     * @covers Fastwf\Core\Router\RouterService
     * @covers Fastwf\Core\Router\Segment
     * @covers Fastwf\Core\Utils\ArrayProxy
     * @covers Fastwf\Core\Utils\ArrayUtil
     * @covers Fastwf\Core\Utils\AsyncProperty
     * @covers Fastwf\Core\Utils\Logging\DefaultLogger
     * @covers Fastwf\Core\Utils\StringUtil
     */
    public function testDumpRouteGeneratorFile()
    {
        $_ENV['SERVER_MODEPRODUCTION'] = 'yes';
        $this->context->run();

        $this->service->dumpRouteGenerator();

        $this->assertTrue(\file_exists($this->getCachedPath()));
    }

    /**
     * @covers Fastwf\Core\Components\RequestHandler
     * @covers Fastwf\Core\Configuration
     * @covers Fastwf\Core\Engine\Engine
     * @covers Fastwf\Core\Engine\Run\Runner
     * @covers Fastwf\Core\Http\Frame\Headers
     * @covers Fastwf\Core\Http\Frame\HttpRequest
     * @covers Fastwf\Core\Http\Frame\HttpResponse
     * @covers Fastwf\Core\Http\Frame\HttpStreamResponse
     * @covers Fastwf\Core\Http\HttpException
     * @covers Fastwf\Core\Http\NotFoundException
     * @covers Fastwf\Core\Router\BaseRoute
     * @covers Fastwf\Core\Router\Components\RouterShutdown
     * @covers Fastwf\Core\Router\Formatter\PartialPathFormatter
     * @covers Fastwf\Core\Router\Formatter\PathFormatter
     * @covers Fastwf\Core\Router\Formatter\RouteGenerator
     * @covers Fastwf\Core\Router\Mount
     * @covers Fastwf\Core\Router\Parser\RouteParser
     * @covers Fastwf\Core\Router\Parser\SpecificationRouteParser
     * @covers Fastwf\Core\Router\Route
     * @covers Fastwf\Core\Router\RouterService
     * @covers Fastwf\Core\Router\Segment
     * @covers Fastwf\Core\Utils\ArrayProxy
     * @covers Fastwf\Core\Utils\ArrayUtil
     * @covers Fastwf\Core\Utils\AsyncProperty
     * @covers Fastwf\Core\Utils\Logging\DefaultLogger
     * @covers Fastwf\Core\Utils\StringUtil
     */
    public function testRestoreRouteGenerator()
    {
        $_ENV['SERVER_MODEPRODUCTION'] = 'yes';
        $this->context->run();

        // call urlFor to cache the route generator
        $this->service->urlFor('hello', ['hello/name' => 'foo']);

        // Create the cache file
        $this->service->dumpRouteGenerator();

        $cachePath = $this->getCachedPath();
        $cachedData = \file_get_contents($cachePath);
        $cachedState = \unserialize($cachedData);

        // Build another service to create a new RouteGenerator instance from the cached file
        $service = new RouterService($this->context, $this->router, '');
        $routerGenerator = $service->restoreRouteGenerator();

        // Verify that the restored state is equals to the dumped state
        $this->assertEquals(
            $cachedState,
            $routerGenerator->dumpState()
        );

        // Corrupt the cached file to create an instance of RouteGenerator from empty state
        \file_put_contents($cachePath, "data not compatible with the serialized data");

        // Build another service to create a new RouteGenerator instance from the cached file corrupted
        $otherGenerator = (new RouterService($this->context, $this->router, ''))->restoreRouteGenerator();

        // Verify that the restored state is not equals to the initial dumped state
        $this->assertNotEquals(
            $cachedState,
            $otherGenerator->dumpState()
        );
    }

    protected function tearDown(): void {
        $cachePath = $this->getCachedPath();
        if (\file_exists($cachePath)) {
            \unlink($cachePath);
        }
    }

    private function getCachedPath() {
        return $this->context->getCachePath('fastwf.core')
            . DIRECTORY_SEPARATOR
            . RouterService::ROUTE_STATE_NAME;
    }

}