<?php

namespace Fastwf\Tests\Components;

use Fastwf\Core\Http\Frame\HttpResponse;
use Fastwf\Core\Components\ExceptionHandler;

class SimpleExceptionHandler implements ExceptionHandler {

    public function catch($exception, $request, $response)
    {
        return new HttpResponse(500, [], "Internal error");
    }

}
