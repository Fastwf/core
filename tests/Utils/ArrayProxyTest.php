<?php

use PHPUnit\Framework\TestCase;

use Fastwf\Core\Utils\ArrayProxy;

class ArrayProxyTest extends TestCase {

    private $data;

    protected function setUp(): void {
        $this->data = [
            'int' => '123',
            'float' => '3.14',
            'bool' => 'true',
            'any' => 'test',
            'array' => ['one', 'two', 'three'],
            'array_int' => [1, 2, 3],
            'array_bool' => [true, false, 'true'],
        ];
    }

    /**
     * @covers Fastwf\Core\Utils\ArrayProxy
     */
    public function testKeys() {
        $proxy = new ArrayProxy($this->data);

        $this->assertEqualsCanonicalizing(
            ['int', 'float', 'bool', 'any', 'array', 'array_int', 'array_bool'],
            $proxy->keys()
        );
    }

    /**
     * @covers Fastwf\Core\Utils\ArrayProxy
     */
    public function testUpdate() {
        $proxy = new ArrayProxy(['noupdate' => true, 'update' => true]);

        $proxy->update(['update' => false, 'inserted' => false]);

        $this->assertTrue($proxy->get('noupdate'));
        $this->assertFalse($proxy->get('update'));
        $this->assertFalse($proxy->get('inserted'));
    }

    /**
     * @covers Fastwf\Core\Utils\ArrayProxy
     */
    public function testHas() {
        $proxy = new ArrayProxy($this->data);

        $this->assertTrue($proxy->has('int'));
        $this->assertFalse($proxy->has('notfound'));
    }

    /**
     * @covers Fastwf\Core\Utils\ArrayProxy
     */
    public function testSet() {
        $proxy = new ArrayProxy($this->data);
        $proxy->set('int', 1);
        $proxy->set('notset', 'set');

        $this->assertEquals(1, $proxy->get('int'));
        $this->assertEquals('set', $proxy->get('notset'));
    }

    /**
     * @covers Fastwf\Core\Utils\ArrayProxy
     */
    public function testGet() {
        $proxy = new ArrayProxy($this->data);

        $this->assertEquals('test', $proxy->get('any'));
        $this->assertEquals(['one', 'two', 'three'], $proxy->get('array', null, true));
    }

    /**
     * @covers Fastwf\Core\Utils\ArrayProxy
     * @covers Fastwf\Core\Utils\StringUtil
     */
    public function testGetSuperGlobal() {
        $proxy = new ArrayProxy(['alone' => 'alone', 'array[]' => [1, 2, 3]], true);

        $this->assertEquals([1, 2, 3], $proxy->get('array', null, true));
        $this->assertEquals('alone', $proxy->get('alone'));
    }

    /**
     * @covers Fastwf\Core\Utils\ArrayProxy
     */
    public function testGetInt() {
        $proxy = new ArrayProxy($this->data);

        $this->assertEquals(123, $proxy->getInt('int'));
        $this->assertEquals([1, 2, 3], $proxy->getInt('array_int', null, true));
        $this->assertNull($proxy->getInt('array_int_notset', null, true));
    }

    /**
     * @covers Fastwf\Core\Utils\ArrayProxy
     */
    public function testGetFloat() {
        $proxy = new ArrayProxy($this->data);

        $this->assertEquals(3.14, $proxy->getFloat('float'));
        $this->assertEquals([1.0, 2.0, 3.0], $proxy->getFloat('array_int', null, true));
        $this->assertNull($proxy->getFloat('array_int_notset', null, true));
    }

    /**
     * @covers Fastwf\Core\Utils\ArrayProxy
     */
    public function testGetBool() {
        $proxy = new ArrayProxy($this->data);

        $this->assertTrue($proxy->getBoolean('bool'));
        $this->assertEquals([true, false, true], $proxy->getBoolean('array_bool', null, true));
        $this->assertNull($proxy->getBoolean('array_bool_notset', null, true));
    }

    /**
     * @covers Fastwf\Core\Utils\ArrayProxy
     */
    public function testRemove() {
        $proxy = new ArrayProxy($this->data);
        $proxy->remove('any');

        $this->assertNull($proxy->get('any'));
    }

}
