<?php

namespace Fastwf\Core\Http;

use Fastwf\Core\Http\Frame\HttpResponse;

class HttpException extends \Exception {

    /**
     * The http response to send to the client.
     *
     * @var Fastwf\Core\Http\Frame\HttpResponse
     */
    private $response;

    public function __construct($message, $args) {
        parent::__construct($message);

        if (\key_exists('response', $args)) {
            $response = $args['response'];
        } else {
            $response = new HttpResponse(
                $args['status'],
                [],
                $args['errorMessage'],
            );
        }

        $this->response = $response;
    }

    /**
     * Get the HttpResponse corresponding to the current exception.
     *
     * @return Fastwf\Core\Http\Frame\HttpResponse
     */
    public function getResponse() {
        return $this->response;
    }

}
