<?php

namespace Fastwf\Tests\Engine;

use Fastwf\Core\Router\Route;
use Fastwf\Core\Settings\GuardSettings;
use Fastwf\Core\Settings\RouteSettings;

use Fastwf\Tests\Components\SimpleGuard;
use Fastwf\Tests\Engine\ErrorRequestHandler;

class SimpleSettings implements GuardSettings, RouteSettings {

    public function getGuards($engine) {
        return [new SimpleGuard()];
    }

    public function getRoutes($engine) {
        return [
            new Route([
                'path' => 'fail',
                'methods' => ['GET'],
                'handler' => function ($context) { return new ErrorRequestHandler($context); }
            ]),
            new Route([
                'path' => 'success',
                'methods' => ['GET'],
                'handler' => function ($context) { return new SuccessRequestHandler($context); }
            ])
        ];
    }

}
