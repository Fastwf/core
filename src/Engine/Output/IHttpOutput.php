<?php

namespace Fastwf\Core\Engine\Output;

/**
 * HttpOutput interface help to define behaviour to write HTTP responses.
 */
interface IHttpOutput {

    /**
     * Set the status of the http response.
     *
     * @param int $status
     */
    public function sendStatus($status);

    /**
     * Send the header line provided in parameter.
     *
     * @param string $header the header with format "HeaderName: HeaderValue"
     */
    public function sendHeader($header);

    /**
     * Access to the response stream to write the HTTP response body.
     *
     * @return resource an output stream where write the body.
     */
    public function getResponseStream();

}
