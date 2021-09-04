<?php

namespace Fastwf\Tests\Router\Parser;

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

    /**
     * @covers Fastwf\Core\Router\Parser\SpecificationRouteParser
     * @covers Fastwf\Core\Router\Parser\RouteParser
     * @covers Fastwf\Core\Router\Segment
     */
    public function testSegmentPath()
    {
        $parser = new SpecificationRouteParser('static/{path:filePath}');
        $parser->rewind();

        $segments = [];
        foreach (['static', 'css', 'style.css'] as $value) {
            $segment = $parser->current();

            if ($segment->match($value)) {
                if (!\in_array($segment, $segments)) {
                    $segments[] = $segment;
                }

                $parser->next();
            } else {
                break;
            }
        }

        $this->assertEquals("css/style.css", \end($segments)->getParameter());
    }

}
