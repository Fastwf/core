<?php

namespace Fastwf\Tests\Components;

use Fastwf\Core\Components\InPipe;

/**
 * Interface used after the guard control and before calling the request handler.
 * 
 * It can be used to perform request transformation or perform common task before handling the request.
 */
class SimpleInPipe implements InPipe {

    public function in($context, $request) {
        return $request;
    }

}
