<?php

namespace Fastwf\Tests\Router\Formatter;

use PHPUnit\Framework\TestCase;
use Fastwf\Core\Router\Formatter\PathFormatter;
use Fastwf\Core\Router\Formatter\PartialPathFormatter;
use Fastwf\Tests\Router\Formatter\PartialPathFormatterTest;

class PathFormatterTest extends TestCase
{

    /**
     * @covers Fastwf\Core\Router\BaseRoute
     * @covers Fastwf\Core\Router\Route
     * @covers Fastwf\Core\Router\Segment
     * @covers Fastwf\Core\Router\Parser\RouteParser
     * @covers Fastwf\Core\Router\Parser\SpecificationRouteParser
     * @covers Fastwf\Core\Router\Formatter\PartialPathFormatter
     * @covers Fastwf\Core\Router\Formatter\PathFormatter
     */
    public function testFormat()
    {
        $formatter = new PathFormatter([
            new PartialPathFormatter(PartialPathFormatterTest::createBaseRouteWith('baseUrl', 'root')),
            new PartialPathFormatter(PartialPathFormatterTest::createBaseRouteWith('admin', 'admin')),
            new PartialPathFormatter(PartialPathFormatterTest::createBaseRouteWith('user/{id}', 'getUserId')),
        ]);

        $this->assertEquals(
            '/baseUrl/admin/user/foo%5Bbar%5D',
            $formatter->format([
                'getUserId/id' => 'foo[bar]'
            ])
        );
    }

    /**
     * @covers Fastwf\Core\Router\BaseRoute
     * @covers Fastwf\Core\Router\Route
     * @covers Fastwf\Core\Router\Segment
     * @covers Fastwf\Core\Router\Parser\RouteParser
     * @covers Fastwf\Core\Router\Parser\SpecificationRouteParser
     * @covers Fastwf\Core\Router\Formatter\PartialPathFormatter
     * @covers Fastwf\Core\Router\Formatter\PathFormatter
     */
    public function testFormatWithEmptySegment()
    {
        $formatter = new PathFormatter([
            new PartialPathFormatter(PartialPathFormatterTest::createBaseRouteWith('', 'root')),
            new PartialPathFormatter(PartialPathFormatterTest::createBaseRouteWith('admin', 'admin')),
            new PartialPathFormatter(PartialPathFormatterTest::createBaseRouteWith('', 'admin-user-base')),
            new PartialPathFormatter(PartialPathFormatterTest::createBaseRouteWith('user/{id}', 'getUserId')),
        ]);

        $this->assertEquals(
            '/admin/user/10',
            $formatter->format([
                'getUserId/id' => 10
            ])
        );
    }

}
