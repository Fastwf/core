<?php

namespace Fastwf\Tests\Components;

use PHPUnit\Framework\TestCase;

class RequestHandlerTest extends TestCase {

    /**
     * @covers Fastwf\Core\Components\RequestHandler
     */
    public function testHandle() {
        $handler = new NullRequestHandler(null);

        $this->assertNull($handler->handle(null));
    }

}
