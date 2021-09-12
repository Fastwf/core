<?php

namespace Fastwf\Tests\Engine;

use PHPUnit\Framework\TestCase;

use Fastwf\Core\Engine\Service;

use Fastwf\Tests\Engine\SimpleEngine;
use Fastwf\Tests\Engine\Services\SimpleService;

class EngineTest extends TestCase {

    const TEST_CONF = __DIR__ . '/../configuration.ini';

    /**
     * @covers \Fastwf\Core\Engine\Engine
     * @covers \Fastwf\Core\Configuration
     * @covers \Fastwf\Core\Http\Frame\HttpResponse
     * @covers \Fastwf\Core\Http\Frame\HttpStreamResponse
     * @covers \Fastwf\Core\Http\HttpException
     * @covers \Fastwf\Core\Http\NotFoundException
     * @covers \Fastwf\Core\Router\BaseRoute
     * @covers \Fastwf\Core\Router\Mount
     * @covers \Fastwf\Core\Router\Route
     * @covers \Fastwf\Core\Router\Parser\RouteParser
     * @covers \Fastwf\Core\Utils\ArrayProxy
     * @covers \Fastwf\Core\Utils\ArrayUtil
     * @covers \Fastwf\Core\Utils\AsyncProperty
     * @covers \Fastwf\Core\Components\RequestHandler
     * @covers \Fastwf\Core\Engine\Run\Runner
     * @covers \Fastwf\Core\Http\Frame\Headers
     * @covers \Fastwf\Core\Http\Frame\HttpRequest
     * @covers \Fastwf\Core\Router\Parser\SpecificationRouteParser
     * @covers \Fastwf\Core\Router\Segment
     */
    public function testConfiguration() {
        $engine = $this->getMockBuilder(SimpleEngine::class)
            ->setConstructorArgs([self::TEST_CONF])
            ->onlyMethods(['handleRequest', 'sendResponse'])
            ->getMock();

        $engine->run();

        $this->assertNotNull($engine->getConfiguration());
    }

    /**
     * @covers \Fastwf\Core\Engine\Engine
     * @covers \Fastwf\Core\Configuration
     * @covers \Fastwf\Core\Http\Frame\HttpResponse
     * @covers \Fastwf\Core\Http\Frame\HttpStreamResponse
     * @covers \Fastwf\Core\Http\HttpException
     * @covers \Fastwf\Core\Http\NotFoundException
     * @covers \Fastwf\Core\Router\BaseRoute
     * @covers \Fastwf\Core\Router\Mount
     * @covers \Fastwf\Core\Router\Route
     * @covers \Fastwf\Core\Router\Parser\RouteParser
     * @covers \Fastwf\Core\Utils\ArrayProxy
     * @covers \Fastwf\Core\Utils\ArrayUtil
     * @covers \Fastwf\Core\Utils\AsyncProperty
     * @covers \Fastwf\Core\Components\RequestHandler
     * @covers \Fastwf\Core\Engine\Run\Runner
     * @covers \Fastwf\Core\Http\Frame\Headers
     * @covers \Fastwf\Core\Http\Frame\HttpRequest
     * @covers \Fastwf\Core\Router\Parser\SpecificationRouteParser
     * @covers \Fastwf\Core\Router\Segment
     */
    public function testServer() {
        $engine = $this->getMockBuilder(SimpleEngine::class)
            ->setConstructorArgs([self::TEST_CONF])
            ->onlyMethods(['handleRequest', 'sendResponse'])
            ->getMock();

        $engine->run();

        $this->assertNotNull($engine->getServer());
    }

