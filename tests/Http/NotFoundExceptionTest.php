<?php

namespace Fastwf\Tests\Http;

use PHPUnit\Framework\TestCase;
use Fastwf\Core\Http\NotFoundException;

class NotFoundExceptionTest extends TestCase {

    /**
     * @covers Fastwf\Core\Http\HttpException
     * @covers Fastwf\Core\Http\NotFoundException
     * @covers Fastwf\Core\Http\Frame\HttpStreamResponse
     * @covers Fastwf\Core\Http\Frame\HttpResponse
     */
    public function testConstructResponse() {
        $exception = new NotFoundException("Not found");

        $this->assertEquals(404, $exception->getResponse()->status);
    }

}
