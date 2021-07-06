<?php

use PHPUnit\Framework\TestCase;

use Fastwf\Core\Router\Parser\SpecificationRouteParser;

class SpecificationRouteParserTest extends TestCase
{

    /**
     * @covers Fastwf\Core\Router\Parser\SpecificationRouteParser
     * @covers Fastwf\Core\Router\Parser\RouteParser
     * @covers Fastwf\Core\Router\Segment
     */
    public function testCurrent()
    {
        $route = 'user/{int:userId}';

        $path = ['user', '10'];
        foreach (new SpecificationRouteParser($route) as $index => $segment) {
            $this->assertTrue($segment->match($path[$index]));
        }
    }

}
