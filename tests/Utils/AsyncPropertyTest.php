<?php

use PHPUnit\Framework\TestCase;

use Fastwf\Core\Utils\AsyncProperty;

class AsyncPropertyTest extends TestCase {

    /**
     * @covers Fastwf\Core\Utils\AsyncProperty
     */
    public function testValue() {
        $prop = new AsyncProperty("test");

        $this->assertEquals("test", $prop->get());
    }

    /**
     * @covers Fastwf\Core\Utils\AsyncProperty
     */
    public function testValueNull() {
        $prop = new AsyncProperty(null);

        $this->assertNull($prop->get());
    }

    /**
     * @covers Fastwf\Core\Utils\AsyncProperty
     */
    public function testValueInvoke() {
        $getter = new AsyncProperty("invoke");

        $this->assertEquals("invoke", $getter());
    }

    /**
     * @covers Fastwf\Core\Utils\AsyncProperty
     */
    public function testValueFactory() {
        $prop = new AsyncProperty(fn() => "async");

        $this->assertEquals("async", $prop->get());
    }

}