    /**
     * @covers \Fastwf\Core\Engine\Engine
     * @covers \Fastwf\Core\Configuration
     * @covers \Fastwf\Core\Http\Frame\HttpResponse
     * @covers \Fastwf\Core\Http\Frame\HttpStreamResponse
     * @covers \Fastwf\Core\Http\HttpException
     * @covers \Fastwf\Core\Http\NotFoundException
     * @covers \Fastwf\Core\Router\BaseRoute
     * @covers \Fastwf\Core\Router\Mount
     * @covers \Fastwf\Core\Router\Route
     * @covers \Fastwf\Core\Router\Parser\RouteParser
     * @covers \Fastwf\Core\Utils\ArrayProxy
     * @covers \Fastwf\Core\Utils\ArrayUtil
     * @covers \Fastwf\Core\Utils\AsyncProperty
     * @covers \Fastwf\Core\Components\RequestHandler
     * @covers \Fastwf\Core\Engine\Run\Runner
     * @covers \Fastwf\Core\Http\Frame\Headers
     * @covers \Fastwf\Core\Http\Frame\HttpRequest
     * @covers \Fastwf\Core\Router\Parser\SpecificationRouteParser
     * @covers \Fastwf\Core\Router\Segment
     */
    public function testConfigurationAuto() {
        $_SERVER['SCRIPT_FILENAME'] = __FILE__;

        $engine = $this->getMockBuilder(SimpleEngine::class)
            ->onlyMethods(['handleRequest', 'sendResponse'])
            ->getMock();

        $engine->run();

        $this->assertNotNull($engine->getConfiguration());
    }

    /**
     * Verify that metadata is set using the configure implementation.
     * 
     * @covers \Fastwf\Core\Engine\Engine
     * @covers \Fastwf\Core\Configuration
     * @covers \Fastwf\Core\Http\Frame\HttpResponse
     * @covers \Fastwf\Core\Http\Frame\HttpStreamResponse
     * @covers \Fastwf\Core\Http\HttpException
     * @covers \Fastwf\Core\Http\NotFoundException
     * @covers \Fastwf\Core\Router\BaseRoute
     * @covers \Fastwf\Core\Router\Mount
     * @covers \Fastwf\Core\Router\Route
     * @covers \Fastwf\Core\Router\Parser\RouteParser
     * @covers \Fastwf\Core\Utils\ArrayProxy
     * @covers \Fastwf\Core\Utils\ArrayUtil
     * @covers \Fastwf\Core\Utils\AsyncProperty
     * @covers \Fastwf\Core\Components\RequestHandler
     * @covers \Fastwf\Core\Engine\Run\Runner
     * @covers \Fastwf\Core\Http\Frame\Headers
     * @covers \Fastwf\Core\Http\Frame\HttpRequest
     * @covers \Fastwf\Core\Router\Parser\SpecificationRouteParser
     * @covers \Fastwf\Core\Router\Segment
     */
    public function testOnConfigurationLoaded() {
        $engine = $this->getMockBuilder(SimpleEngine::class)
            ->setConstructorArgs([self::TEST_CONF])
            ->onlyMethods(['handleRequest', 'sendResponse'])
            ->getMock();

        $engine->run();

        $this->assertEquals('Fastwf Tests', $engine->getMetadata()->get('application'));
    }

    /**
     * Verify that guards are generated and loaded in guard list of engine.
     * 
     * @covers \Fastwf\Core\Engine\Engine
     * @covers \Fastwf\Core\Configuration
     * @covers \Fastwf\Core\Http\Frame\HttpResponse
     * @covers \Fastwf\Core\Http\Frame\HttpStreamResponse
     * @covers \Fastwf\Core\Http\HttpException
     * @covers \Fastwf\Core\Http\NotFoundException
     * @covers \Fastwf\Core\Router\BaseRoute
     * @covers \Fastwf\Core\Router\Mount
     * @covers \Fastwf\Core\Router\Route
     * @covers \Fastwf\Core\Router\Parser\RouteParser
     * @covers \Fastwf\Core\Utils\ArrayProxy
     * @covers \Fastwf\Core\Utils\ArrayUtil
     * @covers \Fastwf\Core\Utils\AsyncProperty
     * @covers \Fastwf\Core\Components\RequestHandler
     * @covers \Fastwf\Core\Engine\Run\Runner
     * @covers \Fastwf\Core\Http\Frame\Headers
     * @covers \Fastwf\Core\Http\Frame\HttpRequest
     * @covers \Fastwf\Core\Router\Parser\SpecificationRouteParser
     * @covers \Fastwf\Core\Router\Segment
     */
    public function testSettingLoadingSystem() {
        $engine = $this->getMockBuilder(SimpleEngine::class)
            ->setConstructorArgs([self::TEST_CONF])
            ->onlyMethods(['handleRequest', 'sendResponse'])
            ->getMock();

        $engine->run();

        $this->assertNotEquals(0, \count($engine->getGuards()));
    }

