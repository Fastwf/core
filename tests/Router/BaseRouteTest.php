<?php

namespace Fastwf\Tests\Router;

use PHPUnit\Framework\TestCase;
use Fastwf\Core\Router\BaseRoute;

class BaseRouteTest extends TestCase {
    
    /**
     * @covers Fastwf\Core\Router\BaseRoute::getParameterName
     */
    public function testGetParameterNameWithNullName() {
        $this->assertEquals(
            "test",
            BaseRoute::getParameterName(null, "test")
        );
    }

    /**
     * @covers Fastwf\Core\Router\BaseRoute::getParameterName
     */
    public function testGetParameterNameFullQualified() {
        $this->assertEquals(
            "routeName/test",
            BaseRoute::getParameterName("routeName", "test")
        );
    }

}