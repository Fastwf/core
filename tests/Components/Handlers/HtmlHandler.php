<?php

namespace Fastwf\Tests\Components\Handlers;

use Fastwf\Core\Components\RequestHandler;

class HtmlHandler extends RequestHandler {

    public function handle($request)
    {
        return $this->html("Hello world!!");
    }

}