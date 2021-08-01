<?php

namespace Fastwf\Tests\Utils;

use PHPUnit\Framework\TestCase;

use Fastwf\Core\Utils\StringUtil;

class StringUtilTest extends TestCase {

    /**
     * @covers Fastwf\Core\Utils\StringUtil
     */
    public function testEndWith() {
        $this->assertTrue(StringUtil::endsWith("hello world", "world"));
        $this->assertFalse(StringUtil::endsWith("hello world", "hello"));
        $this->assertFalse(StringUtil::endsWith("orld", "world"));
    }

}
