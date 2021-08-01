<?php

namespace Fastwf\Tests\Engine;

use Fastwf\Core\Components\RequestHandler;
use Fastwf\Core\Http\Frame\HttpResponse;

class SuccessRequestHandler extends RequestHandler {
    
    public function handle($request) {
        return new HttpResponse(200, [], 'success');
    }

}
