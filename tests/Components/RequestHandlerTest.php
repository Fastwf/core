<?php

namespace Fastwf\Tests\Components;

use PHPUnit\Framework\TestCase;

use Fastwf\Tests\Components\Handlers\HtmlHandler;
use Fastwf\Tests\Components\Handlers\JsonHandler;
use Fastwf\Tests\Components\Handlers\RedirectHandler;

class RequestHandlerTest extends TestCase {

    const CONTENT_TYPE = "Content-Type";

    /**
     * @covers Fastwf\Core\Components\RequestHandler
     */
    public function testHandle() {
        $handler = new NullRequestHandler(null);

        $this->assertNull($handler->handle(null));
    }

    /**
     * @covers Fastwf\Core\Components\RequestHandler
     * @covers Fastwf\Core\Http\Frame\HttpResponse
     * @covers Fastwf\Core\Http\Frame\HttpStreamResponse
     */
    public function testHtml() {
        $handler = new HtmlHandler(null);

        $response = $handler->handle(null);
        $this->assertEquals("text/html; charset=utf-8", $response->headers->get(self::CONTENT_TYPE));
    }

    /**
     * @covers Fastwf\Core\Components\RequestHandler
     * @covers Fastwf\Core\Http\Frame\HttpResponse
     * @covers Fastwf\Core\Http\Frame\HttpStreamResponse
     */
    public function testJson() {
        $handler = new JsonHandler(null);

        $response = $handler->handle(null);
        $this->assertEquals("application/json; charset=utf-8", $response->headers->get(self::CONTENT_TYPE));
    }

    /**
     * @covers Fastwf\Core\Components\RequestHandler
     * @covers Fastwf\Core\Http\Frame\HttpResponse
     * @covers Fastwf\Core\Http\Frame\HttpStreamResponse
     */
    public function testRedirect() {
        $handler = new RedirectHandler(null);

        $response = $handler->handle(null);
        $this->assertTrue($response->headers->has("Location"));
        $this->assertEquals("/", $response->headers->get("Location"));
    }

}
