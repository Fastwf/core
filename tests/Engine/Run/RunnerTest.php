<?php

namespace Fastwf\Tests\Engine\Run;

use PHPUnit\Framework\TestCase;

use Fastwf\Core\Router\Mount;
use Fastwf\Core\Router\Route;

use Fastwf\Core\Engine\Run\Runner;

use Fastwf\Core\Http\Frame\HttpRequest;
use Fastwf\Tests\Components\SimpleGuard;
use Fastwf\Tests\Components\SimpleInPipe;
use Fastwf\Tests\Engine\Run\SimpleEngine;
use Fastwf\Tests\Components\SimpleOutPipe;
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

}
