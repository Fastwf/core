<?php

namespace Fastwf\Tests\Http\Frame;

use PHPUnit\Framework\TestCase;

use Fastwf\Core\Http\Frame\HttpResponse;

class HttpResponseTest extends TestCase {

    const OUT_PATH = __DIR__ . '/out.local.txt';

    /**
     * @covers Fastwf\Core\Http\Frame\HttpStreamResponse
     * @covers Fastwf\Core\Http\Frame\HttpResponse
     * @covers Fastwf\Core\Exceptions\AttributeError
     */
    public function testSend() {
        $output = new FileHttpOutput(self::OUT_PATH);

        $sequence = 'Hello world!!';
        $res = new HttpResponse(200, [], $sequence);
        $res->send($output);

        $this->assertEquals($sequence, \file_get_contents(self::OUT_PATH));
    }

    protected function tearDown(): void {
        if (\file_exists(self::OUT_PATH)) {
            \unlink(self::OUT_PATH);
        }
    }

}
