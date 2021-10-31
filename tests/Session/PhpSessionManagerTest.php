<?php

namespace Fastwf\Tests\Session;

use PHPUnit\Framework\TestCase;
use Fastwf\Tests\Engine\SimpleEngine;
use Fastwf\Core\Session\PhpSessionManager;

class PhpSessionManagerTest extends TestCase
{

    private $engine;

    protected function setup(): void
    {
        $this->engine = $this->getMockBuilder(SimpleEngine::class)
            ->setConstructorArgs([__DIR__ . '/../configuration.ini'])
            ->onlyMethods(['handleRequest', 'sendResponse'])
            ->getMock();
        $this->engine->run();
    }

    /**
     * @covers Fastwf\Core\Components\RequestHandler
     * @covers Fastwf\Core\Configuration
     * @covers Fastwf\Core\Engine\Engine
     * @covers Fastwf\Core\Engine\Run\Runner
     * @covers Fastwf\Core\Engine\Service
     * @covers Fastwf\Core\Engine\ServiceProvider
     * @covers Fastwf\Core\Http\Frame\Headers
     * @covers Fastwf\Core\Http\Frame\HttpRequest
     * @covers Fastwf\Core\Http\Frame\HttpResponse
     * @covers Fastwf\Core\Http\Frame\HttpStreamResponse
     * @covers Fastwf\Core\Http\HttpException
     * @covers Fastwf\Core\Http\NotFoundException
     * @covers Fastwf\Core\Router\BaseRoute
     * @covers Fastwf\Core\Router\Components\RouterShutdown
     * @covers Fastwf\Core\Router\Formatter\PartialPathFormatter
     * @covers Fastwf\Core\Router\Formatter\RouteGenerator
     * @covers Fastwf\Core\Router\Mount
     * @covers Fastwf\Core\Router\Parser\RouteParser
     * @covers Fastwf\Core\Router\Parser\SpecificationRouteParser
     * @covers Fastwf\Core\Router\Route
     * @covers Fastwf\Core\Router\RouterService
     * @covers Fastwf\Core\Router\Segment
     * @covers Fastwf\Core\Session\PhpSessionManager
     * @covers Fastwf\Core\Session\Session
     * @covers Fastwf\Core\Session\SessionManager
     * @covers Fastwf\Core\Utils\ArrayProxy
     * @covers Fastwf\Core\Utils\ArrayUtil
     * @covers Fastwf\Core\Utils\AsyncProperty
     * @covers Fastwf\Core\Utils\Logging\DefaultLogger
     */
    public function testNonLockedSession()
    {
        $service = $this->engine->getService(PhpSessionManager::class);

        $this->assertNotNull($service->getSession());
    }

    /**
     * @covers Fastwf\Core\Components\RequestHandler
     * @covers Fastwf\Core\Configuration
     * @covers Fastwf\Core\Engine\Engine
     * @covers Fastwf\Core\Engine\Run\Runner
     * @covers Fastwf\Core\Engine\Service
     * @covers Fastwf\Core\Engine\ServiceProvider
     * @covers Fastwf\Core\Http\Frame\Headers
     * @covers Fastwf\Core\Http\Frame\HttpRequest
     * @covers Fastwf\Core\Http\Frame\HttpResponse
     * @covers Fastwf\Core\Http\Frame\HttpStreamResponse
     * @covers Fastwf\Core\Http\HttpException
     * @covers Fastwf\Core\Http\NotFoundException
     * @covers Fastwf\Core\Router\BaseRoute
     * @covers Fastwf\Core\Router\Components\RouterShutdown
     * @covers Fastwf\Core\Router\Formatter\PartialPathFormatter
     * @covers Fastwf\Core\Router\Formatter\RouteGenerator
     * @covers Fastwf\Core\Router\Mount
     * @covers Fastwf\Core\Router\Parser\RouteParser
     * @covers Fastwf\Core\Router\Parser\SpecificationRouteParser
     * @covers Fastwf\Core\Router\Route
     * @covers Fastwf\Core\Router\RouterService
     * @covers Fastwf\Core\Router\Segment
     * @covers Fastwf\Core\Session\PhpSessionManager
     * @covers Fastwf\Core\Session\Session
     * @covers Fastwf\Core\Session\SessionManager
     * @covers Fastwf\Core\Utils\ArrayProxy
     * @covers Fastwf\Core\Utils\ArrayUtil
     * @covers Fastwf\Core\Utils\AsyncProperty
     * @covers Fastwf\Core\Utils\Logging\DefaultLogger
     */
    public function testNonLockedSessionRefreshedOrNot()
    {
        $service = $this->engine->getService(PhpSessionManager::class);

        $session = $service->getSession();

        // Verify that the same object is returned when query the session multiple times
        $this->assertEquals($session, $service->getSession(true));
        $this->assertEquals($session, $service->getSession());
    }

