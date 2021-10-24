<?php

namespace Fastwf\Tests\Engine\Run;

use Fastwf\Core\Router\Mount;

use Fastwf\Core\Router\Route;
use PHPUnit\Framework\TestCase;

use Fastwf\Core\Engine\Run\Runner;

use Fastwf\Core\Exceptions\ValueError;
use Fastwf\Core\Http\Frame\HttpRequest;
use Fastwf\Tests\Components\SimpleGuard;
use Fastwf\Tests\Components\SimpleInPipe;
use Fastwf\Tests\Engine\Run\SimpleEngine;
use Fastwf\Tests\Components\SimpleOutPipe;
use Fastwf\Tests\Engine\ErrorRequestHandler;
use Fastwf\Tests\Components\NullRequestHandler;
use Fastwf\Tests\Components\SimpleInInterceptor;
use Fastwf\Tests\Components\SimpleOutInterceptor;

class RunnerTest extends TestCase {

    /**
     * This test verify:
     *  - no errors
     *  - all components are called
     *  - the response is the expected response
     *
     * @covers Fastwf\Core\Engine\Run\Runner
     * @covers Fastwf\Core\Http\Frame\HttpRequest
     * @covers Fastwf\Core\Router\BaseRoute
     * @covers Fastwf\Core\Router\Mount
     * @covers Fastwf\Core\Router\Route
     * @covers Fastwf\Core\Utils\ArrayProxy
     * @covers Fastwf\Core\Utils\ArrayUtil
     * @covers Fastwf\Core\Utils\AsyncProperty
     * @covers Fastwf\Core\Components\RequestHandler
     * @covers Fastwf\Core\Http\Frame\Headers
     */
    public function testRun() {
        $engine = new SimpleEngine();
        $match = [
            'matchers' => [
                new Mount([
                    'path' => 'group',
                    'routes' => [],
                    'inputInterceptors' => [new SimpleInInterceptor()],
                    'guards' => [new SimpleGuard()],
                    'inputPipes' => [new SimpleInPipe()],
                    'outputPipes' => [new SimpleOutPipe()],
                    'outputInterceptors' => [new SimpleOutInterceptor()],
                ]),
                new Route([
                    'path' => 'user',
                    'methods' => ['GET'],
                    'inputInterceptors' => [new SimpleInInterceptor()],
                    'guards' => [new SimpleGuard()],
                    'inputPipes' => [new SimpleInPipe()],
                    'outputPipes' => [new SimpleOutPipe()],
                    'outputInterceptors' => [new SimpleOutInterceptor()],
                    'handler' => new NullRequestHandler($engine),
                ]),
            ],
            'parameters' => []
        ];

        $this->assertNull(
            (new Runner($engine))->run(
                new HttpRequest('/group/user', 'GET'),
                $match
            ),
        );
    }

    /**
     * This test verify:
     *  - response created by using class name.
     *
     * @covers Fastwf\Core\Engine\Run\Runner
     * @covers Fastwf\Core\Http\Frame\HttpRequest
     * @covers Fastwf\Core\Router\BaseRoute
     * @covers Fastwf\Core\Router\Mount
     * @covers Fastwf\Core\Router\Route
     * @covers Fastwf\Core\Utils\ArrayProxy
     * @covers Fastwf\Core\Utils\ArrayUtil
     * @covers Fastwf\Core\Utils\AsyncProperty
     * @covers Fastwf\Core\Components\RequestHandler
     * @covers Fastwf\Core\Http\Frame\Headers
     */
    public function testRunClassName() {
        $engine = new SimpleEngine();
        $match = [
            'matchers' => [
                new Route([
                    'path' => 'user',
                    'methods' => ['GET'],
                    'handler' => NullRequestHandler::class,
                ]),
            ],
            'parameters' => []
        ];

        $this->assertNull(
            (new Runner($engine))->run(
                new HttpRequest('/user', 'GET'),
                $match
            ),
        );
    }

    /**
     * This test verify:
     *  - Bad handler.
     *
     * @covers Fastwf\Core\Engine\Run\Runner
     * @covers Fastwf\Core\Http\Frame\HttpRequest
     * @covers Fastwf\Core\Router\BaseRoute
     * @covers Fastwf\Core\Router\Mount
     * @covers Fastwf\Core\Router\Route
     * @covers Fastwf\Core\Utils\ArrayProxy
     * @covers Fastwf\Core\Utils\ArrayUtil
     * @covers Fastwf\Core\Utils\AsyncProperty
     * @covers Fastwf\Core\Components\RequestHandler
     * @covers Fastwf\Core\Http\Frame\Headers
     * @covers Fastwf\Core\Exceptions\ValueError
     * @covers Fastwf\Core\Http\Frame\HttpResponse
     * @covers Fastwf\Core\Http\Frame\HttpStreamResponse
     */
    public function testRunValueError() {
        $engine = new SimpleEngine();
        $match = [
            'matchers' => [
                new Route([
                    'path' => 'user',
                    'methods' => ['GET'],
                    'handler' => "Bad value",
                ]),
            ],
            'parameters' => []
        ];

        (new Runner($engine))->run(
            new HttpRequest('/user', 'GET'),
            $match
        );

        $this->assertEquals(
            500,
            (new Runner($engine))->run(
                new HttpRequest('/group/user', 'GET'),
                $match
            )->status,
        );
    }

    /**
     * This test verify:
     *  - run and catch an error
     *
     * @covers Fastwf\Core\Engine\Run\Runner
     * @covers Fastwf\Core\Http\Frame\HttpRequest
     * @covers Fastwf\Core\Router\BaseRoute
     * @covers Fastwf\Core\Router\Mount
     * @covers Fastwf\Core\Router\Route
     * @covers Fastwf\Core\Utils\ArrayProxy
     * @covers Fastwf\Core\Utils\ArrayUtil
     * @covers Fastwf\Core\Utils\AsyncProperty
     * @covers Fastwf\Core\Components\RequestHandler
     * @covers Fastwf\Core\Http\Frame\Headers
     * @covers Fastwf\Core\Http\Frame\HttpResponse
     * @covers Fastwf\Core\Http\Frame\HttpStreamResponse
     */
    public function testRunError() {
        $engine = new SimpleEngine();
        $match = [
            'matchers' => [
                new Route([
                    'path' => 'user',
                    'methods' => ['GET'],
                    'handler' => new ErrorRequestHandler($engine),
                ]),
            ],
            'parameters' => []
        ];

        $this->assertEquals(
            500,
            (new Runner($engine))->run(
                new HttpRequest('/group/user', 'GET'),
                $match
            )->status,
        );
    }

}
