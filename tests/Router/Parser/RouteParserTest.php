<?php

use PHPUnit\Framework\TestCase;

use Fastwf\Core\Router\Parser\RouteParser;

class RouteParserTest extends TestCase
{

    /**
     * @covers Fastwf\Core\Router\Parser\RouteParser
     */
    public function testSegmentParsing()
    {
        $pathList = [
            "",
            "hello",
            "hello/",
            "hello/world",
            "hello/world/",
        ];

        $expected = [
            [],
            ['hello'],
            ['hello'],
            ['hello', 'world'],
            ['hello', 'world'],
        ];

        foreach ($pathList as $index => $path) {
            $parser = new RouteParser($path);

            $segments = [];
            foreach ($parser as $_index => $segment) {
                $segments[] = $segment;
            }

            $this->assertEquals($expected[$index], $segments);
        }
    }

    /**
     * @covers Fastwf\Core\Router\Parser\RouteParser
     */
    public function testGetNextPath() {
        $pathList = [
            "",
            "hello",
            "hello/",
            "hello/world",
            "hello/world/",
        ];

        $expected = [
            '',
            '',
            '',
            'world',
            'world/'
        ];

        foreach ($pathList as $index => $path) {
            $parser = new RouteParser($path);

            $parser->rewind();

            $this->assertEquals($expected[$index], $parser->getNextPath());
        }
    }

}
