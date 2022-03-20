<?php

namespace Fastwf\Tests\Session;

use PHPUnit\Framework\TestCase;
use Fastwf\Core\Session\Session;

class SessionTest extends TestCase
{

    /**
     * @covers Fastwf\Core\Session\Session
     */
    public function testUpdate()
    {
        $data = ['foo' => 'bar'];

        $session = new Session($data);
        $session->update(['foo' => 'bar2']);

        $this->assertEquals('bar2', $data['foo']);
    }

    /**
     * @covers Fastwf\Core\Session\Session
     */
    public function testSet()
    {
        $data = ['foo' => 'bar'];

        $session = new Session($data);

        $session->set('foo', 'bar2');

        $this->assertEquals('bar2', $data['foo']);
    }

    /**
     * @covers Fastwf\Core\Session\Session
     */
    public function testRemove()
    {
        $data = ['foo' => 'bar'];

        $session = new Session($data);
        $session->remove('foo');

        $this->assertFalse(\array_key_exists('foo', $data));
    }

    /**
     * @covers Fastwf\Core\Session\Session
     */
    public function testClear()
    {
        $data = ['foo' => true, 'bar' => false];

        $session = new Session($data);
        $session->clear();

        $this->assertFalse(\array_key_exists('foo', $data));
        $this->assertFalse(\array_key_exists('bar', $data));
    }

    /**
     * @covers Fastwf\Core\Session\Session
     */
    public function testIsModified()
    {
        $data = ['foo' => 'bar'];

        $session = new Session($data);
        $session->get('foo');

        $this->assertFalse($session->isModified());
        $session->set('foo', 'bar2');

        $this->assertTrue($session->isModified());
    }

    /**
     * @covers Fastwf\Core\Session\Session
     */
    public function testApplyModification()
    {
        $data = ['foo' => 'bar'];

        $session = new Session($data);
        $session->set('foo', 'bar2');
        $session->set('injected', true);

        $otherData = ['foo' => 'bar'];
        $session->applyModifications($otherData);

        $this->assertEquals(
            [
                'foo' => 'bar2',
                'injected' => true,
            ],
            $otherData
        );
    }

    /**
     * @covers Fastwf\Core\Session\Session
     */
    public function testApplyModificationDeleted()
    {
        $data = ['foo' => 'bar'];

        $session = new Session($data);
        $session->remove('foo');

        $otherData = ['foo' => 'bar'];
        $session->applyModifications($otherData);

        $this->assertEquals([], $otherData);
    }

    /**
     * @covers Fastwf\Core\Session\Session
     */
    public function testApplyModificationClear()
    {
        $data = ['foo' => 'bar'];

        $session = new Session($data);
        $session->set('foo', 'bar2');
        $session->clear();

        $otherData = ['foo' => 'bar'];
        $session->applyModifications($otherData);

        $this->assertEquals(
            [],
            $otherData
        );
    }

    /**
     * @covers Fastwf\Core\Session\Session
     */
    public function testRefresh()
    {
        $data = ['foo' => 'bar'];

        $session = new Session($data);
        $session->set('bar', 'foo');

        $data = ['foo' => 'bar2'];
        $session->refresh($data);

        $this->assertEquals('bar2', $session->get('foo'));
    }

}
