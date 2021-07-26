<?php

use PHPUnit\Framework\TestCase;

use Fastwf\Core\Exceptions\AttributeError;
use Fastwf\Core\Http\Frame\HttpStreamResponse;


define('OUTPUT_PATH', __DIR__ . '/output.txt');


class HttpStreamResponseTest extends TestCase {

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

}
