<?php

namespace Fastwf\Core\Http\Frame;

use Fastwf\Core\Http\Frame\HttpStreamResponse;

/**
 * Simplified http response that allows to send the body from a string.
 */
class HttpResponse extends HttpStreamResponse {

    /**
     * The response body to send to the client.
     *
     * @var string
     */
    protected $body;

    public function __construct($status = 200, $headers = [], $body = "") {
        parent::__construct($status, $headers);

        $this->body = $body;
    }

    /**
     * {@inheritDoc}
     */
    protected function write($resource) {
        $this->writeChunk($resource, $this->body);
    }

}
