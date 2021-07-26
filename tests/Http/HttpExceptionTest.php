<?php

use PHPUnit\Framework\TestCase;
use Fastwf\Core\Http\HttpException;
use Fastwf\Core\Http\Frame\HttpResponse;

class HttpExceptionTest extends TestCase {

    /**
     * @covers Fastwf\Core\Http\HttpException
     * @covers Fastwf\Core\Http\Frame\HttpStreamResponse
     * @covers Fastwf\Core\Http\Frame\HttpResponse
     * @covers Fastwf\Core\Utils\ArrayProxy
     */
    public function testConstructResponse() {
        $res = new HttpResponse(404, [], "Not found");

        $exception = new HttpException("Not found", ['response' => $res]);

        $this->assertEquals($res, $exception->getResponse());
    }

    /**
     * @covers Fastwf\Core\Http\HttpException
     * @covers Fastwf\Core\Http\Frame\HttpStreamResponse
     * @covers Fastwf\Core\Http\Frame\HttpResponse
     * @covers Fastwf\Core\Utils\ArrayProxy
     */
    public function testConstructArgs() {
        $exception = new HttpException("Not found", ['status' => 400, 'errorMessage' => 'Bad request']);

        $res = $exception->getResponse();

        $this->assertEquals(400, $res->status);
    }

}
