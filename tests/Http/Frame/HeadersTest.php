<?php

namespace Fastwf\Tests\Http\Frame;

use PHPUnit\Framework\TestCase;

use Fastwf\Core\Http\Frame\Headers;

class HeadersTest extends TestCase {

    /**
     * @covers Fastwf\Core\Http\Frame\Headers
     * @covers Fastwf\Core\Utils\ArrayProxy
     */
    function testUpdate() {
        $headers = new Headers();

        $headers->update([
            'Content-Type' => 'application/json',
            'Host' => 'localhost'
        ]);
        $this->assertEquals('localhost', $headers->get('host'));
    }

    /**
     * @covers Fastwf\Core\Http\Frame\Headers
     * @covers Fastwf\Core\Utils\ArrayProxy
     */
    function testHas() {
        $headers = new Headers(['Content-Type' => 'application/json']);

        $this->assertTrue($headers->has('content-Type'));
    }

    /**
     * @covers Fastwf\Core\Http\Frame\Headers
     * @covers Fastwf\Core\Utils\ArrayProxy
     */
    function testRemove() {
        $headers = new Headers(['Content-Type' => 'application/json']);

        $headers->remove('content-type');

        $this->assertFalse($headers->has('content-Type'));
    }

    /**
     * @covers Fastwf\Core\Http\Frame\Headers
     * @covers Fastwf\Core\Utils\ArrayProxy
     */
    function testSet() {
        $headers = new Headers();

        $headers->set('Host', 'localhost');

        $this->assertEquals('localhost', $headers->get('host'));
    }

}
