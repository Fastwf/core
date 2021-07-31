<?php

namespace Fastwf\Tests\Http;

use PHPUnit\Framework\TestCase;
use Fastwf\Core\Http\HttpException;
use Fastwf\Core\Http\NotFoundException;
use Fastwf\Core\Http\Frame\HttpResponse;

class NotFoundExceptionTest extends TestCase {

    /**
     * @covers Fastwf\Core\Http\HttpException
     * @covers Fastwf\Core\Http\NotFoundException
     * @covers Fastwf\Core\Http\Frame\HttpStreamResponse
     * @covers Fastwf\Core\Http\Frame\HttpResponse
     * @covers Fastwf\Core\Utils\ArrayProxy
     */
    public function testConstructResponse() {
        $exception = new NotFoundException("Not found");

        $this->assertEquals(404, $exception->getResponse()->status);
    }

}
