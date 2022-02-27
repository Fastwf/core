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
     * @covers \Fastwf\Core\Engine\ServiceProvider
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
     * @covers \Fastwf\Core\Utils\Logging\DefaultLogger
     * @covers \Fastwf\Core\Router\RouterService
     * @covers \Fastwf\Core\Router\Components\RouterShutdown
     * @covers \Fastwf\Core\Router\Formatter\RouteGenerator
     * @covers \Fastwf\Core\Router\Formatter\PathFormatter
     * @covers \Fastwf\Core\Router\Formatter\PartialPathFormatter
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
     * @covers \Fastwf\Core\Engine\ServiceProvider
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
     * @covers \Fastwf\Core\Utils\Logging\DefaultLogger
     * @covers \Fastwf\Core\Router\RouterService
     * @covers \Fastwf\Core\Router\Components\RouterShutdown
     * @covers \Fastwf\Core\Router\Formatter\RouteGenerator
     * @covers \Fastwf\Core\Router\Formatter\PathFormatter
     * @covers \Fastwf\Core\Router\Formatter\PartialPathFormatter
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
     * @covers \Fastwf\Core\Engine\ServiceProvider
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
     * @covers \Fastwf\Core\Utils\Logging\DefaultLogger
     * @covers \Fastwf\Core\Router\RouterService
     * @covers \Fastwf\Core\Router\Components\RouterShutdown
     * @covers \Fastwf\Core\Router\Formatter\RouteGenerator
     * @covers \Fastwf\Core\Router\Formatter\PathFormatter
     * @covers \Fastwf\Core\Router\Formatter\PartialPathFormatter
     */
    public function testRequest() {
        $engine = $this->getMockBuilder(SimpleEngine::class)
            ->setConstructorArgs([self::TEST_CONF])
            ->onlyMethods(['handleRequest', 'sendResponse'])
            ->getMock();

        $engine->run();

        $this->assertNotNull($engine->getServer());
    }

    /**
     * @covers \Fastwf\Core\Engine\Engine
     * @covers \Fastwf\Core\Engine\ServiceProvider
     * @covers \Fastwf\Core\Configuration
     * @covers \Fastwf\Core\Http\Frame\HttpResponse
     * @covers \Fastwf\Core\Http\Frame\HttpStreamResponse
     * @covers \Fastwf\Core\Http\HttpException
     * @covers \Fastwf\Core\Http\NotFoundException
     * @covers \Fastwf\Core\Router\BaseRoute
     * @covers \Fastwf\Core\Router\Mount
     * @covers \Fastwf\Core\Router\Route
     * @covers \Fastwf\Core\Router\Parser\RouteParser
     * @covers \Fastwf\Core\Router\Parser\SpecificationRouteParser
     * @covers \Fastwf\Core\Router\RouterService
     * @covers \Fastwf\Core\Router\Components\RouterShutdown
     * @covers \Fastwf\Core\Router\Formatter\RouteGenerator
     * @covers \Fastwf\Core\Router\Formatter\PathFormatter
     * @covers \Fastwf\Core\Router\Formatter\PartialPathFormatter
     * @covers \Fastwf\Core\Router\Segment
     * @covers \Fastwf\Core\Utils\ArrayProxy
     * @covers \Fastwf\Core\Utils\ArrayUtil
     * @covers \Fastwf\Core\Utils\AsyncProperty
     * @covers \Fastwf\Core\Components\RequestHandler
     * @covers \Fastwf\Core\Engine\Run\Runner
     * @covers \Fastwf\Core\Http\Frame\Headers
     * @covers \Fastwf\Core\Http\Frame\HttpRequest
     * @covers \Fastwf\Core\Utils\Logging\DefaultLogger
     */
    public function testConfigurationAuto() {
        $_SERVER['DOCUMENT_ROOT'] = __DIR__;

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
     * @covers \Fastwf\Core\Engine\ServiceProvider
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
     * @covers \Fastwf\Core\Utils\Logging\DefaultLogger
     * @covers \Fastwf\Core\Router\RouterService
     * @covers \Fastwf\Core\Router\Components\RouterShutdown
     * @covers \Fastwf\Core\Router\Formatter\RouteGenerator
     * @covers \Fastwf\Core\Router\Formatter\PathFormatter
     * @covers \Fastwf\Core\Router\Formatter\PartialPathFormatter
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
     * @covers \Fastwf\Core\Engine\ServiceProvider
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
     * @covers \Fastwf\Core\Utils\Logging\DefaultLogger
     * @covers \Fastwf\Core\Router\RouterService
     * @covers \Fastwf\Core\Router\Components\RouterShutdown
     * @covers \Fastwf\Core\Router\Formatter\RouteGenerator
     * @covers \Fastwf\Core\Router\Formatter\PathFormatter
     * @covers \Fastwf\Core\Router\Formatter\PartialPathFormatter
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
     * @covers \Fastwf\Core\Engine\ServiceProvider
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
     * @covers \Fastwf\Core\Utils\Logging\DefaultLogger
     * @covers \Fastwf\Core\Router\RouterService
     * @covers \Fastwf\Core\Router\Components\RouterShutdown
     * @covers \Fastwf\Core\Router\Formatter\RouteGenerator
     * @covers \Fastwf\Core\Router\Formatter\PathFormatter
     * @covers \Fastwf\Core\Router\Formatter\PartialPathFormatter
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
     * @covers \Fastwf\Core\Engine\ServiceProvider
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
     * @covers \Fastwf\Core\Utils\Logging\DefaultLogger
     * @covers \Fastwf\Core\Router\RouterService
     * @covers \Fastwf\Core\Router\Components\RouterShutdown
     * @covers \Fastwf\Core\Router\Formatter\RouteGenerator
     * @covers \Fastwf\Core\Router\Formatter\PathFormatter
     * @covers \Fastwf\Core\Router\Formatter\PartialPathFormatter
     * @covers \Fastwf\Core\Engine\Service
     * @covers \Fastwf\Core\Session\Components\SessionShutdown
     * @covers \Fastwf\Core\Session\SessionManager
     * @covers \Fastwf\Core\Session\PhpSessionManager
     */
    public function testSuccessResponse() {
        $_SERVER['REQUEST_URI'] = '/success';
        $_SERVER['REQUEST_METHOD'] = 'GET';

        $this->expectOutputString('success');

        $engine = new SimpleEngine(self::TEST_CONF);
        $engine->run();

        // Test the content of the request
        $this->assertNotNull($engine->getRequest());
    }

    /**
     * Test 404 response.
     * 
     * @covers \Fastwf\Core\Engine\Engine
     * @covers \Fastwf\Core\Engine\ServiceProvider
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
     * @covers \Fastwf\Core\Utils\Logging\DefaultLogger
     * @covers \Fastwf\Core\Router\RouterService
     * @covers \Fastwf\Core\Router\Components\RouterShutdown
     * @covers \Fastwf\Core\Router\Formatter\RouteGenerator
     * @covers \Fastwf\Core\Router\Formatter\PathFormatter
     * @covers \Fastwf\Core\Router\Formatter\PartialPathFormatter
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
     * @covers \Fastwf\Core\Engine\ServiceProvider
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
     * @covers \Fastwf\Core\Engine\ServiceProvider
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
     * @covers \Fastwf\Core\Engine\ServiceProvider
     * @covers \Fastwf\Core\Engine\Service
     * @covers \Fastwf\Core\Utils\ArrayProxy
     * @covers \Fastwf\Core\Utils\AsyncProperty
     */
    public function testRegisterServiceImplementation() {
        $engine = new SimpleEngine(self::TEST_CONF);

        $service = new SimpleService($engine);

        $engine->registerService(ISimpleService::class, SimpleService::class);

        // Check the service is the registered service
        $this->assertTrue($engine->getService(ISimpleService::class) instanceof SimpleService);
    }

    /**
     * @covers \Fastwf\Core\Engine\Engine
     * @covers \Fastwf\Core\Engine\ServiceProvider
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
     * @covers \Fastwf\Core\Engine\ServiceProvider
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
     * @covers \Fastwf\Core\Utils\Logging\DefaultLogger
     * @covers \Fastwf\Core\Router\RouterService
     * @covers \Fastwf\Core\Router\Components\RouterShutdown
     * @covers \Fastwf\Core\Router\Formatter\RouteGenerator
     * @covers \Fastwf\Core\Router\Formatter\PathFormatter
     * @covers \Fastwf\Core\Router\Formatter\PartialPathFormatter
     */
    public function testExceptionHandler() {
        $_SERVER['REQUEST_URI'] = '/fail-old-style';
        $_SERVER['REQUEST_METHOD'] = 'GET';

        $this->expectOutputRegex('/^$/');

        $engine = new SimpleEngine(self::TEST_CONF);
        $engine->run();
    }

    /**
     * @covers \Fastwf\Core\Engine\Engine
     * @covers \Fastwf\Core\Engine\ServiceProvider
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
     * @covers \Fastwf\Core\Utils\Logging\DefaultLogger
     * @covers \Fastwf\Core\Router\RouterService
     * @covers \Fastwf\Core\Router\Components\RouterShutdown
     * @covers \Fastwf\Core\Router\Formatter\RouteGenerator
     * @covers \Fastwf\Core\Router\Formatter\PathFormatter
     * @covers \Fastwf\Core\Router\Formatter\PartialPathFormatter
     */
    public function testEngineSpecialVarsNoConfiguration() {
        // Set the document root in 'tests' folder
        $_SERVER['DOCUMENT_ROOT'] = __DIR__ . '/..';

        $engine = $this->getMockBuilder(SimpleEngine::class)
            ->setConstructorArgs([self::TEST_CONF])
            ->onlyMethods(['handleRequest', 'sendResponse'])
            ->getMock();
        $engine->run();

        $this->assertEquals(
            \realpath(__DIR__ . '/../..'),
            \realpath($engine->getRootPath())
        );

        $varPath = __DIR__ . '/../../var';
        if (!\file_exists($varPath)) {
            \mkdir($varPath);
        }
        $this->assertEquals(
            \realpath($varPath),
            \realpath($engine->getVarPath())
        );

        // The cache path will be created
        $cachePath = $engine->getCachePath('fastwf.core');
        $this->assertEquals(
            \realpath(__DIR__ . '/../../var/cache/fastwf.core'),
            \realpath($cachePath)
        );
    }

    /**
     * @covers \Fastwf\Core\Engine\Engine
     * @covers \Fastwf\Core\Engine\ServiceProvider
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
     * @covers \Fastwf\Core\Utils\Logging\DefaultLogger
     * @covers \Fastwf\Core\Router\RouterService
     * @covers \Fastwf\Core\Router\Components\RouterShutdown
     * @covers \Fastwf\Core\Router\Formatter\RouteGenerator
     * @covers \Fastwf\Core\Router\Formatter\PathFormatter
     * @covers \Fastwf\Core\Router\Formatter\PartialPathFormatter
     */
    public function testEngineSpecialVarsWithConfiguration() {
        $_ENV['SERVER_ROOTPATH'] = __DIR__ . '/.root';
        $_ENV['SERVER_VARPATH'] = __DIR__ . '/.var';
        $_ENV['SERVER_CACHEPATH'] = __DIR__ . '/.cache';
        // Create files to allows \realpath to return a valid path
        foreach (['SERVER_ROOTPATH', 'SERVER_VARPATH', 'SERVER_CACHEPATH'] as $key) {
            if (!\file_exists($_ENV[$key])) {
                \mkdir($_ENV[$key]);
            }
        }

        $engine = $this->getMockBuilder(SimpleEngine::class)
            ->setConstructorArgs([__DIR__ . '/../configuration.test.ini'])
            ->onlyMethods(['handleRequest', 'sendResponse'])
            ->getMock();
        $engine->run();

        $this->assertEquals(
            \realpath($_ENV['SERVER_ROOTPATH']),
            \realpath($engine->getRootPath())
        );
        $this->assertEquals(
            \realpath($_ENV['SERVER_VARPATH']),
            \realpath($engine->getVarPath())
        );

        // The cache path will be created
        $cachePath = $engine->getCachePath('fastwf.core');
        
        $this->assertEquals(
            \realpath($_ENV['SERVER_CACHEPATH'] . '/fastwf.core'),
            \realpath($cachePath)
        );
    }

    protected function tearDown(): void
    {
        // Remove temporary files
        $paths = [
            __DIR__ . '/../../var/cache/fastwf.core',
            __DIR__ . '/../../var/cache',
            __DIR__ . '/../../var',
            __DIR__ . '/.root',
            __DIR__ . '/.var',
            __DIR__ . '/.cache/fastwf.core',
            __DIR__ . '/.cache',
        ];

        foreach ($paths as $path) {
            if (\file_exists($path)) {
                \rmdir($path);
            }
        }
    }

}
