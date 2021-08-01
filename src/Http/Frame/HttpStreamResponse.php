<?php

namespace Fastwf\Core\Http\Frame;

use Fastwf\Core\Utils\ArrayProxy;
use Fastwf\Core\Exceptions\IOException;
use Fastwf\Core\Exceptions\AttributeError;

/**
 * Http Response that support streaming.
 * 
 * A response class that allows to develop it's stream logic.
 * This class is usefull when it's necessary to handle chunck from an input stream.
 * 
 * @property-read int $status the response status.
 * @property-read Fastwf\Core\Utils\ArrayProxy $headers the http response headers to send to the client.
 */
class HttpStreamResponse {

    /**
     * The http response code
     *
     * @var int
     */
    private $_status;
    /**
     * The http response headers to send to the client.
     *
     * @var Fastwf\Core\Utils\ArrayProxy
     */
    private $_headers;

    /**
     * An iterator af chunk to write in the body of the response.
     *
     * @var Iterator
     */
    protected $outIterator;

    public function __construct($status = 200, $headers = [], $outIterator = null) {
        $this->_status = $status;
        $this->_headers = new ArrayProxy($headers);
        $this->outIterator = $outIterator;
    }

    public function __get($name) {
        switch ($name) {
            case 'status':
            case 'headers':
                $value = $this->{"_$name"};
                break;
            default:
                throw new AttributeError($name);
        }

        return $value;
    }

    /**
     * Allows to send the response to the client.
     *
     * @param \Fastwf\Core\Engine\Output\IHttpOutput $httpOutput the resource where write the response.
     */
    public function send($httpOutput) {
        // Set the http response code
        $httpOutput->sendStatus($this->_status);

        // Write all headers
        $this->sendHeaders($httpOutput);

        // Write the body when it's possible
        $this->write($httpOutput->getResponseStream());
    }

    /**
     * Write all headers from the array proxy containing headers.
     * 
     * @param \Fastwf\Core\Engine\Output\IHttpOutput $httpOutput the resource where write the response.
     */
    protected function sendHeaders($httpOutput) {
        foreach ($this->_headers->keys() as $header) {
            $value = $this->_headers->get($header);

            $httpOutput->sendHeader("$header: $value");
        }
    }

    /**
     * Allows to write data from outIterator.
     *
     * @param resource $resource The resource where the body must be written.
     */
    protected function write($resource) {
        if ($this->outIterator !== null) {
            foreach ($this->outIterator as $chunk) {
                $this->writeChunk($resource, $chunk);
            }
        }
    }

    /**
     * Allows to write data chunk into the resource.
     *
     * @param resource $resource The resource where the chunk must be written.
     * @throws ErrorException when it's impossible to write into the resource.
     */
    protected function writeChunk($resource, $chunk) {
        if (\fwrite($resource, $chunk) === false) {
            throw new IOException("Failed to write into resource");
        }
    }

}
