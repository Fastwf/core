<?php

namespace Fastwf\Tests\Http\Frame;

use PHPUnit\Framework\TestCase;

use Fastwf\Core\Exceptions\IOException;
use Fastwf\Core\Exceptions\AttributeError;
use Fastwf\Core\Http\Frame\HttpStreamResponse;


define('OUTPUT_PATH', __DIR__ . '/output.txt');


class HttpStreamResponseTest extends TestCase {

    const OUT_PATH = __DIR__ . '/out.local.txt';

    /**
     * @covers Fastwf\Core\Utils\ArrayProxy
     * @covers Fastwf\Core\Http\Frame\HttpStreamResponse
     */
    public function testAttributes() {
        $res = new HttpStreamResponse();

        $this->assertEquals(200, $res->status);
        $this->assertNotNull($res->headers);
    }

    /**
     * @covers Fastwf\Core\Utils\ArrayProxy
     * @covers Fastwf\Core\Http\Frame\HttpStreamResponse
     * @covers Fastwf\Core\Exceptions\AttributeError
     */
    public function testAttributeError() {
        $this->expectException(AttributeError::class);

        $res = new HttpStreamResponse();
        $res->notFound;
    }

    /**
     * @covers Fastwf\Core\Utils\ArrayProxy
     * @covers Fastwf\Core\Http\Frame\HttpStreamResponse
     * @covers Fastwf\Core\Exceptions\AttributeError
     */
    public function testSend() {
        $output = new FileHttpOutput(self::OUT_PATH);

        function gen() {
            yield 'Hello ';
            yield 'world';
            yield '!!';
        }

        $res = new HttpStreamResponse(200, ['Content-Type' => 'text/plain'], gen());
        $res->send($output);

        \fflush($output->resource);
        \fclose($output->resource);
        
        $this->assertEquals(200, $output->status);
        $this->assertEquals(['Content-Type: text/plain'], $output->headers);
        $this->assertEquals('Hello world!!', \file_get_contents(self::OUT_PATH));
    }

    /**
     * @covers Fastwf\Core\Utils\ArrayProxy
     * @covers Fastwf\Core\Http\Frame\HttpStreamResponse
     * @covers Fastwf\Core\Exceptions\AttributeError
     */
    public function testSendIoException() {
        error_reporting(0);

        $this->expectException(IOException::class);

        $output = new FileHttpOutput(self::OUT_PATH);
        
        // Change write rights
        \fclose($output->getResponseStream());
        $output->resource = \fopen(self::OUT_PATH, 'r');

        function genFail() {
            yield 'no write';
        }

        $res = new HttpStreamResponse(200, ['Content-Type' => 'text/plain'], gen());
        $res->send($output);
    }

    protected function tearDown(): void {
        if (\file_exists(self::OUT_PATH)) {
            \unlink(self::OUT_PATH);
        }

        error_reporting(E_ALL);
    }

}
