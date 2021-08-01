<?php

namespace Fastwf\Tests\Engine\Output;

use PHPUnit\Framework\TestCase;

use Fastwf\Core\Engine\Output\ApacheHttpOutput;
use Fastwf\Tests\Engine\Output\ApacheHttpOutputTest;

class ApacheHttpOutputTest extends TestCase {

    /**
     * @covers Fastwf\Core\Engine\Output\ApacheHttpOutput
     */
    public function testStream() {
        $resource = \fopen('php://stdout', 'w');

        $out = new ApacheHttpOutput($resource);

        $this->assertEquals($resource, $out->getResponseStream());

        \fclose($resource);
    }

}
