<?php

namespace Fastwf\Tests\Components;

use Fastwf\Core\Components\InInterceptor;

class SimpleInInterceptor implements InInterceptor {

    public function start($context, $request) {
        return $request;
    }

}