    /**
     * Test 500 internal error
     * 
     * @covers \Fastwf\Core\Engine\Engine
     * @covers \Fastwf\Core\Configuration
     * @covers \Fastwf\Core\Http\Frame\HttpResponse
     * @covers \Fastwf\Core\Http\Frame\HttpStreamResponse
     * @covers \Fastwf\Core\Http\HttpException
     * @covers \Fastwf\Core\Http\NotFoundException
     * @covers \Fastwf\Core\Router\BaseRoute
     * @covers \Fastwf\Core\Router\Mount
     * @covers \Fastwf\Core\Router\Route
     * @covers \Fastwf\Core\Router\Parser\RouteParser
     * @covers \Fastwf\Core\Utils\ArrayProxy
     * @covers \Fastwf\Core\Utils\ArrayUtil
     * @covers \Fastwf\Core\Utils\AsyncProperty
     * @covers \Fastwf\Core\Components\RequestHandler
     * @covers \Fastwf\Core\Engine\Run\Runner
     * @covers \Fastwf\Core\Http\Frame\Headers
     * @covers \Fastwf\Core\Http\Frame\HttpRequest
     * @covers \Fastwf\Core\Router\Parser\SpecificationRouteParser
     * @covers \Fastwf\Core\Router\Segment
     * @covers \Fastwf\Core\Engine\Output\ApacheHttpOutput
     */
    public function testInternalError() {
        $_SERVER['REQUEST_URI'] = '/fail';
        $_SERVER['REQUEST_METHOD'] = 'GET';

        $this->expectOutputRegex('/^$/');

        $engine = new SimpleEngine(self::TEST_CONF);
        $engine->run();
    }

    /**
     * Test 200 response.
     * 
     * @covers \Fastwf\Core\Engine\Engine
     * @covers \Fastwf\Core\Configuration
     * @covers \Fastwf\Core\Http\Frame\HttpResponse
     * @covers \Fastwf\Core\Http\Frame\HttpStreamResponse
     * @covers \Fastwf\Core\Http\HttpException
     * @covers \Fastwf\Core\Http\NotFoundException
     * @covers \Fastwf\Core\Router\BaseRoute
     * @covers \Fastwf\Core\Router\Mount
     * @covers \Fastwf\Core\Router\Route
     * @covers \Fastwf\Core\Router\Parser\RouteParser
     * @covers \Fastwf\Core\Utils\ArrayProxy
     * @covers \Fastwf\Core\Utils\ArrayUtil
     * @covers \Fastwf\Core\Utils\AsyncProperty
     * @covers \Fastwf\Core\Components\RequestHandler
     * @covers \Fastwf\Core\Engine\Run\Runner
     * @covers \Fastwf\Core\Http\Frame\Headers
     * @covers \Fastwf\Core\Http\Frame\HttpRequest
     * @covers \Fastwf\Core\Router\Parser\SpecificationRouteParser
     * @covers \Fastwf\Core\Router\Segment
     * @covers \Fastwf\Core\Engine\Output\ApacheHttpOutput
     */
    public function testSuccessResponse() {
        $_SERVER['REQUEST_URI'] = '/success';
        $_SERVER['REQUEST_METHOD'] = 'GET';

        $this->expectOutputString('success');

        $engine = new SimpleEngine(self::TEST_CONF);
        $engine->run();
    }

    /**
     * Test 404 response.
     * 
     * @covers \Fastwf\Core\Engine\Engine
     * @covers \Fastwf\Core\Configuration
     * @covers \Fastwf\Core\Http\Frame\HttpResponse
     * @covers \Fastwf\Core\Http\Frame\HttpStreamResponse
     * @covers \Fastwf\Core\Http\HttpException
     * @covers \Fastwf\Core\Http\NotFoundException
     * @covers \Fastwf\Core\Router\BaseRoute
     * @covers \Fastwf\Core\Router\Mount
     * @covers \Fastwf\Core\Router\Route
     * @covers \Fastwf\Core\Router\Parser\RouteParser
     * @covers \Fastwf\Core\Utils\ArrayProxy
     * @covers \Fastwf\Core\Utils\ArrayUtil
     * @covers \Fastwf\Core\Utils\AsyncProperty
     * @covers \Fastwf\Core\Components\RequestHandler
     * @covers \Fastwf\Core\Engine\Run\Runner
     * @covers \Fastwf\Core\Http\Frame\Headers
     * @covers \Fastwf\Core\Http\Frame\HttpRequest
     * @covers \Fastwf\Core\Router\Parser\SpecificationRouteParser
     * @covers \Fastwf\Core\Router\Segment
     * @covers \Fastwf\Core\Engine\Output\ApacheHttpOutput
     */
    public function testNotFoundResponse() {
        $_SERVER['REQUEST_URI'] = '/not-found';
        $_SERVER['REQUEST_METHOD'] = 'GET';

        $this->expectOutputString("No match for '/not-found'");

        $engine = new SimpleEngine(self::TEST_CONF);
        $engine->run();
    }

