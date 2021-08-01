<?php

namespace Fastwf\Tests\Engine;

use Fastwf\Core\Components\RequestHandler;

class ErrorRequestHandler extends RequestHandler {
    
    public function handle($request) {
        throw new \Exception("Error Processing Request");
    }

}
