<?php

namespace Fastwf\Core\Engine\Output;

use Fastwf\Api\Engine\Output\HttpOutputInterface;

/**
 * HttpOutputInterface implementation to use for php as http server (apache mod_php, php fpm, ...).
 */
class ApacheHttpOutput implements HttpOutputInterface {

    /**
     * The stream open in write mode.
     *
     * @var resource
     */
    protected $stream;

    public function __construct($stream) {
        $this->stream = $stream;
    }
    
    /**
     * {@inheritDoc}
     */
    public function sendStatus($status) {
        \http_response_code($status);
    }

    /**
     * {@inheritDoc}
     */
    public function sendHeader($header) {
        \header($header);
    }

    /**
     * {@inheritDoc}
     */
    public function getResponseStream() {
        return $this->stream;
    }

}
