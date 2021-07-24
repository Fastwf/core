<?php

use Fastwf\Core\Components\RequestHandler;

/**
 * Null request handler for tests.
 */
class NullRequestHandler extends RequestHandler {

    public function handle($request) {
        return null;
    }

}