    /**
     * @covers \Fastwf\Core\Engine\Engine
     * @covers \Fastwf\Core\Engine\Service
     * @covers \Fastwf\Core\Utils\ArrayProxy
     * @covers \Fastwf\Core\Utils\AsyncProperty
     */
    public function testGetService() {
        $engine = new SimpleEngine(self::TEST_CONF);

        $service = $engine->getService(SimpleService::class);

        // Check is service and the service is a singleton
        $this->assertTrue(\is_subclass_of($service, Service::class));
        $this->assertEquals($service, $engine->getService(SimpleService::class));
    }

    /**
     * @covers \Fastwf\Core\Engine\Engine
     * @covers \Fastwf\Core\Engine\Service
     * @covers \Fastwf\Core\Utils\ArrayProxy
     * @covers \Fastwf\Core\Utils\AsyncProperty
     */
    public function testRegisterService() {
        $engine = new SimpleEngine(self::TEST_CONF);

        $service = new SimpleService($engine);

        $engine->registerService(SimpleService::class, $service);

        // Check the service is the registered service
        $this->assertEquals($service, $engine->getService(SimpleService::class));
    }

    /**
     * @covers \Fastwf\Core\Engine\Engine
     * @covers \Fastwf\Core\Engine\Service
     * @covers \Fastwf\Core\Utils\ArrayProxy
     * @covers \Fastwf\Core\Utils\AsyncProperty
     */
    public function testRegisterServiceFactory() {
        $engine = new SimpleEngine(self::TEST_CONF);

        $service = null;

        $engine->registerService(
            SimpleService::class,
            function () use ($engine, &$service) {
                $service = new SimpleService($engine);

                return $service;
            }
        );

        // Check the service is the registered service
        $this->assertNotNull($engine->getService(SimpleService::class));
        $this->assertEquals($service, $engine->getService(SimpleService::class));
    }

    /**
     * Test 500 internal error with old error style
     * 
     * @covers \Fastwf\Core\Engine\Engine
     * @covers \Fastwf\Core\Configuration
     * @covers \Fastwf\Core\Http\Frame\HttpResponse
     * @covers \Fastwf\Core\Http\Frame\HttpStreamResponse
     * @covers \Fastwf\Core\Http\HttpException
     * @covers \Fastwf\Core\Http\NotFoundException
     * @covers \Fastwf\Core\Router\BaseRoute
     * @covers \Fastwf\Core\Router\Mount
     * @covers \Fastwf\Core\Router\Route
     * @covers \Fastwf\Core\Router\Parser\RouteParser
     * @covers \Fastwf\Core\Utils\ArrayProxy
     * @covers \Fastwf\Core\Utils\ArrayUtil
     * @covers \Fastwf\Core\Utils\AsyncProperty
     * @covers \Fastwf\Core\Components\RequestHandler
     * @covers \Fastwf\Core\Engine\Run\Runner
     * @covers \Fastwf\Core\Http\Frame\Headers
     * @covers \Fastwf\Core\Http\Frame\HttpRequest
     * @covers \Fastwf\Core\Router\Parser\SpecificationRouteParser
     * @covers \Fastwf\Core\Router\Segment
     * @covers \Fastwf\Core\Engine\Output\ApacheHttpOutput
     */
    public function testExceptionHandler() {
        $_SERVER['REQUEST_URI'] = '/fail-old-style';
        $_SERVER['REQUEST_METHOD'] = 'GET';

        $this->expectOutputRegex('/^$/');

        $engine = new SimpleEngine(self::TEST_CONF);
        $engine->run();
    }

}
