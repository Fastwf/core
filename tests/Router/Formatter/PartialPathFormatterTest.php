<?php

namespace Fastwf\Tests\Router\Formatter;

use Fastwf\Core\Router\Route;
use PHPUnit\Framework\TestCase;
use Fastwf\Api\Exceptions\KeyError;
use Fastwf\Core\Router\Exception\FormatException;
use Fastwf\Core\Router\Formatter\PartialPathFormatter;

class PartialPathFormatterTest extends TestCase
{

    public static function createBaseRouteWith($path, $name)
    {
        return new Route([
            'path' => $path,
            'methods' => ['GET'],
            'handler' => null,
            'name' => $name,
        ]);
    }
    
    /**
     * @covers Fastwf\Core\Router\BaseRoute
     * @covers Fastwf\Core\Router\Route
     * @covers Fastwf\Core\Router\Segment
     * @covers Fastwf\Core\Router\Parser\RouteParser
     * @covers Fastwf\Core\Router\Parser\SpecificationRouteParser
     * @covers Fastwf\Core\Router\Formatter\PartialPathFormatter
     */
    public function testFormatEmptySegment()
    {
        $formatter = new PartialPathFormatter(self::createBaseRouteWith('', null));

        $this->assertEquals('', $formatter->format([]));
    }
    
    /**
     * @covers Fastwf\Core\Router\BaseRoute
     * @covers Fastwf\Core\Router\Route
     * @covers Fastwf\Core\Router\Segment
     * @covers Fastwf\Core\Router\Parser\RouteParser
     * @covers Fastwf\Core\Router\Parser\SpecificationRouteParser
     * @covers Fastwf\Core\Router\Formatter\PartialPathFormatter
     */
    public function testFormatSegment()
    {
        $formatter = new PartialPathFormatter(self::createBaseRouteWith('application/user', null));

        $this->assertEquals('application/user', $formatter->format([]));
    }
    
    /**
     * @covers Fastwf\Core\Router\BaseRoute
     * @covers Fastwf\Core\Router\Route
     * @covers Fastwf\Core\Router\Segment
     * @covers Fastwf\Core\Router\Parser\RouteParser
     * @covers Fastwf\Core\Router\Parser\SpecificationRouteParser
     * @covers Fastwf\Core\Router\Formatter\PartialPathFormatter
     */
    public function testFormatParameterNoInterpolation()
    {
        $formatter = new PartialPathFormatter(self::createBaseRouteWith('application/{int:id}', null));

        $this->assertEquals('application/{int:id}', $formatter->format(null));
    }
    
    /**
     * @covers Fastwf\Core\Router\BaseRoute
     * @covers Fastwf\Core\Router\Route
     * @covers Fastwf\Core\Router\Segment
     * @covers Fastwf\Core\Router\Parser\RouteParser
     * @covers Fastwf\Core\Router\Parser\SpecificationRouteParser
     * @covers Fastwf\Core\Router\Formatter\PartialPathFormatter
     */
    public function testFormatParameter()
    {
        $formatter = new PartialPathFormatter(self::createBaseRouteWith('application/{name}', null));

        $this->assertEquals('application/foo', $formatter->format(['name' => 'foo']));
    }
    
    /**
     * @covers Fastwf\Core\Router\BaseRoute
     * @covers Fastwf\Core\Router\Route
     * @covers Fastwf\Core\Router\Segment
     * @covers Fastwf\Core\Router\Parser\RouteParser
     * @covers Fastwf\Core\Router\Parser\SpecificationRouteParser
     * @covers Fastwf\Core\Router\Formatter\PartialPathFormatter
     */
    public function testFormatParameterNoString()
    {
        $formatter = new PartialPathFormatter(self::createBaseRouteWith('application/{int:id}', null));

        $this->assertEquals('application/10', $formatter->format(['id' => 10]));
    }
    
    /**
     * @covers Fastwf\Core\Router\BaseRoute
     * @covers Fastwf\Core\Router\Route
     * @covers Fastwf\Core\Router\Segment
     * @covers Fastwf\Core\Router\Parser\RouteParser
     * @covers Fastwf\Core\Router\Parser\SpecificationRouteParser
     * @covers Fastwf\Core\Router\Formatter\PartialPathFormatter
     */
    public function testFormatParameterPath()
    {
        $formatter = new PartialPathFormatter(self::createBaseRouteWith('application/{path:file}', null));

        $this->assertEquals('application/foo/bar/file.html', $formatter->format(['file' => 'foo/bar/file.html']));
    }
    
    /**
     * @covers Fastwf\Core\Router\BaseRoute
     * @covers Fastwf\Core\Router\Route
     * @covers Fastwf\Core\Router\Segment
     * @covers Fastwf\Core\Router\Parser\RouteParser
     * @covers Fastwf\Core\Router\Parser\SpecificationRouteParser
     * @covers Fastwf\Core\Router\Formatter\PartialPathFormatter
     */
    public function testFormatParameterNamespace()
    {
        $formatter = new PartialPathFormatter(self::createBaseRouteWith('application/{int:id}', 'application'));

        $this->assertEquals('application/10', $formatter->format(['application/id' => 10]));
    }
    
    /**
     * @covers Fastwf\Core\Router\BaseRoute
     * @covers Fastwf\Core\Router\Route
     * @covers Fastwf\Core\Router\Segment
     * @covers Fastwf\Core\Router\Parser\RouteParser
     * @covers Fastwf\Core\Router\Parser\SpecificationRouteParser
     * @covers Fastwf\Core\Router\Formatter\PartialPathFormatter
     */
    public function testFormatWildcard()
    {
        $this->expectException(FormatException::class);

        $formatter = new PartialPathFormatter(self::createBaseRouteWith('application/**', null));
        $formatter->format([]);
    }
    /**
     * @covers Fastwf\Core\Router\BaseRoute
     * @covers Fastwf\Core\Router\Route
     * @covers Fastwf\Core\Router\Segment
     * @covers Fastwf\Core\Router\Parser\RouteParser
     * @covers Fastwf\Core\Router\Parser\SpecificationRouteParser
     * @covers Fastwf\Core\Router\Formatter\PartialPathFormatter
     */
    public function testFormatMissingParameter()
    {
        $this->expectException(KeyError::class);

        $formatter = new PartialPathFormatter(self::createBaseRouteWith('application/{id}', 'test'));
        $formatter->format([]);
    }

}