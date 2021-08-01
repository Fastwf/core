<?php

namespace Fastwf\Core\Http;

use Fastwf\Core\Http\HttpException;

/**
 * Http exception that allows to send a 404 response.
 */
class NotFoundException extends HttpException {

    public function __construct($errorMessage) {
        parent::__construct(
            'Not found',
            [
                'status' => 404,
                'errorMessage' => $errorMessage,
            ]
        );
    }

}
