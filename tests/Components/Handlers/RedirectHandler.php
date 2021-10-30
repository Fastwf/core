<?php

namespace Fastwf\Tests\Components\Handlers;

use Fastwf\Core\Components\RequestHandler;

class RedirectHandler extends RequestHandler {

    public function handle($request)
    {
        return $this->redirect("/");
    }

}