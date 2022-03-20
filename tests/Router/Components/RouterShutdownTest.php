<?php

namespace Fastwf\Tests\Router\Components;

use PHPUnit\Framework\TestCase;
use Fastwf\Core\Router\RouterService;
use Fastwf\Tests\Engine\SimpleEngine;
use PHPUnit\Framework\MockObject\MockObject;
use Fastwf\Core\Router\Components\RouterShutdown;

class RouterShutdownTest extends TestCase
{

    /**
     * @covers Fastwf\Core\Engine\Engine
     * @covers Fastwf\Core\Engine\ServiceProvider
     * @covers Fastwf\Core\Router\Components\RouterShutdown
     */
    public function testEnd()
    {
        /** @var SimpleEngine */
        $context = $this->getMockBuilder(SimpleEngine::class)
            ->setConstructorArgs([__DIR__ . '/../../configuration.ini'])
            ->onlyMethods([])
            ->getMock();
        
        /** @var MockObject */
        $service = $this->createMock(RouterService::class);
        $service->expects($this->once())
            ->method('dumpRouteGenerator');

        $context->registerService(RouterService::class, $service);

        // Call end
        (new RouterShutdown())->end($context, null, null);
    }

}
