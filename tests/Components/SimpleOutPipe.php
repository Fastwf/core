<?php

namespace Fastwf\Tests\Components;

use Fastwf\Core\Components\OutPipe;

class SimpleOutPipe implements OutPipe {

    public function out($context, $request, $response) {
        return $this->response;
    }

}
