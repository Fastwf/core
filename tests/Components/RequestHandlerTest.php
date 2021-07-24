<?php

require_once __DIR__ . '/NullRequestHandler.php';

use PHPUnit\Framework\TestCase;

class RequestHandlerTest extends TestCase {

    /**
     * @covers Fastwf\Core\Components\RequestHandler
     */
    public function testHandle() {
        $handler = new \NullRequestHandler(null);

        $this->assertNull($handler->handle(null));
    }

}
