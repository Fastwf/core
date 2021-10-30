<?php

namespace Fastwf\Core\Components;

use Fastwf\Core\Http\Frame\HttpResponse;

/**
 * Application component that allows to handle the incoming client request.
 */
abstract class RequestHandler {

    private const CONTENT_TYPE = "Content-Type";

    /**
     * The engine context.
     *
     * @var Fastwf\Core\Engine\Context
     */
    protected $context;

    public function __construct($context) {
        $this->context = $context;
    }

    /**
     * Handle the request and return the response object to send to the client.
     *
     * @param Fastwf\Core\Http\Frame\HttpRequest $request the incoming the http request.
     * @return mixed the http reponse.
     */
    public abstract function handle($request);

    // Common response construction

    /**
     * Inject the content type when it's not already provided.
     *
     * @param array $headers the reference to the array containing headers.
     * @param string $contentType the content type to set in the response.
     * @return array the header array parameter by reference.
     */
    private function &setupContentType(&$headers, $contentType) {
        if (!\array_key_exists(self::CONTENT_TYPE, $headers)) {
            $headers[self::CONTENT_TYPE] = $contentType;
        }

        return $headers;
    }

    /**
     * Generate an HttpResponse containing an html document using the body in parameter.
     *
     * @param string $body the body of the html page to send to the client.
     * @param integer $status the http response code (by default 200)
     * @param array $headers the headers to provide to the response (when "Content-Type" is not provided, the response 
     *                       contains "text/html; charset=utf-8").
     * @return Fastwf\Core\Http\Frame\HttpStreamResponse an html document response.
     */
    protected function html($body, $status = 200, $headers = []) {
        return new HttpResponse(
            $status,
            $this->setupContentType($headers, "text/html; charset=utf-8"),
            $body
        );
    }

    /**
     * Generate an HttpResponse containing a json document using the serializable body in parameter.
     *
     * @param mixed $body the body of any type serializable using json_encode function.
     * @param integer $status the http response code (by default 200)
     * @param array $headers the headers to provide to the response (when "Content-Type" is not provided, the response 
     *                       contains "application/json; charset=utf-8").
     * @return Fastwf\Core\Http\Frame\HttpStreamResponse a json document response.
     */
    protected function json($body, $status = 200, $headers = []) {
        return new HttpResponse(
            $status,
            $this->setupContentType($headers, "application/json; charset=utf-8"),
            \json_encode($body)
        );
    }

    /**
     * Generate an HttpResponse that redirect to given url.
     *
     * @param string $url the url where redirect the user
     * @param integer $status the http response code (by default 302).
     * @param array $headers the headers to provide to the response ("Location" will be replaced).
     * @return Fastwf\Core\Http\Frame\HttpStreamResponse a redirection response.
     */
    protected function redirect($url, $status = 302, $headers = []) {
        $headers['Location'] = $url;

        return new HttpResponse(
            $status,
            $headers,
            ""
        );
    }

}
