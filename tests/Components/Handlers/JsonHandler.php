<?php

namespace Fastwf\Tests\Components\Handlers;

use Fastwf\Core\Components\RequestHandler;

class JsonHandler extends RequestHandler {

    public function handle($request)
    {
        return $this->json(["message" => "Hello world!!"]);
    }

}