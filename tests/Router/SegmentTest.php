<?php

use PHPUnit\Framework\TestCase;

use Fastwf\Core\Router\Segment;
use Fastwf\Core\Router\Exception\ParameterTypeException;

class SegmentTest extends TestCase {

    /**
     * @covers Fastwf\Core\Router\Segment
     */
    public function testSegmentConstructorBasic() {
        $segment = new Segment('segment');

        $this->assertFalse($segment->isParameter());
    }

    /**
     * @covers Fastwf\Core\Router\Segment
     */
    public function testSegmentConstructorParameter() {
        $segment = new Segment('{segment}');

        $this->assertTrue($segment->isParameter());
        $this->assertEquals("segment", $segment->getName());
    }

    /**
     * @covers Fastwf\Core\Router\Segment
     */
    public function testSegmentBasicMatch() {
        $seq = 'test';
        $segment = new Segment($seq);

        $this->assertTrue($segment->match($seq));
        $this->assertFalse($segment->match('other'));
    }

    /**
     * @covers Fastwf\Core\Router\Segment
     */
    public function testSegmentParameterStringMatch() {
        foreach (['{implicit}', '{string:explicit}'] as $parameter) {
            $segment = new Segment($parameter);

            $this->assertTrue($segment->match('any'));
            $this->assertTrue($segment->match('other'));
        }
    }

    /**
     * @covers Fastwf\Core\Router\Segment
     */
    public function testSegmentParameterIntMatch() {
        $segment = new Segment('{int:name}');

        $this->assertTrue($segment->match('123'));
        $this->assertFalse($segment->match('3.14'));
        $this->assertFalse($segment->match('100 000'));
        $this->assertFalse($segment->match('test'));
    }

    /**
     * @covers Fastwf\Core\Router\Segment
     */
    public function testSegmentParameterFloatMatch() {
        $segment = new Segment('{float:name}');

        $this->assertTrue($segment->match('3.14'));
        $this->assertTrue($segment->match('123'));
        $this->assertFalse($segment->match('100 000.25'));
        $this->assertFalse($segment->match('test'));
    }

    /**
     * @covers Fastwf\Core\Router\Segment
     * @covers Fastwf\Core\Utils\UuidUtil::isUuid
     */
    public function testSegmentParameterUuidMatch() {
        $segment = new Segment('{uuid:name}');

        $this->assertTrue($segment->match('01234567-89ab-cdef-a456-426614174000'));
        $this->assertFalse($segment->match('no uuid'));
    }

    /**
     * @covers Fastwf\Core\Router\Segment
     */
    public function testSegmentInvalidParameter() {
        $this->expectException(ParameterTypeException::class);

        $segment = new Segment('{undefined:name}');
        $segment->match('mustFailed');
    }

    /**
     * @covers Fastwf\Core\Router\Segment
     */
    public function testSegmentParameterExtraction() {
        $test = [
            [ 'specification' => '{name}', 'segment' => 'test', 'parameter' => 'test'],
            [ 'specification' => '{int:name}', 'segment' => '123', 'parameter' => 123],
            [ 'specification' => '{float:name}', 'segment' => '3.14', 'parameter' => 3.14],
        ];

        foreach ($test as $value) {
            $segment = new Segment($value['specification']);
            $segment->match($value['segment']);

            $this->assertEquals($value['parameter'], $segment->getParameter());
        }
    }

}
