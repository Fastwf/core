<?php

namespace Fastwf\Tests\Utils;

use PHPUnit\Framework\TestCase;

use Fastwf\Core\Utils\StringUtil;

class StringUtilTest extends TestCase {

    /**
     * @covers Fastwf\Core\Utils\StringUtil
     */
    public function testStartsWith() {
        $this->assertTrue(StringUtil::startsWith("hello world", "hello"));
        $this->assertFalse(StringUtil::startsWith("hello world", "world"));
        $this->assertFalse(StringUtil::startsWith("orld", "world"));
        $this->assertTrue(StringUtil::startsWith("/public", "/"));
        $this->assertFalse(StringUtil::startsWith("public", "/"));
        $this->assertTrue(StringUtil::startsWith("public", ""));
        $this->assertTrue(StringUtil::startsWith("", ""));
    }

    /**
     * @covers Fastwf\Core\Utils\StringUtil
     */
    public function testEndsWith() {
        $this->assertTrue(StringUtil::endsWith("hello world", "world"));
        $this->assertFalse(StringUtil::endsWith("hello world", "hello"));
        $this->assertFalse(StringUtil::endsWith("orld", "world"));
        $this->assertTrue(StringUtil::endsWith("public/", "/"));
        $this->assertFalse(StringUtil::endsWith("public", "/"));
    }

}