    /**
     * @covers Fastwf\Core\Components\RequestHandler
     * @covers Fastwf\Core\Configuration
     * @covers Fastwf\Core\Engine\Engine
     * @covers Fastwf\Core\Engine\Run\Runner
     * @covers Fastwf\Core\Engine\Service
     * @covers Fastwf\Core\Engine\ServiceProvider
     * @covers Fastwf\Core\Http\Frame\Headers
     * @covers Fastwf\Core\Http\Frame\HttpRequest
     * @covers Fastwf\Core\Http\Frame\HttpResponse
     * @covers Fastwf\Core\Http\Frame\HttpStreamResponse
     * @covers Fastwf\Core\Http\HttpException
     * @covers Fastwf\Core\Http\NotFoundException
     * @covers Fastwf\Core\Router\BaseRoute
     * @covers Fastwf\Core\Router\Components\RouterShutdown
     * @covers Fastwf\Core\Router\Formatter\PartialPathFormatter
     * @covers Fastwf\Core\Router\Formatter\RouteGenerator
     * @covers Fastwf\Core\Router\Mount
     * @covers Fastwf\Core\Router\Parser\RouteParser
     * @covers Fastwf\Core\Router\Parser\SpecificationRouteParser
     * @covers Fastwf\Core\Router\Route
     * @covers Fastwf\Core\Router\RouterService
     * @covers Fastwf\Core\Router\Segment
     * @covers Fastwf\Core\Session\PhpSessionManager
     * @covers Fastwf\Core\Session\Session
     * @covers Fastwf\Core\Session\SessionManager
     * @covers Fastwf\Core\Utils\ArrayProxy
     * @covers Fastwf\Core\Utils\ArrayUtil
     * @covers Fastwf\Core\Utils\AsyncProperty
     * @covers Fastwf\Core\Utils\Logging\DefaultLogger
     */
    public function testLockedSession()
    {
        $service = $this->engine->getService(PhpSessionManager::class);

        $session = $service->getLockedSession();
        // Close the session to release the lock
        $service->closeSession();

        $this->assertNotNull($session);
    }

    /**
     * @covers Fastwf\Core\Components\RequestHandler
     * @covers Fastwf\Core\Configuration
     * @covers Fastwf\Core\Engine\Engine
     * @covers Fastwf\Core\Engine\Run\Runner
     * @covers Fastwf\Core\Engine\Service
     * @covers Fastwf\Core\Engine\ServiceProvider
     * @covers Fastwf\Core\Http\Frame\Headers
     * @covers Fastwf\Core\Http\Frame\HttpRequest
     * @covers Fastwf\Core\Http\Frame\HttpResponse
     * @covers Fastwf\Core\Http\Frame\HttpStreamResponse
     * @covers Fastwf\Core\Http\HttpException
     * @covers Fastwf\Core\Http\NotFoundException
     * @covers Fastwf\Core\Router\BaseRoute
     * @covers Fastwf\Core\Router\Components\RouterShutdown
     * @covers Fastwf\Core\Router\Formatter\PartialPathFormatter
     * @covers Fastwf\Core\Router\Formatter\RouteGenerator
     * @covers Fastwf\Core\Router\Mount
     * @covers Fastwf\Core\Router\Parser\RouteParser
     * @covers Fastwf\Core\Router\Parser\SpecificationRouteParser
     * @covers Fastwf\Core\Router\Route
     * @covers Fastwf\Core\Router\RouterService
     * @covers Fastwf\Core\Router\Segment
     * @covers Fastwf\Core\Session\PhpSessionManager
     * @covers Fastwf\Core\Session\Session
     * @covers Fastwf\Core\Session\SessionManager
     * @covers Fastwf\Core\Utils\ArrayProxy
     * @covers Fastwf\Core\Utils\ArrayUtil
     * @covers Fastwf\Core\Utils\AsyncProperty
     * @covers Fastwf\Core\Utils\Logging\DefaultLogger
     */
    public function testGetSessionId()
    {
        $service = $this->engine->getService(PhpSessionManager::class);

        $this->assertNotEquals('', $service->getSessionId());
    }

    /**
     * @covers Fastwf\Core\Components\RequestHandler
     * @covers Fastwf\Core\Configuration
     * @covers Fastwf\Core\Engine\Engine
     * @covers Fastwf\Core\Engine\Run\Runner
     * @covers Fastwf\Core\Engine\Service
     * @covers Fastwf\Core\Engine\ServiceProvider
     * @covers Fastwf\Core\Http\Frame\Headers
     * @covers Fastwf\Core\Http\Frame\HttpRequest
     * @covers Fastwf\Core\Http\Frame\HttpResponse
     * @covers Fastwf\Core\Http\Frame\HttpStreamResponse
     * @covers Fastwf\Core\Http\HttpException
     * @covers Fastwf\Core\Http\NotFoundException
     * @covers Fastwf\Core\Router\BaseRoute
     * @covers Fastwf\Core\Router\Components\RouterShutdown
     * @covers Fastwf\Core\Router\Formatter\PartialPathFormatter
     * @covers Fastwf\Core\Router\Formatter\RouteGenerator
     * @covers Fastwf\Core\Router\Mount
     * @covers Fastwf\Core\Router\Parser\RouteParser
     * @covers Fastwf\Core\Router\Parser\SpecificationRouteParser
     * @covers Fastwf\Core\Router\Route
     * @covers Fastwf\Core\Router\RouterService
     * @covers Fastwf\Core\Router\Segment
     * @covers Fastwf\Core\Session\PhpSessionManager
     * @covers Fastwf\Core\Session\Session
     * @covers Fastwf\Core\Session\SessionManager
     * @covers Fastwf\Core\Utils\ArrayProxy
     * @covers Fastwf\Core\Utils\ArrayUtil
     * @covers Fastwf\Core\Utils\AsyncProperty
     * @covers Fastwf\Core\Utils\Logging\DefaultLogger
     */
    public function testCloseSession()
    {
        $service = $this->engine->getService(PhpSessionManager::class);

        $session = $service->getSession();

        $session->set('user_id', 'test');

        // Modification will be detected and the $_SESSION recreated
        $service->closeSession();

        // Verify that $_SESSION 'user_id' is set
        $this->assertTrue(isset($_SESSION['user_id']));
        $this->assertEquals('test', $_SESSION['user_id']);
    }

}
