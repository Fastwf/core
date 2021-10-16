<?php

namespace Fastwf\Core\Session\Components;

use PHPUnit\Framework\TestCase;
use Fastwf\Tests\Engine\SimpleEngine;
use Fastwf\Core\Session\SessionService;
use Fastwf\Core\Session\Components\SessionShutdown;

class SessionShutdownTest extends TestCase
{
    
    /**
     * @covers Fastwf\Core\Engine\Engine
     * @covers Fastwf\Core\Engine\ServiceProvider
     * @covers Fastwf\Core\Utils\ArrayProxy
     * @covers Fastwf\Core\Utils\AsyncProperty
     * @covers Fastwf\Core\Session\Components\SessionShutdown
     */
    public function testEnd()
    {
        $context = $this->getMockBuilder(SimpleEngine::class)
            ->setConstructorArgs([__DIR__ . '/../../configuration.ini'])
            ->onlyMethods([])
            ->getMock();
        
        $service = $this->createMock(SessionService::class);
        $service->expects($this->once())
            ->method('closeSession');

        $context->registerService(SessionService::class, $service);

        // Call end
        (new SessionShutdown())->end($context, null, null);
    }

}