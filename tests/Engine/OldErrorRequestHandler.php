<?php

namespace Fastwf\Tests\Engine;

use Fastwf\Core\Components\RequestHandler;

class OldErrorRequestHandler extends RequestHandler {

    public function handle($request) {
        \trigger_error('Old error style');
    }

}
