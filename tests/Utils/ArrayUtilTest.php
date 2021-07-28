<?php

use PHPUnit\Framework\TestCase;

use Fastwf\Core\Utils\ArrayUtil;
use Fastwf\Core\Exceptions\KeyError;

class ArrayUtilTest extends TestCase {

    /**
     * @covers Fastwf\Core\Utils\ArrayUtil
     */
    public function testGet() {
        $this->assertEquals('foo', ArrayUtil::get(['bar' => 'foo'], 'bar'));
    }

    /**
     * @covers Fastwf\Core\Utils\ArrayUtil
     */
    public function testGetError() {
        $this->expectException(KeyError::class);

        ArrayUtil::get(['bar' => 'foo'], 'foo');
    }

    /**
     * @covers Fastwf\Core\Utils\ArrayUtil
     */
    public function testGetSafe() {
        $this->assertEquals('foo', ArrayUtil::getSafe(['bar' => 'foo'], 'bar'));
    }

    /**
     * @covers Fastwf\Core\Utils\ArrayUtil
     */
    public function testGetSafeDefault() {
        $this->assertEquals('bar', ArrayUtil::getSafe(['bar' => 'foo'], 'foo', 'bar'));
    }

}
