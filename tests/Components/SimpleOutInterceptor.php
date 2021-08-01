<?php

namespace Fastwf\Tests\Components;

use Fastwf\Core\Components\OutInterceptor;

class SimpleOutInterceptor implements OutInterceptor {

    public function end($context, $request, $response) {
        return $response;
    }

}
